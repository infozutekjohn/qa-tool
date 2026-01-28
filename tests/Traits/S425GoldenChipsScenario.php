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

trait S425GoldenChipsScenario
{
    // 4.2.5 Golden Chips multiple bonus round + real money combo scenario (spend)
    #[Group('bonus')]
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('4.2 Golden Chips')]
    #[SubSuite('4.2.5 Golden Chips multiple bonus round + real money combo scenario (spend)')]
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
            "checks"        => &$checks,
            "fullUrl"       => $fullUrl,
            "body"          => $body,
            "endpointType"  => 'bet',
            "balanceAction" => 'deducted',
            "bet"           => $realBetAmount
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
    #[SubSuite('4.2.5 Golden Chips multiple bonus round + real money combo scenario (spend)')]
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
