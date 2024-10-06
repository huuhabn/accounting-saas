<?php

namespace HS\Inventory\Filament\Resources\InventoryStockEntryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use HS\Inventory\Filament\Resources\InventoryStockEntryResource;

class EditInventoryStockEntry extends EditRecord
{
    protected static string $resource = InventoryStockEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
