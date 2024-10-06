<?php

namespace HS\Inventory\Traits;

use Illuminate\Support\Collection;
use HS\Inventory\Models\InventoryStock;
use HS\Inventory\Facades\ProductStock as Stock;
use HS\Inventory\Managers\ProductStockManager;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class HasInventoryTraits
{
    /**
     * Get all the models stock.
     */
    public function stocks(): MorphMany
    {
        return $this->hasMany(InventoryStock::class);
    }

    /**
     * @return Collection
     */
    public function getStocks(): Collection
    {
        return $this->stocks;
    }

    /**
     * Return a ProductStockManager for this model.
     */
    public function stock(): ProductStockManager
    {
        return Stock::for($this);
    }
}
