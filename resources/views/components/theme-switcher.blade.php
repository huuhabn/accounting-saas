@php
    // $alignment = $theme_switcher->getPosition()->value;
@endphp

@if (
    filament()->hasDarkMode() &&
    (! filament()->hasDarkModeForced())
)
    <div
        x-data="{ theme: null }"
        x-init="
            $watch('theme', () => {
                $dispatch('theme-changed', theme)
            })

            theme = localStorage.getItem('theme') || @js(filament()->getDefaultThemeMode()->value)
        "
        class="fi-theme-switcher grid grid-flow-col gap-x-1"
    >
        <x-filament-panels::theme-switcher.button
            icon="heroicon-m-sun"
            theme="light"
        />

        <x-filament-panels::theme-switcher.button
            icon="heroicon-m-moon"
            theme="dark"
        />
    </div>
@endif
