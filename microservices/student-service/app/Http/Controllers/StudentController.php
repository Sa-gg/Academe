<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function __construct(
        private StudentRepositoryInterface $studentRepository
    ) {}

    public function index()
    {
        $students = $this->studentRepository->all();

        return response()->json([
            'data'    => $students,
            'message' => 'success',
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'   => 'VALIDATION_ERROR',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        if (Student::where('email', $request->email)->exists()) {
            return response()->json([
                'error'   => 'DUPLICATE_EMAIL',
                'message' => 'A student with this email already exists',
            ], 409);
        }

        $student = $this->studentRepository->create($validator->validated());

        return response()->json([
            'data'    => $student,
            'message' => 'created',
        ], 201);
    }

    public function show($id)
    {
        $student = $this->studentRepository->find($id);

        if (!$student) {
            return response()->json([
                'error'   => 'NOT_FOUND',
                'message' => 'Student with id ' . $id . ' does not exist',
            ], 404);
        }

        return response()->json([
            'data'    => $student,
            'message' => 'success',
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'   => 'VALIDATION_ERROR',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        if ($request->has('email')) {
            $duplicate = Student::where('email', $request->email)
                                ->where('id', '!=', $id)
                                ->exists();
            if ($duplicate) {
                return response()->json([
                    'error'   => 'DUPLICATE_EMAIL',
                    'message' => 'A student with this email already exists',
                ], 409);
            }
        }

        $student = $this->studentRepository->update($id, $validator->validated());

        if (!$student) {
            return response()->json([
                'error'   => 'NOT_FOUND',
                'message' => 'Student with id ' . $id . ' does not exist',
            ], 404);
        }

        return response()->json([
            'data'    => $student,
            'message' => 'updated',
        ]);
    }

    public function destroy($id)
    {
        $deleted = $this->studentRepository->delete($id);

        if (!$deleted) {
            return response()->json([
                'error'   => 'NOT_FOUND',
                'message' => 'Student with id ' . $id . ' does not exist',
            ], 404);
        }

        return response()->json([
            'data'    => null,
            'message' => 'deleted',
        ]);
    }
}
