@php
    $user = filament()->auth()->user();
@endphp

<div class="flex flex-col">
    <h2
        class="grid flex-1 text-base font-semibold leading-6 text-gray-950 dark:text-white"
    >
        {{ __('filament-panels::widgets/account-widget.welcome', ['app' => config('app.name')]) }}
    </h2>

    <p class="text-sm text-gray-500 dark:text-gray-400">
        {{ filament()->getUserName($user) }}
    </p>
</div>
