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
        Schema::create($this->prefix.'inventory_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->restrictOnUpdate()->nullOnDelete();
            $table->foreignId('inventory_id')->constrained($this->inventoryTable)->restrictOnUpdate()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained($this->prefix.'warehouses')->restrictOnUpdate()->cascadeOnDelete();
            $table->decimal('available_stock')->default(0);
            $table->decimal('committed_stock')->default(0);
            $table->string('reason')->nullable();
            $table->decimal('cost')->default(0);
            $table->string('aisle')->nullable();
            $table->string('row')->nullable();
            $table->string('bin')->nullable();
            $table->timestamps();
            $table->softDeletes();

            /*
             * This allows only one inventory stock
             * to be created on a single location
             */
            $table->unique(['inventory_id', 'warehouse_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'inventory_stocks');
    }
};
