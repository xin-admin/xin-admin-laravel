<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admin_user')->insert([
            'user_id' => 1,
            'username' => 'admin',
            'nickname' => 'admin',
            'email' => Str::random(10).'@example.com',
            'password' => password_hash('123456', PASSWORD_DEFAULT),
            'dept_id' => 1,
            'role_id' => 1,
            'avatar_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
