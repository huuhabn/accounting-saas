<?php

namespace HS\Inventory\Dto;

use HS\Inventory\Models\Warehouse;

class InventoryStockDto
{
    public function __construct(
        public int $quantity = 0,
        public ?string $reason = '',
        public ?int $cost = 0,
        public ?string $aisle = '',
        public ?string $row = '',
        public ?string $bin = '',
    ) {
        //  ..
    }

}
