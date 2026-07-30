<?php

namespace App\Services;

use App\Models\FreeExam;
use App\Models\Subdivision;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SubdivisionExamGroupService
{
    /**
     * @return array<string, Collection<int, FreeExam>>
     */
    public function groupFreeExamsBySubject(Subdivision $subdivision, Collection $freeExams): array
    {
        $groups = [];

        foreach ($freeExams as $freeExam) {
            $subjectName = $this->subjectNameForFreeExam($freeExam);
            $groups[$subjectName][] = $freeExam;
        }

        ksort($groups);

        return array_map(fn (array $exams) => collect($exams), $groups);
    }

    private function subjectNameForFreeExam(FreeExam $freeExam): string
    {
        if ($freeExam->subject_id) {
            $name = DB::table('subjects')->where('id', $freeExam->subject_id)->value('subjectname');
            if ($name) {
                return $name;
            }
        }

        return 'General';
    }

    public function primaryCourseName(int $schoolId): ?string
    {
        return DB::table('courses')
            ->where('school_id', $schoolId)
            ->where('is_visible', 1)
            ->orderBy('coursename')
            ->value('coursename');
    }

    public function courseNameForFreeExam(int $schoolId, string $freeExamTitle): ?string
    {
        $course = $this->courseForFreeExam($schoolId, $freeExamTitle);

        return $course?->coursename ?? $this->primaryCourseName($schoolId);
    }

    public function courseForFreeExam(int $schoolId, string $freeExamTitle): ?object
    {
        $freeExam = FreeExam::query()
            ->where('subdivision_id', $schoolId)
            ->where('title', $freeExamTitle)
            ->first();

        if (! $freeExam?->course_id) {
            return null;
        }

        return DB::table('courses')
            ->where('id', $freeExam->course_id)
            ->select('id', 'coursename', 'slug')
            ->first();
    }

    public function courseForFreeExamModel(FreeExam $freeExam): ?object
    {
        if (! $freeExam->course_id) {
            return null;
        }

        return DB::table('courses')
            ->where('id', $freeExam->course_id)
            ->select('id', 'coursename', 'slug')
            ->first();
    }

    /**
     * @return Collection<int, FreeExam>
     */
    public function freeExamsForCourse(Subdivision $subdivision, int $courseId): Collection
    {
        return FreeExam::query()
            ->where('subdivision_id', $subdivision->id)
            ->where('course_id', $courseId)
            ->orderBy('title')
            ->get();
    }
}
