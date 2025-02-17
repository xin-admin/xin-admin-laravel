<?php

namespace Database\Seeders;

use App\Models\DictItemModel;
use Illuminate\Database\Seeder;

class DictItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DictItemModel::insert([
            [
                'id' => 1,
                'dict_id' => 1,
                'label' => '男',
                'value' => '0',
                'switch' => '1',
                'status' => 'default',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'dict_id' => 1,
                'label' => '女',
                'value' => '1',
                'switch' => '1',
                'status' => 'default',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 3,
                'dict_id' => 1,
                'label' => '其它',
                'value' => '2',
                'switch' => '1',
                'status' => 'default',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 4,
                'dict_id' => 2,
                'label' => '开启',
                'value' => '1',
                'switch' => '1',
                'status' => 'success',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 5,
                'dict_id' => 2,
                'label' => '关闭',
                'value' => '0',
                'switch' => '1',
                'status' => 'error',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 6,
                'dict_id' => 3,
                'label' => '一级菜单',
                'value' => '0',
                'switch' => '1',
                'status' => 'processing',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 7,
                'dict_id' => 3,
                'label' => '子菜单',
                'value' => '1',
                'switch' => '1',
                'status' => 'success',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 8,
                'dict_id' => 3,
                'label' => '按钮/API',
                'value' => '2',
                'switch' => '1',
                'status' => 'default',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 9,
                'dict_id' => 4,
                'label' => '管理员操作',
                'value' => '0',
                'switch' => '1',
                'status' => 'processing',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 10,
                'dict_id' => 4,
                'label' => '消费',
                'value' => '1',
                'switch' => '1',
                'status' => 'error',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 11,
                'dict_id' => 4,
                'label' => '签到奖励',
                'value' => '2',
                'switch' => '1',
                'status' => 'success',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
