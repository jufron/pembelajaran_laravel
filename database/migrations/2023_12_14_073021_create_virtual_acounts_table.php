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
        Schema::create('virtual_acounts', function (Blueprint $table) {
            $table->id();
            $table->string('bank', 50)->nullable(false);
            $table->string('va_number')->nullable(false);
            $table->unsignedBigInteger('wallet_id')->nullable(false);
            $table->timestamps();
            $table->foreign('wallet_id')
                  ->references('id')
                  ->on('wallets')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_acounts');
    }
};
