@php
    $languageSwitch = \App\Filament\Components\LanguageSwitcher::make();
    $locales = $languageSwitch->getLocales();
    $isCircular = $languageSwitch->isCircular();
    $showLabel = $languageSwitch->isShowLabel();
    $flagsOnly = $languageSwitch->isFlagsOnly();
    $hasFlags = filled($languageSwitch->getFlags());
    $placement = $languageSwitch->getPlacement()->value;
@endphp
<x-filament::dropdown
    teleport
    :placement="$placement"
    class="fi-dropdown fi-user-menu"
    :width="$flagsOnly ? '!max-w-[3rem]' : '!max-w-[10rem]'"
>
    <x-slot name="trigger">
        <div
            @class([
                'flex items-center justify-center gap-x-3 p-2 text-sm font-medium outline-none transition duration-75 bg-primary-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5',
                'rounded-full' => $isCircular && !$showLabel,
                'rounded-lg' => !$isCircular || $showLabel,
            ])
            x-tooltip="{
                content: @js($languageSwitch->getLabel(app()->getLocale())),
                theme: $store.theme,
                placement: 'bottom'
            }"
        >
            @if ($flagsOnly || $hasFlags)
                <x-filament::avatar
                    :src="$languageSwitch->getFlag(app()->getLocale())"
                    size="w-6 h-6"
                    :circular="$isCircular"
                    :alt="$languageSwitch->getLabel(app()->getLocale())"
                />
            @else
                <span
                    @class([
                        'flex items-center justify-center flex-shrink-0 w-6 h-6 p-2 text-xs font-semibold bg-primary-500 text-white',
                        'rounded-full' => $isCircular,
                        'rounded-lg' => !$isCircular,
                    ])
                >
                    {{ getCharAvatar(app()->getLocale()) }}
                </span>
            @endif

            @if($showLabel)
                <span class="flex-1 text-left text-primary-600 dark:text-primary-400">
                    {{ $languageSwitch->getLabel(app()->getLocale()) }}
                </span>

                <x-filament::icon
                    icon="heroicon-m-chevron-down"
                    icon-alias="panels::panel-switch-simple-icon"
                    class="h-5 w-5 transition duration-75 text-primary-600 group-hover:text-primary-500 group-focus-visible:text-primary-500 dark:text-primary-500 dark:group-hover:text-primary-400 dark:group-focus-visible:text-primary-400"
                />
            @endif
        </div>
    </x-slot>

    <x-filament::dropdown.list
        @class([
            'space-y-2',
            'flex flex-col m-1' => !$flagsOnly
        ])
    >
        @foreach ($locales as $locale)
            @if (!app()->isLocale($locale))
                <button
                    type="button"
                    wire:click="changeLocale('{{ $locale }}')"
                    @if ($flagsOnly)
                        x-tooltip="{
                        content: @js($languageSwitch->getLabel($locale)),
                        theme: $store.theme,
                        placement: 'right'
                    }"
                    @endif

                    @class([
                        'text-gray-900 dark:text-gray-200 text-sm font-medium flex items-center p-2 rounded-lg hover:bg-gray-200 focus-visible:bg-gray-50 dark:hover:bg-white/5 dark:focus-visible:bg-white/5',
                        'justify-start space-x-2 rtl:space-x-reverse' => !$flagsOnly,
                    ])
                >
                    @if ($flagsOnly)
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
