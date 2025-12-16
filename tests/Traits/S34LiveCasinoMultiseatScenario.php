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

trait S34LiveCasinoMultiseatScenario
{
    #[Group('live')]
    #[Group('live-regular')]
    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.4 Multiseat bets with aggregated win')]
    #[DisplayName('Bet 1 | Live Casino | Multiseat bets with wins')]
    #[Description('Testing live casino multiseat bet 1')]
    #[Test]
    public function live_multiseat_bet_1(): void
    {
        $roundCode = $this->getRoundCode('live_multiseat_scenario');

        $username     = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token        = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $betPrimary   = getenv('TEST_BET_PRIMARY');

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
                "launchAlias" => "bal_baccaratko",
                "tableId" => "1234",
                "tableName" => "Integration Test"
            ]
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Bet 1 request to endpoint')]
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

        $this->attachHttpRequestAndResponse($fullUrl, $payload, $response, $body);

        $this->stepAssertStatus($response, 200, $checks);

        $this->stepAssertNoErrorField($data);

        $this->stepAssertRequestIdMatches($payload, $data);

        $this->stepAssertTransactionResponseSchema($data, $checks);

        $this->stepAssertTimestampFormat($data, $checks);

        $this->stepAssertTimestampGMT($data, $checks);

        $this->stepAssertBalanceError($data, $checks);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('live')]
    #[Group('live-regular')]
    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.4 Multiseat bets with aggregated win')]
    #[DisplayName('Bet 2 | Live Casino | Multiseat bets with wins')]
    #[Description('Testing live casino multiseat bet 2')]
    #[Test]
    public function live_multiseat_bet_2(): void
    {
        $roundCode = $this->getRoundCode('live_multiseat_scenario');

        $username     = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token        = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $betPrimary   = getenv('TEST_BET_PRIMARY');

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
                "launchAlias" => "bal_baccaratko",
                "tableId" => "1234",
                "tableName" => "Integration Test"
            ]
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Bet 2 request to endpoint')]
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

        $this->attachHttpRequestAndResponse($fullUrl, $payload, $response, $body);

        $this->stepAssertStatus($response, 200, $checks);

        $this->stepAssertNoErrorField($data);

        $this->stepAssertRequestIdMatches($payload, $data);

        $this->stepAssertTransactionResponseSchema($data, $checks);

        $this->stepAssertTimestampFormat($data, $checks);

        $this->stepAssertTimestampGMT($data, $checks);

        $this->stepAssertBalanceError($data, $checks);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('live')]
    #[Group('live-regular')]
    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.4 Multiseat bets with aggregated win')]
    #[DisplayName('Bet 3 | Live Casino | Multiseat bets with wins')]
    #[Description('Testing live casino multiseat bet 3')]
    #[Test]
    public function live_multiseat_bet_3(): void
    {
        $roundCode = $this->getRoundCode('live_multiseat_scenario');

        $username     = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token        = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $betPrimary   = getenv('TEST_BET_PRIMARY');

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
                "launchAlias" => "bal_baccaratko",
                "tableId" => "1234",
                "tableName" => "Integration Test"
            ]
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Bet 3 request to endpoint')]
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

        $this->attachHttpRequestAndResponse($fullUrl, $payload, $response, $body);

        $this->stepAssertStatus($response, 200, $checks);

        $this->stepAssertNoErrorField($data);

        $this->stepAssertRequestIdMatches($payload, $data);

        $this->stepAssertTransactionResponseSchema($data, $checks);

        $this->stepAssertTimestampFormat($data, $checks);

        $this->stepAssertTimestampGMT($data, $checks);

        $this->stepAssertBalanceError($data, $checks);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('live')]
    #[Group('live-regular')]
    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.4 Multiseat bets with aggregated win')]
    #[DisplayName('Gameroundresult (aggregated win) | Live Casino | Multiseat bets with aggregated win')]
    #[Description('Testing live casino multiseat aggregated win result')]
    #[Test]
    public function live_multiseat_result_aggregated_win(): void
    {
        $roundCode = $this->getRoundCode('live_multiseat_scenario');

        $username     = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token        = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $winAmount    = getenv('TEST_WIN_AMOUNT') ?: '2';

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => $roundCode,
            "pay" => [
                "transactionCode" => uniqid('test_trx_'),
                "transactionDate" => $date,
                "amount" => $winAmount,
                "type" => "WIN",
                "internalFundChanges" => []
            ],
            "gameRoundClose" => [
                "date" => $date,
                "rngGeneratorId" => "SecureRandom",
                "rngSoftwareId" => "Casino CaGS 12.3.4.5"
            ],
            "gameCodeName" => $liveGameCode,
            "liveTableDetails" => [
                "launchAlias" => "bal_baccaratko",
                "tableId" => "1234",
                "tableName" => "Integration Test"
            ],
            "gameHistoryUrl" => "getgamehistory.php?ThisIsJustAutomatedTestDataOK"
        ];

        $endpoint = Endpoint::playtech('gameroundresult');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Gameroundresult (aggregated win) request to endpoint')]
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

        $this->attachHttpRequestAndResponse($fullUrl, $payload, $response, $body);

        $this->stepAssertStatus($response, 200, $checks);

        $this->stepAssertNoErrorField($data);

        $this->stepAssertRequestIdMatches($payload, $data);

        $this->stepAssertTransactionResponseSchema($data, $checks);

        $this->stepAssertTimestampFormat($data, $checks);

        $this->stepAssertTimestampGMT($data, $checks);

        $this->stepAssertBalanceError($data, $checks);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }
}
