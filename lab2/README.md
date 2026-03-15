# Lab 2 — Microservices Edge Case Testing
### ITSAR2 313 – System Architecture and Integration 2
### BIST 3B

> The three microservices tested in this lab are the same
> services built in Lab 1. Source code lives in:
> lab1/microservices/
>
> lab2/services/ is a standalone copy for isolated testing.
> Use lab2/services/ for all Lab 2 testing.

## Members

| # | Name |
|---|------|
| 1 | Sagum, Patrick Ruiz |
| 2 | Henson, Princess Terana Caram Rasonable |
| 3 | Gargarita, Trisha Faith Casiano |
| 4 | Mogat, Ela Mae Trojillo |
| 5 | Tibo-oc, Paul Felippe Gelle |

## GitHub

> **Repository (Lab 2):** [lab2/](./)

## Services

| Service | Port | Database |
|---------|------|----------|
| student-service | 8001 | database/students.sqlite |
| course-service | 8002 | database/courses.sqlite |
| enrollment-service | 8003 | database/enrollments.sqlite |

## Quick Start (Recommended)

From repository root:

```bash
bash scripts/lab2/setup.sh
```

This performs a **fresh setup** (installs dependencies and resets databases using `migrate:fresh`).

Then open 3 terminals and run:

```bash
bash scripts/lab2/serve.sh student
bash scripts/lab2/serve.sh course
bash scripts/lab2/serve.sh enrollment
```

All services are then ready for the curl commands in `lab2/tests/curl-tests.md`.

## Setup & Run

> For quick professor testing, use **Quick Start** above.

Terminal 1:
```bash
cd lab2/services/student-service
composer install
touch database/students.sqlite
php artisan migrate --seed
php artisan serve --port=8001
```

Terminal 2:
```bash
cd lab2/services/course-service
composer install
touch database/courses.sqlite
php artisan migrate --seed
php artisan serve --port=8002
```

Terminal 3:
```bash
cd lab2/services/enrollment-service
composer install
touch database/enrollments.sqlite
php artisan migrate
php artisan serve --port=8003
```

All three must be running before executing tests.

## Running Edge Case Tests

Terminal 4:
```bash
cd lab2/tests
bash run-tests.sh
```

Evidence output saved to: lab2/docs/evidence/

## Deliverables

| Item | Location |
|------|----------|
| Source code | lab2/services/ |
| curl test commands | lab2/tests/curl-tests.md |
| Edge case report | lab2/docs/report.md |
| Formal report (PDF) | lab2/docs/lab2-report.pdf |
| Source report (DOCX) | lab2/docs/lab2-report.docx |
| Evidence files | lab2/docs/evidence/ |

## Requirements

PHP 8.2+, Composer, curl, bash

> Note: The academe frontend (lab1/academe/) is NOT
> required for Lab 2. The three services run and are
> tested independently via curl.
