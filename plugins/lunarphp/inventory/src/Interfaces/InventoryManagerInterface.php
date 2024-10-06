<?php

namespace HS\Inventory\Interfaces;

use Lunar\Base\Purchasable;
use HS\Inventory\Models\Warehouse;
use HS\Inventory\Dto\InventoryResponse;
use Illuminate\Contracts\Auth\Authenticatable;
use HS\Inventory\Models\InventoryStockMovement;

interface InventoryManagerInterface {
    /**
     * Set the user property.
     *
     * @return self
     */
    public function user(Authenticatable $user);

    /**
     * Set the warehouse property.
     *
     * @return self
     */
    public function warehouse(Warehouse $warehouse);

    /**
     * Set the quantity property.
     *
     * @return self
     */
    public function qty(int $qty);

    /**
     * Get the price for a purchasable.
     *
     * @return InventoryResponse
     */
    public function on(Purchasable $purchasable);

    /**
     * Get the stock for the purchasable.
     */
    public function get();

    /**
     * Processes a 'put' operation on the current stock.
     *
     * @param int $quantity
     * @param string $reason
     * @param int $cost
     */
    public function add(int $quantity, string $reason = '', int $cost = 0);

    /**
     * Removes the specified quantity from the current stock.
     *
     * @param int $quantity
     * @param string $reason
     * @param int $cost
     */
    public function remove(int $quantity, string $reason = '', int $cost = 0);

    /**
     * Removes the specified quantity from the current stock.
     *
     * @param int $quantity
     * @param string $reason
     * @param int $cost
     */
    public function updateQuantity(int $quantity, string $reason = '', int $cost = 0);

    /**
     * Rolls back a specific movement.
     *
     * @param InventoryStockMovement $movement
     * @param bool $recursive
     */
    public function rollback(InventoryStockMovement $movement, bool $recursive = false);

}
