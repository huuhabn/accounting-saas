<?php

namespace HS\Inventory\Traits;

use Illuminate\Database\Eloquent\Model;
use HS\Inventory\Interfaces\InventoryInterface;
use HS\Inventory\Exceptions\InvalidConfiguration;

trait HasInventoryModel
{

    /**
     * @return string
     * @throws InvalidConfiguration
     */
    public static function determineInventoryModel(): string
    {
        $inventoryModel = config('hs.inventory.inventory_model');
        if (!$inventoryModel){
            throw InvalidConfiguration::modelIsNotConfig($inventoryModel);
        }

        if (! is_a($inventoryModel, Model::class, true)) {
            throw InvalidConfiguration::modelIsNotValid($inventoryModel);
        }

        return $inventoryModel;
    }

    /**
     * @throws InvalidConfiguration
     */
    public static function getInventoryModelInstance(): Model
    {
        $inventoryModelClassName = self::determineInventoryModel();

        return new $inventoryModelClassName();
    }
}
