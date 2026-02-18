<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('room_bookings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('booking_id');
            $table->uuid('room_id');
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('guests')->default(1);
            $table->decimal('price_per_night', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->string('status', 20)->default('reserved');
            $table->text('special_requests')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_bookings');
    }
};
