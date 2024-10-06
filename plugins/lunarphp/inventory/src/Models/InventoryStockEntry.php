<?php

namespace HS\Inventory\Models;

use Illuminate\Support\Carbon;
use HS\Inventory\Base\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class InventoryStockEntry
 *
 * @property int $id
 * @property int $inventory_id
 * @property int $warehouse_id
 * @property int|null $purchase_order_id
 * @property int $state_before
 * @property int $state_after
 * @property int $quantity_before
 * @property int $quantity_after
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Lunar\Models\ProductVariant $item
 * @property-read \HS\Inventory\Models\PurchaseOrder|null $purchaseOrder
 * @property-read \HS\Inventory\Models\Warehouse $warehouse
 * @method static Builder|InventoryStockEntry newModelQuery()
 * @method static Builder|InventoryStockEntry newQuery()
 * @method static Builder|InventoryStockEntry query()
 * @method static Builder|InventoryStockEntry whereCreatedAt($value)
 * @method static Builder|InventoryStockEntry whereId($value)
 * @method static Builder|InventoryStockEntry whereInventoryId($value)
 * @method static Builder|InventoryStockEntry wherePurchaseOrderId($value)
 * @method static Builder|InventoryStockEntry whereQuantityAfter($value)
 * @method static Builder|InventoryStockEntry whereQuantityBefore($value)
 * @method static Builder|InventoryStockEntry whereStateAfter($value)
 * @method static Builder|InventoryStockEntry whereStateBefore($value)
 * @method static Builder|InventoryStockEntry whereUpdatedAt($value)
 * @method static Builder|InventoryStockEntry whereWarehouseId($value)
 * @mixin \Eloquent
 */
class InventoryStockEntry extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'warehouse_id',
        'purchase_order_id',
        'state_before',
        'state_after',
        'quantity_before',
        'quantity_after',
    ];

    /**
     * The belongsTo item relationship.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo($this->inventoryClass, 'inventory_id');
    }

    /**
     * The belongsTo warehouse relationship.
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * The belongsTo purchase_order relationship.
     */
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }
}
