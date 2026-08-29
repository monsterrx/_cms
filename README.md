# Monster CMS 13

Monster RX's administration application, migrated to Laravel 13 with Inertia, React, Sanctum, and Vite.

## Architecture

- Laravel 13 uses the slim application configuration in `bootstrap/app.php`.
- Blade provides the Inertia root view; React renders authenticated CMS pages.
- Browser routes are split under `routes/cms`.
- API routes are split under `routes/api`.
- Existing Eloquent models and controllers are retained while modules are migrated incrementally.
- Production frontend assets are compiled into `public/build`; Node.js is not required at runtime.

## Requirements

- PHP 8.3 or newer with the Laravel-required extensions
- Composer 2
- MySQL 5.7 for production-schema compatibility
- Node.js 24 only for local or CI frontend builds
- Docker Desktop for the containerized environment

Composer resolves dependencies against PHP 8.3 so the lockfile remains compatible with Docker and shared hosting.

## Local Docker setup

Create the ignored local environment file and application key:

```powershell
Copy-Item .env.example .env
docker compose build
docker compose run --rm --no-deps app php artisan key:generate --force
docker compose up -d
```

Services:

- CMS: http://localhost:9001
- MySQL: localhost:3309
- Mailpit: http://localhost:8025

The database volume is initialized from `C:\Users\noree\Documents\dumps\rxninthr_monster_20260827.sql` the first time it is created. Existing database volumes are not re-imported automatically.

Check the stack:

```powershell
docker compose ps
docker compose logs --tail=100 app
```

## Development

```powershell
composer install
npm ci
npm run dev
```

Production asset build:

```powershell
npm run build
```

Tests and formatting:

```powershell
php artisan test
vendor\bin\pint --test
```

The local PHP CLI must have `mbstring` enabled to run PHPUnit and Pint.

## Shared-hosting deployment

Build assets locally or in CI with `npm ci && npm run build`. Deploy the Laravel application with `vendor` and `public/build`, point the domain document root to `public`, configure the production `.env`, and ensure `storage` plus `bootstrap/cache` are writable. No Node.js process is required on the server.

Never commit `.env`, credentials, database dumps, `vendor`, or `node_modules`.
