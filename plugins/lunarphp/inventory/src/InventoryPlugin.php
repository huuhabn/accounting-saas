<?php

namespace HS\Inventory;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Navigation\NavigationGroup;
use Filament\Support\Facades\FilamentIcon;
use HS\Inventory\Models\InventoryStockEntry;
use HS\Inventory\Filament\Resources\UnitResource;
use HS\Inventory\Filament\Resources\SupplierResource;
use HS\Inventory\Filament\Resources\WarehouseResource;
use HS\Inventory\Filament\Resources\PurchaseOrderResource;
use HS\Inventory\Filament\Resources\InventoryStockResource;
use HS\Inventory\Filament\Resources\InventoryStockEntryResource;
use HS\Inventory\Filament\Resources\InventoryStockMovementResource;

class InventoryPlugin implements Plugin
{
    public function getId(): string
    {
        return 'inventory';
    }

    public function register(Panel $panel): void
    {
        if (! config('hs.inventory.enabled')) {
            return;
        }



        $panel->navigationGroups([
                NavigationGroup::make()
                    ->label(fn () => __('hs-inventory::lang.section'))
                    ->collapsed(),

            ])
            ->resources([
                PurchaseOrderResource::class,
                InventoryStockResource::class,
                InventoryStockEntryResource::class,
                InventoryStockMovementResource::class,
                SupplierResource::class,
                WarehouseResource::class,
                UnitResource::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
