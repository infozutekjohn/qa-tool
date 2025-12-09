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

trait S36LiveCasinoRefundScenario
{
    // 2.6 Regular refund scenario with relation
    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.6 Regular refund scenario with relation')]
    #[DisplayName('Bet (for refund) | Live Casino | Regular gameround scenario with relation')]
    #[Description('Testing live casino bet for refund with relation')]
    #[Test]
    public function live_bet_for_refund_with_relation(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.6 Regular refund scenario with relation')]
    #[DisplayName('Gameroundresult (refund) | Live Casino | Regular gameround scenario with relation')]
    #[Description('Testing live casino refund result with relation')]
    #[Test]
    public function live_result_refund_with_relation(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    // 2.7 Partial refund scenario with relation
    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.7 Partial refund scenario with relation')]
    #[DisplayName('Bet 1 | Live Casino | Partial refund scenario with relation')]
    #[Description('Testing live casino partial refund bet 1')]
    #[Test]
    public function live_partial_refund_bet_1(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.7 Partial refund scenario with relation')]
    #[DisplayName('Bet 2 | Live Casino | Partial refund scenario with relation')]
    #[Description('Testing live casino partial refund bet 2')]
    #[Test]
    public function live_partial_refund_bet_2(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.7 Partial refund scenario with relation')]
    #[DisplayName('Bet 3 (split or double) | Live Casino | Partial refund scenario with relation')]
    #[Description('Testing live casino partial refund bet 3 split or double')]
    #[Test]
    public function live_partial_refund_bet_3_split_double(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.7 Partial refund scenario with relation')]
    #[DisplayName('Gameroundresult (refund) | Live Casino | Partial refund scenario with relation')]
    #[Description('Testing live casino partial refund result')]
    #[Test]
    public function live_partial_refund_result(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.7 Partial refund scenario with relation')]
    #[DisplayName('Gameroundresult (win) | Live Casino | Partial scenario with relation')]
    #[Description('Testing live casino partial refund win result')]
    #[Test]
    public function live_partial_refund_win_result(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    // 2.8 Full refund scenario with relation
    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.8 Full refund scenario with relation')]
    #[DisplayName('Bet 1 | Live Casino | Full refund scenario with relation')]
    #[Description('Testing live casino full refund bet 1')]
    #[Test]
    public function live_full_refund_bet_1(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.8 Full refund scenario with relation')]
    #[DisplayName('Bet 2 | Live Casino | Full refund scenario with relation')]
    #[Description('Testing live casino full refund bet 2')]
    #[Test]
    public function live_full_refund_bet_2(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.8 Full refund scenario with relation')]
    #[DisplayName('Bet 3 | Live Casino | Full refund scenario with relation')]
    #[Description('Testing live casino full refund bet 3')]
    #[Test]
    public function live_full_refund_bet_3(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.8 Full refund scenario with relation')]
    #[DisplayName('Gameroundresult (refund) | Live Casino | Full refund scenario with relation')]
    #[Description('Testing live casino full refund result 1')]
    #[Test]
    public function live_full_refund_result_1(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.8 Full refund scenario with relation')]
    #[DisplayName('Gameroundresult (refund) | Live Casino | Full refund scenario with relation')]
    #[Description('Testing live casino full refund result 2')]
    #[Test]
    public function live_full_refund_result_2(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('03. Gameslink Casino Tests (live flows)')]
    #[Suite('2.8 Full refund scenario with relation')]
    #[DisplayName('Gameroundresult (refund) | Live Casino | Full refund scenario with relation')]
    #[Description('Testing live casino full refund result 3')]
    #[Test]
    public function live_full_refund_result_3(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }
}
