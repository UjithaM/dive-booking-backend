<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activity_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('activity_id');
            $table->uuid('centre_id');
            $table->uuid('guide_id')->nullable();
            $table->date('date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('max_participants')->nullable();
            $table->integer('current_participants')->default(0);
            $table->string('status', 20)->default('scheduled');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
            $table->foreign('centre_id')->references('id')->on('centres')->onDelete('cascade');
            $table->foreign('guide_id')->references('id')->on('staff_profiles')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_schedules');
    }
};
