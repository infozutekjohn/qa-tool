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

trait S22CasinoScenario
{
    #[Group('casino')]
    #[Group('casino-bet')]
    #[ParentSuite('02. Gameslink Casino Tests (casino flows)')]
    #[Suite('2.2 Regular Gameround Scenario with Jackpot')]
    #[Displayname('Bet | Casino | Regular gameround scenario (no win with no jackpot)')]
    #[Description('Testing wallet regular bet response')]
    #[Test]
    public function bet_no_win_no_jackpot(): void
    {
        $roundCode = $this->getRoundCode('regular_gameround_scenario_with_jackpot_1');
        // $transactionCode = $this->generateTransactionCode();

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

        $this->stepAssertBalanceError($data, $checks);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('casino')]
    #[Group('casino-bet')]
    #[ParentSuite('02. Gameslink Casino Tests (casino flows)')]
    #[Suite('2.2 Regular Gameround Scenario with Jackpot')]
    #[Displayname('Gameroundresult | Casino | Regular gameround scenario (no win with no jackpot)')]
    #[Description('Testing wallet regular no win result response')]
    #[Test]
    public function result_no_win_no_jackpot(): void
    {
        $roundCode = $this->getRoundCode('regular_gameround_scenario_with_jackpot_1');

        $username       = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token          = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $casinoGameCode = getenv('TEST_CASINO_GAME_CODE');

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

        $this->stepAssertBalanceUnchanged($data, 'No win - balance unchanged', $checks);

        $this->stepAssertTimestampFormat($data, $checks);

        $this->stepAssertTimestampGMT($data, $checks);

        $this->stepAssertBalanceError($data, $checks);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('casino')]
    #[Group('casino-bet')]
    #[ParentSuite('02. Gameslink Casino Tests (casino flows)')]
    #[Suite('2.2 Regular Gameround Scenario with Jackpot')]
    #[Displayname('Bet | Casino | Regular gameround scenario (win with no jackpot)')]
    #[Description('Testing wallet regular bet response')]
    #[Test]
    public function bet_win_no_jackpot(): void
    {
        $roundCode = $this->getRoundCode('regular_gameround_scenario_with_jackpot_2');
        // $transactionCode = $this->generateTransactionCode();

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

        $this->stepAssertBalanceError($data, $checks);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('casino')]
    #[Group('casino-bet')]
    #[ParentSuite('02. Gameslink Casino Tests (casino flows)')]
    #[Suite('2.2 Regular Gameround Scenario with Jackpot')]
    #[Displayname('Gameroundresult | Casino | Regular gameround scenario (win with no jackpot)')]
    #[Description('Testing wallet regular no win result response')]
    #[Test]
    public function result_win_no_jackpot(): void
    {
        $roundCode = $this->getRoundCode('regular_gameround_scenario_with_jackpot_2');
        $jackpotId = $this->generateRandomJackpotId();

        $username       = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token          = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $win            = getenv('TEST_WIN_PRIMARY');
        $betPrimary     = getenv('TEST_BET_PRIMARY');
        $casinoGameCode = getenv('TEST_CASINO_GAME_CODE');

        $date = $this->generateDate();

        $contriAmount = $betPrimary * 0.01;
        $subJP1 = $betPrimary * (1 / 3) / 100;
        $subJP2 = $betPrimary * (1 / 3) / 100;
        $subJP3 = $betPrimary * (2 / 9) / 100;
        $subJP4 = $betPrimary * (1 / 9) / 100;

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
            "jackpot" => [
                "contributionAmount" => $contriAmount,
                "winAmount" => "0",
                "jackpotId" => "test_110_120_130_140_333",
                "jackpotContributions" => [
                    [
                        "amount" => $contriAmount,
                        "jackpotId" => "test_110_120_130_140_333",
                        "subJackpotInfo" => [
                            [
                                "amount" => $subJP1,
                                "jackpotId" => "test_140_333",
                            ],
                            [
                                "amount" => $subJP2,
                                "jackpotId" => "test_130_333",
                            ],
                            [
                                "amount" => $subJP3,
                                "jackpotId" => "test_120_333",
                            ],
                            [
                                "amount" => $subJP4,
                                "jackpotId" => "test_110_333",
                            ],
                        ],
                    ],
                ],

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

        $this->stepAssertBalanceError($data, $checks);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('casino')]
    #[Group('casino-bet')]
    #[ParentSuite('02. Gameslink Casino Tests (casino flows)')]
    #[Suite('2.2 Regular Gameround Scenario with Jackpot')]
    #[Displayname('Bet | Casino | Regular gameround scenario (win with jackpot)')]
    #[Description('Testing wallet regular bet response')]
    #[Test]
    public function bet_win_jackpot(): void
    {
        $roundCode = $this->getRoundCode('regular_gameround_scenario_with_jackpot_3');
        // $transactionCode = $this->generateTransactionCode();

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

        $this->stepAssertBalanceError($data, $checks);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('casino')]
    #[Group('casino-bet')]
    #[ParentSuite('02. Gameslink Casino Tests (casino flows)')]
    #[Suite('2.2 Regular Gameround Scenario with Jackpot')]
    #[Displayname('Gameroundresult | Casino | Regular gameround scenario (win with jackpot)')]
    #[Description('Testing wallet regular no win result response')]
    #[Test]
    public function result_win_jackpot(): void
    {
        $roundCode = $this->getRoundCode('regular_gameround_scenario_with_jackpot_3');
        $jackpotId = $this->generateRandomJackpotId();

        $username       = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token          = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $win            = getenv('TEST_WIN_PRIMARY');
        $betPrimary     = getenv('TEST_BET_PRIMARY');
        $casinoGameCode = getenv('TEST_CASINO_GAME_CODE');

        $date = $this->generateDate();

        $contriAmount = $betPrimary * 0.01;
        $subJP1 = $betPrimary * (1 / 3) / 100;
        $subJP2 = $betPrimary * (1 / 3) / 100;
        $subJP3 = $betPrimary * (2 / 9) / 100;
        $subJP4 = $betPrimary * (1 / 9) / 100;

        $jackpotWin = $betPrimary * 0.2;

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
            "jackpot" => [
                "contributionAmount" => $contriAmount,
                "winAmount" => $jackpotWin,
                "jackpotId" => "test_110_120_130_140_333",
                "jackpotContributions" => [
                    [
                        "amount" => $contriAmount,
                        "jackpotId" => "test_110_120_130_140_333",
                        "subJackpotInfo" => [
                            [
                                "amount" => $subJP1,
                                "jackpotId" => "test_140_333",
                            ],
                            [
                                "amount" => $subJP2,
                                "jackpotId" => "test_130_333",
                            ],
                            [
                                "amount" => $subJP3,
                                "jackpotId" => "test_120_333",
                            ],
                            [
                                "amount" => $subJP4,
                                "jackpotId" => "test_110_333",
                            ],
                        ],
                    ],
                ],
                "jackpotWinnings" => [
                    [
                        "amount" => $jackpotWin,
                        "jackpotId" => "test_110_120_130_140_333",
                        "subJackpotInfo" => [
                            [
                                "amount" => $jackpotWin,
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

        $this->attachHttpRequestAndResponse($fullUrl, $payload, $response, $body);

        $this->stepAssertStatus($response, 200, $checks);

        $this->stepAssertNoErrorField($data);

        $this->stepAssertRequestIdMatches($payload, $data);

        $this->stepAssertTransactionResponseSchema($data, $checks);

        $this->stepAssertBalanceWinAdded($data, $win, 'Win amount', $checks);

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