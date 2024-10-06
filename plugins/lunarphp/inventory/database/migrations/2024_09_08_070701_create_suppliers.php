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
        Schema::create($this->prefix.'suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('contact_title')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_fax')->nullable();
            $table->string('contact_email')->nullable();
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
        Schema::dropIfExists($this->prefix.'suppliers');
    }
};
