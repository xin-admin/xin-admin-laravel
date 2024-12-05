<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\Autowired;
use App\Attribute\route\DeleteMapping;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\PutMapping;
use App\Attribute\route\RequestMapping;
use App\Http\Admin\Requests\DictItemRequest;
use App\Http\BaseController;
use App\Models\Dict\DictItemModel;
use Illuminate\Http\JsonResponse;

/**
 * 字典项控制器
 */
#[AdminController]
#[RequestMapping('/admin/dict/item')]
class DictItemController extends BaseController
{
    #[Autowired]
    protected DictItemModel $model;

    // 查询字段
    protected array $searchField = [
        'name' => 'like',
        'dict_id' => '=',
    ];

    /**
     * 获取字典项列表
     */
    #[GetMapping]
    #[Authorize('admin.dict.item.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse($this->model);
    }

    /**
     * 添加字典项
     */
    #[PostMapping]
    #[Authorize('admin.dict.item.add')]
    public function add(DictItemRequest $request): JsonResponse
    {
        return $this->addResponse($this->model, $request);
    }

    /**
     * 编辑字典项
     */
    #[PutMapping]
    #[Authorize('admin.dict.item.edit')]
    public function edit(DictItemRequest $request): JsonResponse
    {
        return $this->editResponse($this->model, $request);
    }

    /**
     * 删除字典项
     */
    #[DeleteMapping]
    #[Authorize('admin.dict.item.delete')]
    public function delete(): JsonResponse
    {
        return $this->deleteResponse($this->model);
    }
}
