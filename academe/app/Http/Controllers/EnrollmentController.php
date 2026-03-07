<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnrollmentRequest;
use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Repositories\Interfaces\EnrollmentRepositoryInterface;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Services\CourseServiceClient;
use App\Services\EnrollmentServiceClient;
use App\Services\StudentServiceClient;

class EnrollmentController extends Controller
{
    protected $enrollmentRepository;
    protected $studentRepository;
    protected $courseRepository;
    protected $enrollmentClient;
    protected $studentClient;
    protected $courseClient;

    public function __construct(
        EnrollmentRepositoryInterface $enrollmentRepository,
        StudentRepositoryInterface $studentRepository,
        CourseRepositoryInterface $courseRepository,
        EnrollmentServiceClient $enrollmentClient,
        StudentServiceClient $studentClient,
        CourseServiceClient $courseClient
    ) {
        $this->enrollmentRepository = $enrollmentRepository;
        $this->studentRepository = $studentRepository;
        $this->courseRepository = $courseRepository;
        $this->enrollmentClient = $enrollmentClient;
        $this->studentClient = $studentClient;
        $this->courseClient = $courseClient;
    }

    public function index()
    {
        $mode = config('backend.mode');

        if ($mode === 'microservices') {
            try {
                $enrollments = $this->enrollmentClient->all();
                $students = $this->studentClient->all();
            } catch (\RuntimeException $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            $enrollments = $this->enrollmentRepository->all();
            $students = $this->studentRepository->all();
        }

        return view('enrollments.index', compact('enrollments', 'students'));
    }

    public function create()
    {
        $mode = config('backend.mode');

        if ($mode === 'microservices') {
            try {
                $students = $this->studentClient->all();
                $courses = $this->courseClient->all();
            } catch (\RuntimeException $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            $students = $this->studentRepository->all();
            $courses = $this->courseRepository->all();
        }

        return view('enrollments.create', compact('students', 'courses'));
    }

    public function store(StoreEnrollmentRequest $request)
    {
        $mode = config('backend.mode');

        if ($mode === 'microservices') {
            try {
                $this->enrollmentClient->create($request->validated());
            } catch (\RuntimeException $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            $this->enrollmentRepository->create($request->validated());
        }

        return redirect()->route('enrollments.index')->with('success', 'Enrollment created successfully.');
    }

    public function show($id)
    {
        $mode = config('backend.mode');

        if ($mode === 'microservices') {
            try {
                $enrollment = $this->enrollmentClient->find($id);
            } catch (\RuntimeException $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }

            if (!$enrollment) {
                abort(404);
            }
        } else {
            $enrollment = $this->enrollmentRepository->findWithDetails($id);
        }

        return view('enrollments.show', compact('enrollment'));
    }

    public function destroy($id)
    {
        $mode = config('backend.mode');

        if ($mode === 'microservices') {
            try {
                $deleted = $this->enrollmentClient->delete($id);
            } catch (\RuntimeException $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
            if (!$deleted) abort(404);
        } else {
            $deleted = $this->enrollmentRepository->delete($id);
            if (!$deleted) abort(404);
        }

        return redirect()->route('enrollments.index')->with('success', 'Enrollment deleted successfully.');
    }

    public function byStudent($id)
    {
        $mode = config('backend.mode');

        if ($mode === 'microservices') {
            try {
                $enrollments = $this->enrollmentClient->byStudent($id);
                $students = $this->studentClient->all();
            } catch (\RuntimeException $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            $enrollments = $this->enrollmentRepository->byStudent($id);
            $students = $this->studentRepository->all();
        }

        return view('enrollments.index', compact('enrollments', 'students'));
    }
}
