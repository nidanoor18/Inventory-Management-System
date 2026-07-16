# Inventory Management System API

A RESTful Inventory Management System built with **Laravel 12** that provides secure authentication, category and product management, stock movement tracking, and inventory reporting. The API uses **Laravel Sanctum** for authentication and follows RESTful design principles.

---

## Features

- User Authentication (Laravel Sanctum)
- Role-Based Authorization (Admin & Staff)
- Category Management (CRUD)
- Product Management (CRUD)
- Stock Movement Tracking
- Low Stock Report
- Movement Summary Report
- Form Request Validation
- API Resources
- RESTful API Design

---

## Technology Stack

- Laravel 12
- PHP 8.x
- MySQL
- Laravel Sanctum
- REST API
- Postman

---

## Project Structure

```
Inventory-Management-System
│
├── app
│   ├── Http
│   │   ├── Controllers
│   │   ├── Requests
│   │   ├── Resources
│   │   └── Middleware
│   ├── Models
│   └── Policies
│
├── database
│   ├── factories
│   ├── migrations
│   └── seeders
│
├── routes
│   └── api.php
│
├── README.md
├── API_DOCUMENTATION.md
├── postman_collection.json
└── composer.json
```

---

## Installation

### Clone Repository

```bash
git clone https://github.com/nidanoor18/Inventory-Management-System.git

cd Inventory-Management-System
```

---

### Install Dependencies

```bash
composer install
```

---

### Configure Environment

Copy the environment file.

```bash
cp .env.example .env
```

Generate the application key.

```bash
php artisan key:generate
```

Configure your MySQL database inside `.env`.

```env
DB_DATABASE=inventory_management
DB_USERNAME=root
DB_PASSWORD=
```

---

### Run Migrations

```bash
php artisan migrate
```

If your project includes seeders:

```bash
php artisan migrate:fresh --seed
```

---

### Start the Server

```bash
php artisan serve
```

The API will be available at:

```
http://127.0.0.1:8000
```

---

# API Base URL

```
http://127.0.0.1:8000/api/v1
```

---

## Authentication

This project uses **Laravel Sanctum**.

Include the following header for protected routes.

```http
Authorization: Bearer YOUR_ACCESS_TOKEN
Accept: application/json
```

---

# API Endpoints

## Authentication

| Method | Endpoint | Description |
|---------|----------|-------------|
| POST | /register | Register User |
| POST | /login | Login |
| POST | /logout | Logout |
| GET | /me | Authenticated User |

---

## Categories

| Method | Endpoint | Description |
|---------|----------|-------------|
| GET | /categories | List Categories |
| POST | /categories | Create Category |
| GET | /categories/{id} | Show Category |
| PUT | /categories/{id} | Update Category |
| DELETE | /categories/{id} | Delete Category (Admin Only) |

---

## Products

| Method | Endpoint | Description |
|---------|----------|-------------|
| GET | /products | List Products |
| POST | /products | Create Product |
| GET | /products/{id} | Show Product |
| PUT | /products/{id} | Update Product |
| DELETE | /products/{id} | Delete Product (Admin Only) |

---

## Stock Movements

| Method | Endpoint | Description |
|---------|----------|-------------|
| GET | /stock-movements | List Stock Movements |
| POST | /stock-movements | Record Stock Movement |
| GET | /stock-movements/{id} | Show Stock Movement |
| DELETE | /stock-movements/{id} | Delete Stock Movement |

---

## Reports

| Method | Endpoint | Description |
|---------|----------|-------------|
| GET | /reports/low-stock | Low Stock Products |
| GET | /reports/movement-summary | Stock Movement Summary |

---

## Running the API

Start the Laravel development server.

```bash
php artisan serve
```

Open Postman and import:

```
Inventory_Management_API.postman_collection.json
```

Update the collection variables.

```
base_url = http://127.0.0.1:8000/api/v1

token = YOUR_ACCESS_TOKEN
```

Login first to obtain a Sanctum token, then test all protected endpoints.

---

## Authentication Workflow

1. Register a new user.
2. Login to receive an API token.
3. Add the token to the Authorization header.
4. Access protected endpoints.
5. Logout to invalidate the token.

---

## Reports

The API provides two inventory reports.

### Low Stock Report

Returns products whose available quantity is less than or equal to the reorder level.

### Movement Summary

Provides a summary of stock-in and stock-out transactions.

---

## HTTP Status Codes

| Code | Description |
|------|-------------|
| 200 | OK |
| 201 | Created |
| 204 | Deleted |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 422 | Validation Error |
| 500 | Internal Server Error |

---

## Documentation

The project includes the following documentation.

- **README.md**
- **API_DOCUMENTATION.md**
- **postman_collection.json**

---

## Future Improvements

- Unit & Feature Testing
- CSV/PDF Report Export
- Dashboard UI
- Product Image Upload
- Pagination & Filtering
- Docker Deployment

---

## Author

**Nida Noor**


Laravel Backend Developer Training Task

---
