<?php

namespace App\Enums\Setting;

use Filament\Support\Contracts\HasLabel;

enum EntityType: string implements HasLabel
{
    case SoleProprietorship = 'sole_proprietorship';

    case LimitedPartnership = 'limited_partnership';
    case LimitedLiabilityPartnership = 'limited_liability_partnership';
    case LimitedLiabilityCompany = 'limited_liability_company';
    case GeneralPartnership = 'general_partnership';
    case Corporation = 'corporation';
    case Nonprofit = 'nonprofit';

    public function getLabel(): ?string
    {
        $label = match ($this) {
            self::Corporation => 'Corporation',
            self::LimitedLiabilityCompany => 'Limited Liability Company',
            self::GeneralPartnership => 'General Partnership',
            self::SoleProprietorship => 'Sole Proprietorship',

            self::LimitedPartnership => 'Limited Partnership (LP)',
            self::LimitedLiabilityPartnership => 'Limited Liability Partnership (LLP)',

            self::Nonprofit => 'Non-profit',
        };

        return translate($label);
    }
}
