# Academe — Student Course Enrollment System

> **Subject:** System Architecture and Integration 2
> **Section:** BIST 3B

| Name | Role |
|------|------|
| Sagum, Patrick Ruiz |  Developer |
| Henson, Princess Terana Caram Rasonable | Developer |
| Gargarita, Trisha Faith Casiano | Developer |
| Mogat, Ela Mae Trojillo | Developer |
| Tibo-oc, Paul Felippe Gelle | Developer |

---

## Project Description

Academe is a student–course enrollment system built twice:

1. **Monolithic** (`academe/`) — a single Laravel 10 application using the MVCR (Model–View–Controller–Repository) pattern.
2. **Microservices** (`microservices/`) — three independent Laravel services communicating over HTTP.

An environment variable toggle (`APP_BACKEND`) lets the monolithic app switch between its local database and the remote microservices at runtime.

---

## Repository Structure

```
sar-lab1/
├── academe/                    # Monolithic Laravel application (port 8000)
│   ├── app/
│   │   ├── Http/Controllers/   # StudentController, CourseController, EnrollmentController
│   │   ├── Models/             # Student, Course, Enrollment
│   │   ├── Repositories/       # Eloquent repositories + interfaces
│   │   └── Services/           # HTTP service clients (microservices mode)
│   ├── config/backend.php      # Backend mode configuration
│   ├── routes/web.php          # Web routes with full CRUD
│   └── resources/views/        # Blade templates
├── microservices/
│   ├── student-service/        # Port 8001 — Students CRUD
│   ├── course-service/         # Port 8002 — Courses CRUD
│   └── enrollment-service/     # Port 8003 — Enrollments + cross-service validation
├── docs/
│   ├── architecture.md         # Architecture diagrams and explanation
│   ├── comparison-table.md     # Monolithic vs Microservices comparison
│   ├── reflection.md           # Team reflection
│   ├── report.md               # Lab 2 edge case testing report
│   └── evidence/               # curl test output evidence (01–06)
├── tests/
│   └── curl-tests.md           # Complete curl test suite
└── README.md                   # This file
```

---

## API Endpoints

### Student Service (port 8001)

| Method | Endpoint | Description | Success | Error Codes |
|--------|----------|-------------|---------|-------------|
| GET | `/api/students` | List all students | 200 | — |
| POST | `/api/students` | Create a student | 201 | 400, 409 |
| GET | `/api/students/{id}` | Get student by ID | 200 | 404 |
| PUT | `/api/students/{id}` | Update student | 200 | 400, 404, 409 |
| DELETE | `/api/students/{id}` | Delete student | 200 | 404 |

### Course Service (port 8002)

| Method | Endpoint | Description | Success | Error Codes |
|--------|----------|-------------|---------|-------------|
| GET | `/api/courses` | List all courses | 200 | — |
| POST | `/api/courses` | Create a course | 201 | 400 |
| GET | `/api/courses/{id}` | Get course by ID | 200 | 404 |
| PUT | `/api/courses/{id}` | Update course | 200 | 400, 404 |
| DELETE | `/api/courses/{id}` | Delete course | 200 | 404 |

### Enrollment Service (port 8003)

| Method | Endpoint | Description | Success | Error Codes |
|--------|----------|-------------|---------|-------------|
| GET | `/api/enrollments` | List all enrollments | 200 | 503, 504 |
| POST | `/api/enrollments` | Create enrollment | 201 | 400, 404, 409, 503, 504 |
| GET | `/api/enrollments/{id}` | Get enrollment by ID | 200 | 404, 503, 504 |
| DELETE | `/api/enrollments/{id}` | Delete enrollment | 200 | 404 |
| GET | `/api/enrollments/student/{id}` | Get enrollments by student | 200 | 503, 504 |

---

## JSON Response Format

All microservice endpoints follow the same JSON structure:

**Success:**
```json
{
  "data": { ... },
  "message": "success" | "created" | "updated" | "deleted"
}
```

**Error:**
```json
{
  "error": "ERROR_CODE",
  "message": "Human-readable description"
}
```

### Error Codes

| Code | HTTP | Description |
|------|------|-------------|
| `VALIDATION_ERROR` | 400 | Missing or invalid request fields |
| `NOT_FOUND` | 404 | Resource does not exist |
| `STUDENT_NOT_FOUND` | 404 | Referenced student does not exist (cross-service) |
| `COURSE_NOT_FOUND` | 404 | Referenced course does not exist (cross-service) |
| `DUPLICATE_EMAIL` | 409 | Email already registered |
| `DUPLICATE_ENROLLMENT` | 409 | Student already enrolled in course |
| `SERVICE_UNAVAILABLE` | 503 | Dependency service is not responding |
| `GATEWAY_TIMEOUT` | 504 | Dependency service took too long (>5s) |

---

## How to Run

### Prerequisites

- PHP 8.1+
- Composer
- SQLite

### 1. Install Dependencies

```bash
cd academe && composer install
cd ../microservices/student-service && composer install
cd ../course-service && composer install
cd ../enrollment-service && composer install
```

### 2. Configure Environment

Each service needs a `.env` file. Copy from `.env.example` and set:

```dotenv
# All services use SQLite
DB_CONNECTION=sqlite
DB_DATABASE=C:/absolute/path/to/database.sqlite
```

For the monolithic app (`academe/.env`), also set:

```dotenv
APP_BACKEND=monolithic
# or
APP_BACKEND=microservices

STUDENT_SERVICE_URL=http://localhost:8001
COURSE_SERVICE_URL=http://localhost:8002
ENROLLMENT_SERVICE_URL=http://localhost:8003
```

### 3. Create Databases and Seed

```bash
# For each service directory:
php artisan migrate --seed
```

### 4. Start Servers

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

### 5. Toggle Backend Mode

Edit `academe/.env` and set `APP_BACKEND`:

```dotenv
APP_BACKEND=monolithic       # Uses local SQLite database
APP_BACKEND=microservices    # Proxies to student/course/enrollment services
```

Then clear the config cache:

```bash
cd academe
php artisan config:clear
```

### 6. Run Tests

See [tests/curl-tests.md](tests/curl-tests.md) for the full test suite.

---

## Documentation

| Document | Description |
|----------|-------------|
| [docs/architecture.md](docs/architecture.md) | Architecture diagrams and patterns |
| [docs/comparison-table.md](docs/comparison-table.md) | Monolithic vs Microservices comparison |
| [docs/reflection.md](docs/reflection.md) | Team reflection on the lab |
| [docs/report.md](docs/report.md) | Edge case testing report |
| [tests/curl-tests.md](tests/curl-tests.md) | curl test commands with expected output |
| [docs/evidence/](docs/evidence/) | curl output evidence files (01–06) |
