<?php

namespace HS\Inventory\Models;

use Illuminate\Support\Carbon;
use HS\Inventory\Base\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class PurchaseOrder
 *
 * @property int $id
 * @property int|null $user_id
 * @property int $stock_id
 * @property string|null $name
 * @property string $status
 * @property string $quantity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $supplier_id
 * @property string $amount
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property-read \HS\Inventory\Models\Supplier $supplier
 * @method static Builder|PurchaseOrder newModelQuery()
 * @method static Builder|PurchaseOrder newQuery()
 * @method static Builder|PurchaseOrder query()
 * @method static Builder|PurchaseOrder whereAmount($value)
 * @method static Builder|PurchaseOrder whereCreatedAt($value)
 * @method static Builder|PurchaseOrder whereCreatedBy($value)
 * @method static Builder|PurchaseOrder whereId($value)
 * @method static Builder|PurchaseOrder whereStatus($value)
 * @method static Builder|PurchaseOrder whereSupplierId($value)
 * @method static Builder|PurchaseOrder whereUpdatedAt($value)
 * @method static Builder|PurchaseOrder whereUpdatedBy($value)
 * @mixin \Eloquent
 */
class PurchaseOrder extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'status',
        'amount',
        'created_by',
        'updated_by '
    ];

    /**
     * The belongsTo supplier relationship.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
