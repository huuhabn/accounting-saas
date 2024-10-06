<?php

return [
    'section' => 'Inventory',
    'label' => 'Inventory',
    'labels' => 'Inventories',
    'description' => 'Inventory product management',
    'reasons' => [
        'first_record' => 'Create inventory',
        'change' => 'Stock Adjustment',
        'rollback' => 'Rolled back to movement ID: :id on :date',
        'transactions' => [
            'checkout' => 'Checkout occurred on Transaction ID: :id on :date',
            'sold-amount' => 'Partial sale occurred on Transaction ID: :id on :date',
            'returned' => 'Full return occurred on Transaction ID: :id on :date',
            'returned-partial' => 'Partial return occurred on Transaction ID: :id on :date',
            'reserved' => 'Reservation occurred on Transaction ID: :id on :date',
            'received' => 'Order fully received on Transaction ID :id on :date',
            'received-partial' => 'Order partially received on Transaction ID :id on :date',
            'back-order-filled' => 'Back-order filled on Transaction ID :id on :date',
            'hold' => 'Stock hold occurred on Transaction ID :id on :date',
            'released' => 'Release occurred on Transaction ID :id on :date',
            'released-partial' => 'Partial release occurred on Transaction ID :id on :date',
            'removed' => 'Removal occurred on Transaction ID :id on :date',
            'cancelled' => 'Cancellation occurred on Transaction ID :id on :date',
        ],
    ],
    'exceptions' => [
        'InvalidLocationException' => 'Location :location is invalid',
        'InvalidMovementException' => 'Movement :movement is invalid',
        'InvalidSupplierException' => 'Supplier :supplier is invalid',
        'InvalidItemException' => 'Item :item is invalid',
        'InvalidQuantityException' => 'Quantity :quantity is invalid',
        'NotEnoughStockException' => 'Not enough stock. Tried to take :quantity but only :available is available',
        'NoUserLoggedInException' => 'Cannot retrieve user ID',
        'StockAlreadyExistsException' => 'Stock already exists on location :location',
        'StockAlreadyExistsWithOtherQytException' => 'Stock already exists in `:warehouse` with a different quantity of :qty.',
        'StockNotFoundException' => 'No stock was found from warehouse :warehouse',
        'SkuAlreadyExistsException' => 'An SKU already exists for this item',
    ]
];
