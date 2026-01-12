<?php

use App\Providers\RouteServiceProvider;
use App\Providers\Telescope\TelescopeServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AutoBindServiceProvider::class,
    RouteServiceProvider::class,
    TelescopeServiceProvider::class,
];
