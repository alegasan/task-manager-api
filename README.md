# Task Manager API

A lightweight Laravel-based REST API for managing tasks and users. This repository contains the API backend for a personal task manager application used for learning and as a starting point for small projects.

## What's included

- API endpoints for creating, listing, updating, and deleting tasks.
- Eloquent models: `User`, `Task`.
- Policies and request validation for task operations.

## Quickstart

Prerequisites:

- PHP 8.1+ (or compatible version)
- Composer
- A database (MySQL, PostgreSQL, SQLite)

Install dependencies:

```bash
composer install
```

Copy the environment file and set credentials:

```bash
cp .env.example .env
# then edit .env to set DB_*, APP_URL, and other values
```

Generate an application key and run migrations:

```bash
php artisan key:generate
php artisan migrate
```

Run the local server:

```bash
php artisan serve
```

Run tests:

```bash
./vendor/bin/pest
```

## Environment notes

- Use `DB_CONNECTION`, `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in `.env`.
- For quick local testing, set `DB_CONNECTION=sqlite` and point `DB_DATABASE` to `database/database.sqlite`.

## API overview

Base URL: `http://localhost:8000/api`

Authentication: The project uses Laravel Sanctum for API authentication (see `config/sanctum.php`).

Common endpoints:

- `POST /api/login` — authenticate and receive a token.
- `POST /api/register` — create a new user.
- `GET /api/tasks` — list tasks for the authenticated user.
- `POST /api/tasks` — create a new task.
- `GET /api/tasks/{id}` — view a task.
- `PUT /api/tasks/{id}` — update a task.
- `DELETE /api/tasks/{id}` — delete a task.

Refer to the `app/Http/Controllers` and `routes/api.php` for exact route definitions and behavior.

---

Updated README to include project-specific setup and API overview.
