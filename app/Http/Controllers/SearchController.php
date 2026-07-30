<?php

namespace App\Http\Controllers;

use App\Models\FreeExam;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SearchController extends Controller
{
    public function query(Request $request): JsonResponse
    {
        $q = trim((string) $request->input('q', ''));

        if (strlen($q) < 2 || ! Schema::hasTable('free_exams')) {
            return response()->json([]);
        }

        $exams = FreeExam::query()
            ->join('schools', 'free_exams.subdivision_id', '=', 'schools.id')
            ->join('courses', 'free_exams.course_id', '=', 'courses.id')
            ->where('free_exams.title', 'LIKE', "%{$q}%")
            ->select(
                'free_exams.title',
                'free_exams.slug as exam_slug',
                'schools.slug as subdivision_slug',
                'schools.schoolname as subdivision_name',
                'courses.slug as course_slug'
            )
            ->orderBy('free_exams.title')
            ->limit(15)
            ->get()
            ->map(fn ($row) => [
                'title' => $row->title,
                'subtitle' => $row->subdivision_name,
                'type' => 'quiz',
                'url' => exam_url($row->subdivision_slug, $row->exam_slug, $row->course_slug),
            ]);

        return response()->json($exams);
    }
}
