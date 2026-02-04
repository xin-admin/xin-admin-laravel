<?php

namespace App\Controllers\System;

use App\Controllers\BaseController;
use App\Services\AnnoRoute\Crud\Create;
use App\Services\AnnoRoute\Crud\Delete;
use App\Services\AnnoRoute\Crud\Query;
use App\Services\AnnoRoute\Crud\Update;
use App\Services\AnnoRoute\RequestAttribute;
use App\Services\System\SysSettingGroupService;

/**
 * 设置分组控制器
 */
#[RequestAttribute('/system/setting/group', 'system.setting.group')]
#[Query, Create, Update, Delete]
class SysSettingGroupController extends BaseController
{

    public function __construct(
        protected SysSettingGroupService $service
    ) {}

}
