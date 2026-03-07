<?php

namespace App\Repositories\Interfaces;

interface EnrollmentRepositoryInterface
{
    public function all();
    public function find($id);
    public function findWithDetails($id);
    public function create(array $data);
    public function delete($id);
    public function byStudent($studentId);
    public function deleteByStudent($studentId);
    public function deleteByCourse($courseId);
}
