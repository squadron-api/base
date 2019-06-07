<?php

namespace Squadron\Base\Tests\Unit;

use Illuminate\Foundation\Testing\TestResponse;
use Squadron\Base\Helpers\ApiResponse;
use Squadron\Base\Tests\TestCase;

class ResponseHelpersTest extends TestCase
{
    private $message = 'Test API response message';

    public function testSuccess(): void
    {
        // default
        $response = TestResponse::fromBaseResponse(ApiResponse::success($this->message));
        $response->assertStatus(200)
            ->assertJson(['success' => true, 'message' => $this->message]);
    }

    public function testError(): void
    {
        // default error code
        $response = TestResponse::fromBaseResponse(ApiResponse::error($this->message));
        $response->assertStatus(400)
            ->assertJson(['success' => false, 'message' => $this->message]);

        // custom error code
        $response = TestResponse::fromBaseResponse(ApiResponse::error($this->message, 422));
        $response->assertStatus(422)
            ->assertJson(['success' => false, 'message' => $this->message]);
    }

    public function testErrorAccess(): void
    {
        $response = TestResponse::fromBaseResponse(ApiResponse::errorAccess($this->message));
        $response->assertStatus(401)
            ->assertJson(['success' => false, 'message' => $this->message]);
    }
}
