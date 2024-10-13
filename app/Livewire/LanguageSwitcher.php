<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Contracts\View\View;
use App\Filament\Components\LanguageSwitcher as LangSwitcher;

class LanguageSwitcher extends Component
{
    #[On('language-switched')]
    public function changeLocale($locale)
    {
        LangSwitcher::trigger(locale: $locale);
    }

    public function render()
    {
        return view('livewire.language-switcher');
    }
}
