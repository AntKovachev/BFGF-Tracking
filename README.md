# BFGF-Tracking

Simple personal finance tracker for two private users.

## Stack

- Frontend: Angular (`/frontend`)
- Backend: Laravel API (`/backend`)
- Database: MySQL (configure in `backend/.env`)
- Auth: Laravel Sanctum token authentication

## Features

- Email/password login
- User-scoped transactions (income/expense)
- User-scoped investments with profit/loss
- Dashboard summary (income, expenses, balance)
- Financial goal tracking toward **250,000 EUR**

## Backend setup

```bash
cd /home/runner/work/BFGF-Tracking/BFGF-Tracking/backend
cp .env.example .env
php artisan key:generate
# configure DB_* values for MySQL in .env
php artisan migrate
php artisan serve
```

## Frontend setup

```bash
cd /home/runner/work/BFGF-Tracking/BFGF-Tracking/frontend
npm install
npm start
```

The frontend expects the API on `http://localhost:8000/api`.
