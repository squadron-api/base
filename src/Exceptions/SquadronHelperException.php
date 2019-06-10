<?php

namespace Squadron\Base\Exceptions;

class SquadronHelperException extends \Exception
{
    public static function badRouteActionString(string $action)
    {
        return new static("Bad route action string: `{$action}`");
    }

    public static function badModelAttach($model)
    {
        $class = \get_class($model);

        return new static("Can't attach instance of class `{$class}`");
    }
}
