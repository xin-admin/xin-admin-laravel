<?php

namespace Database\Seeders;

use App\Models\DictModel;
use Illuminate\Database\Seeder;

class DictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DictModel::insert([
            [
                'id' => 1,
                'name' => '性别',
                'type' => 'default',
                'describe' => '性别字典',
                'code' => 'sex',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'name' => '状态',
                'type' => 'default',
                'describe' => '状态字典',
                'code' => 'status',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'name' => '权限类型',
                'type' => 'default',
                'describe' => '系统权限类型字典',
                'code' => 'ruleType',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 4,
                'name' => '余额变动记录类型',
                'type' => 'default',
                'describe' => '前台用户余额变动记录类型',
                'code' => 'balanceType',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
