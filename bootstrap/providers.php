<?php

use App\Providers\AppServiceProvider;
use App\Providers\AutoBindServiceProvider;
use Xin\AnnoRoute\RouteServiceProvider;

return [
    AppServiceProvider::class,
    AutoBindServiceProvider::class,
    RouteServiceProvider::class,
];
