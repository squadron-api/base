<?php

namespace Squadron\Base\Helpers\Database;

use Illuminate\Database\Eloquent\Model;
use Squadron\Base\Exceptions\SquadronHelperException;

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
            if (! ($childModel instanceof Model))
            {
                throw SquadronHelperException::badModelAttach($childModel);
            }

            $parent = class_basename($parentModel);
            $child = class_basename($childModel);

            $parentKey = sprintf('%sUuid', lcfirst($parent));
            $childKey = sprintf('%sUuid', lcfirst($child));
            $className = sprintf('\App\Models\%s%s', $parent, $child);

            /** @var Model $newPivotModel */
            $newPivotModel = new $className();

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
