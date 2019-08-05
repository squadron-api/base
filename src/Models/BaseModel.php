<?php

namespace Squadron\Base\Models;

use Squadron\Base\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class BaseModel.
 *
 * @property string $uuid
 */
class BaseModel extends Model
{
    use HasUuid;

    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';
    public const DELETED_AT = 'deletedAt';

    public $timestamps = false;

    protected $primaryKey = 'uuid';
    protected $keyType = 'uuid';
    public $incrementing = false;

    /**
     * {@inheritdoc}
     */
    public function getTable()
    {
        if ($this->table === null)
        {
            return str_replace('\\', '', Str::snake(class_basename($this)));
        }

        return $this->table;
    }
}
