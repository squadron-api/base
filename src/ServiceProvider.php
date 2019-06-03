<?php

namespace Squadron\Base;

use Squadron\Base\Console\Commands\HashString;
use Squadron\Base\Console\Commands\SetVersion;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
	    if ($this->app->runningInConsole())
	    {
		    $this->commands([
			    HashString::class,
			    SetVersion::class,
		    ]);
	    }

	    $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }

	public function register(): void
	{

    }
}
