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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->string('costumer_id', 10);
            $table->bigInteger('amount')->nullable(false)->default(0);
            $table->timestamps();

            $table->foreign('costumer_id')
                  ->references('id')
                  ->on('costumers')
                  ->cascadeOnUpdate()
                  ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
