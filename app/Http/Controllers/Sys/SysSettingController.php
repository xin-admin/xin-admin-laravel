<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\BaseController;
use App\Models\Sys\SysSettingModel;
use App\Providers\AnnoRoute\Attribute\Create;
use App\Providers\AnnoRoute\Attribute\Delete;
use App\Providers\AnnoRoute\Attribute\PostMapping;
use App\Providers\AnnoRoute\Attribute\PutMapping;
use App\Providers\AnnoRoute\Attribute\Query;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Providers\AnnoRoute\Attribute\Update;
use App\Repositories\RepositoryInterface;
use App\Repositories\Sys\SysSettingRepository;
use App\Services\SysSettingService;
use Illuminate\Http\JsonResponse;

/**
 * 系统设置
 */
#[RequestMapping('/system/setting', 'system.setting')]
#[Query, Create, Update, Delete]
class SysSettingController extends BaseController
{

    protected function repository(): RepositoryInterface
    {
        return app(SysSettingRepository::class);
    }


    /** 保存设置 */
    #[PutMapping('/save/{id}', 'save')]
    public function save(int $id): JsonResponse
    {
        $value = request()->all();
        foreach ($value as $k => $v) {
            $model = SysSettingModel::where('key', $k)->where('group_id', $id)->first();
            if ($model) {
                $model->values = $v;
                $model->save();
            }
        }
        return $this->success('保存成功');
    }

    /** 刷新设置 */
    #[PostMapping('/refreshCache', 'refresh')]
    public function refreshCache(): JsonResponse
    {
        SysSettingService::refreshSettings();
        return $this->success('重载成功');
    }
}
