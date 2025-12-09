<?php

namespace Tests\Traits;

use GuzzleHttp\Psr7\Message;
use PHPUnit\Framework\AssertionFailedError;
use Qameta\Allure\Allure;
use Qameta\Allure\StepContextInterface;
use Qameta\Allure\Attribute\ParentSuite;
use Qameta\Allure\Attribute\Suite;
use Qameta\Allure\Attribute\DisplayName;
use Qameta\Allure\Attribute\Description;
use PHPUnit\Framework\Attributes\Test;
use Tests\Config\Endpoint;

trait S11LoginScenario
{
    #[ParentSuite('01. Login')]
    #[Suite('1.1 Login Scenario')]
    #[Displayname('Authenticate')]
    #[Description('Testing wallet authenticate response')]
    #[Test]
    public function Authenticate(): void
    {
        $username = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token    = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token
        ];

        // $endpoint = '/to-operator/playtech/authenticate';
        $endpoint = Endpoint::playtech('authenticate');

        $checks = [];

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Authenticate request to endpoint')]
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

        // ⬇️ this whole block is now one helper call
        $this->attachHttpRequestAndResponse($fullUrl, $payload, $response, $body);

        // status check
        $this->stepAssertStatus($response, 200, $checks);

        // ⬇️ reused no-error step
        $this->stepAssertNoErrorField($data);

        $this->stepAssertRequestIdMatches($payload, $data);

        Allure::runStep(
            #[DisplayName('Validate response JSON structure')]
            function (StepContextInterface $step) use ($data, &$checks) {
                $this->assertIsArray($data);
                $checks[] = "✔ Response is JSON array/object";

                // These keys are from your original test; adjust if your real API differs
                $this->assertArrayHasKey('countryCode', $data);
                $checks[] = "✔ 'countryCode' key exists";

                $this->assertArrayHasKey('currencyCode', $data);
                $checks[] = "✔ 'currencyCode' key exists";

                $this->assertArrayHasKey('username', $data);
                $checks[] = "✔ 'username' key exists";

                $this->assertArrayHasKey('requestId', $data);
                $checks[] = "✔ 'requestId' key exists";

                // $this->assertIsInt($data['id']);
                // $checks[] = "✔ 'id' is integer";

                // $this->assertNotEmpty($data['title']);
                // $checks[] = "✔ 'title' is not empty";

                $step->parameter('validatedKeys', 'countryCode,currencyCode,username,requestId');
            }
        );

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[ParentSuite('01. Login')]
    #[Suite('1.1 Login Scenario')]
    #[Displayname('Getbalance')]
    #[Description('Testing wallet balance response')]
    #[Test]
    public function Getbalance(): void
    {
        $username = getenv('TEST_USERNAME') ?: 'fixed_user_fallback';
        $token    = getenv('TEST_TOKEN') ?: 'fixed_token_fallback';

        $payload = [
            "requestId" => uniqid('test_'),
            "username" => $username,
            "externalToken" => $token
        ];

        $endpoint = Endpoint::playtech('getbalance');

        $checks = [];

        $fullUrl = (string)$this->client->getConfig('base_uri') . ltrim($endpoint, '/');

        [$response, $body, $data] = Allure::runStep(
            #[DisplayName('Send Authenticate request to endpoint')]
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

        $this->attachHttpRequestAndResponse($fullUrl, $payload, $response, $body);

        // status check
        $this->stepAssertStatus($response, 200, $checks);

        // ⬇️ reused no-error step
        $this->stepAssertNoErrorField($data);

        $this->stepAssertRequestIdMatches($payload, $data);

        Allure::runStep(
            #[DisplayName('Validate response JSON structure')]
            function (StepContextInterface $step) use ($data, &$checks) {
                /** @var self $this */

                $this->assertIsArray($data);
                $checks[] = "✔ Response is JSON array/object";

                // These keys are from your original test; adjust if your real API differs
                $this->assertArrayHasKey('balance', $data);
                $checks[] = "✔ 'countryCode' key exists";

                $this->assertArrayHasKey('requestId', $data);
                $checks[] = "✔ 'requestId' key exists";

                // $this->assertIsInt($data['id']);
                // $checks[] = "✔ 'id' is integer";

                // $this->assertNotEmpty($data['title']);
                // $checks[] = "✔ 'title' is not empty";

                $step->parameter('validatedKeys', 'balance,requestId');
            }
        );

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }
}
