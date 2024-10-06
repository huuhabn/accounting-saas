<?php

namespace HS\Inventory\Models;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Lunar\Models\ProductVariant;
use HS\Inventory\Base\BaseModel;
use Illuminate\Database\Eloquent\Builder;
use HS\Inventory\Traits\HasInventoryStock;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class InventoryStock
 *
 * @property int $id
 * @property int|null $user_id
 * @property int $inventory_id
 * @property int $warehouse_id
 * @property int $available_stock
 * @property int $committed_stock
 * @property string|null $reason
 * @property string $cost
 * @property string|null $aisle
 * @property string|null $row
 * @property string|null $bin
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read Collection<InventoryStockMovement> $movements
 * @property-read int|null $movements_count
 * @property-read int|null $purchases_count
 * @property-read Warehouse|null $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder|InventoryStock query()
 * @method static Builder|InventoryStock newModelQuery()
 * @method static Builder|InventoryStock newQuery()
 * @method static Builder|InventoryStock productVariants(iterable $variantIds = [], array|string $types = [])
 * @method static Builder|InventoryStock qty(int $qty)
 * @method static Builder|InventoryStock whereAisle($value)
 * @method static Builder|InventoryStock whereAvailableStock($value)
 * @method static Builder|InventoryStock whereBin($value)
 * @method static Builder|InventoryStock whereCommittedStock($value)
 * @method static Builder|InventoryStock whereCost($value)
 * @method static Builder|InventoryStock whereCreatedAt($value)
 * @method static Builder|InventoryStock whereDeletedAt($value)
 * @method static Builder|InventoryStock whereId($value)
 * @method static Builder|InventoryStock whereInventoryId($value)
 * @method static Builder|InventoryStock whereReason($value)
 * @method static Builder|InventoryStock whereRow($value)
 * @method static Builder|InventoryStock whereUpdatedAt($value)
 * @method static Builder|InventoryStock whereUserId($value)
 * @method static Builder|InventoryStock whereWarehouseId($value)
 * @property-read ProductVariant $item
 * @mixin \Eloquent
 */
class InventoryStock extends BaseModel
{
    use HasFactory;
    use HasInventoryStock;

    /**
     * @param Builder $query
     * @param int $qty
     * @return Builder
     */
    public function scopeQty(Builder $query, int $qty = 0): Builder
    {
        return $query->where('available_stock', '>=', $qty);
    }

    /**
     * @param Builder $query
     * @param iterable $variantIds
     * @param array|string $types
     * @return Builder
     */
    public function scopeProductVariants(Builder $query, iterable $variantIds = [], array|string $types = []): Builder
    {
        if (is_array($variantIds)) {
            $variantIds = collect($variantIds);
        }

        $types = Arr::wrap($types);

        return $query->where(
            fn($subQuery) => $subQuery->whereDoesntHave('purchasables', fn($query) => $query->when($types, fn($query) => $query->whereIn('type', $types)))
                ->orWhereHas('purchasables',
                    fn($relation) => $relation->whereIn('purchasable_id', $variantIds)
                        ->wherePurchasableType((new ProductVariant)->getMorphClass())
                        ->when(
                            $types,
                            fn($query) => $query->whereIn('type', $types)
                        )
                )
        );
    }

    /**
     * The belongsTo inventory item relationship.
     *
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo($this->inventoryClass, 'inventory_id', 'id');
    }

    /**
     * The hasOne warehouse relationship.
     *
     * @return BelongsTo
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * The hasMany movements relationship.
     *
     * @return HasMany
     */
    public function movements(): HasMany
    {
        return $this->hasMany(InventoryStockMovement::class, 'stock_id');
    }
}
