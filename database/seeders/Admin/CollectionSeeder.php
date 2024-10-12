<?php

namespace Database\Seeders\Admin;

use Lunar\FieldTypes\Text;
use Lunar\Models\Collection;
use Lunar\Models\CollectionGroup;
use Illuminate\Support\Facades\DB;
use Lunar\FieldTypes\TranslatedText;
use Database\Seeders\AbstractSeeder;

class CollectionSeeder extends AbstractSeeder
{
    /**
     * Run the database seeds.
     *
     */
    public function run(): void
    {
        $collections = $this->getSeedData('collections');

        $collectionGroup = CollectionGroup::first();

        DB::transaction(function () use ($collections, $collectionGroup) {
            foreach ($collections as $collection) {

                $collectionModel = Collection::create([
                    'collection_group_id' => $collectionGroup->id,
                    'attribute_data' => [
                        'name' => new TranslatedText([
                            'en' => new Text($collection->name),
                        ]),
                        'description' => new TranslatedText([
                            'en' => new Text($collection->description),
                        ]),
                    ],
                ]);

                $imgPath = base_path("database/seeders/data/images/collections/{$collection->image}");
                if (file_exists($imgPath)) {
                    $media = $collectionModel->addMedia($imgPath)->preservingOriginal()->toMediaCollection('collections');
                    $media->setCustomProperty('primary', true);
                    $media->save();
                }
            }
        });
    }
}
