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
        Schema::create($this->prefix.'inventory_suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained($this->inventoryTable)->restrictOnUpdate()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained($this->prefix.'suppliers')->restrictOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'inventory_suppliers');
    }
};
