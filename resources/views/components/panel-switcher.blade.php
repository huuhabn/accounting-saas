@php
    $currentPanel = $panelSwitcher->getCurrentPanel();
    $canSwitchPanels = $panelSwitcher->isAbleToSwitchPanels();
    $panels = $panelSwitcher->getPanels();
    $isCircle= $panelSwitcher->isCircle();
    $labels = $panelSwitcher->getLabels();
    $panelName = $labels[$currentPanel->getId()] ?? $currentPanel->getId();
@endphp


<x-filament::dropdown teleport placement="top-start">
    <x-slot name="trigger">
        <button
            type="button"
            class="flex flex-1 -mx-2 items-center justify-center gap-x-3 rounded-lg px-2 py-2 outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5 bg-gray-100 dark:bg-white/5"
        >
            <x-filament::avatar
                :src="$panelSwitcher->getAvatarUrl()"
                size="w-6 h-6"
                :circular="$isCircle"
            />

            <span class="flex-1 text-left text-primary-600 dark:text-primary-400">
                {{ str($panelName)->ucfirst() }}
            </span>

            <x-filament::icon
                icon="heroicon-m-chevron-down"
                icon-alias="panels::panel-switch-simple-icon"
                class="h-5 w-5 transition duration-75 text-primary-600 group-hover:text-primary-500 group-focus-visible:text-primary-500 dark:text-primary-500 dark:group-hover:text-primary-400 dark:group-focus-visible:text-primary-400"
            />

        </button>
    </x-slot>

    <x-filament::dropdown.list>
        <ul class="w-full p-2 list-none flex flex-col space-y-2">
            @foreach ($panels as $panel)
                <x-panel-switcher.item
                    :url="$panelSwitcher->getPanelUrl($panel)"
                    :label="$labels[$panel->getId()] ?? str($panel->getId())->ucfirst()"
                    :image="$panelSwitcher->getAvatarUrl($panel->getId())"
                    :circular="$isCircle"
                />
            @endforeach
        </ul>
    </x-filament::dropdown.list>

</x-filament::dropdown>

