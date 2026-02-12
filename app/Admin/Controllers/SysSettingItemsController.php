<?php

namespace App\Admin\Controllers;

use App\Admin\Services\SysSettingItemsService;
use App\Common\Controllers\BaseController;
use App\Common\Services\AnnoRoute\Crud\Create;
use App\Common\Services\AnnoRoute\Crud\Delete;
use App\Common\Services\AnnoRoute\Crud\Query;
use App\Common\Services\AnnoRoute\Crud\Update;
use App\Common\Services\AnnoRoute\RequestAttribute;
use App\Common\Services\AnnoRoute\Route\PostRoute;
use App\Common\Services\AnnoRoute\Route\PutRoute;
use App\Common\Services\SysSettingService;
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
