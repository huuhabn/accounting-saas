<?php

namespace HS\Inventory\Traits;

use Exception;
use HS\Inventory\Models\Unit;
use HS\Inventory\Models\Supplier;
use HS\Inventory\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use HS\Inventory\Models\InventoryStock;
use HS\Inventory\Dto\InventoryStockDto;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use HS\Inventory\Exceptions\InvalidQuantityException;
use HS\Inventory\Exceptions\InvalidSupplierException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use HS\Inventory\Exceptions\StockAlreadyExistsWithOtherQytException;

/**
 * Trait InventoryTrait.
 */
trait HasInventory
{
    use HasUser;
    use HasDbTransaction;

    public static function bootHasInventory(): void
    {
        /*
        * Assign the current users ID while the item
        * is being created
        */
        static::creating(function (self $record) {
            // $record->user_id = static::getCurrentUserId();
        });

        static::created(function (self $record) {
            // TODO: Do something
        });

        static::deleting(function (self $record) {
            $this->dbStartTransaction();
            try{
                $record->suppliers()->delete();
                $record->stocks()->delete();
                $record->assemblies()->delete();
                $this->dbCommitTransaction();
            } catch (Exception $e) {
                $this->dbRollbackTransaction();
            }
        });
    }

    /**
     * The hasMany stocks relationship.
     *
     * @return HasMany
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(InventoryStock::class);
    }

    /**
     * The belongsToMany suppliers relationship.
     *
     * @return BelongsToMany
     */
    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class, 'inventory_suppliers', 'inventory_id')->withTimestamps();
    }

    /**
     * Adds the specified supplier to the current inventory item.
     *
     * @param Supplier $supplier
     * @return bool
     */
    public function addSupplier(Supplier $supplier): bool
    {
        return $this->processSupplierAttach($supplier);
    }

    /**
     * Processes attaching a supplier to an inventory item.
     *
     * @param Supplier $supplier
     *
     * @return bool
     */
    private function processSupplierAttach(Supplier $supplier): bool
    {
        $this->dbStartTransaction();

        try {
            $this->suppliers()->attach($supplier);

            $this->dbCommitTransaction();

            return true;
        } catch (Exception $e) {
            $this->dbRollbackTransaction();
        }

        return false;
    }

    /**
     * @return BelongsTo
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * The belongsToMany assemblies relationship.
     *
     * @return BelongsToMany
     */
    public function assemblies(): BelongsToMany
    {
        return $this->belongsToMany(static::class, 'inventory_assemblies', 'inventory_id', 'part_id')
            ->withPivot(['quantity'])
            ->withTimestamps();
    }

    /**
     *  Add stock to inventory by supplying a number, and the warehouse
     *
     * @param InventoryStockDto $stockDto
     * @param Warehouse|null $warehouse
     * @return InventoryStock|null
     */
    public function createStockOnWarehouse(InventoryStockDto $stockDto, Warehouse $warehouse = null): ?InventoryStock
    {
        $warehouse = $warehouse ?: Warehouse::getDefault();

        $stock = $this->getStockOnWarehouse($warehouse->id);
        if ($stock){
            if ($stock->available_stock && $stock->available_stock != $stockDto->quantity){
                throw new StockAlreadyExistsWithOtherQytException($warehouse, $stock->available_stock);
            }

            return $stock;
        }

        /** @var InventoryStock $stock */
        $stock = InventoryStock::create([
            'inventory_id' => $this->getKey(),
            'warehouse_id' => $warehouse->getKey(),
            'available_stock' => 0,
            'cost' => 0,
            'aisle' => $stockDto->aisle,
            'row' => $stockDto->row,
            'bin' => $stockDto->bin,
        ]);

        /*
         * Now we'll 'put' the inserted quantity onto the generated stock
         * and return the results
         */
        return $stock->put($stockDto->quantity, $stockDto->reason, $stockDto->cost);

    }

    /**
     * Get stock from warehouse
     *
     * @param int $warehouseId
     * @return InventoryStock|null
     */
    public function getStockOnWarehouse(int $warehouseId): ?InventoryStock
    {
        return InventoryStock::query()
            ->where('inventory_id', $this->getKey())
            ->where('warehouse_id', $warehouseId)
            ->first();
    }
}
