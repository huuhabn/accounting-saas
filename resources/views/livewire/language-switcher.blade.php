@php
    $languageSwitch = \App\Filament\Components\LanguageSwitcher::make();
    $locales = $languageSwitch->getLocales();
    $isCircular = $languageSwitch->isCircular();
    $isFlagsOnly = $languageSwitch->isFlagsOnly();
    $hasFlags = filled($languageSwitch->getFlags());
    $showFlags = true;
    $isVisibleOutsidePanels = $languageSwitch->isVisibleOutsidePanels();
    $placement = $languageSwitch->getOutsidePanelPlacement()->value;
@endphp
<x-filament::dropdown
    teleport
    :placement="$placement"
    class="fi-dropdown fi-user-menu"
{{--    :width="$isFlagsOnly ? 'flags-only' : 'xs'"--}}
>
    <x-slot name="trigger">
        <div
            @class([
                'flex flex-1 items-center justify-center gap-3 rounded-lg outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5 bg-gray-100 dark:bg-white/5',
                'rounded-full' => $isCircular,
                'rounded-lg' => !$isCircular,
                'ring-2 ring-inset ring-gray-200 hover:ring-gray-300 dark:ring-gray-500 hover:dark:ring-gray-400' => $isFlagsOnly || $hasFlags,
            ])
            x-tooltip="{
                content: @js($languageSwitch->getLabel(app()->getLocale())),
                theme: $store.theme,
                placement: 'bottom'
            }"
        >
            @if ($isFlagsOnly || $hasFlags)
                <x-filament::avatar
                    :src="$languageSwitch->getFlag(app()->getLocale())"
                    size="w-6 h-6"
                    :circular="$isCircular"
                    :alt="$languageSwitch->getLabel(app()->getLocale())"
                />
            @else
                <span class="font-semibold text-md">{{ getCharAvatar(app()->getLocale()) }}</span>
            @endif
        </div>
    </x-slot>

    <x-filament::dropdown.list
        style="{{ $isFlagsOnly ? 'max-width: 3rem !important;' : '' }}"
        @class([
            '!border-t-0 space-y-2 !p-2.5',
            'flex flex-col' => !$isFlagsOnly
        ])
    >
        @foreach ($locales as $locale)
            @if (!app()->isLocale($locale))
                <button
                    type="button"
                    wire:click="changeLocale('{{ $locale }}')"
                    @if ($isFlagsOnly)
                        x-tooltip="{
                        content: @js($languageSwitch->getLabel($locale)),
                        theme: $store.theme,
                        placement: 'right'
                    }"
                    @endif

                    @class([
                        'text-gray-700 dark:text-gray-200 text-sm font-medium flex items-center p-2 rounded-lg hover:bg-gray-50 focus-visible:bg-gray-50 dark:hover:bg-white/5 dark:focus-visible:bg-white/5',
                        'justify-center px-2 py-0.5' => $isFlagsOnly,
                        'justify-start space-x-2 rtl:space-x-reverse p-1' => !$isFlagsOnly,
                    ])
                >

                    @if ($isFlagsOnly)
                        <x-filament::avatar
                            :src="$languageSwitch->getFlag($locale)"
                            size="w-6 h-6"
                            :circular="$isCircular"
                            :alt="$languageSwitch->getLabel($locale)"
                        />
                    @else
                        @if ($hasFlags)
                            <x-filament::avatar
                                :src="$languageSwitch->getFlag($locale)"
                                size="w-6 h-6"
                                :circular="$isCircular"
                                :alt="$languageSwitch->getLabel($locale)"
                            />
                        @else
                            <span
                                @class([
                                    'flex items-center justify-center flex-shrink-0 w-6 h-6 p-2 text-xs font-semibold group-hover:bg-white group-hover:text-primary-600 group-hover:border group-hover:border-primary-500/10 group-focus:text-white bg-primary-500/10 text-primary-600',
                                    'rounded-full' => $isCircular,
                                    'rounded-lg' => !$isCircular,
                                ])
                            >
                                {{ getCharAvatar($locale) }}
                            </span>
                        @endif
                        <span class="text-sm font-medium text-gray-600 hover:bg-transparent dark:text-gray-200">
                            {{ $languageSwitch->getLabel($locale) }}
                        </span>

                    @endif
                </button>
            @endif
        @endforeach
    </x-filament::dropdown.list>
</x-filament::dropdown>
