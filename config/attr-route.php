<?php

use App\Models\SysUserModel;

return [
    /*
    |--------------------------------------------------------------------------
    | Auto Scan
    |--------------------------------------------------------------------------
    |
    | When enabled, the AppServiceProvider scans scan_paths on application boot
    | and registers controllers carrying route attributes. You can also
    | disable it and call manually:
    | app(AttrRoute::class)->register($path);
    |
    */
    'auto_scan' => true,

    /*
    |--------------------------------------------------------------------------
    | Scan Paths
    |--------------------------------------------------------------------------
    |
    | Controller directories scanned for attribute routes. The scanner
    | recursively looks for *Controller.php files.
    |
    */
    'scan_paths' => [
        app_path('Http/Controllers'),
    ],

    /*
    |--------------------------------------------------------------------------
    | User Models
    |--------------------------------------------------------------------------
    |
    | User model map keyed by alias. In multi-user-table mode it verifies
    | which model a token belongs to. When `default` is empty, the token's
    | model is not verified.
    |
    */
    'models' => [
        'default' => SysUserModel::class,
    ]
];
