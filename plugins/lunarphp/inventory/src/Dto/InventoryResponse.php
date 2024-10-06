<?php

namespace HS\Inventory\Dto;


class InventoryResponse
{
    public function __construct(
        public int $totalAvailableStock,
        public ?int $totalCommittedStock = 0,
        // public Collection $totalAvailableStockDistribution,
    ) {
        //
    }
}
