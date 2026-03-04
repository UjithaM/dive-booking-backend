<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->decimal('base_price', 10, 2)->default(0);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->boolean('is_per_person')->default(true);
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->decimal('base_price', 10, 2)->default(0);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->boolean('is_per_person')->default(true);
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->renameColumn('base_price_per_night', 'base_price');
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->boolean('is_per_person')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['base_price', 'sale_price', 'is_per_person']);
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['base_price', 'sale_price', 'is_per_person']);
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->renameColumn('base_price', 'base_price_per_night');
            $table->dropColumn(['sale_price', 'is_per_person']);
        });
    }
};
