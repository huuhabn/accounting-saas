<?php

namespace HS\Inventory\Traits;

use DateTime;
use HS\Inventory\Models\Warehouse;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait HasWarehouse.
 */
trait HasWarehouse
{
    public static function bootHasChannels()
    {
        static::created(function (Model $model) {
            // Add our initial warehouses
            $warehouses = Warehouse::get()->mapWithKeys(function ($warehouses) {
                return [
                    $warehouses->id => [
                        'enabled' => $warehouses->default,
                        'starts_at' => $warehouses->default ? now() : null,
                        'ends_at' => null,
                    ],
                ];
            });

            $model->warehouses()->sync($warehouses);
        });
    }

    /**
     * Get all of the models warehouses.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany<Warehouse>
     */
    public function warehouses(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        $prefix = config('lunar.database.table_prefix');

        return $this->morphToMany(
            Warehouse::class,
            'warehouseable',
            "{$prefix}warehouseables",
        )->withPivot([
            'enabled',
            'starts_at',
            'ends_at',
        ])->withTimestamps();
    }

    /**
     * Apply the warehouse scope to the query
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeWarehouse($query, Warehouse|iterable|null $warehouse = null, ?DateTime $startsAt = null, ?DateTime $endsAt = null): Builder
    {
        if (blank($warehouse)) {
            return $query;
        }

        if (! $startsAt) {
            $startsAt = now();
        }

        if (! $endsAt) {
            $endsAt = now()->addSecond();
        }

        $warehouseIds = collect();

        if (is_a($warehouse, Warehouse::class)) {
            $warehouseIds = collect([$warehouse->id]);
        }

        if (is_a($warehouse, Collection::class)) {
            $warehouseIds = $warehouse->pluck('id');
        }

        if (is_array($warehouse)) {
            $warehouseIds = collect($warehouse)->pluck('id');
        }

        return $query->whereHas('warehouses', function ($relation) use ($warehouseIds, $startsAt, $endsAt) {
            $relation->whereIn(
                $this->warehouses()->getTable().'.warehouse_id',
                $warehouseIds
            )->where(function ($query) use ($startsAt) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', $startsAt);
            })->where(function ($query) use ($endsAt) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', $endsAt);
            })->whereEnabled(true);
        });
    }

    /**
     * @param Warehouse|null $warehouse
     * @return Warehouse
     */
    public function getWarehouse(Warehouse $warehouse = null): Warehouse
    {
        if (! $warehouse){
            return Warehouse::getDefault();
        }

        return $warehouse;
    }
}
