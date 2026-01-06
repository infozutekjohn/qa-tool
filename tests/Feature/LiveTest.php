<?php

namespace Tests\Feature;

use Tests\Traits\S31LiveCasinoRegularScenario;
use Tests\Traits\S32LiveCasinoJackpotScenario;
use Tests\Traits\S33LiveCasinoRetriesScenario;
use Tests\Traits\S34LiveCasinoMultiseatScenario;
use Tests\Traits\S35LiveCasinoBonusScenario;
use Tests\Traits\S36LiveCasinoRefundScenario;
use Tests\Traits\S37LiveCasinoPartialRefundScenario;
use Tests\Traits\S38LiveCasinoFullRefundScenario;
use Tests\Traits\S39LiveCasinoTipScenario;
use Tests\Traits\S40LiveCasinoForwardCompatibilityScenario;

class LiveTest extends BaseApiTest
{
    use S31LiveCasinoRegularScenario;
    use S32LiveCasinoJackpotScenario;
    use S33LiveCasinoRetriesScenario;
    use S34LiveCasinoMultiseatScenario;
    use S35LiveCasinoBonusScenario;
    use S36LiveCasinoRefundScenario;
    use S37LiveCasinoPartialRefundScenario;
    use S38LiveCasinoFullRefundScenario;
    // use S39LiveCasinoTipScenario;
    use S40LiveCasinoForwardCompatibilityScenario;
}
