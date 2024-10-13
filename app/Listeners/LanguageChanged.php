<?php

namespace App\Listeners;

use App\Models\Company;
use App\Events\LangChanged;
use Filament\Facades\Filament;
use App\Events\CompanyConfigured;
use App\Models\Setting\Localization;

class LanguageChanged
{
    /**
     * Handle the event.
     */
    public function handle(LangChanged $event): void
    {
        /** @var Company $company */
        $company = Filament::getTenant();

        if ($company && $company->locale->language != $event->locale) {
            $company->locale->language = $event->locale;
            $company->locale->save();
        }
    }
}
