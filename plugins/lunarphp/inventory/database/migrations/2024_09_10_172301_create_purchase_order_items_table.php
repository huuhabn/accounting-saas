<?php

use HS\Inventory\Base\BaseMigration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends BaseMigration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->prefix.'purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained($this->inventoryTable)->restrictOnUpdate()->cascadeOnDelete();
            $table->foreignId('purchase_order_id')->nullable()->constrained($this->prefix.'purchase_orders')->restrictOnUpdate()->cascadeOnDelete();
            $table->integer('quantity')->default(0);
            $table->decimal('unit_price')->default(0);
            $table->decimal('total_price')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'purchase_order_items');
    }
};
