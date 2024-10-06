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
        Schema::create($this->prefix.'warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('default')->index()->default(false);
            $table->string('country')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();

            $table->string('full_address')->virtualAs(
                config('database.default') === 'sqlite'
                    ? "street  || ', ' || zip  || ' ' || city"
                    : "CONCAT(street, ', ', zip, ' ', city)"
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'warehouses');
    }
};
