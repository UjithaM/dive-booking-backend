<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('centre_seasons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('centre_id');
            $table->string('name');
            $table->tinyInteger('start_month');
            $table->tinyInteger('end_month');
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('centre_id')->references('id')->on('centres')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('centre_seasons');
    }
};
