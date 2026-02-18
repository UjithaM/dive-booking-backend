<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('course_prerequisites', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('course_id');
            $table->uuid('prerequisite_course_id');
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('prerequisite_course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->unique(['course_id', 'prerequisite_course_id'], 'course_prereq_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_prerequisites');
    }
};
