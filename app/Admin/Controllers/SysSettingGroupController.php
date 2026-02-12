<?php

namespace App\Admin\Controllers;

use App\Admin\Services\SysSettingGroupService;
use App\Common\Controllers\BaseController;
use App\Common\Services\AnnoRoute\Crud\Create;
use App\Common\Services\AnnoRoute\Crud\Delete;
use App\Common\Services\AnnoRoute\Crud\Query;
use App\Common\Services\AnnoRoute\Crud\Update;
use App\Common\Services\AnnoRoute\RequestAttribute;

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
