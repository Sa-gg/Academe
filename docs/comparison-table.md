# Monolithic vs Microservices Comparison

> **Subject:** System Architecture and Integration 2
> **Section:** BIST 3B
> **Members:** Sagum, Patrick R. · Henson, Princess Terana Caram Rasonable · Gargarita, Trisha Faith Casiano · Mogat, Ela Mae Trojillo · Tibo-oc, Paul Felippe Gelle

---

| Aspect | Monolithic (`academe/`) | Microservices (`microservices/`) |
|--------|------------------------|----------------------------------|
| **Codebase** | Single Laravel project | Three separate Laravel projects |
| **Database** | One shared SQLite file | Three independent SQLite files |
| **Deployment** | One server, one process | Three servers, three processes |
| **Port** | 8000 | 8001, 8002, 8003 |
| **Inter-service communication** | Direct PHP method calls | Synchronous HTTP (Laravel `Http` facade) |
| **Data consistency** | DB-level foreign keys + Eloquent relationships | Application-level validation via HTTP calls |
| **Cascade deletes** | Repository calls `$model->enrollments()->delete()` before deleting the parent | Each service only deletes its own records; no cross-service cascade |
| **Error handling** | Laravel exception handler catches `ModelNotFoundException` → 404 | Each controller catches HTTP response codes + `ConnectionException` → 400/404/409/503/504 |
| **Response format** | Blade views (HTML) | JSON API (`{"data":...,"message":"..."}`) |
| **Validation** | `FormRequest` classes with conditional rules | `Validator::make()` in controllers |
| **Duplicate checks** | `unique:students` rule in `StoreStudentRequest` | `Student::where('email', ...)->exists()` in controller |
| **FK checks on enrollment** | `exists:students,id` / `exists:courses,id` rules | HTTP GET to student-service / course-service |
| **Failure modes** | Database errors only | 503 (service down), 504 (timeout), plus database errors |
| **Timeout handling** | N/A (in-process) | `Http::timeout(5)` + `ConnectionException` catch |
| **Scalability** | Scale entire app | Scale each service independently |
| **Complexity** | Lower — single codebase and deploy | Higher — three codebases, HTTP glue, distributed error handling |
| **Development speed** | Faster for small teams | Slower initial setup, better for parallel team work |
| **Testing** | PHPUnit against one app | curl against each service endpoint independently |

---

## Key Architectural Trade-offs

### 1. Data Integrity

The monolith guarantees referential integrity through database foreign keys and Eloquent `exists:` validation rules. If a student is deleted, their enrollments are cascade-deleted in the same database transaction.

In microservices, the enrollment service only stores `student_id` and `course_id` as plain integers — there are no foreign key constraints. Integrity is checked at write time via HTTP calls, but there is no mechanism to automatically remove orphaned enrollments if a student is deleted from the student service.

### 2. Performance

The monolith resolves all data in one process with zero network overhead. A single SQL query with Eloquent eager loading (`with(['student', 'course'])`) fetches an enrollment and its related data.

In microservices, fetching an enrollment requires three steps: (1) read from local DB, (2) HTTP GET to student-service, (3) HTTP GET to course-service. Each network call adds latency and introduces failure modes.

### 3. Fault Isolation

If the monolith's database crashes, everything stops. There is no partial availability.

In microservices, a failure in the student service does not stop the course service. The enrollment service degrades gracefully — reads that require enrichment return 503, but the course listing at `:8002/api/courses` remains unaffected.

### 4. Backend Switch

The `APP_BACKEND` toggle demonstrates that from the controller's perspective, the data source is abstracted. Repository interfaces and service clients share the same conceptual API (all, find, create, update, delete). The controller branches on `config('backend.mode')` and calls the appropriate implementation.

This pattern shows that the choice between monolith and microservices is an infrastructure decision — the business logic (what data to validate, what to return) stays the same.
