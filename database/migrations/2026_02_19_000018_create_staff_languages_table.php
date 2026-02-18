<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('staff_languages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('staff_profile_id');
            $table->string('language');
            $table->string('proficiency', 20)->default('fluent');
            $table->timestamps();

            $table->foreign('staff_profile_id')->references('id')->on('staff_profiles')->onDelete('cascade');
            $table->unique(['staff_profile_id', 'language']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_languages');
    }
};
