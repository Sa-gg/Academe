# Course Service

Microservice for managing course data. Runs on **port 8002**.

## Run

```bash
php artisan serve --port=8002
```

## Endpoints

| Method | URL                  | Description         |
|--------|----------------------|---------------------|
| GET    | /api/courses         | List all courses    |
| GET    | /api/courses/{id}    | Get course by ID    |
