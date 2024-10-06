<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends \HS\Inventory\Base\BaseMigration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->prefix.'inventory_stock_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained($this->inventoryTable)->restrictOnUpdate()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained($this->prefix.'warehouses')->restrictOnUpdate()->cascadeOnDelete();
            $table->foreignId('purchase_order_id')->nullable()->constrained($this->prefix.'purchase_orders')->restrictOnUpdate()->cascadeOnDelete();
            /*
             * Allows tracking states for each order
             */
            $table->integer('state_before')->default(0);
            $table->integer('state_after')->default(0);

            /*
             * Allows tracking the quantities of each oder
             */
            $table->integer('quantity_before')->default(0);
            $table->integer('quantity_after')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'inventory_stock_entries');
    }
};
