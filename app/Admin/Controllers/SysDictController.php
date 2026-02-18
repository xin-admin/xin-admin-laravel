<?php

namespace App\Admin\Controllers;

use App\Admin\Services\SysDictService;
use App\Common\Controllers\BaseController;
use App\Common\Services\AnnoRoute\Crud\Create;
use App\Common\Services\AnnoRoute\Crud\Delete;
use App\Common\Services\AnnoRoute\Crud\Query;
use App\Common\Services\AnnoRoute\Crud\Update;
use App\Common\Services\AnnoRoute\RequestAttribute;
use App\Common\Services\AnnoRoute\Route\GetRoute;
use App\Common\Services\AnnoRoute\Route\PostRoute;
use Illuminate\Http\JsonResponse;

/**
 * 字典管理
 */
#[RequestAttribute('/system/dict/list', 'system.dict.list')]
#[Query, Create, Update, Delete]
class SysDictController extends BaseController
{
    public function __construct(
        protected SysDictService $service
    ) {}

    /**
     * 获取所有字典数据
     */
    #[GetRoute('/all', false)]
    public function all(): array
    {
        return $this->service->getAllDict();
    }

    /**
     * 刷新字典缓存
     */
    #[PostRoute('/refresh', false)]
    public function refresh(): JsonResponse
    {
        $this->service->refreshCache();
        return $this->success('字典缓存刷新成功');
    }
}
