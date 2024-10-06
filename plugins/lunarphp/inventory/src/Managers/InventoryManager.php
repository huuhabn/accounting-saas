<?php

namespace HS\Inventory\Managers;

use Lunar\Base\Purchasable;
use Illuminate\Pipeline\Pipeline;
use HS\Inventory\Models\Supplier;
use HS\Inventory\Models\Warehouse;
use HS\Inventory\Dto\InventoryResponse;
use Illuminate\Support\Facades\Auth;
use HS\Inventory\Models\InventoryStock;
use Illuminate\Contracts\Auth\Authenticatable;
use HS\Inventory\Models\InventoryStockMovement;
use HS\Inventory\Resolvers\InventoryStockResolver;
use HS\Inventory\Exceptions\NotEnoughStockException;
use HS\Inventory\Exceptions\InvalidQuantityException;
use HS\Inventory\Interfaces\InventoryManagerInterface;
use HS\Inventory\Exceptions\MissingWarehouseStockException;

class InventoryManager implements InventoryManagerInterface
{
    /**
     * The instance of the stock.
     */
    public InventoryStock $stock;

    /**
     * The instance of the purchasable model.
     */
    public Purchasable $purchasable;

    /**
     * The instance of the warehouse model
     */
    protected ?Warehouse $warehouse = null;

    /**
     * The instance of the supplier model
     */
    protected ?Supplier $supplier = null;

    /**
     * The instance of the user.
     */
    public ?Authenticatable $user = null;

    /**
     * The quantity value.
     */
    public int $qty = 0;

    /**
     * Initialise the InventoryManager.
     */
    public function __construct()
    {
        if (Auth::check()) {
            $this->user = Auth::user();
        }
    }

    /**
     * Set the purchasable property.
     *
     * @param Purchasable $purchasable
     * @return $this|InventoryResponse
     */
    public function on(Purchasable $purchasable): InventoryResponse|static
    {
        $this->purchasable = $purchasable;

        return $this;
    }

    /**
     * Set the user property.
     *
     * @return self
     */
    public function user(?Authenticatable $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Set the quantity property.
     *
     * @param int $qty
     * @return $this|InventoryManagerInterface
     */
    public function qty(int $qty)
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * Set the warehouse property
     */
    public function warehouse(?Warehouse $warehouse = null): self
    {
        $this->warehouse = $warehouse;

        return $this;
    }

    public function stocks(?Purchasable $purchasable = null): InventoryStockResolver
    {
        return new InventoryStockResolver($purchasable);
    }

    public function suppliers(?Purchasable $purchasable = null): InventoryStockResolver
    {
        return new InventoryStockResolver($purchasable);
    }

    /**
     * Get the stock for the purchasable.
     *
     * @throws MissingWarehouseStockException
     * @throws NotEnoughStockException
     * @throws \ErrorException
     */
    public function get(): InventoryStock
    {
        if (! $this->purchasable) {
            throw new \ErrorException('No purchasable set.');
        }

        if (!$this->warehouse){
            $this->warehouse = Warehouse::getDefault();
        }

        $warehouseStock = InventoryStock::query()->whereWarehouseId($this->warehouse->id)->first();

        if (!$warehouseStock){
            throw new MissingWarehouseStockException;
        }

        if ($this->qty && $warehouseStock->available_stock < $this->qty){
            throw new NotEnoughStockException;
        }

        $this->stock = $warehouseStock;

        $response = app(Pipeline::class)
            ->send($warehouseStock)
            ->through(
                config('hs.inventory.stock.pipelines', [])
            )->thenReturn(function ($warehouseStock) {
                return $warehouseStock;
            });

        $this->reset();

        return $response;
    }

    /**
     * Reset the manager into a base instance.
     *
     * @return void
     */
    private function reset(): void
    {
        $this->warehouse = null;
    }

    /**
     * @param int $quantity
     * @param string $reason
     * @param int $cost
     * @return bool|InventoryStock
     *
     * - Enter purchase order information.
     * - Check quantity, quality, and select storage warehouse.
     * - Update inventory in the corresponding warehouse.
     * - Monitor stock levels in each warehouse.
     * - Determine the warehouse for shipping and update the quantity of goods shipped.
     * - Analyze stock levels in each warehouse for redistribution.
     *
     */
    public function receiveGoods(int $quantity, string $reason = '', int $cost = 0): InventoryStock|bool
    {
        try {
            return $this->stock->put($quantity, $reason, $cost);
        }  catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param int $quantity
     * @param string $reason
     * @param int $cost
     * @return bool|InventoryStock
     */
    public function add(int $quantity, string $reason = '', int $cost = 0): InventoryStock|bool
    {
        try {
            return $this->stock->put($quantity, $reason, $cost);
        }  catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @throws \Throwable
     * @throws InvalidQuantityException
     * @throws NotEnoughStockException
     */
    public function remove(int $quantity, string $reason = '', int $cost = 0)
    {
        $this->stock->take($quantity, $reason, $cost);
    }

    /**
     * Removes the specified quantity from the current stock.
     *
     * @param int $quantity
     * @param string $reason
     * @param int $cost
     */
    public function updateQuantity(int $quantity, string $reason = '', int $cost = 0)
    {
        $this->stock->updateQuantity($quantity, $reason, $cost);
    }

    /**
     * Rolls back a specific movement.
     *
     * @param InventoryStockMovement $movement
     * @param bool $recursive
     */
    public function rollback(InventoryStockMovement $movement, bool $recursive = false)
    {
        $this->stock->processRollbackOperation($movement, $recursive);
    }
}
