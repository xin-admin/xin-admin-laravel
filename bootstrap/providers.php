<?php

use App\Providers\AppServiceProvider;
use Modules\AnnoRoute\RouteServiceProvider;
use Modules\Common\Providers\PaginationProvider;
use Modules\FileManage\Providers\FileManageServiceProvider;
use Modules\SystemTool\Providers\SystemToolServiceProvider;
use Modules\SystemUser\Providers\SystemUserServiceProvider;

return [
    AppServiceProvider::class,
    RouteServiceProvider::class,
    PaginationProvider::class,
    FileManageServiceProvider::class,
    SystemUserServiceProvider::class,
    SystemToolServiceProvider::class,
];
