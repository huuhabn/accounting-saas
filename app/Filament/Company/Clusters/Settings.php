<?php

namespace App\Filament\Company\Clusters;

use Filament\Clusters\Cluster;
use Illuminate\Contracts\Support\Htmlable;

class Settings extends Cluster
{
    protected static ?string $title = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    public function getTitle(): string|Htmlable
    {
        return translate(static::$title);
    }

    public static function getNavigationLabel(): string
    {
        return translate(static::$title);
    }
}
