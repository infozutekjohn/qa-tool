# Project Notes: 2025-12-09

## Summary
Fixed cURL error 6 DNS threading issue on Windows and added automation command for running tests.

## Changes Made

### 1. Fixed cURL Error 6 (DNS Threading Issue)
**Files Modified:**
- `tests/Feature/ApiTest.php`
- `app/Services/ApiTestRunner.php`

**Problem:**
Windows PHP built-in server has DNS threading limitations causing `cURL error 6: getaddrinfo() thread failed to start` when making HTTP requests to external APIs.

**Solution:**
- Added `force_ip_resolve => 'v4'` to Guzzle client configuration
- Added `CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4` to force IPv4 resolution
- Added `CURLOPT_DNS_CACHE_TIMEOUT => 120` to cache DNS lookups
- Increased timeout from 10s to 30s with separate connect_timeout of 10s
- Added TEMP, TMP, and SystemRoot environment variables to PHPUnit process

**Impact:**
- Tests now run reliably without DNS threading failures
- Works on both local development (php artisan serve) and Docker environments

### 2. Fixed PHP Execution Timeout
**File Modified:**
- `app/Http/Controllers/TestRunController.php`

**Problem:**
Default 30-second PHP max_execution_time was too short for PHPUnit test runs.

**Solution:**
- Added `set_time_limit(300)` (5 minutes) at the start of the store method

**Impact:**
- Long-running test suites complete without timeout errors

### 3. Added Transaction Code Storage
**File Modified:**
- `tests/Support/AllureHttpHelpers.php`

**Changes:**
- Added `$transactionCodes` static array
- Added `setTransactionCode()` and `getTransactionCode()` helper methods

**Impact:**
- Tests can now store and retrieve transaction codes between test methods

### 4. Implemented Forward Compatibility Tests
**File Modified:**
- `tests/Traits/S29ForwardCompatibilityScenario.php`

**Changes:**
- Implemented `bet_forward_compatibility()` test
- Implemented `result_forward_compatibility_no_win()` test
- Added `forwardCompatibilityCheck` field to payloads

**Impact:**
- New test coverage for forward compatibility scenarios

### 5. Added Automation Command
**File Created:**
- `.claude/commands/run-automated.md`

**Purpose:**
Run `/run-automated` to execute tests with pre-configured credentials:
- Username: HH88_K476502
- Endpoint: https://api-uat.agmidway.net
- Casino Game: gpas_gstorm2_pop
- Live Game: ubal

## Test Results
- Test Run #9: phpunit_exit=0 (all passed)
- Test Run #10: phpunit_exit=1 (some incomplete tests)

## Technical Details

### Guzzle Client Configuration (ApiTest.php:58-69)
```php
$this->client = new Client([
    'base_uri' => $endpoint,
    'timeout'  => 30,
    'connect_timeout' => 10,
    'verify'   => false,
    'force_ip_resolve' => 'v4',
    'curl' => [
        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        CURLOPT_DNS_CACHE_TIMEOUT => 120,
    ],
]);
```

### Environment Variables for PHPUnit (ApiTestRunner.php:57-60)
```php
'TEMP' => $tempDir,
'TMP' => $tempDir,
'SystemRoot' => getenv('SystemRoot') ?: 'C:\\Windows',
```
