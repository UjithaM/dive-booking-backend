<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id');
            $table->uuid('booking_id');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('USD');
            $table->string('method', 20);
            $table->string('status', 20)->default('pending');
            $table->string('transaction_reference')->nullable();
            $table->string('gateway')->nullable();
            $table->json('gateway_response')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
