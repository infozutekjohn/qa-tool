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

trait S45NotificationsScenario
{
    // 5.1 Bonus removal
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('5.1 Bonus removal')]
    #[DisplayName('Notifybonusevent | Bonus removal')]
    #[Description('Testing bonus removal notification')]
    #[Test]
    public function notify_bonus_removal(): void
    {
        // TODO: Implement - POST /to-operator/playtech/notifybonusevent
        $this->markTestIncomplete('Awaiting request/response implementation');
    }
}
