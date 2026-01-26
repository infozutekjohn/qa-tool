<?php

namespace Tests\Traits;

use GuzzleHttp\Psr7\Message;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\AssertionFailedError;
use PHPUnit\Framework\Attributes\Group;
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
    #[Group('login')]
    #[ParentSuite('01. Gameslink Login')]
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
        
        // $this->attachHttpRequestAndResponse($fullUrl, $payload, $response, $body);

        // $this->stepAssertStatus($response, 200, $checks);

        // $this->stepAssertNoErrorField($data);

        // $this->stepAssertRequestIdMatches($payload, $data);

        // $this->stepAssertTransactionResponseSchema( $data, $checks, ['type'=>'auth']);

        $this->validateApiResponse([
            "response" => $response,
            "data" => $data,
            "payload" => $payload,
            "checks" => $checks,
            "fullUrl" => $fullUrl,
            "body" => $body,
            "endpointType" => 'auth',
        ]);

        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }

    #[Group('login')]
    #[ParentSuite('01. Gameslink Login')]
    #[Suite('1.1 Login Scenario')]
    #[Displayname('GetBalance')]
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

        // To register a balance for the tester
        // $this->updateTrackedBalance($data);

        // $this->attachHttpRequestAndResponse($fullUrl, $payload, $response, $body);

        // $this->stepAssertStatus($response, 200, $checks);

        // $this->stepAssertNoErrorField($data);

        // $this->stepAssertRequestIdMatches($payload, $data);

        // $this->stepAssertTimestampFormat($data, $checks, ['includeBalance' => true, 'ignoreDefault' => true]);

        // $this->stepAssertTimestampGMT($data, $checks, ['includeBalance' => true, 'ignoreDefault' => true]);

        // $this->stepAssertTransactionResponseSchema($data, $checks, ['type' => 'getbalance']);

        // $this->stepAssertBalanceError($data, $checks);

        $this->validateApiResponse([
            "response" => $response,
            "data" => $data,
            "payload" => $payload,
            "checks" => $checks,
            "fullUrl" => $fullUrl,
            "body" => $body,
            "endpointType" => 'getbalance',
            "includeBalance"  => true,
            "ignoreDefault" => true
        ]);


        Allure::attachment(
            'Validation Checks',
            implode(PHP_EOL, $checks),
            'text/plain'
        );
    }
}
