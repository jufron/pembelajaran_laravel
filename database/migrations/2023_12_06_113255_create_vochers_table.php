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
        Schema::create('vochers', function (Blueprint $table) {
            $table->ulid('id')->nullable(false)->primary();
            $table->string('name', 50)->nullable(false);
            $table->string('vocher_code', 50)->nullable(false);
            $table->timestamp('createt_at')->nullable(false)->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vochers');
    }
};
