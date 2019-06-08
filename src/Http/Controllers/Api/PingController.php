<?php

namespace Squadron\Base\Http\Controllers;

class PingController extends BaseController
{
    public function ping(): array
    {
        return ['v' => env('APP_VERSION')];
    }
}
