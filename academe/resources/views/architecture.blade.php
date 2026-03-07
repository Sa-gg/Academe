@extends('layouts.app')

@section('title', 'Architecture')
@section('subtitle', 'System Design')

@section('content')
    @php $mode = config('backend.mode', 'monolithic'); @endphp
    <div style="max-width: 880px; margin: 0 auto;">
        <div class="hero" style="text-align: center;">
            <div class="hero-eyebrow">System Design</div>
            <h1 class="hero-title">Architecture Comparison</h1>
            <p class="hero-subtitle">Monolithic vs. Microservices — how Academe is built and how it could evolve.</p>
            <div style="margin-top: var(--sp-4);">
                @if($mode === 'microservices')
                    <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 16px; border-radius: var(--radius-full); background: var(--accent-soft); color: var(--accent-2); font-size: var(--text-sm); font-weight: var(--weight-semibold);">&#x2B21; Microservices Mode</span>
                @else
                    <span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 16px; border-radius: var(--radius-full); background: var(--gold-soft); color: var(--gold); font-size: var(--text-sm); font-weight: var(--weight-semibold);">&#x2B21; Monolithic Mode</span>
                @endif
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════
             MONOLITHIC ARCHITECTURE DIAGRAM
             ══════════════════════════════════════════════════ --}}
        <div class="card" style="margin-bottom: var(--sp-8); overflow: hidden; transition: opacity 300ms ease, filter 300ms ease;{{ $mode !== 'monolithic' ? ' opacity: 0.35; filter: grayscale(1);' : '' }}">
            <div class="card-header">
                <h3 style="margin-bottom: 0; font-size: var(--text-sm); font-family: var(--font-body); text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-tertiary);">
                    Monolithic Architecture
                </h3>
                @if($mode === 'monolithic')
                    <span class="badge badge-primary">&bull; Active</span>
                @else
                    <span class="badge" style="background: var(--bg-tertiary); color: var(--text-tertiary);">&#9675; Inactive</span>
                @endif
            </div>

            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 720 500" style="width: 100%; height: auto; border-radius: var(--radius-md); background: #FAFAF8;">
                <defs>
                    <marker id="monoArrow" markerWidth="8" markerHeight="8" refX="7" refY="4" orient="auto">
                        <path d="M1,1 L7,4 L1,7" fill="none" stroke="#6366F1" stroke-width="1.5"/>
                    </marker>
                </defs>

                {{-- Step labels on left --}}
                <text x="28" y="50" font-family="Plus Jakarta Sans, sans-serif" font-size="9" fill="#94A3B8" font-weight="600" letter-spacing="0.06em">REQUEST</text>
                <text x="28" y="130" font-family="Plus Jakarta Sans, sans-serif" font-size="9" fill="#94A3B8" font-weight="600" letter-spacing="0.06em">ROUTE</text>
                <text x="28" y="190" font-family="Plus Jakarta Sans, sans-serif" font-size="9" fill="#94A3B8" font-weight="600" letter-spacing="0.06em">VALIDATE</text>
                <text x="28" y="250" font-family="Plus Jakarta Sans, sans-serif" font-size="9" fill="#94A3B8" font-weight="600" letter-spacing="0.06em">CONTROL</text>
                <text x="28" y="310" font-family="Plus Jakarta Sans, sans-serif" font-size="9" fill="#94A3B8" font-weight="600" letter-spacing="0.06em">REPO</text>
                <text x="28" y="370" font-family="Plus Jakarta Sans, sans-serif" font-size="9" fill="#94A3B8" font-weight="600" letter-spacing="0.06em">MODEL</text>
                <text x="28" y="430" font-family="Plus Jakarta Sans, sans-serif" font-size="9" fill="#94A3B8" font-weight="600" letter-spacing="0.06em">DATABASE</text>

                {{-- Browser --}}
                <rect x="240" y="20" width="240" height="44" rx="8" fill="#FFFFFF" stroke="#94A3B8" stroke-width="1.5"/>
                <text x="360" y="47" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="12" font-weight="600" fill="#334155">Browser Request</text>

                {{-- Arrow 1 --}}
                <line x1="360" y1="64" x2="360" y2="98" stroke="#6366F1" stroke-width="1.5" marker-end="url(#monoArrow)"/>

                {{-- Route box --}}
                <rect x="200" y="100" width="320" height="40" rx="6" fill="#EEF2FF" stroke="#6366F1" stroke-width="1.2"/>
                <text x="360" y="118" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="10" font-weight="700" fill="#4F46E5">routes/api.php  ·  routes/web.php</text>
                <text x="360" y="133" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="9" fill="#6366F1">Route → Controller mapping</text>

                {{-- Arrow 2 --}}
                <line x1="360" y1="140" x2="360" y2="158" stroke="#6366F1" stroke-width="1.5" marker-end="url(#monoArrow)"/>

                {{-- Form Request box --}}
                <rect x="200" y="160" width="320" height="40" rx="6" fill="#F0FDF4" stroke="#16A34A" stroke-width="1.2"/>
                <text x="360" y="178" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="10" font-weight="700" fill="#15803D">app/Http/Requests/StoreStudentRequest.php</text>
                <text x="360" y="193" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="9" fill="#16A34A">authorize() + rules() — validated before controller runs</text>

                {{-- Arrow 3 --}}
                <line x1="360" y1="200" x2="360" y2="218" stroke="#6366F1" stroke-width="1.5" marker-end="url(#monoArrow)"/>

                {{-- Controller box --}}
                <rect x="200" y="220" width="320" height="40" rx="6" fill="#EEF2FF" stroke="#6366F1" stroke-width="1.2"/>
                <text x="360" y="238" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="10" font-weight="700" fill="#4F46E5">app/Http/Controllers/StudentController.php</text>
                <text x="360" y="253" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="9" fill="#6366F1">Injects StudentRepositoryInterface — no direct DB calls</text>

                {{-- Arrow 4 --}}
                <line x1="360" y1="260" x2="360" y2="278" stroke="#6366F1" stroke-width="1.5" marker-end="url(#monoArrow)"/>

                {{-- Repository Interface + Implementation --}}
                <rect x="160" y="280" width="400" height="56" rx="6" fill="#FFFFFF" stroke="#94A3B8" stroke-width="1.2"/>
                <rect x="162" y="282" width="194" height="52" rx="5" fill="#F8F8F6" stroke="#D4D4D2" stroke-width="1"/>
                <text x="259" y="306" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="9" font-weight="700" fill="#525252">Repositories/Interfaces/</text>
                <text x="259" y="320" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="9" fill="#737373">StudentRepositoryInterface</text>
                <rect x="364" y="282" width="194" height="52" rx="5" fill="#EEF2FF" stroke="#6366F1" stroke-width="1"/>
                <text x="461" y="306" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="9" font-weight="700" fill="#4F46E5">Repositories/</text>
                <text x="461" y="320" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="9" fill="#6366F1">StudentRepository (Eloquent)</text>
                {{-- implements arrow --}}
                <line x1="355" y1="308" x2="367" y2="308" stroke="#6366F1" stroke-width="1" marker-end="url(#monoArrow)"/>

                {{-- Arrow 5 --}}
                <line x1="360" y1="336" x2="360" y2="354" stroke="#6366F1" stroke-width="1.5" marker-end="url(#monoArrow)"/>

                {{-- Model --}}
                <rect x="200" y="356" width="320" height="40" rx="6" fill="#FFFFFF" stroke="#94A3B8" stroke-width="1.2"/>
                <text x="360" y="374" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="10" font-weight="700" fill="#334155">app/Models/Student.php</text>
                <text x="360" y="389" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="9" fill="#94A3B8">Eloquent ORM — fillable, relationships, casts</text>

                {{-- Arrow 6 --}}
                <line x1="360" y1="396" x2="360" y2="414" stroke="#D97706" stroke-width="1.5" marker-end="url(#monoArrow)"/>

                {{-- SQLite DB --}}
                <rect x="240" y="416" width="240" height="40" rx="8" fill="#FEF3C7" stroke="#D97706" stroke-width="1.5"/>
                <text x="360" y="440" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="12" font-weight="600" fill="#92400E">SQLite Database (database.sqlite)</text>

                {{-- Return path on right --}}
                <text x="620" y="440" font-family="Plus Jakarta Sans, sans-serif" font-size="9" fill="#94A3B8" font-weight="600" letter-spacing="0.04em">RESPONSE</text>
                <line x1="608" y1="436" x2="608" y2="244" stroke="#6366F1" stroke-width="1" stroke-dasharray="4 3" marker-end="url(#monoArrow)"/>
                <text x="616" y="340" font-family="Plus Jakarta Sans, sans-serif" font-size="8" fill="#94A3B8" transform="rotate(-90,616,340)">Controller → View → HTTP Response</text>
            </svg>
        </div>

        {{-- ══════════════════════════════════════════════════
             MICROSERVICES ARCHITECTURE DIAGRAM
             ══════════════════════════════════════════════════ --}}
        <div class="card" style="margin-bottom: var(--sp-8); overflow: hidden; transition: opacity 300ms ease, filter 300ms ease;{{ $mode !== 'microservices' ? ' opacity: 0.35; filter: grayscale(1);' : '' }}">
            <div class="card-header">
                <h3 style="margin-bottom: 0; font-size: var(--text-sm); font-family: var(--font-body); text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-tertiary);">
                    Microservices Architecture
                </h3>
                @if($mode === 'microservices')
                    <span class="badge badge-primary">&bull; Active</span>
                @else
                    <span class="badge" style="background: var(--bg-tertiary); color: var(--text-tertiary);">&#9675; Inactive</span>
                @endif
            </div>

            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 760 460" style="width: 100%; height: auto; border-radius: var(--radius-md); background: #FAFAF8;">
                <defs>
                    <marker id="microArrow" markerWidth="8" markerHeight="8" refX="7" refY="4" orient="auto">
                        <path d="M1,1 L7,4 L1,7" fill="none" stroke="#6366F1" stroke-width="1.5"/>
                    </marker>
                    <marker id="httpArrow" markerWidth="8" markerHeight="8" refX="7" refY="4" orient="auto">
                        <path d="M1,1 L7,4 L1,7" fill="none" stroke="#D97706" stroke-width="1.5"/>
                    </marker>
                </defs>

                {{-- Academe Monolith (the caller) --}}
                <rect x="240" y="16" width="280" height="52" rx="8" fill="#EEF2FF" stroke="#6366F1" stroke-width="1.8"/>
                <text x="380" y="38" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="12" font-weight="700" fill="#4F46E5">Academe Laravel App  :8000</text>
                <text x="380" y="57" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="9" fill="#6366F1">APP_BACKEND=microservices in .env</text>

                {{-- Fan-out arrows --}}
                <line x1="290" y1="68" x2="140" y2="136" stroke="#6366F1" stroke-width="1.4" marker-end="url(#microArrow)"/>
                <line x1="380" y1="68" x2="380" y2="136" stroke="#6366F1" stroke-width="1.4" marker-end="url(#microArrow)"/>
                <line x1="470" y1="68" x2="620" y2="136" stroke="#6366F1" stroke-width="1.4" marker-end="url(#microArrow)"/>

                {{-- ── Student Service ── --}}
                <rect x="30" y="140" width="220" height="130" rx="10" fill="none" stroke="#6366F1" stroke-width="1.6" stroke-dasharray="5 3"/>
                <text x="140" y="160" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="9" font-weight="700" fill="#6366F1" letter-spacing="0.07em">STUDENT SERVICE</text>
                <text x="140" y="173" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="9" fill="#94A3B8">:8001</text>
                <rect x="50" y="180" width="180" height="36" rx="5" fill="#FFFFFF" stroke="#6366F1" stroke-width="1"/>
                <text x="140" y="196" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="10" font-weight="600" fill="#334155">REST API (Laravel)</text>
                <text x="140" y="210" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="8" fill="#94A3B8">GET /students  POST /students  GET /students/{id}</text>
                {{-- DB cylinder --}}
                <ellipse cx="140" cy="237" rx="60" ry="8" fill="#FEF3C7" stroke="#D97706" stroke-width="1.2"/>
                <rect x="80" y="237" width="120" height="24" fill="#FEF3C7" stroke="#D97706" stroke-width="0"/>
                <line x1="80" y1="237" x2="80" y2="261" stroke="#D97706" stroke-width="1.2"/>
                <line x1="200" y1="237" x2="200" y2="261" stroke="#D97706" stroke-width="1.2"/>
                <ellipse cx="140" cy="261" rx="60" ry="8" fill="#FEF3C7" stroke="#D97706" stroke-width="1.2"/>
                <text x="140" y="254" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="9" font-weight="600" fill="#92400E">students.sqlite</text>

                {{-- ── Course Service ── --}}
                <rect x="270" y="140" width="220" height="130" rx="10" fill="none" stroke="#6366F1" stroke-width="1.6" stroke-dasharray="5 3"/>
                <text x="380" y="160" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="9" font-weight="700" fill="#6366F1" letter-spacing="0.07em">COURSE SERVICE</text>
                <text x="380" y="173" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="9" fill="#94A3B8">:8002</text>
                <rect x="290" y="180" width="180" height="36" rx="5" fill="#FFFFFF" stroke="#6366F1" stroke-width="1"/>
                <text x="380" y="196" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="10" font-weight="600" fill="#334155">REST API (Laravel)</text>
                <text x="380" y="210" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="8" fill="#94A3B8">GET /courses  GET /courses/{id}</text>
                {{-- DB cylinder --}}
                <ellipse cx="380" cy="237" rx="60" ry="8" fill="#FEF3C7" stroke="#D97706" stroke-width="1.2"/>
                <rect x="320" y="237" width="120" height="24" fill="#FEF3C7" stroke="#D97706" stroke-width="0"/>
                <line x1="320" y1="237" x2="320" y2="261" stroke="#D97706" stroke-width="1.2"/>
                <line x1="440" y1="237" x2="440" y2="261" stroke="#D97706" stroke-width="1.2"/>
                <ellipse cx="380" cy="261" rx="60" ry="8" fill="#FEF3C7" stroke="#D97706" stroke-width="1.2"/>
                <text x="380" y="254" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="9" font-weight="600" fill="#92400E">courses.sqlite</text>

                {{-- ── Enrollment Service ── --}}
                <rect x="510" y="140" width="220" height="130" rx="10" fill="none" stroke="#6366F1" stroke-width="1.6" stroke-dasharray="5 3"/>
                <text x="620" y="160" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="9" font-weight="700" fill="#6366F1" letter-spacing="0.07em">ENROLLMENT SERVICE</text>
                <text x="620" y="173" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="9" fill="#94A3B8">:8003</text>
                <rect x="530" y="180" width="180" height="36" rx="5" fill="#FFFFFF" stroke="#6366F1" stroke-width="1"/>
                <text x="620" y="196" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="10" font-weight="600" fill="#334155">REST API (Laravel)</text>
                <text x="620" y="210" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="8" fill="#94A3B8">GET /enrollments  POST /enrollments  GET /enrollments/{id}</text>
                {{-- DB cylinder --}}
                <ellipse cx="620" cy="237" rx="60" ry="8" fill="#FEF3C7" stroke="#D97706" stroke-width="1.2"/>
                <rect x="560" y="237" width="120" height="24" fill="#FEF3C7" stroke="#D97706" stroke-width="0"/>
                <line x1="560" y1="237" x2="560" y2="261" stroke="#D97706" stroke-width="1.2"/>
                <line x1="680" y1="237" x2="680" y2="261" stroke="#D97706" stroke-width="1.2"/>
                <ellipse cx="620" cy="261" rx="60" ry="8" fill="#FEF3C7" stroke="#D97706" stroke-width="1.2"/>
                <text x="620" y="254" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="9" font-weight="600" fill="#92400E">enrollments.sqlite</text>

                {{-- HTTP arrows: Enrollment → Student --}}
                <path d="M 530,320 Q 350,360 200,320" fill="none" stroke="#D97706" stroke-width="1.4" stroke-dasharray="5 3" marker-end="url(#httpArrow)"/>
                <text x="360" y="372" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="9" font-weight="600" fill="#D97706">HTTP GET /api/students/{id}</text>

                {{-- HTTP arrows: Enrollment → Course --}}
                <path d="M 530,305 Q 470,345 450,305" fill="none" stroke="#D97706" stroke-width="1.4" stroke-dasharray="5 3" marker-end="url(#httpArrow)"/>
                <text x="488" y="350" text-anchor="middle" font-family="Plus Jakarta Sans, sans-serif" font-size="9" font-weight="600" fill="#D97706">HTTP GET /api/courses/{id}</text>

                {{-- Legend --}}
                <line x1="50" y1="420" x2="80" y2="420" stroke="#6366F1" stroke-width="1.4"/>
                <text x="86" y="424" font-family="Plus Jakarta Sans, sans-serif" font-size="9" fill="#6366F1">Service call (from Academe)</text>
                <line x1="50" y1="440" x2="80" y2="440" stroke="#D97706" stroke-width="1.4" stroke-dasharray="4 2"/>
                <text x="86" y="444" font-family="Plus Jakarta Sans, sans-serif" font-size="9" fill="#D97706">Inter-service HTTP call (Enrollment → Student/Course)</text>
            </svg>
        </div>

        {{-- ══════════════════════════════════════════════════
             ACTIVE MODE CALLOUT
             ══════════════════════════════════════════════════ --}}
        <div class="card" style="margin-bottom: var(--sp-8); border-left: 4px solid {{ $mode === 'microservices' ? 'var(--accent)' : 'var(--gold)' }}; background: {{ $mode === 'microservices' ? 'var(--accent-soft)' : 'var(--gold-soft)' }};">
            @if($mode === 'microservices')
                <p style="margin: 0; font-size: var(--text-sm); color: var(--text-secondary); line-height: 1.7;">
                    <strong style="color: var(--accent-2);">Microservices mode is active.</strong>
                    The system is running as three independent Laravel services (Student :8001, Course :8002, Enrollment :8003)
                    with the Academe frontend proxying requests via HTTP service clients.
                    To switch to monolithic mode, set <code>APP_BACKEND=monolithic</code> in <code>academe/.env</code> and reload.
                </p>
            @else
                <p style="margin: 0; font-size: var(--text-sm); color: var(--text-secondary); line-height: 1.7;">
                    <strong style="color: var(--gold);">Monolithic mode is active.</strong>
                    The system is running as a single Laravel application with Eloquent ORM backed by a single SQLite database.
                    Controllers use the Repository Pattern to access data through interface-driven implementations.
                    To switch to microservices mode, set <code>APP_BACKEND=microservices</code> in <code>academe/.env</code> and reload.
                </p>
            @endif
        </div>

        {{-- ══════════════════════════════════════════════════
             COMPARISON TABLE
             ══════════════════════════════════════════════════ --}}
        <div class="card" style="margin-bottom: var(--sp-8); padding: 0; overflow: hidden;">
            <div style="padding: var(--sp-5) var(--sp-6); border-bottom: 1px solid var(--border-subtle);">
                <h3 style="margin-bottom: 0; font-size: var(--text-sm); font-family: var(--font-body); text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-tertiary);">
                    Side-by-Side Comparison
                </h3>
            </div>
            <table style="border: none; border-radius: 0; box-shadow: none;">
                <colgroup>
                    <col>
                    <col{!! $mode === 'monolithic' ? ' style="background: var(--gold-soft);"' : '' !!}>
                    <col{!! $mode === 'microservices' ? ' style="background: var(--accent-soft);"' : '' !!}>
                    <col>
                </colgroup>
                <thead>
                    <tr>
                        <th style="width: 30%;">Criteria</th>
                        <th style="width: 25%;{{ $mode === 'monolithic' ? ' background: var(--gold-soft);' : '' }}">Monolithic</th>
                        <th style="width: 25%;{{ $mode === 'microservices' ? ' background: var(--accent-soft);' : '' }}">Microservices</th>
                        <th style="width: 20%;">Winner</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Ease of Development</td>
                        <td>Low — single codebase, shared ORM</td>
                        <td>High — distributed systems, inter-service HTTP</td>
                        <td><span class="badge badge-warning">Mono</span></td>
                    </tr>
                    <tr>
                        <td>Deployment Difficulty</td>
                        <td>Single artifact, one server</td>
                        <td>Per-service CI/CD pipelines, container orchestration</td>
                        <td><span class="badge badge-warning">Mono</span></td>
                    </tr>
                    <tr>
                        <td>Scalability</td>
                        <td>Vertical only — scale everything together</td>
                        <td>Horizontal, scale each service independently</td>
                        <td><span class="badge badge-success">Micro</span></td>
                    </tr>
                    <tr>
                        <td>Fault Isolation</td>
                        <td>One bug or exception crashes the entire app</td>
                        <td>Failure stays within one service boundary</td>
                        <td><span class="badge badge-success">Micro</span></td>
                    </tr>
                    <tr>
                        <td>Performance</td>
                        <td>In-process calls — no network overhead</td>
                        <td>HTTP round-trips between services add latency</td>
                        <td><span class="badge badge-warning">Mono</span></td>
                    </tr>
                    <tr>
                        <td>Team Autonomy</td>
                        <td>Shared repo, merge conflicts, coordinated releases</td>
                        <td>Independent teams, independent deploys</td>
                        <td><span class="badge badge-success">Micro</span></td>
                    </tr>
                    <tr>
                        <td>Data Management</td>
                        <td>ACID transactions trivial across all models</td>
                        <td>Eventual consistency, no cross-service joins</td>
                        <td><span class="badge badge-warning">Mono</span></td>
                    </tr>
                    <tr>
                        <td>Debugging</td>
                        <td>Single stack trace, shared logs</td>
                        <td>Distributed tracing required (Jaeger, Zipkin)</td>
                        <td><span class="badge badge-warning">Mono</span></td>
                    </tr>
                    <tr>
                        <td>Best For</td>
                        <td>Small teams, MVPs, lab projects</td>
                        <td>Large orgs, SaaS, independent scaling needs</td>
                        <td><span class="badge badge-info">Depends</span></td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- ══════════════════════════════════════════════════
             REFLECTION
             ══════════════════════════════════════════════════ --}}
        <div class="card">
            <div class="card-header">
                <h3 style="margin-bottom: 0; font-size: var(--text-sm); font-family: var(--font-body); text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-tertiary);">
                    Reflection
                </h3>
            </div>

            <div style="max-width: 640px; line-height: 1.7;">
                <p>
                    Academe uses a <strong>monolithic architecture with the Repository Pattern (MVCR)</strong> as the preferred design.
                    For a student course management system of this scale, this is the right trade-off — the data model is tightly coupled
                    (students enroll in courses), ACID transactions are critical, and the team size is small.
                    The pattern is embodied in <code>app/Repositories/</code> where each domain
                    (Student, Course, Enrollment) has an interface (<code>StudentRepositoryInterface</code>) and an Eloquent
                    implementation. Controllers never call Eloquent directly — they type-hint the interface, and Laravel's
                    service container injects the concrete class. Form validation is handled before the controller ever executes
                    via <code>app/Http/Requests/StoreStudentRequest.php</code> and <code>StoreEnrollmentRequest.php</code>,
                    keeping controller methods focused on orchestration rather than input sanitisation.
                </p>
                <p>
                    The microservices build in <code>microservices/enrollment-service</code> illustrates where this architecture gains
                    complexity. Every write to <code>POST /api/enrollments</code> requires two outbound HTTP calls —
                    <code>GET http://localhost:8001/api/students/{id}</code> and <code>GET http://localhost:8002/api/courses/{id}</code>
                    — before persisting anything. These calls are wrapped in
                    <code>try/catch (\Illuminate\Http\Client\ConnectionException)</code> blocks to handle 503/504 scenarios.
                    What was a single in-process Eloquent query in the monolith becomes a distributed transaction with
                    network failure modes, timeout budgets, and partial-failure states that each need explicit handling.
                </p>
                <p>
                    <strong>Where the monolith breaks down at scale:</strong>
                    As Academe grows to serve thousands of institutions, a single deployment becomes a liability.
                    A spike in enrollment submissions forces scaling the entire application — including the course catalog and
                    student directory — even though only the enrollment path is under load. A bug in one module can bring down
                    the entire system. Teams working on separate domains must coordinate releases, and database schema changes
                    require application-wide migrations that block other work. These are the points at which the clean repository
                    boundaries in <code>app/Repositories/Interfaces/</code> would guide a natural decomposition into services.
                </p>
                <p>
                    <strong>Where microservices add unnecessary complexity for a project of this size:</strong>
                    For a lab system with three developers and a single SQLite database, the microservices version requires
                    three separate servers, three separate databases, and inter-service HTTP calls that can fail, time out,
                    or return stale data. The enrollment service must validate student and course existence via the network on
                    every write — something the monolith does with a single Eloquent <code>exists()</code> call.
                    Debugging a 503 across three terminal windows is significantly harder than reading a single stack trace.
                    The overhead is real and the benefit — independent scaling — is entirely hypothetical at this scale.
                    Microservices are an organisational solution first; a technical one second.
                </p>
                <p>
                    The Repository Pattern is the bridge between both worlds. Interfaces decouple the application logic from the
                    data layer, making it possible to swap an Eloquent implementation for an HTTP client without changing a
                    single controller line. That is the real lesson here:
                    <strong>architectural patterns that work at one scale prepare you for the next.</strong>
                </p>
            </div>

            <hr class="divider">

            <h4 style="font-family: var(--font-body); font-size: var(--text-sm); font-weight: var(--weight-semibold); color: var(--text-primary); margin-bottom: var(--sp-4);">
                Key Takeaways
            </h4>
            <div style="display: flex; flex-direction: column; gap: var(--sp-3);">
                <div style="display: flex; align-items: flex-start; gap: var(--sp-3);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px; flex-shrink: 0; margin-top: 2px;"><polyline points="20 6 9 17 4 12"/></svg>
                    <span style="font-size: var(--text-sm); color: var(--text-secondary);">Monolithic architecture is the correct default for small teams and MVPs.</span>
                </div>
                <div style="display: flex; align-items: flex-start; gap: var(--sp-3);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px; flex-shrink: 0; margin-top: 2px;"><polyline points="20 6 9 17 4 12"/></svg>
                    <span style="font-size: var(--text-sm); color: var(--text-secondary);">Repository Pattern provides clean boundaries that make future decomposition possible.</span>
                </div>
                <div style="display: flex; align-items: flex-start; gap: var(--sp-3);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px; flex-shrink: 0; margin-top: 2px;"><polyline points="20 6 9 17 4 12"/></svg>
                    <span style="font-size: var(--text-sm); color: var(--text-secondary);">Microservices introduce distributed systems complexity — networking, consistency, observability.</span>
                </div>
                <div style="display: flex; align-items: flex-start; gap: var(--sp-3);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px; flex-shrink: 0; margin-top: 2px;"><polyline points="20 6 9 17 4 12"/></svg>
                    <span style="font-size: var(--text-sm); color: var(--text-secondary);">Interface-driven design is the prerequisite for any architectural evolution.</span>
                </div>
                <div style="display: flex; align-items: flex-start; gap: var(--sp-3);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#16A34A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px; flex-shrink: 0; margin-top: 2px;"><polyline points="20 6 9 17 4 12"/></svg>
                    <span style="font-size: var(--text-sm); color: var(--text-secondary);">Choose architecture based on team size, operational maturity, and actual scaling needs — not trends.</span>
                </div>
            </div>
        </div>
    </div>
@endsection
