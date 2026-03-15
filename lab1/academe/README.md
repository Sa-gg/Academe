# Academe — Student Course System
### ITSAR2 313 – System Architecture and Integration 2 | Lab 1
### BIST 3B

> **GitHub (Lab 1):** <!-- TODO: replace with your GitHub link, e.g. https://github.com/username/sar-lab1/tree/main/lab1/academe -->

A Laravel 10 web application for managing students, courses, and enrollments. Built with the Repository Pattern (MVCR) for clean separation of concerns, SQLite for zero-config persistence, and a hand-crafted design system with no external CSS or JS frameworks.

---

## System Architecture

Academe follows the **Model-View-Controller-Repository (MVCR)** pattern. Controllers never touch Eloquent directly — they delegate to repository interfaces, which are resolved by Laravel's service container.

```
Request Lifecycle
─────────────────

  Browser
    │
    ▼
  routes/web.php
    │
    ▼
  Controller ──────► Repository Interface
    │                      │
    ▼                      ▼
  Blade View         Eloquent Implementation
                           │
                           ▼
                       SQLite DB
```

This architecture means the data access layer can be swapped (e.g., from Eloquent to an API client) without changing any controller or view logic.

## Tech Stack

| Layer       | Technology                |
|-------------|---------------------------|
| Framework   | Laravel 10                |
| Language    | PHP 8.1+                  |
| Database    | SQLite                    |
| Templating  | Blade                     |
| CSS         | Inline design system (no frameworks) |
| JS          | Vanilla JS (`public/js/academe.js`) |
| Typography  | Fraunces + Plus Jakarta Sans (Google Fonts) |
| Pattern     | Repository Pattern (MVCR) |

## Requirements

- PHP 8.1 or higher
- Composer 2.x
- SQLite extension enabled (`pdo_sqlite`)
- A web server (Laravel Herd, Valet, or `php artisan serve`)

## Installation

1. Clone the repository:
   ```bash
   git clone <repository-url> academe
   cd academe
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Copy the environment file:
   ```bash
   cp .env.example .env
   ```

4. Generate the application key:
   ```bash
   php artisan key:generate
   ```

5. Create the SQLite database:
   ```bash
   touch database/database.sqlite
   ```

6. Configure `.env` for SQLite:
   ```
   DB_CONNECTION=sqlite
   DB_DATABASE=/absolute/path/to/database/database.sqlite
   ```

7. Run migrations:
   ```bash
   php artisan migrate
   ```

8. Seed the database:
   ```bash
   php artisan db:seed
   ```

9. Start the development server:
   ```bash
   php artisan serve
   ```

10. Open `http://localhost:8000` in your browser.

## Available Routes

| Method | URI                    | Name                 | Description            |
|--------|------------------------|----------------------|------------------------|
| GET    | `/`                    | —                    | Redirects to `/courses`|
| GET    | `/courses`             | `courses.index`      | Course catalog         |
| GET    | `/students`            | `students.index`     | Student directory      |
| GET    | `/students/create`     | `students.create`    | Add student form       |
| POST   | `/students`            | `students.store`     | Store new student      |
| GET    | `/enrollments`         | `enrollments.index`  | Enrollment records     |
| GET    | `/enrollments/create`  | `enrollments.create` | New enrollment form    |
| POST   | `/enrollments`         | `enrollments.store`  | Store enrollment       |
| GET    | `/enrollments/{id}`    | `enrollments.show`   | Enrollment detail      |
| GET    | `/architecture`        | `architecture`       | Architecture comparison|

## Project Structure

```
academe/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── CourseController.php
│   │   │   ├── EnrollmentController.php
│   │   │   └── StudentController.php
│   │   └── Requests/
│   │       └── StoreEnrollmentRequest.php
│   ├── Models/
│   │   ├── Course.php
│   │   ├── Enrollment.php
│   │   └── Student.php
│   ├── Providers/
│   │   └── AppServiceProvider.php        # Repository bindings
│   └── Repositories/
│       ├── Interfaces/
│       │   ├── CourseRepositoryInterface.php
│       │   ├── EnrollmentRepositoryInterface.php
│       │   └── StudentRepositoryInterface.php
│       ├── CourseRepository.php
│       ├── EnrollmentRepository.php
│       └── StudentRepository.php
├── database/
│   ├── migrations/
│   │   ├── create_students_table.php
│   │   ├── create_courses_table.php
│   │   └── create_enrollments_table.php
│   ├── seeders/
│   │   └── DatabaseSeeder.php
│   └── database.sqlite
├── public/
│   └── js/
│       └── academe.js                    # Client-side interactivity
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php             # Design system + layout
│       ├── courses/
│       │   └── index.blade.php
│       ├── students/
│       │   ├── index.blade.php
│       │   └── create.blade.php
│       ├── enrollments/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   └── show.blade.php
│       └── architecture.blade.php
└── routes/
    └── web.php
```
## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
