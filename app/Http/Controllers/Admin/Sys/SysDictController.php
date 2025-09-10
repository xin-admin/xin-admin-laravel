<?php

namespace App\Http\Controllers\Admin\Sys;

use App\Http\Controllers\BaseController;
use App\Models\Sys\SysDictModel;
use App\Repositories\Sys\SysDictRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Xin\AnnoRoute\Attribute\Create;
use Xin\AnnoRoute\Attribute\DeleteMapping;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\Query;
use Xin\AnnoRoute\Attribute\RequestMapping;
use Xin\AnnoRoute\Attribute\Update;

/**
 * 字典管理
 */
#[RequestMapping('/system/dict', 'system.dict')]
#[Query, Create, Update]
class SysDictController extends BaseController
{
    protected array $noPermission = ['itemList'];

    public function __construct(SysDictRepository $repository, SysDictModel $model)
    {
        $this->repository = $repository;
        $this->model = $model;
    }

    /** 删除字典 */
    #[DeleteMapping('/{id}', 'delete')]
    public function delete($id): JsonResponse
    {
        $model = $this->model->findOrFail($id);
        $count = $model->dictItems()->count();
        if ($count > 0) {
            return $this->error('字典包含子项！');
        }
        $model->delete();
        return $this->success('ok');
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
