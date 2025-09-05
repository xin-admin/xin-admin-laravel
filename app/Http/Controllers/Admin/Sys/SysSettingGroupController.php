<?php

namespace App\Http\Controllers\Admin\Sys;

use App\Http\Controllers\BaseController;
use App\Models\Sys\SysSettingGroupModel;
use App\Repositories\Sys\SysSettingGroupRepository;
use Illuminate\Http\JsonResponse;
use Xin\AnnoRoute\Attribute\Create;
use Xin\AnnoRoute\Attribute\DeleteMapping;
use Xin\AnnoRoute\Attribute\Query;
use Xin\AnnoRoute\Attribute\RequestMapping;
use Xin\AnnoRoute\Attribute\Update;

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
        $model = $this->model->find($id);
        $count = $model->settings()->count();
        if ($count > 0) {
            return $this->error('请先删除该分组下的设置');
        }
        return $this->success($model->delete());
    }
}
