<?php

namespace HanaSales\FilamentAdvanced;

use BladeUI\Icons\Factory;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Blade;
use Filament\Support\Facades\FilamentView;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Lab404\Impersonate\Events\TakeImpersonation;
use Lab404\Impersonate\Events\LeaveImpersonation;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use HanaSales\FilamentAdvanced\Tables\Actions\Impersonate;
use HanaSales\FilamentAdvanced\Commands\FilamentAdvancedCommand;
use HanaSales\FilamentAdvanced\Testing\TestsFilamentAdvanced;

class FilamentAdvancedServiceProvider extends PackageServiceProvider
{
    protected static string $name = 'filament-advanced';
    protected static string $viewNamespace = 'filament-advanced';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasRoute('web')
            ->hasCommands($this->getCommands())
            ->hasInstallCommand($this->getInstallCommandConfig());

        $this->configurePackageResources($package);
    }

    public function packageRegistered(): void
    {
        Event::listen(TakeImpersonation::class, fn () => $this->clearAuthHashes());
        Event::listen(LeaveImpersonation::class, fn () => $this->clearAuthHashes());

        $this->registerIcon();
    }

    public function bootingPackage(): void
    {
        $this->registerRenderHook();
        $this->loadViewsFrom(__DIR__.'/../resources/views', static::$viewNamespace);
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', static::$viewNamespace);

        $this->registerClassAliases();
    }

    public function packageBooted(): void
    {
        $this->registerAssets();
        $this->publishAssets();
        $this->registerTesting();
    }

    protected function getInstallCommandConfig(): callable
    {
        return function (InstallCommand $command) {
            $command
                ->publishConfigFile()
                ->publishMigrations()
                ->askToRunMigrations();
        };
    }

    protected function configurePackageResources(Package $package): void
    {
        $configFileName = $package->shortName();
        $packagePath = $package->basePath('/../');

        if (file_exists($packagePath . "config/{$configFileName}.php")) {
            $package->hasConfigFile();
        }

        if (file_exists($packagePath . 'database/migrations')) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($packagePath . 'resources/lang')) {
            $package->hasTranslations();
        }

        if (file_exists($packagePath . 'resources/views')) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    protected function registerRenderHook(): void
    {
        FilamentView::registerRenderHook(
            config('filament-advanced.impersonate.banner.render_hook', 'panels::body.start'),
            fn (): string => Blade::render("<x-filament-advanced::impersonate-banner/>")
        );
    }

    protected function registerClassAliases(): void
    {
        if (!class_exists(\HanaSales\FilamentAdvanced\Impersonate::class)) {
            class_alias(Impersonate::class, \HanaSales\FilamentAdvanced\Impersonate::class);
        }
    }

    protected function registerAssets(): void
    {
        FilamentAsset::register($this->getAssets(), $this->getAssetPackageName());
        FilamentAsset::registerScriptData($this->getScriptData(), $this->getAssetPackageName());
        FilamentIcon::register($this->getIcons());
    }

    protected function publishAssets(): void
    {
        if (app()->runningInConsole()) {
            // Publish images
            foreach (app(Filesystem::class)->directories(__DIR__ . '/../resources/img/') as $directory) {
                $this->publishes([
                    $directory => public_path( 'img/' . $this->package->shortName() . '/' . basename($directory)),
                ], "{$this->package->shortName()}-assets");
            }
        }
    }

    protected function registerTesting(): void
    {
        Testable::mixin(new TestsFilamentAdvanced);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'huuhabn/filament-advanced';
    }

    protected function getAssets(): array
    {
        return [
            Css::make('fa-backgrounds', __DIR__ . '/../resources/dist/fa-backgrounds.css')->loadedOnRequest(),
            Css::make('filament-advanced-styles', __DIR__ . '/../resources/dist/filament-advanced.css')->loadedOnRequest(),
            Js::make('filament-advanced-scripts', __DIR__ . '/../resources/dist/filament-advanced.js')->loadedOnRequest(),
            AlpineComponent::make('fa-combobox', __DIR__ . '/../resources/dist/fa-combobox.js')->loadedOnRequest(),
        ];
    }

    protected function getCommands(): array
    {
        return [
            FilamentAdvancedCommand::class,
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

    protected function getMigrations(): array
    {
        return [
            'create_filament-advanced_table',
        ];
    }

    protected function clearAuthHashes(): void
    {
        session()->forget(array_unique([
            'password_hash_' . session('impersonate.guard'),
            'password_hash_' . Filament::getCurrentPanel()->getAuthGuard(),
            'password_hash_' . Filament::getPanel(session()->get('impersonate.back_to_panel'))->getAuthGuard(),
            'password_hash_' . auth()->getDefaultDriver(),
            'password_hash_sanctum'
        ]));
    }

    protected function registerIcon(): void
    {
        $this->callAfterResolving(Factory::class, function (Factory $factory) {
            $factory->add('impersonate', [
                'path' => __DIR__.'/../resources/views/icons',
                'prefix' => 'impersonate',
            ]);
        });
    }
}
