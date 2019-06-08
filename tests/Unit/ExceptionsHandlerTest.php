<?php

namespace Squadron\Base\Tests\Unit;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Squadron\Base\Exceptions\Handler;
use Squadron\Base\Tests\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionsHandlerTest extends TestCase
{
    /** @var Handler */
    private $handler;

    public function setUp(): void
    {
        parent::setUp();

        // handler
        $this->handler = new Handler(Container::getInstance());
    }

    public function testLocalReports(): void
    {
        // mock sentry
        $this->mock('sentry', function ($mock) {
            $mock->shouldNotReceive('captureException');
        });

        // local env - no report
        app()['env'] = 'local';
        $this->handler->report(new \Exception('Test exception', 777));
    }

    public function testProductionReports(): void
    {
        // mock sentry
        $this->mock('sentry', function ($mock) {
            $mock->shouldReceive('captureException')->once();
        });

        // production env - report
        app()['env'] = 'production';
        $this->handler->report(new \Exception('Test exception', 777));
    }

    public function testHttpExceptionsRender(): void
    {
        $this->assertExceptionRender(
            new NotFoundHttpException('Not Found'),
            404, 'Not Found'
        );
    }

    public function testValidationExceptionsRender(): void
    {
        $validator = Validator::make([
            'fieldOne' => 'Radio',
            'fieldTwo' => 'The Riff',
            'fieldThree' => 'Sanctified with Dynamite',
        ], [
            'fieldOne' => 'string',
            'fieldTwo' => 'boolean',
            'fieldThree' => 'integer',
            'fieldFour' => 'required',
        ]);

        $validator->fails();

        $this->assertExceptionRender(new ValidationException($validator), 422, 'Input data is invalid.')
             ->assertJsonMissingValidationErrors(['fieldOne'])
             ->assertJsonValidationErrors(['fieldTwo', 'fieldThree', 'fieldFour']);
    }

    public function testAccessExceptionsRender(): void
    {
        $this->assertExceptionRender(
            new AuthorizationException('No way'),
            401, 'This action is unauthorized.'
        );

        $this->assertExceptionRender(
            new AuthenticationException('No auth'),
            401, 'Unauthenticated.'
        );
    }

    public function testCustomExceptionsRender(): void
    {
        // custom exception with HTTP code in range [400, 599]
        $this->assertExceptionRender(new \Exception('Custom exception', 500), 500);

        // custom exception with custom error code (HTTP default = 400)
        $this->assertExceptionRender(new \Exception('Custom exception', 777), 400);
    }

    public function testDebugRender(): void
    {
        $exception = new \Exception('Test exception', 999);

        // debug OFF
        app('config')->set('app.debug', false);

        $this->assertExceptionRender($exception, 400)
             ->assertJsonMissing(['message' => 'Test exception']);

        // debug ON
        app('config')->set('app.debug', true);

        $this->assertExceptionRender($exception, 400)
             ->assertJsonStructure(['exception' => [
                 'class', 'message', 'file', 'line', 'trace',
             ]]);
    }

    private function assertExceptionRender(\Exception $exception, int $status, ?string $message = null): TestResponse
    {
        $response = TestResponse::fromBaseResponse(
            $this->handler->render(new Request(), $exception)
        );

        // assert code
        $response->assertStatus($status);

        // assert message
        $message = $message ?? 'Sorry, something went wrong.'; // default
        $response->assertJson([
            'message' => $message,
        ]);

        return $response;
    }
}
