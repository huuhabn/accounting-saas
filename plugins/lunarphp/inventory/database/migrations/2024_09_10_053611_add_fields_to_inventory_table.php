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
        if (!Schema::hasColumn($this->inventoryTable, 'unit_id')) {
            Schema::table($this->inventoryTable, function (Blueprint $table) {
                $table->foreignId('unit_id')
                    ->after('shippable')
                    ->nullable()->constrained($this->prefix . 'units')
                    ->restrictOnUpdate()
                    ->cascadeOnDelete();
            });
        }

        if (!Schema::hasColumn($this->inventoryTable, 'inventoriable')) {
            Schema::table($this->inventoryTable, function (Blueprint $table) {
                $table->boolean('inventoriable')
                    ->default(false)
                    ->after('shippable');
            });
        }

        if (!Schema::hasColumn($this->inventoryTable, 'is_assembly')) {
            Schema::table($this->inventoryTable, function (Blueprint $table) {
                $table->boolean('is_assembly')
                    ->default(false)
                    ->after('shippable');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
//        Schema::table($this->inventoryTable, function (Blueprint $table) {
//            $table->dropColumn('unit_id');
//            $table->dropColumn('is_assembly');
//        });
    }
};
