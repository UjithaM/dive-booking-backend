<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->renameColumn('duration_days', 'duration_hours');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->decimal('duration_hours', 5, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->integer('duration_hours')->nullable()->change();
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->renameColumn('duration_hours', 'duration_days');
        });
    }
};
