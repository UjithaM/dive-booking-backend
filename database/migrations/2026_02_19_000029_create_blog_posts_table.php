<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->unsignedBigInteger('author_id');
            $table->string('title');
            $table->string('slug');
            $table->text('excerpt')->nullable();
            $table->longText('body');
            $table->string('featured_image_url')->nullable();
            $table->string('status', 20)->default('draft');
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['tenant_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
