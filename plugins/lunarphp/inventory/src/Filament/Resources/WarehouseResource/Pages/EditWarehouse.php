<?php

namespace HS\Inventory\Filament\Resources\WarehouseResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use HS\Inventory\Filament\Resources\WarehouseResource;

class EditWarehouse extends EditRecord
{
    protected static string $resource = WarehouseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
