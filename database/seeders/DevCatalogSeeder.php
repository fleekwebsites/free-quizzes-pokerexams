<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DevCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = require database_path('seeders/data/dev_catalog.php');

        DB::table('subjects')->delete();
        DB::table('courses')->delete();
        DB::table('schools')->delete();

        foreach ($catalog as $schoolData) {
            $schoolId = DB::table('schools')->insertGetId([
                'schoolname' => $schoolData['schoolname'],
                'slug' => $schoolData['slug'],
                'regdate' => now(),
            ]);

            foreach ($schoolData['courses'] as $courseData) {
                $courseId = DB::table('courses')->insertGetId([
                    'coursename' => $courseData['coursename'],
                    'slug' => $courseData['slug'],
                    'school_id' => $schoolId,
                    'is_visible' => 1,
                    'regdate' => now(),
                ]);

                foreach ($this->subjectNamesForCourse($courseData['slug']) as $subjectName) {
                    DB::table('subjects')->insert([
                        'subjectname' => $subjectName,
                        'course_id' => $courseId,
                        'regdate' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * @return list<string>
     */
    private function subjectNamesForCourse(string $courseSlug): array
    {
        $pool = ['Core Concepts', 'Applied Practice', 'Exam Strategies', 'Key Topics'];
        $count = (crc32($courseSlug) % 2) + 1;

        return collect($pool)
            ->sortBy(fn (string $name) => crc32($courseSlug.$name))
            ->take($count)
            ->values()
            ->all();
    }
}
