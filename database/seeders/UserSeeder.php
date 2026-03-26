<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();
        $staffRole = Role::where('slug', 'staff')->first();

        // 1. Initial Super Admin
        User::firstOrCreate(
            ['email' => 'admin@inventaris.com'],
            [
                'name' => 'Administrator IT',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'is_active' => true,
            ]
        );

        // 2. Optional: Initial Staff IT (Commented out or add if needed)
        /*
        User::firstOrCreate(
            ['email' => 'staff@inventaris.com'],
            [
                'name' => 'Staff IT',
                'username' => 'staff',
                'password' => Hash::make('password'),
                'role_id' => $staffRole->id,
                'is_active' => true,
            ]
        );
        */
    }
}
