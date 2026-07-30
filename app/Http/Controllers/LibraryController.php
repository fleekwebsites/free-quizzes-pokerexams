<?php

namespace App\Http\Controllers;

use App\Models\FreeExam;
use App\Models\Subdivision;
use App\Services\SidebarNavigationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class LibraryController extends Controller
{
    public function __construct(
        private SidebarNavigationService $sidebarNavigation,
    ) {}

    public function index(): View
    {
        $subdivisions = $this->subdivisionsWithExams();
        $totalExams = $subdivisions->sum(fn ($s) => (int) ($s->free_exam_count ?? 0));
        $sidebarSchools = $this->sidebarNavigation->schoolsWithCourses();
        $totalCourses = $this->sidebarNavigation->totalCourseCount();

        return view('index', [
            'subdivisions' => $subdivisions,
            'totalExams' => $totalExams,
            'sidebarSchools' => $sidebarSchools,
            'totalCourses' => $totalCourses,
            'canonical' => route('library.index'),
        ]);
    }

    private function subdivisionsWithExams()
    {
        if (! Schema::hasTable('free_exams') || ! Schema::hasTable('schools')) {
            return collect();
        }

        return DB::table('schools')
            ->select('schools.id', 'schools.schoolname', 'schools.slug', DB::raw('COUNT(free_exams.id) as free_exam_count'))
            ->join('free_exams', 'schools.id', '=', 'free_exams.subdivision_id')
            ->groupBy('schools.id', 'schools.schoolname', 'schools.slug')
            ->having('free_exam_count', '>', 0)
            ->orderBy('schools.schoolname')
            ->get();
    }
}
