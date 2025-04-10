<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DropdownSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('mst_dropdowns')->insert([
            [
                'id' => 1,
                'category' => 'Role User',
                'name_value' => 'Super Admin',
                'code_format' => 'SA',
                'is_active' => 1,
                'created_at' => '2024-01-20 04:35:49',
                'updated_at' => '2024-01-20 04:35:49',
            ],
            [
                'id' => 2,
                'category' => 'Role User',
                'name_value' => 'Admin',
                'code_format' => 'AD',
                'is_active' => 1,
                'created_at' => '2024-01-20 04:38:10',
                'updated_at' => '2024-01-20 04:44:47',
            ],
            [
                'id' => 3,
                'category' => 'Role User',
                'name_value' => 'User',
                'code_format' => 'US',
                'is_active' => 1,
                'created_at' => '2024-01-26 23:47:51',
                'updated_at' => '2024-05-29 13:26:46',
            ],
        ]);
    }
}
