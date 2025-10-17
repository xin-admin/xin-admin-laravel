<?php

use App\Providers\AnnoRoute\RouteServiceProvider;
use App\Providers\Telescope\TelescopeServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AutoBindServiceProvider::class,
    App\Http\Middleware\LanguageMiddleware::class,
    RouteServiceProvider::class,
    TelescopeServiceProvider::class,
];
