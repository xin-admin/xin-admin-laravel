<?php

use App\Providers\AppServiceProvider;
use Modules\AnnoRoute\RouteServiceProvider;
use Modules\Common\Providers\PaginationProvider;
use Modules\SystemAgent\Providers\SystemAgentServiceProvider;
use Modules\SystemTool\Providers\SystemToolServiceProvider;
use Modules\SystemUser\Providers\SystemUserServiceProvider;

return [
    AppServiceProvider::class,
    RouteServiceProvider::class,
    PaginationProvider::class,
    SystemAgentServiceProvider::class,
    SystemUserServiceProvider::class,
    SystemToolServiceProvider::class,
];
