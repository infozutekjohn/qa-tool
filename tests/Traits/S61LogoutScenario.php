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

trait S61LogoutScenario
{
    #[ParentSuite('06. Gameslink Logout')]
    #[Suite('Logout')]
    #[DisplayName('Logout')]
    #[Description('Testing user logout')]
    #[Test]
    public function logout(): void
    {
        // TODO: Implement - POST /to-operator/playtech/logout
        $this->markTestIncomplete('Awaiting request/response implementation');
    }
}
