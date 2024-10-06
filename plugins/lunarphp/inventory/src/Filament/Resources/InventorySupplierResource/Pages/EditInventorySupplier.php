<?php

namespace HS\Inventory\Filament\Resources\InventorySupplierResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use HS\Inventory\Filament\Resources\InventorySupplierResource;

class EditInventorySupplier extends EditRecord
{
    protected static string $resource = InventorySupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
