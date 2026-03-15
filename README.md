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
| Lab 1 | [lab1/](lab1/) |
| Lab 2 | [lab2/](lab2/) |
| Lab 3 | [lab3/](lab3/) |

---

## Repository Structure

| Folder | Contents |
|--------|----------|
| `lab1/` | Monolithic + Microservices source code, architecture docs |
| `lab2/` | Edge case testing, curl tests, evidence, report |
| `lab3/` | Business Logic API (docs + server + tests) |

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
│   ├── README.md                       ← Lab 3 setup & docs
│   ├── docs/
│   │   ├── lab3-report.docx
│   │   └── lab3-report.pdf
│   ├── server/
│   │   ├── server.js
│   │   ├── controllers/
│   │   ├── middleware/
│   │   ├── routes/
│   │   ├── data/
│   │   └── package.json
│   └── tests/
│       └── curl-tests.md
│
└── README.md
```

---

## Lab 1 — Monolith vs Microservices

### Fast Setup (Recommended)

```bash
bash scripts/lab1/setup.sh
```

Fresh setup is automatic (`migrate:fresh` + seed where available).

Use **Git Bash**. The script prints progress indicators such as `[lab1][1/4]` so it is clear that setup is still running.

Why it can take time on first run:
- Composer installs PHP dependencies
- npm installs frontend dependencies
- frontend assets are built
- databases are recreated from scratch

Run services in separate terminals:

```bash
bash scripts/lab1/serve-microservice.sh student
bash scripts/lab1/serve-microservice.sh course
bash scripts/lab1/serve-microservice.sh enrollment
bash scripts/lab1/serve-academe.sh
```

Open: http://localhost:8000

One-terminal fallback:

```bash
npm install
npm run setup:lab1
npm run serve:lab1
```

Detailed/manual steps: [`lab1/README.md`](lab1/README.md)

---

## Lab 2 — Edge Case Testing

### Fast Setup (Recommended)

```bash
bash scripts/lab2/setup.sh
```

Fresh setup is automatic (`migrate:fresh` + seed where available).

Use **Git Bash**. The script prints progress indicators such as `[lab2][1/3]` so it is clear that setup is still running.

Why it can take time on first run:
- Composer runs for each service
- all service databases are recreated from scratch

Run services in separate terminals:

```bash
bash scripts/lab2/serve.sh student
bash scripts/lab2/serve.sh course
bash scripts/lab2/serve.sh enrollment
```

One-terminal fallback:

```bash
npm install
npm run setup:lab2
npm run serve:lab2
```

Detailed/manual steps: [`lab2/README.md`](lab2/README.md)

### Running the Tests

All curl commands are in: [`lab2/tests/curl-tests.md`](lab2/tests/curl-tests.md)

Evidence files are in: [`lab2/docs/evidence/`](lab2/docs/evidence/)

Report is in: [`lab2/docs/lab2-report.pdf`](lab2/docs/lab2-report.pdf)

---

## Lab 3 — Business Logic API

Lab 3 is complete and organized with:
- API source in [`lab3/server/`](lab3/server/)
- test commands in [`lab3/tests/curl-tests.md`](lab3/tests/curl-tests.md)
- report files in [`lab3/docs/`](lab3/docs/)

Primary report: [`lab3/docs/lab3-report.pdf`](lab3/docs/lab3-report.pdf)

### Fast Setup (Recommended)

```bash
bash scripts/lab3/setup.sh
bash scripts/lab3/serve.sh
```

`setup.sh` restores `products.json` from seed for consistent testing.

Use **Git Bash**. The script prints progress messages so it is clear when install/reset is running.

One-terminal fallback:

```bash
npm install
npm run setup:lab3
npm run serve:lab3
```

Detailed/manual steps: [`lab3/README.md`](lab3/README.md).

---

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- NPM
- curl

---

## Report Files (Per Lab)

| Lab | Report (PDF) |
|-----|--------------|
| Lab 1 | [`lab1/docs/lab1-report.pdf`](lab1/docs/lab1-report.pdf) |
| Lab 2 | [`lab2/docs/lab2-report.pdf`](lab2/docs/lab2-report.pdf) |
| Lab 3 | [`lab3/docs/lab3-report.pdf`](lab3/docs/lab3-report.pdf) |
