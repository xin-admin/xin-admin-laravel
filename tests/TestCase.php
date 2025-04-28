<?php

namespace Tests;

use App\Generator\Column;
use App\Generator\GeneratorEntry;
use App\Generator\Service\ModuleService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    //
    public function test_get_entry() {

        $entry = GeneratorEntry::make([
            'name' => 'User',
            'path' => 'user\name',
            'module' => 'Admin',
            'routePrefix' => '/user',
            'abilitiesPrefix' => 'user',
            'page' => [
                'is_file' => false,
                'route' => '/user/user',
            ],
            'crud' => [
                'find' => true,
                'create' => true,
                'update' => true,
                'delete' => true,
                'query' => true,
            ],
        ]);

        dump($entry->toArray());

    }

    public function test_modules() {

        $modules = ModuleService::moules();
        dump($modules);

    }

    public function test_column()
    {
        $data = Column::fromSchema('admin_user', 'user_id');
        echo $data->toSql();
        echo PHP_EOL;
        echo $data->toMigration();
    }
}
