<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('mst_rules')->insert([
            [
                'id' => 1,
                'rule_name' => 'Development',
                'rule_value' => '1',
                'is_active' => 1,
                'created_at' => '2024-04-24 09:23:49',
                'updated_at' => '2025-02-19 14:18:58',
            ],
            [
                'id' => 2,
                'rule_name' => 'Email Development',
                'rule_value' => 'harusimam@gmail.com',
                'is_active' => 1,
                'created_at' => '2024-04-24 09:24:25',
                'updated_at' => '2025-02-22 01:19:23',
            ],
            [
                'id' => 3,
                'rule_name' => 'Turn On Audit Log',
                'rule_value' => '0',
                'is_active' => 1,
                'created_at' => '2024-05-12 09:18:33',
                'updated_at' => '2024-05-12 09:18:33',
            ],
        ]);
    }
}
