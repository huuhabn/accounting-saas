<?php

namespace HS\Inventory\Base;

use Lunar\Base\Migration;
use HS\Inventory\Traits\HasInventoryModel;

class BaseMigration extends Migration
{
    use HasInventoryModel;

    /**
     * Inventory table.
     */
    protected string $inventoryTable = '';

    /**
     * Create a new instance of the migration.
     */
    public function __construct()
    {
        parent::__construct();

        $this->inventoryTable = static::getInventoryModelInstance()->getTable();
    }
}
