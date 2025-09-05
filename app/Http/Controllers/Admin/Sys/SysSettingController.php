<?php

namespace App\Http\Controllers\Admin\Sys;

use App\Http\Controllers\BaseController;
use App\Models\Sys\SysSettingModel;
use App\Repositories\Sys\SysSettingRepository;
use App\Services\SysSettingService;
use Illuminate\Http\JsonResponse;
use Xin\AnnoRoute\Attribute\Create;
use Xin\AnnoRoute\Attribute\Delete;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\PostMapping;
use Xin\AnnoRoute\Attribute\PutMapping;
use Xin\AnnoRoute\Attribute\Query;
use Xin\AnnoRoute\Attribute\RequestMapping;
use Xin\AnnoRoute\Attribute\Update;

/**
 * 系统设置
 */
#[RequestMapping('/system/setting', 'system.setting')]
#[Query, Create, Update, Delete]
class SysSettingController extends BaseController
{
    public function __construct(SysSettingModel $model, SysSettingRepository $repository) {
        $this->model = $model;
        $this->repository = $repository;
    }

    /** 通过ID获取设置列表 */
    #[GetMapping('/query/{id}', 'query')]
    public function get(int $id): JsonResponse
    {
        $data = $this->model->where('group_id', $id)->get()->toArray();
        return $this->success($data);
    }

    #[PutMapping('/save/{id}', 'save')]
    public function save(int $id): JsonResponse
    {
        $value = request()->all();
        foreach ($value as $k => $v) {
            $model = $this->model->where('key', $k)->where('group_id', $id)->first();
            if ($model) {
                $model->values = $v;
                $model->save();
            }
        }
        return $this->success('保存成功');
    }

    #[PostMapping('/refreshCache', 'refresh')]
    public function refreshCache(): JsonResponse
    {
        SysSettingService::refreshSettings();
        return $this->success('重载成功');
    }
}
