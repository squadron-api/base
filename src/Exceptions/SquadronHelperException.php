<?php

namespace Squadron\Base\Exceptions;

class SquadronHelperException extends \Exception
{
    public static function badRouteActionString(string $action)
    {
        return new static("Bad route action string: `{$action}`");
    }
}
