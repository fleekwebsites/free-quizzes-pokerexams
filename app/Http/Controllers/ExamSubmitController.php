<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExamSubmitController extends Controller
{
    private function normalizeAnswerList(string $value): array
    {
        $parts = array_values(array_filter(array_map('trim', explode(',', $value))));

        sort($parts);

        return $parts;
    }

    private function answersMatch(array $correct, array $selected): bool
    {
        return $correct === $selected;
    }

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

        $correctArray = $this->normalizeAnswerList($question->correctAnswer);
        $selectedArray = $this->normalizeAnswerList($selectedOption);

        $isCorrect = $this->answersMatch($correctArray, $selectedArray);

        $answers = session('exam_answers_'.$examId, []);
        $alreadyAnswered = array_key_exists($questionId, $answers);
        $answers[$questionId] = $selectedOption;
        session(['exam_answers_'.$examId => $answers]);
        session(['current_index_'.$examId => $index + 1]);

        $correctCount = (int) session('exam_correct_count_'.$examId, 0);
        if (! $alreadyAnswered && $isCorrect) {
            $correctCount++;
            session(['exam_correct_count_'.$examId => $correctCount]);
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
