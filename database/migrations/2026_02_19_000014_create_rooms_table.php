<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('centre_id');
            $table->uuid('room_type_id');
            $table->string('room_number');
            $table->string('floor')->nullable();
            $table->decimal('base_price_per_night', 10, 2);
            $table->string('status', 20)->default('available');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('centre_id')->references('id')->on('centres')->onDelete('cascade');
            $table->foreign('room_type_id')->references('id')->on('room_types')->onDelete('cascade');
            $table->unique(['tenant_id', 'centre_id', 'room_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
