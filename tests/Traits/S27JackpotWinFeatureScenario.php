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

trait S27JackpotWinFeatureScenario
{
    #[Group('casino')]
    #[Group('casino-bet')]
    #[ParentSuite('02. Gameslink Casino Tests (casino flows)')]
    #[Suite('2.7 Jackpot win through feature')]
    #[Displayname('Bet | Casino | Jackpot win through feature scenario')]
    #[Description('Testing bet for jackpot win through feature scenario')]
    #[Test]
    public function bet_jackpot_win_through_feature(): void
    {
        $roundCode = $this->getRoundCode('jackpot_win_feature');

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
    #[Suite('2.7 Jackpot win through feature')]
    #[Displayname('Gameroundresult | Casino | Jackpot win through feature scenario (jackpot win)')]
    #[Description('Testing game round result with feature jackpot win')]
    #[Test]
    public function result_feature_jackpot_win(): void
    {
        $roundCode = $this->getRoundCode('jackpot_win_feature');

        $username       = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token          = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $casinoGameCode = getenv('TEST_CASINO_GAME_CODE');
        $betPrimary     = getenv('TEST_BET_PRIMARY');

        $date = $this->generateDate();

        $contriAmount = $betPrimary * 0.01;
        $subJP1 = $betPrimary * (1 / 3) / 100;
        $subJP2 = $betPrimary * (1 / 3) / 100;
        $subJP3 = $betPrimary * (2 / 9) / 100;
        $subJP4 = $betPrimary * (1 / 9) / 100;
        $jackpotWinAmount = $betPrimary;

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => $roundCode,
            "pay" => [
                "transactionCode" => uniqid('test_trx_'),
                "transactionDate" => $date,
                "amount" => (string)$jackpotWinAmount,
                "type" => "WIN",
                "internalFundChanges" => []
            ],
            "jackpot" => [
                "contributionAmount" => sprintf('%.16f', $contriAmount),
                "winAmount" => (string)$jackpotWinAmount,
                "jackpotId" => "test_110_120_130_140_333",
                "jackpotContributions" => [
                    [
                        "amount" => sprintf('%.16f', $contriAmount),
                        "jackpotId" => "test_110_120_130_140_333",
                        "subJackpotInfo" => [
                            [
                                "amount" => sprintf('%.16f', $subJP1),
                                "jackpotId" => "test_140_333"
                            ],
                            [
                                "amount" => sprintf('%.16f', $subJP2),
                                "jackpotId" => "test_130_333"
                            ],
                            [
                                "amount" => sprintf('%.16f', $subJP3),
                                "jackpotId" => "test_120_333"
                            ],
                            [
                                "amount" => sprintf('%.16f', $subJP4),
                                "jackpotId" => "test_110_333"
                            ]
                        ]
                    ]
                ],
                "jackpotWinnings" => [
                    [
                        "amount" => (string)$jackpotWinAmount,
                        "jackpotId" => "test_110_120_130_140_333",
                        "subJackpotInfo" => [
                            [
                                "amount" => (string)$jackpotWinAmount,
                                "jackpotId" => "test_110_333"
                            ]
                        ]
                    ]
                ]
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
            "win"           => (string)$jackpotWinAmount,
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
    #[Suite('2.7 Jackpot win through feature')]
    #[Displayname('Gameroundresult | Casino | Jackpot win through feature scenario (feature win)')]
    #[Description('Testing game round result with feature win')]
    #[Test]
    public function result_feature_win(): void
    {
        $roundCode = $this->getRoundCode('jackpot_win_feature');

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
            "gameCodeName" => $casinoGameCode
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
