# Academe Microservices

Academe Microservices decomposes the student-course management system into three independent Laravel services that communicate exclusively over HTTP. Each service owns its own database, deploys independently, and fails in isolation. The monolithic Academe frontend switches between its local database and these services by toggling a single environment variable.

---

## Architecture

```
[Browser / Frontend]
        |
[Academe Laravel :8000]  ← APP_BACKEND=microservices in .env
        |
┌───────┼──────────────────────┐
↓       ↓                      ↓
[Student]  [Course]      [Enrollment]
Service    Service        Service
:8001      :8002           :8003
  |          |                 |  ──→  HTTP GET /api/students/{id}
[students  [courses      [enrollments  ──→  HTTP GET /api/courses/{id}
 .sqlite]   .sqlite]      .sqlite]
```

---

## Tech Stack

| Layer        | Technology                         |
|--------------|------------------------------------|
| Framework    | Laravel 10.x                       |
| Architecture | MVC with Repository Pattern (MVCR) |
| Database     | SQLite (one file per service)      |
| PHP Version  | 8.2+                               |
| HTTP Client  | Laravel Http Facade                |

---

## Requirements

- PHP 8.2+
- Composer 2.x
- Node.js 18+ (for Vite asset compilation, optional for API-only use)

---

## Installation

Run the following steps for **each service** (`student-service`, `course-service`, `enrollment-service`):

### 1. Student Service (port 8001)

```bash
cd microservices/student-service
composer install
cp .env.example .env
# Edit .env: set DB_DATABASE to the absolute path to database/students.sqlite
touch database/students.sqlite
php artisan migrate --seed
php artisan serve --port=8001
```

### 2. Course Service (port 8002)

```bash
cd microservices/course-service
composer install
cp .env.example .env
# Edit .env: set DB_DATABASE to the absolute path to database/courses.sqlite
touch database/courses.sqlite
php artisan migrate --seed
php artisan serve --port=8002
```

### 3. Enrollment Service (port 8003)

```bash
cd microservices/enrollment-service
composer install
cp .env.example .env
# Edit .env: set DB_DATABASE to the absolute path to database/enrollments.sqlite
touch database/enrollments.sqlite
php artisan migrate
php artisan serve --port=8003
```

> **Note (Windows):** SQLite requires an absolute path in `.env`.  
> Example: `DB_DATABASE=C:\Users\you\Desktop\sar-lab1\microservices\student-service\database\students.sqlite`

---

## Running All Services

Open three separate terminals:

```bash
# Terminal 1
php artisan serve --port=8001 --chdir=microservices/student-service

# Terminal 2
php artisan serve --port=8002 --chdir=microservices/course-service

# Terminal 3
php artisan serve --port=8003 --chdir=microservices/enrollment-service
```

---

## API Endpoints

### Student Service `:8001`

| Method | Endpoint              | Description          |
|--------|-----------------------|----------------------|
| GET    | /api/students         | List all students    |
| POST   | /api/students         | Create a student     |
| GET    | /api/students/{id}    | Get student by ID    |

### Course Service `:8002`

| Method | Endpoint              | Description         |
|--------|-----------------------|---------------------|
| GET    | /api/courses          | List all courses    |
| GET    | /api/courses/{id}     | Get course by ID    |

### Enrollment Service `:8003`

| Method | Endpoint              | Description              |
|--------|-----------------------|--------------------------|
| GET    | /api/enrollments      | List all enrollments     |
| POST   | /api/enrollments      | Create an enrollment     |
| GET    | /api/enrollments/{id} | Get enrollment by ID     |

---

## Edge Cases Handled

| Status | Scenario                                    | Service              |
|--------|---------------------------------------------|----------------------|
| 400    | Missing or invalid input fields             | Student, Enrollment  |
| 404    | Resource not found by ID                    | All three            |
| 409    | Duplicate email (student)                   | Student              |
| 409    | Student already enrolled in same course     | Enrollment           |
| 503    | Dependency service is unreachable           | Enrollment           |
| 504    | Dependency service timed out (> 5 seconds)  | Enrollment           |

---

## Response Format

All endpoints return JSON in one of these two shapes:

**Success**
```json
{ "data": { ... }, "message": "success" }
```

**Created (HTTP 201)**
```json
{ "data": { ... }, "message": "created" }
```

**Error**
```json
{ "error": "ERROR_CODE", "message": "Human-readable description" }
```

Error codes: `VALIDATION_ERROR`, `NOT_FOUND`, `DUPLICATE_EMAIL`, `STUDENT_NOT_FOUND`, `COURSE_NOT_FOUND`, `DUPLICATE_ENROLLMENT`, `SERVICE_UNAVAILABLE`, `GATEWAY_TIMEOUT`
