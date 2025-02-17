<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            AdminDeptSeeder::class,
            AdminRuleSeeder::class,
            AdminRoleSeeder::class,
            AdminUserSeeder::class,
            DictSeeder::class,
            DictItemSeeder::class,
        ]);
    }
}
