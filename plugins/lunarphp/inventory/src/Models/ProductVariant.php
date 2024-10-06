<?php

namespace HS\Inventory\Models;

use HS\Inventory\Traits\HasAssembly;
use HS\Inventory\Traits\HasInventory;

/**
 * Class ProductVariant
 *
 * @property int $id
 * @property int $product_id
 * @property int $tax_class_id
 * @property \Lunar\Base\Casts\AsAttributeData|null $attribute_data
 * @property string|null $tax_ref
 * @property int $unit_quantity
 * @property int $min_quantity
 * @property int $quantity_increment
 * @property string|null $sku
 * @property string|null $gtin
 * @property string|null $mpn
 * @property string|null $ean
 * @property string|null $length_value
 * @property string|null $length_unit
 * @property string|null $width_value
 * @property string|null $width_unit
 * @property string|null $height_value
 * @property string|null $height_unit
 * @property string|null $weight_value
 * @property string|null $weight_unit
 * @property string|null $volume_value
 * @property string|null $volume_unit
 * @property int $shippable
 * @property int $is_assembly
 * @property int $inventoriable
 * @property int $stock
 * @property int $backorder
 * @property string $purchasable
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $unit_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ProductVariant> $assemblies
 * @property-read int|null $assemblies_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ProductVariant> $assembliesRecursive
 * @property-read int|null $assemblies_recursive_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Lunar\Models\Price> $basePrices
 * @property-read int|null $base_prices_count
 * @property-read string $attributable_classname
 * @property-read mixed $attributable_morph_map
 * @property-read \Cartalyst\Converter\Converter $height
 * @property-read \Cartalyst\Converter\Converter $length
 * @property-read \Cartalyst\Converter\Converter $volume
 * @property-read \Cartalyst\Converter\Converter $weight
 * @property-read \Cartalyst\Converter\Converter $width
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, \Spatie\MediaLibrary\MediaCollections\Models\Media> $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Lunar\Models\Attribute> $mappedAttributes
 * @property-read int|null $mapped_attributes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Lunar\Models\Price> $priceBreaks
 * @property-read int|null $price_breaks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Lunar\Models\Price> $prices
 * @property-read int|null $prices_count
 * @property-read \Lunar\Models\Product $product
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \HS\Inventory\Models\InventoryStock> $stocks
 * @property-read int|null $stocks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \HS\Inventory\Models\Supplier> $suppliers
 * @property-read int|null $suppliers_count
 * @property-read \Lunar\Models\TaxClass $taxClass
 * @property-read \HS\Inventory\Models\Unit|null $unit
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Lunar\Models\ProductOptionValue> $values
 * @property-read int|null $values_count
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant assembly()
 * @method static \Lunar\Database\Factories\ProductVariantFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereAttributeData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereBackorder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereEan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereGtin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereHeightUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereHeightValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereInventoriable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereIsAssembly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereLengthUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereLengthValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereMinQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereMpn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant wherePurchasable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereQuantityIncrement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereShippable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereTaxClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereTaxRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereUnitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereUnitQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereVolumeUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereVolumeValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereWeightUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereWeightValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereWidthUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereWidthValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant withoutTrashed()
 * @mixin \Eloquent
 */
class ProductVariant extends \Lunar\Models\ProductVariant
{
    use HasInventory;
    use HasAssembly;

    public function canBeFulfilledAtQuantity(int $quantity): bool
    {
        if ($this->purchasable == 'always') {
            return true;
        }

        return $quantity <= $this->getTotalInventory();
    }

    public function getTotalInventory(): int
    {
        if ($this->purchasable == 'in_stock') {
            return $this->stock;
        }

        return $this->stock + $this->backorder;
    }
}
