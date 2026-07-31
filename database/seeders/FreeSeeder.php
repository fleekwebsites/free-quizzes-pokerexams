<?php

namespace Database\Seeders;

use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use RuntimeException;

class FreeSeeder extends Seeder
{
    private const DATA_DIR = 'database/seeders/data/freedata';
    private const QUESTION_CHUNK_SIZE = 200;

    private array $courseSlugById = [];
    private array $schoolCache = [];
    private array $courseCache = [];
    private array $subjectCache = [];
    private array $examCache = [];
    private array $examQuestionPositions = [];
    private array $examQuestionCounts = [];
    private array $usedSlugs = [];
    private array $questionChunk = [];

    public function run(): void
    {
        $directory = base_path(self::DATA_DIR);

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
            $this->command?->warn('Created '.self::DATA_DIR.'. Drop your CSV file(s) there and run again.');
            return;
        }

        $files = collect(File::glob($directory.'/*.csv'))->sort()->values();

        if ($files->isEmpty()) {
            $this->command?->error('No CSV files found in '.self::DATA_DIR);
            return;
        }

        DB::disableQueryLog();
        $this->configureBulkImportSession();

        $this->resetImportState();
        
        // Disable FK checks for faster truncation and insertion
        Schema::disableForeignKeyConstraints();
        $this->truncateAll();

        $totals = [
            'schools' => 0,
            'courses' => 0,
            'subjects' => 0,
            'free_exams' => 0,
            'free_questions' => 0,
        ];

        $now = now();

        foreach ($files as $path) {
            $this->command?->info('Importing: '.basename($path));

            DB::transaction(function () use ($path, $now, &$totals): void {
                $stats = $this->importFile($path, $now);
                $this->flushQuestionChunk();

                foreach ($stats as $key => $count) {
                    $totals[$key] += $count;
                }
            });
        }

        $this->syncAllExamQuestionCounts();
        $this->restoreBulkImportSession();
        Schema::enableForeignKeyConstraints();

        $this->command?->newLine();
        $this->command?->info('Free data import complete.');
        $this->command?->table(
            ['Metric', 'Count'],
            collect($totals)->map(fn (int $count, string $label) => [$label, $count])->values()->all()
        );
    }

    private function resetImportState(): void
    {
        $this->schoolCache = [];
        $this->courseCache = [];
        $this->subjectCache = [];
        $this->examCache = [];
        $this->examQuestionPositions = [];
        $this->examQuestionCounts = [];
        $this->usedSlugs = [];
        $this->questionChunk = [];
    }

    private function configureBulkImportSession(): void
    {
        try {
            DB::statement('SET GLOBAL max_allowed_packet = 67108864');
        } catch (QueryException) {
            // Global setting requires elevated privileges; adaptive chunk inserts handle this.
        }

        DB::statement('SET SESSION unique_checks = 0');
        DB::statement('SET SESSION foreign_key_checks = 0');
    }

    private function restoreBulkImportSession(): void
    {
        DB::statement('SET SESSION unique_checks = 1');
        DB::statement('SET SESSION foreign_key_checks = 1');
    }

    private function importFile(string $path, Carbon $now): array
    {
        $handle = fopen($path, 'r');
        if ($handle === false) {
            throw new RuntimeException("Could not open CSV: {$path}");
        }

        $header = fgetcsv($handle);
        if ($header === false) {
            fclose($handle);
            throw new RuntimeException('CSV file is empty: '.basename($path));
        }

        $columns = $this->normalizeHeader($header);
        $stats = [
            'schools' => 0,
            'courses' => 0,
            'subjects' => 0,
            'free_exams' => 0,
            'free_questions' => 0,
        ];

        while (($row = fgetcsv($handle)) !== false) {
            if ($this->isBlankRow($row)) {
                continue;
            }

            $data = $this->rowToAssoc($columns, $row);
            $this->assertRequiredFields($data);

            $schoolId = $this->resolveSchoolId($data['school'], $stats, $now);
            $courseId = $this->resolveCourseId($schoolId, $data['course'], $stats, $now);
            $subjectId = $this->resolveSubjectId($courseId, $data['subject'], $stats, $now);
            $exam = $this->resolveFreeExam(
                $schoolId,
                $courseId,
                $subjectId,
                $data['free_exam_names'],
                $stats,
                $now
            );

            // Removed Duplicate Check here entirely.
            
            $examKey = (string) $exam['id'];
            $this->examQuestionPositions[$examKey] = ($this->examQuestionPositions[$examKey] ?? 0) + 1;
            $position = $this->examQuestionPositions[$examKey];
            $questionSlug = $this->reserveUniqueSlug('free_questions', $exam['slug'].'-q'.$position);
            
            $this->questionChunk[] = [
                'free_exam_id' => $exam['id'],
                'slug' => $questionSlug,
                'extract' => $this->nullable($data['extract'] ?? null),
                'question' => $data['question'],
                'choiceA' => $this->nullable($data['choiceA'] ?? null),
                'choiceB' => $this->nullable($data['choiceB'] ?? null),
                'choiceC' => $this->nullable($data['choiceC'] ?? null),
                'choiceD' => $this->nullable($data['choiceD'] ?? null),
                'choiceE' => $this->nullable($data['choiceE'] ?? null),
                'choiceF' => $this->nullable($data['choiceF'] ?? null),
                'choiceG' => $this->nullable($data['choiceG'] ?? null),
                'correctAnswer' => $data['correctAnswer'],
                'rationale' => $this->nullable($data['rationale'] ?? null) ?? '',
                'image' => normalize_question_image($this->nullable($data['image'] ?? null)),
                'qtype' => $data['qtype'],
                'heading' => $this->nullable($data['heading'] ?? null),
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $this->examQuestionCounts[$exam['id']] = ($this->examQuestionCounts[$exam['id']] ?? 0) + 1;
            $stats['free_questions']++;

            if (count($this->questionChunk) >= self::QUESTION_CHUNK_SIZE) {
                $this->flushQuestionChunk();
            }
        }

        fclose($handle);

        return $stats;
    }

    private function flushQuestionChunk(): void
    {
        if ($this->questionChunk === []) {
            return;
        }

        $chunk = $this->questionChunk;
        $this->questionChunk = [];
        $this->insertQuestionChunk($chunk);
    }

    private function insertQuestionChunk(array $chunk): void
    {
        try {
            DB::table('free_questions')->insert($chunk);
        } catch (QueryException $exception) {
            if (count($chunk) <= 1) {
                throw $exception;
            }

            $mid = (int) ceil(count($chunk) / 2);
            $this->insertQuestionChunk(array_slice($chunk, 0, $mid));
            $this->insertQuestionChunk(array_slice($chunk, $mid));
        }
    }

    private function syncAllExamQuestionCounts(): void
    {
        if ($this->examQuestionCounts === []) {
            return;
        }

        $examIds = array_keys($this->examQuestionCounts);
        $now = now();

        foreach (array_chunk($examIds, 500) as $chunkIds) {
            $cases = [];
            $bindings = [];

            foreach ($chunkIds as $examId) {
                $count = (int) ($this->examQuestionCounts[$examId] ?? 0);
                $cases[] = "WHEN id = ? THEN ?";
                $bindings[] = $examId;
                $bindings[] = $count;
            }
            
            if (count($cases) > 0) {
                $bindings[] = $now;
                $bindings = array_merge($bindings, $chunkIds);
                
                $caseSql = implode(' ', $cases);
                $placeholders = implode(',', array_fill(0, count($chunkIds), '?'));
                
                DB::update("
                    UPDATE free_exams 
                    SET question_count = CASE {$caseSql} END,
                        updated_at = ?
                    WHERE id IN ({$placeholders})
                ", $bindings);
            }
        }
    }

    private function truncateAll(): void
    {
        DB::table('free_questions')->truncate();
        DB::table('free_exams')->truncate();
        DB::table('subjects')->truncate();
        DB::table('courses')->truncate();
        DB::table('schools')->truncate();
    }

    private function normalizeHeader(array $header): array
    {
        $aliases = [
            'free_exam_title' => 'free_exam_names',
            'free_exam_name' => 'free_exam_names',
            'exam_title' => 'free_exam_names',
            'schoolname' => 'school',
            'coursename' => 'course',
            'subjectname' => 'subject',
            'choicea' => 'choiceA',
            'choiceb' => 'choiceB',
            'choicec' => 'choiceC',
            'choiced' => 'choiceD',
            'choicee' => 'choiceE',
            'choicef' => 'choiceF',
            'choiceg' => 'choiceG',
            'correctanswer' => 'correctAnswer',
        ];

        $columns = [];
        foreach ($header as $index => $name) {
            $key = Str::of((string) $name)
                ->trim()
                ->lower()
                ->replace(' ', '_')
                ->toString();

            $key = $aliases[$key] ?? $key;
            $columns[$key] = $index;
        }

        foreach (['school', 'course', 'free_exam_names', 'question', 'correctAnswer', 'qtype'] as $required) {
            if (! array_key_exists($required, $columns)) {
                throw new RuntimeException("CSV is missing required column: {$required}");
            }
        }

        return $columns;
    }

    private function rowToAssoc(array $columns, array $row): array
    {
        $data = [];
        foreach ($columns as $name => $index) {
            $data[$name] = trim((string) ($row[$index] ?? ''));
        }
        return $data;
    }

    private function isBlankRow(array $row): bool
    {
        foreach ($row as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }
        return true;
    }

    private function assertRequiredFields(array $data): void
    {
        foreach (['school', 'course', 'free_exam_names', 'question', 'correctAnswer', 'qtype'] as $field) {
            if ($data[$field] === '') {
                throw new RuntimeException("CSV row is missing required value for [{$field}].");
            }
        }
    }

    private function resolveSchoolId(string $schoolName, array &$stats, Carbon $now): int
    {
        if (isset($this->schoolCache[$schoolName])) {
            return $this->schoolCache[$schoolName];
        }

        $slug = $this->reserveUniqueSlug('schools', Str::slug($schoolName) ?: 'school');

        $id = (int) DB::table('schools')->insertGetId([
            'schoolname' => $schoolName,
            'slug' => $slug,
            'regdate' => $now,
        ]);

        $this->schoolCache[$schoolName] = $id;
        $stats['schools']++;

        return $id;
    }

    private function resolveCourseId(int $schoolId, string $courseName, array &$stats, Carbon $now): int
    {
        $cacheKey = $schoolId.'|'.$courseName;
        if (isset($this->courseCache[$cacheKey])) {
            return $this->courseCache[$cacheKey];
        }

        $slug = Str::slug($courseName) ?: 'course';

        $id = (int) DB::table('courses')->insertGetId([
            'coursename' => $courseName,
            'slug' => $slug,
            'school_id' => $schoolId,
            'is_visible' => 1,
            'regdate' => $now,
        ]);

        $this->courseCache[$cacheKey] = $id;
        $this->courseSlugById[$id] = $slug;
        $stats['courses']++;

        return $id;
    }

    private function resolveSubjectId(int $courseId, string $subjectName, array &$stats, Carbon $now): ?int
    {
        if ($subjectName === '') {
            return null;
        }

        $cacheKey = $courseId.'|'.$subjectName;
        if (array_key_exists($cacheKey, $this->subjectCache)) {
            return $this->subjectCache[$cacheKey];
        }

        $id = (int) DB::table('subjects')->insertGetId([
            'subjectname' => $subjectName,
            'course_id' => $courseId,
            'regdate' => $now,
        ]);

        $this->subjectCache[$cacheKey] = $id;
        $stats['subjects']++;

        return $id;
    }

    private function resolveFreeExam(
        int $schoolId,
        int $courseId,
        ?int $subjectId,
        string $examTitle,
        array &$stats,
        Carbon $now
    ): array {
        $cacheKey = $schoolId.'|'.$courseId.'|'.$examTitle;
        if (isset($this->examCache[$cacheKey])) {
            return $this->examCache[$cacheKey];
        }

        $courseSlug = $this->courseSlugById[$courseId] ?? $this->loadCourseSlug($courseId);
        $slug = $this->reserveUniqueSlug('free_exams', $this->buildExamSlug($courseSlug, $examTitle));

        $id = (int) DB::table('free_exams')->insertGetId([
            'subdivision_id' => $schoolId,
            'course_id' => $courseId,
            'subject_id' => $subjectId,
            'slug' => $slug,
            'title' => $examTitle,
            'question_count' => 0,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $exam = ['id' => $id, 'slug' => $slug];
        $this->examCache[$cacheKey] = $exam;
        $stats['free_exams']++;

        return $exam;
    }

    private function loadCourseSlug(int $courseId): string
    {
        $slug = (string) DB::table('courses')->where('id', $courseId)->value('slug');
        $this->courseSlugById[$courseId] = $slug;

        return $slug;
    }

    private function buildExamSlug(string $courseSlug, string $examTitle): string
    {
        $titleSlug = Str::slug($examTitle) ?: 'quiz';
        $candidate = Str::slug($courseSlug.'-'.$titleSlug);

        if (strlen($candidate) > 60) {
            $candidate = rtrim(substr($candidate, 0, 60), '-');
        }

        return $candidate;
    }

    private function reserveUniqueSlug(string $table, string $base): string
    {
        $slug = $base;
        $suffix = 2;

        while (isset($this->usedSlugs[$table][$slug])) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        $this->usedSlugs[$table][$slug] = true;

        return $slug;
    }

    private function nullable(?string $value): ?string
    {
        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }
}