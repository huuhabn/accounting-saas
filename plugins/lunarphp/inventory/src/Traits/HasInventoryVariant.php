<?php

namespace HS\Inventory\Traits;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Trait InventoryVariantTrait.
 */
trait HasInventoryVariant
{
    /**
     * The hasMany recursive variant's relationship.
     */
    public function variantsRecursive(): HasMany
    {
        return $this->variants()->with('variantsRecursive');
    }

    /**
     * The hasMany variants relationship.
     */
    public function variants(): HasMany
    {
        return $this->hasMany(get_class($this), 'parent_id');
    }

    /**
     * Returns true / false if the current item is a variant of another item.
     */
    public function isVariant(): bool
    {
        if ($this->parent_id) {
            return true;
        }

        return false;
    }

    /**
     * Returns the total sum of the item
     * variants stock. This method is recursive
     * by default, and includes variants of variants
     * total stock.
     *
     * @param bool $recursive
     *
     * @return int|float
     */
    public function getTotalVariantStock(bool $recursive = true): float|int
    {
        $quantity = 0;

        $variants = $this->getVariants();

        if (count($variants) > 0) {
            foreach ($variants as $variant) {
                $quantity = $quantity + $variant->getTotalStock();

                /*
                 * If the developer wants complete recursive variant stock,
                 * we'll return a complete quantity for the variants variants
                 */
                if ($recursive && $variant->hasVariants()) {
                    $quantity = $quantity + $variant->getTotalVariantStock();
                }
            }
        }

        return $quantity;
    }

    /**
     * Returns all variants of the current item.
     *
     * This method does not retrieve variants recursively.
     *
     * @param bool $recursive
     *
     * @return Collection
     */
    public function getVariants($recursive = false): Collection
    {
        if ($recursive) {
            return $this->variantsRecursive;
        } else {
            return $this->variants;
        }
    }

    /**
     * Returns true / false if the current
     * item has variants.
     *
     * @return bool
     */
    public function hasVariants()
    {
        if (count($this->getVariants()) > 0) {
            return true;
        }

        return false;
    }

    /**
     * Creates a new variant instance, saves it,
     * and returns the resulting variant.
     *
     * @param string $name
     * @param string $description
     * @param int|string|null $categoryId
     * @param int|string|null $metricId
     *
     * @return bool|Model
     */
    public function createVariant(string $name = '', string $description = '', int|string $categoryId = null, int|string $metricId = null): Model|bool
    {
        $variant = $this->newVariant($name);

        try {
            if (!empty($description)) {
                $variant->description = $description;
            }

            if ($categoryId !== null) {
                $variant->category_id = $categoryId;
            }

            if ($metricId !== null) {
                $variant->metric_id = $metricId;
            }

            if ($variant->save()) {
                $this->dbCommitTransaction();

                $this->fireEvent('inventory.variant.created', [
                    'item' => $this,
                ]);

                return $variant;
            }
        } catch (Exception $e) {
            $this->dbRollbackTransaction();
        }

        return false;
    }

    /**
     * Returns a new Inventory model instance with it's parent
     * ID, category ID, and metric ID set to the current item's
     * for the creation of a variant.
     *
     * @param string $name
     *
     * @return Model
     */
    public function newVariant(string $name = ''): Model
    {
        $variant = new $this();

        $variant->parent_id = $this->getKey();
        $variant->category_id = $this->category_id;
        $variant->metric_id = $this->metric_id;

        if (!empty($name)) {
            $variant->name = $name;
        }

        return $variant;
    }

    /**
     * Makes the current item a variant of
     * the specified item.
     *
     * @param Model $item
     *
     * @return $this|bool
     */
    public function makeVariantOf(Model $item)
    {
        return $this->processMakeVariant($item->getKey());
    }

    /**
     * Processes making the current item a variant
     * of the specified item ID.
     *
     * @param int|string $itemId
     *
     * @return $this|bool
     */
    private function processMakeVariant($itemId)
    {
        $this->dbStartTransaction();

        try {
            $this->parent_id = $itemId;

            if ($this->save()) {
                $this->dbCommitTransaction();

                $this->fireEvent('inventory.variant.made', [
                    'item' => $this,
                ]);

                return $this;
            }
        } catch (Exception $e) {
            $this->dbRollbackTransaction();
        }

        return false;
    }
}
