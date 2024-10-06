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
        Schema::create($this->prefix.'purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained($this->prefix.'suppliers')->restrictOnUpdate()->cascadeOnDelete();
            $table->enum('status', ['pending', 'received', 'cancelled'])->index()->default('pending');
            $table->decimal('amount')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->restrictOnUpdate()->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->restrictOnUpdate()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'purchase_orders');
    }
};
