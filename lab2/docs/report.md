# Lab 2 - Microservices Edge Case Testing Report

> Subject: System Architecture and Integration 2  
> Section: BIST 3B  
> Members: Sagum, Patrick Ruiz; Henson, Princess Terana Caram Rasonable; Gargarita, Trisha Faith Casiano; Mogat, Ela Mae Trojillo; Tibo-oc, Paul Felippe Gelle

---

## 1. Overview

This document reports the full Lab 2 microservices validation for:
- student-service (port 8001)
- course-service (port 8002)
- enrollment-service (port 8003)

The testing objective is complete endpoint and edge-case verification using sequential curl commands that preserve valid data flow from one step to the next.

---

## 2. Test Coverage Matrix

| Category | Student | Course | Enrollment |
|---|---|---|---|
| GET | Yes | Yes | Yes |
| POST | Yes | Yes | Yes |
| PUT | Yes | Yes | N/A |
| DELETE | Yes | Yes | Yes |
| 400 Validation Error | Yes | Yes | Yes |
| 404 Not Found | Yes | Yes | Yes |
| 409 Duplicate | Yes | N/A | Yes |
| 503 Dependency Down | N/A | N/A | Yes |
| 504 Timeout | N/A | N/A | Yes |

---

## 3. Deterministic Pre-Conditions (Required)

Before capturing screenshots, reset the databases in this exact order:

```bash
cd lab2/services/student-service
php artisan migrate:fresh --seed

cd ../course-service
php artisan migrate:fresh --seed

cd ../enrollment-service
php artisan migrate:fresh
```

Expected starting state:
- students: seeded IDs 1..3
- courses: seeded IDs 1..3
- enrollments: empty

This reset guarantees that sequential steps produce expected IDs:
- new student ID: 4
- new course ID: 4
- new enrollment ID: 1

---

## 4. Service Startup

Terminal 1:
```bash
cd lab2/services/student-service
php artisan serve --port=8001
```
Screenshot required: Terminal 1 startup banner

Terminal 2:
```bash
cd lab2/services/course-service
php artisan serve --port=8002
```
Screenshot required: Terminal 2 startup banner

Terminal 3:
```bash
cd lab2/services/enrollment-service
php artisan serve --port=8003
```
Screenshot required: Terminal 3 startup banner

Use Terminal 4 for all curl commands below.

---

## 5. Sequential curl Procedure With Required Screenshots

### Step 1 - GET students (200)
```bash
curl -i http://localhost:8001/api/students
```
Expected: 200 success with 3 seeded rows
Screenshot required: Terminal 4 Step 1

### Step 2 - GET courses (200)
```bash
curl -i http://localhost:8002/api/courses
```
Expected: 200 success with 3 seeded rows
Screenshot required: Terminal 4 Step 2

### Step 3 - POST student (201)
```bash
curl -i -X POST http://localhost:8001/api/students \
  -H "Content-Type: application/json" \
  -d '{"name":"Sequential Student","email":"seq.student@example.com"}'
```
Expected: 201 created (student ID 4)
Screenshot required: Terminal 4 Step 3

### Step 4 - GET created student (200)
```bash
curl -i http://localhost:8001/api/students/4
```
Expected: 200 success
Screenshot required: Terminal 4 Step 4

### Step 5 - PUT student update (200)
```bash
curl -i -X PUT http://localhost:8001/api/students/4 \
  -H "Content-Type: application/json" \
  -d '{"name":"Sequential Student Updated","email":"seq.student.updated@example.com"}'
```
Expected: 200 updated
Screenshot required: Terminal 4 Step 5

### Step 6 - POST course (201)
```bash
curl -i -X POST http://localhost:8002/api/courses \
  -H "Content-Type: application/json" \
  -d '{"title":"Sequential Course","description":"Course used for ordered testing"}'
```
Expected: 201 created (course ID 4)
Screenshot required: Terminal 4 Step 6

### Step 7 - GET created course (200)
```bash
curl -i http://localhost:8002/api/courses/4
```
Expected: 200 success
Screenshot required: Terminal 4 Step 7

### Step 8 - PUT course update (200)
```bash
curl -i -X PUT http://localhost:8002/api/courses/4 \
  -H "Content-Type: application/json" \
  -d '{"title":"Sequential Course Updated","description":"Updated for sequential test"}'
```
Expected: 200 updated
Screenshot required: Terminal 4 Step 8

### Step 9 - POST enrollment (201)
```bash
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":4,"course_id":4}'
```
Expected: 201 created (enrollment ID 1)
Screenshot required: Terminal 4 Step 9

### Step 10 - GET enrollment by ID (200)
```bash
curl -i http://localhost:8003/api/enrollments/1
```
Expected: 200 success with enriched student and course payload
Screenshot required: Terminal 4 Step 10

### Step 11 - GET enrollments by student (200)
```bash
curl -i http://localhost:8003/api/enrollments/student/4
```
Expected: 200 success
Screenshot required: Terminal 4 Step 11

### Step 12 - Validation error student missing name (400)
```bash
curl -i -X POST http://localhost:8001/api/students \
  -H "Content-Type: application/json" \
  -d '{"email":"missing-name@example.com"}'
```
Expected: 400 VALIDATION_ERROR
Screenshot required: Terminal 4 Step 12

### Step 13 - Validation error student invalid email (400)
```bash
curl -i -X POST http://localhost:8001/api/students \
  -H "Content-Type: application/json" \
  -d '{"name":"Bad Email","email":"not-an-email"}'
```
Expected: 400 VALIDATION_ERROR
Screenshot required: Terminal 4 Step 13

### Step 14 - Validation error enrollment missing course_id (400)
```bash
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":4}'
```
Expected: 400 VALIDATION_ERROR
Screenshot required: Terminal 4 Step 14

### Step 15 - Not found student (404)
```bash
curl -i http://localhost:8001/api/students/9999
```
Expected: 404 NOT_FOUND
Screenshot required: Terminal 4 Step 15

### Step 16 - Not found course (404)
```bash
curl -i http://localhost:8002/api/courses/9999
```
Expected: 404 NOT_FOUND
Screenshot required: Terminal 4 Step 16

### Step 17 - Not found enrollment (404)
```bash
curl -i http://localhost:8003/api/enrollments/9999
```
Expected: 404 NOT_FOUND
Screenshot required: Terminal 4 Step 17

### Step 18 - Duplicate student email (409)
```bash
curl -i -X POST http://localhost:8001/api/students \
  -H "Content-Type: application/json" \
  -d '{"name":"Duplicate Student","email":"juan@example.com"}'
```
Expected: 409 DUPLICATE_EMAIL
Screenshot required: Terminal 4 Step 18

### Step 19 - Duplicate enrollment pair (409)
```bash
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":4,"course_id":4}'
```
Expected: 409 DUPLICATE_ENROLLMENT
Screenshot required: Terminal 4 Step 19

### Step 20 - Cross-service student not found (404)
```bash
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":9999,"course_id":4}'
```
Expected: 404 STUDENT_NOT_FOUND
Screenshot required: Terminal 4 Step 20

### Step 21 - Cross-service course not found (404)
```bash
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":4,"course_id":9999}'
```
Expected: 404 COURSE_NOT_FOUND
Screenshot required: Terminal 4 Step 21

### Step 22 - Dependency down (503)
Stop student-service in Terminal 1, then run:

```bash
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":4,"course_id":4}'
```
Expected: 503 SERVICE_UNAVAILABLE
Screenshot required: Terminal 4 Step 22 and Terminal 1 stopped state

Restart student-service before next step.

### Step 23 - Timeout (504)
Temporarily add `sleep(10);` in student-service `show()` before returning response, then run:

```bash
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":4,"course_id":4}'
```
Expected: 504 GATEWAY_TIMEOUT
Screenshot required: Terminal 4 Step 23

Remove `sleep(10);` after this test.

### Step 24 - DELETE enrollment (200)
```bash
curl -i -X DELETE http://localhost:8003/api/enrollments/1
```
Expected: 200 deleted
Screenshot required: Terminal 4 Step 24

### Step 25 - DELETE student (200)
```bash
curl -i -X DELETE http://localhost:8001/api/students/4
```
Expected: 200 deleted
Screenshot required: Terminal 4 Step 25

### Step 26 - DELETE course (200)
```bash
curl -i -X DELETE http://localhost:8002/api/courses/4
```
Expected: 200 deleted
Screenshot required: Terminal 4 Step 26

### Step 27 - Verify deleted resources now return 404
```bash
curl -i http://localhost:8001/api/students/4
curl -i http://localhost:8002/api/courses/4
curl -i http://localhost:8003/api/enrollments/1
```
Expected: 404 for all three checks
Screenshot required: Terminal 4 Step 27

---

## 6. Why This Sequence Is Safe

- New temporary entities (student 4 and course 4) are created early and used for mutation tests.
- Destructive deletes are intentionally moved to final cleanup steps.
- Edge-case tests that should not mutate data (400/404/409/503/504) are executed before cleanup.
- Reset commands guarantee reproducible IDs and expected outputs for documentation.

---

## 7. Evidence Checklist (for DOCX screenshots)

Insert all screenshots in this order:

1. Service startup x3
2. Step 1 to Step 27 terminal outputs
3. Terminal evidence for stopped dependency in Step 22
4. Optional code snippet screenshot showing temporary `sleep(10);` for Step 23

Reference command source: `lab2/tests/curl-tests.md`.
