# Lab 2 — Microservices Edge Case Testing Report

> **Subject:** System Architecture and Integration 2
> **Section:** BIST 3B
> **Members:** Sagum, Patrick R. · Henson, Princess Terana Caram Rasonable · Gargarita, Trisha Faith Casiano · Mogat, Ela Mae Trojillo · Tibo-oc, Paul Felippe Gelle

---

## 1. Overview

This report documents the edge cases implemented and tested across the three microservices that make up the Academe system: `student-service` (port 8001), `course-service` (port 8002), and `enrollment-service` (port 8003).

Edge cases in distributed systems are critical because failures do not propagate as cleanly as they do in a monolithic application. In a monolith, a missing record throws a `ModelNotFoundException` that is caught by the global exception handler and returns a uniform 404. In a microservices architecture, the same missing record might be on a different server that could be down, slow, or returning malformed data — each scenario requiring different error codes and consumer handling strategies.

The goal of this testing phase was to exercise every failure mode defined in the API contract and confirm that each service returns the correct HTTP status code and JSON error body.

---

## 2. Edge Cases Implemented

### 400 — Validation Error

**What triggers it:**
A `POST` or `PUT` request is made with a missing required field or an invalid field value (e.g., a non-email string in the `email` field).

**Which service handles it:**
`student-service` (POST/PUT /api/students), `course-service` (POST/PUT /api/courses), and `enrollment-service` (POST /api/enrollments).

**HTTP status code returned:** `400 Bad Request`

**JSON error format:**
```json
{
  "error": "VALIDATION_ERROR",
  "message": "The name field is required."
}
```

**Implementation:** `Validator::make()` is called in the controller. If `$validator->fails()`, the first error message is returned with a 400 status.

---

### 404 — Resource Not Found

**What triggers it:**
A `GET`, `PUT`, or `DELETE` request references an ID that does not exist in the target service's database.

**Which service handles it:**
All three services for their own resources. Enrollment service also returns `STUDENT_NOT_FOUND` (404) and `COURSE_NOT_FOUND` (404) when the referenced entities don't exist in their respective services.

**HTTP status code returned:** `404 Not Found`

**JSON error formats:**
```json
{ "error": "NOT_FOUND",          "message": "Student with id 9999 does not exist" }
{ "error": "STUDENT_NOT_FOUND",  "message": "Student with id 9999 does not exist" }
{ "error": "COURSE_NOT_FOUND",   "message": "Course with id 9999 does not exist" }
```

---

### 409 — Duplicate Resource

**What triggers it:**
- `POST /api/students` with an email that already exists → `DUPLICATE_EMAIL`
- `POST /api/enrollments` with a student_id + course_id combination that already exists → `DUPLICATE_ENROLLMENT`

**HTTP status code returned:** `409 Conflict`

**JSON error formats:**
```json
{ "error": "DUPLICATE_EMAIL",      "message": "A student with this email already exists" }
{ "error": "DUPLICATE_ENROLLMENT", "message": "This student is already enrolled in this course" }
```

---

### 503 — Dependency Service Unavailable

**What triggers it:**
The `enrollment-service` attempts to call `student-service` or `course-service` but the target server is not running or refuses the connection.

**HTTP status code returned:** `503 Service Unavailable`

**JSON error format:**
```json
{
  "error": "SERVICE_UNAVAILABLE",
  "message": "A dependency service is not responding"
}
```

**Implementation:** All `Http::timeout(5)->get(...)` calls in the enrollment controller are wrapped in `try/catch (\Illuminate\Http\Client\ConnectionException)`. The catch block inspects the exception message to distinguish between a connection refusal (503) and a timeout (504).

---

### 504 — Dependency Service Timeout

**What triggers it:**
The `enrollment-service` makes an HTTP call to `student-service` or `course-service`, but the target takes longer than 5 seconds to respond.

**HTTP status code returned:** `504 Gateway Timeout`

**JSON error format:**
```json
{
  "error": "GATEWAY_TIMEOUT",
  "message": "Dependency service took too long to respond"
}
```

**Implementation:** The same `ConnectionException` catch block distinguishes timeouts by checking `str_contains($e->getMessage(), 'timed out')`. When true, HTTP 504 and `GATEWAY_TIMEOUT` are returned instead of 503.

---

## 3. Service Communication

The `enrollment-service` is the only service that calls other services. It does not rely on shared database access or a message queue — all cross-service data retrieval is done via synchronous HTTP.

**On write (POST /api/enrollments):**
The controller makes two sequential HTTP GET calls before writing:
1. `GET http://localhost:8001/api/students/{student_id}` — validates the student exists
2. `GET http://localhost:8002/api/courses/{course_id}` — validates the course exists

Both are wrapped in `try/catch (\Illuminate\Http\Client\ConnectionException)` to handle:
- Connection refused → 503 `SERVICE_UNAVAILABLE`
- Timeout after 5 seconds → 504 `GATEWAY_TIMEOUT`

**On read (GET /api/enrollments/{id} and GET /api/enrollments):**
The controller fetches the enrollment from its own database first, then enriches the response by calling student-service and course-service. If a dependency is unreachable during a read, the same 503/504 response is returned.

**When student-service or course-service is unreachable:**
No enrollment is persisted. The enrollment-service fails fast and returns the appropriate error. There is no retry or circuit-breaker logic in this implementation.

---

## 4. Reflection

**The hardest edge case to implement: 503 vs 504 distinction**

Both connection-refused and timeout scenarios throw a `\Illuminate\Http\Client\ConnectionException`. Distinguishing between them requires inspecting the exception message string, which is fragile. A production system would use a dedicated circuit-breaker library and expose a `/health` endpoint on each service.

**Why distributed error handling is harder than monolithic:**

In the monolith, a failed database query throws an exception that bubbles up to a single handler. Every possible error path is in-process and covered by PHP's exception hierarchy. In a microservices setup, a single user request to `POST /api/enrollments` can fail at four different points: validation (local), student check (remote), course check (remote), or the final write (local). Each remote call has its own failure mode that must be handled distinctly.

---

## 5. How to Run

Open four terminal windows from the `sar-lab1` directory:

```bash
# Terminal 1 — Monolithic app
cd academe
php artisan serve --port=8000

# Terminal 2 — Student Service
cd microservices/student-service
php artisan serve --port=8001

# Terminal 3 — Course Service
cd microservices/course-service
php artisan serve --port=8002

# Terminal 4 — Enrollment Service
cd microservices/enrollment-service
php artisan serve --port=8003
```

Run tests from [tests/curl-tests.md](../tests/curl-tests.md).

---

## 6. Evidence

Test output evidence is captured in `docs/evidence/`:

| File | Edge Case | HTTP Code |
|------|-----------|-----------|
| [01-happy-path.txt](evidence/01-happy-path.txt) | Successful CRUD operations | 200, 201 |
| [02-validation-error.txt](evidence/02-validation-error.txt) | Missing/invalid fields | 400 |
| [03-not-found.txt](evidence/03-not-found.txt) | Nonexistent resources | 404 |
| [04-duplicate.txt](evidence/04-duplicate.txt) | Duplicate email/enrollment | 409 |
| [05-dependency-down.txt](evidence/05-dependency-down.txt) | Service offline | 503 |
| [06-timeout.txt](evidence/06-timeout.txt) | Service timeout (>5s) | 504 |
