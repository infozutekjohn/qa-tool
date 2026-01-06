<?php

namespace Tests\Traits;

use PHPUnit\Framework\Attributes\Group;
use Qameta\Allure\Allure;
use Qameta\Allure\StepContextInterface;
use Qameta\Allure\Attribute\ParentSuite;
use Qameta\Allure\Attribute\Suite;
use Qameta\Allure\Attribute\DisplayName;
use Qameta\Allure\Attribute\Description;
use PHPUnit\Framework\Attributes\Test;
use Tests\Config\Endpoint;

trait S51ErrorHandlingScenario
{
    #[Group('error')]
    #[ParentSuite('05. Gameslink Casino Tests (error handling)')]
    #[Suite('2. Error handling')]
    #[DisplayName('Authenticate (invalid token)')]
    #[Description('Testing authentication with invalid token')]
    #[Test]
    public function authenticate_invalid_token(): void
    {
        // $this->requireGroup('error');
        // if (!$this->shouldRunGroup('error')) {
        //     return;
        // }

        $username = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => "InvalidTokenTest"
        ];

        $endpoint = Endpoint::playtech('authenticate');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Authenticate (invalid token) request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('errorType', 'Invalid Token');

                $response = $this->client->post($endpoint, [
                    'json' => $payload,
                    'http_errors' => false,
                ]);

                $body = (string)$response->getBody();
                $data = json_decode($body, true);
                return [$response, $body, $data];
            }
        );

        $checks = [];

        $this->validateApiResponse([
            "response"      => $response,
            "data"          => $data,
            "payload"       => $payload,
            "checks"        => $checks,
            "fullUrl"       => $fullUrl,
            "body"          => $body,
            "endpointType"  => 'error',
            "errorScenario" => true,
            "balanceAction" => null,
        ]);

        Allure::runStep(
            #[DisplayName('Verify error response contains ERR_AUTHENTICATION_FAILED')]
            function (StepContextInterface $step) use ($data, &$checks) {
                $this->assertArrayHasKey('error', $data, 'Response should contain error field');
                $this->assertEquals('ERR_AUTHENTICATION_FAILED', $data['error']['code'] ?? null, 'Error code should be ERR_AUTHENTICATION_FAILED');
                $checks[] = '[PASS] Error code is ERR_AUTHENTICATION_FAILED';
                $checks[] = '[PASS] Error description: ' . ($data['error']['description'] ?? 'N/A');
            }
        );

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('error')]
    #[ParentSuite('05. Gameslink Casino Tests (error handling)')]
    #[Suite('2. Error handling')]
    #[DisplayName('Authenticate (invalid username)')]
    #[Description('Testing authentication with invalid username')]
    #[Test]
    public function authenticate_invalid_username(): void
    {
        // $this->requireGroup('error');
        // if (!$this->shouldRunGroup('error')) {
        //     return;
        // }

        $token = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => "InvalidUsernameTest",
            "externalToken" => $token
        ];

        $endpoint = Endpoint::playtech('authenticate');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Authenticate (invalid username) request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('errorType', 'Invalid Username');

                $response = $this->client->post($endpoint, [
                    'json' => $payload,
                    'http_errors' => false,
                ]);

                $body = (string)$response->getBody();
                $data = json_decode($body, true);
                return [$response, $body, $data];
            }
        );

        $checks = [];

        $this->validateApiResponse([
            "response"      => $response,
            "data"          => $data,
            "payload"       => $payload,
            "checks"        => $checks,
            "fullUrl"       => $fullUrl,
            "body"          => $body,
            "endpointType"  => 'error',
            "errorScenario" => true,
            "balanceAction" => null,
        ]);

        Allure::runStep(
            #[DisplayName('Verify error response contains ERR_PLAYER_NOT_FOUND')]
            function (StepContextInterface $step) use ($data, &$checks) {
                $this->assertArrayHasKey('error', $data, 'Response should contain error field');
                $this->assertEquals('ERR_PLAYER_NOT_FOUND', $data['error']['code'] ?? null, 'Error code should be ERR_PLAYER_NOT_FOUND');
                $checks[] = '[PASS] Error code is ERR_PLAYER_NOT_FOUND';
            }
        );

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('error')]
    #[ParentSuite('05. Gameslink Casino Tests (error handling)')]
    #[Suite('2. Error handling')]
    #[DisplayName('Bet (insufficient funds)')]
    #[Description('Testing bet with insufficient funds')]
    #[Test]
    public function bet_insufficient_funds(): void
    {
        // $this->requireGroup('error');
        // if (!$this->shouldRunGroup('error')) {
        //     return;
        // }

        $username     = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token        = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $rngGameCode  = getenv('TEST_RNG_GAME_CODE') ?: 'gpas_buddhaways_pop';

        $date = $this->generateDate();

        $currentBalance  = $this->getTrackedBalance();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => uniqid('test_') . bin2hex(random_bytes(8)),
            "transactionCode" => uniqid('test_') . bin2hex(random_bytes(4)),
            "transactionDate" => $date,
            "amount" => $currentBalance + 1,
            "internalFundChanges" => [],
            "gameCodeName" => $rngGameCode
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Bet (insufficient funds) request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('errorType', 'Insufficient Funds');
                $step->parameter('amount', $payload['amount']);

                $response = $this->client->post($endpoint, [
                    'json' => $payload,
                    'http_errors' => false,
                ]);

                $body = (string)$response->getBody();
                $data = json_decode($body, true);
                return [$response, $body, $data];
            }
        );

        $checks = [];

        $this->validateApiResponse([
            "response"      => $response,
            "data"          => $data,
            "payload"       => $payload,
            "checks"        => $checks,
            "fullUrl"       => $fullUrl,
            "body"          => $body,
            "endpointType"  => 'error',
            "errorScenario" => true,
            "balanceAction" => null,
        ]);

        Allure::runStep(
            #[DisplayName('Verify error response contains ERR_INSUFFICIENT_FUNDS')]
            function (StepContextInterface $step) use ($data, &$checks) {
                $this->assertArrayHasKey('error', $data, 'Response should contain error field');
                $this->assertEquals('ERR_INSUFFICIENT_FUNDS', $data['error']['code'] ?? null, 'Error code should be ERR_INSUFFICIENT_FUNDS');
                $checks[] = '[PASS] Error code is ERR_INSUFFICIENT_FUNDS';
                $checks[] = '[PASS] Error description: ' . ($data['error']['description'] ?? 'N/A');
            }
        );

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('error')]
    #[ParentSuite('05. Gameslink Casino Tests (error handling)')]
    #[Suite('2. Error handling')]
    #[DisplayName('Bet (invalid externalToken)')]
    #[Description('Testing bet with invalid external token')]
    #[Test]
    public function bet_invalid_external_token(): void
    {
        // $this->requireGroup('error');
        // if (!$this->shouldRunGroup('error')) {
        //     return;
        // }

        $username    = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $rngGameCode = getenv('TEST_RNG_GAME_CODE') ?: 'gpas_buddhaways_pop';

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => "InvalidTokenTestValue",
            "gameRoundCode" => uniqid('test_') . bin2hex(random_bytes(8)),
            "transactionCode" => uniqid('test_') . bin2hex(random_bytes(4)),
            "transactionDate" => $date,
            "amount" => "1",
            "internalFundChanges" => [],
            "gameCodeName" => $rngGameCode
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Bet (invalid externalToken) request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('errorType', 'Invalid External Token');

                $response = $this->client->post($endpoint, [
                    'json' => $payload,
                    'http_errors' => false,
                ]);

                $body = (string)$response->getBody();
                $data = json_decode($body, true);
                return [$response, $body, $data];
            }
        );

        $checks = [];

        $this->validateApiResponse([
            "response"      => $response,
            "data"          => $data,
            "payload"       => $payload,
            "checks"        => $checks,
            "fullUrl"       => $fullUrl,
            "body"          => $body,
            "endpointType"  => 'error',
            "errorScenario" => true,
            "balanceAction" => null,
        ]);

        Allure::runStep(
            #[DisplayName('Verify error response contains ERR_AUTHENTICATION_FAILED')]
            function (StepContextInterface $step) use ($data, &$checks) {
                $this->assertArrayHasKey('error', $data, 'Response should contain error field');
                $this->assertEquals('ERR_AUTHENTICATION_FAILED', $data['error']['code'] ?? null, 'Error code should be ERR_AUTHENTICATION_FAILED');
                $checks[] = '[PASS] Error code is ERR_AUTHENTICATION_FAILED';
                $checks[] = '[PASS] Error description: ' . ($data['error']['description'] ?? 'N/A');
            }
        );

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('error')]
    #[ParentSuite('05. Gameslink Casino Tests (error handling)')]
    #[Suite('2. Error handling')]
    #[DisplayName('Bet (invalid format)')]
    #[Description('Testing bet with invalid format')]
    #[Test]
    public function bet_invalid_format(): void
    {
        // $this->requireGroup('error');
        // if (!$this->shouldRunGroup('error')) {
        //     return;
        // }

        $username = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token    = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';

        $date = $this->generateDate();

        // Intentionally malformed payload: missing transactionCode (typo: transactionCod) and empty gameCodeName
        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => uniqid('test_') . bin2hex(random_bytes(8)),
            "transactionCod" => uniqid('test_') . bin2hex(random_bytes(4)),
            "transactionDate" => $date,
            "amount" => "0",
            "internalFundChanges" => [],
            "gameCodeName" => ""
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Bet (invalid format) request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('errorType', 'Invalid Format');

                $response = $this->client->post($endpoint, [
                    'json' => $payload,
                    'http_errors' => false,
                ]);

                $body = (string)$response->getBody();
                $data = json_decode($body, true);
                return [$response, $body, $data];
            }
        );

        $checks = [];

        $this->validateApiResponse([
            "response"      => $response,
            "data"          => $data,
            "payload"       => $payload,
            "checks"        => $checks,
            "fullUrl"       => $fullUrl,
            "body"          => $body,
            "endpointType"  => 'error',
            "errorScenario" => true,
            "balanceAction" => null,
        ]);

        Allure::runStep(
            #[DisplayName('Verify error response contains INVALID_REQUEST_PAYLOAD')]
            function (StepContextInterface $step) use ($data, &$checks) {
                $this->assertArrayHasKey('error', $data, 'Response should contain error field');
                $this->assertEquals('INVALID_REQUEST_PAYLOAD', $data['error']['code'] ?? null, 'Error code should be INVALID_REQUEST_PAYLOAD');
                $checks[] = '[PASS] Error code is INVALID_REQUEST_PAYLOAD';
                $checks[] = '[PASS] Error description: ' . ($data['error']['description'] ?? 'N/A');
            }
        );

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

}
