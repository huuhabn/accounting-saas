<?php

namespace HS\Inventory\Filament\Resources\InventoryStockMovementResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use HS\Inventory\Filament\Resources\InventoryStockMovementResource;

class EditInventoryStockMovement extends EditRecord
{
    protected static string $resource = InventoryStockMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
