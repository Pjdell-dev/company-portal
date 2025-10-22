# Company Portal — Development README

This README contains quick start steps for local development, Docker, database migrations, seeding, sample credentials, and a minimal sample code snippet demonstrating the Sanctum SPA login flow (CSRF cookie + session login). The project uses Laravel for backend and React (Vite) for frontend.

## Prerequisites
- Docker & Docker Compose (if using docker-compose)
- PHP 8.1+ (if running Laravel without Docker)
- Composer (if running Laravel without Docker)
- Node.js 18+ and npm/yarn (for frontend)
- A Postgres server or Docker container for the DB (project defaults use Postgres)

## Start with Docker Compose (recommended for local dev)
From the repository root:

```powershell
# Build and start services
docker compose up --build -d

# Run migrations & seed (backend container name may vary)
docker compose exec backend php artisan migrate --seed
```

If you run Laravel without Docker, make sure `.env` is configured and DB is reachable.

## Migrations & Seeding (without Docker)
From `backend` folder:

```powershell
# Install dependencies if needed
composer install

# Create DB tables required for cache/session when using database drivers
php artisan cache:table
php artisan session:table
php artisan migrate
php artisan db:seed
```

Note: If you don't want to create DB-backed cache, set `CACHE_DRIVER=file` in `backend/.env`.

## Local .env tips for dev
- `APP_URL=http://localhost:8000`
- `SANCTUM_STATEFUL_DOMAINS=localhost:3000,localhost:5173`
- `SESSION_DOMAIN=null`
- `SESSION_SECURE_COOKIE=false` (for non-HTTPS local dev)
- `SESSION_SAME_SITE=lax`

After editing `.env`, run:

```powershell
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## Sample credentials (seeded)
The seeder creates an example company and user. If not seeded, create a user using tinker or the DB UI.

- Username: `admin`
- Password: `password123`
- Company code: `ACME`

(Adjust if your DatabaseSeeder uses different values.)

## Sample axios code snippet — Sanctum SPA login flow (most important)
This example shows how the SPA must request the CSRF cookie first, then POST credentials with cookies included. The backend expects this flow when using Laravel Sanctum with cookie-based authentication.

```js
// frontend/src/lib/api.js
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000',
  withCredentials: true, // send cookies
});

export default api;

// Usage in a login handler (e.g., src/pages/Login.jsx)
import api from './lib/api';

async function login(username, password, companyCode) {
  // 1) Get CSRF cookie (sets XSRF-TOKEN and laravel_session)
  await api.get('/sanctum/csrf-cookie');

  // 2) Optionally read cookie and set header (defensive)
  const match = document.cookie.match(new RegExp('(^|; )XSRF-TOKEN=([^;]+)'));
  if (match) {
    api.defaults.headers.common['X-XSRF-TOKEN'] = decodeURIComponent(match[2]);
  }

  // 3) Post credentials to session-based login (web route)
  const res = await api.post('/login', {
    username,
    password,
    company_code: companyCode,
  });

  return res.data; // user and company
}
```

## Troubleshooting
- 419 CSRF error: ensure `/sanctum/csrf-cookie` responded with `Set-Cookie: XSRF-TOKEN` and the POST includes `X-XSRF-TOKEN` header. Check DevTools → Network and Application → Cookies.
- CORS preflight fails: ensure `backend/config/cors.php` includes your frontend origin in `allowed_origins` and the login path in `paths`. Also ensure `supports_credentials` = true.
- Cache clear fails due to DB cache driver: set `CACHE_DRIVER=file` in `.env` or create the cache table (`php artisan cache:table` then migrate).

## Quick test commands (Windows PowerShell)
```powershell
# Backend (from backend folder)
php artisan config:clear
php artisan cache:clear
php artisan migrate --seed
php artisan serve --host=0.0.0.0 --port=8000

# Frontend
npm install
npm run dev
```

## Where to look for issues
- Backend logs: `backend/storage/logs/laravel.log`
- Browser DevTools: Network / Application / Console


