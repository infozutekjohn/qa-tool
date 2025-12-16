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

trait S43GoldenChipsScenario
{
    // 3.1 Golden Chips bonus round scenario (spend)
    #[Group('bonus')]
    #[Group('bonus-freespin')]
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.1 Golden Chips bonus round scenario (spend)')]
    #[DisplayName('GC Bet | Golden Chips bonus round scenario (spend)')]
    #[Description('Testing golden chips bet spend 1')]
    #[Test]
    public function gc_bet_spend_1(): void
    {
        $roundCode = $this->getRoundCode('gc_spend_scenario');
        $transactionCode = uniqid('test_trx_') . bin2hex(random_bytes(4));

        $username          = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token             = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode      = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $launchAlias  = getenv('TEST_LAUNCH_ALIAS') ?: 'bal_baccaratko';
        $tableId      = getenv('TEST_TABLE_ID') ?: '1234';
        $tableName    = getenv('TEST_TABLE_NAME') ?: 'Integration Test';
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
                    "goldenChipChange" => [
                        "count" => -1,
                        "value" => $betPrimary
                    ],
                    "bonusTemplateId" => $bonusTemplateId
                ]
            ],
            "liveTableDetails" => [
                "launchAlias" => $launchAlias,
                "tableId" => $tableId,
                "tableName" => $tableName
            ]
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send GC Bet 1 request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('betType', 'Golden Chips (spend)');

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
            "balanceAction" => 'unchanged',
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('bonus')]
    #[Group('bonus-freespin')]
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.1 Golden Chips bonus round scenario (spend)')]
    #[DisplayName('GC Bet | Golden Chips bonus round scenario (spend)')]
    #[Description('Testing golden chips bet spend 2')]
    #[Test]
    public function gc_bet_spend_2(): void
    {
        $roundCode = $this->getRoundCode('gc_spend_scenario_2');
        $transactionCode = uniqid('test_trx_') . bin2hex(random_bytes(4));

        $username          = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token             = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode      = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $launchAlias  = getenv('TEST_LAUNCH_ALIAS') ?: 'bal_baccaratko';
        $tableId      = getenv('TEST_TABLE_ID') ?: '1234';
        $tableName    = getenv('TEST_TABLE_NAME') ?: 'Integration Test';
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
                    "goldenChipChange" => [
                        "count" => -1,
                        "value" => $betPrimary
                    ],
                    "bonusTemplateId" => $bonusTemplateId
                ]
            ],
            "liveTableDetails" => [
                "launchAlias" => $launchAlias,
                "tableId" => $tableId,
                "tableName" => $tableName
            ]
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send GC Bet 2 request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('betType', 'Golden Chips (spend)');

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
            "balanceAction" => 'unchanged',
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('bonus')]
    #[Group('bonus-freespin')]
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.1 Golden Chips bonus round scenario (spend)')]
    #[DisplayName('GC Gameroundresult (no win) | Golden Chips bonus round scenario (spend)')]
    #[Description('Testing golden chips result no win spend')]
    #[Test]
    public function gc_result_no_win_spend(): void
    {
        $roundCode = $this->getRoundCode('gc_spend_scenario');

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
            #[DisplayName('Send GC Gameroundresult (no win) request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('resultType', 'No Win (spend)');

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
            "balanceAction" => 'unchanged',
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('bonus')]
    #[Group('bonus-freespin')]
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.1 Golden Chips bonus round scenario (spend)')]
    #[DisplayName('GC Gameroundresult (win) | Golden Chips bonus round scenario (spend)')]
    #[Description('Testing golden chips result win spend')]
    #[Test]
    public function gc_result_win_spend(): void
    {
        $roundCode = $this->getRoundCode('gc_spend_scenario_2');

        $username          = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token             = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode      = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $launchAlias  = getenv('TEST_LAUNCH_ALIAS') ?: 'bal_baccaratko';
        $tableId      = getenv('TEST_TABLE_ID') ?: '1234';
        $tableName    = getenv('TEST_TABLE_NAME') ?: 'Integration Test';
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
                "amount" => $winAmount,
                "type" => "WIN",
                "internalFundChanges" => [],
                "bonusChanges" => [],
                "bonusWinningsInfo" => [
                    "freeSpinWinning" => null,
                    "goldenChipWinnings" => [
                        [
                            "remoteBonusCode" => $remoteBonusCode,
                            "bonusInstanceCode" => $bonusInstanceCode,
                            "amount" => $winAmount,
                            "bonusTemplateId" => $bonusTemplateId
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
                "launchAlias" => $launchAlias,
                "tableId" => $tableId,
                "tableName" => $tableName
            ]
        ];

        $endpoint = Endpoint::playtech('gameroundresult');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send GC Gameroundresult (win) request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('resultType', 'Win (spend)');

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
            "balanceAction" => 'unchanged',
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    // 3.2 Golden Chips bonus dealer push scenario (spend)
    #[Group('bonus')]
    #[Group('bonus-freespin')]
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.2 Golden Chips bonus dealer push scenario (spend)')]
    #[DisplayName('GC Bet | Golden Chips bonus dealer push scenario (spend)')]
    #[Description('Testing golden chips dealer push bet')]
    #[Test]
    public function gc_bet_dealer_push(): void
    {
        $roundCode = $this->getRoundCode('gc_dealer_push_scenario');
        $transactionCode = uniqid('test_trx_') . bin2hex(random_bytes(4));

        $username          = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token             = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode      = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $launchAlias  = getenv('TEST_LAUNCH_ALIAS') ?: 'bal_baccaratko';
        $tableId      = getenv('TEST_TABLE_ID') ?: '1234';
        $tableName    = getenv('TEST_TABLE_NAME') ?: 'Integration Test';
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
                    "goldenChipChange" => [
                        "count" => -1,
                        "value" => $betPrimary
                    ],
                    "bonusTemplateId" => $bonusTemplateId
                ]
            ],
            "liveTableDetails" => [
                "launchAlias" => $launchAlias,
                "tableId" => $tableId,
                "tableName" => $tableName
            ]
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send GC Bet (dealer push) request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('betType', 'Golden Chips (dealer push)');

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
            "balanceAction" => 'unchanged',
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('bonus')]
    #[Group('bonus-freespin')]
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.2 Golden Chips bonus dealer push scenario (spend)')]
    #[DisplayName('GC Gameroundresult (push) | Golden Chips bonus dealer push scenario (spend)')]
    #[Description('Testing golden chips dealer push result')]
    #[Test]
    public function gc_result_dealer_push(): void
    {
        $roundCode = $this->getRoundCode('gc_dealer_push_scenario');

        $username          = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token             = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode      = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $launchAlias  = getenv('TEST_LAUNCH_ALIAS') ?: 'bal_baccaratko';
        $tableId      = getenv('TEST_TABLE_ID') ?: '1234';
        $tableName    = getenv('TEST_TABLE_NAME') ?: 'Integration Test';
        $remoteBonusCode   = getenv('TEST_REMOTE_BONUS_CODE_PRIMARY') ?: '';
        $bonusInstanceCode = getenv('TEST_BONUS_INSTANCE_CODE_PRIMARY') ?: '';
        $bonusTemplateId   = getenv('TEST_BONUS_TEMPLATE_PRIMARY') ?: '12345';
        $betPrimary        = getenv('TEST_BET_PRIMARY') ?: '1';

        $date = $this->generateDate();

        // Dealer push returns the golden chip - pay amount equals bet amount
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
                        "amount" => $betPrimary
                    ]
                ],
                "bonusChanges" => [
                    [
                        "remoteBonusCode" => $remoteBonusCode,
                        "bonusInstanceCode" => $bonusInstanceCode,
                        "goldenChipChange" => [
                            "count" => 1,
                            "value" => $betPrimary
                        ],
                        "bonusTemplateId" => $bonusTemplateId
                    ]
                ],
                "bonusWinningsInfo" => [
                    "freeSpinWinning" => null,
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
                "launchAlias" => $launchAlias,
                "tableId" => $tableId,
                "tableName" => $tableName
            ]
        ];

        $endpoint = Endpoint::playtech('gameroundresult');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send GC Gameroundresult (dealer push) request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('resultType', 'Dealer Push - Golden Chip returned');

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
            "balanceAction" => 'unchanged',
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    // 3.3 Golden Chips multiple bonus round scenario (spend)
    #[Group('bonus')]
    #[Group('bonus-freespin')]
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.3 Golden Chips multiple bonus round scenario (spend)')]
    #[DisplayName('GC Bet | Golden Chips multiple bonus round scenario (spend)')]
    #[Description('Testing golden chips multiple bet 1')]
    #[Test]
    public function gc_multiple_bet_1(): void
    {
        $roundCode = $this->getRoundCode('gc_multiple_scenario');
        $transactionCode = uniqid('test_trx_') . bin2hex(random_bytes(4));

        $username           = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token              = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode       = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $launchAlias  = getenv('TEST_LAUNCH_ALIAS') ?: 'bal_baccaratko';
        $tableId      = getenv('TEST_TABLE_ID') ?: '1234';
        $tableName    = getenv('TEST_TABLE_NAME') ?: 'Integration Test';
        $remoteBonusCode    = getenv('TEST_REMOTE_BONUS_CODE_PRIMARY') ?: '';
        $bonusInstanceCode  = getenv('TEST_BONUS_INSTANCE_CODE_PRIMARY') ?: '';
        $bonusTemplateId    = getenv('TEST_BONUS_TEMPLATE_PRIMARY') ?: '12345';
        $remoteBonusCode2   = getenv('TEST_REMOTE_BONUS_CODE_SECONDARY') ?: '';
        $bonusInstanceCode2 = getenv('TEST_BONUS_INSTANCE_CODE_SECONDARY') ?: '';
        $bonusTemplateId2   = getenv('TEST_BONUS_TEMPLATE_SECONDARY') ?: '12346';
        $betPrimary         = getenv('TEST_BET_PRIMARY') ?: '1';

        $date = $this->generateDate();

        // Multiple golden chips from different bonus instances
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
                    "goldenChipChange" => [
                        "count" => -1,
                        "value" => $betPrimary
                    ],
                    "bonusTemplateId" => $bonusTemplateId
                ]
            ],
            "liveTableDetails" => [
                "launchAlias" => $launchAlias,
                "tableId" => $tableId,
                "tableName" => $tableName
            ]
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send GC Multiple Bet 1 request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('betType', 'Golden Chips Multiple (first bet)');

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
            "balanceAction" => 'unchanged',
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('bonus')]
    #[Group('bonus-freespin')]
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.3 Golden Chips multiple bonus round scenario (spend)')]
    #[DisplayName('GC Bet | Golden Chips multiple bonus round scenario (spend)')]
    #[Description('Testing golden chips multiple bet 2')]
    #[Test]
    public function gc_multiple_bet_2(): void
    {
        $roundCode = $this->getRoundCode('gc_multiple_scenario');
        $transactionCode = uniqid('test_trx_') . bin2hex(random_bytes(4));

        $username           = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token              = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode       = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $launchAlias  = getenv('TEST_LAUNCH_ALIAS') ?: 'bal_baccaratko';
        $tableId      = getenv('TEST_TABLE_ID') ?: '1234';
        $tableName    = getenv('TEST_TABLE_NAME') ?: 'Integration Test';
        $remoteBonusCode2   = getenv('TEST_REMOTE_BONUS_CODE_SECONDARY') ?: '';
        $bonusInstanceCode2 = getenv('TEST_BONUS_INSTANCE_CODE_SECONDARY') ?: '';
        $bonusTemplateId2   = getenv('TEST_BONUS_TEMPLATE_SECONDARY') ?: '12346';
        $betPrimary         = getenv('TEST_BET_PRIMARY') ?: '1';

        $date = $this->generateDate();

        // Second golden chip from secondary bonus instance
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
            "remoteBonusCode" => $remoteBonusCode2,
            "bonusChanges" => [
                [
                    "remoteBonusCode" => $remoteBonusCode2,
                    "bonusInstanceCode" => $bonusInstanceCode2,
                    "goldenChipChange" => [
                        "count" => -1,
                        "value" => $betPrimary
                    ],
                    "bonusTemplateId" => $bonusTemplateId2
                ]
            ],
            "liveTableDetails" => [
                "launchAlias" => $launchAlias,
                "tableId" => $tableId,
                "tableName" => $tableName
            ]
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send GC Multiple Bet 2 request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('betType', 'Golden Chips Multiple (second bet)');

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
            "balanceAction" => 'unchanged',
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('bonus')]
    #[Group('bonus-freespin')]
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.3 Golden Chips multiple bonus round scenario (spend)')]
    #[DisplayName('GC Gameroundresult (no win) | Golden Chips multiple bonus round scenario (spend)')]
    #[Description('Testing golden chips multiple result no win')]
    #[Test]
    public function gc_multiple_result_no_win(): void
    {
        $roundCode = $this->getRoundCode('gc_multiple_scenario');

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
            #[DisplayName('Send GC Multiple Gameroundresult (no win) request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('resultType', 'Multiple GC No Win');

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
            "balanceAction" => 'unchanged',
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('bonus')]
    #[Group('bonus-freespin')]
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.3 Golden Chips multiple bonus round scenario (spend)')]
    #[DisplayName('GC Gameroundresult (win) | Golden Chips multiple bonus round scenario (spend)')]
    #[Description('Testing golden chips multiple result win')]
    #[Test]
    public function gc_multiple_result_win(): void
    {
        $roundCode = $this->getRoundCode('gc_multiple_scenario_win');

        $username           = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token              = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode       = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $launchAlias  = getenv('TEST_LAUNCH_ALIAS') ?: 'bal_baccaratko';
        $tableId      = getenv('TEST_TABLE_ID') ?: '1234';
        $tableName    = getenv('TEST_TABLE_NAME') ?: 'Integration Test';
        $remoteBonusCode    = getenv('TEST_REMOTE_BONUS_CODE_PRIMARY') ?: '';
        $bonusInstanceCode  = getenv('TEST_BONUS_INSTANCE_CODE_PRIMARY') ?: '';
        $bonusTemplateId    = getenv('TEST_BONUS_TEMPLATE_PRIMARY') ?: '12345';
        $remoteBonusCode2   = getenv('TEST_REMOTE_BONUS_CODE_SECONDARY') ?: '';
        $bonusInstanceCode2 = getenv('TEST_BONUS_INSTANCE_CODE_SECONDARY') ?: '';
        $bonusTemplateId2   = getenv('TEST_BONUS_TEMPLATE_SECONDARY') ?: '12346';
        $winAmountPrimary   = getenv('TEST_WIN_AMOUNT_PRIMARY') ?: '1';
        $winAmountSecondary = getenv('TEST_WIN_AMOUNT_SECONDARY') ?: '10';

        // Total win from multiple golden chips
        $totalWin = (string)((float)$winAmountPrimary + (float)$winAmountSecondary);

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => $roundCode,
            "pay" => [
                "transactionCode" => uniqid('test_trx_'),
                "transactionDate" => $date,
                "amount" => $totalWin,
                "type" => "WIN",
                "internalFundChanges" => [],
                "bonusChanges" => [],
                "bonusWinningsInfo" => [
                    "goldenChipWinnings" => [
                        [
                            "remoteBonusCode" => $remoteBonusCode,
                            "bonusInstanceCode" => $bonusInstanceCode,
                            "amount" => $winAmountPrimary,
                            "count" => 1,
                            "bonusTemplateId" => $bonusTemplateId
                        ],
                        [
                            "remoteBonusCode" => $remoteBonusCode2,
                            "bonusInstanceCode" => $bonusInstanceCode2,
                            "amount" => $winAmountSecondary,
                            "count" => 1,
                            "bonusTemplateId" => $bonusTemplateId2
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
                "launchAlias" => $launchAlias,
                "tableId" => $tableId,
                "tableName" => $tableName
            ]
        ];

        $endpoint = Endpoint::playtech('gameroundresult');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send GC Multiple Gameroundresult (win) request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('resultType', 'Multiple GC Win (spend)');

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
            "balanceAction" => 'unchanged',
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    // 3.4 Golden Chips bonus round + real money combo scenario (spend)
    #[Group('bonus')]
    #[Group('bonus-freespin')]
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.4 Golden Chips bonus round + real money combo scenario (spend)')]
    #[DisplayName('GC Bet | Golden Chips round + real money combo scenario (spend)')]
    #[Description('Testing golden chips real money combo bet')]
    #[Test]
    public function gc_real_money_combo_bet(): void
    {
        $roundCode = $this->getRoundCode('gc_real_money_combo_scenario');
        $transactionCode = uniqid('test_trx_') . bin2hex(random_bytes(4));

        $username          = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token             = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode      = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $launchAlias  = getenv('TEST_LAUNCH_ALIAS') ?: 'bal_baccaratko';
        $tableId      = getenv('TEST_TABLE_ID') ?: '1234';
        $tableName    = getenv('TEST_TABLE_NAME') ?: 'Integration Test';
        $remoteBonusCode   = getenv('TEST_REMOTE_BONUS_CODE_PRIMARY') ?: '';
        $bonusInstanceCode = getenv('TEST_BONUS_INSTANCE_CODE_PRIMARY') ?: '';
        $bonusTemplateId   = getenv('TEST_BONUS_TEMPLATE_PRIMARY') ?: '12345';
        $betPrimary        = getenv('TEST_BET_PRIMARY') ?: '1';
        $realBetAmount     = getenv('TEST_REAL_BET_AMOUNT') ?: '1';

        // Total bet = real money + golden chip value
        $totalBet = (string)((float)$realBetAmount + (float)$betPrimary);

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => $roundCode,
            "transactionCode" => $transactionCode,
            "transactionDate" => $date,
            "amount" => $realBetAmount,
            "internalFundChanges" => [
                [
                    "type" => "REAL",
                    "amount" => "-" . $realBetAmount
                ],
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
                    "goldenChipChange" => [
                        "count" => -1,
                        "value" => $betPrimary
                    ],
                    "bonusTemplateId" => $bonusTemplateId
                ]
            ],
            "liveTableDetails" => [
                "launchAlias" => $launchAlias,
                "tableId" => $tableId,
                "tableName" => $tableName
            ]
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send GC + Real Money Combo Bet request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('betType', 'Golden Chips + Real Money Combo');

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
            "balanceAction" => 'unchanged',
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('bonus')]
    #[Group('bonus-freespin')]
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.4 Golden Chips bonus round + real money combo scenario (spend)')]
    #[DisplayName('GC Gameroundresult (win) | Golden Chips round + real money combo scenario (spend)')]
    #[Description('Testing golden chips real money combo result win')]
    #[Test]
    public function gc_real_money_combo_result_win(): void
    {
        $roundCode = $this->getRoundCode('gc_real_money_combo_scenario');

        $username          = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token             = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode      = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $launchAlias  = getenv('TEST_LAUNCH_ALIAS') ?: 'bal_baccaratko';
        $tableId      = getenv('TEST_TABLE_ID') ?: '1234';
        $tableName    = getenv('TEST_TABLE_NAME') ?: 'Integration Test';
        $remoteBonusCode   = getenv('TEST_REMOTE_BONUS_CODE_PRIMARY') ?: '';
        $bonusInstanceCode = getenv('TEST_BONUS_INSTANCE_CODE_PRIMARY') ?: '';
        $bonusTemplateId   = getenv('TEST_BONUS_TEMPLATE_PRIMARY') ?: '12345';
        $winAmount         = getenv('TEST_WIN_AMOUNT') ?: '4';

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
                "internalFundChanges" => [
                    [
                        "type" => "REAL",
                        "amount" => $winAmount
                    ]
                ],
                "bonusChanges" => [],
                "bonusWinningsInfo" => [
                    "freeSpinWinning" => null,
                    "goldenChipWinnings" => [
                        [
                            "remoteBonusCode" => $remoteBonusCode,
                            "bonusInstanceCode" => $bonusInstanceCode,
                            "amount" => $winAmount,
                            "bonusTemplateId" => $bonusTemplateId
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
                "launchAlias" => $launchAlias,
                "tableId" => $tableId,
                "tableName" => $tableName
            ]
        ];

        $endpoint = Endpoint::playtech('gameroundresult');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send GC + Real Money Combo Gameroundresult (win) request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('resultType', 'GC + Real Money Combo Win - REAL funds');

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
            "balanceAction" => 'unchanged',
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    // 3.5 Golden Chips multiple bonus round + real money combo scenario (spend)
    #[Group('bonus')]
    #[Group('bonus-freespin')]
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.5 Golden Chips multiple bonus round + real money combo scenario (spend)')]
    #[DisplayName('GC Bet | Multiple GC + real money combo scenario (spend)')]
    #[Description('Testing golden chips multiple real money combo bet')]
    #[Test]
    public function gc_multiple_real_money_bet(): void
    {
        $roundCode = $this->getRoundCode('gc_multiple_real_money_scenario');
        $transactionCode = uniqid('test_trx_') . bin2hex(random_bytes(4));

        $username           = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token              = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode       = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $launchAlias  = getenv('TEST_LAUNCH_ALIAS') ?: 'bal_baccaratko';
        $tableId      = getenv('TEST_TABLE_ID') ?: '1234';
        $tableName    = getenv('TEST_TABLE_NAME') ?: 'Integration Test';
        $remoteBonusCode    = getenv('TEST_REMOTE_BONUS_CODE_PRIMARY') ?: '';
        $bonusInstanceCode  = getenv('TEST_BONUS_INSTANCE_CODE_PRIMARY') ?: '';
        $bonusTemplateId    = getenv('TEST_BONUS_TEMPLATE_PRIMARY') ?: '12345';
        $remoteBonusCode2   = getenv('TEST_REMOTE_BONUS_CODE_SECONDARY') ?: '';
        $bonusInstanceCode2 = getenv('TEST_BONUS_INSTANCE_CODE_SECONDARY') ?: '';
        $bonusTemplateId2   = getenv('TEST_BONUS_TEMPLATE_SECONDARY') ?: '12346';
        $betPrimary         = getenv('TEST_BET_PRIMARY') ?: '1';
        $realBetAmount      = getenv('TEST_REAL_BET_AMOUNT') ?: '1';

        // Total bonus = 2 golden chips
        $totalBonus = (string)((float)$betPrimary * 2);

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => $roundCode,
            "transactionCode" => $transactionCode,
            "transactionDate" => $date,
            "amount" => $realBetAmount,
            "internalFundChanges" => [
                [
                    "type" => "REAL",
                    "amount" => "-" . $realBetAmount
                ],
                [
                    "type" => "BONUS",
                    "amount" => "-" . $totalBonus
                ]
            ],
            "gameCodeName" => $liveGameCode,
            "remoteBonusCode" => $remoteBonusCode,
            "bonusChanges" => [
                [
                    "remoteBonusCode" => $remoteBonusCode,
                    "bonusInstanceCode" => $bonusInstanceCode,
                    "goldenChipChange" => [
                        "count" => -1,
                        "value" => $betPrimary
                    ],
                    "bonusTemplateId" => $bonusTemplateId
                ],
                [
                    "remoteBonusCode" => $remoteBonusCode2,
                    "bonusInstanceCode" => $bonusInstanceCode2,
                    "goldenChipChange" => [
                        "count" => -1,
                        "value" => $betPrimary
                    ],
                    "bonusTemplateId" => $bonusTemplateId2
                ]
            ],
            "liveTableDetails" => [
                "launchAlias" => $launchAlias,
                "tableId" => $tableId,
                "tableName" => $tableName
            ]
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Multiple GC + Real Money Combo Bet request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('betType', 'Multiple Golden Chips + Real Money Combo');

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
            "balanceAction" => 'unchanged',
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('bonus')]
    #[Group('bonus-freespin')]
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.5 Golden Chips multiple bonus round + real money combo scenario (spend)')]
    #[DisplayName('GC Gameroundresult (win) | Multiple GC + real money combo scenario (spend)')]
    #[Description('Testing golden chips multiple real money combo result')]
    #[Test]
    public function gc_multiple_real_money_result(): void
    {
        $roundCode = $this->getRoundCode('gc_multiple_real_money_scenario');

        $username           = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token              = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode       = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $launchAlias  = getenv('TEST_LAUNCH_ALIAS') ?: 'bal_baccaratko';
        $tableId      = getenv('TEST_TABLE_ID') ?: '1234';
        $tableName    = getenv('TEST_TABLE_NAME') ?: 'Integration Test';
        $remoteBonusCode    = getenv('TEST_REMOTE_BONUS_CODE_PRIMARY') ?: '';
        $bonusInstanceCode  = getenv('TEST_BONUS_INSTANCE_CODE_PRIMARY') ?: '';
        $bonusTemplateId    = getenv('TEST_BONUS_TEMPLATE_PRIMARY') ?: '12345';
        $remoteBonusCode2   = getenv('TEST_REMOTE_BONUS_CODE_SECONDARY') ?: '';
        $bonusInstanceCode2 = getenv('TEST_BONUS_INSTANCE_CODE_SECONDARY') ?: '';
        $bonusTemplateId2   = getenv('TEST_BONUS_TEMPLATE_SECONDARY') ?: '12346';
        $winAmount          = getenv('TEST_WIN_AMOUNT') ?: '2';

        // Total win from multiple golden chips + real money
        $totalWin = (string)((float)$winAmount * 3);

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => $roundCode,
            "pay" => [
                "transactionCode" => uniqid('test_trx_'),
                "transactionDate" => $date,
                "amount" => $totalWin,
                "type" => "WIN",
                "internalFundChanges" => [
                    [
                        "type" => "REAL",
                        "amount" => $totalWin
                    ]
                ],
                "bonusChanges" => [],
                "bonusWinningsInfo" => [
                    "freeSpinWinning" => null,
                    "goldenChipWinnings" => [
                        [
                            "remoteBonusCode" => $remoteBonusCode,
                            "bonusInstanceCode" => $bonusInstanceCode,
                            "amount" => $winAmount,
                            "bonusTemplateId" => $bonusTemplateId
                        ],
                        [
                            "remoteBonusCode" => $remoteBonusCode2,
                            "bonusInstanceCode" => $bonusInstanceCode2,
                            "amount" => $winAmount,
                            "bonusTemplateId" => $bonusTemplateId2
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
                "launchAlias" => $launchAlias,
                "tableId" => $tableId,
                "tableName" => $tableName
            ]
        ];

        $endpoint = Endpoint::playtech('gameroundresult');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Multiple GC + Real Money Combo Gameroundresult (win) request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('resultType', 'Multiple GC + Real Money Combo Win - REAL funds');

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
            "balanceAction" => 'unchanged',
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    // 3.6 Golden Chips bonus refund scenario (spend)
    #[Group('bonus')]
    #[Group('bonus-freespin')]
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.6 Golden Chips bonus refund scenario (spend)')]
    #[DisplayName('GC Bet | Golden Chips bonus refund scenario (spend)')]
    #[Description('Testing golden chips refund bet')]
    #[Test]
    public function gc_refund_bet(): void
    {
        $roundCode = $this->getRoundCode('gc_refund_scenario');
        $transactionCode = uniqid('test_trx_') . bin2hex(random_bytes(4));

        $username          = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token             = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode      = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $launchAlias  = getenv('TEST_LAUNCH_ALIAS') ?: 'bal_baccaratko';
        $tableId      = getenv('TEST_TABLE_ID') ?: '1234';
        $tableName    = getenv('TEST_TABLE_NAME') ?: 'Integration Test';
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
                    "goldenChipChange" => [
                        "count" => -1,
                        "value" => $betPrimary
                    ],
                    "bonusTemplateId" => $bonusTemplateId
                ]
            ],
            "liveTableDetails" => [
                "launchAlias" => $launchAlias,
                "tableId" => $tableId,
                "tableName" => $tableName
            ]
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send GC Bet (for refund) request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('betType', 'Golden Chips (for refund)');

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
            "balanceAction" => 'unchanged',
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('bonus')]
    #[Group('bonus-freespin')]
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.6 Golden Chips bonus refund scenario (spend)')]
    #[DisplayName('GC Gameroundresult (gameRoundClose) | Casino | Golden Chips bonus refund scenario (spend)')]
    #[Description('Testing golden chips refund game round close')]
    #[Test]
    public function gc_refund_game_round_close(): void
    {
        $roundCode = $this->getRoundCode('gc_refund_scenario');

        $username     = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token        = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $launchAlias  = getenv('TEST_LAUNCH_ALIAS') ?: 'bal_baccaratko';
        $tableId      = getenv('TEST_TABLE_ID') ?: '1234';
        $tableName    = getenv('TEST_TABLE_NAME') ?: 'Integration Test';

        $date = $this->generateDate();

        // Game round close before refund
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
            #[DisplayName('Send GC Gameroundresult (gameRoundClose) request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('resultType', 'Game Round Close (before refund)');

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
            "balanceAction" => 'unchanged',
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('bonus')]
    #[Group('bonus-freespin')]
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.6 Golden Chips bonus refund scenario (spend)')]
    #[DisplayName('GC Gameroundresult (refund) | Golden Chips bonus refund scenario (spend)')]
    #[Description('Testing golden chips refund result')]
    #[Test]
    public function gc_refund_result(): void
    {
        $roundCode = $this->getRoundCode('gc_refund_scenario');

        $username           = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token              = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode       = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $launchAlias  = getenv('TEST_LAUNCH_ALIAS') ?: 'bal_baccaratko';
        $tableId      = getenv('TEST_TABLE_ID') ?: '1234';
        $tableName    = getenv('TEST_TABLE_NAME') ?: 'Integration Test';
        $remoteBonusCode    = getenv('TEST_REMOTE_BONUS_CODE_PRIMARY') ?: '';
        $bonusInstanceCode  = getenv('TEST_BONUS_INSTANCE_CODE_PRIMARY') ?: '';
        $bonusTemplateId    = getenv('TEST_BONUS_TEMPLATE_PRIMARY') ?: '12345';
        $betPrimary         = getenv('TEST_BET_PRIMARY') ?: '1';

        $date = $this->generateDate();

        // Refund uses type REFUND with relatedTransactionCode pointing to original bet
        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => $roundCode,
            "pay" => [
                "transactionCode" => uniqid('test_trx_'),
                "transactionDate" => $date,
                "amount" => "0",
                "type" => "REFUND",
                "internalFundChanges" => [
                    [
                        "type" => "BONUS",
                        "amount" => $betPrimary
                    ]
                ],
                "relatedTransactionCode" => uniqid('test_trx_'),
                "bonusChanges" => [
                    [
                        "remoteBonusCode" => $remoteBonusCode,
                        "bonusInstanceCode" => $bonusInstanceCode,
                        "goldenChipsChange" => [
                            "count" => 1,
                            "value" => $betPrimary
                        ],
                        "bonusTemplateId" => $bonusTemplateId
                    ]
                ]
            ],
            "gameCodeName" => $liveGameCode,
            "liveTableDetails" => [
                "launchAlias" => $launchAlias,
                "tableId" => $tableId,
                "tableName" => $tableName
            ]
        ];

        $endpoint = Endpoint::playtech('gameroundresult');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send GC Gameroundresult (refund) request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('resultType', 'Refund - Golden Chip returned');

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
            "balanceAction" => 'unchanged',
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }
}
