<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('tenant_id')->nullable()->after('id');
            $table->string('role', 20)->default('staff')->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->boolean('is_active')->default(true)->after('remember_token');

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->index('tenant_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn(['tenant_id', 'role', 'phone', 'is_active']);
        });
    }
};
