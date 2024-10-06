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
        Schema::create($this->prefix.'units', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable()->default(null);
            $table->string('name');
            $table->string('symbol');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->prefix.'units');
    }
};
