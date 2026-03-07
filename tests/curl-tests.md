# Microservices curl Test Suite

All commands use `-i` to show response headers. Run all three services first:

```bash
php artisan serve --port=8001 --chdir=microservices/student-service
php artisan serve --port=8002 --chdir=microservices/course-service
php artisan serve --port=8003 --chdir=microservices/enrollment-service
```

---

## 1. Happy Path

### GET all students
```bash
curl -i http://localhost:8001/api/students
```
**Expected: HTTP 200**
```json
{"data":[{"id":1,"name":"Juan dela Cruz","email":"juan@example.com",...},...],"message":"success"}
```

---

### GET all courses
```bash
curl -i http://localhost:8002/api/courses
```
**Expected: HTTP 200**
```json
{"data":[{"id":1,"title":"Introduction to Programming","description":"..."},...],"message":"success"}
```

---

### POST valid student
```bash
curl -i -X POST http://localhost:8001/api/students \
  -H "Content-Type: application/json" \
  -d '{"name":"Test Student","email":"test@example.com"}'
```
**Expected: HTTP 201**
```json
{"data":{"id":4,"name":"Test Student","email":"test@example.com","created_at":"...","updated_at":"..."},"message":"created"}
```

---

### PUT update student
```bash
curl -i -X PUT http://localhost:8001/api/students/1 \
  -H "Content-Type: application/json" \
  -d '{"name":"Updated Name","email":"updated@example.com"}'
```
**Expected: HTTP 200**
```json
{"data":{"id":1,"name":"Updated Name","email":"updated@example.com",...},"message":"updated"}
```

---

### POST valid course
```bash
curl -i -X POST http://localhost:8002/api/courses \
  -H "Content-Type: application/json" \
  -d '{"title":"New Course","description":"A new course description"}'
```
**Expected: HTTP 201**
```json
{"data":{"id":4,"title":"New Course","description":"A new course description",...},"message":"created"}
```

---

### PUT update course
```bash
curl -i -X PUT http://localhost:8002/api/courses/1 \
  -H "Content-Type: application/json" \
  -d '{"title":"Updated Title","description":"Updated description"}'
```
**Expected: HTTP 200**
```json
{"data":{"id":1,"title":"Updated Title","description":"Updated description",...},"message":"updated"}
```

---

### POST valid enrollment
```bash
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":1,"course_id":1}'
```
**Expected: HTTP 201**
```json
{"data":{"id":1,"student_id":1,"course_id":1,"created_at":"...","updated_at":"..."},"message":"created"}
```

---

### GET enrollment by ID
```bash
curl -i http://localhost:8003/api/enrollments/1
```
**Expected: HTTP 200**
```json
{"data":{"id":1,"student":{"id":1,"name":"Juan dela Cruz",...},"course":{"id":1,"title":"Introduction to Programming",...},"enrolled_at":"..."},"message":"success"}
```

---

### GET enrollments by student
```bash
curl -i http://localhost:8003/api/enrollments/student/1
```
**Expected: HTTP 200**
```json
{"data":[{"id":1,"student":{...},"course":{...},"enrolled_at":"..."}],"message":"success"}
```

---

### DELETE enrollment
```bash
curl -i -X DELETE http://localhost:8003/api/enrollments/1
```
**Expected: HTTP 200**
```json
{"data":null,"message":"deleted"}
```

---

### DELETE student
```bash
curl -i -X DELETE http://localhost:8001/api/students/4
```
**Expected: HTTP 200**
```json
{"data":null,"message":"deleted"}
```

---

### DELETE course
```bash
curl -i -X DELETE http://localhost:8002/api/courses/4
```
**Expected: HTTP 200**
```json
{"data":null,"message":"deleted"}
```

---

## 2. Validation Errors (400)

### POST student — missing name
```bash
curl -i -X POST http://localhost:8001/api/students \
  -H "Content-Type: application/json" \
  -d '{"email":"no-name@example.com"}'
```
**Expected: HTTP 400**
```json
{"error":"VALIDATION_ERROR","message":"The name field is required."}
```

---

### POST student — missing email
```bash
curl -i -X POST http://localhost:8001/api/students \
  -H "Content-Type: application/json" \
  -d '{"name":"No Email"}'
```
**Expected: HTTP 400**
```json
{"error":"VALIDATION_ERROR","message":"The email field is required."}
```

---

### POST student — invalid email format
```bash
curl -i -X POST http://localhost:8001/api/students \
  -H "Content-Type: application/json" \
  -d '{"name":"Bad Email","email":"not-an-email"}'
```
**Expected: HTTP 400**
```json
{"error":"VALIDATION_ERROR","message":"The email field must be a valid email address."}
```

---

### POST enrollment — missing student_id
```bash
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"course_id":1}'
```
**Expected: HTTP 400**
```json
{"error":"VALIDATION_ERROR","message":"The student id field is required."}
```

---

### POST enrollment — missing course_id
```bash
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":1}'
```
**Expected: HTTP 400**
```json
{"error":"VALIDATION_ERROR","message":"The course id field is required."}
```

---

## 3. Not Found (404)

### GET student that does not exist
```bash
curl -i http://localhost:8001/api/students/9999
```
**Expected: HTTP 404**
```json
{"error":"NOT_FOUND","message":"Student with id 9999 does not exist"}
```

---

### GET course that does not exist
```bash
curl -i http://localhost:8002/api/courses/9999
```
**Expected: HTTP 404**
```json
{"error":"NOT_FOUND","message":"Course with id 9999 does not exist"}
```

---

### GET enrollment that does not exist
```bash
curl -i http://localhost:8003/api/enrollments/9999
```
**Expected: HTTP 404**
```json
{"error":"NOT_FOUND","message":"Enrollment with id 9999 does not exist"}
```

---

### PUT nonexistent student
```bash
curl -i -X PUT http://localhost:8001/api/students/9999 \
  -H "Content-Type: application/json" \
  -d '{"name":"Ghost"}'
```
**Expected: HTTP 404**
```json
{"error":"NOT_FOUND","message":"Student with id 9999 does not exist"}
```

---

### DELETE nonexistent student
```bash
curl -i -X DELETE http://localhost:8001/api/students/9999
```
**Expected: HTTP 404**
```json
{"error":"NOT_FOUND","message":"Student with id 9999 does not exist"}
```

---

### DELETE nonexistent enrollment
```bash
curl -i -X DELETE http://localhost:8003/api/enrollments/9999
```
**Expected: HTTP 404**
```json
{"error":"NOT_FOUND","message":"Enrollment with id 9999 does not exist"}
```

---

### POST enrollment — nonexistent student_id
```bash
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":9999,"course_id":1}'
```
**Expected: HTTP 404**
```json
{"error":"STUDENT_NOT_FOUND","message":"Student with id 9999 does not exist"}
```

---

### POST enrollment — nonexistent course_id
```bash
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":1,"course_id":9999}'
```
**Expected: HTTP 404**
```json
{"error":"COURSE_NOT_FOUND","message":"Course with id 9999 does not exist"}
```

---

## 4. Duplicate (409)

### POST duplicate enrollment (same student + same course)
```bash
# First create the enrollment (if not done already)
curl -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":1,"course_id":1}'

# Then try to enroll the same student in the same course again
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":1,"course_id":1}'
```
**Expected: HTTP 409**
```json
{"error":"DUPLICATE_ENROLLMENT","message":"This student is already enrolled in this course"}
```

---

### POST student with duplicate email
```bash
curl -i -X POST http://localhost:8001/api/students \
  -H "Content-Type: application/json" \
  -d '{"name":"Second User","email":"juan@example.com"}'
```
**Expected: HTTP 409**
```json
{"error":"DUPLICATE_EMAIL","message":"A student with this email already exists"}
```

---

## 5. Dependency Down (503)

### POST enrollment while student-service is offline

Stop the student-service (`php artisan serve --port=8001`) then run:

```bash
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":1,"course_id":1}'
```
**Expected: HTTP 503**
```json
{"error":"SERVICE_UNAVAILABLE","message":"A dependency service is not responding"}
```

---

### POST enrollment while course-service is offline

Stop the course-service (`php artisan serve --port=8002`) then run:

```bash
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":1,"course_id":1}'
```
**Expected: HTTP 503**
```json
{"error":"SERVICE_UNAVAILABLE","message":"A dependency service is not responding"}
```

---

## 6. Timeout (504)

### POST enrollment while a dependency responds slowly (> 5 seconds)

Introduce an artificial delay in student-service (e.g. `sleep(10)` in `StudentController::show()`), then:

```bash
curl -i -X POST http://localhost:8003/api/enrollments \
  -H "Content-Type: application/json" \
  -d '{"student_id":1,"course_id":1}'
```
**Expected: HTTP 504**
```json
{"error":"GATEWAY_TIMEOUT","message":"Dependency service took too long to respond"}
```
