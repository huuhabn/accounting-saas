<?php

namespace Database\Seeders;

use Closure;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\Console\Helper\ProgressBar;

abstract class AbstractSeeder extends Seeder
{
    protected function getSeedData($file): \Illuminate\Support\Collection
    {
        return collect(json_decode(
            File::get(
                __DIR__ . "/data/{$file}.json"
            )
        ));
    }

    protected function withProgressBar(int $amount, Closure $createCollectionOfOne): Collection
    {
        $progressBar = new ProgressBar($this->command->getOutput(), $amount);

        $progressBar->start();

        $items = new Collection();

        foreach (range(1, $amount) as $i) {
            $items = $items->merge(
                $createCollectionOfOne($i)
            );
            $progressBar->advance();
        }

        $progressBar->finish();

        $this->command->getOutput()->writeln('');

        return $items;
    }
}
