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

trait S33LiveCasinoRetriesScenario
{
    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.3 Regular gameround scenario with retries')]
    #[DisplayName('Bet (retry) | Live Casino | Regular gameround scenario with retries')]
    #[Description('Testing live casino bet retry')]
    #[Test]
    public function live_bet_retry(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.3 Regular gameround scenario with retries')]
    #[DisplayName('Bet | Live Casino | Regular gameround scenario with retries')]
    #[Description('Testing live casino bet with retries')]
    #[Test]
    public function live_bet_with_retries(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.3 Regular gameround scenario with retries')]
    #[DisplayName('Gameroundresult (win retry) | Live Casino | Regular gameround scenario with retries')]
    #[Description('Testing live casino result win retry')]
    #[Test]
    public function live_result_win_retry(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.3 Regular gameround scenario with retries')]
    #[DisplayName('Gameroundresult (win) | Live Casino | Regular gameround scenario with retries')]
    #[Description('Testing live casino result win with retries')]
    #[Test]
    public function live_result_win_with_retries(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }
}
