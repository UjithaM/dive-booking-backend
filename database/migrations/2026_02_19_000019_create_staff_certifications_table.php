<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('staff_certifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('staff_profile_id');
            $table->string('certification_name');
            $table->string('certification_number')->nullable();
            $table->string('issuing_organization')->default('PADI');
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();

            $table->foreign('staff_profile_id')->references('id')->on('staff_profiles')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_certifications');
    }
};
