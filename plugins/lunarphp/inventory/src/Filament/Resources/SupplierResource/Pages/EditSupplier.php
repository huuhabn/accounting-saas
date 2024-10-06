<?php

namespace HS\Inventory\Filament\Resources\SupplierResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use HS\Inventory\Filament\Resources\SupplierResource;

class EditSupplier extends EditRecord
{
    protected static string $resource = SupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
