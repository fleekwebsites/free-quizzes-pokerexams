<?php

use App\Models\FreeExam;
use App\Models\Subdivision;
use App\Services\SubdivisionExamGroupService;

if (! function_exists('main_url')) {
    /** Link to the main pokerexams.com platform (intentional bridge links only). */
    function main_url(string $path = '/'): string
    {
        $base = config('pokerexams.main_site_url', 'https://pokerexams.com');
        $path = $path === '/' ? '' : (str_starts_with($path, '/') ? $path : '/'.$path);

        return $base.$path;
    }
}

if (! function_exists('free_url')) {
    /** Link within this free-quizzes subdomain. */
    function free_url(string $path = '/'): string
    {
        return url($path);
    }
}

if (! function_exists('course_url')) {
    function course_url(string $subdivisionSlug, string $courseSlug): string
    {
        return route('course.show', [
            'subdivision' => $subdivisionSlug,
            'course' => $courseSlug,
        ]);
    }
}

if (! function_exists('resolve_exam_course_slug')) {
    function resolve_exam_course_slug(string $subdivisionSlug, string $examSlug): ?string
    {
        $subdivision = Subdivision::query()->where('slug', $subdivisionSlug)->first();
        if (! $subdivision) {
            return null;
        }

        $exam = FreeExam::query()
            ->where('subdivision_id', $subdivision->id)
            ->where('slug', $examSlug)
            ->first();

        if (! $exam) {
            return null;
        }

        if ($exam->course_id) {
            return DB::table('courses')->where('id', $exam->course_id)->value('slug');
        }

        return app(SubdivisionExamGroupService::class)
            ->courseForFreeExam($subdivision->id, $exam->title)
            ?->slug;
    }
}

if (! function_exists('exam_url')) {
    function exam_url(string $subdivisionSlug, string $examSlug, ?string $courseSlug = null): string
    {
        $courseSlug ??= resolve_exam_course_slug($subdivisionSlug, $examSlug);

        if ($courseSlug === null) {
            $exam = FreeExam::query()->where('slug', $examSlug)->first();
            if ($exam) {
                $subdivision = Subdivision::query()->find($exam->subdivision_id);
                $course = app(SubdivisionExamGroupService::class)
                    ->courseForFreeExam($exam->subdivision_id, $exam->title);

                if ($subdivision && $course) {
                    $subdivisionSlug = $subdivision->slug;
                    $courseSlug = $course->slug;
                }
            }
        }

        if ($courseSlug === null) {
            throw new \InvalidArgumentException("Unable to resolve course for exam [{$examSlug}].");
        }

        return route('exam.show', [
            'subdivision' => $subdivisionSlug,
            'course' => $courseSlug,
            'exam' => $examSlug,
        ]);
    }
}

if (! function_exists('subdivision_url_for_course')) {
    /** Map a main-site course slug to this subdomain's course page. */
    function subdivision_url_for_course(string $courseSlug): string
    {
        $course = \Illuminate\Support\Facades\DB::table('courses')
            ->join('schools', 'courses.school_id', '=', 'schools.id')
            ->where('courses.slug', $courseSlug)
            ->where('courses.is_visible', 1)
            ->select('schools.slug as subdivision_slug', 'courses.slug')
            ->first();

        if ($course) {
            return course_url($course->subdivision_slug, $course->slug);
        }

        $map = config('pokerexams.course_subdivision_map', []);
        $subdivisionSlug = $map[$courseSlug] ?? $courseSlug;

        return route('subdivision.show', $subdivisionSlug);
    }
}
