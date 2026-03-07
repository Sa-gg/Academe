<?php

namespace App\Repositories;

use App\Models\Enrollment;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;

class EnrollmentRepository implements EnrollmentRepositoryInterface
{
    public function all()
    {
        return Enrollment::all();
    }

    public function find($id)
    {
        return Enrollment::find($id);
    }

    public function findWithDetails($id)
    {
        return Enrollment::findOrFail($id);
    }

    public function create(array $data)
    {
        return Enrollment::create($data);
    }

    public function delete($id)
    {
        $enrollment = Enrollment::find($id);
        if (!$enrollment) return false;
        $enrollment->delete();
        return true;
    }

    public function byStudent($studentId)
    {
        return Enrollment::where('student_id', $studentId)->get();
    }

    public function deleteByStudent($studentId): void
    {
        Enrollment::where('student_id', $studentId)->delete();
    }

    public function deleteByCourse($courseId): void
    {
        Enrollment::where('course_id', $courseId)->delete();
    }
}
