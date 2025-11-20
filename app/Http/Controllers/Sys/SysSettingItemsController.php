<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\BaseController;
use App\Models\Sys\SysSettingItemsModel;
use App\Providers\AnnoRoute\Attribute\Create;
use App\Providers\AnnoRoute\Attribute\Delete;
use App\Providers\AnnoRoute\Attribute\PostMapping;
use App\Providers\AnnoRoute\Attribute\PutMapping;
use App\Providers\AnnoRoute\Attribute\Query;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Providers\AnnoRoute\Attribute\Update;
use App\Repositories\RepositoryInterface;
use App\Repositories\Sys\SysSettingItemsRepository;
use App\Services\SysSettingService;
use Illuminate\Http\JsonResponse;

/**
 * 系统设置
 */
#[RequestMapping('/system/setting/items', 'system.setting')]
#[Query, Create, Update, Delete]
class SysSettingItemsController extends BaseController
{

    protected function repository(): RepositoryInterface
    {
        return app(SysSettingItemsRepository::class);
    }

    /** 保存设置 */
    #[PutMapping('/save/{id}', 'save')]
    public function save(int $id, SysSettingService $service): JsonResponse
    {
        $values = request()->input('values');
        if (is_null($values)) {
            return $this->error('请提供设置值');
        }
        $service->setSetting($id, $values);
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
