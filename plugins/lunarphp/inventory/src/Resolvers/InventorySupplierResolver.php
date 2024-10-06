<?php

namespace HS\Inventory\Resolvers;

use Lunar\Base\Purchasable;
use HS\Inventory\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;

class InventorySupplierResolver
{
    /**
     * The instance of the purchasable model.
     */
    public Purchasable $purchasable;

    /**
     * Initialise the resolver.
     */
    public function __construct(?Purchasable $purchasable = null)
    {
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

}
