<?php

namespace Squadron\Base\Tests\Unit;

use Illuminate\Http\Request;
use Squadron\Base\Tests\Support\Controller;
use Squadron\Base\Tests\TestCase;

class BaseControllerTest extends TestCase
{
    public function testControllerTraitInitializers(): void
    {
        $controller = new Controller();

        $this->assertTrue($controller->controllerTraitOneInitDone, 'ControllerTraitOne was initialized');

        $this->assertTrue($controller->controllerTraitTwoInitDone, 'ControllerTraitTwo was initialized');
        $this->assertInstanceOf(Request::class, $controller->controllerTraitTwoRequest, 'ControllerTraitTwo injected request dependency');
    }
}
