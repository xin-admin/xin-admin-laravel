<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\SysSettingItemsService;
use App\Services\SysSettingService;
use Illuminate\Http\JsonResponse;
use Xin\AnnoRoute\Crud\Create;
use Xin\AnnoRoute\Crud\Delete;
use Xin\AnnoRoute\Crud\Query;
use Xin\AnnoRoute\Crud\Update;
use Xin\AnnoRoute\RequestAttribute;
use Xin\AnnoRoute\Route\PostRoute;
use Xin\AnnoRoute\Route\PutRoute;

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
