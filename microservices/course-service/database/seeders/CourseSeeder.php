<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        Course::create([
            'title'       => 'Introduction to Programming',
            'description' => 'Learn the basics of programming concepts and logic.',
        ]);
        Course::create([
            'title'       => 'Web Development',
            'description' => 'Build modern web applications using current technologies.',
        ]);
        Course::create([
            'title'       => 'Database Systems',
            'description' => 'Understand database design, SQL, and data management.',
        ]);
    }
}
