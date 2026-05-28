# Dogsitter – Hundebetreuungs-Vermittlungsportal

Vermittlungsplattform zwischen Hundesittern und Hundebesitzern.

## Stack

- **Backend:** Laravel 13 + PHP 8.4 + MySQL + Laravel Sanctum
- **Frontend:** Nuxt 3 + Vue 3 + TailwindCSS + Pinia
- **Deployment:** all-inkl.com (Backend) + Vercel (Frontend)

## Lokale Entwicklung

### Voraussetzungen

- PHP 8.4, Composer
- Node.js 20+, npm
- XAMPP (MySQL läuft auf Port 3306)

### Setup

```bash
# 1. XAMPP starten → MySQL starten
# In phpMyAdmin: Datenbank "dogsitter" anlegen

# 2. Backend
cd backend
cp .env.example .env
# .env prüfen: DB_USERNAME=root, DB_PASSWORD leer lassen
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve        # läuft auf http://localhost:8000

# 3. Frontend (neues Terminal)
cd frontend
cp .env.example .env
npm install
npm run dev              # läuft auf http://localhost:3000
```

## Tests

```bash
# Backend (PHPUnit) — nutzt SQLite in-memory, XAMPP nicht nötig
cd backend
php artisan test

# Frontend (Vitest)
cd frontend
npm run test
```

## Deployment

Push auf `main` → GitHub Actions deployen automatisch:
- `backend/` → all-inkl via SSH
- `frontend/` → Vercel
