<?php

namespace HS\Inventory\Database\Seeders;

use App\Models\Country;
use Illuminate\Support\Facades\DB;
use Database\Seeders\AbstractSeeder;

class CountrySeeder extends AbstractSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Importing Countries and States');
        $countries = $this->getSeedData('countries-states');
        $existing = Country::pluck('iso3');

        $newCountries = collect($countries)->filter(function ($country) use ($existing) {
            return ! $existing->contains($country->iso3);
        });

        if (! $newCountries->count()) {
            $this->command->info('There are no new countries to import');
            return;
        }

        DB::transaction(function () use ($newCountries) {
            $this->command->withProgressBar($newCountries, function ($country) {
                $model = Country::create([
                    'name' => $country->name,
                    'iso3' => $country->iso3,
                    'iso2' => $country->iso2,
                    'phone_code' => $country->phone_code,
                    'capital' => $country->capital,
                    'currency' => $country->currency,
                    'native' => $country->native,
                    'emoji' => $country->emoji,
                    'emoji_u' => $country->emojiU,
                ]);

                $states = collect($country->states)->map(function ($state) {
                    return [
                        'name' => $state->name,
                        'code' => $state->state_code,
                    ];
                });

                $model->states()->createMany($states->toArray());
            });
        });

        $this->command->line('');
    }
}
