# Inventory Management System API Documentation

## Overview

This RESTful API is built using **Laravel 12** and **Laravel Sanctum**. It provides secure authentication and inventory management features including category management, product management, stock movement tracking, and inventory reports.

---

# Base URL

```text
http://127.0.0.1:8000/api/v1
```

---

# Authentication

The API uses **Laravel Sanctum** for token-based authentication.

For all protected endpoints include:

```http
Authorization: Bearer YOUR_ACCESS_TOKEN
Accept: application/json
Content-Type: application/json
```

---

# Authentication Endpoints

## Register

**POST** `/register`

Registers a new user.

### Request

```json
{
    "name": "Nida Noor",
    "email": "nida@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

### Response

```json
{
    "message": "User registered successfully.",
    "token": "1|xxxxxxxxxxxxxxxx"
}
```

---

## Login

**POST** `/login`

Authenticates a user and returns an access token.

### Request

```json
{
    "email": "admin@example.com",
    "password": "password"
}
```

### Response

```json
{
    "message": "Login successful.",
    "token": "1|xxxxxxxxxxxxxxxx"
}
```

---

## Logout

**POST** `/logout`

Authentication Required.

Invalidates the current access token.

---

## Current User

**GET** `/me`

Authentication Required.

Returns details of the authenticated user.

---

# Category Endpoints

Authentication Required.

## Get Categories

**GET** `/categories`

Returns all categories.

---

## Create Category

**POST** `/categories`

### Request

```json
{
    "name": "Electronics"
}
```

---

## Get Category

**GET** `/categories/{id}`

Returns a single category.

---

## Update Category

**PUT** `/categories/{id}`

### Request

```json
{
    "name": "Updated Electronics"
}
```

---

## Delete Category

**DELETE** `/categories/{id}`

**Admin Only**

Deletes the selected category.

---

# Product Endpoints

Authentication Required.

## List Products

**GET** `/products`

Returns all products.

---

## Create Product

**POST** `/products`

### Request

```json
{
    "category_id": 1,
    "sku": "SKU-0002",
    "name": "USB-C Cable",
    "price": 9.99,
    "quantity": 100,
    "reorder_level": 15
}
```

---

## Show Product

**GET** `/products/{id}`

Returns product details.

---

## Update Product

**PUT** `/products/{id}`

### Request

```json
{
    "category_id": 1,
    "sku": "SKU-0002",
    "name": "USB-C Cable",
    "price": 12.99,
    "quantity": 150,
    "reorder_level": 20
}
```

---

## Delete Product

**DELETE** `/products/{id}`

**Admin Only**

Deletes the selected product.

---

# Stock Movement Endpoints

Authentication Required.

## List Stock Movements

**GET** `/stock-movements`

Returns all stock movement records.

---

## Record Stock Movement

**POST** `/stock-movements`

### Request

```json
{
    "product_id": 1,
    "type": "in",
    "quantity": 50,
    "remarks": "Restock from supplier"
}
```

Possible values for `type`

- `in`
- `out`

---

## Show Stock Movement

**GET** `/stock-movements/{id}`

Returns details of a specific stock movement.

---

## Delete Stock Movement

**DELETE** `/stock-movements/{id}`

Deletes a stock movement record.

---

# Report Endpoints

Authentication Required.

## Low Stock Report

**GET** `/reports/low-stock`

Returns products whose available quantity is at or below the reorder level.

---

## Movement Summary Report

**GET** `/reports/movement-summary`

Returns a summary of stock-in and stock-out transactions.

---

# Authentication Flow

1. Register a new user.
2. Login to receive a Sanctum token.
3. Add the token to the Authorization header.

```http
Authorization: Bearer YOUR_ACCESS_TOKEN
```

4. Access all protected endpoints.
5. Logout to revoke the token.

---

# HTTP Status Codes

| Code | Description |
|------|-------------|
| 200 | OK |
| 201 | Resource Created |
| 204 | Resource Deleted |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 422 | Validation Error |
| 500 | Internal Server Error |

---

# Technologies Used

- Laravel 12
- Laravel Sanctum
- PHP 8.x
- MySQL
- REST API
- Postman

---

# Postman Collection

The project includes a Postman collection for testing all API endpoints.

```
Inventory_Management_API.postman_collection.json
```

Import the collection into Postman, configure the `base_url` and `token` variables, and test the API.

---

# Author

**Nida Noor**

Laravel Backend Developer Training Task