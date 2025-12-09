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

trait S35LiveCasinoBonusScenario
{
    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.5 In-game bonus round scenario')]
    #[DisplayName('Bet | Live Casino | In-game bonus round scenario')]
    #[Description('Testing live casino in-game bonus bet')]
    #[Test]
    public function live_bet_in_game_bonus(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.5 In-game bonus round scenario')]
    #[DisplayName('Gameroundresult (no win) | Live Casino | In-game bonus round scenario')]
    #[Description('Testing live casino in-game bonus no win result')]
    #[Test]
    public function live_result_in_game_bonus_no_win(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.5 In-game bonus round scenario')]
    #[DisplayName('Gameroundresult (win) | Live Casino | In-game bonus round scenario')]
    #[Description('Testing live casino in-game bonus win result 1')]
    #[Test]
    public function live_result_in_game_bonus_win_1(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.5 In-game bonus round scenario')]
    #[DisplayName('Gameroundresult (win) | Live Casino | In-game bonus round scenario')]
    #[Description('Testing live casino in-game bonus win result 2')]
    #[Test]
    public function live_result_in_game_bonus_win_2(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.5 In-game bonus round scenario')]
    #[DisplayName('Gameroundresult (win) | Live Casino | In-game bonus round scenario')]
    #[Description('Testing live casino in-game bonus win result 3')]
    #[Test]
    public function live_result_in_game_bonus_win_3(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.5 In-game bonus round scenario')]
    #[DisplayName('Gameroundresult | Live Casino | In-game bonus round scenario')]
    #[Description('Testing live casino in-game bonus final result')]
    #[Test]
    public function live_result_in_game_bonus_final(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }
}
