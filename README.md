# Laravel 13 API From Scratch

A practice project built while following [Laravel Daily's "How to Build Laravel 13 API From Scratch"](https://laraveldaily.com/course/laravel-api) course — a from-scratch REST API covering authentication, resource design, versioning, performance, documentation, and real-world third-party package integration.

## Why this project

This is the fourth project in a personal full-stack learning roadmap. After building a traditional server-rendered app ([Blog + CRM](https://github.com/omereroglu1923/03-blog-crm)), this project shifts focus to building an API consumed by five different frontend clients (Vue, Next.js, Nuxt, Flutter, and React Native/Expo) — the same backend, tested against real, independent clients rather than a single paired frontend.

## What's inside

- **Category & Product API** — full CRUD, nested resources, pagination, file uploads, caching with automatic invalidation
- **Authentication** — Sanctum token-based auth (register/login) consumed by four separate clients, plus a fifth client using Sanctum's cookie-based SPA authentication
- **API versioning** — `v1`/`v2` namespaces demonstrating how to evolve an API without breaking existing consumers
- **Rate limiting, CORS, custom 404/validation handling**
- **API documentation** — both Scribe and OpenAPI/L5-Swagger set up side by side
- **Automated tests** — Pest feature tests for API endpoints
- **Third-party package trials** — Laravel Orion, Laravel Restify, API Tool Kit, and API Response Helpers, each evaluated against this project's real constraints (see notes below)
- **AI-assisted workflow** — a Claude Code security audit skill (`laraveldaily-api-audit`) was run against this codebase and its findings (auth gaps, password field exposure) were fixed; a custom code-generation skill (`laravel-api-resource-generator`) was written and used to scaffold two additional resources (`Author`, `Book`) following this project's established conventions

## Concepts learned / practiced

Eloquent API Resources, `whenLoaded()` and eager loading (N+1 prevention verified with Telescope), Form Request validation, correct HTTP status code usage (201/204/409/422/429), Sanctum (token and SPA modes), CORS, API versioning strategy, cache invalidation via Observers, rate limiting, JSON:API conventions, and evaluating third-party packages by reading their source when documentation falls short.

## Tech stack

- Laravel 13, PHP 8.5
- SQLite (development & testing)
- Laravel Sanctum, Telescope, Pest
- Scribe, L5-Swagger (OpenAPI)

## Setup

```bash
git clone https://github.com/omereroglu1923/04-laravel-api-course.git
cd 04-laravel-api-course
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

API docs available at `/docs` (Scribe) and `/api/documentation` (Swagger UI) once the server is running.

## Part of a larger roadmap

This project is the fourth stop in a full-stack learning path: [`01-weather-cli-app`](https://github.com/omereroglu1923/weather-cli-app) → [`02-chirper`](https://github.com/omereroglu1923/chirper) → [`03-blog-crm`](https://github.com/omereroglu1923/03-blog-crm) → **`04-laravel-api-course`** → five client applications ([Vue](https://github.com/omereroglu1923/04-laravel-api-course-vue-client), [Next.js](https://github.com/omereroglu1923/04-laravel-api-course-nextjs-client), [Nuxt](https://github.com/omereroglu1923/04-laravel-api-course-nuxt-client), [Flutter](https://github.com/omereroglu1923/04-laravel-api-course-flutter-app), [Expo/React Native](https://github.com/omereroglu1923/04-laravel-api-course-expo-app)) consuming this API.