<?php

namespace HanaSales\FilamentAdvanced\Pages\Actions;

use Filament\Actions\Action;
use HanaSales\FilamentAdvanced\Concerns\Impersonates;

class Impersonate extends Action
{
    use Impersonates;

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('filament-advanced::lang.impersonate.label'))
            ->icon('impersonate-icon')
            ->action(fn ($record) => $this->impersonate($record))
            ->hidden(fn ($record) => !$this->canBeImpersonated($record));
    }
}
