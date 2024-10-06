<?php

namespace Database\Seeders\Admin;

use Lunar\Models\Attribute;
use Lunar\Models\AttributeGroup;
use Illuminate\Support\Facades\DB;
use Database\Seeders\AbstractSeeder;

class AttributeSeeder extends AbstractSeeder
{
    /**
     * Run the database seeds.
     *
     */
    public function run(): void
    {
        $attributes = $this->getSeedData('attributes');

        $attributeGroup = AttributeGroup::first();

        DB::transaction(function () use ($attributes, $attributeGroup) {
            foreach ($attributes as $attribute) {
                Attribute::create([
                    'attribute_group_id' => $attributeGroup->id,
                    'attribute_type' => $attribute->attribute_type,
                    'handle' => $attribute->handle,
                    'section' => 'main',
                    'type' => $attribute->type,
                    'required' => false,
                    'searchable' => true,
                    'filterable' => false,
                    'system' => false,
                    'position' => $attributeGroup->attributes()->count() + 1,
                    'name' => [
                        'en' => $attribute->name,
                    ],
                    'description' => [
                        'en' => $attribute->name,
                    ],
                    'configuration' => (array) $attribute->configuration,
                ]);
            }
        });
    }
}
