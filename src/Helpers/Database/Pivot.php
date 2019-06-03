<?php

namespace Squadron\Base\Helpers\Database;

use Illuminate\Database\Eloquent\Model;

class Pivot
{
	public static function attach(Model $parentModel, $childModels, ?\Closure $afterPivotModelFill = null): void
	{
		if ($childModels instanceof Model)
		{
			$childModels = [$childModels];
		}

		/** @var Model $childModel */
		foreach ($childModels as $childModel)
		{
			if (!($childModel instanceof Model))
			{
				throw new \InvalidArgumentException(sprintf('Can\'t attach instance of class `%s`', \get_class($childModel)), 400);
			}

			$parent = class_basename($parentModel);
			$child = class_basename($childModel);

			$parentKey = sprintf('%sUuid', lcfirst($parent));
			$childKey = sprintf('%sUuid', lcfirst($child));
			$className = sprintf('\App\Models\%s%s', $parent, $child);

			/** @var Model $newPivotModel */
			$newPivotModel = new $className;

			$newPivotModel->$parentKey = $parentModel->getKey();
			$newPivotModel->$childKey = $childModel->getKey();

			if ($afterPivotModelFill !== null)
			{
				$afterPivotModelFill($newPivotModel);
			}

			$newPivotModel->save();
		}
	}

	public static function clear(Model $parentModel, $childModelClass): void
	{
		$parent = class_basename($parentModel);
		$child = class_basename($childModelClass);

		$parentKey = sprintf('%sUuid', lcfirst($parent));
		$className = sprintf('\App\Models\%s%s', $parent, $child);

		$className::where($parentKey, $parentModel->getKey())->delete();
	}
}