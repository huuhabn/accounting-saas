<?php

namespace App\Enums\Accounting;

use Filament\Support\Contracts\HasLabel;

enum AccountType: string implements HasLabel
{
    case CurrentAsset = 'current_asset';
    case NonCurrentAsset = 'non_current_asset';
    case ContraAsset = 'contra_asset';
    case CurrentLiability = 'current_liability';
    case NonCurrentLiability = 'non_current_liability';
    case ContraLiability = 'contra_liability';
    case Equity = 'equity';
    case ContraEquity = 'contra_equity';
    case OperatingRevenue = 'operating_revenue';
    case NonOperatingRevenue = 'non_operating_revenue';
    case ContraRevenue = 'contra_revenue';
    case UncategorizedRevenue = 'uncategorized_revenue';
    case OperatingExpense = 'operating_expense';
    case NonOperatingExpense = 'non_operating_expense';
    case ContraExpense = 'contra_expense';
    case UncategorizedExpense = 'uncategorized_expense';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::CurrentAsset => translate('Current Asset'),
            self::NonCurrentAsset => translate('Non-Current Asset'),
            self::ContraAsset => translate('Contra Asset'),
            self::CurrentLiability => translate('Current Liability'),
            self::NonCurrentLiability => translate('Non-Current Liability'),
            self::ContraLiability => translate('Contra Liability'),
            self::Equity => translate('Equity'),
            self::ContraEquity => translate('Contra Equity'),
            self::OperatingRevenue => translate('Operating Revenue'),
            self::NonOperatingRevenue => translate('Non-Operating Revenue'),
            self::ContraRevenue => translate('Contra Revenue'),
            self::UncategorizedRevenue => translate('Uncategorized Revenue'),
            self::OperatingExpense => translate('Operating Expense'),
            self::NonOperatingExpense => translate('Non-Operating Expense'),
            self::ContraExpense => translate('Contra Expense'),
            self::UncategorizedExpense => translate('Uncategorized Expense'),
        };
    }

    public function getCategory(): AccountCategory
    {
        return match ($this) {
            self::CurrentAsset, self::NonCurrentAsset, self::ContraAsset => AccountCategory::Asset,
            self::CurrentLiability, self::NonCurrentLiability, self::ContraLiability => AccountCategory::Liability,
            self::Equity, self::ContraEquity => AccountCategory::Equity,
            self::OperatingRevenue, self::NonOperatingRevenue, self::ContraRevenue, self::UncategorizedRevenue => AccountCategory::Revenue,
            self::OperatingExpense, self::NonOperatingExpense, self::ContraExpense, self::UncategorizedExpense => AccountCategory::Expense,
        };
    }

    public function isUncategorized(): bool
    {
        return match ($this) {
            self::UncategorizedRevenue, self::UncategorizedExpense => true,
            default => false,
        };
    }
}
