<?php

use App\Providers\AppServiceProvider;
use Xin\AnnoRoute\RouteServiceProvider;
use App\Providers\PaginationProvider;

return [
    AppServiceProvider::class,
    RouteServiceProvider::class,
    PaginationProvider::class,
];
