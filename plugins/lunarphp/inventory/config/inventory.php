<?php
return [
    /*
     * If set to false, no inventory will be saved to the database.
     */
    'enabled' => env('HS_INVENTORY_ENABLED', true),

    /*
     * This model will be used to process inventory.
     * It should implement the HS\Inventory\Models\Contracts\Inventory interface
     * and extend Illuminate\Database\Eloquent\Model.
     */
    'inventory_model' => \HS\Inventory\Models\ProductVariant::class,

    /*
    |--------------------------------------------------------------------------
    | Inventory Pipelines
    |--------------------------------------------------------------------------
    |
    | Define which pipelines should be run when performing cart calculations.
    | The default ones provided should suit most needs, however you are
    | free to add your own as you see fit.
    |
    | Each pipeline class will be run from top to bottom.
    |
    */
    'stock' => [
        'pipelines' => [],
    ],
    /*
    |--------------------------------------------------------------------------
    | Inventory Actions
    |--------------------------------------------------------------------------
    |
    | Here you can decide what action should be run during a Carts lifecycle.
    | The default actions should be fine for most cases.
    |
    */
    'actions' => [
        'add_to_stock' => Lunar\Actions\Carts\AddOrUpdatePurchasable::class,
        'get_existing_stock' => Lunar\Actions\Carts\GetExistingCartLine::class,
        'update_stock' => Lunar\Actions\Carts\UpdateCartLine::class,
        'remove_from_inventory' => Lunar\Actions\Carts\RemovePurchasable::class,
        'add_warehouse' => Lunar\Actions\Carts\AddAddress::class,
        'add_movement' => Lunar\Actions\Carts\AddAddress::class,
        'set_shipping_option' => Lunar\Actions\Carts\SetShippingOption::class,
        'order_create' => Lunar\Actions\Carts\CreateOrder::class,
    ],

    /*
     * Allows inventory changes to occur without a user responsible
     *
     * @var bool
     */
    'allow_no_user' => false,

    /*
     * Allows inventory stock movements to have the same before and after quantity
     *
     * @var bool
     */
    'allow_duplicate_movements' => true,

    /*
     * When set to true, this will reverse the cost in the rolled back movement.
     *
     * For example, if the movement's cost that is being rolled back is 500, the rolled back
     * movement will be -500.
     *
     * @var bool
     */
    'rollback_cost' => true,

    /*
     * SKU prefix
     *
     * @var bool
     */
    'sku_prefix' => 'IVT',

    /*
     * The sku prefix length, not including the code for example:
     *
     * An item with a category named 'Sauce', the sku prefix generated will be: SAU
     *
     * @var int
     */
    'sku_prefix_length' => 3,

    /*
     * The sku code length, not including prefix for example:
     *
     * An item with an ID of 1 (one) the sku code will be: 000001
     *
     * @var int
     */
    'sku_code_length' => 6,

    /*
     * The sku separator for use in separating the prefix from the code.
     *
     * For example, if a hyphen (-) is inserted in the string below, a possible
     * SKU might be 'DRI-00001'
     *
     * @var string
     */
    'sku_separator' => '-',
];
