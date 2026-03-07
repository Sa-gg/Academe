@extends('layouts.app')

@section('title', 'Edit Student')
@section('subtitle', 'Update Details')

@section('content')
    @php
        $sid   = $student->id ?? $student['id'] ?? '';
        $sname = old('name', $student->name ?? $student['name'] ?? '');
        $semail = old('email', $student->email ?? $student['email'] ?? '');
    @endphp

    <a href="{{ route('students.index') }}" class="back-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        Back to Students
    </a>

    <div style="max-width: 480px;">
        <div class="hero">
            <div class="hero-eyebrow">Update Details</div>
            <h1 class="hero-title">Edit Student</h1>
            <p class="hero-subtitle">Modify the student record below.</p>
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
            <form action="{{ route('students.update', $sid) }}" method="POST" data-protect-submit>
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ $sname }}" placeholder="e.g. Maria Santos" required>
                    @error('name')
                        <div style="font-size: 11px; color: var(--danger); margin-top: var(--sp-1);">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ $semail }}" placeholder="e.g. maria@university.edu" required>
                    @error('email')
                        <div style="font-size: 11px; color: var(--danger); margin-top: var(--sp-1);">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display: flex; align-items: center; gap: var(--sp-3); padding-top: var(--sp-3);">
                    <button type="submit" class="btn">
                        Update Student
                    </button>
                    <a href="{{ route('students.index') }}" class="btn btn-ghost">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
