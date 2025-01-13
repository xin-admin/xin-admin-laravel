<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\route\DeleteMapping;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\Admin\Requests\DictItemRequest;
use App\Http\BaseController;
use App\Models\DictItemModel;
use Illuminate\Http\JsonResponse;

/**
 * 字典项控制器
 */
#[AdminController]
#[RequestMapping('/system/dict/item')]
class DictItemController extends BaseController
{
    public function __construct()
    {
        $this->model = new DictItemModel;
        $this->searchField = [
            'name' => 'like',
            'dict_id' => '=',
        ];
    }

    /** 获取字典项列表 */
    #[GetMapping] #[Authorize('system.dict.item.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse();
    }

    /** 添加字典项 */
    #[PostMapping] #[Authorize('system.dict.item.add')]
    public function add(DictItemRequest $request): JsonResponse
    {
        return $this->addResponse($request);
    }

    /** 编辑字典项 */
    #[PutMapping] #[Authorize('system.dict.item.edit')]
    public function edit(DictItemRequest $request): JsonResponse
    {
        return $this->editResponse($request);
    }

    /** 删除字典项 */
    #[DeleteMapping] #[Authorize('system.dict.item.delete')]
    public function delete(): JsonResponse
    {
        return $this->deleteResponse();
    }
}
