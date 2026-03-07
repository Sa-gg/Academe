# Enrollment Service

Microservice for managing enrollments. Runs on **port 8003**.

Depends on Student Service (port 8001) and Course Service (port 8002).

## Run

```bash
php artisan serve --port=8003
```

## Endpoints

| Method | URL                      | Description             |
|--------|--------------------------|-------------------------|
| GET    | /api/enrollments         | List all enrollments    |
| POST   | /api/enrollments         | Create a new enrollment |
| GET    | /api/enrollments/{id}    | Get enrollment by ID    |
