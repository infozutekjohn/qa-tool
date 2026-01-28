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

trait S62FeatureTestsScenario
{
    // 3. GameCodeName check - 3.1 Regular gameround scenario random game
    #[Group('gameslink')]
    #[ParentSuite('06. Gameslink Features Tests')]
    #[Suite('6.2 GameCodeName check')]
    #[DisplayName('Bet | GameCodeName check random game')]
    #[Description('Testing gameCodeName validation with random game')]
    #[Test]
    public function gamecodename_bet_random(): void
    {
        $roundCode = $this->getRoundCode('gamecodename_random');

        $username     = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token        = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $rngGameCode  = getenv('TEST_GAMECODENAME_GAME') ?: 'vnodc';
        $betPrimary   = getenv('TEST_BET_PRIMARY') ?: '1';

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
            "gameCodeName" => $rngGameCode
        ];

        $endpoint = Endpoint::playtech('bet');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send GameCodeName Bet request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint, $rngGameCode) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('gameCodeName', $rngGameCode);

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
            #[DisplayName('Verify gameCodeName was accepted')]
            function (StepContextInterface $step) use ($rngGameCode, &$checks) {
                $checks[] = '✔ gameCodeName: ' . $rngGameCode . ' was accepted';
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
    #[Suite('6.2 GameCodeName check')]
    #[DisplayName('Gameroundresult | GameCodeName check random game')]
    #[Description('Testing gameCodeName validation result for random game')]
    #[Test]
    public function gamecodename_result_random(): void
    {
        $roundCode = $this->getRoundCode('gamecodename_random');

        $username     = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token        = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $rngGameCode  = getenv('TEST_GAMECODENAME_GAME') ?: 'vnodc';

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
            "gameCodeName" => $rngGameCode,
            "gameHistoryUrl" => "getgamehistory.php?ThisIsJustAutomatedTestDataOK"
        ];

        $endpoint = Endpoint::playtech('gameroundresult');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send GameCodeName Result request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint, $rngGameCode) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('gameCodeName', $rngGameCode);

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
            #[DisplayName('Verify gameCodeName result completed')]
            function (StepContextInterface $step) use ($rngGameCode, &$checks) {
                $checks[] = '✔ gameCodeName: ' . $rngGameCode . ' round completed successfully';
            }
        );

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }
}
