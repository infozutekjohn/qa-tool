# QA Tool - Coding Standards & Conventions

This document defines the coding standards for this Laravel + React QA Testing Tool project. These standards MUST be automatically applied when generating or modifying code.

## PHP Coding Standards (PSR-12 + Laravel)

### File Structure
- Use `<?php` opening tag (no closing tag)
- Namespace declarations on line 3 (after `<?php` and blank line)
- Use statements grouped and alphabetically sorted:
  1. PHP built-in classes (DateTime, Exception, etc.)
  2. Third-party packages (GuzzleHttp, Qameta\Allure, etc.)
  3. Internal classes (Tests\Config, App\Models, etc.)
- One blank line between use statement groups
- One blank line before class/trait declaration

### Variable Naming & Alignment
```php
// CORRECT - Aligned assignment operators for related variables
$username       = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
$token          = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
$casinoGameCode = getenv('TEST_CASINO_GAME_CODE');
$betPrimary     = getenv('TEST_BET_PRIMARY');

// INCORRECT - Unaligned
$username = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
$token = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
```

### Test Trait Structure (PHPUnit + Allure)
```php
<?php

namespace Tests\Traits;

use DateTime;
use GuzzleHttp\Psr7\Message;
use Qameta\Allure\Allure;
use Qameta\Allure\StepContextInterface;
use Qameta\Allure\Attribute\ParentSuite;
use Qameta\Allure\Attribute\Suite;
use Qameta\Allure\Attribute\SubSuite;
use Qameta\Allure\Attribute\DisplayName;
use Qameta\Allure\Attribute\Description;
use PHPUnit\Framework\Attributes\Test;
use Tests\Config\Endpoint;

trait S##ScenarioName
{
    #[ParentSuite('XX. Parent Suite Name')]
    #[Suite('X.X Suite Name')]
    #[Displayname('Action | Context | Description')]
    #[Description('Testing description')]
    #[Test]
    public function test_method_name(): void
    {
        // 1. Get round code for test group
        $roundCode = $this->getRoundCode('scenario_group_name');

        // 2. Get environment variables (aligned)
        $username       = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token          = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $casinoGameCode = getenv('TEST_CASINO_GAME_CODE');
        $betPrimary     = getenv('TEST_BET_PRIMARY');

        // 3. Generate date
        $date = $this->generateDate();

        // 4. Build payload (double-quoted keys)
        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            // ... more fields
        ];

        // 5. Get endpoint
        $endpoint = Endpoint::playtech('bet');

        // 6. Build full URL for logging
        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        // 7. Execute request in Allure step
        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);

                $response = $this->client->post($endpoint, [
                    'json' => $payload,
                ]);

                $body = (string)$response->getBody();
                $data = json_decode($body, true);
                return [$response, $body, $data];
            }
        );

        // 8. Initialize checks array
        $checks = [];

        // 9. Attach request/response to Allure
        $this->attachHttpRequestAndResponse($fullUrl, $payload, $response, $body);

        // 10. Run assertions
        $this->stepAssertStatus($response, 200, $checks);
        $this->stepAssertNoErrorField($data);
        $this->stepAssertRequestIdMatches($payload, $data);
        $this->stepAssertTransactionResponseSchema($data, $checks);

        // 11. Attach validation summary
        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }
}
```

### Naming Conventions

#### Test Trait Files
- Pattern: `S{XX}{ScenarioName}Scenario.php`
- Casino Scenarios (S21-S29):
  - `S21CasinoScenario.php` (2.1 Regular Gameround)
  - `S22CasinoScenario.php` (2.2 Regular Gameround with Jackpot)
  - `S27JackpotWinFeatureScenario.php` (2.7 Jackpot Win Through Feature)
  - `S28RefundScenario.php` (2.8 Regular Refund)
  - `S29ForwardCompatibilityScenario.php` (2.9 Forward Compatibility)
- Live Casino Scenarios (S31-S40):
  - `S31LiveCasinoRegularScenario.php` (3.1 Regular Live Casino)
  - `S32LiveCasinoJackpotScenario.php` (3.2 Live Casino Jackpot)
  - `S33LiveCasinoRetriesScenario.php` (3.3 Idempotent Retries)
  - `S34LiveCasinoMultiseatScenario.php` (3.4 Multiseat)
  - `S35LiveCasinoBonusScenario.php` (3.5 Bonus)
  - `S36LiveCasinoRefundScenario.php` (3.6 Refund)
  - `S37LiveCasinoPartialRefundScenario.php` (3.7 Partial Refund)
  - `S38LiveCasinoFullRefundScenario.php` (3.8 Full Refund)
  - `S39LiveCasinoTipScenario.php` (3.9 Live Tip)
  - `S40LiveCasinoForwardCompatibilityScenario.php` (3.10 Forward Compatibility)

#### Test Method Names
- Use snake_case: `bet_no_win`, `result_win_jackpot`, `bet_for_refund`
- Start with action: `bet_`, `result_`, `login_`, `logout_`
- Include context: `_no_win`, `_win_jackpot`, `_forward_compatibility`

#### Round Code Groups
- Use snake_case descriptive names
- Examples:
  - `'regular_gameround_scenario_1'`
  - `'regular_gameround_scenario_with_jackpot_1'`
  - `'jackpot_win_feature'`
  - `'refund_scenario'`
  - `'forward_compatibility'`

### Allure Suite Naming
```php
#[ParentSuite('02. Gameslink Casino Tests (casino flows)')]  // XX. Category
#[Suite('2.1 Regular Gameround Scenario')]                    // X.X Feature
#[Displayname('Bet | Casino | Regular gameround scenario')]   // Action | Context | Description
#[Description('Testing wallet regular bet response')]         // Full description
```

### Payload Structure
- Use double quotes for array keys (JSON-style)
- Consistent field ordering:
  1. `requestId`
  2. `username`
  3. `externalToken`
  4. `gameRoundCode`
  5. `transactionCode` / `pay` / `gameRoundClose`
  6. Other fields
  7. `gameCodeName`
  8. `gameHistoryUrl` (if applicable)

### Comments
- Use `//` for single-line comments with space after
- No PHPDoc blocks unless documenting public API
- Numbered steps in test methods: `// 1. Get round code`, `// 2. Get environment variables`

## JavaScript/React Standards (Prettier)

### Configuration
```json
{
  "semi": true,
  "singleQuote": true,
  "tabWidth": 4,
  "trailingComma": "es5"
}
```

### Component Structure
- Use functional components with hooks
- Props destructuring in function parameters
- Named exports for components

## Git Commit Messages
- Format: `type: brief description`
- Types: `feat`, `fix`, `refactor`, `test`, `docs`, `chore`
- Include emoji footer: `Generated with [Claude Code]`

## Environment Variables
- Test variables prefixed with `TEST_`
- Examples:
  - `TEST_USERNAME`
  - `TEST_TOKEN`
  - `TEST_ENDPOINT`
  - `TEST_CASINO_GAME_CODE`
  - `TEST_BET_PRIMARY`
  - `TEST_WIN_PRIMARY`

## Helper Methods Available

### Core Methods
- `$this->getRoundCode(string $group)` - Get/create round code for test group
- `$this->setTransactionCode(string $key, string $value)` - Store transaction code
- `$this->getTransactionCode(string $key)` - Retrieve stored transaction code
- `$this->generateDate()` - Generate ISO date with microseconds
- `$this->generateRandomJackpotId()` - Generate random jackpot ID

### Allure Attachment Methods
- `$this->attachHttpRequestAndResponse(...)` - Attach HTTP artifacts to Allure

### Assertion Methods
- `$this->stepAssertStatus(...)` - Assert HTTP status in Allure step
- `$this->stepAssertNoErrorField(...)` - Assert no error field
- `$this->stepAssertRequestIdMatches(...)` - Assert requestId matches
- `$this->stepAssertTransactionResponseSchema(...)` - Assert response schema

### Balance Tracking Methods (Lenient - log warnings instead of failing)
- `$this->getTrackedBalance()` - Get current tracked balance
- `$this->updateTrackedBalance(array $data)` - Update tracked balance from response
- `$this->stepAssertBalanceDeducted($data, $betAmount, &$checks)` - Assert balance deducted after bet
- `$this->stepAssertBalanceWinAdded($data, $winAmount, $message, &$checks)` - Assert balance increased after win
- `$this->stepAssertBalanceUnchanged($data, $message, &$checks)` - Assert balance unchanged (for idempotent retries)

### Timestamp Assertion Methods
- `$this->stepAssertTimestampFormat($data, &$checks)` - Assert timestamp format (YYYY-MM-DD HH:mm:ss.SSS)
- `$this->stepAssertTimestampGMT($data, &$checks)` - Assert timestamp is in GMT

## Auto-Apply Rules
When modifying or creating code in this project:
1. ALWAYS check existing similar files for patterns
2. ALWAYS align variable assignments for related variables
3. ALWAYS use the established test method structure
4. ALWAYS follow the naming conventions
5. NEVER add unnecessary comments or documentation
6. NEVER change established patterns without explicit request
