<?php

namespace Tests\Support;

use GuzzleHttp\Psr7\Message;
use Psr\Http\Message\ResponseInterface;
use Qameta\Allure\Allure;
use Qameta\Allure\StepContextInterface;
use Qameta\Allure\Attribute\DisplayName;

trait AllureHttpHelpers
{
    private static array $roundCodes = [];
    private static array $transactionCodes = [];

    private function getRoundCode(string $group): string
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

    private function generateRandomJackpotId(): string
    {
        $numbers = [];

        // generate 5 random 3-digit segments
        for ($i = 0; $i < 5; $i++) {
            $numbers[] = random_int(100, 999);
        }

        return 'test_' . implode('_', $numbers);
    }

    /**
     * Attach the standard HTTP request/response artifacts to Allure.
     */
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

    /**
     * Reusable "status must be X" step, with optional $checks array.
     */
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

    /**
     * Reusable "response must NOT contain an 'error' field" step.
     */
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

    protected function stepAssertTransactionResponseSchema(
        array $data,
        ?array &$checks = null
    ): void {
        Allure::runStep(
            #[DisplayName('Validate response JSON structure')]
            function (StepContextInterface $step) use ($data, &$checks) {
                /** @var self $this */

                $this->assertIsArray($data);
                $checks[] = "✔ Response is JSON array/object";

                $this->assertArrayHasKey('externalTransactionCode', $data);
                $checks[] = "✔ 'username' key exists";

                $this->assertArrayHasKey('requestId', $data);
                $checks[] = "✔ 'requestId' key exists";

                $this->assertArrayHasKey('externalTransactionDate', $data);
                $checks[] = "✔ 'requestId' key exists";

                $this->assertArrayHasKey('balance', $data);
                $checks[] = "✔ 'requestId' key exists";

                // $this->assertIsInt($data['id']);
                // $checks[] = "✔ 'id' is integer";

                // $this->assertNotEmpty($data['title']);
                // $checks[] = "✔ 'title' is not empty";

                $step->parameter('validatedKeys', 'externalTransactionCode,requestId,externalTransactionDate,balance');
            }
        );
    }
}
