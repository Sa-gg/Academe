# Student Service

Microservice for managing student data. Runs on **port 8001**.

## Run

```bash
php artisan serve --port=8001
```

## Endpoints

| Method | URL                   | Description          |
|--------|-----------------------|----------------------|
| GET    | /api/students         | List all students    |
| POST   | /api/students         | Create a new student |
| GET    | /api/students/{id}    | Get student by ID    |
