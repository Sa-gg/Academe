# Reflection

> **Subject:** System Architecture and Integration 2
> **Section:** BIST 3B
> **Members:** Sagum, Patrick R. · Henson, Princess Terana Caram Rasonable · Gargarita, Trisha Faith Casiano · Mogat, Ela Mae Trojillo · Tibo-oc, Paul Felippe Gelle

---

## What we learned

### 1. Monolith-first is easier to reason about

Starting with the monolithic version gave us a solid understanding of the domain model — students, courses, and enrollments with their relationships. The MVCR pattern kept things organized, and having a single database made data integrity straightforward with foreign keys and Eloquent relationships.

### 2. Splitting into microservices exposes hidden assumptions

When we moved to three independent services, we realized how many assumptions the monolith made about data being "just there." The enrollment service could no longer do `exists:students,id` in a validation rule — it had to make an HTTP call to a different server. This forced us to think about what happens when that server is down, slow, or returns unexpected data.

### 3. Error handling in distributed systems is fundamentally different

In the monolith, errors are PHP exceptions that bubble up through a single call stack. In microservices, a "student not found" error starts as a 404 JSON response from the student service, gets interpreted by the enrollment service's HTTP client, and must be translated into a meaningful error for the end user. The cognitive overhead of tracking error origins and propagation paths is significant.

### 4. The 503 vs 504 distinction is subtle but important

Both connection-refused and timeout scenarios throw the same `ConnectionException` in Laravel's HTTP client. We had to inspect the exception message string to tell them apart. This feels fragile, and in production we would use proper circuit-breaker libraries and health-check endpoints instead.

### 5. The backend switch was a good exercise in abstraction

Implementing the `APP_BACKEND` toggle showed us that controllers shouldn't care where data comes from. Whether the data is fetched from a local SQLite database or an HTTP API, the controller branches once at the top of each method and the rest of the logic stays the same. This is the same principle behind the Repository pattern — abstracting the data source.

---

## Challenges we faced

1. **SQLite path configuration on Windows** — Laravel's SQLite driver requires an absolute path. Relative paths silently create a new empty database in the wrong directory.

2. **Blade views expecting objects** — When data comes from the microservice HTTP client, it arrives as associative arrays. We had to cast everything to `(object)` in the service clients so Blade's `$student->name` syntax works the same as with Eloquent models.

3. **Route ordering for `/enrollments/student/{id}`** — This route had to be registered before the `{id}` wildcard route, otherwise Laravel would try to match "student" as an enrollment ID.

4. **Cascade deletes across service boundaries** — In the monolith, deleting a student also deletes their enrollments in the same transaction. In microservices, deleting a student from the student service leaves orphaned enrollment records in the enrollment service. We documented this as an intentional architectural trade-off.

---

## What we would do differently in production

1. Use an event-driven architecture (message queue) for cross-service data consistency instead of synchronous HTTP.
2. Add circuit breakers to prevent cascade failures when a dependency is down.
3. Implement a `/health` endpoint on each service for monitoring.
4. Use distributed tracing (OpenTelemetry) to follow a request across all three services.
5. Add retry logic with exponential backoff for transient network failures.
6. Use a proper database migration strategy and not SQLite for production workloads.
