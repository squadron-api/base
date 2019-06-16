<?php

namespace Squadron\Base\Tests\Feature;

use Squadron\Base\Tests\TestCase;

class PingTest extends TestCase
{
    public function testName(): void
    {
        $this->get('/api/ping')->assertExactJson(['v' => '0.0.0']);
    }
}
