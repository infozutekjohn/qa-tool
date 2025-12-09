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

trait S44PromotionalsScenario
{
    // 4.1 Cash Bonus
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('4.1 Cash Bonus')]
    #[DisplayName('Transferfunds | Cash Bonus')]
    #[Description('Testing cash bonus transferfunds')]
    #[Test]
    public function cash_bonus_transferfunds(): void
    {
        // TODO: Implement - POST /to-operator/playtech/transferfunds
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    // 4.2 Golden Chips Promo (spend)
    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('4.2 Golden Chips Promo (spend)')]
    #[DisplayName('GC Bet | Golden Chips bonus round scenario (spend)')]
    #[Description('Testing golden chips promo bet 1')]
    #[Test]
    public function gc_promo_bet_1(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('4.2 Golden Chips Promo (spend)')]
    #[DisplayName('GC Bet | Golden Chips bonus round scenario (spend)')]
    #[Description('Testing golden chips promo bet 2')]
    #[Test]
    public function gc_promo_bet_2(): void
    {
        // TODO: Implement - POST /to-operator/playtech/bet
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('4.2 Golden Chips Promo (spend)')]
    #[DisplayName('GC Gameroundresult (no win) | Golden Chips bonus round scenario (spend)')]
    #[Description('Testing golden chips promo result no win')]
    #[Test]
    public function gc_promo_result_no_win(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }

    #[ParentSuite('04. Gameslink Casino Tests (bonus flows)')]
    #[Suite('4.2 Golden Chips Promo (spend)')]
    #[DisplayName('GC Gameroundresult (win) | Golden Chips bonus round scenario (spend)')]
    #[Description('Testing golden chips promo result win')]
    #[Test]
    public function gc_promo_result_win(): void
    {
        // TODO: Implement - POST /to-operator/playtech/gameroundresult
        $this->markTestIncomplete('Awaiting request/response implementation');
    }
}
