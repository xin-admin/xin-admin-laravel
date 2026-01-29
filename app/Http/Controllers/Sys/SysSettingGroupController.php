<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Crud\Create;
use App\Providers\AnnoRoute\Crud\Delete;
use App\Providers\AnnoRoute\Crud\Query;
use App\Providers\AnnoRoute\Crud\Update;
use App\Providers\AnnoRoute\RequestAttribute;
use App\Repositories\RepositoryInterface;
use App\Repositories\Sys\SysSettingGroupRepository;

/**
 * 设置分组控制器
 */
#[RequestAttribute('/system/setting/group', 'system.setting.group')]
#[Query, Create, Update, Delete]
class SysSettingGroupController extends BaseController
{
    protected string $repository = SysSettingGroupRepository::class;
}
