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

## Stack

| Layer | Technology |
|------|------------|
| Services | Laravel / PHP |
| Database | SQLite |
| Testing | curl |

## Seeded Data

- Student service: 3 seeded students
- Course service: 3 seeded courses
- Enrollment service: fresh empty database before tests

## Prerequisites

- Node.js 18+
- npm
- PHP 8.2+
- Composer
- curl
- Git Bash (only if using the bash script alternative below)

## Quick Start (Windows · Mac · Linux)

From repository root:

```bash
npm install
npm run setup:lab2
npm run serve:lab2
```

This works on **any terminal** (Windows Command Prompt, PowerShell, Git Bash, or Terminal on Mac/Linux).

`setup:lab2` does a **fresh setup**:
- installs PHP dependencies for all 3 services
- copies `.env` files if missing
- regenerates app keys
- recreates all SQLite databases from scratch
- reseeds student and course services

`serve:lab2` uses `concurrently` to start all 3 services in one terminal with color-coded output.

All services are then ready for the curl commands in `lab2/tests/curl-tests.md`.

Why first run can take time:
- Composer must prepare Laravel dependencies for each service
- all three databases are recreated from scratch

Reruns are faster because `vendor/` folders are reused.

## Alternative: Git Bash / Linux / Mac

The bash scripts have progress indicators (`[lab2][1/3]`) if you prefer them:

```bash
bash scripts/lab2/setup.sh
```

Then open 3 terminals:

```bash
bash scripts/lab2/serve.sh student
bash scripts/lab2/serve.sh course
bash scripts/lab2/serve.sh enrollment
```

## Manual Setup (Full Reference)

> For quick professor testing, use **Quick Start** above.

Terminal 1:
```bash
cd lab2/services/student-service
composer install
touch database/students.sqlite
php artisan migrate:fresh --seed
php artisan serve --port=8001
```

Terminal 2:
```bash
cd lab2/services/course-service
composer install
touch database/courses.sqlite
php artisan migrate:fresh --seed
php artisan serve --port=8002
```

Terminal 3:
```bash
cd lab2/services/enrollment-service
composer install
touch database/enrollments.sqlite
php artisan migrate:fresh
php artisan serve --port=8003
```

All three must be running before executing tests.

## Running Edge Case Tests

With all 3 services running, open a new terminal and follow the curl commands in:

[`lab2/tests/curl-tests.md`](tests/curl-tests.md)

Evidence output is pre-saved in: `lab2/docs/evidence/`

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

See **Prerequisites** above.

> Note: The academe frontend (lab1/academe/) is NOT
> required for Lab 2. The three services run and are
> tested independently via curl.
