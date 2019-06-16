<?php

namespace Squadron\Base\Tests\Support;

use Squadron\Base\Http\Controllers\BaseController;

class Controller extends BaseController
{
    use ControllerTraitOne, ControllerTraitTwo;
}
