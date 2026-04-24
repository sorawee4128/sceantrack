<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'manage users',
            'manage roles',
            'manage permissions',
            'manage shifts',
            'view all reports',
            'view own reports',
            'manage master data',
            'manage scene cases',
            'view own scene cases',
            'edit own draft',
            'submit scene cases',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $doctor = Role::firstOrCreate(['name' => 'doctor', 'guard_name' => 'web']);
        $assistant = Role::firstOrCreate(['name' => 'assistant', 'guard_name' => 'web']);

        $admin->syncPermissions(Permission::pluck('name')->all());

        $doctor->syncPermissions([
            'view own reports',
            'view own scene cases',
            'edit own draft',
            'submit scene cases',
        ]);

        $assistant->syncPermissions([
            'view own reports',
            'view own scene cases',
            'edit own draft',
            'submit scene cases',
        ]);
    }
}
