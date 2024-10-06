<?php

namespace HS\Inventory\Filament\Resources\UnitResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use HS\Inventory\Filament\Resources\UnitResource;

class EditUnit extends EditRecord
{
    protected static string $resource = UnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
