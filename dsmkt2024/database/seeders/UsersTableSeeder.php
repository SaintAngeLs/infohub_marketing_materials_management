<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'users_groups_id' => 1,
                'branch_id' => 1,
                'name' => 'John',
                'surname' => 'Doe',
                'email' => 'test@email.com',
                'phone' => '123456789',
                'password' => Hash::make('password123'),
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
                'password_valid' => 90,
                'password_last_changed' => now(),
                'token' => null,
                'token_time' => null,
                'last_login' => now(),
                'active' => 1,
                'created_at' => now(),
                'fingerprint' => now(),
                'remember_token' => null,
                'updated_at' => now(),
            ],
            [
                'users_groups_id' => 2,
                'branch_id' => 2,
                'name' => 'Jane',
                'surname' => 'Smith',
                'email' => 'jane.smith@example.com',
                'phone' => '987654321',
                'password' => Hash::make('password456'),
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
                'password_valid' => 90,
                'password_last_changed' => now(),
                'token' => null,
                'token_time' => null,
                'last_login' => now(),
                'active' => 1,
                'created_at' => now(),
                'fingerprint' => now(),
                'remember_token' => null,
                'updated_at' => now(),
            ],
        ]);
    }
}
