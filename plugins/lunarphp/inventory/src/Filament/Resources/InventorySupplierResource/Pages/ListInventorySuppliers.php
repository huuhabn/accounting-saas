<?php

namespace HS\Inventory\Filament\Resources\InventorySupplierResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HS\Inventory\Filament\Resources\InventorySupplierResource;

class ListInventorySuppliers extends ListRecords
{
    protected static string $resource = InventorySupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
