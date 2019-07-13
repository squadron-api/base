<?php

namespace Squadron\Base\Exceptions;

class SquadronException extends \Exception
{
    public static function packageIsNotInstalled(string $packageName)
    {
        return new static("Package {$packageName} must be installed for this feature");
    }
}
