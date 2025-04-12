<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Requests\SystemRequest\DictRequest;
use App\Http\BaseController;
use App\Models\DictModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Xin\AnnoRoute\Attribute\Authorize;
use Xin\AnnoRoute\Attribute\DeleteMapping;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\PostMapping;
use Xin\AnnoRoute\Attribute\PutMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;

/**
 * 字典管理
 */
#[RequestMapping('/system/dict')]
class DictController extends BaseController
{
    protected array $noPermission = ['itemList'];

    public function __construct()
    {
        $this->model = new DictModel;
        $this->quickSearchField = ['id', 'name', 'code'];
    }

    /** 获取字典列表 */
    #[GetMapping] #[Authorize('system.dict.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse();
    }

    /** 添加字典 */
    #[PostMapping] #[Authorize('system.dict.add')]
    public function add(DictRequest $request): JsonResponse
    {
        return $this->addResponse($request);
    }

    /** 修改字典信息 */
    #[PutMapping] #[Authorize('system.dict.edit')]
    public function edit(DictRequest $request): JsonResponse
    {
        return $this->editResponse($request);
    }

    /** 删除字典 */
    #[DeleteMapping] #[Authorize('system.dict.delete')]
    public function delete(): JsonResponse
    {
        // TODO 删除字典需判断是否有子项 （待完成）
        return $this->deleteResponse();
    }

    /** 获取字典 */
    #[GetMapping('/list')]
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
