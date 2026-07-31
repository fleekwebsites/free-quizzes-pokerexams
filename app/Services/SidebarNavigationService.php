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
        return $this->schoolsWithCoursesAndExams();
    }

    public function schoolsWithCoursesAndExams(): Collection
    {
        if (! Schema::hasTable('schools') || ! Schema::hasTable('courses')) {
            return collect();
        }

        $schools = DB::table('schools')
            ->orderBy('schoolname')
            ->get();

        $schools = $schools
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

        if (! Schema::hasTable('free_exams') || $schools->isEmpty()) {
            return $schools;
        }

        $courseIds = $schools
            ->flatMap(fn (object $school) => $school->courses->pluck('id'))
            ->unique()
            ->values();

        $examsByCourse = DB::table('free_exams')
            ->whereIn('course_id', $courseIds)
            ->orderBy('title')
            ->get(['id', 'course_id', 'slug', 'title', 'question_count'])
            ->groupBy('course_id');

        return $schools->map(function (object $school) use ($examsByCourse) {
            $school->courses = $school->courses->map(function (object $course) use ($examsByCourse) {
                $course->exams = $examsByCourse->get($course->id, collect())->values();

                return $course;
            });

            $school->exam_count = $school->courses->sum(fn (object $course) => $course->exams->count());

            return $school;
        });
    }

    public function totalCourseCount(): int
    {
        return $this->schoolsWithCourses()->sum('course_count');
    }
}
