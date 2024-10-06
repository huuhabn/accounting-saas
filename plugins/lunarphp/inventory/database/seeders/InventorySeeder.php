<?php

namespace HS\Inventory\Database\Seeders;

use Closure;
use HS\Inventory\Models\Unit;
use Illuminate\Database\Seeder;
use Lunar\Models\ProductVariant;
use HS\Inventory\Models\Supplier;
use Illuminate\Support\Collection;
use HS\Inventory\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use HS\Inventory\Facades\Inventory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use HS\Inventory\Dto\InventoryStockDto;
use HS\Inventory\Models\InventoryStockMovement;
use Symfony\Component\Console\Helper\ProgressBar;
use HS\Inventory\Exceptions\InvalidQuantityException;

class InventorySeeder extends Seeder
{
    protected array $toTruncate = [
        'units',
        'warehouses',
        'inventory_stock_movements',
        'inventory_stocks',
        'suppliers',
        'inventory_suppliers',
        'purchase_histories',
        'purchases',
        'inventory_assemblies'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws InvalidQuantityException
     * @throws \HS\Inventory\Exceptions\NotEnoughStockException
     * @throws \Throwable
     */
    public function run(): void
    {
//        /** @var ProductVariant $productVariant */
//        $productVariant = ProductVariant::first();
//        $stock = Inventory::on($productVariant)->add(20, 'Check add stock', 25300);

        if ($this->command->confirm('Truncate inventory tables and seeder demo data?')) {
            // Truncate tables
            $this->truncateTables();

            $warehouses = $this->withProgressBar(10, fn() => Warehouse::factory(1)->create());
            $defaultWarehouse = $warehouses->random()->first();
            $defaultWarehouse->default = true;
            $defaultWarehouse->save();

            $suppliers = $this->withProgressBar(15, fn() => Supplier::factory(1)->create());

            // Create default data
            $litresUnit = Unit::create([
                'name' => 'Litres',
                'symbol' => 'L',
            ]);

            /** @var ProductVariant[] $products */
            $products = ProductVariant::get();
            foreach ($products as $product) {
                $inventoryStockDto = new InventoryStockDto(random_int(15, 200));
                $product->createStockOnWarehouse($inventoryStockDto);
                $supplier = $suppliers->random()->first();
                $product->addSupplier($supplier);
            }

            /** @var ProductVariant $productVariant */
            $productVariant = $products->random()->first();
            $warehouse = Warehouse::getDefault();

            $productStock = $productVariant->getStockOnWarehouse($warehouse->id);

            // Adding More Stock
            $productStock->put(3, 'Put Stock', '30000');

            // Taking Stock
            $productStock->take(15, 'Check take stock');
            $productStock->updateQuantity(20);

            // Rolling back movements
            $productStock->rollback();
            $movement = InventoryStockMovement::find(2);
            $productStock->rollback($movement);
            //Inventory Stock Movement Model Methods
            $movement->rollback();

            $productStock->moveTo($warehouse);
        }
    }

    protected function truncateTables()
    {
        Schema::disableForeignKeyConstraints();
        //        Artisan::call('migrate:rollback');
        //        Artisan::call('migrate');
        $prefix = config('lunar.database.table_prefix');

        foreach ($this->toTruncate as $table) {
            DB::table($prefix . $table)->truncate();
        }

        Schema::enableForeignKeyConstraints();
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
