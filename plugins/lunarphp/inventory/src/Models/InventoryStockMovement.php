<?php

namespace HS\Inventory\Models;

use Illuminate\Support\Carbon;
use HS\Inventory\Traits\HasUser;
use HS\Inventory\Base\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class InventoryStockMovement.
 *
 * @property int $id
 * @property int $stock_id
 * @property string $before
 * @property string $after
 * @property string|null $cost
 * @property string|null $receiver_type
 * @property int|null $receiver_id
 * @property string|null $reason
 * @property int $returned
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $user_id
 * @property-read \HS\Inventory\Models\InventoryStock $stock
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement query()
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement whereAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement whereBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement whereReceiverType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement whereReturned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement whereStockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStockMovement whereUserId($value)
 * @mixin \Eloquent
 */
class InventoryStockMovement extends BaseModel
{
    use HasUser;
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (InventoryStockMovement $stockMovement) {
            $stockMovement->created_by = static::getCurrentUserId();
        });

        static::updating(function (InventoryStockMovement $stockMovement) {
            $stockMovement->updated_by = static::getCurrentUserId();
        });

        static::deleting(function (InventoryStockMovement $stockMovement) {
            DB::beginTransaction();
            $stockMovement->stock()->delete();
            DB::commit();
        });
    }

    /**
     * The belongsTo stock relationship.
     *
     * @return BelongsTo
     */
    public function stock(): BelongsTo
    {
        return $this->belongsTo(InventoryStock::class, 'stock_id');
    }
}
