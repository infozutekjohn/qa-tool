<?php

namespace Tests\Feature;

use DateTime;
use Tests\TestCase;                           // Laravel base TestCase
use GuzzleHttp\Client;
use Tests\Support\AllureHttpHelpers;
use Tests\Support\ApiResponseValidator;
// 01. Login
use Tests\Traits\S11LoginScenario;

// 02. Casino Tests (casino flows)
use Tests\Traits\S21CasinoScenario;
use Tests\Traits\S22CasinoScenario;
use Tests\Traits\S23CasinoScenario;
use Tests\Traits\S24CasinoScenario;
use Tests\Traits\S25CasinoScenario;
use Tests\Traits\S26CasinoScenario;
use Tests\Traits\S27JackpotWinFeatureScenario;
use Tests\Traits\S28RefundScenario;
use Tests\Traits\S29ForwardCompatibilityScenario;

// 03. Live Casino Tests (live flows)
use Tests\Traits\S31LiveCasinoRegularScenario;
use Tests\Traits\S32LiveCasinoJackpotScenario;
use Tests\Traits\S33LiveCasinoRetriesScenario;
use Tests\Traits\S34LiveCasinoMultiseatScenario;
use Tests\Traits\S35LiveCasinoBonusScenario;
use Tests\Traits\S36LiveCasinoRefundScenario;
use Tests\Traits\S37LiveCasinoPartialRefundScenario;
use Tests\Traits\S38LiveCasinoFullRefundScenario;
use Tests\Traits\S39LiveCasinoTipScenario;
use Tests\Traits\S40LiveCasinoForwardCompatibilityScenario;

// 04. Bonus Flows
use Tests\Traits\S41FreespinHeldScenario;
use Tests\Traits\S42FreespinSpendScenario;
use Tests\Traits\S43GoldenChipsScenario;
use Tests\Traits\S44PromotionalsScenario;
use Tests\Traits\S45NotificationsScenario;

// 05. Error Handling
use Tests\Traits\S51ErrorHandlingScenario;

// 06. Gameslink Features Tests
use Tests\Traits\S60FeatureTestsScenario;

// 07. Logout
use Tests\Traits\S61LogoutScenario;

class ApiTest extends TestCase
{
    use AllureHttpHelpers;

    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

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

    // 01. Login
    use S11LoginScenario;

    // 02. Casino Tests (casino flows)
    use S21CasinoScenario;
    use S22CasinoScenario;
    use S23CasinoScenario;
    use S24CasinoScenario;
    use S25CasinoScenario;
    use S26CasinoScenario;
    use S27JackpotWinFeatureScenario;
    use S28RefundScenario;
    use S29ForwardCompatibilityScenario;

    // 03. Live Casino Tests (live flows)
    use S31LiveCasinoRegularScenario;
    use S32LiveCasinoJackpotScenario;
    use S33LiveCasinoRetriesScenario;
    use S34LiveCasinoMultiseatScenario;
    use S35LiveCasinoBonusScenario;
    use S36LiveCasinoRefundScenario;
    use S37LiveCasinoPartialRefundScenario;
    use S38LiveCasinoFullRefundScenario;
    use S39LiveCasinoTipScenario;
    use S40LiveCasinoForwardCompatibilityScenario;

    // 04. Bonus Flows
    use S41FreespinHeldScenario;
    use S42FreespinSpendScenario;
    use S43GoldenChipsScenario;
    use S44PromotionalsScenario;
    use S45NotificationsScenario;

    // 05. Error Handling
    use S51ErrorHandlingScenario;

    // 06. Gameslink Features Tests
    use S60FeatureTestsScenario;

    // 07. Logout
    use S61LogoutScenario;
}
