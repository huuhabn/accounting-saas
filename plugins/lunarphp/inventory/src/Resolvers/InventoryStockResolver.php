<?php

namespace HS\Inventory\Resolvers;

use Lunar\Base\Purchasable;
use HS\Inventory\Models\Warehouse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use HS\Inventory\Models\InventoryStock;
use Illuminate\Contracts\Auth\Authenticatable;

class InventoryStockResolver
{
    /**
     * The instance of the purchasable model.
     */
    public Purchasable $purchasable;

    /**
     * The instance of the user.
     */
    public ?Authenticatable $user = null;

    /**
     * The warehouse to product when resolving stocks.
     */
    protected ?Warehouse $warehouse = null;

    /**
     * Initialise the resolver.
     */
    public function __construct(?Purchasable $purchasable = null)
    {
        if (Auth::check() && is_lunar_user(Auth::user())) {
            $this->user = Auth::user();
        }

        $this->setPurchasable($purchasable);
    }

    /**
     *  Set the purchase property
     *
     * @param Purchasable|null $purchasable
     * @return $this
     */
    public function setPurchasable(?Purchasable $purchasable = null)
    {
        $this->purchasable = $purchasable;

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

    /**
     * Return the inventory stocks applicable to the purchasable.
     */
    public function get(): Collection
    {
        if (! $this->purchasable) {
            throw new \ErrorException('No purchasable set.');
        }

        if (! $this->warehouse) {
            $this->warehouse = Warehouse::getDefault();
        }

        $query = InventoryStock::query()->warehouse($this->warehouse);

        return $query->get();
    }
}
