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

trait S32LiveCasinoJackpotScenario
{
    #[Group('live')]
    #[Group('live-regular')]
    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.2 Regular gameround scenarios (with jackpot)')]
    #[DisplayName('Bet | Casino | Regular gameround scenario (no win with no jackpot)')]
    #[Description('Testing live casino bet with no win and no jackpot')]
    #[Test]
    public function live_bet_no_win_no_jackpot(): void
    {
        $roundCode = $this->getRoundCode('live_jackpot_no_win');

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
    #[Suite('2.2 Regular gameround scenarios (with jackpot)')]
    #[DisplayName('Bet | Casino | Regular gameround scenario (win with jackpot)')]
    #[Description('Testing live casino bet with win and jackpot')]
    #[Test]
    public function live_bet_win_with_jackpot(): void
    {
        $roundCode = $this->getRoundCode('live_jackpot_win');

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
    #[Suite('2.2 Regular gameround scenarios (with jackpot)')]
    #[DisplayName('Bet | Casino | Regular gameround scenario (win with no jackpot)')]
    #[Description('Testing live casino bet with win and no jackpot')]
    #[Test]
    public function live_bet_win_no_jackpot(): void
    {
        $roundCode = $this->getRoundCode('live_jackpot_win_no_jackpot');

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
    #[Suite('2.2 Regular gameround scenarios (with jackpot)')]
    #[DisplayName('Gameroundresult | Casino | Regular gameround scenario (no win with no jackpot)')]
    #[Description('Testing live casino result no win no jackpot')]
    #[Test]
    public function live_result_no_win_no_jackpot(): void
    {
        $roundCode = $this->getRoundCode('live_jackpot_no_win');

        $username     = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token        = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => $roundCode,
            "jackpot" => [
                "contributionAmount" => "0.0100000000000000",
                "winAmount" => "0",
                "jackpotId" => "test_110_120_130_140_333",
                "jackpotContributions" => [
                    [
                        "amount" => "0.0100000000000000",
                        "jackpotId" => "test_110_120_130_140_333",
                        "subJackpotInfo" => [
                            [
                                "amount" => "0.0033333333333333",
                                "jackpotId" => "test_140_333"
                            ],
                            [
                                "amount" => "0.0033333333333333",
                                "jackpotId" => "test_130_333"
                            ],
                            [
                                "amount" => "0.0022222222222222",
                                "jackpotId" => "test_120_333"
                            ],
                            [
                                "amount" => "0.0011111111111111",
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
            "gameCodeName" => $liveGameCode,
            "gameHistoryUrl" => "getgamehistory.php?ThisIsJustAutomatedTestDataOK",
            "liveTableDetails" => [
                "launchAlias" => "bal_baccaratko",
                "tableId" => "1234",
                "tableName" => "Integration Test"
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
    #[Suite('2.2 Regular gameround scenarios (with jackpot)')]
    #[DisplayName('Gameroundresult | Casino | Regular gameround scenario (win with jackpot)')]
    #[Description('Testing live casino result with win and jackpot')]
    #[Test]
    public function live_result_win_with_jackpot(): void
    {
        $roundCode = $this->getRoundCode('live_jackpot_win');

        $username     = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token        = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $winPrimary   = getenv('TEST_WIN_PRIMARY');

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
            "jackpot" => [
                "contributionAmount" => "0.0100000000000000",
                "winAmount" => "1",
                "jackpotId" => "test_110_120_130_140_333",
                "jackpotContributions" => [
                    [
                        "amount" => "0.0100000000000000",
                        "jackpotId" => "test_110_120_130_140_333",
                        "subJackpotInfo" => [
                            [
                                "amount" => "0.0033333333333333",
                                "jackpotId" => "test_140_333"
                            ],
                            [
                                "amount" => "0.0033333333333333",
                                "jackpotId" => "test_130_333"
                            ],
                            [
                                "amount" => "0.0022222222222222",
                                "jackpotId" => "test_120_333"
                            ],
                            [
                                "amount" => "0.0011111111111111",
                                "jackpotId" => "test_110_333"
                            ]
                        ]
                    ]
                ],
                "jackpotWinnings" => [
                    [
                        "amount" => "1",
                        "jackpotId" => "test_110_120_130_140_333",
                        "subJackpotInfo" => [
                            [
                                "amount" => "1",
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
            "gameCodeName" => $liveGameCode,
            "gameHistoryUrl" => "getgamehistory.php?ThisIsJustAutomatedTestDataOK",
            "liveTableDetails" => [
                "launchAlias" => "bal_baccaratko",
                "tableId" => "1234",
                "tableName" => "Integration Test"
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
    #[Suite('2.2 Regular gameround scenarios (with jackpot)')]
    #[DisplayName('Gameroundresult | Casino | Regular gameround scenario (win with no jackpot)')]
    #[Description('Testing live casino result with win and no jackpot')]
    #[Test]
    public function live_result_win_no_jackpot(): void
    {
        $roundCode = $this->getRoundCode('live_jackpot_win_no_jackpot');

        $username     = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token        = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $winPrimary   = getenv('TEST_WIN_PRIMARY');

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
            "jackpot" => [
                "contributionAmount" => "0.0100000000000000",
                "winAmount" => "0",
                "jackpotId" => "test_110_120_130_140_333",
                "jackpotContributions" => [
                    [
                        "amount" => "0.0100000000000000",
                        "jackpotId" => "test_110_120_130_140_333",
                        "subJackpotInfo" => [
                            [
                                "amount" => "0.0033333333333333",
                                "jackpotId" => "test_140_333"
                            ],
                            [
                                "amount" => "0.0033333333333333",
                                "jackpotId" => "test_130_333"
                            ],
                            [
                                "amount" => "0.0022222222222222",
                                "jackpotId" => "test_120_333"
                            ],
                            [
                                "amount" => "0.0011111111111111",
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
            "gameCodeName" => $liveGameCode,
            "gameHistoryUrl" => "getgamehistory.php?ThisIsJustAutomatedTestDataOK",
            "liveTableDetails" => [
                "launchAlias" => "bal_baccaratko",
                "tableId" => "1234",
                "tableName" => "Integration Test"
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
