<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Admin Dev',
            'email' => 'admindev@gmail.com',
            'email_verified_at' => null,
            'password' => '$2y$12$4KHY6wX4sLr4mBvtT.zhzODoo82v3u1Q.FAXraLseYhnehlZjgtPm',
            'remember_token' => null,
            'dealer_type' => 'Main Dealer',
            'dealer_name' => 'Mitra Sendang Kemakmuran',
            'department' => 'IT',
            'role' => 'Super Admin',
            'last_login' => null,
            'last_session' => null,
            'photo_path' => null,
            'is_darkmode' => null,
            'language' => 'en',
            'is_active' => 1,
            'created_at' => '2024-01-20 02:46:46',
            'updated_at' => '2025-04-09 14:53:25',
        ]);

        DB::table('users')->insert([
            'id' => 2,
            'name' => 'Admin Recruitment',
            'email' => 'adminrecruitment@gmail.com',
            'email_verified_at' => null,
            'password' => '$2y$12$4KHY6wX4sLr4mBvtT.zhzODoo82v3u1Q.FAXraLseYhnehlZjgtPm',
            'remember_token' => null,
            'dealer_type' => 'Main Dealer',
            'dealer_name' => 'Mitra Sendang Kemakmuran',
            'department' => 'IT',
            'role' => 'Admin',
            'last_login' => null,
            'last_session' => null,
            'photo_path' => null,
            'is_darkmode' => null,
            'language' => 'en',
            'is_active' => 1,
            'created_at' => '2024-01-20 02:46:46',
            'updated_at' => '2025-04-09 14:53:25',
        ]);
    }
}
