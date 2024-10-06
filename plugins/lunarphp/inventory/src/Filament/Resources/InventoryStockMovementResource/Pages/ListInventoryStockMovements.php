<?php

namespace HS\Inventory\Filament\Resources\InventoryStockMovementResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HS\Inventory\Filament\Resources\InventoryStockMovementResource;

class ListInventoryStockMovements extends ListRecords
{
    protected static string $resource = InventoryStockMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
