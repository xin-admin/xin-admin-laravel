<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminDeptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admin_dept')->insert(
            [
                [
                    'dept_id' => 1,
                    'name' => '小刘快跑网络科技有限公司',
                    'parent_id' => 0,
                    'sort' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'dept_id' => 2,
                    'name' => '小刘洛阳分公司',
                    'parent_id' => 1,
                    'sort' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'dept_id' => 3,
                    'name' => '小刘郑州分公司',
                    'parent_id' => 1,
                    'sort' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'dept_id' => 4,
                    'name' => '小刘南阳分公司',
                    'parent_id' => 1,
                    'sort' => 2,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ]
        );
    }
}
