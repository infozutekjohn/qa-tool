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

trait S421GoldenChipsScenario
{
    // 4.2.1 Golden Chips bonus round scenario (spend)
    #[Group('bonus')]
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('4.2 Golden Chips')]
    #[SubSuite('4.2.1 Golden Chips bonus round scenario (spend)')]
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
    #[SubSuite('4.2.1 Golden Chips bonus round scenario (spend)')]
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
    #[SubSuite('4.2.1 Golden Chips bonus round scenario (spend)')]
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
    #[SubSuite('4.2.1 Golden Chips bonus round scenario (spend)')]
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
            "checks"        => &$checks,
            "fullUrl"       => $fullUrl,
            "body"          => $body,
            "endpointType"  => 'result',
            "balanceAction" => 'added',
            "win"           => $winAmount
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }
}
