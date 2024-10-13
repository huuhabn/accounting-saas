@php
    $languageSwitch = \App\Filament\Components\LanguageSwitcher::make();
    $locales = $languageSwitch->getLocales();
    $isCircular = $languageSwitch->isCircular();
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
                'flex items-center justify-center gap-x-3 text-sm font-medium outline-none transition duration-75 hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5',
                'rounded-full ring-1' => $isCircular,
                'rounded-lg' => !$isCircular,
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
                <span class="font-semibold text-md">{{ getCharAvatar(app()->getLocale()) }}</span>
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
