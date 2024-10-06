<?php

namespace HS\Inventory\Models;

use Illuminate\Support\Carbon;
use HS\Inventory\Base\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use HS\Inventory\Traits\HasDefaultRecord;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use HS\Inventory\Database\Factories\WarehouseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Warehouse
 *
 * @property int $id
 * @property string $name
 * @property int $default
 * @property string|null $country
 * @property string|null $street
 * @property string|null $city
 * @property string|null $state
 * @property string|null $zip
 * @property string|null $full_address
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Warehouse newModelQuery()
 * @method static Builder|Warehouse newQuery()
 * @method static Builder|Warehouse query()
 * @method static Builder|Warehouse whereCity($value)
 * @method static Builder|Warehouse whereCountry($value)
 * @method static Builder|Warehouse whereCreatedAt($value)
 * @method static Builder|Warehouse whereDefault($value)
 * @method static Builder|Warehouse whereFullAddress($value)
 * @method static Builder|Warehouse whereId($value)
 * @method static Builder|Warehouse whereName($value)
 * @method static Builder|Warehouse whereState($value)
 * @method static Builder|Warehouse whereStreet($value)
 * @method static Builder|Warehouse whereUpdatedAt($value)
 * @method static Builder|Warehouse whereZip($value)
 * @property-read Collection<int, \HS\Inventory\Models\InventoryStock> $stocks
 * @property-read int|null $stocks_count
 * @method static Builder|Warehouse default($default = true)
 * @method static \HS\Inventory\Database\Factories\WarehouseFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
class Warehouse extends BaseModel
{
    use HasFactory;
    use HasDefaultRecord;

    protected $guarded = [];

    /**
     * Boot warehouse
     *
     * @return void
     */
    protected static function booted(): void
    {
        $handleDefaultFunction = fn(Warehouse $warehouse) => Warehouse::when(
            $warehouse->default,
            fn($query) => $query->where('id', '!=', $warehouse->id)->update([
                'default' => false,
            ])
        );

        static::created(function (Warehouse $warehouse) use ($handleDefaultFunction) {
            $handleDefaultFunction($warehouse);

            // TODO: Do something
        });

        static::created($handleDefaultFunction);

        static::updated($handleDefaultFunction);

        static::deleting(function (self $warehouse) {
            DB::beginTransaction();
            $warehouse->stocks()->delete();
            DB::commit();
        });
    }

    /**
     * The hasMany stocks relationship.
     */
    public function stocks(): HasMany
    {
        return $this->hasMany(InventoryStock::class, 'warehouse_id', 'id');
    }

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory(): WarehouseFactory
    {
        return WarehouseFactory::new();
    }

}
