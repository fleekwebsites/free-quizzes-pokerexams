<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DevFreeContentSeeder extends Seeder
{
    private const QUESTIONS_PER_EXAM = 12;

    public function run(): void
    {
        DB::table('free_questions')->delete();
        DB::table('free_exams')->delete();

        $schools = DB::table('schools')->orderBy('id')->get();

        foreach ($schools as $school) {
            $courses = DB::table('courses')
                ->where('school_id', $school->id)
                ->where('is_visible', 1)
                ->orderBy('coursename')
                ->get();

            foreach ($courses as $course) {
                $subjectId = DB::table('subjects')
                    ->where('course_id', $course->id)
                    ->orderBy('id')
                    ->value('id');

                for ($quizNum = 1; $quizNum <= 2; $quizNum++) {
                    $examSlug = $this->examSlug($school, $course, $quizNum);
                    $examTitle = $course->coursename.' Free Quiz '.$quizNum;

                    $freeExamId = DB::table('free_exams')->insertGetId([
                        'subdivision_id' => $school->id,
                        'course_id' => $course->id,
                        'subject_id' => $subjectId,
                        'slug' => $examSlug,
                        'title' => $examTitle,
                        'question_count' => self::QUESTIONS_PER_EXAM,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    for ($position = 1; $position <= self::QUESTIONS_PER_EXAM; $position++) {
                        $isMultipleChoice = $position % 2 === 0;
                        $qtype = $isMultipleChoice ? 'Multiple Choice' : 'Regular';

                        DB::table('free_questions')->insert([
                            'free_exam_id' => $freeExamId,
                            'slug' => $examSlug.'-q'.$position,
                            'extract' => null,
                            'question' => $isMultipleChoice
                                ? "Sample question {$position} for {$examTitle}. Select all that apply."
                                : "Sample question {$position} for {$examTitle}.",
                            'choiceA' => 'First option',
                            'choiceB' => 'Second option',
                            'choiceC' => 'Third option',
                            'choiceD' => 'Fourth option',
                            'choiceE' => $isMultipleChoice ? 'Fifth option' : null,
                            'choiceF' => null,
                            'choiceG' => null,
                            'correctAnswer' => $isMultipleChoice ? 'A,C' : 'A',
                            'rationale' => $isMultipleChoice
                                ? 'Options A and C are correct because they together address the full scenario described in the question stem.'
                                : 'Option A is correct because it best matches the scenario described in the question stem.',
                            'image' => null,
                            'qtype' => $qtype,
                            'heading' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }

    private function examSlug(object $school, object $course, int $quizNum): string
    {
        if ($school->slug === 'teacher-certification-and-licensure-exam-prep') {
            return 'praxis-5001-quiz-'.$quizNum;
        }

        $base = Str::slug($course->slug);
        if (strlen($base) > 45) {
            $base = rtrim(substr($base, 0, 45), '-');
        }

        return $base.'-quiz-'.$quizNum;
    }
}
