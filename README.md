# 🚀 FlagFlux API

A high-performance, production-ready **Feature Flag & A/B Testing Engine** built with Laravel 12, MySQL, and Redis. Designed to showcase clean architecture, service-oriented design, dynamic rule evaluation, and robust automated testing.

---

## 🛠️ Tech Stack

* **Framework:** Laravel 12 (PHP)
* **Database:** MySQL / SQLite (for testing)
* **Caching Layer:** Redis (`Cache::remember` for sub-millisecond evaluations)
* **Testing:** PHPUnit (Feature & Integration testing)

---

## 📐 Architecture & Design

FlagFlux follows a **Service-Oriented Architecture (SOA)**, separating HTTP routing/controllers from business logic.

* **`FeatureFlagEvaluatorService`**: Handles high-performance caching, master switch logic, and rule matching.
* **Relational Schema**: 
  * `Projects` (Multi-tenant container)
  * `Environments` (Production, Staging, etc.)
  * `FeatureFlags` (Master toggle state)
  * `FlagRules` (JSON-driven dynamic targeting attributes using operators like `equals` and `in`)

---

## ⚙️ Getting Started (Local Setup)

Follow these steps to set up the project locally on your machine:

### 1. Clone the Repository
```bash
git clone [https://github.com/silonrajthala/flagflux-api.git](https://github.com/silonrajthala/flagflux-api.git)
cd flagflux-api
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
*(Make sure your database credentials are correctly set in your `.env` file).*

### 4. Run Migrations & Seeders
```bash
php artisan migrate:fresh --seed
```

### 5. Start the Local Server
```bash
php artisan serve
```

---

## 🔌 API Usage

### Evaluate a Feature Flag
* **Endpoint:** `POST /api/v1/evaluate/{projectSlug}/{flagKey}`
* **Content-Type:** `application/json`

#### Example Request (cURL):
```bash
curl -X POST [http://127.0.0.1:8000/api/v1/evaluate/global-store/one-click-apple-pay](http://127.0.0.1:8000/api/v1/evaluate/global-store/one-click-apple-pay) \
  -H "Content-Type: application/json" \
  -d '{"context": {"country": "DE"}}'
```

#### Example Response:
```json
{
  "project": "global-store",
  "flag": "one-click-apple-pay",
  "enabled": true
}
```

---

## 🧪 Running Automated Tests

FlagFlux includes a comprehensive feature test suite covering edge cases, global switches, and multi-attribute rule matching.

Run the test suite using:
```bash
php artisan test
```

---

## 🛡️ License

This project is open-source and available under the [MIT License](LICENSE).
