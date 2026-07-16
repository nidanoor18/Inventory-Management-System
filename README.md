# Inventory Management System (Laravel API)

A REST API for managing products, categories, and stock movements, with
token-based auth and role-based authorization (Admin / Staff).

## About this deliverable

This repo contains the **application-layer source code** — migrations,
models, controllers, form requests, API resources, policies, routes,
factories, and seeders. It's built to drop into a fresh Laravel 11
installation. It was generated without a live PHP/Composer environment, so
it hasn't been executed — read it over before relying on it, and expect to
fix the odd typo.

## 1. Create the base Laravel project

```bash
composer create-project laravel/laravel inventory-management
cd inventory-management
composer require laravel/sanctum
php artisan install:api
```

`php artisan install:api` publishes Sanctum's config, adds the
`personal_access_tokens` migration, and switches on API routing
(creates `routes/api.php` if it doesn't already exist).

## 2. Copy in the files from this delivery

Copy each file from this project into the matching path in your new
Laravel project, overwriting where a file already exists:

```
app/Http/Controllers/Controller.php
app/Http/Controllers/Api/V1/AuthController.php
app/Http/Controllers/Api/V1/CategoryController.php
app/Http/Controllers/Api/V1/ProductController.php
app/Http/Controllers/Api/V1/StockMovementController.php
app/Http/Controllers/Api/V1/ReportController.php
app/Http/Requests/*.php
app/Http/Resources/*.php
app/Models/User.php            (replaces the default one)
app/Models/Category.php
app/Models/Product.php
app/Models/StockMovement.php
app/Policies/*.php
app/Providers/AppServiceProvider.php  (replaces the default one)
database/migrations/2024_01_01_*.php
database/factories/UserFactory.php     (replaces the default one)
database/factories/CategoryFactory.php
database/factories/ProductFactory.php
database/seeders/*.php
routes/api.php                 (replaces the generated one)
.env.example                   (merge with the one Laravel generated)
```

## 3. Configure the environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your MySQL credentials (`DB_DATABASE`, `DB_USERNAME`,
`DB_PASSWORD`). Create the database itself first, e.g.:

```sql
CREATE DATABASE inventory_system CHARACTER SET utf8mb4;
```

## 4. Run migrations and seed data

```bash
php artisan migrate --seed
```

This creates all tables and seeds:

- An admin user: `admin@example.com` / `password`
- A staff user: `staff@example.com` / `password`
- 5 categories, each with ~8 products (2 of which are deliberately
  below their reorder level, for testing the low-stock report)

## 5. Run the app

```bash
php artisan serve
```

The API is now available at `http://localhost:8000/api/v1`.

## Authentication

Token-based via Sanctum. Register or log in to get a bearer token, then
send it as `Authorization: Bearer <token>` on every subsequent request.

```
POST /api/v1/register   { name, email, password, password_confirmation }
POST /api/v1/login      { email, password }
POST /api/v1/logout     (auth required)
GET  /api/v1/me         (auth required)
```

New self-registrations are always created with the `staff` role. To create
an admin, either use the seeded `admin@example.com` account or update a
user's `role` column directly (`php artisan tinker` → `User::find(1)->update(['role' => 'admin'])`).

## Authorization model

- **Everyone authenticated** can view categories, products, stock
  movements, and reports.
- **Staff and Admin** can create/update products and record stock
  movements (this is the day-to-day job).
- **Admin only** can create/update/delete categories, delete products,
  and delete stock movements (destructive or structural changes).

This is enforced via Laravel Policies (`app/Policies`), registered in
`AppServiceProvider`, and checked either inside Form Requests
(`authorize()`) or explicitly with `$this->authorize()` in controllers.

## API endpoints

All routes below are prefixed with `/api/v1` and require
`Authorization: Bearer <token>` unless noted otherwise.

| Method | Endpoint | Notes |
|---|---|---|
| POST | `/register` | Public |
| POST | `/login` | Public |
| POST | `/logout` | |
| GET | `/me` | |
| GET | `/categories` | Paginated |
| POST | `/categories` | Admin only |
| GET | `/categories/{id}` | |
| PUT/PATCH | `/categories/{id}` | Admin only |
| DELETE | `/categories/{id}` | Admin only; blocked if it still has products |
| GET | `/products` | Filters: `search`, `category_id`, `low_stock` |
| POST | `/products` | |
| GET | `/products/{id}` | |
| PUT/PATCH | `/products/{id}` | |
| DELETE | `/products/{id}` | Admin only |
| GET | `/stock-movements` | Filters: `product_id`, `type`, `from`, `to` |
| POST | `/stock-movements` | Adjusts product quantity atomically; `type` is `in` or `out` |
| GET | `/stock-movements/{id}` | |
| DELETE | `/stock-movements/{id}` | Admin only; reverses the quantity change |
| GET | `/reports/low-stock` | Products at or below reorder level |
| GET | `/reports/movement-summary` | Stock-in vs stock-out totals; filters: `product_id`, `from`, `to` |

A ready-to-import Postman collection is included at
`postman_collection.json`. Set the `base_url` and `token` collection
variables after logging in.

## Design notes

- **Stock changes are transactional.** `StockMovementController@store`
  locks the product row, checks for sufficient quantity on `out`
  movements, and updates `products.quantity` in the same DB transaction
  as the movement record — no race conditions between two people
  recording stock at once.
- **Low-stock is a query scope**, not a stored flag, so it's always
  accurate: `Product::lowStock()` returns products where
  `quantity <= reorder_level`.
- **Roles are a single enum column** on `users` (`admin` / `staff`)
  rather than a full permissions package, since the brief only calls
  for two roles. Swapping in `spatie/laravel-permission` later would
  mean changing the Policies, not the controllers.

## Known limitations / not implemented

- No automated tests (PHPUnit/Pest) are included — add feature tests
  per controller if this goes further than a take-home exercise.
- No rate limiting beyond Laravel's defaults is configured.
- No soft deletes; deleting a category/product/movement is permanent.
- Reporting is limited to low-stock and stock-in/out totals; no
  CSV/PDF export.
- No web UI/Blade views — this is API-only, per the brief.
