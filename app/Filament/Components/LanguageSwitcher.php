<?php

namespace App\Filament\Components;

use Closure;
use Exception;
use Filament\Panel;
use App\Events\LangChanged;
use App\Enums\DropdownPlacement;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Filament\Support\Components\Component;
use Filament\Support\Facades\FilamentView;

class LanguageSwitcher extends Component
{
    protected ?string $displayLocale = null;

    protected array | Closure $excludes = [];

    protected array | Closure $flags = [];

    protected bool $flagsOnly = false;

    protected bool | Closure $isCircular = true;

    protected array | Closure $labels = [];

    protected array | Closure $locales = [];

    protected bool $nativeLabel = false;

    protected ?DropdownPlacement $placement = null;

    protected bool | Closure $visibleInsidePanels = false;

    protected Closure | string $renderHook = PanelsRenderHook::GLOBAL_SEARCH_AFTER;

    protected static string $view = 'components.language-switcher';

    protected Closure | string | null $userPreferredLocale = null;

    public static function make(): static
    {
        $static = app(static::class);

        $static->visible();

        $static->displayLocale();

        $static->configure();

        return $static;
    }

    public static function boot(): void
    {
        $static = static::make();

        if ($static->isVisibleInsidePanels()) {
            FilamentView::registerRenderHook(
                name: $static->getRenderHook(),
                hook: fn (): string => Blade::render('<livewire:language-switcher/>')
            );
        }
    }

    public function circular(bool $condition = true): static
    {
        $this->isCircular = $condition;

        return $this;
    }

    public function displayLocale(?string $locale = null): static
    {
        $this->displayLocale = $locale ?? app()->getLocale();

        return $this;
    }

    public function nativeLabel(bool $condition = true): static
    {
        $this->nativeLabel = $condition;

        return $this;
    }

    public function excludes(array | Closure $excludes): static
    {
        $this->excludes = $excludes;

        return $this;
    }

    public function flagsOnly(bool $condition = true): static
    {
        $this->flagsOnly = $condition;

        return $this;
    }

    public function labels(array | Closure $labels): static
    {
        $this->labels = $labels;

        return $this;
    }

    public function locales(array | Closure $locales): static
    {
        if (array_is_list($locales)){
            $this->locales = $locales;
        } else {
            $this->locales = array_keys($locales);
            $this->flags = $locales;
        }

        return $this;
    }

    public function placement(DropdownPlacement $placement): static
    {
        $this->placement = $placement;

        return $this;
    }

    public function renderHook(string $hook): static
    {
        $this->renderHook = $hook;

        return $this;
    }

    public function userPreferredLocale(Closure | string | null $locale): static
    {
        $this->userPreferredLocale = $locale;

        return $this;
    }

    public function visible(bool | Closure $insidePanels = true): static
    {
        $this->visibleInsidePanels = $insidePanels;

        return $this;
    }

    public function getDisplayLocale(): ?string
    {
        return $this->evaluate($this->displayLocale);
    }

    public function getExcludes(): array
    {
        return (array) $this->evaluate($this->excludes);
    }

    /**
     * @throws Exception
     */
    public function getFlags(): array
    {
        $flagUrls = (array) $this->evaluate($this->flags);

        foreach ($flagUrls as $url) {
            if (! filter_var($url, FILTER_VALIDATE_URL)) {
                throw new \Exception('Invalid flag url');
                exit;
            }
        }

        return $flagUrls;
    }

    public function isCircular(): bool
    {
        return (bool) $this->evaluate($this->isCircular);
    }

    /**
     * @throws Exception
     */
    public function isFlagsOnly(): bool
    {
        return $this->evaluate($this->flagsOnly) && filled($this->getFlags());
    }

    public function isVisibleInsidePanels(): bool
    {
        return ($this->evaluate($this->visibleInsidePanels)
            && count($this->locales) > 1
            && $this->isCurrentPanelIncluded());
    }

    public function getLabels(): array
    {
        return (array) $this->evaluate($this->labels);
    }

    public function getLocales(): array
    {
        return (array) $this->evaluate($this->locales);
    }

    public function getNativeLabel(): bool
    {
        return (bool) $this->evaluate($this->nativeLabel);
    }

    /**
     * @throws Exception
     */
    public function getPlacement(): DropdownPlacement
    {
        if ($this->flagsOnly()){
            return DropdownPlacement::Bottom;
        }

        return $this->placement ?? DropdownPlacement::BottomEnd;
    }

    public function getRenderHook(): string
    {
        return (string) $this->evaluate($this->renderHook);
    }

    public function getUserPreferredLocale(): ?string
    {
        return $this->evaluate($this->userPreferredLocale) ?? null;
    }

    public function getPreferredLocale(): string
    {
        $locale = session()->get('locale') ??
            request()->get('locale') ??
            request()->cookie('filament_language_switch_locale') ??
            $this->getUserPreferredLocale() ??
            config('app.locale', 'en') ??
            request()->getPreferredLanguage();

        return in_array($locale, $this->getLocales(), true) ? $locale : config('app.locale');
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

    public function getFlag(string $locale): string
    {
        return $this->flags[$locale] ?? str($locale)->upper()->toString();
    }

    public function getLabel(string $locale): string
    {
        if (array_key_exists($locale, $this->labels) && ! $this->getNativeLabel()) {
            return strval($this->labels[$locale]);
        }

        return str(
            locale_get_display_name(
                locale: $locale,
                displayLocale: $this->getNativeLabel() ? $locale : $this->getDisplayLocale()
            )
        )
            ->title()
            ->toString();
    }

    public function isCurrentPanelIncluded(): bool
    {
        return array_key_exists($this->getCurrentPanel()->getId(), $this->getPanels());
    }

    public static function trigger(string $locale)
    {
        session()->put('locale', $locale);

        cookie()->queue(cookie()->forever('filament_language_switcher_locale', $locale));

        event(new LangChanged($locale));

        return redirect(request()->header('Referer'));
    }
}
