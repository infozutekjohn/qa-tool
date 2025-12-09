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

trait S34LiveCasinoMultiseatScenario
{
    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.4 Multiseat bets with aggregated win')]
    #[DisplayName('Bet 1 | Live Casino | Multiseat bets with wins')]
    #[Description('Testing live casino multiseat bet 1')]
    #[Test]
    public function live_multiseat_bet_1(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.4 Multiseat bets with aggregated win')]
    #[DisplayName('Bet 2 | Live Casino | Multiseat bets with wins')]
    #[Description('Testing live casino multiseat bet 2')]
    #[Test]
    public function live_multiseat_bet_2(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.4 Multiseat bets with aggregated win')]
    #[DisplayName('Bet 3 | Live Casino | Multiseat bets with wins')]
    #[Description('Testing live casino multiseat bet 3')]
    #[Test]
    public function live_multiseat_bet_3(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.4 Multiseat bets with aggregated win')]
    #[DisplayName('Gameroundresult (aggregated win) | Live Casino | Multiseat bets with aggregated win')]
    #[Description('Testing live casino multiseat aggregated win result')]
    #[Test]
    public function live_multiseat_result_aggregated_win(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }
}
