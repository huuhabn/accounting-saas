<?php

namespace App\Filament\Components;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;

class ThemeSwitcher implements Plugin
{
    protected string $view = 'components.theme-switcher';

    protected string $renderHook = PanelsRenderHook::USER_MENU_BEFORE;

    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'theme-switcher';
    }

    public function register(Panel $panel): void
    {
        if (filament()->hasDarkMode() && (! filament()->hasDarkModeForced())) {
            $panel->renderHook(
                name: $this->getRenderHook(),
                hook: fn (): View => view($this->view, [
                    'theme_switcher' => $this,
                ])
            );
        }
    }

    public function boot(Panel $panel): void
    {
        debugbar()->info('ThemeSwitcher boot');
    }

    public function renderHook(string $hook): static
    {

        $this->renderHook = $hook;

        return $this;
    }

    public function getRenderHook(): string
    {
        return $this->renderHook;
    }
}
