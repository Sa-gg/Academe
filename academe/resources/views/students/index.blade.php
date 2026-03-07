@extends('layouts.app')

@section('title', 'Students')

@section('content')
    <div class="hero-row">
        <div class="hero">
            <div class="hero-eyebrow">Student Directory</div>
            <h1 class="hero-title">Students</h1>
            <p class="hero-subtitle">Manage registered students and their enrollments.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('students.create') }}" class="btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Student
            </a>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat">
                <div class="stat-value" data-count="{{ $students->count() }}">0</div>
                <div class="stat-label">Registered Students</div>
            </div>
        </div>
    </div>

    @if($students->count())
        <table>
            <thead>
                <tr>
                    <th style="width: 44px;"></th>
                    <th>Name</th>
                    <th>Email</th>
                    <th style="width: 180px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    @php
                        $sid   = $student->id ?? $student['id'] ?? '';
                        $sname = $student->name ?? $student['name'] ?? '';
                        $semail = $student->email ?? $student['email'] ?? '';
                    @endphp
                    <tr>
                        <td>
                            <div class="avatar avatar-secondary" style="width: 28px; height: 28px; font-size: 10px;">
                                {{ strtoupper(substr($sname, 0, 1)) }}{{ strtoupper(substr(strstr($sname, ' ') ?: '', 1, 1)) }}
                            </div>
                        </td>
                        <td style="font-weight: var(--weight-medium); color: var(--text-primary);">{{ $sname }}</td>
                        <td>{{ $semail }}</td>
                        <td>
                            <div style="display: flex; align-items: center; gap: var(--sp-2);">
                                <a href="{{ route('enrollments.create', ['student_id' => $sid]) }}" class="btn btn-ghost" style="padding: var(--sp-1) var(--sp-3); height: 28px; font-size: 11px;">Enroll</a>
                                <a href="{{ route('students.edit', $sid) }}" class="btn btn-ghost" style="padding: var(--sp-1) var(--sp-3); height: 28px; font-size: 11px;">Edit</a>
                                <form id="delete-student-{{ $sid }}" action="{{ route('students.destroy', $sid) }}" method="POST" style="display:none;">@method('DELETE') @csrf</form>
                                <button type="button" class="btn btn-danger" style="padding: var(--sp-1) var(--sp-3); height: 28px; font-size: 11px;" data-confirm="Delete this student? All their enrollments will also be removed." data-form="delete-student-{{ $sid }}">Delete</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M2 21v-2a4 4 0 014-4h6a4 4 0 014 4v2"/>
                </svg>
            </div>
            <div class="empty-state-title">No students registered</div>
            <p class="empty-state-text">Create your first student to begin enrolling them in courses.</p>
            <a href="{{ route('students.create') }}" class="btn">Add First Student</a>
        </div>
    @endif
@endsection
