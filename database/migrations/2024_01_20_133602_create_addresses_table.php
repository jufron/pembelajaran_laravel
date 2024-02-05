<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('street', 100)->nullable(false);
            $table->string('rt', 10)->nullable(false);
            $table->string('rw', 10)->nullable(false);
            $table->string('city', 100)->nullable(false);
            $table->string('province', 100)->nullable(false);
            $table->string('country', 100)->nullable(false);
            $table->string('postal_code', 10)->nullable(false);
            $table->unsignedBigInteger('contact_id');

            $table->foreign('contact_id')->references('id')->on('contacts')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
