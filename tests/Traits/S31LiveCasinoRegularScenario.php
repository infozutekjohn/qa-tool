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

trait S31LiveCasinoRegularScenario
{
    #[Group('live')]
    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.1 Regular gameround scenarios')]
    #[DisplayName('Bet | Live Casino | Regular gameround scenario 1')]
    #[Description('Testing live casino bet for regular gameround scenario 1')]
    #[Test]
    public function live_bet_regular_scenario_1(): void
    {
        $roundCode = $this->getRoundCode('live_regular_scenario');

        $username     = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token        = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $launchAlias  = getenv('TEST_LAUNCH_ALIAS') ?: 'bal_baccaratko';
        $tableId      = getenv('TEST_TABLE_ID') ?: '1234';
        $tableName    = getenv('TEST_TABLE_NAME') ?: 'Integration Test';
        $betPrimary   = getenv('TEST_BET_PRIMARY') ?: '1';

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => $roundCode,
            "transactionCode" => uniqid('test_trx_'),
            "transactionDate" => $date,
            "amount" => $betPrimary,
            "internalFundChanges" => [],
            "gameCodeName" => $liveGameCode,
            "liveTableDetails" => [
                "launchAlias" => $launchAlias,
                "tableId" => $tableId,
                "tableName" => $tableName
            ]
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Bet request to endpoint')]
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

        $checks = [];

        $this->validateApiResponse([
            "response"      => $response,
            "data"          => $data,
            "payload"       => $payload,
            "checks"        => $checks,
            "fullUrl"       => $fullUrl,
            "body"          => $body,
            "endpointType"  => 'bet',
            "balanceAction" => 'deducted',
            "bet"           => $betPrimary,
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('live')]
    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.1 Regular gameround scenarios')]
    #[DisplayName('Bet | Live Casino | Regular gameround scenario 2')]
    #[Description('Testing live casino bet for regular gameround scenario 2')]
    #[Test]
    public function live_bet_regular_scenario_2(): void
    {
        $roundCode = $this->getRoundCode('live_regular_scenario_2');

        $username     = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token        = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $launchAlias  = getenv('TEST_LAUNCH_ALIAS') ?: 'bal_baccaratko';
        $tableId      = getenv('TEST_TABLE_ID') ?: '1234';
        $tableName    = getenv('TEST_TABLE_NAME') ?: 'Integration Test';
        $betPrimary   = getenv('TEST_BET_PRIMARY') ?: '1';

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => $roundCode,
            "transactionCode" => uniqid('test_trx_'),
            "transactionDate" => $date,
            "amount" => $betPrimary,
            "internalFundChanges" => [],
            "gameCodeName" => $liveGameCode,
            "liveTableDetails" => [
                "launchAlias" => $launchAlias,
                "tableId" => $tableId,
                "tableName" => $tableName
            ]
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Bet request to endpoint')]
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

        $checks = [];

        $this->validateApiResponse([
            "response"      => $response,
            "data"          => $data,
            "payload"       => $payload,
            "checks"        => $checks,
            "fullUrl"       => $fullUrl,
            "body"          => $body,
            "endpointType"  => 'bet',
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('live')]
    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.1 Regular gameround scenarios')]
    #[DisplayName('Gameroundresult (no win) | Live Casino | Regular gameround scenario')]
    #[Description('Testing live casino game round result with no win')]
    #[Test]
    public function live_result_no_win(): void
    {
        $roundCode = $this->getRoundCode('live_regular_scenario');

        $username     = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token        = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $launchAlias  = getenv('TEST_LAUNCH_ALIAS') ?: 'bal_baccaratko';
        $tableId      = getenv('TEST_TABLE_ID') ?: '1234';
        $tableName    = getenv('TEST_TABLE_NAME') ?: 'Integration Test';

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => $roundCode,
            "gameRoundClose" => [
                "date" => $date,
                "rngGeneratorId" => "SecureRandom",
                "rngSoftwareId" => "Casino CaGS 12.3.4.5"
            ],
            "gameCodeName" => $liveGameCode,
            "gameHistoryUrl" => "getgamehistory.php?ThisIsJustAutomatedTestDataOK",
            "liveTableDetails" => [
                "launchAlias" => $launchAlias,
                "tableId" => $tableId,
                "tableName" => $tableName
            ]
        ];

        $endpoint = Endpoint::playtech('gameroundresult');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send game round result request to endpoint')]
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

        $checks = [];

        $this->validateApiResponse([
            "response"      => $response,
            "data"          => $data,
            "payload"       => $payload,
            "checks"        => $checks,
            "fullUrl"       => $fullUrl,
            "body"          => $body,
            "endpointType"  => 'result',
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('live')]
    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.1 Regular gameround scenarios')]
    #[DisplayName('Gameroundresult (win) | Live Casino | Regular gameround scenario')]
    #[Description('Testing live casino game round result with win')]
    #[Test]
    public function live_result_win(): void
    {
        $roundCode = $this->getRoundCode('live_regular_scenario_2');

        $username     = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token        = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $launchAlias  = getenv('TEST_LAUNCH_ALIAS') ?: 'bal_baccaratko';
        $tableId      = getenv('TEST_TABLE_ID') ?: '1234';
        $tableName    = getenv('TEST_TABLE_NAME') ?: 'Integration Test';
        $winPrimary   = getenv('TEST_WIN_PRIMARY') ?: '2';

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => $roundCode,
            "pay" => [
                "transactionCode" => uniqid('test_trx_'),
                "transactionDate" => $date,
                "amount" => $winPrimary,
                "type" => "WIN",
                "internalFundChanges" => []
            ],
            "gameRoundClose" => [
                "date" => $date,
                "rngGeneratorId" => "SecureRandom",
                "rngSoftwareId" => "Casino CaGS 12.3.4.5"
            ],
            "gameCodeName" => $liveGameCode,
            "gameHistoryUrl" => "getgamehistory.php?ThisIsJustAutomatedTestDataOK",
            "liveTableDetails" => [
                "launchAlias" => $launchAlias,
                "tableId" => $tableId,
                "tableName" => $tableName
            ]
        ];

        $endpoint = Endpoint::playtech('gameroundresult');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send game round result request to endpoint')]
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

        $checks = [];

        $this->validateApiResponse([
            "response"      => $response,
            "data"          => $data,
            "payload"       => $payload,
            "checks"        => $checks,
            "fullUrl"       => $fullUrl,
            "body"          => $body,
            "endpointType"  => 'result',
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('live')]
    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.1 Regular gameround scenarios')]
    #[DisplayName('What Is My Purpose?')]
    #[Description('Echo test for live casino')]
    #[Test]
    public function live_what_is_my_purpose(): void
    {
        $requestId = uniqid('test_');
        $purpose = 'To act as skipRequest() until Allure is fixed.';

        $queryParams = [
            'requestId' => $requestId,
            'purpose' => $purpose,
        ];

        $fullUrl = 'https://postman-echo.com/get?' . http_build_query($queryParams);

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send GET request to postman-echo')]
            function (StepContextInterface $step) use ($fullUrl) {
                $step->parameter('method', 'GET');
                $step->parameter('url', $fullUrl);

                $response = $this->client->get($fullUrl);

                $body = (string)$response->getBody();
                $data = json_decode($body, true);
                return [$response, $body, $data];
            }
        );

        $checks = [];

        Allure::attachment(
            'Request URL',
            $fullUrl,
            'text/plain'
        );

        Allure::attachment(
            'Response Body',
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            'application/json'
        );

        $this->stepAssertStatus($response, 200, $checks);

        Allure::runStep(
            #[DisplayName('Assert args.requestId matches')]
            function () use ($data, $requestId, &$checks) {
                $this->assertArrayHasKey('args', $data);
                $this->assertArrayHasKey('requestId', $data['args']);
                $this->assertEquals($requestId, $data['args']['requestId']);
                $checks[] = '✓ args.requestId matches sent requestId';
            }
        );

        Allure::runStep(
            #[DisplayName('Assert args.purpose matches')]
            function () use ($data, $purpose, &$checks) {
                $this->assertArrayHasKey('purpose', $data['args']);
                $this->assertEquals($purpose, $data['args']['purpose']);
                $checks[] = '✓ args.purpose matches sent purpose';
            }
        );

        Allure::runStep(
            #[DisplayName('Assert url field present')]
            function () use ($data, &$checks) {
                $this->assertArrayHasKey('url', $data);
                $this->assertStringContainsString('postman-echo.com', $data['url']);
                $checks[] = '✓ url field present and contains postman-echo.com';
            }
        );

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('live')]
    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.10 Forward compatibility check')]
    #[DisplayName('Bet | Forward compatibility check')]
    #[Description('Testing live casino bet for forward compatibility')]
    #[Test]
    public function live_bet_forward_compatibility(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[Group('live')]
    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.10 Forward compatibility check')]
    #[DisplayName('Gameroundresult (no win) | Forward compatibility check')]
    #[Description('Testing live casino forward compatibility result')]
    #[Test]
    public function live_result_forward_compatibility(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[Group('live')]
    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.10 Forward compatibility check')]
    #[DisplayName('What Is My Purpose Again?')]
    #[Description('Echo test for forward compatibility')]
    #[Test]
    public function live_what_is_my_purpose_again(): void
    {
        // TODO: Implement - GET https://postman-echo.com/get
        $this->markTestIncomplete('Awaiting request/response implementation');
    }
}
