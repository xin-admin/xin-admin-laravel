<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\SysSettingGroupService;
use Xin\AnnoRoute\Crud\Create;
use Xin\AnnoRoute\Crud\Delete;
use Xin\AnnoRoute\Crud\Query;
use Xin\AnnoRoute\Crud\Update;
use Xin\AnnoRoute\RequestAttribute;

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
