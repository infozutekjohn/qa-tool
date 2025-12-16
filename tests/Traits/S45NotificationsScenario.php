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

trait S45NotificationsScenario
{
    // 5.1 Bonus removal
    #[Group('bonus')]
    #[Group('bonus-freespin')]
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('5.1 Bonus removal')]
    #[DisplayName('Notifybonusevent | Bonus removal')]
    #[Description('Testing bonus removal notification 1')]
    #[Test]
    public function notify_bonus_removal_1(): void
    {
        $username          = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $remoteBonusCode   = getenv('TEST_REMOTE_BONUS_CODE_PRIMARY') ?: '123123';
        $bonusInstanceCode = getenv('TEST_BONUS_INSTANCE_CODE_PRIMARY') ?: '123123';
        $bonusTemplateId   = getenv('TEST_BONUS_TEMPLATE_PRIMARY') ?: '12345';
        $bonusBalanceChange = getenv('TEST_BONUS_BALANCE_CHANGE') ?: '4';

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "remoteBonusCode" => $remoteBonusCode,
            "bonusInstanceCode" => $bonusInstanceCode,
            "resultingStatus" => "REMOVED",
            "date" => $date,
            "bonusBalanceChange" => $bonusBalanceChange,
            "bonusTemplateId" => $bonusTemplateId
        ];

        $endpoint = Endpoint::playtech('notifybonusevent');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Notifybonusevent (removal 1) request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('resultingStatus', 'REMOVED');

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

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('bonus')]
    #[Group('bonus-freespin')]
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('5.1 Bonus removal')]
    #[DisplayName('Notifybonusevent | Bonus removal')]
    #[Description('Testing bonus removal notification 2')]
    #[Test]
    public function notify_bonus_removal_2(): void
    {
        $username          = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $remoteBonusCode   = getenv('TEST_REMOTE_BONUS_CODE_SECONDARY') ?: '123124';
        $bonusInstanceCode = getenv('TEST_BONUS_INSTANCE_CODE_SECONDARY') ?: '123124';
        $bonusTemplateId   = getenv('TEST_BONUS_TEMPLATE_SECONDARY') ?: '12346';
        $bonusBalanceChange = getenv('TEST_BONUS_BALANCE_CHANGE') ?: '4';

        $date = $this->generateDate();

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "remoteBonusCode" => $remoteBonusCode,
            "bonusInstanceCode" => $bonusInstanceCode,
            "resultingStatus" => "REMOVED",
            "date" => $date,
            "bonusBalanceChange" => $bonusBalanceChange,
            "bonusTemplateId" => $bonusTemplateId
        ];

        $endpoint = Endpoint::playtech('notifybonusevent');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Notifybonusevent (removal 2) request to endpoint')]
            function (StepContextInterface $step) use ($payload, $endpoint) {
                $step->parameter('method', 'POST');
                $step->parameter('endpoint', $endpoint);
                $step->parameter('resultingStatus', 'REMOVED');

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

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }
}
