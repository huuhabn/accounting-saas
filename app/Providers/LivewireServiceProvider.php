<?php

namespace App\Providers;

use Livewire\Livewire;
use App\Livewire\LanguageSwitcher;
use Illuminate\Support\ServiceProvider;

class LivewireServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Livewire::component('language-switcher', LanguageSwitcher::class);
    }
}
