<?php

namespace App\Http\Controllers;

use App\Models\FreeExam;
use App\Models\Subdivision;
use App\Services\SubdivisionExamGroupService;
use Illuminate\View\View;

class SubdivisionController extends Controller
{
    public function __construct(
        private SubdivisionExamGroupService $examGroupService,
    ) {}

    public function show(Subdivision $subdivision): View
    {
        $exams = FreeExam::query()
            ->where('subdivision_id', $subdivision->id)
            ->orderBy('title')
            ->get();

        $examGroups = $this->examGroupService->groupFreeExamsBySubject($subdivision, $exams);

        return view('subject', [
            'subdivision' => $subdivision,
            'exams' => $exams,
            'examGroups' => $examGroups,
            'canonical' => route('subdivision.show', $subdivision),
            'activeGroup' => self::groupKeyForSlug($subdivision->slug),
            'activeSubdivisionSlug' => $subdivision->slug,
        ]);
    }

    public static function groupKeyForSlug(string $slug): ?string
    {
        return match (true) {
            str_contains($slug, 'business-and-finance') => 'business-finance',
            str_contains($slug, 'college-admissions') => 'college',
            str_contains($slug, 'high-school') => 'high-school',
            str_contains($slug, 'insurance') => 'insurance',
            str_contains($slug, 'it-and-tech') => 'it-certification',
            str_contains($slug, 'healthcare') => 'medical-allied-health',
            str_contains($slug, 'nursing') => 'nursing',
            str_contains($slug, 'cognitive') => 'others',
            str_contains($slug, 'trades') => 'professional-licensing',
            str_contains($slug, 'real-estate') => 'real-estate',
            str_contains($slug, 'teacher') => 'teaching-certification',
            default => null,
        };
    }
}
