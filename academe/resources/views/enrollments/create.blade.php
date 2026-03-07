@extends('layouts.app')

@section('title', 'New Enrollment')
@section('subtitle', 'Assign Student to Course')

@section('content')
    <a href="{{ route('enrollments.index') }}" class="back-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        Back to Enrollments
    </a>

    <div class="hero">
        <div class="hero-eyebrow">New Enrollment</div>
        <h1 class="hero-title">Create Enrollment</h1>
        <p class="hero-subtitle">Select a student and a course to create a new enrollment record.</p>
    </div>

    @if($errors->any())
        <div class="alert-danger" style="display: flex; align-items: flex-start; gap: var(--sp-3);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px; flex-shrink: 0; margin-top: 1px;">
                <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
            <div>
                <div style="font-weight: var(--weight-semibold); margin-bottom: var(--sp-1);">Please fix the following errors</div>
                <ul class="error-list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 340px; gap: var(--sp-6); align-items: start;">
        {{-- Form --}}
        <div class="card" style="margin-bottom: 0;">
            <div class="card-header">
                <h3 style="margin-bottom: 0; font-size: var(--text-sm); font-family: var(--font-body); text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-tertiary);">Enrollment Details</h3>
            </div>
            <form action="{{ route('enrollments.store') }}" method="POST" data-protect-submit id="enrollmentForm">
                @csrf

                <div class="form-group">
                    <label for="student_id">Student</label>
                    <select id="student_id" name="student_id" required>
                        <option value="" data-name="" data-email="">Select a student</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}"
                                    data-name="{{ $student->name }}"
                                    data-email="{{ $student->email }}"
                                    {{ (old('student_id') ?? request('student_id')) == $student->id ? 'selected' : '' }}>
                                {{ $student->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <div style="font-size: 11px; color: var(--danger); margin-top: var(--sp-1);">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="course_id">Course</label>
                    <select id="course_id" name="course_id" required>
                        <option value="" data-title="" data-desc="">Select a course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}"
                                    data-title="{{ $course->title }}"
                                    data-desc="{{ $course->description }}"
                                    {{ (old('course_id') ?? request('course_id')) == $course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <div style="font-size: 11px; color: var(--danger); margin-top: var(--sp-1);">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display: flex; align-items: center; gap: var(--sp-3); padding-top: var(--sp-2);">
                    <button type="submit" class="btn btn-success">
                        Confirm Enrollment
                    </button>
                    <a href="{{ route('enrollments.index') }}" class="btn btn-ghost">Cancel</a>
                </div>
            </form>
        </div>

        {{-- Live preview --}}
        <div class="card" id="enrollmentPreview" style="margin-bottom: 0; position: sticky; top: calc(var(--topbar-height) + var(--sp-8)); border-color: var(--border-strong);">
            <div style="font-size: 11px; font-weight: var(--weight-semibold); color: var(--text-tertiary); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: var(--sp-5);">
                Live Preview
            </div>

            <div style="margin-bottom: var(--sp-5);">
                <div style="font-size: 11px; color: var(--text-tertiary); text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: var(--sp-2);">Student</div>
                <div style="display: flex; align-items: center; gap: var(--sp-3);">
                    <div class="avatar avatar-secondary" style="width: 32px; height: 32px; font-size: 11px;" id="previewStudentAvatar">?</div>
                    <div>
                        <div style="font-weight: var(--weight-medium); color: var(--text-primary); font-size: var(--text-sm);" id="previewStudentName">Not selected</div>
                        <div style="font-size: 11px; color: var(--text-tertiary);" id="previewStudentEmail">&mdash;</div>
                    </div>
                </div>
            </div>

            <hr class="divider" style="margin: var(--sp-4) 0;">

            <div style="margin-bottom: var(--sp-5);">
                <div style="font-size: 11px; color: var(--text-tertiary); text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: var(--sp-2);">Course</div>
                <div style="display: flex; align-items: center; gap: var(--sp-3);">
                    <div class="avatar avatar-primary" style="width: 32px; height: 32px; font-size: 11px;" id="previewCourseAvatar">?</div>
                    <div>
                        <div style="font-weight: var(--weight-medium); color: var(--text-primary); font-size: var(--text-sm);" id="previewCourseName">Not selected</div>
                        <div style="font-size: 11px; color: var(--text-tertiary); max-width: 220px;" id="previewCourseDesc">&mdash;</div>
                    </div>
                </div>
            </div>

            <hr class="divider" style="margin: var(--sp-4) 0;">

            <div style="display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 11px; color: var(--text-tertiary);">Status</span>
                <span class="badge badge-info" id="previewStatus">Incomplete</span>
            </div>
        </div>
    </div>
@endsection
