<?php

namespace Tests\Traits;

use Qameta\Allure\Allure;
use Qameta\Allure\StepContextInterface;
use Qameta\Allure\Attribute\ParentSuite;
use Qameta\Allure\Attribute\Suite;
use Qameta\Allure\Attribute\DisplayName;
use Qameta\Allure\Attribute\Description;
use PHPUnit\Framework\Attributes\Test;
use Tests\Config\Endpoint;

trait S41FreespinHeldScenario
{
    // 2.1 Freespin bonus round scenario without wagering (held)
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('2.1 Freespin bonus round scenario without wagering (held)')]
    #[DisplayName('FS Bet | Freespin bonus round scenario without wagering (held)')]
    #[Description('Testing freespin bet without wagering held')]
    #[Test]
    public function fs_bet_held_1(): void
    {
        $roundCode = $this->getRoundCode('fs_held_scenario');
        $transactionCode = uniqid('test_trx_') . bin2hex(random_bytes(4));

        $username          = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token             = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode      = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $remoteBonusCode   = getenv('TEST_REMOTE_BONUS_CODE_PRIMARY') ?: '';
        $bonusInstanceCode = getenv('TEST_BONUS_INSTANCE_CODE_PRIMARY') ?: '';
        $bonusTemplateId   = getenv('TEST_BONUS_TEMPLATE_PRIMARY') ?: '12345';
        $betPrimary        = getenv('TEST_BET_PRIMARY') ?: '1';

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => $roundCode,
            "transactionCode" => $transactionCode,
            "transactionDate" => $date,
            "amount" => "0",
            "internalFundChanges" => [
                [
                    "type" => "BONUS",
                    "amount" => "-" . $betPrimary
                ]
            ],
            "gameCodeName" => $liveGameCode,
            "remoteBonusCode" => $remoteBonusCode,
            "bonusChanges" => [
                [
                    "remoteBonusCode" => $remoteBonusCode,
                    "bonusInstanceCode" => $bonusInstanceCode,
                    "freeSpinChange" => [
                        "count" => -1,
                        "value" => $betPrimary
                    ],
                    "bonusTemplateId" => $bonusTemplateId
                ]
            ],
            "liveTableDetails" => [
                "launchAlias" => "bal_baccaratko",
                "tableId" => "1234",
                "tableName" => "Integration Test"
            ]
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send FS Bet 1 request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('betType', 'Freespin (held)');

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

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('2.1 Freespin bonus round scenario without wagering (held)')]
    #[DisplayName('FS Bet | Freespin bonus round scenario without wagering (held)')]
    #[Description('Testing freespin bet without wagering held 2')]
    #[Test]
    public function fs_bet_held_2(): void
    {
        $roundCode = $this->getRoundCode('fs_held_scenario_2');
        $transactionCode = uniqid('test_trx_') . bin2hex(random_bytes(4));

        $username          = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token             = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode      = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $remoteBonusCode   = getenv('TEST_REMOTE_BONUS_CODE_PRIMARY') ?: '';
        $bonusInstanceCode = getenv('TEST_BONUS_INSTANCE_CODE_PRIMARY') ?: '';
        $bonusTemplateId   = getenv('TEST_BONUS_TEMPLATE_PRIMARY') ?: '12345';
        $betPrimary        = getenv('TEST_BET_PRIMARY') ?: '1';

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => $roundCode,
            "transactionCode" => $transactionCode,
            "transactionDate" => $date,
            "amount" => "0",
            "internalFundChanges" => [
                [
                    "type" => "BONUS",
                    "amount" => "-" . $betPrimary
                ]
            ],
            "gameCodeName" => $liveGameCode,
            "remoteBonusCode" => $remoteBonusCode,
            "bonusChanges" => [
                [
                    "remoteBonusCode" => $remoteBonusCode,
                    "bonusInstanceCode" => $bonusInstanceCode,
                    "freeSpinChange" => [
                        "count" => -1,
                        "value" => $betPrimary
                    ],
                    "bonusTemplateId" => $bonusTemplateId
                ]
            ],
            "liveTableDetails" => [
                "launchAlias" => "bal_baccaratko",
                "tableId" => "1234",
                "tableName" => "Integration Test"
            ]
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send FS Bet 2 request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('betType', 'Freespin (held)');

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

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('2.1 Freespin bonus round scenario without wagering (held)')]
    #[DisplayName('FS Bet | Freespin bonus round scenario without wagering (held)')]
    #[Description('Testing freespin bet without wagering held 3')]
    #[Test]
    public function fs_bet_held_3(): void
    {
        $roundCode = $this->getRoundCode('fs_held_scenario_3');
        $transactionCode = uniqid('test_trx_') . bin2hex(random_bytes(4));

        $username          = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token             = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode      = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $remoteBonusCode   = getenv('TEST_REMOTE_BONUS_CODE_PRIMARY') ?: '';
        $bonusInstanceCode = getenv('TEST_BONUS_INSTANCE_CODE_PRIMARY') ?: '';
        $bonusTemplateId   = getenv('TEST_BONUS_TEMPLATE_PRIMARY') ?: '12345';
        $betPrimary        = getenv('TEST_BET_PRIMARY') ?: '1';

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => $roundCode,
            "transactionCode" => $transactionCode,
            "transactionDate" => $date,
            "amount" => "0",
            "internalFundChanges" => [
                [
                    "type" => "BONUS",
                    "amount" => "-" . $betPrimary
                ]
            ],
            "gameCodeName" => $liveGameCode,
            "remoteBonusCode" => $remoteBonusCode,
            "bonusChanges" => [
                [
                    "remoteBonusCode" => $remoteBonusCode,
                    "bonusInstanceCode" => $bonusInstanceCode,
                    "freeSpinChange" => [
                        "count" => -1,
                        "value" => $betPrimary
                    ],
                    "bonusTemplateId" => $bonusTemplateId
                ]
            ],
            "liveTableDetails" => [
                "launchAlias" => "bal_baccaratko",
                "tableId" => "1234",
                "tableName" => "Integration Test"
            ]
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send FS Bet 3 request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('betType', 'Freespin (held)');

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

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('2.1 Freespin bonus round scenario without wagering (held)')]
    #[DisplayName('FS Gameroundresult (no win) | Freespin bonus round scenario without wagering (held)')]
    #[Description('Testing freespin result no win held')]
    #[Test]
    public function fs_result_no_win_held(): void
    {
        $roundCode = $this->getRoundCode('fs_held_scenario');

        $username     = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token        = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';

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
                "launchAlias" => "bal_baccaratko",
                "tableId" => "1234",
                "tableName" => "Integration Test"
            ]
        ];

        $endpoint = Endpoint::playtech('gameroundresult');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send FS Gameroundresult (no win) request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('resultType', 'No Win (held)');

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

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('2.1 Freespin bonus round scenario without wagering (held)')]
    #[DisplayName('FS Gameroundresult (win) | Freespin bonus round scenario without wagering (held)')]
    #[Description('Testing freespin result win held')]
    #[Test]
    public function fs_result_win_held(): void
    {
        $roundCode = $this->getRoundCode('fs_held_scenario_2');

        $username          = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token             = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode      = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $remoteBonusCode   = getenv('TEST_REMOTE_BONUS_CODE_PRIMARY') ?: '';
        $bonusInstanceCode = getenv('TEST_BONUS_INSTANCE_CODE_PRIMARY') ?: '';
        $bonusTemplateId   = getenv('TEST_BONUS_TEMPLATE_PRIMARY') ?: '12345';
        $winAmount         = getenv('TEST_WIN_AMOUNT') ?: '2';

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => $roundCode,
            "pay" => [
                "transactionCode" => uniqid('test_trx_'),
                "transactionDate" => $date,
                "amount" => "0",
                "type" => "WIN",
                "internalFundChanges" => [
                    [
                        "type" => "BONUS",
                        "amount" => $winAmount
                    ]
                ],
                "bonusChanges" => [],
                "bonusWinningsInfo" => [
                    "freeSpinWinning" => [
                        "remoteBonusCode" => $remoteBonusCode,
                        "bonusInstanceCode" => $bonusInstanceCode,
                        "amount" => $winAmount,
                        "bonusTemplateId" => $bonusTemplateId
                    ],
                    "goldenChipWinnings" => []
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
            #[DisplayName('Send FS Gameroundresult (win) request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('resultType', 'Win (held)');

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

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('2.1 Freespin bonus round scenario without wagering (held)')]
    #[DisplayName('FS Gameroundresult (win) | Freespin bonus round scenario without wagering (held)')]
    #[Description('Testing freespin result win held 2')]
    #[Test]
    public function fs_result_win_held_2(): void
    {
        $roundCode = $this->getRoundCode('fs_held_scenario_3');

        $username          = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token             = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode      = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $remoteBonusCode   = getenv('TEST_REMOTE_BONUS_CODE_PRIMARY') ?: '';
        $bonusInstanceCode = getenv('TEST_BONUS_INSTANCE_CODE_PRIMARY') ?: '';
        $bonusTemplateId   = getenv('TEST_BONUS_TEMPLATE_PRIMARY') ?: '12345';
        $winAmount         = getenv('TEST_WIN_AMOUNT') ?: '2';

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => $roundCode,
            "pay" => [
                "transactionCode" => uniqid('test_trx_'),
                "transactionDate" => $date,
                "amount" => "0",
                "type" => "WIN",
                "internalFundChanges" => [
                    [
                        "type" => "BONUS",
                        "amount" => $winAmount
                    ]
                ],
                "bonusChanges" => [],
                "bonusWinningsInfo" => [
                    "freeSpinWinning" => [
                        "remoteBonusCode" => $remoteBonusCode,
                        "bonusInstanceCode" => $bonusInstanceCode,
                        "amount" => $winAmount,
                        "bonusTemplateId" => $bonusTemplateId
                    ],
                    "goldenChipWinnings" => []
                ]
            ],
            "gameCodeName" => $liveGameCode,
            "liveTableDetails" => [
                "launchAlias" => "bal_baccaratko",
                "tableId" => "1234",
                "tableName" => "Integration Test"
            ]
        ];

        $endpoint = Endpoint::playtech('gameroundresult');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send FS Gameroundresult (win 2) request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('resultType', 'Win (held) - no close');

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

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('2.1 Freespin bonus round scenario without wagering (held)')]
    #[DisplayName('FS Gameroundresult (win) | Freespin bonus round scenario without wagering (held) with jackpot')]
    #[Description('Testing freespin result win held with jackpot')]
    #[Test]
    public function fs_result_win_held_jackpot(): void
    {
        $roundCode = $this->getRoundCode('fs_held_scenario_3');

        $username          = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token             = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode      = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $remoteBonusCode   = getenv('TEST_REMOTE_BONUS_CODE_PRIMARY') ?: '';
        $bonusInstanceCode = getenv('TEST_BONUS_INSTANCE_CODE_PRIMARY') ?: '';
        $bonusTemplateId   = getenv('TEST_BONUS_TEMPLATE_PRIMARY') ?: '12345';
        $winAmount         = getenv('TEST_WIN_AMOUNT') ?: '1';
        $jackpotWinAmount  = getenv('TEST_JACKPOT_WIN_AMOUNT') ?: '1';

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => $roundCode,
            "pay" => [
                "transactionCode" => uniqid('test_trx_'),
                "transactionDate" => $date,
                "amount" => "0",
                "type" => "WIN",
                "internalFundChanges" => [
                    [
                        "type" => "BONUS",
                        "amount" => $winAmount
                    ]
                ],
                "bonusChanges" => [],
                "bonusWinningsInfo" => [
                    "freeSpinWinning" => [
                        "remoteBonusCode" => $remoteBonusCode,
                        "bonusInstanceCode" => $bonusInstanceCode,
                        "amount" => $winAmount,
                        "bonusTemplateId" => $bonusTemplateId
                    ],
                    "goldenChipWinnings" => []
                ]
            ],
            "jackpot" => [
                "contributionAmount" => "0.0100000000000000",
                "winAmount" => $jackpotWinAmount,
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
                        "amount" => $jackpotWinAmount,
                        "jackpotId" => "test_110_120_130_140_333",
                        "subJackpotInfo" => [
                            [
                                "amount" => $jackpotWinAmount,
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
            #[DisplayName('Send FS Gameroundresult (win with jackpot) request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('resultType', 'Win (held) with jackpot');

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

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('2.1 Freespin bonus round scenario without wagering (held)')]
    #[DisplayName('Transferfunds | Freespin bonus round scenario without wagering (held)')]
    #[Description('Testing freespin transferfunds held')]
    #[Test]
    public function fs_transferfunds_held(): void
    {
        $username          = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $remoteBonusCode   = getenv('TEST_REMOTE_BONUS_CODE_PRIMARY') ?: '';
        $bonusInstanceCode = getenv('TEST_BONUS_INSTANCE_CODE_PRIMARY') ?: '';
        $bonusTemplateId   = getenv('TEST_BONUS_TEMPLATE_PRIMARY') ?: '12345';
        $transferAmount    = getenv('TEST_TRANSFER_AMOUNT') ?: '5';

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "transactionCode" => uniqid('test_trx_'),
            "transactionDate" => $date,
            "amount" => $transferAmount,
            "remoteBonusCode" => $remoteBonusCode,
            "bonusInstanceCode" => $bonusInstanceCode,
            "bonusTemplateId" => $bonusTemplateId,
            "type" => "BONUS",
            "bonusInfo" => [
                "remoteBonusCode" => $remoteBonusCode,
                "bonusInstanceCode" => $bonusInstanceCode,
                "bonusTemplateId" => $bonusTemplateId,
                "bonusType" => "FREESPIN"
            ]
        ];

        $endpoint = Endpoint::playtech('transferfunds');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Transferfunds request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('type', 'BONUS (FREESPIN)');

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
}
