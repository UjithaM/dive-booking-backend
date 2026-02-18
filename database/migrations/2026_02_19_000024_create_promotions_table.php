<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('code')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('discount_type', 20);
            $table->decimal('discount_value', 10, 2);
            $table->decimal('min_booking_value', 10, 2)->nullable();
            $table->decimal('max_discount_amount', 10, 2)->nullable();
            $table->dateTime('valid_from');
            $table->dateTime('valid_until');
            $table->integer('max_uses')->nullable();
            $table->integer('current_uses')->default(0);
            $table->string('applicable_to', 20)->default('all');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->unique(['tenant_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
