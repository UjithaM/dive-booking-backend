<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->string('booking_reference')->unique();
            $table->uuid('customer_id');
            $table->uuid('centre_id');
            $table->uuid('promotion_id')->nullable();
            $table->string('status', 20)->default('pending');
            $table->date('booking_date');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('final_amount', 10, 2);
            $table->string('currency', 10)->default('USD');
            $table->integer('number_of_participants')->default(1);
            $table->text('special_requests')->nullable();
            $table->text('internal_notes')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('centre_id')->references('id')->on('centres')->onDelete('cascade');
            $table->foreign('promotion_id')->references('id')->on('promotions')->onDelete('set null');
            $table->index('status');
            $table->index('booking_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
