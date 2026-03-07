@extends('layouts.app')

@section('title', 'Enrollment Details')
@section('subtitle', 'Record #' . ($enrollment->id ?? $enrollment['id'] ?? ''))

@section('content')
    @php
        $eid = $enrollment->id ?? $enrollment['id'] ?? '';
        $estudent = $enrollment->student ?? $enrollment['student'] ?? null;
        $ecourse  = $enrollment->course ?? $enrollment['course'] ?? null;
        $ename  = $estudent ? ($estudent->name ?? $estudent['name'] ?? '') : '';
        $eemail = $estudent ? ($estudent->email ?? $estudent['email'] ?? '') : '';
        $etitle = $ecourse ? ($ecourse->title ?? $ecourse['title'] ?? '') : '';
        $edesc  = $ecourse ? ($ecourse->description ?? $ecourse['description'] ?? '') : '';
        $createdAt = $enrollment->created_at ?? $enrollment['created_at'] ?? null;
    @endphp

    <a href="{{ route('enrollments.index') }}" class="back-link">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        Back to Enrollments
    </a>

    <div style="max-width: 560px; margin: 0 auto;">
        <div class="hero" style="text-align: center;">
            <div class="hero-eyebrow">Enrollment Record</div>
            <h1 class="hero-title">Enrollment #{{ $eid }}</h1>
            <div style="margin-top: var(--sp-2);">
                <span class="badge badge-success" style="font-size: var(--text-sm); padding: var(--sp-1) var(--sp-3);">Active</span>
            </div>
        </div>

        <div class="card">
            {{-- Student --}}
            <div style="display: flex; align-items: center; gap: var(--sp-4); margin-bottom: var(--sp-6);">
                <div class="avatar avatar-secondary avatar-lg">
                    {{ strtoupper(substr($ename, 0, 1)) }}
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-tertiary); margin-bottom: var(--sp-1);">Student</div>
                    <div style="font-size: var(--text-lg); font-weight: var(--weight-semibold); color: var(--text-primary);">{{ $ename }}</div>
                    <div style="font-size: var(--text-sm); color: var(--text-secondary);">{{ $eemail }}</div>
                </div>
            </div>

            {{-- Arrow --}}
            <div style="text-align: center; margin-bottom: var(--sp-6); color: var(--text-tertiary);">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px; opacity: 0.4;">
                    <line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/>
                </svg>
            </div>

            {{-- Course --}}
            <div style="display: flex; align-items: center; gap: var(--sp-4); margin-bottom: var(--sp-6);">
                <div class="avatar avatar-primary avatar-lg">
                    {{ strtoupper(substr($etitle, 0, 1)) }}
                </div>
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-tertiary); margin-bottom: var(--sp-1);">Course</div>
                    <div style="font-size: var(--text-lg); font-weight: var(--weight-semibold); color: var(--text-primary);">{{ $etitle }}</div>
                    <div style="font-size: var(--text-sm); color: var(--text-secondary); margin-top: var(--sp-1);">{{ $edesc }}</div>
                </div>
            </div>

            <hr class="divider">

            {{-- Metadata --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--sp-4);">
                <div>
                    <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-tertiary); margin-bottom: var(--sp-1);">Enrolled On</div>
                    <div style="font-weight: var(--weight-medium); color: var(--text-primary); font-size: var(--text-sm);">
                        @if($createdAt)
                            {{ is_string($createdAt) ? \Carbon\Carbon::parse($createdAt)->format('F j, Y') : $createdAt->format('F j, Y') }}
                        @else
                            &mdash;
                        @endif
                    </div>
                </div>
                <div>
                    <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-tertiary); margin-bottom: var(--sp-1);">Time</div>
                    <div style="font-weight: var(--weight-medium); color: var(--text-primary); font-size: var(--text-sm);">
                        @if($createdAt)
                            {{ is_string($createdAt) ? \Carbon\Carbon::parse($createdAt)->format('h:i A') : $createdAt->format('h:i A') }}
                        @else
                            &mdash;
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: var(--sp-3);">
            <a href="{{ route('enrollments.index') }}" class="btn btn-ghost">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px;"><polyline points="15 18 9 12 15 6"/></svg>
                All Enrollments
            </a>
            <a href="{{ route('enrollments.create') }}" class="btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New Enrollment
            </a>
            <form id="delete-enrollment-{{ $eid }}" action="{{ route('enrollments.destroy', $eid) }}" method="POST" style="display:none;">@method('DELETE') @csrf</form>
            <button type="button" class="btn btn-danger" data-confirm="Delete this enrollment?" data-form="delete-enrollment-{{ $eid }}">Delete</button>
        </div>
    </div>
@endsection
