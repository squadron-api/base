<?php

namespace Squadron\Base\Helpers;

class Route
{
	public static function actionExists(string $action): bool
	{
		[$actionController, $actionMethod] = explode('@', $action, 2);

		return method_exists(sprintf('\\App\\Http\\Controllers\\%s', $actionController), $actionMethod);
	}
}