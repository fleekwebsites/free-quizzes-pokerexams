<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FreeExamDemoSeeder extends Seeder
{
    public function run(): void
    {
        $subdivision = DB::table('schools')
            ->where('slug', 'teacher-certification-and-licensure-exam-prep')
            ->first();

        if (! $subdivision) {
            return;
        }

        DB::table('free_exams')->updateOrInsert(
            ['slug' => 'praxis-5001-quiz-1'],
            [
                'subdivision_id' => $subdivision->id,
                'title' => 'Praxis 5001 Quiz 1',
                'question_count' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $exam = DB::table('free_exams')->where('slug', 'praxis-5001-quiz-1')->first();

        DB::table('free_exams')->updateOrInsert(
            ['slug' => 'praxis-5001-quiz-2'],
            [
                'subdivision_id' => $subdivision->id,
                'title' => 'Praxis 5001 Quiz 2',
                'question_count' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        if (! $exam) {
            return;
        }

        for ($i = 1; $i <= 15; $i++) {
            $isMultipleChoice = $i % 2 === 0;
            DB::table('free_questions')->updateOrInsert(
                ['slug' => "praxis-5001-q{$i}"],
                [
                    'free_exam_id' => $exam->id,
                    'extract' => null,
                    'question' => $isMultipleChoice
                        ? "Sample Praxis question {$i} for free quiz demo. Select all that apply."
                        : "Sample Praxis question {$i} for free quiz demo.",
                    'choiceA' => 'Option A',
                    'choiceB' => 'Option B',
                    'choiceC' => 'Option C',
                    'choiceD' => 'Option D',
                    'choiceE' => $isMultipleChoice ? 'Option E' : null,
                    'choiceF' => null,
                    'choiceG' => null,
                    'correctAnswer' => $isMultipleChoice ? 'A,C' : 'A',
                    'rationale' => $isMultipleChoice
                        ? 'Options A and C are correct because they together address the full scenario.'
                        : 'The correct answer is A because it best matches the question stem.',
                    'image' => null,
                    'qtype' => $isMultipleChoice ? 'Multiple Choice' : 'Regular',
                    'heading' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        DB::table('free_exams')
            ->where('id', $exam->id)
            ->update(['question_count' => 15]);
    }
}
