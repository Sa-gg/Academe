<?php

namespace App\Repositories;

use App\Models\Student;
use App\Repositories\Interfaces\StudentRepositoryInterface;

class StudentRepository implements StudentRepositoryInterface
{
    public function all()
    {
        return Student::all();
    }

    public function find($id)
    {
        return Student::findOrFail($id);
    }

    public function create(array $data)
    {
        return Student::create($data);
    }

    public function update($id, array $data)
    {
        $student = Student::find($id);
        if (!$student) return null;
        $student->update($data);
        return $student;
    }

    public function delete($id)
    {
        $student = Student::find($id);
        if (!$student) return false;
        // Cascade: remove enrollments first
        $student->enrollments()->delete();
        $student->delete();
        return true;
    }
}
