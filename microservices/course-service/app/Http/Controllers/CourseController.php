<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function __construct(
        private CourseRepositoryInterface $courseRepository
    ) {}

    public function index()
    {
        $courses = $this->courseRepository->all();

        return response()->json([
            'data'    => $courses,
            'message' => 'success',
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'   => 'VALIDATION_ERROR',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $course = $this->courseRepository->create($validator->validated());

        return response()->json([
            'data'    => $course,
            'message' => 'created',
        ], 201);
    }

    public function show($id)
    {
        $course = $this->courseRepository->find($id);

        if (!$course) {
            return response()->json([
                'error'   => 'NOT_FOUND',
                'message' => 'Course with id ' . $id . ' does not exist',
            ], 404);
        }

        return response()->json([
            'data'    => $course,
            'message' => 'success',
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'       => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'   => 'VALIDATION_ERROR',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        $course = $this->courseRepository->update($id, $validator->validated());

        if (!$course) {
            return response()->json([
                'error'   => 'NOT_FOUND',
                'message' => 'Course with id ' . $id . ' does not exist',
            ], 404);
        }

        return response()->json([
            'data'    => $course,
            'message' => 'updated',
        ]);
    }

    public function destroy($id)
    {
        $deleted = $this->courseRepository->delete($id);

        if (!$deleted) {
            return response()->json([
                'error'   => 'NOT_FOUND',
                'message' => 'Course with id ' . $id . ' does not exist',
            ], 404);
        }

        return response()->json([
            'data'    => null,
            'message' => 'deleted',
        ]);
    }
}
