<?php

namespace HS\Inventory\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use HS\Inventory\Exceptions\InvalidPartException;
use HS\Inventory\Exceptions\InvalidQuantityException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class AssemblyTrait.
 */
trait HasAssembly
{
    /**
     * The items assembly cache key.
     *
     * @var string
     */
    protected $assemblyCacheKey = 'inventory::assembly.';

    /**
     * The belongsToMany recursive assembly's relationship.
     *
     * @return BelongsToMany
     */
    public function assembliesRecursive(): BelongsToMany
    {
        return $this->assemblies()->with('assembliesRecursive');
    }

    /**
     * The hasMany assemblies relationship.
     *
     * @return BelongsToMany
     */
    abstract public function assemblies(): BelongsToMany;

    /**
     * Adds multiple parts to the current items assembly.
     *
     * @param array $parts
     * @param int|float|string $quantity
     * @param array $extra
     *
     * @return int
     * @throws InvalidQuantityException
     *
     */
    public function addAssemblyItems(array $parts, $quantity = 1, array $extra = []): int
    {
        $count = 0;

        if (count($parts) > 0) {
            foreach ($parts as $part) {
                if ($this->addAssemblyItem($part, $quantity, $extra)) {
                    $count++;
                }
            }
        }

        return $count;
    }

    /**
     * Adds an item to the current assembly.
     *
     * @param Model $part
     * @param int|float|string $quantity
     * @param array $extra
     *
     * @return $this
     * @throws InvalidQuantityException|InvalidPartException
     *
     */
    public function addAssemblyItem(Model $part, $quantity = 1, array $extra = []): false|static
    {
        if (!$this->is_assembly) {
            $this->makeAssembly();
        }

        if ($part->is_assembly) {
            $this->validatePart($part);
        }

        $attributes = array_merge(['quantity' => $quantity], $extra);

        if ($this->assemblies()->save($part, $attributes)) {

            $this->forgetCachedAssemblyItems();

            return $this;
        }

        return false;
    }

    /**
     * Makes the current item an assembly.
     *
     * @return $this
     */
    public function makeAssembly(): static
    {
        $this->is_assembly = true;

        return $this->save();
    }

    /**
     * Validates that the inserted parts assembly
     * does not contain the current item. This
     * prevents infinite recursion.
     *
     * @param Model $part
     *
     * @return bool
     *
     * @throws InvalidPartException
     */
    private function validatePart(Model $part)
    {
        if ((int)$part->getKey() === (int)$this->getKey()) {
            $message = 'An item cannot be an assembly of itself.';

            throw new InvalidPartException($message);
        }

        $list = $part->getAssemblyItemsList();

        array_walk_recursive($list, [$this, 'validatePartAgainstList']);

        return true;
    }

    /**
     * Returns all of the assemblies items in an
     * easy to work with array.
     *
     * @param bool $recursive
     * @param int $depth
     *
     * @return array
     */
    public function getAssemblyItemsList($recursive = true, $depth = 0)
    {
        $list = [];

        $level = 0;

        $depth++;

        $items = $this->getAssemblyItems();

        foreach ($items as $item) {
            $list[$level] = [
                'id' => $item->getKey(),
                'name' => $item->name,
                'metric_id' => $item->metric_id,
                'category_id' => $item->category_id,
                'quantity' => $item->pivot->quantity,
                'depth' => $depth,
            ];

            if ($item->is_assembly && $recursive) {
                $list[$level]['parts'] = $item->getAssemblyItemsList(true, $depth);
            }

            $level++;
        }

        return $list;
    }

    /**
     * Returns all of the assemblies items. If recursive
     * is true, the entire nested assemblies collection
     * is returned.
     *
     * @param bool $recursive
     *
     * @return Collection
     */
    public function getAssemblyItems($recursive = true)
    {
        if ($recursive) {
            $results = $this->getCachedAssemblyItems();

            if (!$results) {
                $results = $this->assembliesRecursive;

                /*
                 * Cache forever since adding / removing assembly
                 * items will automatically clear this cache
                 */
                Cache::forever($this->getAssemblyCacheKey(), $results);
            }

            return $results;
        }

        return $this->assemblies;
    }

    /**
     * Returns the current cached items assembly if
     * it exists inside the cache. Returns false
     * otherwise.
     *
     * @return bool|Collection
     */
    public function getCachedAssemblyItems(): Collection|bool
    {
        if ($this->hasCachedAssemblyItems()) {
            return Cache::get($this->getAssemblyCacheKey());
        }

        return false;
    }

    /**
     * Returns true / false if the current item
     * has a cached assembly.
     *
     * @return bool
     */
    public function hasCachedAssemblyItems(): bool
    {
        return Cache::has($this->getAssemblyCacheKey());
    }

    /**
     * Returns the current items assemblies cache key.
     *
     * @return string
     */
    private function getAssemblyCacheKey()
    {
        return $this->assemblyCacheKey . $this->getKey();
    }

    /**
     * Removes the current items assembly items
     * from the cache.
     *
     * @return bool
     */
    public function forgetCachedAssemblyItems()
    {
        return Cache::forget($this->getAssemblyCacheKey());
    }

    /**
     * Updates multiple parts with the specified quantity.
     *
     * @param array $parts
     * @param int|float|string $quantity
     * @param array $extra
     *
     * @return int
     * @throws InvalidQuantityException
     *
     */
    public function updateAssemblyItems(array $parts, $quantity, array $extra = []): int
    {
        $count = 0;

        if (count($parts) > 0) {
            foreach ($parts as $part) {
                if ($this->updateAssemblyItem($part, $quantity, $extra)) {
                    $count++;
                }
            }
        }

        return $count;
    }

    /**
     * Updates the inserted parts quantity for the current
     * item's assembly.
     *
     * @param int|string|Model $part
     * @param int|float|string $quantity
     * @param array $extra
     *
     * @return $this|bool
     * @throws InvalidQuantityException
     *
     */
    public function updateAssemblyItem($part, $quantity = 1, array $extra = []): bool|static
    {
        if ($this->isValidQuantity($quantity)) {
            $id = $part;

            if ($part instanceof Model) {
                $id = $part->getKey();
            }

            $attributes = array_merge(['quantity' => $quantity], $extra);

            if ($this->assemblies()->updateExistingPivot($id, $attributes)) {
                $this->fireEvent('inventory.assembly.part-updated', [
                    'item' => $this,
                    'part' => $part,
                ]);

                $this->forgetCachedAssemblyItems();

                return $this;
            }
        }

        return false;
    }

    /**
     * Removes multiple parts from the current items assembly.
     *
     * @param array $parts
     *
     * @return int
     */
    public function removeAssemblyItems(array $parts)
    {
        $count = 0;

        if (count($parts) > 0) {
            foreach ($parts as $part) {
                if ($this->removeAssemblyItem($part)) {
                    $count++;
                }
            }
        }

        return $count;
    }

    /**
     * Removes the specified part from
     * the current items assembly.
     *
     * @param int|string|Model $part
     *
     * @return bool
     */
    public function removeAssemblyItem($part)
    {
        if ($this->assemblies()->detach($part)) {
            $this->fireEvent('inventory.assembly.part-removed', [
                'item' => $this,
                'part' => $part,
            ]);

            $this->forgetCachedAssemblyItems();

            return true;
        }

        return false;
    }

    /**
     * Scopes the current query to only retrieve
     * inventory items that are an assembly.
     *
     * @param $query
     *
     * @return mixed
     */
    public function scopeAssembly(Builder $query)
    {
        return $query->where('is_assembly', '=', true);
    }

    /**
     * Validates the value and key of the values
     * from the assemblies item list to verify that
     * it does not equal the current items ID.
     *
     * @param mixed $value
     * @param int|string $key
     *
     * @throws InvalidPartException
     */
    private function validatePartAgainstList($value, $key)
    {
        if ($key === $this->getKeyName()) {
            if ((int)$value === (int)$this->getKey()) {
                $message = 'The inserted part exists inside the assembly tree. An item cannot be an assembly of itself.';

                throw new InvalidPartException($message);
            }
        }
    }
}
