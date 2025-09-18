<?php

namespace App\Http\Controllers\Admin\Sys;

use App\Http\Controllers\BaseController;
use App\Models\Sys\SysSettingGroupModel;
use App\Providers\AnnoRoute\Attribute\Create;
use App\Providers\AnnoRoute\Attribute\DeleteMapping;
use App\Providers\AnnoRoute\Attribute\Query;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Providers\AnnoRoute\Attribute\Update;
use App\Repositories\Sys\SysSettingGroupRepository;
use Illuminate\Http\JsonResponse;

/**
 * 设置分组控制器
 */
#[RequestMapping('/system/setting/group', 'system.setting.group')]
#[Query, Create, Update]
class SysSettingGroupController extends BaseController
{
    public function __construct(SysSettingGroupModel $model, SysSettingGroupRepository $repository)
    {
        $this->model = $model;
        $this->repository = $repository;
    }

    /** 删除设置 */
    #[DeleteMapping('/{id}', 'delete')]
    public function delete($id): JsonResponse
    {
        $this->repository->delete($id);
        return $this->success('ok');
    }
}
