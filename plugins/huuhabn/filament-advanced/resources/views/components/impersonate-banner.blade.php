@props(['style', 'display', 'fixed', 'position'])

@if(app('impersonate')->isImpersonating())

@php
$display = $display ?? Filament\Facades\Filament::getUserName(Filament\Facades\Filament::auth()->user());
$fixed = $fixed ?? config('filament-advanced.impersonate.banner.fixed');
$position = $position ?? config('filament-advanced.impersonate.banner.position');
@endphp

<style>
    :root {
        --impersonate-banner-height: 50px;
    }
    html {
        margin-{{ $position }}: var(--impersonate-banner-height);
    }


    #impersonate-banner {
        position: {{ $fixed ? 'fixed' : 'absolute' }};
        height: var(--impersonate-banner-height);
        {{ $position }}: 0;
        width: 100%;
        display: flex;
        column-gap: 20px;
        justify-content: center;
        align-items: center;
        background: linear-gradient(117deg, #FEF0F8 14.35%, #DACFFF 84.4%);
        color: #000424;
        z-index: 45;
        font-size: clamp(0.6875rem, 0.459rem + 0.475vw, 0.875rem);;
    }

    .dark #impersonate-banner {
        background: #00031E;
        color: #ffffff;
    }

    #impersonate-banner a {
        display: block;
        padding: 5px 20px;
        border-radius: 99px;
        background: #fff;
        color: #000424;
        font-size: 0.875rem;
    }
    #impersonate-banner a:hover,
    .dark #impersonate-banner a {
        background: #5e36f2;
        color: #ffffff;
    }

    .dark #impersonate-banner a:hover {
        color: #5e36f2;
        background: #ffffff;
    }

    @if($fixed)
    div.fi-layout > aside.fi-sidebar {
        height: calc(100vh - var(--impersonate-banner-height));
    }

    @if($position === 'top')
    .fi-topbar {
        top: var(--impersonate-banner-height);
    }
    div.fi-layout > aside.fi-sidebar {
        top: var(--impersonate-banner-height);
    }
    @endif

    @else
    div.fi-layout > aside.fi-sidebar {
        padding-bottom: var(--impersonate-banner-height);
    }
    @endif

    @media print{
        aside, body {
            margin-top: 0;
        }

        #impersonate-banner {
            display: none;
        }
    }
</style>

<div id="impersonate-banner">
    <div>
        {{ __('filament-advanced::lang.impersonate.impersonating') }} <strong>{{ $display }}</strong>
    </div>

    <a href="{{ route('impersonate.leave') }}">{{ __('filament-advanced::lang.impersonate.leave') }}</a>
</div>
@endIf
