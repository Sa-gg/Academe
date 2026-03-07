# Lab 2 — Microservices Edge Case Testing Report

---

## 1. Overview

This report documents the edge cases implemented and tested across the three microservices that make up the Academe system: `student-service` (port 8001), `course-service` (port 8002), and `enrollment-service` (port 8003).

Edge cases in distributed systems are critical because failures do not propagate as cleanly as they do in a monolithic application. In a monolith, a missing record throws a `ModelNotFoundException` that is caught by the global exception handler and returns a uniform 404. In a microservices architecture, the same missing record might be on a different server that could be down, slow, or returning malformed data — each scenario requiring different error codes and consumer handling strategies.

The goal of this testing phase was to exercise every failure mode defined in the API contract and confirm that each service returns the correct HTTP status code and JSON error body.

---

## 2. Edge Cases Implemented

### 400 — Validation Error

**What triggers it:**  
A `POST` request is made with a missing required field or an invalid field value (e.g., a non-email string in the `email` field).

**Which service handles it:**  
`student-service` (POST /api/students) and `enrollment-service` (POST /api/enrollments).

**HTTP status code returned:** `400 Bad Request`

**JSON error format:**
```json
{
  "error": "VALIDATION_ERROR",
  "message": "The name field is required."
}
```

**Implementation:** `Validator::make()` is called in the controller. If `$validator->fails()`, the first error message is returned with a 400 status. The standard Laravel form request was intentionally bypassed here to control the status code (Laravel's default for failed form requests is 422).

**curl example:**
```bash
curl -i -X POST http://localhost:8001/api/students \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com"}'
```

---

### 404 — Resource Not Found

**What triggers it:**  
A `GET` or `POST` request references an ID that does not exist in the target service's database.

**Which service handles it:**  
All three services for their own resources. Enrollment service also returns `STUDENT_NOT_FOUND` (404) and `COURSE_NOT_FOUND` (404) when the referenced entities don't exist in their respective services.

**HTTP status code returned:** `404 Not Found`

**JSON error formats:**
```json
{ "error": "NOT_FOUND",          "message": "Student with id 9999 does not exist" }
{ "error": "STUDENT_NOT_FOUND",  "message": "Student with id 9999 does not exist" }
{ "error": "COURSE_NOT_FOUND",   "message": "Course with id 9999 does not exist" }
```

**curl example:**
```bash
curl -i http://localhost:8001/api/students/9999
```

---

### 409 — Duplicate Enrollment

**What triggers it:**  
A `POST /api/enrollments` request is made for a `student_id` and `course_id` combination that already exists in the `enrollments` table.

**Which service handles it:**  
`enrollment-service`.

**HTTP status code returned:** `409 Conflict`

**JSON error format:**
```json
{
  "error": "DUPLICATE_ENROLLMENT",
  "message": "This student is already enrolled in this course"
}
```

**Implementation:** After passing validation and both 404 checks, the controller runs:
```php
Enrollment::where('student_id', $request->student_id)
           ->where('course_id',  $request->course_id)
           ->exists();
```
If true, a 409 is returned before any write.

**curl example:**
```bash
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":1,"course_id":1}'
# (run twice — second call returns 409)
```

---

### 503 — Dependency Service Unavailable

**What triggers it:**  
The `enrollment-service` attempts to call `student-service` or `course-service` but the target server is not running or refuses the connection.

**Which service handles it:**  
`enrollment-service` (in `store()`, `show()`, and `index()`).

**HTTP status code returned:** `503 Service Unavailable`

**JSON error format:**
```json
{
  "error": "SERVICE_UNAVAILABLE",
  "message": "A dependency service is not responding"
}
```

**Implementation:** All `Http::timeout(5)->get(...)` calls in the enrollment controller are wrapped in `try/catch (\Illuminate\Http\Client\ConnectionException)`. The catch block inspects the exception message to distinguish between a connection refusal (503) and a timeout (504).

**curl example:**
```bash
# Stop student-service first, then:
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":1,"course_id":1}'
```

---

### 504 — Dependency Service Timeout

**What triggers it:**  
The `enrollment-service` makes an HTTP call to `student-service` or `course-service`, but the target takes longer than 5 seconds to respond.

**Which service handles it:**  
`enrollment-service`.

**HTTP status code returned:** `504 Gateway Timeout`

**JSON error format:**
```json
{
  "error": "GATEWAY_TIMEOUT",
  "message": "Dependency service took too long to respond"
}
```

**Implementation:** The same `ConnectionException` catch block distinguishes timeouts by checking `str_contains($e->getMessage(), 'timed out')`. When true, HTTP 504 and `GATEWAY_TIMEOUT` are returned instead of 503.

**curl example:**
```bash
# Add sleep(10) to StudentController::show() to simulate slowness, then:
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":1,"course_id":1}'
```

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

**When a dependency times out:**  
The Laravel `Http` facade is configured with a 5-second timeout (`Http::timeout(5)`). If the TCP connection is established but the response exceeds 5 seconds, a `ConnectionException` with "timed out" in the message is thrown.

---

## 4. Reflection

**The hardest edge case to implement: 503 vs 504 distinction**

Both connection-refused and timeout scenarios throw a `\Illuminate\Http\Client\ConnectionException`. Distinguishing between them requires inspecting the exception message string, which is fragile. A production system would use a dedicated circuit-breaker library (e.g., `ganesha` for PHP) and expose a `/health` endpoint on each service. For this lab, `str_contains($e->getMessage(), 'timed out')` is sufficient to demonstrate the concept.

**Why distributed error handling is harder than monolithic:**

In the monolith, a failed database query throws an exception that bubbles up to a single handler in `app/Exceptions/Handler.php`. Every possible error path is in-process and covered by PHP's exception hierarchy. In a microservices setup, a single user request to `POST /api/enrollments` can fail at four different points: validation (local), student check (remote), course check (remote), or the final write (local). Each remote call has its own failure mode (connection error, timeout, unexpected status code) that must be handled distinctly. The cognitive overhead of tracking what can fail where, and ensuring consistent error formats across all services, is non-trivial.

**What would make this system more resilient in production:**

1. **Circuit breaker pattern** (e.g., `ackintosh/ganesha`) — stop calling a service that keeps failing; fail fast immediately.
2. **Health check endpoints** (`GET /health`) on each service so a health-monitoring layer can detect failures before users do.
3. **Retry with exponential backoff** for transient connection errors.
4. **Distributed tracing** (OpenTelemetry) so a single request across three services can be traced as one unit in logs.
5. **Dead-letter queues** — move to an event-driven model for enrollment creation so that a temporarily down service doesn't lose writes.

---

## 5. How to Run

Open three separate terminal windows from the `sar-lab1` directory:

```bash
# Terminal 1 — Student Service
php artisan serve --port=8001 --chdir=microservices/student-service

# Terminal 2 — Course Service
php artisan serve --port=8002 --chdir=microservices/course-service

# Terminal 3 — Enrollment Service
php artisan serve --port=8003 --chdir=microservices/enrollment-service
```

Run tests from `microservices/tests/curl-tests.md`.
