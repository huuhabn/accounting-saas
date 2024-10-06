<?php

namespace HS\Inventory\Models;

use Illuminate\Support\Carbon;
use HS\Inventory\Base\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class InventorySupplier
 *
 * @property int $id
 * @property int $inventory_id
 * @property int $supplier_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|InventorySupplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InventorySupplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InventorySupplier query()
 * @method static \Illuminate\Database\Eloquent\Builder|InventorySupplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventorySupplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventorySupplier whereInventoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventorySupplier whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InventorySupplier whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class InventorySupplier extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'postal_code',
        'zip_code',
        'region',
        'city',
        'country',
        'contact_title',
        'contact_name',
        'contact_phone',
        'contact_fax',
        'contact_email',
    ];

    /**
     * The belongsToMany items relationship.
     *
     * @return BelongsToMany
     */
    public function items(): BelongsToMany
    {
        return $this->belongsToMany($this->inventoryClass, 'inventory_suppliers', 'supplier_id')->withTimestamps();
    }
}
