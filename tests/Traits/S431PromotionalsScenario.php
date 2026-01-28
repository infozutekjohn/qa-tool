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

trait S431PromotionalsScenario
{
    // 4.3.1 Cash Bonus
    #[Group('bonus')]
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('4.3 Promotionals')]
    #[SubSuite('4.3.1 Cash Bonus')]
    #[DisplayName('Transferfunds | Cash Bonus')]
    #[Description('Testing cash bonus transferfunds')]
    #[Test]
    public function cash_bonus_transferfunds(): void
    {
        $username          = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $bonusInstanceCode = getenv('TEST_BONUS_INSTANCE_CODE_PRIMARY') ?: '123123';
        $bonusTemplateId   = getenv('TEST_BONUS_TEMPLATE_PRIMARY') ?: '12345';
        $transferAmount    = getenv('TEST_TRANSFER_AMOUNT') ?: '2';

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "transactionCode" => uniqid('test_trx_') . bin2hex(random_bytes(4)),
            "transactionDate" => $date,
            "amount" => $transferAmount,
            "bonusInstanceCode" => $bonusInstanceCode,
            "bonusTemplateId" => $bonusTemplateId,
            "type" => "BONUS",
            "bonusInfo" => [
                "bonusInstanceCode" => $bonusInstanceCode,
                "bonusTemplateId" => $bonusTemplateId,
                "bonusType" => "CASH"
            ]
        ];

        $endpoint = Endpoint::playtech('transferfunds');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Cash Bonus Transferfunds request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('type', 'BONUS (CASH)');

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
            "endpointType"  => 'transferfunds',
            "balanceAction" => 'transferred',
            "transfer"      => $transferAmount
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }
}
