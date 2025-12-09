# QA Tool - API Testing Suite

A Laravel 12 + React 19 web application for automated API testing with PHPUnit 11 and Allure reporting.

## Features

- Automated API testing with PHPUnit 11
- Beautiful test reports with Allure Framework
- React 19 frontend for test execution and monitoring
- Support for both Docker and local development environments
- Comprehensive test scenarios for casino and live casino flows

## Tech Stack

- **Backend**: Laravel 12, PHP 8.3
- **Frontend**: React 19, Vite 6
- **Testing**: PHPUnit 11, Allure PHP Commons
- **Database**: SQLite (default) / MySQL
- **Containerization**: Docker, Docker Compose

---

## Quick Start

### Option 1: Docker Mode (Recommended)

Docker mode provides a fully containerized environment with Allure reporting service.

#### Prerequisites
- Docker Desktop installed and running
- Git

#### Setup

```bash
# Clone the repository
git clone <repository-url>
cd qa_tool

# Copy environment file
cp .env.example .env

# Start all containers
docker-compose up -d

# Install dependencies and setup (first time only)
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
docker-compose exec app npm install
docker-compose exec app npm run build
```

#### Access Points
| Service | URL |
|---------|-----|
| Web Application | http://localhost:8000 |
| Allure Reports | http://localhost:5050 |
| MySQL Database | localhost:3307 |

#### Docker Services
- **app**: PHP-FPM container running Laravel
- **web**: Nginx reverse proxy
- **db**: MySQL 8.0 database
- **allure**: Allure Docker Service for test reports

#### Running Tests in Docker

```bash
# Run all tests
docker-compose exec app php vendor/bin/phpunit

# Run with environment variables
docker-compose exec app php vendor/bin/phpunit \
  --configuration phpunit.xml
```

#### Stopping Docker

```bash
# Stop containers
docker-compose down

# Stop and remove volumes (resets database)
docker-compose down -v
```

---

### Option 2: Local Development (Without Docker)

Local mode runs the application directly on your machine using PHP's built-in server.

#### Prerequisites
- PHP 8.3+ with extensions: pdo, sqlite, curl, zip, mbstring
- Composer 2.x
- Node.js 20+ and npm
- Java 17+ (for Allure CLI)

#### Setup

```bash
# Clone the repository
git clone <repository-url>
cd qa_tool

# Copy environment file
cp .env.example .env

# Configure for local Allure (add to .env)
echo "ALLURE_USE_LOCAL=true" >> .env

# Install PHP dependencies
composer install

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Install Node.js dependencies
npm install

# Build frontend assets
npm run build
```

#### Install Allure CLI (Local Mode)

**Windows (via npm - recommended):**
```bash
npm install -g allure-commandline
```

**Windows (via Scoop):**
```bash
scoop install allure
```

**macOS:**
```bash
brew install allure
```

**Linux:**
```bash
# Download and extract
curl -o allure-2.24.0.tgz -Ls https://github.com/allure-framework/allure2/releases/download/2.24.0/allure-2.24.0.tgz
tar -zxvf allure-2.24.0.tgz -C /opt/
ln -s /opt/allure-2.24.0/bin/allure /usr/bin/allure
```

#### Running the Application

**Terminal 1 - Backend Server:**
```bash
php artisan serve --host=127.0.0.1 --port=8000
```

**Terminal 2 - Frontend Dev Server:**
```bash
npm run dev
```

#### Access Points
| Service | URL |
|---------|-----|
| Web Application | http://localhost:8000 |
| Vite Dev Server | http://localhost:5173 |
| Local Allure Reports | http://localhost:8000/allure-reports/{project-id}/index.html |

---

## Running API Tests

### Via Web Interface

1. Navigate to http://localhost:8000
2. Fill in the test credentials form
3. Click "Run Tests"
4. View results and Allure report link

### Via API Endpoint

```bash
curl -X POST "http://127.0.0.1:8000/api/test-runs" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "username": "YOUR_USERNAME",
    "token": "YOUR_TOKEN",
    "endpoint": "https://api-uat.example.com",
    "casinoGameCode": "game_code",
    "liveGameCode": "live_game_code",
    "betPrimary": "1",
    "winPrimary": "2",
    "jackpot": "Yes"
  }'
```

### Response Format

```json
{
  "id": 1,
  "username": "test_user",
  "phpunit_exit": 0,
  "project_id": "20251209-104249",
  "report_url": "/allure-reports/20251209-104249/index.html",
  "created_at": "2025-12-09T10:42:49.000000Z"
}
```

- `phpunit_exit`: 0 = all tests passed, 1 = some tests failed

---

## Test Scenarios

### Casino Tests (S21-S29)
| Code | Scenario |
|------|----------|
| S21 | Regular Gameround |
| S22 | Regular Gameround with Jackpot |
| S23 | Gameround with Retries (Idempotent) |
| S24 | Gameround with External Bonus |
| S25 | Gameround with Internal Bonus |
| S26 | Gameround with Mixed Bonus |
| S27 | Jackpot Win Through Feature |
| S28 | Regular Refund |
| S29 | Forward Compatibility |

### Live Casino Tests (S31-S40)
| Code | Scenario |
|------|----------|
| S31 | Regular Live Casino |
| S32 | Live Casino Jackpot |
| S33 | Idempotent Retries |
| S34 | Multiseat |
| S35 | Bonus |
| S36 | Refund |
| S37 | Partial Refund |
| S38 | Full Refund |
| S39 | Live Tip |
| S40 | Forward Compatibility |

---

## Environment Variables

### Core Settings
| Variable | Description | Default |
|----------|-------------|---------|
| `APP_ENV` | Application environment | `local` |
| `APP_DEBUG` | Debug mode | `true` |
| `DB_CONNECTION` | Database driver | `sqlite` |

### Allure Configuration
| Variable | Description | Default |
|----------|-------------|---------|
| `ALLURE_USE_LOCAL` | Use local Allure CLI | `false` |
| `ALLURE_INTERNAL_BASE_URL` | Docker internal URL | `http://allure:5050` |
| `ALLURE_PUBLIC_BASE_URL` | Public access URL | `http://localhost:5050` |

### Test Variables (passed at runtime)
| Variable | Description |
|----------|-------------|
| `TEST_USERNAME` | Test account username |
| `TEST_TOKEN` | Authentication token |
| `TEST_ENDPOINT` | API base URL |
| `TEST_CASINO_GAME_CODE` | Casino game code |
| `TEST_LIVE_GAME_CODE` | Live casino game code |
| `TEST_BET_PRIMARY` | Primary bet amount |
| `TEST_WIN_PRIMARY` | Primary win amount |

---

## Project Structure

```
qa_tool/
├── app/
│   ├── Http/Controllers/
│   │   └── TestRunController.php    # API endpoint for running tests
│   ├── Models/
│   │   └── TestRun.php              # Test run database model
│   └── Services/
│       └── ApiTestRunner.php        # PHPUnit execution service
├── tests/
│   ├── Feature/
│   │   └── ApiTest.php              # Main test class
│   ├── Support/
│   │   └── AllureHttpHelpers.php    # Helper methods for tests
│   ├── Traits/
│   │   ├── S21CasinoScenario.php    # Casino test scenarios
│   │   ├── S31LiveCasinoRegularScenario.php
│   │   └── ...
│   └── Config/
│       └── Endpoint.php             # API endpoint configuration
├── docker/
│   └── nginx.conf                   # Nginx configuration
├── docker-compose.yml               # Docker services definition
├── Dockerfile                       # PHP-FPM image
└── phpunit.xml                      # PHPUnit configuration
```

---

## Troubleshooting

### Common Issues

**cURL Error 6 (DNS resolution) on Windows**
```bash
# The application handles this automatically by setting proper
# environment variables for the subprocess
```

**Allure reports not generating**
```bash
# Check if Java is installed (for local mode)
java -version

# Verify allure-commandline is installed
npx allure --version
```

**Permission errors in Docker**
```bash
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

**Database migration errors**
```bash
# Reset and re-run migrations
php artisan migrate:fresh
```

---

## License

This project is proprietary software.
