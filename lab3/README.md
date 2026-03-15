# Lab 3 — Business Logic API
### ITSAR2 313 – System Architecture and Integration 2
### Section: BIST 3B

## Members
| # | Name |
|---|------|
| 1 | Sagum, Patrick Ruiz |
| 2 | Henson, Princess Terana Caram Rasonable |
| 3 | Gargarita, Trisha Faith Casiano |
| 4 | Mogat, Ela Mae Trojillo |
| 5 | Tibo-oc, Paul Felippe Gelle |

## System Architecture

| Layer | File | Responsibility |
|-------|------|----------------|
| Presentation | curl client | Sends HTTP requests |
| Business Logic | server/controllers/orderController.js | Validates rules, processes orders |
| Data | server/data/products.json | Stores products and stock |

## Setup

```bash
cd server
npm install
cp .env.example .env
node server.js
```

Server: http://localhost:3000

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/products | List all products |
| GET | /api/products/:id | Get product by ID |
| POST | /api/orders | Place an order |

## Business Rules

| Rule | Error Code | HTTP |
|------|------------|------|
| Missing fields | VALIDATION_ERROR | 400 |
| Quantity is zero | INVALID_QUANTITY | 400 |
| Negative quantity | INVALID_QUANTITY | 400 |
| Non-integer quantity | INVALID_QUANTITY | 400 |
| Product not found | PRODUCT_NOT_FOUND | 404 |
| Out of stock | OUT_OF_STOCK | 400 |
| Quantity exceeds stock | STOCK_EXCEEDED | 400 |

## Running Tests

Start the API from `server/`, then run all 13 curl commands from `tests/curl-tests.md`.

## Deliverables

| Item | Location |
|------|----------|
| API source code | `lab3/server/` |
| curl test suite | `lab3/tests/curl-tests.md` |
| Lab 3 report (PDF) | `lab3/docs/lab3-report.pdf` |
| Lab 3 source report (DOCX) | `lab3/docs/lab3-report.docx` |

## Requirements

Node.js 18+, npm, curl
