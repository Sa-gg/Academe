<?php

namespace App\Repositories;

use App\Models\Course;
use App\Repositories\Interfaces\CourseRepositoryInterface;

class CourseRepository implements CourseRepositoryInterface
{
    public function all()
    {
        return Course::all();
    }

    public function find($id)
    {
        return Course::findOrFail($id);
    }

    public function create(array $data)
    {
        return Course::create($data);
    }

    public function update($id, array $data)
    {
        $course = Course::find($id);
        if (!$course) return null;
        $course->update($data);
        return $course;
    }

    public function delete($id)
    {
        $course = Course::find($id);
        if (!$course) return false;
        // Cascade: remove enrollments first
        $course->enrollments()->delete();
        $course->delete();
        return true;
    }
}
