<?php

namespace App\Http\Controllers;

use App\Models\FreeExam;
use App\Models\Subdivision;
use App\Services\FreeExamSidebarService;
use App\Services\SubdivisionExamGroupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ExamController extends Controller
{
    public function __construct(
        private FreeExamSidebarService $sidebarService,
        private SubdivisionExamGroupService $examGroupService,
    ) {}

    public function show(Subdivision $subdivision, string $course, string $exam): View|RedirectResponse
    {
        $courseRow = DB::table('courses')
            ->where('school_id', $subdivision->id)
            ->where('slug', $course)
            ->where('is_visible', 1)
            ->first();

        abort_if(! $courseRow, 404);

        $freeExam = FreeExam::query()
            ->where('subdivision_id', $subdivision->id)
            ->where(function ($query) use ($exam) {
                $query->where('new_slug', $exam)
                    ->orWhere('slug', $exam);
            })
            ->firstOrFail();
        if ($freeExam->new_slug && $exam !== $freeExam->new_slug) {
            return redirect()->route('exam.show', [
                'subdivision' => $subdivision,
                'course' => $course,
                'exam' => $freeExam->new_slug,
            ], 301);
        }

        abort_if((int) $freeExam->course_id !== (int) $courseRow->id, 404);

        $examCourse = $this->examGroupService->courseForFreeExamModel($freeExam);
        abort_if(! $examCourse, 404);

        $questions = $freeExam->questions()->orderBy('id')->get();

        if ($freeExam->question_count !== $questions->count()) {
            $freeExam->update(['question_count' => $questions->count()]);
        }

        $similarExam = $this->sidebarService->similarExam($freeExam);
        $otherExams = $this->sidebarService->otherExams($freeExam);
        $currentCourse = $examCourse;
        $currentCourseName = $currentCourse->coursename;
        $courseName = $currentCourseName;

        return view('quiz', [
            'subdivision' => $subdivision,
            'exam' => $freeExam,
            'questions' => $questions,
            'totalExamQuestions' => $questions->count(),
            'currentIndex' => 0,
            'similarExam' => $similarExam,
            'otherExams' => $otherExams,
            'currentCourseName' => $currentCourseName,
            'currentCourse' => $currentCourse,
            'courseName' => $courseName,
            'canonical' => route('exam.show', [
                'subdivision' => $subdivision,
                'course' => $currentCourse->slug,
                'exam' => $freeExam->new_slug ?? $freeExam->slug,
            ]),
            'activeGroup' => SubdivisionController::groupKeyForSlug($subdivision->slug),
            'activeSubdivisionSlug' => $subdivision->slug,
            'activeCourseSlug' => $currentCourse->slug,
            'activeExam' => $freeExam->new_slug ?? $freeExam->slug,
        ]);
    }
}
