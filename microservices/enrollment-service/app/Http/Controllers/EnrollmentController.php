<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use App\Services\CourseServiceClient;
use App\Services\StudentServiceClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class EnrollmentController extends Controller
{
    public function __construct(
        private EnrollmentRepositoryInterface $enrollmentRepository,
        private StudentServiceClient $studentService,
        private CourseServiceClient $courseService,
    ) {}

    private function serviceError(\Illuminate\Http\Client\ConnectionException $e): \Illuminate\Http\JsonResponse
    {
        $isTimed = str_contains($e->getMessage(), 'timed out');
        return response()->json([
            'error'   => $isTimed ? 'GATEWAY_TIMEOUT' : 'SERVICE_UNAVAILABLE',
            'message' => $isTimed
                ? 'Dependency service took too long to respond'
                : 'A dependency service is not responding',
        ], $isTimed ? 504 : 503);
    }

    public function index()
    {
        $enrollments = $this->enrollmentRepository->all();
        $enriched = [];

        foreach ($enrollments as $enrollment) {
            try {
                $student = $this->studentService->find($enrollment->student_id);
                $course  = $this->courseService->find($enrollment->course_id);
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                return $this->serviceError($e);
            }

            $enriched[] = [
                'id'          => $enrollment->id,
                'student'     => $student,
                'course'      => $course,
                'enrolled_at' => $enrollment->created_at,
            ];
        }

        return response()->json([
            'data'    => $enriched,
            'message' => 'success',
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|integer',
            'course_id'  => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'   => 'VALIDATION_ERROR',
                'message' => $validator->errors()->first(),
            ], 400);
        }

        try {
            $studentResponse = Http::timeout(5)
                ->get('http://localhost:8001/api/students/' . $request->student_id);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return $this->serviceError($e);
        }

        if ($studentResponse->status() === 404) {
            return response()->json([
                'error'   => 'STUDENT_NOT_FOUND',
                'message' => 'Student with id ' . $request->student_id . ' does not exist',
            ], 404);
        }

        try {
            $courseResponse = Http::timeout(5)
                ->get('http://localhost:8002/api/courses/' . $request->course_id);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return $this->serviceError($e);
        }

        if ($courseResponse->status() === 404) {
            return response()->json([
                'error'   => 'COURSE_NOT_FOUND',
                'message' => 'Course with id ' . $request->course_id . ' does not exist',
            ], 404);
        }

        $exists = Enrollment::where('student_id', $request->student_id)
                            ->where('course_id',  $request->course_id)
                            ->exists();

        if ($exists) {
            return response()->json([
                'error'   => 'DUPLICATE_ENROLLMENT',
                'message' => 'This student is already enrolled in this course',
            ], 409);
        }

        $enrollment = $this->enrollmentRepository->create($validator->validated());

        return response()->json([
            'data'    => $enrollment,
            'message' => 'created',
        ], 201);
    }

    public function show($id)
    {
        try {
            $enrollment = $this->enrollmentRepository->findWithDetails($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error'   => 'NOT_FOUND',
                'message' => 'Enrollment with id ' . $id . ' does not exist',
            ], 404);
        }

        try {
            $student = $this->studentService->find($enrollment->student_id);
            $course  = $this->courseService->find($enrollment->course_id);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return $this->serviceError($e);
        }

        return response()->json([
            'data'    => [
                'id'          => $enrollment->id,
                'student'     => $student,
                'course'      => $course,
                'enrolled_at' => $enrollment->created_at,
            ],
            'message' => 'success',
        ]);
    }

    public function destroy($id)
    {
        $deleted = $this->enrollmentRepository->delete($id);

        if (!$deleted) {
            return response()->json([
                'error'   => 'NOT_FOUND',
                'message' => 'Enrollment with id ' . $id . ' does not exist',
            ], 404);
        }

        return response()->json([
            'data'    => null,
            'message' => 'deleted',
        ]);
    }

    public function byStudent($id)
    {
        $enrollments = $this->enrollmentRepository->byStudent($id);
        $enriched = [];

        foreach ($enrollments as $enrollment) {
            try {
                $student = $this->studentService->find($enrollment->student_id);
                $course  = $this->courseService->find($enrollment->course_id);
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                return $this->serviceError($e);
            }

            $enriched[] = [
                'id'          => $enrollment->id,
                'student'     => $student,
                'course'      => $course,
                'enrolled_at' => $enrollment->created_at,
            ];
        }

        return response()->json([
            'data'    => $enriched,
            'message' => 'success',
        ]);
    }
}
