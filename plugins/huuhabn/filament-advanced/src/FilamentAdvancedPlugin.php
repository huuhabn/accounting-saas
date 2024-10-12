<?php

namespace HanaSales\FilamentAdvanced;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\Css;
use Illuminate\Support\HtmlString;
use Filament\Support\Assets\Asset;
use Illuminate\Support\Facades\Cache;
use HanaSales\FilamentAdvanced\Dto\Image;
use Filament\Support\Facades\FilamentAsset;
use HanaSales\FilamentAdvanced\Concerns\BgImage;

class FilamentAdvancedPlugin implements Plugin
{
    protected Panel $panel;
    protected \DateInterval | \DateTimeInterface | int $ttl = 0;
    protected bool $showAttribution = true;
    protected string $bg_type = 'jpg';

    public function getId(): string
    {
        return 'filament-advanced';
    }

    public function register(Panel $panel): void
    {
        $this->panel = $panel;
    }

    public function boot(Panel $panel): void
    {
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerCssVariables(
            $this->getCssVariables(),
            $this->getAssetPackageName()
        );
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    protected function getAssetPackageName(): string
    {
        return 'huuhabn/filament-advanced';
    }

    /**
     * @return list<Asset>
     */
    protected function getAssets(): array
    {
        return [
            Css::make('fa-backgrounds', __DIR__ . '/../resources/dist/fa-backgrounds.css'),
        ];
    }

    /**
     * @return array<string, HtmlString>
     */
    protected function getCssVariables(): array
    {
        $image = $this->getImage();

        return array_filter([
            'fa-backgrounds-image' => new HtmlString($image->image),
            'fa-backgrounds-attribution' => $this->showAttribution && $image->attribution ? new HtmlString('"' . addslashes($image->attribution) . '"') : null,
            'fa-backgrounds-attribution-backdrop' => $this->showAttribution && $image->attribution ? new HtmlString('""') : null,
        ]);
    }
    public function bgType(string $type): static
    {
        $this->bg_type = $type;

        return $this;
    }

    protected function getImage(): Image
    {
        return Cache::remember(
            'fa-backgrounds:image:' . $this->panel->getId(),
            $this->ttl,
            fn () => $this->bg_type == 'svg' ? BgImage::make()->getTrianglesImage() : BgImage::make()->getImage()
        );
    }


    public function remember(\DateInterval | \DateTimeInterface | int $ttl): static
    {
        $this->ttl = $ttl;

        return $this;
    }

    public function showAttribution(bool $showAttribution): static
    {
        $this->showAttribution = $showAttribution;

        return $this;
    }
}
