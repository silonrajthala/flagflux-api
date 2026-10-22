
# 🚀 FlagFlux API

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <strong>A high-performance feature flag and A/B testing platform built with Laravel 12.</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.3%2B-blue" alt="PHP Version">
  <img src="https://img.shields.io/badge/Laravel-12.x-red" alt="Laravel Version">
  <img src="https://img.shields.io/badge/Database-PostgreSQL-orange" alt="PostgreSQL">
  <img src="https://img.shields.io/badge/License-MIT-yellow.svg" alt="License: MIT">
</p>

---

## ✨ Features

- **Deterministic Percentage Rollouts:** Uses CRC32-based bucketing to consistently assign users to feature rollouts.
- **Flexible Rollout Control:** Supports globally enabled, disabled, and percentage-based feature flags.
- **Attribute-Based Targeting:** Evaluates user context, including country and user groups.
- **Secure Authentication:** Uses Laravel Sanctum for token-based API authentication.
- **Management API:** CRUD operations for projects, feature flags, and targeting rules.
- **Request Validation:** Uses dedicated Form Request classes to maintain clean controllers.
- **Automated Testing:** Supports feature and unit testing with Pest/PHPUnit.

## 🛠️ Tech Stack

- **Language:** PHP 8.3+
- **Framework:** Laravel 12
- **Database:** PostgreSQL
- **Caching:** Redis
- **Authentication:** Laravel Sanctum
- **Testing:** Pest / PHPUnit

## 📡 API Documentation

**Base URL:** `http://localhost:8000/api/v1`

### Public Endpoints

#### 1. Evaluate a Feature Flag

`POST /api/v1/evaluate/{projectSlug}/{flagKey}`

Evaluates whether a feature flag is enabled for a given user context.

**Request Body:**

```json
{
  "context": {
    "user_id": "user-123",
    "country": "DE"
  }
}
```

**Response:**

```json
{
  "enabled": true
}
```

#### 2. Admin Login

`POST /api/v1/login`

Authenticates an administrator and returns a Sanctum bearer token.

**Request Body:**

```json
{
  "email": "admin@flagflux.test",
  "password": "password"
}
```

**Response:**

```json
{
  "message": "Login successful",
  "token": "YOUR_ACCESS_TOKEN"
}
```

### Protected Management Endpoints

Include the following headers in authenticated requests:

```http
Authorization: Bearer YOUR_ACCESS_TOKEN
Accept: application/json
```

| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/v1/admin/projects/{projectId}/flags` | List project flags |
| `POST` | `/api/v1/admin/projects/{projectId}/flags` | Create a feature flag |
| `PUT` | `/api/v1/admin/flags/{id}` | Update a feature flag |
| `DELETE` | `/api/v1/admin/flags/{id}` | Delete a feature flag |

### Create a Feature Flag

`POST /api/v1/admin/projects/{projectId}/flags`

**Request Body:**

```json
{
  "name": "Beta Dashboard",
  "key": "beta-dashboard",
  "is_enabled": true,
  "rollout_percentage": 50
}
```

## ⚙️ Installation and Setup

### Prerequisites

- PHP 8.3+
- Composer
- PostgreSQL
- Redis (if enabled in the application configuration)

### 1. Clone the Repository

```bash
git clone <YOUR_REPOSITORY_URL>
cd FlagFlux
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Update your `.env` file with your PostgreSQL credentials and Redis configuration, if applicable.

### 4. Run Database Migrations

```bash
php artisan migrate
```

### 5. Start the Development Server

```bash
php artisan serve
```

The API will be available at `http://localhost:8000`.

## 🧪 Running Tests

Run the automated test suite:

```bash
php artisan test
```

## 📄 License

This project is open-source software licensed under the [MIT License](https://opensource.org/licenses/MIT).

---

<p align="center">
  Built with ❤️ using Laravel.
</p>
