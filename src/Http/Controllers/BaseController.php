<?php

namespace Squadron\Base\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->initializeTraits();
    }

    /**
     * Init controller traits.
     */
    private function initializeTraits(): void
    {
        $container = app();
        $initialized = [];

        foreach (class_uses_recursive($this) as $trait)
        {
            $method = 'initialize'.class_basename($trait);

            if (! in_array($method, $initialized, true) && method_exists($this, $method))
            {
                $container->call([$this, $method]);
                $initialized[] = $method;
            }
        }
    }
}
