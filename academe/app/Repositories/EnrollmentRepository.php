<?php

namespace App\Repositories;

use App\Models\Enrollment;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;

class EnrollmentRepository implements EnrollmentRepositoryInterface
{
    public function all()
    {
        return Enrollment::with(['student', 'course'])->get();
    }

    public function find($id)
    {
        return Enrollment::findOrFail($id);
    }

    public function create(array $data)
    {
        return Enrollment::create($data);
    }

    public function findWithDetails($id)
    {
        return Enrollment::with(['student', 'course'])->findOrFail($id);
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
        return Enrollment::with(['student', 'course'])
            ->where('student_id', $studentId)
            ->get();
    }

    public function deleteByStudent($studentId)
    {
        return Enrollment::where('student_id', $studentId)->delete();
    }

    public function deleteByCourse($courseId)
    {
        return Enrollment::where('course_id', $courseId)->delete();
    }
}
