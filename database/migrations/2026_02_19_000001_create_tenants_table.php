<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('website')->nullable();
            $table->text('description')->nullable();
            $table->string('currency', 10)->default('USD');
            $table->string('timezone', 50)->default('UTC');
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
