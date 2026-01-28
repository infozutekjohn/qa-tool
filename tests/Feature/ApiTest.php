<?php

namespace Tests\Feature;

use DateTime;
use Tests\TestCase;                           // Laravel base TestCase
use GuzzleHttp\Client;
use Tests\Support\AllureHttpHelpers;
use Tests\Support\ApiResponseValidator;
use Tests\Support\GroupGuardTrait;
use Tests\Support\TestCaseActivate;
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
use Tests\Traits\S301LiveCasinoRegularScenario;
use Tests\Traits\S302LiveCasinoJackpotScenario;
use Tests\Traits\S303LiveCasinoRetriesScenario;
use Tests\Traits\S304LiveCasinoMultiseatScenario;
use Tests\Traits\S305LiveCasinoBonusScenario;
use Tests\Traits\S306LiveCasinoRefundScenario;
use Tests\Traits\S307LiveCasinoPartialRefundScenario;
use Tests\Traits\S308LiveCasinoFullRefundScenario;
use Tests\Traits\S309LiveCasinoTipScenario;
use Tests\Traits\S310LiveCasinoForwardCompatibilityScenario;

// 04. Bonus Flows
use Tests\Traits\S411FreespinHeldScenario;
use Tests\Traits\S422FreespinSpendScenario;
use Tests\Traits\S421GoldenChipsScenario;
use Tests\Traits\S422GoldenChipsScenario;
use Tests\Traits\S423GoldenChipsScenario;
use Tests\Traits\S424GoldenChipsScenario;
use Tests\Traits\S425GoldenChipsScenario;
use Tests\Traits\S426GoldenChipsScenario;
use Tests\Traits\S431PromotionalsScenario;
use Tests\Traits\S432PromotionalsScenario;
use Tests\Traits\S441NotificationsScenario;

// 05. Error Handling
use Tests\Traits\S51ErrorHandlingScenario;

// 06. Gameslink Features Tests
use Tests\Traits\S61FeatureTestsScenario;
use Tests\Traits\S62FeatureTestsScenario;
use Tests\Traits\S63FeatureTestsScenario;

// 07. Logout
use Tests\Traits\S61LogoutScenario;

class ApiTest extends TestCase
{
    use AllureHttpHelpers;
    // use TestCaseActivate;

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
    use S301LiveCasinoRegularScenario;
    use S302LiveCasinoJackpotScenario;
    use S303LiveCasinoRetriesScenario;
    use S304LiveCasinoMultiseatScenario;
    use S305LiveCasinoBonusScenario;
    use S306LiveCasinoRefundScenario;
    use S307LiveCasinoPartialRefundScenario;
    use S308LiveCasinoFullRefundScenario;
    use S309LiveCasinoTipScenario;
    use S310LiveCasinoForwardCompatibilityScenario;

    // 04. Bonus Flows
    use S411FreespinHeldScenario;
    use S412FreespinSpendScenario;
    use S421GoldenChipsScenario;
    use S422GoldenChipsScenario;
    use S423GoldenChipsScenario;
    use S424GoldenChipsScenario;
    use S425GoldenChipsScenario;
    use S426GoldenChipsScenario;
    use S431PromotionalsScenario;
    use S432PromotionalsScenario;
    use S441NotificationsScenario;

    // 05. Error Handling
    use S51ErrorHandlingScenario;

    // 06. Gameslink Features Tests
    use S61FeatureTestsScenario;
    use S62FeatureTestsScenario;
    use S63FeatureTestsScenario;

    // 07. Logout
    use S61LogoutScenario;
}
