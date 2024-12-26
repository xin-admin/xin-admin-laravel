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
use App\Http\Admin\Requests\DictRequest;
use App\Http\BaseController;
use App\Models\DictModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

/**
 * 字典管理
 */
#[AdminController]
#[RequestMapping('/admin/dict')]
class DictController extends BaseController
{
    public function __construct()
    {
        $this->model = new DictModel;
        $this->searchField = [
            'id' => '=',
            'name' => 'like',
            'code' => '=',
            'type' => '=',
            'created_at' => 'date',
            'updated_at' => 'date',
        ];
    }

    /** 获取字典列表 */
    #[GetMapping] #[Authorize('list')]
    public function list(): JsonResponse
    {
        return $this->listResponse();
    }

    /** 添加字典 */
    #[PostMapping] #[Authorize('add')]
    public function add(DictRequest $request): JsonResponse
    {
        return $this->addResponse($request);
    }

    /** 修改字典信息 */
    #[PutMapping] #[Authorize('admin.dict.edit')]
    public function edit(DictRequest $request): JsonResponse
    {
        return $this->editResponse($request);
    }

    /** 删除字典 */
    #[DeleteMapping] #[Authorize('admin.dict.delete')]
    public function delete(): JsonResponse
    {
        // TODO 删除字典需判断是否有子项
        return $this->deleteResponse();
    }

    /** 获取字典 */
    #[GetMapping('/list')] #[Authorize('admin.dict.list')]
    public function itemList(): JsonResponse
    {
        if (Cache::has('sys_dict')) {
            $dict = Cache::get('sys_dict');
        } else {
            $dict = $this->model->getDictItems();
            // 缓存字典
            Cache::store('redis')->put('bar', $dict, 600);
        }

        return $this->success($dict);
    }
}
