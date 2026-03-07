<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        Student::create(['name' => 'Juan dela Cruz', 'email' => 'juan@example.com']);
        Student::create(['name' => 'Maria Santos', 'email' => 'maria@example.com']);
        Student::create(['name' => 'Pedro Reyes', 'email' => 'pedro@example.com']);
    }
}
