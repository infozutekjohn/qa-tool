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

trait S43GoldenChipsScenario
{
    // 3.1 Golden Chips bonus round scenario (spend)
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.1 Golden Chips bonus round scenario (spend)')]
    #[DisplayName('GC Bet | Golden Chips bonus round scenario (spend)')]
    #[Description('Testing golden chips bet spend 1')]
    #[Test]
    public function gc_bet_spend_1(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.1 Golden Chips bonus round scenario (spend)')]
    #[DisplayName('GC Bet | Golden Chips bonus round scenario (spend)')]
    #[Description('Testing golden chips bet spend 2')]
    #[Test]
    public function gc_bet_spend_2(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.1 Golden Chips bonus round scenario (spend)')]
    #[DisplayName('GC Gameroundresult (no win) | Golden Chips bonus round scenario (spend)')]
    #[Description('Testing golden chips result no win spend')]
    #[Test]
    public function gc_result_no_win_spend(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.1 Golden Chips bonus round scenario (spend)')]
    #[DisplayName('GC Gameroundresult (win) | Golden Chips bonus round scenario (spend)')]
    #[Description('Testing golden chips result win spend')]
    #[Test]
    public function gc_result_win_spend(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    // 3.2 Golden Chips bonus dealer push scenario (spend)
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.2 Golden Chips bonus dealer push scenario (spend)')]
    #[DisplayName('GC Bet | Golden Chips bonus dealer push scenario (spend)')]
    #[Description('Testing golden chips dealer push bet')]
    #[Test]
    public function gc_bet_dealer_push(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.2 Golden Chips bonus dealer push scenario (spend)')]
    #[DisplayName('GC Gameroundresult (push) | Golden Chips bonus dealer push scenario (spend)')]
    #[Description('Testing golden chips dealer push result')]
    #[Test]
    public function gc_result_dealer_push(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    // 3.3 Golden Chips multiple bonus round scenario (spend)
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.3 Golden Chips multiple bonus round scenario (spend)')]
    #[DisplayName('GC Bet | Golden Chips multiple bonus round scenario (spend)')]
    #[Description('Testing golden chips multiple bet 1')]
    #[Test]
    public function gc_multiple_bet_1(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.3 Golden Chips multiple bonus round scenario (spend)')]
    #[DisplayName('GC Bet | Golden Chips multiple bonus round scenario (spend)')]
    #[Description('Testing golden chips multiple bet 2')]
    #[Test]
    public function gc_multiple_bet_2(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.3 Golden Chips multiple bonus round scenario (spend)')]
    #[DisplayName('GC Gameroundresult (no win) | Golden Chips multiple bonus round scenario (spend)')]
    #[Description('Testing golden chips multiple result no win')]
    #[Test]
    public function gc_multiple_result_no_win(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.3 Golden Chips multiple bonus round scenario (spend)')]
    #[DisplayName('GC Gameroundresult (win) | Golden Chips multiple bonus round scenario (spend)')]
    #[Description('Testing golden chips multiple result win')]
    #[Test]
    public function gc_multiple_result_win(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    // 3.4 Golden Chips bonus round + real money combo scenario (spend)
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.4 Golden Chips bonus round + real money combo scenario (spend)')]
    #[DisplayName('GC Bet | Golden Chips round + real money combo scenario (spend)')]
    #[Description('Testing golden chips real money combo bet')]
    #[Test]
    public function gc_real_money_combo_bet(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.4 Golden Chips bonus round + real money combo scenario (spend)')]
    #[DisplayName('GC Gameroundresult (win) | Golden Chips round + real money combo scenario (spend)')]
    #[Description('Testing golden chips real money combo result win')]
    #[Test]
    public function gc_real_money_combo_result_win(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    // 3.5 Golden Chips multiple bonus round + real money combo scenario (spend)
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.5 Golden Chips multiple bonus round + real money combo scenario (spend)')]
    #[DisplayName('GC Bet | Multiple GC + real money combo scenario (spend)')]
    #[Description('Testing golden chips multiple real money combo bet')]
    #[Test]
    public function gc_multiple_real_money_bet(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.5 Golden Chips multiple bonus round + real money combo scenario (spend)')]
    #[DisplayName('GC Gameroundresult (win) | Multiple GC + real money combo scenario (spend)')]
    #[Description('Testing golden chips multiple real money combo result')]
    #[Test]
    public function gc_multiple_real_money_result(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    // 3.6 Golden Chips bonus refund scenario (spend)
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.6 Golden Chips bonus refund scenario (spend)')]
    #[DisplayName('GC Bet | Golden Chips bonus refund scenario (spend)')]
    #[Description('Testing golden chips refund bet')]
    #[Test]
    public function gc_refund_bet(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.6 Golden Chips bonus refund scenario (spend)')]
    #[DisplayName('GC Gameroundresult (gameRoundClose) | Casino | Golden Chips bonus refund scenario (spend)')]
    #[Description('Testing golden chips refund game round close')]
    #[Test]
    public function gc_refund_game_round_close(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('3.6 Golden Chips bonus refund scenario (spend)')]
    #[DisplayName('GC Gameroundresult (refund) | Golden Chips bonus refund scenario (spend)')]
    #[Description('Testing golden chips refund result')]
    #[Test]
    public function gc_refund_result(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }
}
