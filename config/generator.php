<?php

return [

    'controller' => [

        'path' => 'app/Http/Admin/Controllers',

        'namespace' => 'App\Http\Admin\Controllers',

    ],

    'model' => [

        'path' => 'app/Models',

        'namespace' => 'App\Models',

    ],

    'request' => [

        'path' => 'app/Http/Admin/Requests',

        'namespace' => 'App\Http\Admin\Requests',

    ],

    'seeders' => [

        'path' => database_path('seeders' . DIRECTORY_SEPARATOR . 'Menu'),

        'namespace' => 'Database\Seeders\Menu',
    ],

    'migrations' => [

        'path' => database_path('migrations'),

    ],

    'web' => [

        'view' => [
            'path' => web_path('src' . DIRECTORY_SEPARATOR . 'pages'),
        ],

        'domain' => [
            'path' => web_path('src' . DIRECTORY_SEPARATOR . 'domain'),
        ]

    ],
];