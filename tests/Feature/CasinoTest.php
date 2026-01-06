<?php

namespace Tests\Feature;

use Tests\Traits\S21CasinoScenario;
use Tests\Traits\S22CasinoScenario;
use Tests\Traits\S23CasinoScenario;
use Tests\Traits\S24CasinoScenario;
use Tests\Traits\S25CasinoScenario;
use Tests\Traits\S26CasinoScenario;
use Tests\Traits\S27JackpotWinFeatureScenario;
use Tests\Traits\S28RefundScenario;
use Tests\Traits\S29ForwardCompatibilityScenario;

class CasinoTest extends BaseApiTest
{
    use S21CasinoScenario;
    use S22CasinoScenario;
    use S23CasinoScenario;
    use S24CasinoScenario;
    use S25CasinoScenario;
    use S26CasinoScenario;
    use S27JackpotWinFeatureScenario;
    use S28RefundScenario;
    use S29ForwardCompatibilityScenario;
}
