<?php

namespace App\Controllers\System;

use App\Controllers\BaseController;
use App\Services\AnnoRoute\Crud\Create;
use App\Services\AnnoRoute\Crud\Delete;
use App\Services\AnnoRoute\Crud\Query;
use App\Services\AnnoRoute\Crud\Update;
use App\Services\AnnoRoute\RequestAttribute;
use App\Services\AnnoRoute\Route\PostRoute;
use App\Services\AnnoRoute\Route\PutRoute;
use App\Services\SysSettingService;
use App\Services\System\SysSettingItemsService;
use Illuminate\Http\JsonResponse;

/**
 * 系统设置
 */
#[RequestAttribute('/system/setting/items', 'system.setting.items')]
#[Query, Create, Update, Delete]
class SysSettingItemsController extends BaseController
{
    public function __construct(
        protected SysSettingItemsService $service
    ) {}

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
