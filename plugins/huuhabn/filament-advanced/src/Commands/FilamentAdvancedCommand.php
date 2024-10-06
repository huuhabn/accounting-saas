<?php

namespace HanaSales\FilamentAdvanced\Commands;

use Illuminate\Console\Command;

class FilamentAdvancedCommand extends Command
{
    public $signature = 'filament-advanced';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
