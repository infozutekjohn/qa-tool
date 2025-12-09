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

trait S42FreespinSpendScenario
{
    // 2.2 Freespin bonus round scenario without wagering (spend)
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('2.2 Freespin bonus round scenario without wagering (spend)')]
    #[DisplayName('FS Bet | Freespin bonus round scenario without wagering (spend)')]
    #[Description('Testing freespin bet without wagering spend')]
    #[Test]
    public function fs_bet_spend_1(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('2.2 Freespin bonus round scenario without wagering (spend)')]
    #[DisplayName('FS Bet | Freespin bonus round scenario without wagering (spend)')]
    #[Description('Testing freespin bet without wagering spend 2')]
    #[Test]
    public function fs_bet_spend_2(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('2.2 Freespin bonus round scenario without wagering (spend)')]
    #[DisplayName('FS Bet | Freespin bonus round scenario without wagering (spend) for jackpot')]
    #[Description('Testing freespin bet without wagering spend for jackpot')]
    #[Test]
    public function fs_bet_spend_jackpot(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('2.2 Freespin bonus round scenario without wagering (spend)')]
    #[DisplayName('FS Gameroundresult (no win) | Freespin bonus round scenario without wagering (spend)')]
    #[Description('Testing freespin result no win spend')]
    #[Test]
    public function fs_result_no_win_spend(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('2.2 Freespin bonus round scenario without wagering (spend)')]
    #[DisplayName('FS Gameroundresult (win) | Freespin bonus round scenario without wagering (spend)')]
    #[Description('Testing freespin result win spend')]
    #[Test]
    public function fs_result_win_spend(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('2.2 Freespin bonus round scenario without wagering (spend)')]
    #[DisplayName('FS Gameroundresult (win) | Freespin bonus round scenario without wagering (spend) with jackpot')]
    #[Description('Testing freespin result win spend with jackpot')]
    #[Test]
    public function fs_result_win_spend_jackpot(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }
}
