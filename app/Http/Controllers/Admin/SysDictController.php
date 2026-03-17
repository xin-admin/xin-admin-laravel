<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\Admin\SysDictService;
use Illuminate\Http\JsonResponse;
use Xin\AnnoRoute\Crud\Create;
use Xin\AnnoRoute\Crud\Delete;
use Xin\AnnoRoute\Crud\Query;
use Xin\AnnoRoute\Crud\Update;
use Xin\AnnoRoute\RequestAttribute;
use Xin\AnnoRoute\Route\GetRoute;

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
    public function all(): JsonResponse
    {
        $data = $this->service->getAllDict();
        return $this->success($data);
    }
}
