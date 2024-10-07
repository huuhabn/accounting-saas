<?php

namespace App\Filament\User\Clusters;

use Filament\Clusters\Cluster;
use Illuminate\Contracts\Support\Htmlable;

class Account extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-s-user';

    protected static ?string $title = 'My Account';

    public static function getNavigationUrl(): string
    {
        return static::getUrl(panel: 'user');
    }

    public function getTitle(): string | Htmlable
    {
        return translate(static::$title);
    }

    public static function getNavigationLabel(): string
    {
        return translate(static::$title);
    }
}
