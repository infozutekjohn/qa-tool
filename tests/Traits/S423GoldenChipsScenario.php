<?php

namespace Tests\Traits;

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

trait S423GoldenChipsScenario
{
    // 4.2.3 Golden Chips multiple bonus round scenario (spend)
    #[Group('bonus')]
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('4.2 Golden Chips')]
    #[SubSuite('4.2.3 Golden Chips multiple bonus round scenario (spend)')]
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
            "checks"        => &$checks,
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
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('4.2 Golden Chips')]
    #[SubSuite('4.2.3 Golden Chips multiple bonus round scenario (spend)')]
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
            "checks"        => &$checks,
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
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('4.2 Golden Chips')]
    #[SubSuite('4.2.3 Golden Chips multiple bonus round scenario (spend)')]
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
            "checks"        => &$checks,
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
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('4.2 Golden Chips')]
    #[SubSuite('4.2.3 Golden Chips multiple bonus round scenario (spend)')]
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
            "checks"        => &$checks,
            "fullUrl"       => $fullUrl,
            "body"          => $body,
            "endpointType"  => 'result',
            "balanceAction" => 'added',
            "win"           => $totalWin
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }
}
