<?php

namespace HS\Inventory\Facades;

use Lunar\Base\Purchasable;
use Illuminate\Support\Facades\Facade;
use HS\Inventory\Dto\InventoryResponse;
use HS\Inventory\Managers\InventoryManager;
use HS\Inventory\Interfaces\InventoryManagerInterface;


/**
 * Class Inventory.
 *
 * @method static InventoryManager on(Purchasable $purchasable)
 * @method static InventoryResponse get()
 * @method static void add(string $interfaceClass, string $modelClass)
 * @method static void replace(string $interfaceClass, string $modelClass)
 * @method static string guessContractClass(string $modelClass)
 * @method static string guessModelClass(string $modelContract)
 * @method static void morphMap()
 * @method static string getMorphMapKey(string $className)
 *
 * @see InventoryManager
 */
class Inventory extends Facade
{
    protected static function getFacadeAccessor()
    {
        return InventoryManagerInterface::class;
    }
}
