<?php

namespace HS\Inventory;

use HS\Inventory\Console\Install;
use Illuminate\Filesystem\Filesystem;
use Spatie\LaravelPackageTools\Package;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Facades\FilamentAsset;
use HS\Inventory\Managers\InventoryManager;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use HS\Inventory\Interfaces\InventoryManagerInterface;
use Spatie\LaravelPackageTools\Commands\InstallCommand;

class InventoryServiceProvider extends PackageServiceProvider
{
    public static string $name = 'inventory';

    public static string $viewNamespace = 'inventory';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasCommands($this->getCommands());

        $this->mergeConfigFrom(__DIR__."/../config/inventory.php", "hs.inventory");

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'hs-inventory');
    }

    public function packageRegistered(): void
    {
//        \Lunar\Facades\ModelManifest::replace(
//            \Lunar\Models\Contracts\ProductVariant::class,
//            \HS\Inventory\Models\ProductVariant::class,
//        );

        $this->app->bind(InventoryManagerInterface::class, function ($app) {
            return $app->make(InventoryManager::class);
        });
    }

    public function packageBooted(): void
    {
        if (app()->runningInConsole()) {

            $packageName = $this->package->shortName();

            $this->publishes([
                __DIR__."/../config/inventory.php" => config_path("hs/inventory.php"),
            ], "{$packageName}-config");

            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations'),
            ], "{$packageName}-migrations");

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/hs'),
            ], "{$packageName}-views");

            $this->publishes([
                __DIR__.'/../resources/lang' => lang_path('hs'),
            ], "{$packageName}-lang");


            // Publish images
//            foreach (app(Filesystem::class)->directories(__DIR__ . '/../resources/img/') as $directory) {
//                $this->publishes([
//                    $directory => public_path( 'img/' . $this->package->shortName() . '/' . basename($directory)),
//                ], "{$this->package->shortName()}-assets");
//            }
        }
    }

    protected function registerRenderHook(): void
    {
        //
    }

    protected function registerClassAliases(): void
    {
        //
    }

    protected function registerAssets(): void
    {
        FilamentAsset::register($this->getAssets(), $this->getAssetPackageName());
        FilamentAsset::registerScriptData($this->getScriptData(), $this->getAssetPackageName());
        FilamentIcon::register($this->getIcons());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'inventory';
    }

    protected function getAssets(): array
    {
        return [];
    }

    protected function getCommands(): array
    {
        return [
            Install::class,
        ];
    }

    protected function getIcons(): array
    {
        return [];
    }

    protected function getScriptData(): array
    {
        return [];
    }

}
