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

trait S32LiveCasinoJackpotScenario
{
    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.2 Regular gameround scenarios (with jackpot)')]
    #[DisplayName('Bet | Casino | Regular gameround scenario (no win with no jackpot)')]
    #[Description('Testing live casino bet with no win and no jackpot')]
    #[Test]
    public function live_bet_no_win_no_jackpot(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.2 Regular gameround scenarios (with jackpot)')]
    #[DisplayName('Bet | Casino | Regular gameround scenario (win with jackpot)')]
    #[Description('Testing live casino bet with win and jackpot')]
    #[Test]
    public function live_bet_win_with_jackpot(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.2 Regular gameround scenarios (with jackpot)')]
    #[DisplayName('Bet | Casino | Regular gameround scenario (win with no jackpot)')]
    #[Description('Testing live casino bet with win and no jackpot')]
    #[Test]
    public function live_bet_win_no_jackpot(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.2 Regular gameround scenarios (with jackpot)')]
    #[DisplayName('Gameroundresult | Casino | Regular gameround scenario (no win with no jackpot)')]
    #[Description('Testing live casino result no win no jackpot')]
    #[Test]
    public function live_result_no_win_no_jackpot(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.2 Regular gameround scenarios (with jackpot)')]
    #[DisplayName('Gameroundresult | Casino | Regular gameround scenario (win with jackpot)')]
    #[Description('Testing live casino result with win and jackpot')]
    #[Test]
    public function live_result_win_with_jackpot(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.2 Regular gameround scenarios (with jackpot)')]
    #[DisplayName('Gameroundresult | Casino | Regular gameround scenario (win with no jackpot)')]
    #[Description('Testing live casino result with win and no jackpot')]
    #[Test]
    public function live_result_win_no_jackpot(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }
}
