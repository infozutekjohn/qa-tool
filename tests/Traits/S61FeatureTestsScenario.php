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

trait S61FeatureTestsScenario
{
    // 2. Crosslaunch check - 2.1 Regular gameround scenario GAME 1
    #[Group('gameslink')]
    #[ParentSuite('06. Gameslink Features Tests')]
    #[Suite('6.1 Crosslaunch check')]
    #[DisplayName('Bet | Crosslaunch check GAME 1')]
    #[Description('Testing crosslaunch bet for GAME 1')]
    #[Test]
    public function crosslaunch_bet_game_1(): void
    {
        $roundCode = $this->getRoundCode('crosslaunch_game_1');

        $username          = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token             = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $crosslaunchGame1  = getenv('TEST_CROSSLAUNCH_GAME_1') ?: 'ubal';
        $crosslaunchAlias1 = getenv('TEST_CROSSLAUNCH_ALIAS_1') ?: 'bal_emperorbaccarat';
        $betPrimary        = getenv('TEST_BET_PRIMARY') ?: '1';
        $tableId           = getenv('TEST_TABLE_ID') ?: '1234';
        $tableName         = getenv('TEST_TABLE_NAME') ?: 'Integration Test';

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => $roundCode,
            "transactionCode" => uniqid('test_trx_') . bin2hex(random_bytes(4)),
            "transactionDate" => $date,
            "amount" => $betPrimary,
            "internalFundChanges" => [],
            "gameCodeName" => $crosslaunchGame1,
            "liveTableDetails" => [
                "launchAlias" => $crosslaunchAlias1,
                "tableId" => $tableId,
                "tableName" => $tableName
            ]
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Crosslaunch Bet GAME 1 request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('testType', 'Crosslaunch GAME 1');

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
            "checks"        => &$checks,
            "fullUrl"       => $fullUrl,
            "body"          => $body,
            "endpointType"  => 'bet',
            "errorScenario" => false,
            "balanceAction" => 'deducted',
            "bet"           => $betPrimary
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('gameslink')]
    #[ParentSuite('06. Gameslink Features Tests')]
    #[Suite('6.1 Crosslaunch check')]
    #[DisplayName('Gameroundresult | Crosslaunch check GAME 1')]
    #[Description('Testing crosslaunch result for GAME 1')]
    #[Test]
    public function crosslaunch_result_game_1(): void
    {
        $roundCode = $this->getRoundCode('crosslaunch_game_1');

        $username          = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token             = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $crosslaunchGame1  = getenv('TEST_CROSSLAUNCH_GAME_1') ?: 'ubal';
        $crosslaunchAlias1 = getenv('TEST_CROSSLAUNCH_ALIAS_1') ?: 'bal_emperorbaccarat';
        $tableId           = getenv('TEST_TABLE_ID') ?: '1234';
        $tableName         = getenv('TEST_TABLE_NAME') ?: 'Integration Test';

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
            "gameCodeName" => $crosslaunchGame1,
            "gameHistoryUrl" => "getgamehistory.php?ThisIsJustAutomatedTestDataOK",
            "liveTableDetails" => [
                "launchAlias" => $crosslaunchAlias1,
                "tableId" => $tableId,
                "tableName" => $tableName
            ]
        ];

        $endpoint = Endpoint::playtech('gameroundresult');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Crosslaunch Result GAME 1 request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('testType', 'Crosslaunch GAME 1 Result');

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
            "checks"        => &$checks,
            "fullUrl"       => $fullUrl,
            "body"          => $body,
            "endpointType"  => 'gameroundresult',
            "errorScenario" => false,
            "balanceAction" => 'unchanged',
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    // 2. Crosslaunch check - 2.2 Regular gameround scenario GAME 2 (RNG game)
    #[Group('gameslink')]
    #[ParentSuite('06. Gameslink Features Tests')]
    #[Suite('6.1 Crosslaunch check')]
    #[DisplayName('Bet | Crosslaunch check GAME 2')]
    #[Description('Testing crosslaunch bet for GAME 2 (RNG)')]
    #[Test]
    public function crosslaunch_bet_game_2(): void
    {
        $roundCode = $this->getRoundCode('crosslaunch_game_2');

        $username      = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token         = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $crosslaunchGame2 = getenv('TEST_CROSSLAUNCH_GAME_2') ?: 'gpas_anightout_pop';
        $betPrimary    = getenv('TEST_BET_PRIMARY') ?: '1';

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => $roundCode,
            "transactionCode" => uniqid('test_trx_') . bin2hex(random_bytes(4)),
            "transactionDate" => $date,
            "amount" => $betPrimary,
            "internalFundChanges" => [],
            "gameCodeName" => $crosslaunchGame2
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Crosslaunch Bet GAME 2 request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('testType', 'Crosslaunch GAME 2');

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
            "checks"        => &$checks,
            "fullUrl"       => $fullUrl,
            "body"          => $body,
            "endpointType"  => 'bet',
            "errorScenario" => false,
            "balanceAction" => 'deducted',
            "bet"           => $betPrimary
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('gameslink')]
    #[ParentSuite('06. Gameslink Features Tests')]
    #[Suite('6.1 Crosslaunch check')]
    #[DisplayName('Gameroundresult | Crosslaunch check GAME 2')]
    #[Description('Testing crosslaunch result for GAME 2 (RNG)')]
    #[Test]
    public function crosslaunch_result_game_2(): void
    {
        $roundCode = $this->getRoundCode('crosslaunch_game_2');

        $username      = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token         = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $crosslaunchGame2 = getenv('TEST_CROSSLAUNCH_GAME_2') ?: 'gpas_anightout_pop';

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
            "gameCodeName" => $crosslaunchGame2,
            "gameHistoryUrl" => "getgamehistory.php?ThisIsJustAutomatedTestDataOK"
        ];

        $endpoint = Endpoint::playtech('gameroundresult');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Crosslaunch Result GAME 2 request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('testType', 'Crosslaunch GAME 2 Result');

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
            "checks"        => &$checks,
            "fullUrl"       => $fullUrl,
            "body"          => $body,
            "endpointType"  => 'gameroundresult',
            "errorScenario" => false,
            "balanceAction" => 'unchanged',
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }
}
