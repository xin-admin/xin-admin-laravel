<?php

use App\Providers\AppServiceProvider;
use Xin\AnnoRoute\RouteServiceProvider;
use Xin\Pagination\PaginationProvider;

return [
    AppServiceProvider::class,
    RouteServiceProvider::class,
    PaginationProvider::class,
];
