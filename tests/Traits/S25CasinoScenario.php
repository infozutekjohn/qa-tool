<?php

namespace Tests\Traits;

use DateTime;
use GuzzleHttp\Psr7\Message;
use PHPUnit\Framework\Attributes\Group;
use Qameta\Allure\Allure;
use Qameta\Allure\StepContextInterface;
use Qameta\Allure\Attribute\ParentSuite;
use Qameta\Allure\Attribute\Suite;
use Qameta\Allure\Attribute\SubSuite;
use Qameta\Allure\Attribute\DisplayName;
use Qameta\Allure\Attribute\Description;
use PHPUnit\Framework\Attributes\Test;
use Tests\Config\Endpoint;

trait S25CasinoScenario
{
    #[Group('casino')]
    #[Group('casino-bet')]
    #[ParentSuite('02. Gameslink Casino Tests (casino flows)')]
    #[Suite('2.5 Alternating Bet and Result Scenario')]
    #[Displayname('Bet 1 | Casino | Alternating bet and result scenario')]
    #[Description('Testing wallet regular bet response')]
    #[Test]
    public function bet_alternate_1(): void
    {
        $roundCode = $this->getRoundCode('alternating_scenario_1');

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

    #[Group('casino')]
    #[Group('casino-bet')]
    #[ParentSuite('02. Gameslink Casino Tests (casino flows)')]
    #[Suite('2.5 Alternating Bet and Result Scenario')]
    #[Displayname('Gameroundresult 1 | Casino | Alternating bet and result scenario')]
    #[Description('Testing wallet regular no win result response')]
    #[Test]
    public function result_alternate_1(): void
    {
        $roundCode = $this->getRoundCode('alternating_scenario_1');

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

        $this->validateApiResponse([
            "response"      => $response,
            "data"          => $data,
            "payload"       => $payload,
            "checks"        => $checks,
            "fullUrl"       => $fullUrl,
            "body"          => $body,
            "endpointType"  => 'result',
            "balanceAction" => 'added',
            "win"           => $win,
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('casino')]
    #[Group('casino-bet')]
    #[ParentSuite('02. Gameslink Casino Tests (casino flows)')]
    #[Suite('2.5 Alternating Bet and Result Scenario')]
    #[Displayname('Bet 2 | Casino | Alternating bet and result scenario')]
    #[Description('Testing wallet regular bet response')]
    #[Test]
    public function bet_alternate_2(): void
    {
        $roundCode = $this->getRoundCode('alternating_scenario_1');

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

    #[Group('casino')]
    #[Group('casino-bet')]
    #[ParentSuite('02. Gameslink Casino Tests (casino flows)')]
    #[Suite('2.5 Alternating Bet and Result Scenario')]
    #[Displayname('Gameroundresult 2 | Casino | Alternating bet and result scenario')]
    #[Description('Testing wallet regular no win result response')]
    #[Test]
    public function result_alternate_2(): void
    {
        $roundCode = $this->getRoundCode('alternating_scenario_1');

        $username       = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token          = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $casinoGameCode = getenv('TEST_CASINO_GAME_CODE');
        $win            = getenv('TEST_WIN_PRIMARY');

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

        $this->validateApiResponse([
            "response"      => $response,
            "data"          => $data,
            "payload"       => $payload,
            "checks"        => $checks,
            "fullUrl"       => $fullUrl,
            "body"          => $body,
            "endpointType"  => 'result',
            "balanceAction" => 'added',
            "win"           => $win,
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }
}
