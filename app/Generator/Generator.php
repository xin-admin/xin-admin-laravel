<?php

namespace App\Generator;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Generator
{

    public static function moules(): array
    {
        return collect(File::directories(app_path()))->map(function ($path) {
            return Str::after($path, app_path() . DIRECTORY_SEPARATOR);
        })->toArray();
    }

    public static function analysis()
    {

    }

}