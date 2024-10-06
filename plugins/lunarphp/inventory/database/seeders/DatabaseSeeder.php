<?php

namespace HS\Inventory\Database\Seeders;

use Lunar\Facades\DB;
use Lunar\Models\Country;
use Lunar\Models\Channel;
use Lunar\Models\Product;
use Lunar\Models\Language;
use Lunar\Models\Currency;
use Lunar\Models\TaxClass;
use Lunar\Models\Attribute;
use Lunar\Models\Collection;
use Lunar\Admin\Models\Staff;
use Lunar\Models\ProductType;
use Lunar\Models\CustomerGroup;
use Illuminate\Database\Seeder;
use Lunar\Models\AttributeGroup;
use Lunar\Models\CollectionGroup;
use Lunar\FieldTypes\TranslatedText;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    protected array $toTruncate = ['channels', 'attributes', 'attribute_groups', 'product_types'];

    /**
     * Seed the application's database.
     *
     */
    public function run(): void
    {
        // $this->truncateTables();

        $this->installDefaultValues();

        $this->installDemoShopData();

    }

    /**
     * Truncate shop tables
     *
     * @return void
     */
    public function truncateTables(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ($this->toTruncate as $table) {
            \Illuminate\Support\Facades\DB::table(config('lunar.table_prefix').$table)->truncate();
        }

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Install default values for shop
     *
     * @return void
     */
    public function installDefaultValues(): void
    {
        DB::transaction(function () {

            if (class_exists(Staff::class) && ! Staff::whereAdmin(true)->exists()) {
                $this->command->info('First create an admin account');
                $this->call('lunar:create-admin');
            }

            if (! Country::count()) {
                $this->command->info('Importing countries');
                $this->call(CountrySeeder::class);
            }

            if (! Channel::whereDefault(true)->exists()) {
                $this->command->info('Setting up default channel');

                Channel::create([
                    'name' => 'Web',
                    'handle' => 'web',
                    'default' => true,
                    'url' => env('APP_URL', 'http://localhost'),
                ]);
            }

            if (! Language::count()) {
                $this->command->info('Adding default language');

                Language::create([
                    'code' => 'en',
                    'name' => 'English',
                    'default' => true,
                ]);
            }

            if (! Currency::whereDefault(true)->exists()) {
                $this->command->info('Adding a default currency (USD)');

                Currency::create([
                    'code' => 'USD',
                    'name' => 'US Dollar',
                    'exchange_rate' => 1,
                    'decimal_places' => 2,
                    'default' => true,
                    'enabled' => true,
                ]);
            }

            if (! CustomerGroup::whereDefault(true)->exists()) {
                $this->command->info('Adding a default customer group.');

                CustomerGroup::create([
                    'name' => 'Default',
                    'handle' => 'default',
                    'default' => true,
                ]);
            }

            if (! CollectionGroup::count()) {
                $this->command->info('Adding an initial collection group');

                CollectionGroup::create([
                    'name' => 'Main',
                    'handle' => 'main',
                ]);
            }

            if (! TaxClass::count()) {
                $this->command->info('Adding a default tax class.');

                TaxClass::create([
                    'name' => 'Default Tax Class',
                    'default' => true,
                ]);
            }

            if (! Attribute::count()) {
                $this->command->info('Setting up initial attributes');

                $group = AttributeGroup::create([
                    'attributable_type' => (new Product)->getMorphClass(),
                    'name' => collect([
                        'en' => 'Details',
                    ]),
                    'handle' => 'details',
                    'position' => 1,
                ]);

                $collectionGroup = AttributeGroup::create([
                    'attributable_type' => (new Collection)->getMorphClass(),
                    'name' => collect([
                        'en' => 'Details',
                    ]),
                    'handle' => 'collection_details',
                    'position' => 1,
                ]);

                Attribute::create([
                    'attribute_type' => 'product',
                    'attribute_group_id' => $group->id,
                    'position' => 1,
                    'name' => [
                        'en' => 'Name',
                    ],
                    'handle' => 'name',
                    'section' => 'main',
                    'type' => TranslatedText::class,
                    'required' => true,
                    'default_value' => null,
                    'configuration' => [
                        'richtext' => false,
                    ],
                    'system' => true,
                    'description' => [
                        'en' => '',
                    ],
                ]);

                Attribute::create([
                    'attribute_type' => 'collection',
                    'attribute_group_id' => $collectionGroup->id,
                    'position' => 1,
                    'name' => [
                        'en' => 'Name',
                    ],
                    'handle' => 'name',
                    'section' => 'main',
                    'type' => TranslatedText::class,
                    'required' => true,
                    'default_value' => null,
                    'configuration' => [
                        'richtext' => false,
                    ],
                    'system' => true,
                    'description' => [
                        'en' => '',
                    ],
                ]);

                Attribute::create([
                    'attribute_type' => 'product',
                    'attribute_group_id' => $group->id,
                    'position' => 2,
                    'name' => [
                        'en' => 'Description',
                    ],
                    'handle' => 'description',
                    'section' => 'main',
                    'type' => TranslatedText::class,
                    'required' => false,
                    'default_value' => null,
                    'configuration' => [
                        'richtext' => true,
                    ],
                    'system' => false,
                    'description' => [
                        'en' => '',
                    ],
                ]);

                Attribute::create([
                    'attribute_type' => 'collection',
                    'attribute_group_id' => $collectionGroup->id,
                    'position' => 2,
                    'name' => [
                        'en' => 'Description',
                    ],
                    'handle' => 'description',
                    'section' => 'main',
                    'type' => TranslatedText::class,
                    'required' => false,
                    'default_value' => null,
                    'configuration' => [
                        'richtext' => true,
                    ],
                    'system' => false,
                    'description' => [
                        'en' => '',
                    ],
                ]);
            }

            if (! ProductType::count()) {
                $this->command->info('Adding a product type.');

                $type = ProductType::create([
                    'name' => 'Base',
                ]);

                $type->mappedAttributes()->attach(
                    Attribute::whereAttributeType(
                        (new Product)->getMorphClass()
                    )->get()->pluck('id')
                );
            }
        });
    }

    /**
     * Install demo data for shop
     *
     * @return void
     */
    public function installDemoShopData(): void
    {
        $this->call(CollectionSeeder::class);
        $this->call(AttributeSeeder::class);
        $this->call(TaxSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(ShippingSeeder::class);
        $this->call(InventorySeeder::class);
        $this->call(OrderSeeder::class);
    }
}
