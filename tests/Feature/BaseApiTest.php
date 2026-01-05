<?php

namespace Tests\Feature;

use DateTime;
use Tests\TestCase;
use GuzzleHttp\Client;
use Tests\Support\AllureHttpHelpers;
use Tests\Support\ApiResponseValidator;
// use Qameta\Allure\Allure;

abstract class BaseApiTest  extends TestCase
{
    use AllureHttpHelpers;
    // use TestCaseActivate;

    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        // $allureDir = getenv('ALLURE_RESULTS_DIRECTORY');
        // if ($allureDir) {
        //     Allure::setOutputDirectory($allureDir);
        // }

        $endpoint = getenv('TEST_ENDPOINT') ?: 'https://api-uat.agmidway.net';
        $username = getenv('TEST_USERNAME');
        $token    = getenv('TEST_TOKEN');

        // DEBUG: Output environment variables to stderr (visible in PHPUnit output)
        fwrite(STDERR, "\n[DEBUG ApiTest::setUp] Environment Variables Received:\n");
        fwrite(STDERR, "  TEST_ENDPOINT: " . ($endpoint ?: 'NOT SET') . "\n");
        fwrite(STDERR, "  TEST_USERNAME: " . ($username ?: 'NOT SET') . "\n");
        fwrite(STDERR, "  TEST_TOKEN: " . ($token ? substr($token, 0, 20) . '...' : 'NOT SET') . "\n");
        fwrite(STDERR, "  TEST_CASINO_GAME_CODE: " . (getenv('TEST_CASINO_GAME_CODE') ?: 'NOT SET') . "\n");
        fwrite(STDERR, "  TEST_LIVE_GAME_CODE: " . (getenv('TEST_LIVE_GAME_CODE') ?: 'NOT SET') . "\n");
        fwrite(STDERR, "  TEST_BET_PRIMARY: " . (getenv('TEST_BET_PRIMARY') ?: 'NOT SET') . "\n");

        $this->client = new Client([
            'base_uri' => $endpoint,
            'timeout'  => 30,
            'connect_timeout' => 10,
            'verify'   => false,
            // Force IPv4 to avoid Windows DNS threading issues (cURL error 6)
            'force_ip_resolve' => 'v4',
            'curl' => [
                CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                CURLOPT_DNS_CACHE_TIMEOUT => 120,
            ],
        ]);
    }

    public function generateDate(): string
    {
        $date = new DateTime();

        $formattedDateWithMicroseconds = $date->format('Y-m-d H:i:s.u');

        $milliseconds = substr($date->format('u'), 0, 3);

        $finalFormattedDate = str_replace(
            '.' . $date->format('u'),
            '.' . $milliseconds,
            $formattedDateWithMicroseconds
        );

        return $finalFormattedDate;
    }

    // General response validation
    use ApiResponseValidator;
}
