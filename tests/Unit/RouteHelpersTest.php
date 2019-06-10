<?php

namespace Squadron\Base\Tests\Unit;

use Squadron\Base\Exceptions\SquadronHelperException;
use Squadron\Base\Helpers\Route;
use Squadron\Base\Tests\TestCase;

class RouteHelpersTest extends TestCase
{
    public function badRouteActions(): array
    {
        return [
            ['\Squadron\Base\Http\Controllers\Api\PingController'],
            ['\Squadron\Base\Http\Controllers\Api\PingController@ping@pingItAgain'],
        ];
    }

    public function testValidRouteActions(): void
    {
        $this->assertTrue(
            Route::actionExists('\Squadron\Base\Http\Controllers\Api\PingController@ping'),
            'Check that ping action exist'
        );

        $this->assertFalse(
            Route::actionExists('\Squadron\Base\Http\Controllers\Api\FakeController@makeThemHappy'),
            'Check that some fake action missing'
        );

        $this->assertFalse(
            Route::actionExists('Api\FakeController@makeThemHappy'),
            'Check that some fake action in default namespace missing'
        );
    }

    /**
     * @dataProvider badRouteActions
     * @param string $badRouteAction
     */
    public function testBadRouteActions(string $badRouteAction): void
    {
        $this->expectException(SquadronHelperException::class);
        Route::actionExists($badRouteAction);
    }
}
