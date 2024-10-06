<?php

namespace HanaSales\FilamentAdvanced\Tables\Actions;

use Filament\Tables\Actions\Action;
use HanaSales\FilamentAdvanced\Concerns\Impersonates;

class Impersonate extends Action
{
    use Impersonates;

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('filament-advanced::impersonate.label'))
            ->iconButton()
            ->icon('impersonate-icon')
            ->action(fn ($record) => $this->impersonate($record))
            ->hidden(fn ($record) => !$this->canBeImpersonated($record));
    }
}
