<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('booking_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('booking_id');
            $table->string('bookable_type');
            $table->uuid('bookable_id');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->date('scheduled_date')->nullable();
            $table->time('scheduled_time')->nullable();
            $table->string('status', 20)->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->index(['bookable_type', 'bookable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_items');
    }
};
