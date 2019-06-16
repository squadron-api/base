<?php

namespace Squadron\Base\Tests\Support;

use Illuminate\Http\Request;

trait ControllerTraitTwo
{
    public $controllerTraitTwoInitDone;
    public $controllerTraitTwoRequest;

    public function initializeControllerTraitTwo(Request $request): void
    {
        $this->controllerTraitTwoInitDone = true;
        $this->controllerTraitTwoRequest = $request;
    }
}
