<?php

namespace HS\Inventory\Base;

use Lunar\Base\BaseModel as Model;
use HS\Inventory\Traits\HasInventoryModel;

class BaseModel extends Model
{
    use HasInventoryModel;

    /**
     * Inventory model instance.
     */
    protected string $inventoryModel = '';

    /**
     * Inventory class.
     */
    protected string $inventoryClass = '';

    /**
     * Migration table prefix.
     */
    protected string $prefix = '';

    /**
     * Create a new instance of the Model.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->prefix = config('lunar.database.table_prefix');

        $this->inventoryClass = static::determineInventoryModel();

        $this->inventoryModel = static::getInventoryModelInstance();

    }
}
