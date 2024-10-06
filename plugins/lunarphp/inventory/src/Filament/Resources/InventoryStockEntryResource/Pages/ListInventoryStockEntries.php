<?php

namespace HS\Inventory\Filament\Resources\InventoryStockEntryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HS\Inventory\Filament\Resources\InventoryStockEntryResource;

class ListInventoryStockEntries extends ListRecords
{
    protected static string $resource = InventoryStockEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
