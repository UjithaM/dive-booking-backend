<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('staff_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id');
            $table->uuid('tenant_id');
            $table->string('title')->nullable();
            $table->text('bio')->nullable();
            $table->string('profile_photo_url')->nullable();
            $table->json('specialties')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_profiles');
    }
};
