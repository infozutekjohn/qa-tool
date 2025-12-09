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

trait S31LiveCasinoRegularScenario
{
    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.1 Regular gameround scenarios')]
    #[DisplayName('Bet | Live Casino | Regular gameround scenario 1')]
    #[Description('Testing live casino bet for regular gameround scenario 1')]
    #[Test]
    public function live_bet_regular_scenario_1(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.1 Regular gameround scenarios')]
    #[DisplayName('Bet | Live Casino | Regular gameround scenario 2')]
    #[Description('Testing live casino bet for regular gameround scenario 2')]
    #[Test]
    public function live_bet_regular_scenario_2(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.1 Regular gameround scenarios')]
    #[DisplayName('Gameroundresult (no win) | Live Casino | Regular gameround scenario')]
    #[Description('Testing live casino game round result with no win')]
    #[Test]
    public function live_result_no_win(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.1 Regular gameround scenarios')]
    #[DisplayName('Gameroundresult (win) | Live Casino | Regular gameround scenario')]
    #[Description('Testing live casino game round result with win')]
    #[Test]
    public function live_result_win(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.1 Regular gameround scenarios')]
    #[DisplayName('What Is My Purpose?')]
    #[Description('Echo test for live casino')]
    #[Test]
    public function live_what_is_my_purpose(): void
    {
        // TODO: Implement - GET https://postman-echo.com/get
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.10 Forward compatibility check')]
    #[DisplayName('Bet | Forward compatibility check')]
    #[Description('Testing live casino bet for forward compatibility')]
    #[Test]
    public function live_bet_forward_compatibility(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.10 Forward compatibility check')]
    #[DisplayName('Gameroundresult (no win) | Forward compatibility check')]
    #[Description('Testing live casino forward compatibility result')]
    #[Test]
    public function live_result_forward_compatibility(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.10 Forward compatibility check')]
    #[DisplayName('What Is My Purpose Again?')]
    #[Description('Echo test for forward compatibility')]
    #[Test]
    public function live_what_is_my_purpose_again(): void
    {
        // TODO: Implement - GET https://postman-echo.com/get
        $this->markTestIncomplete('Awaiting request/response implementation');
    }
}
