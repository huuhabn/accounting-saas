<?php

use Lunar\Base\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->prefix.'inventory_stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->constrained($this->prefix.'inventory_stocks')->restrictOnUpdate()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained($this->prefix.'warehouses')->restrictOnUpdate()->cascadeOnDelete();
            $table->decimal('before', 8, 2)->default(0);
            $table->decimal('after', 8, 2)->default(0);
            $table->decimal('cost', 8, 2)->default(0)->nullable();
            $table->nullableMorphs('receiver');
            $table->string('reason')->nullable();
            $table->boolean('returned')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->restrictOnUpdate()->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->restrictOnUpdate()->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'inventory_stock_movements');
    }
};
