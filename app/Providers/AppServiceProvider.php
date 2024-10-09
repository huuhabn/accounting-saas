<?php

namespace App\Providers;

use App\Filament\Components\PanelSwitcher;
use App\Services\DateRangeService;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Notifications\Livewire\Notifications;
use Filament\Panel;
use Filament\Support\Assets\Js;
use Filament\Support\Enums\Alignment;
use Filament\Support\Facades\FilamentAsset;
use HanaSales\FilamentAdvanced\FilamentAdvancedPlugin;
use Illuminate\Support\ServiceProvider;
use Lunar\Admin\Support\Facades\LunarPanel;
use Lunar\Shipping\ShippingPlugin;

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
                            ->bgType('svg'),
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

        //        PanelSwitcher::configureUsing(function (PanelSwitcher $panelSwitch) {
        //            $panelSwitch->circle();
        //        });
        PanelSwitcher::boot();

        FilamentAsset::register([
            Js::make('TopNavigation', __DIR__ . '/../../resources/js/TopNavigation.js'),
        ]);
    }
}
