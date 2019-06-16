<?php

namespace Squadron\Base\Tests\Support;

trait ControllerTraitOne
{
    public $controllerTraitOneInitDone;

    public function initializeControllerTraitOne(): void
    {
        $this->controllerTraitOneInitDone = true;
    }
}
