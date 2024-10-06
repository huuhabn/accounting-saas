@php
    $options = $getOptionsForJs();
    $selected = $getState();
    $height = $getHeight();
    $showLabels = $isLabelsVisible();
    $optionsLabel = $getOptionsLabel();
    $selectedLabel = $getSelectedLabel();
    $statePath = $getStatePath();
@endphp
<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div class="flex flex-col gap-2" x-ignore ax-load
        ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('fa-combobox', 'huuhabn/filament-advanced') }}"
        x-data="FAComboBoxFormComponent({ options: @js($options), selected: @js($selected), wire: $wire, statePath: @js($statePath) })" wire:ignore>

        @if ($showLabels)
            <div class="w-full flex">
                <div class="w-1/2">
                    <x-filament-advanced::box-label :label="$optionsLabel" />
                </div>
                <div class="w-20"></div>
                <div class="w-1/2">
                    <x-filament-advanced::box-label :label="$selectedLabel" />
                </div>

            </div>
        @endif
        @if ($showBoxSearchs())
            <div class="w-full flex">
                <div class="w-1/2">
                    <x-filament-advanced::box-input-search :target="'options'" :disabled="$isDisabled()" />
                </div>
                <div class="w-20"></div>
                <div class="w-1/2">
                    <x-filament-advanced::box-input-search :target="'selected'" :disabled="$isDisabled()" />
                </div>

            </div>
        @endif
        <div class="w-full flex min-h-36" style="height: {{ $height }}">
            <div class="w-1/2 h-full">
                <x-filament-advanced::box-items :target="'options'" :disabled="$isDisabled()" />
            </div>
            <div class="w-20 flex flex-col gap-1 p-2 justify-center items-center">
                <x-filament::icon-button
                    icon="heroicon-m-chevron-right"
                    x-on:click="moveToRight"
                    :disabled="$isDisabled()"
                />
                <x-filament::icon-button
                    icon="heroicon-m-chevron-double-right"
                    x-on:click="moveToRightAll"
                    :disabled="$isDisabled()"
                />
                <x-filament::icon-button
                    icon="heroicon-m-chevron-left"
                    x-on:click="moveToLeft"
                    :disabled="$isDisabled()"
                />
                <x-filament::icon-button
                    icon="heroicon-m-chevron-double-left"
                    x-on:click="moveToLeftAll"
                    :disabled="$isDisabled()"
                />
            </div>
            <div class="w-1/2 h-full">
                <x-filament-advanced::box-items :target="'selected'" :disabled="$isDisabled()" />
            </div>
        </div>

    </div>
</x-dynamic-component>
