<?php

namespace App\Filament\Components;

use Closure;
use Filament\Panel;
use Filament\Support\Components\Component;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;

class PanelSwitcher extends Component
{
    protected static string $view = 'components.panel-switcher';

    protected array | Closure $excludes = [];

    protected bool | Closure | null $visible = null;

    protected bool | Closure | null $canSwitchPanel = true;

    protected bool | Closure $isCircle = false;


    protected array | Closure $labels = [];

    protected string $renderHook = PanelsRenderHook::GLOBAL_SEARCH_AFTER;

    public static function boot(): static
    {
        $static = app(static::class);

        $static->visible(function () {
            if (($user = auth()->user()) === null) {
                return false;
            }

            if (method_exists($user, 'canAccessPanel')) {
                return $user->canAccessPanel(filament()->getCurrentPanel() ?? filament()->getDefaultPanel());
            }

            return true;
        });

        $static->configure();

        FilamentView::registerRenderHook(
            name: $static->getRenderHook(),
            hook: function () use ($static) {
                if (! $static->isVisible()) {
                    return '';
                }

                return view(static::$view, [
                    'panelSwitcher' => $static,
                ]);
            },
        );

        return $static;
    }

    public function canSwitchPanels(bool | Closure $condition): static
    {
        $this->canSwitchPanel = $condition;

        return $this;
    }

    public function excludes(array | Closure $panelIds): static
    {
        $this->excludes = $panelIds;

        return $this;
    }

    public function labels(array | Closure $labels): static
    {
        $this->labels = $labels;

        return $this;
    }

    public function renderHook(string $hook): static
    {
        $this->renderHook = $hook;

        return $this;
    }

    public function circle(bool | Closure $condition = true): static
    {
        $this->isCircle = $condition;

        return $this;
    }

    public function visible(bool | Closure $visible): static
    {
        $this->visible = $visible;

        return $this;
    }

    public function getExcludes(): array
    {
        return (array) $this->evaluate($this->excludes);
    }

    public function getLabels(): array
    {
        return (array) $this->evaluate($this->labels);
    }

    public function isAbleToSwitchPanels(): bool
    {
        if (($user = auth()->user()) === null) {
            return false;
        }

        if (method_exists($user, 'canSwitchPanels')) {
            return $user->canSwitchPanels();
        }

        return $this->evaluate($this->canSwitchPanel);
    }

    public function isVisible(): bool
    {
        return (bool) $this->evaluate($this->visible);
    }

    public function isCircle(): bool
    {
        return (bool) $this->evaluate($this->isCircle);
    }

    /**
     * @return array<string, Panel>
     */
    public function getPanels(): array
    {
        return collect(filament()->getPanels())
            ->reject(fn (Panel $panel) => in_array($panel->getId(), $this->getExcludes()))
            ->toArray();
    }

    public function getCurrentPanel(): Panel
    {
        return filament()->getCurrentPanel();
    }

    public function getPanelUrl(Panel $panel): string
    {
        $currentPanel = $this->getCurrentPanel();
        filament()->setCurrentPanel($panel);
        $panelUrl = $panel->getUrl();
        filament()->setCurrentPanel($currentPanel);

        return $panelUrl;
    }

    public function getAvatarUrl(?string $panelName = null): string
    {
        if (empty($panelName)) {
            $currentPanel = $this->getCurrentPanel();
            $panelName = $currentPanel->getId();
        }

        $backgroundColor = str(
            \Spatie\Color\Rgb::fromString('rgb(' . \Filament\Support\Facades\FilamentColor::getColors()['primary'][600] . ')')->toHex()
        )->after('#');

        return 'https://ui-avatars.com/api/?name=' . urlencode($panelName) . '&color=FFFFFF&background=' . $backgroundColor;
    }

    public function getRenderHook(): string
    {
        return $this->renderHook;
    }
}
