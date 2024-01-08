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
        Schema::create('table_costumers_likes_products', function (Blueprint $table) {
            $table->string('costumer_id')->nullable(false);
            $table->string('product_id')->nullable(false);
            $table->primary(['costumer_id', 'product_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_costumers_likes_products');
    }
};
