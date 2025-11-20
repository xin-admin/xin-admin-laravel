<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\BaseController;
use App\Models\Sys\SysSettingGroupModel;
use App\Providers\AnnoRoute\Attribute\Create;
use App\Providers\AnnoRoute\Attribute\Delete;
use App\Providers\AnnoRoute\Attribute\DeleteMapping;
use App\Providers\AnnoRoute\Attribute\Query;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Providers\AnnoRoute\Attribute\Update;
use App\Repositories\RepositoryInterface;
use App\Repositories\Sys\SysSettingGroupRepository;
use Illuminate\Http\JsonResponse;

/**
 * 设置分组控制器
 */
#[RequestMapping('/system/setting/group', 'system.setting.group')]
#[Query, Create, Update, Delete]
class SysSettingGroupController extends BaseController
{
    protected function repository(): RepositoryInterface
    {
        return app(SysSettingGroupRepository::class);
    }
}
