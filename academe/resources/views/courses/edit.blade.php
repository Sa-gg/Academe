@extends('layouts.app')

@section('title', 'Edit Course')
@section('subtitle', 'Update Details')

@section('content')
    @php
        $cid    = $course->id ?? $course['id'] ?? '';
        $ctitle = old('title', $course->title ?? $course['title'] ?? '');
        $cdesc  = old('description', $course->description ?? $course['description'] ?? '');
    @endphp

    <a href="{{ route('courses.index') }}" class="back-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        Back to Courses
    </a>

    <div style="max-width: 480px;">
        <div class="hero">
            <div class="hero-eyebrow">Update Details</div>
            <h1 class="hero-title">Edit Course</h1>
            <p class="hero-subtitle">Modify the course record below.</p>
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

        <div class="card">
            <form action="{{ route('courses.update', $cid) }}" method="POST" data-protect-submit>
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title">Course Title</label>
                    <input type="text" id="title" name="title" value="{{ $ctitle }}" placeholder="e.g. Introduction to Computer Science" required>
                    @error('title')
                        <div style="font-size: 11px; color: var(--danger); margin-top: var(--sp-1);">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Brief description of the course...">{{ $cdesc }}</textarea>
                    @error('description')
                        <div style="font-size: 11px; color: var(--danger); margin-top: var(--sp-1);">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display: flex; align-items: center; gap: var(--sp-3); padding-top: var(--sp-3);">
                    <button type="submit" class="btn">
                        Update Course
                    </button>
                    <a href="{{ route('courses.index') }}" class="btn btn-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
