<?php

namespace Tests\Feature;

use DateTime;
use Tests\TestCase;                           // Laravel base TestCase
use GuzzleHttp\Client;
use Tests\Support\AllureHttpHelpers;
use Tests\Traits\S11LoginScenario;
use Tests\Traits\S21CasinoScenario;
use Tests\Traits\S22CasinoScenario;
use Tests\Traits\S23CasinoScenario;
use Tests\Traits\S24CasinoScenario;
use Tests\Traits\S25CasinoScenario;
use Tests\Traits\S26CasinoScenario;

class ApiTest extends TestCase
{
    use AllureHttpHelpers;

    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $endpoint = getenv('TEST_ENDPOINT') ?: 'fixed_user_fallback';  //https://api-uat.agmidway.net/

        $this->client = new Client([
            'base_uri' => $endpoint,
            'timeout'  => 10,
            'verify'   => false,
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

    use S11LoginScenario;
    use S21CasinoScenario;
    use S22CasinoScenario;
    use S23CasinoScenario;
    use S24CasinoScenario;
    use S25CasinoScenario;
    use S26CasinoScenario;
}
