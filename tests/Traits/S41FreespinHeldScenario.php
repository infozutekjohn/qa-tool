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

trait S41FreespinHeldScenario
{
    // 2.1 Freespin bonus round scenario without wagering (held)
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('2.1 Freespin bonus round scenario without wagering (held)')]
    #[DisplayName('FS Bet | Freespin bonus round scenario without wagering (held)')]
    #[Description('Testing freespin bet without wagering held')]
    #[Test]
    public function fs_bet_held_1(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('2.1 Freespin bonus round scenario without wagering (held)')]
    #[DisplayName('FS Bet | Freespin bonus round scenario without wagering (held)')]
    #[Description('Testing freespin bet without wagering held 2')]
    #[Test]
    public function fs_bet_held_2(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('2.1 Freespin bonus round scenario without wagering (held)')]
    #[DisplayName('FS Bet | Freespin bonus round scenario without wagering (held)')]
    #[Description('Testing freespin bet without wagering held 3')]
    #[Test]
    public function fs_bet_held_3(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('2.1 Freespin bonus round scenario without wagering (held)')]
    #[DisplayName('FS Gameroundresult (no win) | Freespin bonus round scenario without wagering (held)')]
    #[Description('Testing freespin result no win held')]
    #[Test]
    public function fs_result_no_win_held(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('2.1 Freespin bonus round scenario without wagering (held)')]
    #[DisplayName('FS Gameroundresult (win) | Freespin bonus round scenario without wagering (held)')]
    #[Description('Testing freespin result win held')]
    #[Test]
    public function fs_result_win_held(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('2.1 Freespin bonus round scenario without wagering (held)')]
    #[DisplayName('FS Gameroundresult (win) | Freespin bonus round scenario without wagering (held)')]
    #[Description('Testing freespin result win held 2')]
    #[Test]
    public function fs_result_win_held_2(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('2.1 Freespin bonus round scenario without wagering (held)')]
    #[DisplayName('FS Gameroundresult (win) | Freespin bonus round scenario without wagering (held) with jackpot')]
    #[Description('Testing freespin result win held with jackpot')]
    #[Test]
    public function fs_result_win_held_jackpot(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('2.1 Freespin bonus round scenario without wagering (held)')]
    #[DisplayName('Transferfunds | Freespin bonus round scenario without wagering (held)')]
    #[Description('Testing freespin transferfunds held')]
    #[Test]
    public function fs_transferfunds_held(): void
    {
        // TODO: Implement - POST /to-operator/playtech/transferfunds
        $this->markTestIncomplete('Awaiting request/response implementation');
    }
}
