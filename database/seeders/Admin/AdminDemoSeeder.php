<?php

namespace Database\Seeders\Admin;

use App\Models\User;
use Lunar\Models\Product;
use Lunar\Admin\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class AdminDemoSeeder extends Seeder
{
    /**
     * Exclude truncate default tables
     * @var array|string[]
     */
    protected array $excludeTruncate = ['countries', 'channels', 'languages', 'currencies', 'customer_groups', 'collection_groups', 'tax_classes', 'attributes', 'attribute_groups', 'product_types'];

    protected array $toTruncate = ['media', 'users', 'activity_log'];

    public function run(): void
    {
        // Clear images
        Storage::deleteDirectory('public');

        $this->truncateTables();

        $this->createAdmins();

        $this->call(CollectionSeeder::class);
        // $this->call(AttributeSeeder::class); // Create it on install lunar
        $this->call(TaxSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(ShippingSeeder::class);
        $this->call(OrderSeeder::class);
    }

    protected function createAdmins()
    {
        // Create admin account
        if (class_exists(User::class) && ! User::where('current_company_id', 1)->exists()) {
            User::create([
                'name' => 'Cyber Admin',
                'email' => 'huuhadev@gmail.com',
                'password' => bcrypt('password'),
                'current_company_id' => 1,  // Assuming this will be the ID of the created company
            ]);
        }

        // Create staff admin
        if (class_exists(Staff::class) && ! Staff::whereAdmin(true)->exists()) {
            $this->command->info('First create a admin user');
            Artisan::call('lunar:create-admin', [
                '--firstname' => 'Admin',
                '--lastname' => 'Staff',
                '--email' => 'huuhabn@gmail.com',
                '--password' => 'password',
            ]);
        }
    }

    /**
     * Truncate shop tables
     *
     * @return void
     */
    public function truncateTables(): void
    {
        Schema::disableForeignKeyConstraints();
        $prefix = config('lunar.database.table_prefix');
        $tables = DB::select("SHOW TABLES LIKE '{$prefix}%'");

        $tableNames = array_filter(array_map(function($table) use ($prefix) {
            $tableName = array_values((array) $table)[0];
            $trimmedTable = str_replace($prefix, '', $tableName);
            return in_array($trimmedTable, $this->excludeTruncate) ? null : $tableName;
        }, $tables));

        $truncateTables = array_merge($tableNames, $this->toTruncate);

        foreach ($truncateTables as $tableName) {
            echo "Truncate: $tableName\n";
            DB::table($tableName)->truncate();
        }
        Schema::enableForeignKeyConstraints();
    }
}
