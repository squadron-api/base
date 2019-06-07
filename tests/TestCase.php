<?php

namespace Squadron\Base\Tests;

use Dotenv\Dotenv;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;
use ReflectionClass;
use ReflectionException;
use Squadron\Base\ServiceProvider;

abstract class TestCase extends Orchestra
{
    public function setUp(): void
    {
        $this->loadEnvironmentVariables();

        parent::setUp();
    }

    protected function loadEnvironmentVariables(): void
    {
        if (! file_exists(__DIR__.'/../.env'))
        {
            return;
        }

        Dotenv::create(__DIR__.'/..')->load();
    }

    /**
     * Define environment setup.
     *
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'port' => env('DB_PORT'),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
    }

    /**
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [ServiceProvider::class];
    }

    /**
     * Calls private / protected method.
     *
     * @param $obj
     * @param $name
     * @param array $args
     *
     * @throws ReflectionException
     *
     * @return mixed
     */
    protected function callMethod($obj, $name, array $args = [])
    {
        $class = new ReflectionClass($obj);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method->invokeArgs($obj, $args);
    }
}
