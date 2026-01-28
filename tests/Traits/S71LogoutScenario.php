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

trait S71LogoutScenario
{
    #[Group('logout')]
    #[ParentSuite('07. Gameslink Logout')]
    // #[Suite('7.0 Logout')]
    // #[DisplayName('Logout | User session logout')]
    #[DisplayName('Logout')]
    #[Description('Testing user logout functionality')]
    #[Test]
    public function logout(): void
    {
        $username = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token    = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token
        ];

        $endpoint = Endpoint::playtech('logout');

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Logout request to endpoint')]
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

        $this->validateApiResponse([
            "response"      => $response,
            "data"          => $data,
            "payload"       => $payload,
            "checks"        => &$checks,
            "fullUrl"       => $fullUrl,
            "body"          => $body,
            "endpointType"  => 'event',
            "errorScenario" => false,
            "balanceAction" => null,
        ]);

        Allure::runStep(
            #[DisplayName('Verify logout response structure')]
            function (StepContextInterface $step) use ($data, &$checks) {
                $this->assertIsArray($data, 'Response should be an array');
                $checks[] = '✔ Logout response received successfully';
            }
        );

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }
}
