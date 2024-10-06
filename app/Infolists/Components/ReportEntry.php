<?php

namespace App\Infolists\Components;

use Filament\Infolists\Components\Entry;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Support\Concerns\HasDescription;
use Filament\Support\Concerns\HasHeading;
use Filament\Support\Concerns\HasIcon;
use Filament\Support\Concerns\HasIconColor;

class ReportEntry extends Entry
{
    use HasDescription;
    use HasHeading;
    use HasIcon;
    use HasIconColor;

    protected string $view = 'infolists.components.report-entry';

    public function heading(string | Htmlable | Closure | null $heading = null): static
    {
        $this->heading = translate($heading);

        return $this;
    }

    public function description(string | Htmlable | Closure | null $description = null): static
    {
        $this->description = translate($description);

        return $this;
    }
}
