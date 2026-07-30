<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamSubmitController extends Controller
{
    public function submitAnswer(Request $request): JsonResponse
    {
        $request->validate([
            'question_id' => 'required|integer',
            'selected_option' => 'required|string',
            'exam_id' => 'required|integer',
            'index' => 'nullable|integer',
        ]);

        $questionId = (int) $request->input('question_id');
        $selectedOption = $request->input('selected_option');
        $examId = (int) $request->input('exam_id');
        $index = (int) $request->input('index', 0);

        $question = DB::table('free_questions')
            ->where('id', $questionId)
            ->where('free_exam_id', $examId)
            ->first();

        if (! $question) {
            return response()->json(['success' => false, 'message' => 'Question not found']);
        }

        $correctArray = array_values(array_filter(array_map('trim', explode(',', $question->correctAnswer))));
        sort($correctArray);

        $selectedArray = array_values(array_filter(array_map('trim', explode(',', $selectedOption))));
        sort($selectedArray);

        $isCorrect = $correctArray === $selectedArray;

        $answers = session('exam_answers_'.$examId, []);
        $answers[$questionId] = $selectedOption;
        session(['exam_answers_'.$examId => $answers]);
        session(['current_index_'.$examId => $index + 1]);

        $correctCount = 0;
        foreach ($answers as $qId => $ans) {
            $q = DB::table('free_questions')->where('id', $qId)->first();
            if (! $q) {
                continue;
            }

            $dbCorrect = array_values(array_filter(array_map('trim', explode(',', $q->correctAnswer))));
            sort($dbCorrect);

            $dbSelected = array_values(array_filter(array_map('trim', explode(',', $ans))));
            sort($dbSelected);

            if ($dbCorrect === $dbSelected) {
                $correctCount++;
            }
        }

        $answeredCount = count($answers);
        $incorrectCount = $answeredCount - $correctCount;
        $accuracy = $answeredCount > 0 ? round(($correctCount / $answeredCount) * 100) : 0;

        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            'correct_answer' => $question->correctAnswer,
            'rationale' => $question->rationale,
            'stats' => [
                'answered' => $answeredCount,
                'correct' => $correctCount,
                'incorrect' => $incorrectCount,
                'accuracy' => $accuracy,
            ],
        ]);
    }
}
