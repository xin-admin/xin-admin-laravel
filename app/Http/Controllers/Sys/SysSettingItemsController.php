<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\BaseController;
use App\Providers\AnnoRoute\Crud\Create;
use App\Providers\AnnoRoute\Crud\Delete;
use App\Providers\AnnoRoute\Crud\Query;
use App\Providers\AnnoRoute\Crud\Update;
use App\Providers\AnnoRoute\RequestAttribute;
use App\Providers\AnnoRoute\Route\PostRoute;
use App\Providers\AnnoRoute\Route\PutRoute;
use App\Repositories\RepositoryInterface;
use App\Repositories\Sys\SysSettingItemsRepository;
use App\Services\SysSettingService;
use Illuminate\Http\JsonResponse;

/**
 * 系统设置
 */
#[RequestAttribute('/system/setting/items', 'system.setting.items')]
#[Query, Create, Update, Delete]
class SysSettingItemsController extends BaseController
{
    protected string $repository = SysSettingItemsRepository::class;

    /** 保存设置 */
    #[PutRoute('/save/{id}', 'save')]
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
    #[PostRoute('/refreshCache', 'refresh')]
    public function refreshCache(): JsonResponse
    {
        SysSettingService::refreshSettings();
        return $this->success('重载成功');
    }
}
