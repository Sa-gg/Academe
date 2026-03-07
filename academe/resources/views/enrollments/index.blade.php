@extends('layouts.app')

@section('title', 'Enrollments')

@section('content')
    @php
        $uniqueStudents = $enrollments->pluck('student_id')->unique()->count();
        $uniqueCourses  = $enrollments->pluck('course_id')->unique()->count();
    @endphp

    <div class="hero-row">
        <div class="hero">
            <div class="hero-eyebrow">Enrollment Records</div>
            <h1 class="hero-title">Enrollments</h1>
            <p class="hero-subtitle">Track student-course assignments across the system.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('enrollments.create') }}" class="btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Enrollment
            </a>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat">
                <div class="stat-value" data-count="{{ $enrollments->count() }}">0</div>
                <div class="stat-label">Total Enrollments</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat">
                <div class="stat-value" style="color: var(--primary);" data-count="{{ $uniqueStudents }}">0</div>
                <div class="stat-label">Students Enrolled</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat">
                <div class="stat-value" style="color: var(--secondary);" data-count="{{ $uniqueCourses }}">0</div>
                <div class="stat-label">Courses Active</div>
            </div>
        </div>
    </div>

    {{-- Student filter --}}
    @if(isset($students) && count($students))
        <div style="margin-bottom: var(--sp-5);">
            <form method="GET" style="display: flex; align-items: center; gap: var(--sp-3);">
                <label for="filter-student" style="margin-bottom: 0; white-space: nowrap;">Filter by Student</label>
                <select id="filter-student" onchange="if(this.value){window.location.href=this.value;}else{window.location.href='{{ route('enrollments.index') }}';}">
                    <option value="">All Students</option>
                    @foreach($students as $stu)
                        @php
                            $stuId   = $stu->id ?? $stu['id'] ?? '';
                            $stuName = $stu->name ?? $stu['name'] ?? '';
                            $currentFilter = request()->segment(3);
                        @endphp
                        <option value="{{ route('enrollments.byStudent', $stuId) }}" {{ $currentFilter == $stuId ? 'selected' : '' }}>{{ $stuName }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    @endif

    @if($enrollments->count())
        <table>
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Student</th>
                    <th>Course</th>
                    <th>Date</th>
                    <th style="width: 80px;">Status</th>
                    <th style="width: 80px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enrollments as $enrollment)
                    @php
                        $eid = $enrollment->id ?? $enrollment['id'] ?? '';
                        $estudent = $enrollment->student ?? $enrollment['student'] ?? null;
                        $ecourse  = $enrollment->course ?? $enrollment['course'] ?? null;
                        $ename = '';
                        $etitle = '';
                        if ($estudent) {
                            $ename = $estudent->name ?? $estudent['name'] ?? '';
                        }
                        if ($ecourse) {
                            $etitle = $ecourse->title ?? $ecourse['title'] ?? '';
                        }
                    @endphp
                    <tr data-href="{{ route('enrollments.show', $eid) }}">
                        <td style="font-variant-numeric: tabular-nums; color: var(--text-tertiary);">{{ $eid }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: var(--sp-3);">
                                <div class="avatar avatar-secondary" style="width: 24px; height: 24px; font-size: 9px;">
                                    {{ strtoupper(substr($ename, 0, 1)) }}
                                </div>
                                <span style="font-weight: var(--weight-medium); color: var(--text-primary);">{{ $ename }}</span>
                            </div>
                        </td>
                        <td>{{ $etitle }}</td>
                        <td style="font-variant-numeric: tabular-nums;">
                            @php
                                $createdAt = $enrollment->created_at ?? $enrollment['created_at'] ?? null;
                            @endphp
                            @if($createdAt)
                                {{ is_string($createdAt) ? \Carbon\Carbon::parse($createdAt)->format('M d, Y') : $createdAt->format('M d, Y') }}
                            @else
                                &mdash;
                            @endif
                        </td>
                        <td><span class="badge badge-success">Active</span></td>
                        <td>
                            <form id="delete-enrollment-{{ $eid }}" action="{{ route('enrollments.destroy', $eid) }}" method="POST" style="display:none;">@method('DELETE') @csrf</form>
                            <button type="button" class="btn btn-danger" style="padding: var(--sp-1) var(--sp-3); height: 28px; font-size: 11px;" data-confirm="Delete this enrollment?" data-form="delete-enrollment-{{ $eid }}">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"/>
                    <rect x="8" y="2" width="8" height="4" rx="1"/>
                </svg>
            </div>
            <div class="empty-state-title">No enrollments yet</div>
            <p class="empty-state-text">Create your first enrollment by assigning a student to a course.</p>
            <a href="{{ route('enrollments.create') }}" class="btn">Create First Enrollment</a>
        </div>
    @endif
@endsection
