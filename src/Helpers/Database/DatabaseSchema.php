<?php

namespace Squadron\Base\Helpers\Database;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;

class DatabaseSchema
{
	public static function create(string $tableName, \Closure $tableStructure,
	                              ?array $references = null, bool $useTimestamps = false, bool $useSorting = false): void
	{
		Schema::create($tableName, function (Blueprint $table) use ($tableStructure, $references, $useTimestamps, $useSorting) {
			$table->uuid('uuid');
			$table->primary('uuid');

			$tableStructure($table);

			if ($references !== null)
			{
				foreach ($references as $referenceKey => $reference)
				{
					$referenceSettings = [];

					if (!is_numeric($referenceKey) && is_array($reference))
					{
						$referenceSettings = $reference;
						$reference = $referenceKey;
					}

					$columnName = sprintf('%sUuid', Str::camel($reference));
					$onUpdate = $referenceSettings['onUpdate'] ?? 'cascade';
					$onDelete = $referenceSettings['onDelete'] ?? 'cascade';

					$column = $table->uuid($columnName);

					if ($onUpdate === 'set null' || $onDelete === 'set null')
					{
						$column->nullable();
					}

					$table->foreign($columnName)->references('uuid')->on($reference)->onUpdate($onUpdate)->onDelete($onDelete);
				}
			}

			if ($useSorting)
			{
				$table->unsignedMediumInteger('sortOrder')->default(0);
			}

			if ($useTimestamps)
			{
				$table->timestamp('createdAt')->nullable();
				$table->timestamp('updatedAt')->nullable();
			}
		});
	}

	public static function createPivot(string $name1, string $name2, ?string $table1 = null, ?string $table2 = null): void
	{
		$pivotName = sprintf('%s_%s', $name1, $name2);

		$primaryKeys = [
			sprintf('%sUuid', $name1),
			sprintf('%sUuid', $name2)
		];

		$tables = [
			$table1 ?? $name1,
			$table2 ?? $name2,
		];

		Schema::create($pivotName, function (Blueprint $table) use ($primaryKeys, $tables) {
			$table->uuid($primaryKeys[0]);
			$table->uuid($primaryKeys[1]);

			$table->primary($primaryKeys);

			$table->foreign($primaryKeys[0])->references('uuid')->on($tables[0])->onDelete('cascade')->onUpdate('cascade');
			$table->foreign($primaryKeys[1])->references('uuid')->on($tables[1])->onDelete('cascade')->onUpdate('cascade');
		});
	}
}