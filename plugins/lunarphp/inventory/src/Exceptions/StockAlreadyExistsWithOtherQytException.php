<?php

namespace HS\Inventory\Exceptions;

use Exception;
use HS\Inventory\Models\Warehouse;

/**
 * Class StockAlreadyExistsWithOtherQytException.
 */
class StockAlreadyExistsWithOtherQytException extends Exception
{
    public function __construct(Warehouse $warehouse, int $qty = 0)
    {
        $this->message = __('hs-inventory::lang.exceptions.StockAlreadyExistsWithOtherQytException', [
            'warehouse' => $warehouse->name,
            'qty' => $qty,
        ]);
    }
}
