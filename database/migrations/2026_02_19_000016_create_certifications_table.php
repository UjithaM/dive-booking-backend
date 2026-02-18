<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('certifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('customer_id');
            $table->string('certification_name');
            $table->string('certification_number')->nullable();
            $table->string('issuing_organization')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certifications');
    }
};
