<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->dropPrimary('model_has_permissions_permission_model_type_primary');
        });

        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->string('model_uuid', 36)->change();
            $table->primary(
                ['permission_id', 'model_uuid', 'model_type'],
                'model_has_permissions_permission_model_type_primary'
            );
        });

        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->dropPrimary('model_has_roles_role_model_type_primary');
        });

        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->string('model_uuid', 36)->change();
            $table->primary(
                ['role_id', 'model_uuid', 'model_type'],
                'model_has_roles_role_model_type_primary'
            );
        });
    }

    public function down(): void
    {
        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->dropPrimary('model_has_permissions_permission_model_type_primary');
        });

        Schema::table('model_has_permissions', function (Blueprint $table) {
            $table->uuid('model_uuid')->change();
            $table->primary(
                ['permission_id', 'model_uuid', 'model_type'],
                'model_has_permissions_permission_model_type_primary'
            );
        });

        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->dropPrimary('model_has_roles_role_model_type_primary');
        });

        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->uuid('model_uuid')->change();
            $table->primary(
                ['role_id', 'model_uuid', 'model_type'],
                'model_has_roles_role_model_type_primary'
            );
        });
    }
};
