# Lab 2 Sequential curl Test Suite

Tests are ordered so each step has the required data available for the next step.

---

## Pre-conditions (Reset Before Every Run)

```bash
cd lab2/services/student-service && php artisan migrate:fresh --seed
cd ../course-service && php artisan migrate:fresh --seed
cd ../enrollment-service && php artisan migrate:fresh
```

Baseline after reset:
- students: IDs 1–3 seeded (Juan dela Cruz, Maria Santos, Pedro Reyes)
- courses: IDs 1–3 seeded (Introduction to Programming, Web Development, Database Systems)
- enrollments: empty table

---

## Service Startup

```bash
# Terminal 1
cd lab2/services/student-service && php artisan serve --port=8001

# Terminal 2
cd lab2/services/course-service && php artisan serve --port=8002

# Terminal 3
cd lab2/services/enrollment-service && php artisan serve --port=8003
```

Screenshot required: Terminal 1, 2, and 3 startup banners

---

## Sequential Test Steps (Terminal 4)

---

### Step 1 — GET all students (200)

```bash
curl -i http://localhost:8001/api/students
```

Output: HTTP 200
```json
{"data":[{"id":1,"name":"Juan dela Cruz","email":"juan@example.com","created_at":"2026-03-15T03:46:17.000000Z","updated_at":"2026-03-15T03:46:17.000000Z"},{"id":2,"name":"Maria Santos","email":"maria@example.com","created_at":"2026-03-15T03:46:17.000000Z","updated_at":"2026-03-15T03:46:17.000000Z"},{"id":3,"name":"Pedro Reyes","email":"pedro@example.com","created_at":"2026-03-15T03:46:17.000000Z","updated_at":"2026-03-15T03:46:17.000000Z"}],"message":"success"}
```

Screenshot required: Terminal 4, Step 1

---

### Step 2 — GET all courses (200)

```bash
curl -i http://localhost:8002/api/courses
```

Output: HTTP 200
```json
{"data":[{"id":1,"title":"Introduction to Programming","description":"Learn the basics of programming concepts and logic.","created_at":"2026-03-15T03:46:19.000000Z","updated_at":"2026-03-15T03:46:19.000000Z"},{"id":2,"title":"Web Development","description":"Build modern web applications using current technologies.","created_at":"2026-03-15T03:46:19.000000Z","updated_at":"2026-03-15T03:46:19.000000Z"},{"id":3,"title":"Database Systems","description":"Understand database design, SQL, and data management.","created_at":"2026-03-15T03:46:19.000000Z","updated_at":"2026-03-15T03:46:19.000000Z"}],"message":"success"}
```

Screenshot required: Terminal 4, Step 2

---

### Step 3 — POST student (201)

```bash
curl -i -X POST http://localhost:8001/api/students \
  -H "Content-Type: application/json" \
  -d '{"name":"Sequential Student","email":"seq.student@example.com"}'
```

Output: HTTP 201
```json
{"data":{"name":"Sequential Student","email":"seq.student@example.com","updated_at":"2026-03-15T04:05:10.000000Z","created_at":"2026-03-15T04:05:10.000000Z","id":4},"message":"created"}
```

Screenshot required: Terminal 4, Step 3

---

### Step 4 — GET created student by ID (200)

```bash
curl -i http://localhost:8001/api/students/4
```

Output: HTTP 200
```json
{"data":{"id":4,"name":"Sequential Student","email":"seq.student@example.com","created_at":"2026-03-15T04:05:10.000000Z","updated_at":"2026-03-15T04:05:10.000000Z"},"message":"success"}
```

Screenshot required: Terminal 4, Step 4

---

### Step 5 — PUT update student (200)

```bash
curl -i -X PUT http://localhost:8001/api/students/4 \
  -H "Content-Type: application/json" \
  -d '{"name":"Sequential Student Updated","email":"seq.student.updated@example.com"}'
```

Output: HTTP 200
```json
{"data":{"id":4,"name":"Sequential Student Updated","email":"seq.student.updated@example.com","created_at":"2026-03-15T04:05:10.000000Z","updated_at":"2026-03-15T04:05:30.000000Z"},"message":"updated"}
```

Screenshot required: Terminal 4, Step 5

---

### Step 6 — POST course (201)

```bash
curl -i -X POST http://localhost:8002/api/courses \
  -H "Content-Type: application/json" \
  -d '{"title":"Sequential Course","description":"Course used for ordered testing"}'
```

Output: HTTP 201
```json
{"data":{"title":"Sequential Course","description":"Course used for ordered testing","updated_at":"2026-03-15T04:05:45.000000Z","created_at":"2026-03-15T04:05:45.000000Z","id":4},"message":"created"}
```

Screenshot required: Terminal 4, Step 6

---

### Step 7 — GET created course by ID (200)

```bash
curl -i http://localhost:8002/api/courses/4
```

Output: HTTP 200
```json
{"data":{"id":4,"title":"Sequential Course","description":"Course used for ordered testing","created_at":"2026-03-15T04:05:45.000000Z","updated_at":"2026-03-15T04:05:45.000000Z"},"message":"success"}
```

Screenshot required: Terminal 4, Step 7

---

### Step 8 — PUT update course (200)

```bash
curl -i -X PUT http://localhost:8002/api/courses/4 \
  -H "Content-Type: application/json" \
  -d '{"title":"Sequential Course Updated","description":"Updated for sequential test"}'
```

Output: HTTP 200
```json
{"data":{"id":4,"title":"Sequential Course Updated","description":"Updated for sequential test","created_at":"2026-03-15T04:05:45.000000Z","updated_at":"2026-03-15T04:06:00.000000Z"},"message":"updated"}
```

Screenshot required: Terminal 4, Step 8

---

### Step 9 — POST enrollment (201)

```bash
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":4,"course_id":4}'
```

Output: HTTP 201
```json
{"data":{"student_id":4,"course_id":4,"updated_at":"2026-03-15T04:06:15.000000Z","created_at":"2026-03-15T04:06:15.000000Z","id":1},"message":"created"}
```

Screenshot required: Terminal 4, Step 9

---

### Step 10 — GET enrollment by ID, enriched (200)

```bash
curl -i http://localhost:8003/api/enrollments/1
```

Output: HTTP 200
```json
{"data":{"id":1,"student":{"id":4,"name":"Sequential Student Updated","email":"seq.student.updated@example.com","created_at":"2026-03-15T04:05:10.000000Z","updated_at":"2026-03-15T04:05:30.000000Z"},"course":{"id":4,"title":"Sequential Course Updated","description":"Updated for sequential test","created_at":"2026-03-15T04:05:45.000000Z","updated_at":"2026-03-15T04:06:00.000000Z"},"enrolled_at":"2026-03-15T04:06:15.000000Z"},"message":"success"}
```

Screenshot required: Terminal 4, Step 10

---

### Step 11 — GET enrollments by student ID (200)

```bash
curl -i http://localhost:8003/api/enrollments/student/4
```

Output: HTTP 200
```json
{"data":[{"id":1,"student":{"id":4,"name":"Sequential Student Updated","email":"seq.student.updated@example.com","created_at":"2026-03-15T04:05:10.000000Z","updated_at":"2026-03-15T04:05:30.000000Z"},"course":{"id":4,"title":"Sequential Course Updated","description":"Updated for sequential test","created_at":"2026-03-15T04:05:45.000000Z","updated_at":"2026-03-15T04:06:00.000000Z"},"enrolled_at":"2026-03-15T04:06:15.000000Z"}],"message":"success"}
```

Screenshot required: Terminal 4, Step 11

---

### Step 12 — Validation: POST student missing name (400)

```bash
curl -i -X POST http://localhost:8001/api/students \
  -H "Content-Type: application/json" \
  -d '{"email":"missing-name@example.com"}'
```

Output: HTTP 400
```json
{"error":"VALIDATION_ERROR","message":"The name field is required."}
```

Screenshot required: Terminal 4, Step 12

---

### Step 13 — Validation: POST student invalid email (400)

```bash
curl -i -X POST http://localhost:8001/api/students \
  -H "Content-Type: application/json" \
  -d '{"name":"Bad Email","email":"not-an-email"}'
```

Output: HTTP 400
```json
{"error":"VALIDATION_ERROR","message":"The email field must be a valid email address."}
```

Screenshot required: Terminal 4, Step 13

---

### Step 14 — Validation: POST enrollment missing course_id (400)

```bash
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":4}'
```

Output: HTTP 400
```json
{"error":"VALIDATION_ERROR","message":"The course id field is required."}
```

Screenshot required: Terminal 4, Step 14

---

### Step 15 — Not Found: GET nonexistent student (404)

```bash
curl -i http://localhost:8001/api/students/9999
```

Output: HTTP 404
```json
{"error":"NOT_FOUND","message":"Student with id 9999 does not exist"}
```

Screenshot required: Terminal 4, Step 15

---

### Step 16 — Not Found: GET nonexistent course (404)

```bash
curl -i http://localhost:8002/api/courses/9999
```

Output: HTTP 404
```json
{"error":"NOT_FOUND","message":"Course with id 9999 does not exist"}
```

Screenshot required: Terminal 4, Step 16

---

### Step 17 — Not Found: GET nonexistent enrollment (404)

```bash
curl -i http://localhost:8003/api/enrollments/9999
```

Output: HTTP 404
```json
{"error":"NOT_FOUND","message":"Enrollment with id 9999 does not exist"}
```

Screenshot required: Terminal 4, Step 17

---

### Step 18 — Duplicate: POST student with existing email (409)

```bash
curl -i -X POST http://localhost:8001/api/students \
  -H "Content-Type: application/json" \
  -d '{"name":"Duplicate Student","email":"juan@example.com"}'
```

Output: HTTP 409
```json
{"error":"DUPLICATE_EMAIL","message":"A student with this email already exists"}
```

Screenshot required: Terminal 4, Step 18

---

### Step 19 — Duplicate: POST enrollment already enrolled (409)

> Enrollment for student_id:4, course_id:4 was created in Step 9 and still exists.

```bash
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":4,"course_id":4}'
```

Output: HTTP 409
```json
{"error":"DUPLICATE_ENROLLMENT","message":"This student is already enrolled in this course"}
```

Screenshot required: Terminal 4, Step 19

---

### Step 20 — Cross-service Not Found: nonexistent student_id in enrollment (404)

```bash
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":9999,"course_id":4}'
```

Output: HTTP 404
```json
{"error":"STUDENT_NOT_FOUND","message":"Student with id 9999 does not exist"}
```

Screenshot required: Terminal 4, Step 20

---

### Step 21 — Cross-service Not Found: nonexistent course_id in enrollment (404)

```bash
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":4,"course_id":9999}'
```

Output: HTTP 404
```json
{"error":"COURSE_NOT_FOUND","message":"Course with id 9999 does not exist"}
```

Screenshot required: Terminal 4, Step 21

---

### Step 22 — Dependency Down: student-service offline (503)

> Stop student-service in Terminal 1 (Ctrl+C), then run:

```bash
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":4,"course_id":4}'
```

Output: HTTP 503
```json
{"error":"SERVICE_UNAVAILABLE","message":"A dependency service is not responding"}
```

Screenshot required: Terminal 4, Step 22 (and Terminal 1 stopped)

> Restart student-service in Terminal 1 before Step 23.

---

### Step 23 — Timeout: student-service slow response (504)

> Add `sleep(10);` inside `StudentController::show()` before any other logic.
> Restart student-service in Terminal 1, then run:

```bash
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":4,"course_id":4}'
```

Output: HTTP 504
```json
{"error":"GATEWAY_TIMEOUT","message":"Dependency service took too long to respond"}
```

Screenshot required: Terminal 4, Step 23

> Remove `sleep(10);` and restart student-service after capturing evidence.

---

### Step 24 — DELETE enrollment (200)

```bash
curl -i -X DELETE http://localhost:8003/api/enrollments/1
```

Output: HTTP 200
```json
{"data":null,"message":"deleted"}
```

Screenshot required: Terminal 4, Step 24

---

### Step 25 — DELETE student (200)

```bash
curl -i -X DELETE http://localhost:8001/api/students/4
```

Output: HTTP 200
```json
{"data":null,"message":"deleted"}
```

Screenshot required: Terminal 4, Step 25

---

### Step 26 — DELETE course (200)

```bash
curl -i -X DELETE http://localhost:8002/api/courses/4
```

Output: HTTP 200
```json
{"data":null,"message":"deleted"}
```

Screenshot required: Terminal 4, Step 26

---

### Step 27 — Verify cleanup: all deleted resources return 404

```bash
curl -i http://localhost:8001/api/students/4
```

Output: HTTP 404
```json
{"error":"NOT_FOUND","message":"Student with id 4 does not exist"}
```

```bash
curl -i http://localhost:8002/api/courses/4
```

Output: HTTP 404
```json
{"error":"NOT_FOUND","message":"Course with id 4 does not exist"}
```

```bash
curl -i http://localhost:8003/api/enrollments/1
```

Output: HTTP 404
```json
{"error":"NOT_FOUND","message":"Enrollment with id 1 does not exist"}
```

Screenshot required: Terminal 4, Step 27

---

## Summary

| Step | Service | Method | Endpoint | HTTP |
|------|---------|--------|----------|------|
| 1 | student | GET | /api/students | 200 |
| 2 | course | GET | /api/courses | 200 |
| 3 | student | POST | /api/students | 201 |
| 4 | student | GET | /api/students/4 | 200 |
| 5 | student | PUT | /api/students/4 | 200 |
| 6 | course | POST | /api/courses | 201 |
| 7 | course | GET | /api/courses/4 | 200 |
| 8 | course | PUT | /api/courses/4 | 200 |
| 9 | enrollment | POST | /api/enrollments | 201 |
| 10 | enrollment | GET | /api/enrollments/1 | 200 |
| 11 | enrollment | GET | /api/enrollments/student/4 | 200 |
| 12 | student | POST | /api/students | 400 |
| 13 | student | POST | /api/students | 400 |
| 14 | enrollment | POST | /api/enrollments | 400 |
| 15 | student | GET | /api/students/9999 | 404 |
| 16 | course | GET | /api/courses/9999 | 404 |
| 17 | enrollment | GET | /api/enrollments/9999 | 404 |
| 18 | student | POST | /api/students | 409 |
| 19 | enrollment | POST | /api/enrollments | 409 |
| 20 | enrollment | POST | /api/enrollments | 404 |
| 21 | enrollment | POST | /api/enrollments | 404 |
| 22 | enrollment | POST | /api/enrollments | 503 |
| 23 | enrollment | POST | /api/enrollments | 504 |
| 24 | enrollment | DELETE | /api/enrollments/1 | 200 |
| 25 | student | DELETE | /api/students/4 | 200 |
| 26 | course | DELETE | /api/courses/4 | 200 |
| 27 | all | GET | (cleanup verification) | 404 |
