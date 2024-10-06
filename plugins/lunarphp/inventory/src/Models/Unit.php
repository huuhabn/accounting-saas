<?php

namespace HS\Inventory\Models;

use Illuminate\Support\Carbon;
use HS\Inventory\Base\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Unit
 *
 * @property int $id
 * @property int|null $parent_id
 * @property string $name
 * @property string $symbol
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Unit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit query()
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereSymbol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Unit whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Unit extends BaseModel
{
    use HasFactory;

    /**
     * The hasMany inventory items relationship.
     */
    public function items(): HasMany
    {
        return $this->hasMany($this->inventoryClass, 'unit_id');
    }
}
