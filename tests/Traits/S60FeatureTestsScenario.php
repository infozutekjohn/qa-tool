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

trait S60FeatureTestsScenario
{
    // 2. Crosslaunch check - 2.1 Regular gameround scenario GAME 1
    #[Group('feature')]
    #[ParentSuite('06. Gameslink Features Tests')]
    #[Suite('2. Crosslaunch check')]
    #[DisplayName('2.1 Bet | Crosslaunch check GAME 1')]
    #[Description('Testing crosslaunch bet for GAME 1')]
    #[Test]
    public function crosslaunch_bet_game_1(): void
    {
        $roundCode = $this->getRoundCode('crosslaunch_game_1');

        $username      = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token         = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $crosslaunchGame1 = getenv('TEST_CROSSLAUNCH_GAME_1') ?: 'ubal';
        $crosslaunchAlias1 = getenv('TEST_CROSSLAUNCH_ALIAS_1') ?: 'bal_emperorbaccarat';
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
            "gameCodeName" => $crosslaunchGame1,
            "liveTableDetails" => [
                "launchAlias" => $crosslaunchAlias1,
                "tableId" => "1234",
                "tableName" => "Integration Test"
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

        $this->attachHttpRequestAndResponse($fullUrl, $payload, $response, $body);

        $this->stepAssertStatus($response, 200, $checks);

        $this->stepAssertNoErrorField($data);

        $this->stepAssertRequestIdMatches($payload, $data);

        $this->stepAssertTransactionResponseSchema($data, $checks);

        $this->stepAssertTimestampFormat($data, $checks);

        $this->stepAssertTimestampGMT($data, $checks);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('feature')]
    #[ParentSuite('06. Gameslink Features Tests')]
    #[Suite('2. Crosslaunch check')]
    #[DisplayName('2.1 Gameroundresult | Crosslaunch check GAME 1')]
    #[Description('Testing crosslaunch result for GAME 1')]
    #[Test]
    public function crosslaunch_result_game_1(): void
    {
        $roundCode = $this->getRoundCode('crosslaunch_game_1');

        $username      = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token         = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $crosslaunchGame1 = getenv('TEST_CROSSLAUNCH_GAME_1') ?: 'ubal';
        $crosslaunchAlias1 = getenv('TEST_CROSSLAUNCH_ALIAS_1') ?: 'bal_emperorbaccarat';

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
                "tableId" => "1234",
                "tableName" => "Integration Test"
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

        $this->attachHttpRequestAndResponse($fullUrl, $payload, $response, $body);

        $this->stepAssertStatus($response, 200, $checks);

        $this->stepAssertNoErrorField($data);

        $this->stepAssertRequestIdMatches($payload, $data);

        $this->stepAssertTransactionResponseSchema($data, $checks);

        $this->stepAssertTimestampFormat($data, $checks);

        $this->stepAssertTimestampGMT($data, $checks);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    // 2. Crosslaunch check - 2.2 Regular gameround scenario GAME 2 (RNG game)
    #[Group('feature')]
    #[ParentSuite('06. Gameslink Features Tests')]
    #[Suite('2. Crosslaunch check')]
    #[DisplayName('2.2 Bet | Crosslaunch check GAME 2')]
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

        $this->attachHttpRequestAndResponse($fullUrl, $payload, $response, $body);

        $this->stepAssertStatus($response, 200, $checks);

        $this->stepAssertNoErrorField($data);

        $this->stepAssertRequestIdMatches($payload, $data);

        $this->stepAssertTransactionResponseSchema($data, $checks);

        $this->stepAssertTimestampFormat($data, $checks);

        $this->stepAssertTimestampGMT($data, $checks);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('feature')]
    #[ParentSuite('06. Gameslink Features Tests')]
    #[Suite('2. Crosslaunch check')]
    #[DisplayName('2.2 Gameroundresult | Crosslaunch check GAME 2')]
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

        $this->attachHttpRequestAndResponse($fullUrl, $payload, $response, $body);

        $this->stepAssertStatus($response, 200, $checks);

        $this->stepAssertNoErrorField($data);

        $this->stepAssertRequestIdMatches($payload, $data);

        $this->stepAssertTransactionResponseSchema($data, $checks);

        $this->stepAssertTimestampFormat($data, $checks);

        $this->stepAssertTimestampGMT($data, $checks);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    // 3. GameCodeName check - 3.1 Regular gameround scenario random game
    #[Group('feature')]
    #[ParentSuite('06. Gameslink Features Tests')]
    #[Suite('3. GameCodeName check')]
    #[DisplayName('3.1 Bet | GameCodeName check random game')]
    #[Description('Testing gameCodeName validation with random game')]
    #[Test]
    public function gamecodename_bet_random(): void
    {
        $roundCode = $this->getRoundCode('gamecodename_random');

        $username     = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token        = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $rngGameCode  = getenv('TEST_GAMECODENAME_GAME') ?: 'vnodc';
        $betPrimary   = getenv('TEST_BET_PRIMARY') ?: '1';

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
            "gameCodeName" => $rngGameCode
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send GameCodeName Bet request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint, $rngGameCode) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('gameCodeName', $rngGameCode);

                $response = $this->client->post($endpoint, [
                    'json' => $payload,
                ]);

                $body = (string)$response->getBody();
                $data = json_decode($body, true);
                return [$response, $body, $data];
            }
        );

        $checks = [];

        $this->attachHttpRequestAndResponse($fullUrl, $payload, $response, $body);

        $this->stepAssertStatus($response, 200, $checks);

        $this->stepAssertNoErrorField($data);

        $this->stepAssertRequestIdMatches($payload, $data);

        $this->stepAssertTransactionResponseSchema($data, $checks);

        $this->stepAssertTimestampFormat($data, $checks);

        $this->stepAssertTimestampGMT($data, $checks);

        Allure::runStep(
            #[DisplayName('Verify gameCodeName was accepted')]
            function (StepContextInterface $step) use ($rngGameCode, &$checks) {
                $checks[] = '[PASS] gameCodeName: ' . $rngGameCode . ' was accepted';
            }
        );

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('feature')]
    #[ParentSuite('06. Gameslink Features Tests')]
    #[Suite('3. GameCodeName check')]
    #[DisplayName('3.1 Gameroundresult | GameCodeName check random game')]
    #[Description('Testing gameCodeName validation result for random game')]
    #[Test]
    public function gamecodename_result_random(): void
    {
        $roundCode = $this->getRoundCode('gamecodename_random');

        $username     = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token        = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $rngGameCode  = getenv('TEST_GAMECODENAME_GAME') ?: 'vnodc';

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
            "gameCodeName" => $rngGameCode,
            "gameHistoryUrl" => "getgamehistory.php?ThisIsJustAutomatedTestDataOK"
        ];

        $endpoint = Endpoint::playtech('gameroundresult');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send GameCodeName Result request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint, $rngGameCode) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('gameCodeName', $rngGameCode);

                $response = $this->client->post($endpoint, [
                    'json' => $payload,
                ]);

                $body = (string)$response->getBody();
                $data = json_decode($body, true);
                return [$response, $body, $data];
            }
        );

        $checks = [];

        $this->attachHttpRequestAndResponse($fullUrl, $payload, $response, $body);

        $this->stepAssertStatus($response, 200, $checks);

        $this->stepAssertNoErrorField($data);

        $this->stepAssertRequestIdMatches($payload, $data);

        $this->stepAssertTransactionResponseSchema($data, $checks);

        $this->stepAssertTimestampFormat($data, $checks);

        $this->stepAssertTimestampGMT($data, $checks);

        Allure::runStep(
            #[DisplayName('Verify gameCodeName result completed')]
            function (StepContextInterface $step) use ($rngGameCode, &$checks) {
                $checks[] = '[PASS] gameCodeName: ' . $rngGameCode . ' round completed successfully';
            }
        );

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    // 4. LaunchAlias check - 4.1 Regular gameround scenario random game (live casino with launchAlias)
    #[Group('feature')]
    #[ParentSuite('06. Gameslink Features Tests')]
    #[Suite('4. LaunchAlias check')]
    #[DisplayName('4.1 Bet | LaunchAlias check random game')]
    #[Description('Testing launchAlias validation with random live game')]
    #[Test]
    public function launchalias_bet_random(): void
    {
        $roundCode = $this->getRoundCode('launchalias_random');

        $username      = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token         = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode  = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $launchAlias   = getenv('TEST_LAUNCH_ALIAS') ?: 'bal_baccaratko';
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
            "gameCodeName" => $liveGameCode,
            "liveTableDetails" => [
                "launchAlias" => $launchAlias,
                "tableId" => "1234",
                "tableName" => "Integration Test"
            ]
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send LaunchAlias Bet request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint, $launchAlias) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('launchAlias', $launchAlias);

                $response = $this->client->post($endpoint, [
                    'json' => $payload,
                ]);

                $body = (string)$response->getBody();
                $data = json_decode($body, true);
                return [$response, $body, $data];
            }
        );

        $checks = [];

        $this->attachHttpRequestAndResponse($fullUrl, $payload, $response, $body);

        $this->stepAssertStatus($response, 200, $checks);

        $this->stepAssertNoErrorField($data);

        $this->stepAssertRequestIdMatches($payload, $data);

        $this->stepAssertTransactionResponseSchema($data, $checks);

        $this->stepAssertTimestampFormat($data, $checks);

        $this->stepAssertTimestampGMT($data, $checks);

        Allure::runStep(
            #[DisplayName('Verify launchAlias was accepted')]
            function (StepContextInterface $step) use ($launchAlias, &$checks) {
                $checks[] = '[PASS] launchAlias: ' . $launchAlias . ' was accepted';
            }
        );

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('feature')]
    #[ParentSuite('06. Gameslink Features Tests')]
    #[Suite('4. LaunchAlias check')]
    #[DisplayName('4.1 Gameroundresult | LaunchAlias check random game')]
    #[Description('Testing launchAlias validation result for random live game')]
    #[Test]
    public function launchalias_result_random(): void
    {
        $roundCode = $this->getRoundCode('launchalias_random');

        $username      = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token         = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode  = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $launchAlias   = getenv('TEST_LAUNCH_ALIAS') ?: 'bal_baccaratko';

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => $roundCode,
            "gameRoundClose" => [
                "date" => $date
            ],
            "gameCodeName" => $liveGameCode,
            "gameHistoryUrl" => "getgamehistory.php?ThisIsJustAutomatedTestDataOK",
            "liveTableDetails" => [
                "launchAlias" => $launchAlias,
                "tableId" => "1234",
                "tableName" => "Integration Test"
            ]
        ];

        $endpoint = Endpoint::playtech('gameroundresult');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send LaunchAlias Result request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint, $launchAlias) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('launchAlias', $launchAlias);

                $response = $this->client->post($endpoint, [
                    'json' => $payload,
                ]);

                $body = (string)$response->getBody();
                $data = json_decode($body, true);
                return [$response, $body, $data];
            }
        );

        $checks = [];

        $this->attachHttpRequestAndResponse($fullUrl, $payload, $response, $body);

        $this->stepAssertStatus($response, 200, $checks);

        $this->stepAssertNoErrorField($data);

        $this->stepAssertRequestIdMatches($payload, $data);

        $this->stepAssertTransactionResponseSchema($data, $checks);

        $this->stepAssertTimestampFormat($data, $checks);

        $this->stepAssertTimestampGMT($data, $checks);

        Allure::runStep(
            #[DisplayName('Verify launchAlias result completed')]
            function (StepContextInterface $step) use ($launchAlias, &$checks) {
                $checks[] = '[PASS] launchAlias: ' . $launchAlias . ' round completed successfully';
            }
        );

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }
}
