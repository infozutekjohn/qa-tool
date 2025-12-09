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

trait S24CasinoScenario
{
    #[ParentSuite('02. Gameslink Casino Tests (casino flows)')]
    #[Suite('2.4 Two Bet Gameround Scenario with Win')]
    #[Displayname('Bet 1 | Casino | Two bet gameround scenario with win')]
    #[Description('Testing wallet regular bet response')]
    #[Test]
    public function two_bet_first(): void
    {
        $roundCode = $this->getRoundCode('two_bet_regular_gameround');

        $username       = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token          = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $casinoGameCode = getenv('TEST_CASINO_GAME_CODE');
        $betPrimary     = getenv('TEST_BET_PRIMARY');


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
            "gameCodeName" => $casinoGameCode
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

        $this->attachHttpRequestAndResponse($fullUrl, $payload, $response, $body);

        $this->stepAssertStatus($response, 200, $checks);

        $this->stepAssertNoErrorField($data);

        $this->stepAssertRequestIdMatches($payload, $data);

        $this->stepAssertTransactionResponseSchema($data, $checks);

        $this->stepAssertBalanceDeducted($data, $betPrimary, $checks);

        $this->stepAssertTimestampFormat($data, $checks);

        $this->stepAssertTimestampGMT($data, $checks);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[ParentSuite('02. Gameslink Casino Tests (casino flows)')]
    #[Suite('2.4 Two Bet Gameround Scenario with Win')]
    #[Displayname('Bet 2 (split in card game) | Casino | Two bet gameround scenario with win')]
    #[Description('Testing wallet regular bet response')]
    #[Test]
    public function two_bet_second(): void
    {
        $roundCode = $this->getRoundCode('two_bet_regular_gameround');

        $username       = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token          = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $casinoGameCode = getenv('TEST_CASINO_GAME_CODE');
        $betSecondary     = getenv('TEST_BET_SECONDARY');


        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => $roundCode,
            "transactionCode" => uniqid('test_trx_'),
            "transactionDate" => $date,
            "amount" => $betSecondary,
            "internalFundChanges" => [],
            "gameCodeName" => $casinoGameCode
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

        $this->attachHttpRequestAndResponse($fullUrl, $payload, $response, $body);

        $this->stepAssertStatus($response, 200, $checks);

        $this->stepAssertNoErrorField($data);

        $this->stepAssertRequestIdMatches($payload, $data);

        $this->stepAssertTransactionResponseSchema($data, $checks);

        $this->stepAssertBalanceDeducted($data, $betSecondary, $checks);

        $this->stepAssertTimestampFormat($data, $checks);

        $this->stepAssertTimestampGMT($data, $checks);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[ParentSuite('02. Gameslink Casino Tests (casino flows)')]
    #[Suite('2.4 Two Bet Gameround Scenario with Win')]
    #[Displayname('Gameroundresult (combined result) | Casino | Two bet gameround scenario with win')]
    #[Description('Testing wallet regular no win result response')]
    #[Test]
    public function result_two_bet_combined(): void
    {
        $roundCode = $this->getRoundCode('two_bet_regular_gameround');

        $username       = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token          = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $win            = getenv('TEST_WIN_PRIMARY');
        $casinoGameCode = getenv('TEST_CASINO_GAME_CODE');

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => $roundCode,
            "pay" => [
                "transactionCode" => uniqid('test_trx_'),
                "transactionDate" => $date,
                "amount" => $win,
                "type" => "WIN",
                "internalFundChanges" => []
            ],
            "gameRoundClose" => [
                "date" => $date,
                "rngGeneratorId" => "SecureRandom",
                "rngSoftwareId" => "Casino CaGS 12.3.4.5"
            ],
            "gameCodeName" => $casinoGameCode,
            "gameHistoryUrl" => "getgamehistory.php?ThisIsJustAutomatedTestDataOK"
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

        $this->attachHttpRequestAndResponse($fullUrl, $payload, $response, $body);

        $this->stepAssertStatus($response, 200, $checks);

        $this->stepAssertNoErrorField($data);

        $this->stepAssertRequestIdMatches($payload, $data);

        $this->stepAssertTransactionResponseSchema($data, $checks);

        $this->stepAssertBalanceWinAdded($data, $win, 'Win amount', $checks);

        $this->stepAssertTimestampFormat($data, $checks);

        $this->stepAssertTimestampGMT($data, $checks);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }
}
