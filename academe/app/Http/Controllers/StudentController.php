<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Repositories\Interfaces\StudentRepositoryInterface;
use App\Services\StudentServiceClient;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected $studentRepository;
    protected $studentClient;

    public function __construct(StudentRepositoryInterface $studentRepository, StudentServiceClient $studentClient)
    {
        $this->studentRepository = $studentRepository;
        $this->studentClient = $studentClient;
    }

    public function index()
    {
        $mode = config('backend.mode');

        if ($mode === 'microservices') {
            try {
                $students = $this->studentClient->all();
            } catch (\RuntimeException $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            $students = $this->studentRepository->all();
        }

        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(StoreStudentRequest $request)
    {
        $mode = config('backend.mode');

        if ($mode === 'microservices') {
            try {
                $this->studentClient->create($request->validated());
            } catch (\RuntimeException $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            $this->studentRepository->create($request->validated());
        }

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    public function edit($id)
    {
        $mode = config('backend.mode');

        if ($mode === 'microservices') {
            try {
                $student = $this->studentClient->find($id);
            } catch (\RuntimeException $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
            if (!$student) abort(404);
        } else {
            $student = $this->studentRepository->find($id);
        }

        return view('students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $mode = config('backend.mode');

        $request->validate([
            'name'  => 'required|string',
            'email' => 'required|email',
        ]);

        if ($mode === 'microservices') {
            try {
                $student = $this->studentClient->update($id, $request->only(['name', 'email']));
            } catch (\RuntimeException $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
            if (!$student) abort(404);
        } else {
            $student = $this->studentRepository->update($id, $request->only(['name', 'email']));
            if (!$student) abort(404);
        }

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy($id)
    {
        $mode = config('backend.mode');

        if ($mode === 'microservices') {
            try {
                $deleted = $this->studentClient->delete($id);
            } catch (\RuntimeException $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
            if (!$deleted) abort(404);
        } else {
            $deleted = $this->studentRepository->delete($id);
            if (!$deleted) abort(404);
        }

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}
