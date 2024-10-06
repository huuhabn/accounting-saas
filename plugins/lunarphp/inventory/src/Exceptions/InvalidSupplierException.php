<?php

namespace HS\Inventory\Exceptions;

use Exception;
use HS\Inventory\Models\Supplier;

/**
 * Class InvalidSupplierException.
 */
class InvalidSupplierException extends Exception
{
    public function __construct(Supplier $supplier)
    {
        $this->message = __('hs-inventory::lang.exceptions.InvalidSupplierException', [
            'supplier' => $supplier->name
        ]);
    }
}
