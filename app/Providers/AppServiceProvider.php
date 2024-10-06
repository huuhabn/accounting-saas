<?php

namespace App\Providers;

use Filament\Panel;
use App\Services\DateRangeService;
use Lunar\Shipping\ShippingPlugin;
use Lunar\Admin\Support\Facades\LunarPanel;
use HanaSales\FilamentAdvanced\FilamentAdvancedPlugin;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Notifications\Livewire\Notifications;
use Filament\Support\Assets\Js;
use Filament\Support\Enums\Alignment;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        LunarPanel::panel(
            function (Panel $panel) {
                return $panel
                    ->plugins([
                        new ShippingPlugin,
                        FilamentAdvancedPlugin::make()
                            ->bgType('svg')
                    ]);
            }
        )
            ->register();
        $this->app->singleton(DateRangeService::class);
        $this->app->singleton(LoginResponse::class, \App\Http\Responses\LoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Notifications::alignment(Alignment::Center);

        FilamentAsset::register([
            Js::make('TopNavigation', __DIR__ . '/../../resources/js/TopNavigation.js'),
        ]);
    }
}
