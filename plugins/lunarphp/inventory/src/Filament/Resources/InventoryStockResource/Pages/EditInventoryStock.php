<?php

namespace HS\Inventory\Filament\Resources\InventoryStockResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use HS\Inventory\Filament\Resources\InventoryStockResource;

class EditInventoryStock extends EditRecord
{
    protected static string $resource = InventoryStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
