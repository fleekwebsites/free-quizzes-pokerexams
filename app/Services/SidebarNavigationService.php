<?php

namespace App\Services;

use App\Http\Controllers\SubdivisionController;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SidebarNavigationService
{
    public function schoolsWithCourses(): Collection
    {
        if (! Schema::hasTable('schools') || ! Schema::hasTable('courses')) {
            return collect();
        }

        $schools = DB::table('schools')
            ->orderBy('schoolname')
            ->get();

        return $schools
            ->map(function (object $school) {
                $school->courses = DB::table('courses')
                    ->where('school_id', $school->id)
                    ->where('is_visible', 1)
                    ->orderBy('coursename')
                    ->get(['id', 'coursename', 'slug']);

                $school->course_count = $school->courses->count();
                $school->group_key = SubdivisionController::groupKeyForSlug($school->slug);

                return $school;
            })
            ->filter(fn (object $school) => $school->course_count > 0)
            ->values();
    }

    public function totalCourseCount(): int
    {
        return $this->schoolsWithCourses()->sum('course_count');
    }
}
