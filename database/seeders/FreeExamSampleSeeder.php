<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FreeExamSampleSeeder extends Seeder
{
    private const QUESTIONS_PER_EXAM = 12;

    public function run(): void
    {
        $schools = DB::table('schools')->orderBy('schoolname')->get();

        foreach ($schools as $school) {
            $courses = DB::table('courses')
                ->where('school_id', $school->id)
                ->where('is_visible', 1)
                ->orderBy('coursename')
                ->get();

            if ($courses->isEmpty()) {
                $this->seedCourseExams($school, null, null);

                continue;
            }

            foreach ($courses as $course) {
                $sourceExam = $this->findSourceExamForCourse($course->id);
                $this->seedCourseExams($school, $course, $sourceExam);
            }
        }
    }

    private function seedCourseExams(object $school, ?object $course, ?object $sourceExam): void
    {
        $sourceQuestions = $sourceExam
            ? DB::table('actual_questions')
                ->where('exam_id', $sourceExam->id)
                ->orderBy('id')
                ->get()
            : collect();

        for ($quizNum = 1; $quizNum <= 2; $quizNum++) {
            $examSlug = $this->examSlug($school, $course, $sourceExam, $quizNum);
            $examTitle = $this->examTitle($school, $sourceExam, $quizNum);

            DB::table('free_exams')->updateOrInsert(
                ['slug' => $examSlug],
                [
                    'subdivision_id' => $school->id,
                    'title' => $examTitle,
                    'question_count' => 0,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $freeExam = DB::table('free_exams')->where('slug', $examSlug)->first();
            if (! $freeExam) {
                continue;
            }

            DB::table('free_questions')->where('free_exam_id', $freeExam->id)->delete();

            $offset = ($quizNum - 1) * self::QUESTIONS_PER_EXAM;
            $chunk = $sourceQuestions->slice($offset, self::QUESTIONS_PER_EXAM)->values();

            if ($chunk->count() < self::QUESTIONS_PER_EXAM) {
                $needed = self::QUESTIONS_PER_EXAM - $chunk->count();
                $synthetic = collect($this->syntheticQuestions($school, $sourceExam, $quizNum, $chunk->count() + 1));
                $chunk = $chunk->concat($synthetic->take($needed));
            }

            $position = 0;
            foreach ($chunk as $question) {
                $position++;
                $questionSlug = $examSlug.'-q'.$position;

                DB::table('free_questions')->insert(
                    array_merge(
                        ['slug' => $questionSlug],
                        $this->mapQuestion($freeExam->id, $question, $position)
                    )
                );
            }

            DB::table('free_exams')
                ->where('id', $freeExam->id)
                ->update([
                    'question_count' => $position,
                    'updated_at' => now(),
                ]);
        }
    }

    private function findSourceExamForCourse(int $courseId): ?object
    {
        return DB::table('exams')
            ->join('subjects', 'exams.subject_id', '=', 'subjects.id')
            ->where('subjects.course_id', $courseId)
            ->where('exams.is_visible', 1)
            ->select('exams.id', 'exams.examname', 'exams.slug')
            ->orderBy('exams.examname')
            ->first();
    }

    private function examSlug(object $school, ?object $course, ?object $sourceExam, int $quizNum): string
    {
        if ($school->slug === 'teacher-certification-and-licensure-exam-prep') {
            return 'praxis-5001-quiz-'.$quizNum;
        }

        $base = Str::slug($sourceExam?->slug ?? $course?->slug ?? $school->schoolname);
        if (strlen($base) > 45) {
            $base = rtrim(substr($base, 0, 45), '-');
        }

        return $base.'-quiz-'.$quizNum;
    }

    private function examTitle(object $school, ?object $sourceExam, int $quizNum): string
    {
        $name = $sourceExam->examname ?? $school->schoolname;

        return $name.' Free Quiz '.$quizNum;
    }

    private function mapQuestion(int $freeExamId, object $question, int $position): array
    {
        $now = now();

        return [
            'free_exam_id' => $freeExamId,
            'extract' => $question->extract ?: null,
            'question' => $question->question,
            'choiceA' => $question->choiceA ?? '',
            'choiceB' => $question->choiceB ?? '',
            'choiceC' => $question->choiceC ?? '',
            'choiceD' => $question->choiceD ?? '',
            'choiceE' => $this->nullableChoice($question->choiceE ?? null),
            'choiceF' => $this->nullableChoice($question->choiceF ?? null),
            'choiceG' => $this->nullableChoice($question->choiceG ?? null),
            'correctAnswer' => $question->correctAnswer ?? 'A',
            'rationale' => $question->rationale ?? 'Review the question stem and eliminate incorrect options.',
            'image' => $question->image ?: null,
            'qtype' => $question->qtype ?? 'Regular',
            'heading' => $question->heading ?: null,
            'updated_at' => $now,
            'created_at' => $now,
        ];
    }

    private function nullableChoice(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    /**
     * @return array<int, object>
     */
    private function syntheticQuestions(object $school, ?object $sourceExam, int $quizNum, int $startIndex = 1): array
    {
        $label = $sourceExam->examname ?? $school->schoolname;
        $questions = [];

        for ($i = $startIndex; $i < $startIndex + self::QUESTIONS_PER_EXAM; $i++) {
            $isMultipleChoice = $i % 2 === 0;
            $questions[] = (object) [
                'extract' => null,
                'question' => $isMultipleChoice
                    ? "Sample question {$i} for {$label} (Free Quiz {$quizNum}). Select all that apply."
                    : "Sample question {$i} for {$label} (Free Quiz {$quizNum}).",
                'choiceA' => 'First option',
                'choiceB' => 'Second option',
                'choiceC' => 'Third option',
                'choiceD' => 'Fourth option',
                'choiceE' => $isMultipleChoice ? 'Fifth option' : null,
                'choiceF' => null,
                'choiceG' => null,
                'correctAnswer' => $isMultipleChoice ? 'A,C' : 'A',
                'rationale' => $isMultipleChoice
                    ? 'Options A and C are correct because they together address the full scenario.'
                    : 'Option A is correct because it directly answers the question based on the study material.',
                'image' => null,
                'qtype' => $isMultipleChoice ? 'Multiple Choice' : 'Regular',
                'heading' => null,
            ];
        }

        return $questions;
    }
}
