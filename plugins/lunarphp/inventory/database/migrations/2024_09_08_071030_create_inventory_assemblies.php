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
        Schema::create($this->prefix.'inventory_assemblies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained($this->inventoryTable)->restrictOnUpdate()->cascadeOnDelete();
            $table->foreignId('part_id')->constrained($this->inventoryTable)->restrictOnUpdate()->cascadeOnDelete();
            $table->integer('quantity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'inventory_assemblies');
    }
};
