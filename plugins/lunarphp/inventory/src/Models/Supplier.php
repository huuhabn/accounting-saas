<?php

namespace HS\Inventory\Models;

use Illuminate\Support\Carbon;
use HS\Inventory\Base\BaseModel;
use HS\Inventory\Database\Factories\SupplierFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Supplier
 *
 * @property int $id
 * @property string $name
 * @property string|null $contact_title
 * @property string|null $contact_name
 * @property string|null $contact_phone
 * @property string|null $contact_fax
 * @property string|null $contact_email
 * @property string|null $country
 * @property string|null $street
 * @property string|null $city
 * @property string|null $state
 * @property string|null $zip
 * @property string|null $full_address
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static \HS\Inventory\Database\Factories\SupplierFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier query()
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereContactEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereContactFax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereContactPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereContactTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereFullAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Supplier whereZip($value)
 * @mixin \Eloquent
 */
class Supplier extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'street',
        'zip',
        'state',
        'city',
        'country',
        'contact_title',
        'contact_name',
        'contact_phone',
        'contact_fax',
        'contact_email',
    ];

    /**
     * Return a new factory instance for the model.
     */
    protected static function newFactory(): SupplierFactory
    {
        return SupplierFactory::new();
    }

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
