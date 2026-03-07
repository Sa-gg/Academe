<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            ['title' => 'Introduction to Programming', 'description' => 'Learn the fundamentals of programming.'],
            ['title' => 'Web Development', 'description' => 'Build modern web applications.'],
            ['title' => 'Database Systems', 'description' => 'Understand database design and management.'],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
