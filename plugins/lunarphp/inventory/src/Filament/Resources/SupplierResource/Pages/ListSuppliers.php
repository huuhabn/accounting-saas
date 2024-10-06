<?php

namespace HS\Inventory\Filament\Resources\SupplierResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use HS\Inventory\Filament\Resources\SupplierResource;

class ListSuppliers extends ListRecords
{
    protected static string $resource = SupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
