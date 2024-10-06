<?php

namespace HS\Inventory\Interfaces;

use Illuminate\Support\Collection;

interface InventoryInterface
{
    public function stocks(): Collection;
}
