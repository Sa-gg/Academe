# Lab 1 — Monolith vs Microservices
### ITSAR2 313 – System Architecture and Integration 2
### BIST 3B

> This lab implements a Student Course System in two architectures:
> a **monolithic** app (Academe) and a **microservices** decomposition,
> both accessible through the same Laravel frontend via a config switch.

## Members

| # | Name |
|---|------|
| 1 | Sagum, Patrick R. |
| 2 | Henson, Princess Terana Caram Rasonable |
| 3 | Gargarita, Trisha Faith Casiano |
| 4 | Mogat, Ela Mae Trojillo |
| 5 | Tibo-oc, Paul Felippe Gelle |

## GitHub

> **Repository (Lab 1):** <!-- TODO: replace with your GitHub link, e.g. https://github.com/username/sar-lab1/tree/main/lab1 -->

---

## Applications

| App | Description | Port |
|-----|-------------|------|
| `academe/` | Laravel frontend + monolithic backend | 8000 |
| `microservices/student-service/` | Student REST API | 8001 |
| `microservices/course-service/` | Course REST API | 8002 |
| `microservices/enrollment-service/` | Enrollment REST API | 8003 |

---

## Option A — Run with Microservices (Primary)

> **This is the primary architecture required by the lab.**

**Terminal 1 — Student Service:**
```bash
cd lab1/microservices/student-service
composer install
cp .env.example .env
php artisan key:generate
touch database/students.sqlite
php artisan migrate --seed
php artisan serve --port=8001
```

**Terminal 2 — Course Service:**
```bash
cd lab1/microservices/course-service
composer install
cp .env.example .env
php artisan key:generate
touch database/courses.sqlite
php artisan migrate --seed
php artisan serve --port=8002
```

**Terminal 3 — Enrollment Service:**
```bash
cd lab1/microservices/enrollment-service
composer install
cp .env.example .env
php artisan key:generate
touch database/enrollments.sqlite
php artisan migrate
php artisan serve --port=8003
```

**Terminal 4 — Frontend (Academe):**
```bash
cd lab1/academe
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
# APP_BACKEND=microservices is already the default in .env.example
php artisan config:clear
php artisan serve
# Visit http://localhost:8000
# Topbar badge shows "⬡ Microservices"
```

---

## Option B — Run Monolithic Only (Optional)

> Provided for architectural comparison only.

```bash
cd lab1/academe
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
# In .env: set APP_BACKEND=monolithic
php artisan config:clear
php artisan serve
# Visit http://localhost:8000
# Topbar badge shows "⬡ Monolithic"
```

### Switching Between Backends

```bash
# Edit lab1/academe/.env
# Change APP_BACKEND=monolithic  OR  APP_BACKEND=microservices
php artisan config:clear
# Refresh browser — topbar badge confirms active backend
```

---

## Microservices API Endpoints

### Student Service (port 8001)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/students` | List all students |
| POST | `/api/students` | Create student |
| GET | `/api/students/{id}` | Get student by ID |
| PUT | `/api/students/{id}` | Update student |
| DELETE | `/api/students/{id}` | Delete student |

### Course Service (port 8002)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/courses` | List all courses |
| POST | `/api/courses` | Create course |
| GET | `/api/courses/{id}` | Get course by ID |
| PUT | `/api/courses/{id}` | Update course |
| DELETE | `/api/courses/{id}` | Delete course |

### Enrollment Service (port 8003)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/enrollments` | List all enrollments |
| POST | `/api/enrollments` | Create enrollment |
| GET | `/api/enrollments/{id}` | Get enrollment by ID (enriched) |
| GET | `/api/enrollments/student/{id}` | Get enrollments by student |
| DELETE | `/api/enrollments/{id}` | Delete enrollment |

---

## Architecture Pattern

The monolithic backend and the Academe frontend both implement the
**Model-View-Controller-Repository (MVCR)** pattern. Controllers delegate
all data access to repository interfaces resolved by Laravel's service
container — swapping backends requires only a config change.

```
APP_BACKEND=microservices  →  repositories call HTTP APIs on ports 8001–8003
APP_BACKEND=monolithic     →  repositories call Eloquent models directly
```

---

## Deliverables

| Item | Location |
|------|----------|
| Monolithic source code | `lab1/academe/` |
| Microservices source code | `lab1/microservices/` |
| Architecture documentation | `lab1/docs/architecture.md` |
| Architecture report (DOCX) | `lab1/docs/architecture.docx` |
| Comparison table | `lab1/docs/comparison-table.md` |
| Reflection | `lab1/docs/reflection.md` |
| Lab 1 formal report | `lab1/docs/lab1-report.docx` |

---

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- NPM
- curl
