<?php

namespace Squadron\Base\Http\Controllers\Api;

use Squadron\Base\Http\Controllers\BaseController;

class PingController extends BaseController
{
    public function ping(): array
    {
        return ['v' => env('APP_VERSION', '0.0.0')];
    }
}
