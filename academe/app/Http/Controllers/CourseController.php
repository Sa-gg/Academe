<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\CourseRepositoryInterface;
use App\Services\CourseServiceClient;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    protected $courseRepository;
    protected $courseClient;

    public function __construct(CourseRepositoryInterface $courseRepository, CourseServiceClient $courseClient)
    {
        $this->courseRepository = $courseRepository;
        $this->courseClient = $courseClient;
    }

    public function index()
    {
        $mode = config('backend.mode');

        if ($mode === 'microservices') {
            try {
                $courses = $this->courseClient->all();
            } catch (\RuntimeException $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            $courses = $this->courseRepository->all();
        }

        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        return view('courses.create');
    }

    public function store(Request $request)
    {
        $mode = config('backend.mode');

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($mode === 'microservices') {
            try {
                $this->courseClient->create($request->only(['title', 'description']));
            } catch (\RuntimeException $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            $this->courseRepository->create($request->only(['title', 'description']));
        }

        return redirect()->route('courses.index')->with('success', 'Course created successfully.');
    }

    public function edit($id)
    {
        $mode = config('backend.mode');

        if ($mode === 'microservices') {
            try {
                $course = $this->courseClient->find($id);
            } catch (\RuntimeException $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
            if (!$course) abort(404);
        } else {
            $course = $this->courseRepository->find($id);
        }

        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, $id)
    {
        $mode = config('backend.mode');

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($mode === 'microservices') {
            try {
                $course = $this->courseClient->update($id, $request->only(['title', 'description']));
            } catch (\RuntimeException $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
            if (!$course) abort(404);
        } else {
            $course = $this->courseRepository->update($id, $request->only(['title', 'description']));
            if (!$course) abort(404);
        }

        return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy($id)
    {
        $mode = config('backend.mode');

        if ($mode === 'microservices') {
            try {
                $deleted = $this->courseClient->delete($id);
            } catch (\RuntimeException $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
            if (!$deleted) abort(404);
        } else {
            $deleted = $this->courseRepository->delete($id);
            if (!$deleted) abort(404);
        }

        return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
    }
}
