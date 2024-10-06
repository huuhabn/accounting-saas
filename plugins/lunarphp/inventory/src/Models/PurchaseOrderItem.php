<?php

namespace HS\Inventory\Models;

use Illuminate\Support\Carbon;
use HS\Inventory\Base\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PurchaseOrderItem
 *
 * @property int $id
 * @property int|null $user_id
 * @property int $stock_id
 * @property string|null $name
 * @property string $status
 * @property string $quantity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $inventory_id
 * @property int|null $purchase_order_id
 * @property string $unit_price
 * @property string $total_price
 * @property-read \Lunar\Models\ProductVariant $item
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \HS\Inventory\Models\PurchaseOrder> $purchaseOrder
 * @property-read int|null $purchase_order_count
 * @method static Builder|PurchaseOrderItem newModelQuery()
 * @method static Builder|PurchaseOrderItem newQuery()
 * @method static Builder|PurchaseOrderItem query()
 * @method static Builder|PurchaseOrderItem whereCreatedAt($value)
 * @method static Builder|PurchaseOrderItem whereId($value)
 * @method static Builder|PurchaseOrderItem whereInventoryId($value)
 * @method static Builder|PurchaseOrderItem wherePurchaseOrderId($value)
 * @method static Builder|PurchaseOrderItem whereQuantity($value)
 * @method static Builder|PurchaseOrderItem whereTotalPrice($value)
 * @method static Builder|PurchaseOrderItem whereUnitPrice($value)
 * @method static Builder|PurchaseOrderItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PurchaseOrderItem extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'purchase_order_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    /**
     * The belongsTo item relationship.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo($this->inventoryClass, 'inventory_id');
    }

    /**
     * The hasMany purchase order relationship.
     */
    public function purchaseOrder(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

}
