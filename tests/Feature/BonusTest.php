<?php

namespace Tests\Feature;

use Tests\Traits\S411FreespinHeldScenario;
use Tests\Traits\S412FreespinSpendScenario;
use Tests\Traits\S421GoldenChipsScenario;
use Tests\Traits\S422GoldenChipsScenario;
use Tests\Traits\S423GoldenChipsScenario;
use Tests\Traits\S424GoldenChipsScenario;
use Tests\Traits\S425GoldenChipsScenario;
use Tests\Traits\S426GoldenChipsScenario;
use Tests\Traits\S431PromotionalsScenario;
use Tests\Traits\S432PromotionalsScenario;
use Tests\Traits\S441NotificationsScenario;

class BonusTest extends BaseApiTest
{
    use S411FreespinHeldScenario;
    use S412FreespinSpendScenario;
    use S421GoldenChipsScenario;
    use S422GoldenChipsScenario;
    use S423GoldenChipsScenario;
    use S424GoldenChipsScenario;
    use S425GoldenChipsScenario;
    use S426GoldenChipsScenario;
    use S431PromotionalsScenario;
    use S432PromotionalsScenario;
    use S441NotificationsScenario;
}