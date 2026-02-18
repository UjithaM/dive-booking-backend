<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('category', 20);
            $table->string('name');
            $table->string('slug');
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();
            $table->integer('duration_days')->nullable();
            $table->integer('min_age')->nullable();
            $table->integer('max_participants')->nullable();
            $table->json('includes')->nullable();
            $table->string('hero_image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->unique(['tenant_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
