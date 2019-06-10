<?php

namespace Squadron\Base\Helpers;

use Squadron\Base\Exceptions\SquadronHelperException;

class Route
{
    public static $defaultControllerNamespace = '\\App\\Http\\Controllers\\';

    public static function actionExists(string $action): bool
    {
        $actionData = explode('@', $action);

        if (count($actionData) === 2)
        {
            [$actionController, $actionMethod] = $actionData;

            $controllerNamespace = $actionController[0] !== '\\'
                                      ? self::$defaultControllerNamespace : '';

            return method_exists($controllerNamespace.$actionController, $actionMethod);
        }

        throw SquadronHelperException::badRouteActionString($action);
    }
}
