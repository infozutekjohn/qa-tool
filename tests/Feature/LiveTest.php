<?php

namespace Tests\Feature;

use Tests\Traits\S301LiveCasinoRegularScenario;
use Tests\Traits\S302LiveCasinoJackpotScenario;
use Tests\Traits\S303LiveCasinoRetriesScenario;
use Tests\Traits\S304LiveCasinoMultiseatScenario;
use Tests\Traits\S305LiveCasinoBonusScenario;
use Tests\Traits\S306LiveCasinoRefundScenario;
use Tests\Traits\S307LiveCasinoPartialRefundScenario;
use Tests\Traits\S308LiveCasinoFullRefundScenario;
use Tests\Traits\S309LiveCasinoTipScenario;
use Tests\Traits\S310LiveCasinoForwardCompatibilityScenario;

class LiveTest extends BaseApiTest
{
    use S301LiveCasinoRegularScenario;
    use S302LiveCasinoJackpotScenario;
    use S303LiveCasinoRetriesScenario;
    use S304LiveCasinoMultiseatScenario;
    use S305LiveCasinoBonusScenario;
    use S306LiveCasinoRefundScenario;
    use S307LiveCasinoPartialRefundScenario;
    use S308LiveCasinoFullRefundScenario;
    // use S309LiveCasinoTipScenario;
    use S310LiveCasinoForwardCompatibilityScenario;
}
