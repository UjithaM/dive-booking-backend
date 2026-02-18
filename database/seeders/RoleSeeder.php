<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'guard_name' => 'api',
            ],
            [
                'name' => 'tenant_admin',
                'guard_name' => 'api',
            ],
            [
                'name' => 'customer',
                'guard_name' => 'api',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name'], 'guard_name' => $role['guard_name']],
                $role
            );
        }
    }
}
