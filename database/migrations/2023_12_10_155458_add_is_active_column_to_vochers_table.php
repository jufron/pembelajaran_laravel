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
        Schema::table('vochers', function (Blueprint $table) {
            $table->boolean('is_active')
                  ->after('vocher_code')
                  ->nullable()
                  ->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vochers', function (Blueprint $table) {
            $table->dropIfExists('is_active');
        });
    }
};
