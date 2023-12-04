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
        Schema::create('product', function (Blueprint $table) {
            $table->string('id', 10)->nullable(false)->primary();
            $table->string('name', 60)->nullable(false);
            $table->text('description')->nullable(true);
            $table->integer('price')->nullable(false);
            $table->string('category_id', 10)->nullable('false');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('category_id')
                  ->references('id')
                  ->on('category')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
