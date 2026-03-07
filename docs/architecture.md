# Architecture

> **Subject:** System Architecture and Integration 2
> **Section:** BIST 3B
> **Members:** Sagum, Patrick R. · Henson, Princess Terana Caram Rasonable · Gargarita, Trisha Faith Casiano · Mogat, Ela Mae Trojillo · Tibo-oc, Paul Felippe Gelle

---

## 1. Monolithic Architecture (MVCR)

The monolithic application (`academe/`) follows the **Model–View–Controller–Repository** pattern inside a single Laravel 10 project.

```
┌────────────────────────────────────────────────────────┐
│                    Browser (port 8000)                  │
└──────────────────────────┬─────────────────────────────┘
                           │
┌──────────────────────────▼─────────────────────────────┐
│                     routes/web.php                      │
└──────────────────────────┬─────────────────────────────┘
                           │
┌──────────────────────────▼─────────────────────────────┐
│                      Controllers                        │
│  StudentController · CourseController · EnrollmentCtrl  │
└──────────────────────────┬─────────────────────────────┘
                           │
┌──────────────────────────▼─────────────────────────────┐
│                Repository Interfaces                    │
│  StudentRepositoryInterface · CourseRepositoryInterface │
│              EnrollmentRepositoryInterface              │
└──────────────────────────┬─────────────────────────────┘
                           │
┌──────────────────────────▼─────────────────────────────┐
│               Eloquent Repositories                     │
│  StudentRepository · CourseRepository · EnrollmentRepo  │
└──────────────────────────┬─────────────────────────────┘
                           │
┌──────────────────────────▼─────────────────────────────┐
│             Eloquent Models (SQLite)                    │
│         Student · Course · Enrollment                   │
└────────────────────────────────────────────────────────┘
```

**Key points:**
- All three domain objects share a single SQLite database.
- Repository interfaces are bound in `AppServiceProvider`.
- Controllers receive repository implementations via constructor injection.
- Cascade deletes (e.g., deleting a student removes their enrollments) are handled in the repository layer using Eloquent relationships.

---

## 2. Microservices Architecture

The system is split into three independent Laravel services, each with its own database.

```
┌───────────────┐   ┌───────────────┐   ┌────────────────┐
│ Student Svc   │   │ Course Svc    │   │ Enrollment Svc │
│ :8001         │   │ :8002         │   │ :8003          │
│               │   │               │   │                │
│ GET  /students│   │ GET  /courses │   │ GET  /enroll.  │
│ POST /students│   │ POST /courses │   │ POST /enroll.  │
│ GET  /{id}    │   │ GET  /{id}    │   │ GET  /{id}     │
│ PUT  /{id}    │   │ PUT  /{id}    │   │ DELETE /{id}   │
│ DELETE /{id}  │   │ DELETE /{id}  │   │ GET /student/  │
│               │   │               │   │   {id}         │
│ [SQLite]      │   │ [SQLite]      │   │ [SQLite]       │
└───────────────┘   └───────────────┘   └───────┬────────┘
                                                │
                            HTTP calls ─────────┘
                        ┌───────────┐   ┌───────────┐
                        │ :8001     │   │ :8002     │
                        │ student?  │   │ course?   │
                        └───────────┘   └───────────┘
```

**Key points:**
- Each service owns its own SQLite database and only its domain data.
- The enrollment service validates foreign keys by making synchronous HTTP GET calls to the student and course services before writing.
- No shared database, no message queue, no API gateway.
- `Http::timeout(5)` is used for all cross-service calls. Failures produce 503 (connection refused) or 504 (timeout).

---

## 3. Backend Switch Mechanism

The monolithic app can operate in two modes controlled by `APP_BACKEND` in `.env`:

```
┌───────────────────────────────────────────────────────────┐
│                    Controllers                             │
│                                                           │
│  if (config('backend.mode') === 'microservices')          │
│      → use ServiceClient (HTTP calls to :8001/8002/8003)  │
│  else                                                     │
│      → use Repository (direct Eloquent/SQLite)            │
└───────────────────────────────────────────────────────────┘
```

**Service Clients** (`app/Services/`):
- `StudentServiceClient` — full CRUD via HTTP to `:8001`
- `CourseServiceClient` — full CRUD via HTTP to `:8002`
- `EnrollmentServiceClient` — CRUD + `byStudent()` via HTTP to `:8003`

Each controller method checks `config('backend.mode')` and branches accordingly. The views receive the same data shape regardless of backend mode.

---

## 4. Data Flow: Create Enrollment

### Monolithic mode

```
Browser POST /enrollments {student_id, course_id}
  → EnrollmentController::store()
    → StoreEnrollmentRequest validates (exists:students, exists:courses)
    → EnrollmentRepository::create()
      → Enrollment::create() [single SQLite DB]
    → redirect with success
```

### Microservices mode

```
Browser POST /enrollments {student_id, course_id}
  → EnrollmentController::store()
    → EnrollmentServiceClient::create()
      → POST http://localhost:8003/api/enrollments
        → EnrollmentController (enrollment-service)
          → Validator::make (400 if invalid)
          → GET http://localhost:8001/api/students/{id} (404 if missing, 503/504 if down)
          → GET http://localhost:8002/api/courses/{id} (404 if missing, 503/504 if down)
          → Check duplicate (409 if exists)
          → Enrollment::create() [enrollment-service SQLite]
          → 201 Created
    → redirect with success
```
