<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dive_sites', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('centre_id')->nullable();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->decimal('max_depth_meters', 6, 2)->nullable();
            $table->decimal('min_depth_meters', 6, 2)->nullable();
            $table->string('difficulty_level', 20)->default('beginner');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->json('marine_life')->nullable();
            $table->tinyInteger('best_season_start')->nullable();
            $table->tinyInteger('best_season_end')->nullable();
            $table->string('hero_image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('centre_id')->references('id')->on('centres')->onDelete('set null');
            $table->unique(['tenant_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dive_sites');
    }
};
