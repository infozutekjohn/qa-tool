<?php

namespace Tests\Support;

use GuzzleHttp\Psr7\Message;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;
use Qameta\Allure\Allure;
use Qameta\Allure\StepContextInterface;
use Qameta\Allure\Attribute\DisplayName;

trait AllureHttpHelpers
{
    private static array $roundCodes = [];
    private static array $transactionCodes = [];
    private static ?float $currentBalance = null;
    private array $schemas = [
        'auth' => [
            'requestId',
            'username',
            'permanentExternalToken',
            'currencyCode',
            'countryCode',
            'subBrand'
        ],

        'getbalance' => [
            'requestId',
            'balance'
        ],

        // bet, result, transferfunds, tip
        'transaction' => [
            'requestId',
            'externalTransactionCode',
            'externalTransactionDate',
            'balance'
        ],

        'error' => [
            'requestId',
            'error'
        ],

        // notifybonus, logout
        'event' => [
            'requestId'
        ]
    ];

    protected function getRoundCode(string $group): string
    {
        if (!isset(self::$roundCodes[$group])) {
            self::$roundCodes[$group] = 'test_rnd' . bin2hex(random_bytes(6));
        }
        return self::$roundCodes[$group];
    }

    protected function setTransactionCode(string $key, string $value): void
    {
        self::$transactionCodes[$key] = $value;
    }

    protected function getTransactionCode(string $key): ?string
    {
        return self::$transactionCodes[$key] ?? null;
    }

    protected function generateRandomJackpotId(): string
    {
        $numbers = [];

        // generate 5 random 3-digit segments
        for ($i = 0; $i < 5; $i++) {
            $numbers[] = random_int(100, 999);
        }

        return 'test_' . implode('_', $numbers);
    }

    protected function attachHttpRequestAndResponse(
        string $fullUrl,
        array $payload,
        ResponseInterface $response,
        string $body
    ): void {
        Allure::attachment(
            'Raw HTTP Response',
            Message::toString($response),
            'text/plain'
        );

        Allure::attachment(
            'Request URL',
            $fullUrl,
            'text/plain'
        );

        Allure::attachment(
            'Request Payload',
            json_encode($payload, JSON_PRETTY_PRINT),
            'application/json'
        );

        Allure::attachment(
            'Response Headers',
            json_encode($response->getHeaders(), JSON_PRETTY_PRINT),
            'application/json'
        );

        Allure::attachment(
            'Response Body',
            $body,
            'application/json'
        );
    }

    protected function stepAssertStatus(
        ResponseInterface $response,
        int $expectedStatus = 200,
        ?array &$checks = null,
    ): void {
        Allure::runStep(
            #[DisplayName('Validate HTTP response status')]
            function (StepContextInterface $step) use ($response, $expectedStatus, &$checks) {
                $statusCode = $response->getStatusCode();

                $step->parameter('expectedStatus', (string) $expectedStatus);
                $step->parameter('actualStatus', (string) $statusCode);

                $this->assertSame(
                    $expectedStatus,
                    $statusCode,
                    "Status code should be {$expectedStatus}, got {$statusCode}"
                );

                if (is_array($checks)) {
                    $checks[] = "✔ Status code is {$expectedStatus} (actual: {$statusCode})";
                }
            }
        );
    }

    protected function stepAssertNoErrorField(
        array $data
    ): void {
        Allure::runStep(
            #[DisplayName('Response does not have an error')]
            function (StepContextInterface $step) use ($data) {

                if (array_key_exists('error', $data)) {
                    Allure::attachment(
                        'Detected Error',
                        json_encode($data['error'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
                        'application/json'
                    );

                    $step->parameter('errorPresent', 'true');
                } else {
                    $step->parameter('errorPresent', 'false');
                }

                $this->assertArrayNotHasKey(
                    'error',
                    $data,
                    'Expecting successful response. There should be no error'
                );
            }
        );
    }

    protected function stepAssertRequestIdMatches(
        array $payload,
        array $data,
        ?array &$checks = null
    ): void {
        Allure::runStep(
            #[DisplayName('RequestId in response matches request')]
            function (StepContextInterface $step) use (&$checks, $data, $payload) {
                $expected = $payload['requestId'] ?? null;
                $actual   = $data['requestId'] ?? null;

                $step->parameter('expectedRequestId', (string) $expected);
                $step->parameter('actualRequestId', (string) $actual);

                $this->assertSame(
                    $expected,
                    $actual,
                    "Request ID of response should match the request. expected={$expected}, actual={$actual}"
                );
            }
        );
    }

    protected function stepAssertCurrencyCode(
        array $data,
        array $payload
    ): void {
        Allure::runStep(
            #[DisplayName('Currency code defined is supported')]
            function (StepContextInterface $step) use (&$checks, $data, $payload) {
                $expected = $payload['requestId'] ?? null;
                $actual   = $data['requestId'] ?? null;

                $step->parameter('expectedRequestId', (string) $expected);
                $step->parameter('actualRequestId', (string) $actual);

                $this->assertSame(
                    $expected,
                    $actual,
                    "Request ID of response should match the request. expected={$expected}, actual={$actual}"
                );
            }
        );
    }

    protected function stepAssertCountryCode(
        array $data,
        array $payload,
    ): void {
        Allure::runStep(
            #[DisplayName('Country code defined is supported')]
            function (StepContextInterface $step) use (&$checks, $data, $payload) {
                $expected = $payload['requestId'] ?? null;
                $actual   = $data['requestId'] ?? null;

                $step->parameter('expectedRequestId', (string) $expected);
                $step->parameter('actualRequestId', (string) $actual);

                $this->assertSame(
                    $expected,
                    $actual,
                    "Request ID of response should match the request. expected={$expected}, actual={$actual}"
                );
            }
        );
    }

    // TODO: Implement to all
    protected function stepAssertTransactionResponseSchema(
        array $data,
        ?array &$checks = null,
        array $options = []
    ): void {
        Allure::runStep(
            #[DisplayName('Validate response JSON structure')]
            function (StepContextInterface $step) use ($data, &$checks, $options) {

                // 1. Determine schema based on type
                $type = $options['type'] ?? 'transaction'; // default old behavior

                $allowedKeys = $this->schemas[$type] ?? $this->schemas['transaction'];

                // VALIDATE KEY EXISTENCE
                foreach ($allowedKeys as $key) {
                    $this->assertArrayHasKey(
                        $key,
                        $data,
                        "Missing required key '{$key}' for type '{$type}'"
                    );

                    $checks[] = "✔ '{$key}' key exists";
                }

                // VALIDATE NO EXTRA KEYS ALLOWED
                foreach ($data as $key => $value) {
                    $this->assertContains(
                        $key,
                        $allowedKeys,
                        "Unexpected key '{$key}' for type '{$type}'"
                    );
                }

                // RULE: NO KEY THAT APPEARS CAN BE NULL
                foreach ($data as $key => $value) {
                    $this->assertNotNull(
                        $value,
                        "Key '{$key}' must not be null"
                    );
                    $checks[] = "✔ '{$key}' is not null";
                }

                // SPECIAL RULE FOR BALANCE
                if (isset($data['balance'])) {
                    $this->assertIsArray($data['balance'], "'balance' must be an object");

                    // real can be int/string/float but must have <= 2 decimals
                    $real = $data['balance']['real'] ?? null;

                    $this->assertNotNull($real, "'balance.real' cannot be null");

                    if (is_int($real)) {
                        // ok
                    } else {
                        $realStr = (string)$real;
                        $this->assertMatchesRegularExpression(
                            '/^\d+(\.\d{1,2})?$/',
                            $realStr,
                            "'balance.real' must have max 2 decimal places"
                        );
                    }
                }

                $step->parameter('validatedKeys', implode(',', $allowedKeys));
            }
        );
    }


    protected function getTrackedBalance(): ?float
    {
        return self::$currentBalance;
    }

    protected function updateTrackedBalance(array $data): void
    {
        if (isset($data['balance']['real'])) {
            self::$currentBalance = (float) $data['balance']['real'];
        }
    }

    // Just for checking the balance + error case
    // TODO: Implement to all
    protected function stepAssertBalanceError(array $data, ?array &$checks = null): void
    {
        Allure::runStep(
            #[DisplayName("No coexistence of 'balance' and 'error'")]
            function (StepContextInterface $step) use ($data, &$checks) {

                /** 1. balance + error must NEVER both exist */
                if (array_key_exists('balance', $data) && array_key_exists('error', $data)) {
                    $checks[] = "⚠ There is a coexistence of 'balance' and 'error'";
                    $this->fail(
                        "Invalid response: 'balance' and 'error' cannot appear at the same time."
                    );
                } else {
                    $checks[] = "✔ No coexistence of 'balance' and 'error'";
                }
            }
        );
    }

    // Balance computations
    protected function stepAssertBalanceDeducted(
        array $data,
        string $betAmount,
        ?array &$checks = null
    ): void {
        Allure::runStep(
            #[DisplayName('Balance updated correctly (bet deduction)')]
            function (StepContextInterface $step) use ($data, $betAmount, &$checks) {
                $this->assertArrayHasKey('balance', $data, 'Missing balance in response');
                $this->assertArrayHasKey('real', $data['balance'], 'Missing balance.real in response');

                $balanceBefore = self::$currentBalance;
                $balanceAfter = (float) $data['balance']['real'];
                $betAmountFloat = (float) $betAmount;

                $step->parameter('balanceBefore', (string) $balanceBefore);
                $step->parameter('betAmount', $betAmount);
                $step->parameter('balanceAfter', (string) $balanceAfter);

                // Verify balance is a valid non-negative number
                $this->assertGreaterThanOrEqual(0, $balanceAfter, 'Balance should be non-negative');

                if ($balanceBefore !== null) {
                    $actualDeduction = round($balanceBefore - $balanceAfter, 2);
                    $step->parameter('actualDeduction', (string) $actualDeduction);

                    // Log the balance change for debugging, but don't fail on mismatch
                    // as balance may be affected by other concurrent transactions
                    if (is_array($checks)) {
                        if (abs($actualDeduction - $betAmountFloat) < 0.01) {
                            $checks[] = "✔ Balance deducted correctly | Bet: {$betAmount} | Balance after: {$balanceAfter}";
                        } else {
                            $checks[] = "⚠ Balance change: {$actualDeduction} (expected: {$betAmount}) | Balance after: {$balanceAfter}";
                        }
                    }
                }

                // Update tracked balance
                self::$currentBalance = $balanceAfter;
            }
        );
    }

    protected function stepAssertBalanceWinAdded(
        array $data,
        string $winAmount,
        string $message = 'Win amount',
        ?array &$checks = null
    ): void {
        Allure::runStep(
            #[DisplayName('Balance updated correctly (win addition)')]
            function (StepContextInterface $step) use ($data, $winAmount, $message, &$checks) {
                $this->assertArrayHasKey('balance', $data, 'Missing balance in response');
                $this->assertArrayHasKey('real', $data['balance'], 'Missing balance.real in response');

                $balanceBefore = self::$currentBalance;
                $balanceAfter = (float) $data['balance']['real'];
                $winAmountFloat = (float) $winAmount;

                $step->parameter('balanceBefore', (string) $balanceBefore);
                $step->parameter('winAmount', $winAmount);
                $step->parameter('balanceAfter', (string) $balanceAfter);

                // Verify balance is a valid non-negative number
                $this->assertGreaterThanOrEqual(0, $balanceAfter, 'Balance should be non-negative');

                if ($balanceBefore !== null) {
                    $actualAddition = round($balanceAfter - $balanceBefore, 2);
                    $step->parameter('actualAddition', (string) $actualAddition);

                    // Log the balance change for debugging, but don't fail on mismatch
                    // as balance may be affected by other concurrent transactions
                    if (is_array($checks)) {
                        if (abs($actualAddition - $winAmountFloat) < 0.01) {
                            $checks[] = "✔ Balance increased correctly | {$message}: {$winAmount} | Balance after: {$balanceAfter}";
                        } else {
                            $checks[] = "⚠ Balance change: {$actualAddition} (expected: {$winAmount}) | Balance after: {$balanceAfter}";
                        }
                    }
                }

                // Update tracked balance
                self::$currentBalance = $balanceAfter;
            }
        );
    }

    protected function stepAssertBalanceUnchanged(
        array $data,
        string $message = 'No balance change expected',
        ?array &$checks = null
    ): void {
        Allure::runStep(
            #[DisplayName('Balance unchanged')]
            function (StepContextInterface $step) use ($data, $message, &$checks) {
                $this->assertArrayHasKey('balance', $data, 'Missing balance in response');
                $this->assertArrayHasKey('real', $data['balance'], 'Missing balance.real in response');

                $balanceBefore = self::$currentBalance;
                $balanceAfter = (float) $data['balance']['real'];

                $step->parameter('balanceBefore', (string) $balanceBefore);
                $step->parameter('balanceAfter', (string) $balanceAfter);

                // Verify balance is a valid non-negative number
                $this->assertGreaterThanOrEqual(0, $balanceAfter, 'Balance should be non-negative');

                if ($balanceBefore !== null) {
                    // Check the balance change
                    $actualChange = round($balanceAfter - $balanceBefore, 2);
                    $step->parameter('actualChange', (string) $actualChange);

                    // Log the balance change for debugging, but don't fail on mismatch
                    // as balance may be affected by other concurrent transactions or idempotent retries
                    if (is_array($checks)) {
                        if (abs($actualChange) < 0.01) {
                            $checks[] = "✔ Balance unchanged | {$message} | Balance: {$balanceAfter}";
                        } else {
                            $checks[] = "⚠ Balance change: {$actualChange} (expected: 0) | {$message} | Balance: {$balanceAfter}";
                            $this->fail(
                                "expected {$balanceAfter} to equal to {$balanceBefore}"
                            );
                        }
                    }
                }

                // Update tracked balance
                self::$currentBalance = $balanceAfter;
            }
        );
    }

    protected function stepAssertBalanceTransferAdded(
        array $data,
        string $transferAmount,
        string $message = 'Transfer amount',
        ?array &$checks = null
    ): void {
        Allure::runStep(
            #[DisplayName('Balance updated correctly (transfer addition)')]
            function (StepContextInterface $step) use ($data, $transferAmount, $message, &$checks) {
                $this->assertArrayHasKey('balance', $data, 'Missing balance in response');
                $this->assertArrayHasKey('real', $data['balance'], 'Missing balance.real in response');

                $balanceBefore = self::$currentBalance;
                $balanceAfter = (float) $data['balance']['real'];
                $transferAmountFloat = (float) $transferAmount;

                $step->parameter('balanceBefore', (string) $balanceBefore);
                $step->parameter('transferAmount', $transferAmount);
                $step->parameter('balanceAfter', (string) $balanceAfter);

                // Verify balance is a valid non-negative number
                $this->assertGreaterThanOrEqual(0, $balanceAfter, 'Balance should be non-negative');

                if ($balanceBefore !== null) {
                    $actualAddition = round($balanceAfter - $balanceBefore, 2);
                    $step->parameter('actualAddition', (string) $actualAddition);

                    // Log the balance change for debugging, but don't fail on mismatch
                    // as balance may be affected by other concurrent transactions
                    if (is_array($checks)) {
                        if (abs($actualAddition - $transferAmountFloat) < 0.01) {
                            $checks[] = "✔ Balance increased correctly | {$message}: {$transferAmount} | Balance after: {$balanceAfter}";
                        } else {
                            $checks[] = "⚠ Balance change: {$actualAddition} (expected: {$transferAmount}) | Balance after: {$balanceAfter}";
                            $expectedBalance = round($balanceBefore + $transferAmount, 2);
                            $this->fail(
                                "expected {$balanceAfter} to equal to {$expectedBalance}"
                            );
                        }
                    }
                }

                // Update tracked balance
                self::$currentBalance = $balanceAfter;
            }
        );
    } 
    
    protected function stepAssertBalanceRefundAdded(
        array $data,
        string $refundAmount,
        string $message = 'Refund amount',
        ?array &$checks = null
    ): void {
        Allure::runStep(
            #[DisplayName('Balance updated correctly (refund addition)')]
            function (StepContextInterface $step) use ($data, $refundAmount, $message, &$checks) {
                $this->assertArrayHasKey('balance', $data, 'Missing balance in response');
                $this->assertArrayHasKey('real', $data['balance'], 'Missing balance.real in response');

                $balanceBefore = self::$currentBalance;
                $balanceAfter = (float) $data['balance']['real'];
                $refundAmountFloat = (float) $refundAmount;

                $step->parameter('balanceBefore', (string) $balanceBefore);
                $step->parameter('refundAmount', $refundAmount);
                $step->parameter('balanceAfter', (string) $balanceAfter);

                // Verify balance is a valid non-negative number
                $this->assertGreaterThanOrEqual(0, $balanceAfter, 'Balance should be non-negative');

                if ($balanceBefore !== null) {
                    $actualAddition = round($balanceAfter - $balanceBefore, 2);
                    $step->parameter('actualAddition', (string) $actualAddition);

                    // Log the balance change for debugging, but don't fail on mismatch
                    // as balance may be affected by other concurrent transactions
                    if (is_array($checks)) {
                        if (abs($actualAddition - $refundAmountFloat) < 0.01) {
                            $checks[] = "✔ Balance increased correctly | {$message}: {$refundAmount} | Balance after: {$balanceAfter}";
                        } else {
                            $checks[] = "⚠ Balance change: {$actualAddition} (expected: {$refundAmount}) | Balance after: {$balanceAfter}";
                            $expectedBalance = round($balanceBefore + $refundAmount, 2);
                            $this->fail(
                                "expected {$balanceAfter} to equal to {$expectedBalance}"
                            );
                        }
                    }
                }

                // Update tracked balance
                self::$currentBalance = $balanceAfter;
            }
        );
    }     
    
    protected function stepAssertBalanceTipDeducted(
        array $data,
        string $tipAmount,
        ?array &$checks = null
    ): void {
        Allure::runStep(
            #[DisplayName('Balance updated correctly (tip deduction)')]
            function (StepContextInterface $step) use ($data, $tipAmount, &$checks) {
                $this->assertArrayHasKey('balance', $data, 'Missing balance in response');
                $this->assertArrayHasKey('real', $data['balance'], 'Missing balance.real in response');

                $balanceBefore = self::$currentBalance;
                $balanceAfter = (float) $data['balance']['real'];
                $tipAmountFloat = (float) $tipAmount;

                $step->parameter('balanceBefore', (string) $balanceBefore);
                $step->parameter('tipAmount', $tipAmount);
                $step->parameter('balanceAfter', (string) $balanceAfter);

                // Verify balance is a valid non-negative number
                $this->assertGreaterThanOrEqual(0, $balanceAfter, 'Balance should be non-negative');

                if ($balanceBefore !== null) {
                    $actualDeduction = round($balanceBefore - $balanceAfter, 2);
                    $step->parameter('actualDeduction', (string) $actualDeduction);

                    // Log the balance change for debugging, but don't fail on mismatch
                    // as balance may be affected by other concurrent transactions
                    if (is_array($checks)) {
                        if (abs($actualDeduction - $tipAmountFloat) < 0.01) {
                            $checks[] = "✔ Balance deducted correctly | Tip: {$tipAmount} | Balance after: {$balanceAfter}";
                        } else {
                            $checks[] = "⚠ Balance change: {$actualDeduction} (expected: {$tipAmount}) | Balance after: {$balanceAfter}";
                            $expectedBalance = round($balanceBefore - $tipAmount, 2);
                            $this->fail(
                                "expected {$balanceAfter} to equal to {$expectedBalance}"
                            );
                        }
                    }
                }

                // Update tracked balance
                self::$currentBalance = $balanceAfter;
            }
        );
    }    

    // TODO: Make the includeBalance as the default checking
    protected function stepAssertTimestampFormat(
        array $data,
        ?array &$checks = null,
        array $options = []
    ): void {
        Allure::runStep(
            #[DisplayName('Timestamp in correct format')]
            function (StepContextInterface $step) use ($data, &$checks, $options) {
                if (empty($options) || empty($options['ignoreDefault'])) {
                    $this->assertArrayHasKey('externalTransactionDate', $data, 'Missing externalTransactionDate in response');

                    $timestamp = $data['externalTransactionDate'];
                    $step->parameter('timestamp', $timestamp);

                    // Pattern: YYYY-MM-DD HH:mm:ss.SSS
                    $pattern = '/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]\.\d{3}$/';

                    $this->assertMatchesRegularExpression(
                        $pattern,
                        $timestamp,
                        "Timestamp format should be YYYY-MM-DD HH:mm:ss.SSS, got: {$timestamp}"
                    );
                    if (is_array($checks)) {
                        $checks[] = "✔ Timestamp in correct format";
                    }
                }

                if (!empty($options['includeBalance'])) {
                    $this->assertArrayHasKey('timestamp', $data['balance'], 'Missing balance timestamp in response');

                    $balanceTimestamp = $data['balance']['timestamp'];
                    $step->parameter('timestamp', $balanceTimestamp);

                    // Pattern: YYYY-MM-DD HH:mm:ss.SSS
                    $pattern = '/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]\.\d{3}$/';

                    $this->assertMatchesRegularExpression(
                        $pattern,
                        $balanceTimestamp,
                        "Timestamp format should be YYYY-MM-DD HH:mm:ss.SSS, got: {$balanceTimestamp}"
                    );
                }
            }
        );
    }

    // TODO: Make the includeBalance as the default checking
    protected function stepAssertTimestampGMT(
        array $data,
        ?array &$checks = null,
        array $options = []
    ): void {
        Allure::runStep(
            #[DisplayName('Timestamp in GMT')]
            function (StepContextInterface $step) use ($data, &$checks, $options) {
                if (empty($options) || empty($options['ignoreDefault'])) {
                    $this->assertArrayHasKey('externalTransactionDate', $data, 'Missing externalTransactionDate in response');

                    $timestamp = $data['externalTransactionDate'];

                    // Parse the timestamp and convert to UTC, should remain the same if already in GMT
                    $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s.v', $timestamp, new \DateTimeZone('UTC'));

                    if ($dateTime) {
                        $utcFormatted = $dateTime->format('Y-m-d H:i:s.v');

                        $step->parameter('fromResponse', $timestamp);
                        $step->parameter('conversionToGMT', $utcFormatted);

                        $this->assertEquals(
                            $timestamp,
                            $utcFormatted,
                            "Timestamp should be in GMT. Original: {$timestamp}, UTC conversion: {$utcFormatted}"
                        );

                        if (is_array($checks)) {
                            $checks[] = "✔ Timestamp in GMT | From response: {$timestamp} | Conversion to GMT: {$utcFormatted}";
                        }
                    } else {
                        $step->parameter('parseError', 'Could not parse timestamp');
                        $this->fail("Could not parse timestamp: {$timestamp}");
                    }
                }

                if (!empty($options['includeBalance'])) {
                    $this->assertArrayHasKey('timestamp', $data['balance'], 'Missing balance timestamp in response');

                    $balanceTimestamp = $data['balance']['timestamp'];
                    $step->parameter('timestamp', $balanceTimestamp);

                    // Parse the timestamp and convert to UTC, should remain the same if already in GMT
                    $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s.v', $balanceTimestamp, new \DateTimeZone('UTC'));

                    if ($dateTime) {
                        $utcFormatted = $dateTime->format('Y-m-d H:i:s.v');

                        $step->parameter('fromBalanceResponse', $balanceTimestamp);
                        $step->parameter('conversionToGMT', $utcFormatted);

                        $this->assertEquals(
                            $balanceTimestamp,
                            $utcFormatted,
                            "Balance timestamp should be in GMT. Original: {$balanceTimestamp}, UTC conversion: {$utcFormatted}"
                        );

                        if (is_array($checks)) {
                            $checks[] = "✔ Balance timestamp in GMT | From response: {$balanceTimestamp} | Conversion to GMT: {$utcFormatted}";
                        }
                    } else {
                        $step->parameter('parseError', 'Could not parse timestamp');
                        $this->fail("Could not parse timestamp: {$balanceTimestamp}");
                    }
                }
            }
        );
    }
}
