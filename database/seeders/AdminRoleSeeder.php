<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admin_role')->insert(
            [
                [
                    'role_id' => 1,
                    'name' => '超级管理员',
                    'rules' => '*',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'role_id' => 2,
                    'name' => '财务',
                    'rules' => '1,2,3,4',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'role_id' => 3,
                    'name' => '电商总监',
                    'rules' => '1,2,3,4',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'role_id' => 4,
                    'name' => '市场运营',
                    'rules' => '5,6,7,8,9,10,11',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ]
        );
    }
}
