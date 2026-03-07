@extends('layouts.app')

@section('title', 'Courses')

@section('content')
    <div class="hero-row">
        <div class="hero">
            <div class="hero-eyebrow">Course Catalog</div>
            <h1 class="hero-title">Courses</h1>
            <p class="hero-subtitle">Browse available programs and enroll students.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('courses.create') }}" class="btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Add Course
            </a>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat">
                <div class="stat-value" data-count="{{ $courses->count() }}">0</div>
                <div class="stat-label">Total Courses</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat">
                <div class="stat-value" style="color: var(--primary);" data-count="{{ $courses->count() }}">0</div>
                <div class="stat-label">Active Programs</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat">
                <div style="display: flex; align-items: center; gap: var(--sp-2);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="var(--secondary)" stroke-width="2" style="width: 24px; height: 24px;" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 6s1.5-2 5-2 5 2 5 2v14s-1.5-1-5-1-5 1-5 1V6z"/>
                        <path d="M12 6s1.5-2 5-2 5 2 5 2v14s-1.5-1-5-1-5 1-5 1V6z"/>
                    </svg>
                    <span class="stat-value" style="font-size: var(--text-xl); color: var(--secondary);">Open</span>
                </div>
                <div class="stat-label">Catalog Status</div>
            </div>
        </div>
    </div>

    @if($courses->count())
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: var(--sp-4);">
            @foreach($courses as $course)
                @php
                    $cid   = $course->id ?? $course['id'] ?? '';
                    $ctitle = $course->title ?? $course['title'] ?? '';
                    $cdesc  = $course->description ?? $course['description'] ?? '';
                @endphp
                <div class="card" style="margin-bottom: 0; display: flex; flex-direction: column;">
                    <div style="display: flex; align-items: center; gap: var(--sp-3); margin-bottom: var(--sp-4);">
                        <div class="avatar avatar-primary" style="width: 36px; height: 36px;">
                            {{ strtoupper(substr($ctitle, 0, 1)) }}
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-weight: var(--weight-semibold); color: var(--text-primary); font-size: var(--text-sm);">
                                {{ $ctitle }}
                            </div>
                            <span class="badge badge-primary" style="margin-top: 2px;">Course #{{ $cid }}</span>
                        </div>
                    </div>

                    <p style="font-size: var(--text-sm); color: var(--text-secondary); margin-bottom: 0; flex: 1; line-height: 1.6;">
                        {{ $cdesc }}
                    </p>

                    <div style="display: flex; align-items: center; gap: var(--sp-2); padding-top: var(--sp-4); margin-top: var(--sp-4); border-top: 1px solid var(--border-subtle);">
                        <a href="{{ route('enrollments.create', ['course_id' => $cid]) }}" class="btn btn-ghost" style="padding: var(--sp-1) var(--sp-3); height: 28px; font-size: 11px;">Enroll</a>
                        <a href="{{ route('courses.edit', $cid) }}" class="btn btn-ghost" style="padding: var(--sp-1) var(--sp-3); height: 28px; font-size: 11px;">Edit</a>
                        <form id="delete-course-{{ $cid }}" action="{{ route('courses.destroy', $cid) }}" method="POST" style="display:none;">@method('DELETE') @csrf</form>
                        <button type="button" class="btn btn-danger" style="padding: var(--sp-1) var(--sp-3); height: 28px; font-size: 11px;" data-confirm="Delete this course? All enrollments for this course will also be removed." data-form="delete-course-{{ $cid }}">Delete</button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="9" rx="1"/>
                    <rect x="14" y="3" width="7" height="5" rx="1"/>
                    <rect x="14" y="12" width="7" height="9" rx="1"/>
                    <rect x="3" y="16" width="7" height="5" rx="1"/>
                </svg>
            </div>
            <div class="empty-state-title">No courses yet</div>
            <p class="empty-state-text">Run the database seeder to populate the course catalog.</p>
        </div>
    @endif
@endsection
