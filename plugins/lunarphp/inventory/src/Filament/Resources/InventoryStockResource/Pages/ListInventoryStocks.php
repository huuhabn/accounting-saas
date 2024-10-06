<?php

namespace HS\Inventory\Filament\Resources\InventoryStockResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HS\Inventory\Filament\Resources\InventoryStockResource;

class ListInventoryStocks extends ListRecords
{
    protected static string $resource = InventoryStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
