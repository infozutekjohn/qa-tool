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

trait S63FeatureTestsScenario
{
    // 4. LaunchAlias check - 4.1 Regular gameround scenario random game (live casino with launchAlias)
    #[Group('gameslink')]
    #[ParentSuite('06. Gameslink Features Tests')]
    #[Suite('6.3 LaunchAlias check')]
    #[DisplayName('Bet | LaunchAlias check random game')]
    #[Description('Testing launchAlias validation with random live game')]
    #[Test]
    public function launchalias_bet_random(): void
    {
        $roundCode = $this->getRoundCode('launchalias_random');

        $username     = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token        = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $launchAlias  = getenv('TEST_LAUNCH_ALIAS') ?: 'bal_baccaratko';
        $betPrimary   = getenv('TEST_BET_PRIMARY') ?: '1';
        $tableId      = getenv('TEST_TABLE_ID') ?: '1234';
        $tableName    = getenv('TEST_TABLE_NAME') ?: 'Integration Test';

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "gameRoundCode" => $roundCode,
            "transactionCode" => uniqid('test_trx_') . bin2hex(random_bytes(4)),
            "transactionDate" => $date,
            "amount" => $betPrimary,
            "internalFundChanges" => [],
            "gameCodeName" => $liveGameCode,
            "liveTableDetails" => [
                "launchAlias" => $launchAlias,
                "tableId" => $tableId,
                "tableName" => $tableName
            ]
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send LaunchAlias Bet request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint, $launchAlias) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('launchAlias', $launchAlias);

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
            "errorScenario" => false,
            "balanceAction" => 'deducted',
            "bet"           => $betPrimary
        ]);

        Allure::runStep(
            #[DisplayName('Verify launchAlias was accepted')]
            function (StepContextInterface $step) use ($launchAlias, &$checks) {
                $checks[] = '✔ launchAlias: ' . $launchAlias . ' was accepted';
            }
        );

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('gameslink')]
    #[ParentSuite('06. Gameslink Features Tests')]
    #[Suite('6.3 LaunchAlias check')]
    #[DisplayName('Gameroundresult | LaunchAlias check random game')]
    #[Description('Testing launchAlias validation result for random live game')]
    #[Test]
    public function launchalias_result_random(): void
    {
        $roundCode = $this->getRoundCode('launchalias_random');

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
                "date" => $date
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
            #[DisplayName('Send LaunchAlias Result request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint, $launchAlias) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('launchAlias', $launchAlias);

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
            "endpointType"  => 'gameroundresult',
            "errorScenario" => false,
            "balanceAction" => 'unchanged',
        ]);

        Allure::runStep(
            #[DisplayName('Verify launchAlias result completed')]
            function (StepContextInterface $step) use ($launchAlias, &$checks) {
                $checks[] = '✔ launchAlias: ' . $launchAlias . ' round completed successfully';
            }
        );

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }
}
