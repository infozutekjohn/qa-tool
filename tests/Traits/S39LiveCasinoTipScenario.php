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

trait S39LiveCasinoTipScenario
{
    // 2.9 Live tip scenario
    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.9 Live tip scenario')]
    #[DisplayName('Livetip | Live Casino | Live tip scenario')]
    #[Description('Testing live casino tip transaction')]
    #[Test]
    public function live_tip(): void
    {
        $username     = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token        = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';
        $liveGameCode = getenv('TEST_LIVE_GAME_CODE') ?: 'ubal';
        $tipAmount    = getenv('TEST_TIP_AMOUNT') ?: '1';

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token,
            "transactionCode" => uniqid('test_trx_') . bin2hex(random_bytes(4)),
            "transactionDate" => $date,
            "amount" => $tipAmount,
            "gameCodeName" => $liveGameCode,
            "liveTableDetails" => [
                "launchAlias" => "bal_baccaratko",
                "tableId" => "1234",
                "tableName" => "Integration Test"
            ]
        ];

        $endpoint = Endpoint::playtech('livetip');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Livetip request to endpoint')]
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

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }
}
