<?php

namespace App\Http\Controllers;

use App\Models\FreeExam;
use App\Models\Subdivision;
use App\Services\SubdivisionExamGroupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function __construct(
        private SubdivisionExamGroupService $examGroupService,
    ) {}

    public function show(Subdivision $subdivision, string $course): View|RedirectResponse
    {
        $courseRow = DB::table('courses')
            ->where('school_id', $subdivision->id)
            ->where('slug', $course)
            ->where('is_visible', 1)
            ->first();

        if ($courseRow) {
            $exams = $this->examGroupService->freeExamsForCourse($subdivision, (int) $courseRow->id);
            $examGroups = $this->examGroupService->groupFreeExamsBySubject($subdivision, $exams);

            return view('subject', [
                'subdivision' => $subdivision,
                'course' => $courseRow,
                'exams' => $exams,
                'examGroups' => $examGroups,
                'canonical' => route('course.show', [
                    'subdivision' => $subdivision,
                    'course' => $courseRow->slug,
                ]),
                'activeGroup' => SubdivisionController::groupKeyForSlug($subdivision->slug),
                'activeSubdivisionSlug' => $subdivision->slug,
                'activeCourseSlug' => $courseRow->slug,
            ]);
        }

        $legacyExam = FreeExam::query()
            ->where('subdivision_id', $subdivision->id)
            ->where('slug', $course)
            ->first();

        if ($legacyExam) {
            $examCourse = $this->examGroupService->courseForFreeExam($subdivision->id, $legacyExam->title);
            abort_if(! $examCourse, 404);

            return redirect()->route('exam.show', [
                'subdivision' => $subdivision->slug,
                'course' => $examCourse->slug,
                'exam' => $legacyExam->slug,
            ], 301);
        }

        abort(404);
    }
}
