<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_centre', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('service_id');
            $table->uuid('centre_id');
            $table->decimal('base_price', 10, 2);
            $table->decimal('discounted_price', 10, 2)->nullable();
            $table->decimal('price_per_additional_dive', 10, 2)->nullable();
            $table->string('currency', 10)->default('USD');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('centre_id')->references('id')->on('centres')->onDelete('cascade');
            $table->unique(['service_id', 'centre_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_centre');
    }
};
