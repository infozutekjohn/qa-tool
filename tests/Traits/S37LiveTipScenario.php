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

trait S37LiveTipScenario
{
    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.9 Live tip scenario')]
    #[DisplayName('Livetip | Live tip scenario')]
    #[Description('Testing live tip scenario')]
    #[Test]
    public function live_tip(): void
    {
        // TODO: Implement - POST /to-operator/playtech/livetip
        $this->markTestIncomplete('Awaiting request/response implementation');
    }
}
