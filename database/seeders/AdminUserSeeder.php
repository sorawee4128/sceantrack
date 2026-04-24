<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin'],
            [
                'name' => 'admin',
                'full_name' => 'System Administrator',
                'email' => 'admin@example.com',
                'phone' => '0800000000',
                'position' => 'System Admin',
                'password' => 'Admin@12345',
                'is_active' => true,
            ]
        );

        $user->syncRoles(['admin']);
    }
}
