# SAR Lab — Student Course System

### System Architecture and Integration 2

---

## Group Information

|                |                                              |
|----------------|----------------------------------------------|
| **Section**    | BIST 3B                                      |
| **Subject**    | System Architecture and Integration 2        |
| **Professor**  | Engr. Joao Roumil G. Vergara, CpE            |

### Members

| # | Last Name   | First Name              | Middle Name |
|---|-------------|-------------------------|-------------|
| 1 | Sagum       | Patrick                 | Ruiz        |
| 2 | Henson      | Princess Terana Caram   | Rasonable   |
| 3 | Gargarita   | Trisha Faith            | Casiano     |
| 4 | Mogat       | Ela Mae                 | Trojillo    |
| 5 | Tibo-oc     | Paul Felippe            | Gelle       |

---

## GitHub Repositories

| Lab | Link |
|-----|------|
| Lab 1 | <!-- TODO: replace with your GitHub link, e.g. https://github.com/username/sar-lab1/tree/main/lab1 --> |
| Lab 2 | <!-- TODO: replace with your GitHub link, e.g. https://github.com/username/sar-lab1/tree/main/lab2 --> |
| Lab 3 | <!-- TODO: replace with your GitHub link, e.g. https://github.com/username/sar-lab1/tree/main/lab3 --> |

---

## Repository Structure

| Folder | Contents |
|--------|----------|
| `lab1/` | Monolithic + Microservices source code, architecture docs |
| `lab2/` | Edge case testing, curl tests, evidence, report |
| `lab3/` | (To be assigned) |

```
sar-lab/
├── lab1/
│   ├── README.md                       ← Lab 1 setup & docs
│   ├── academe/                        ← monolithic source code (port 8000)
│   ├── microservices/
│   │   ├── student-service/            ← port 8001
│   │   ├── course-service/             ← port 8002
│   │   └── enrollment-service/         ← port 8003
│   └── docs/
│       ├── lab1-report.docx
│       ├── architecture.md
│       ├── architecture.docx
│       ├── comparison-table.md
│       ├── comparison-table.docx
│       ├── reflection.md
│       └── reflection.docx
│
├── lab2/
│   ├── README.md                       ← Lab 2 setup & docs
│   ├── services/
│   │   ├── student-service/
│   │   ├── course-service/
│   │   └── enrollment-service/
│   ├── tests/
│   │   └── curl-tests.md
│   └── docs/
│       ├── lab2-report.docx
│       ├── report.md
│       └── evidence/
│           ├── 01-happy-path.txt
│           ├── 02-validation-error.txt
│           ├── 03-not-found.txt
│           ├── 04-duplicate.txt
│           ├── 05-dependency-down.txt
│           └── 06-timeout.txt
│
├── lab3/
│   └── README.md                       ← Lab 3 placeholder
│
└── README.md
```

---

## Lab 1 — Monolith vs Microservices

### Option A — Run with Microservices (Default)

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
php artisan migrate --seed
php artisan serve --port=8003
```

**Terminal 4 — Frontend (Monolith as Gateway):**
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

### Option B — Run Monolithic Only (Optional)

> **Optional:** The monolithic backend is provided for comparison purposes only.

```bash
# Step 1:  Navigate to the monolithic app
cd lab1/academe

# Step 2:  Install PHP dependencies
composer install

# Step 3:  Install frontend dependencies and build assets
npm install && npm run build

# Step 4:  Create environment file from example
cp .env.example .env

# Step 5:  Generate application key
php artisan key:generate

# Step 6:  Create the SQLite database file
touch database/database.sqlite

# Step 7:  Run migrations and seed data
php artisan migrate --seed

# Step 8:  Set backend mode to monolithic in .env
#          APP_BACKEND=monolithic

# Step 9:  Clear cached config
php artisan config:clear

# Step 10: Start the development server
php artisan serve

# Step 11: Visit http://localhost:8000
#          Topbar badge shows "⬡ Monolithic"
```

### Switching Between Backends

```bash
# Open lab1/academe/.env
# Change: APP_BACKEND=monolithic  OR  APP_BACKEND=microservices
cd lab1/academe
php artisan config:clear
# Refresh browser — topbar badge confirms active backend
```

---

## Lab 2 — Edge Case Testing

### Setup

**Terminal 1 — Student Service:**
```bash
cd lab2/services/student-service
composer install
cp .env.example .env
php artisan key:generate
touch database/students.sqlite
php artisan migrate --seed
php artisan serve --port=8001
```

**Terminal 2 — Course Service:**
```bash
cd lab2/services/course-service
composer install
cp .env.example .env
php artisan key:generate
touch database/courses.sqlite
php artisan migrate --seed
php artisan serve --port=8002
```

**Terminal 3 — Enrollment Service:**
```bash
cd lab2/services/enrollment-service
composer install
cp .env.example .env
php artisan key:generate
touch database/enrollments.sqlite
php artisan migrate --seed
php artisan serve --port=8003
```

### Running the Tests

All curl commands are in: [`lab2/tests/curl-tests.md`](lab2/tests/curl-tests.md)

Evidence files are in: [`lab2/docs/evidence/`](lab2/docs/evidence/)

Report is in: [`lab2/docs/lab2-report.docx`](lab2/docs/lab2-report.docx)

---

## Lab 3 — (To Be Assigned)

> Lab 3 has not been assigned yet. See [`lab3/README.md`](lab3/README.md) for the placeholder.

---

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- NPM
- curl
