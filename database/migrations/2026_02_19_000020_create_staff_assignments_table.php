<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('staff_assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('staff_profile_id');
            $table->uuid('centre_id');
            $table->string('role', 20);
            $table->boolean('is_primary')->default(false);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();

            $table->foreign('staff_profile_id')->references('id')->on('staff_profiles')->onDelete('cascade');
            $table->foreign('centre_id')->references('id')->on('centres')->onDelete('cascade');
            $table->unique(['staff_profile_id', 'centre_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_assignments');
    }
};
