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

trait S422GoldenChipsScenario
{
    // 4.2.2 Golden Chips bonus dealer push scenario (spend)
    #[Group('bonus')]
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('4.2 Golden Chips')]
    #[SubSuite('4.2.2 Golden Chips bonus dealer push scenario (spend)')]
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
    #[SubSuite('4.2.2 Golden Chips bonus dealer push scenario (spend)')]
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
}
