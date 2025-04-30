<?php

namespace App\Generator;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class Generator
{
    private string $table;

    private Collection $columns;

    public function __construct(string $table)
    {
        $this->table = $table;
        $this->columns = $this->getSchemaColumns($table)->map(function ($column) use ($table) {
            return new Column($table, $column);
        });
    }

    public function renderMigration($column)
    {
        $up = '';
        $down = '';
    }

    public static function moules(): array
    {
        return collect(File::directories(app_path()))->map(function ($path) {
            return Str::after($path, app_path() . DIRECTORY_SEPARATOR);
        })->toArray();
    }

    public static function analysis()
    {

    }

    public function getSchemaColumns(string $table): Collection
    {
        $tableName =  substr($table, strlen(DB::getTablePrefix()));
        return new Collection(Schema::getColumns(Schema::getCurrentSchemaName() . '.' . $tableName));
    }

}