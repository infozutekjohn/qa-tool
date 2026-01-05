<?php

namespace Tests\Feature;

use Tests\Traits\S41FreespinHeldScenario;
use Tests\Traits\S42FreespinSpendScenario;
use Tests\Traits\S43GoldenChipsScenario;
use Tests\Traits\S44PromotionalsScenario;
use Tests\Traits\S45NotificationsScenario;

class BonusTest extends BaseApiTest
{
    use S41FreespinHeldScenario;
    use S42FreespinSpendScenario;
    use S43GoldenChipsScenario;
    use S44PromotionalsScenario;
    use S45NotificationsScenario;
}