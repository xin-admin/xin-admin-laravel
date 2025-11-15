<?php

namespace App\Http\Controllers\Sys;

use App\Http\Controllers\BaseController;
use App\Models\Sys\SysDictModel;
use App\Providers\AnnoRoute\Attribute\Create;
use App\Providers\AnnoRoute\Attribute\DeleteMapping;
use App\Providers\AnnoRoute\Attribute\GetMapping;
use App\Providers\AnnoRoute\Attribute\Query;
use App\Providers\AnnoRoute\Attribute\RequestMapping;
use App\Providers\AnnoRoute\Attribute\Update;
use App\Repositories\RepositoryInterface;
use App\Repositories\Sys\SysDictRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

/**
 * 字典管理
 */
#[RequestMapping('/system/dict', 'system.dict')]
#[Query, Create, Update]
class SysDictController extends BaseController
{
    protected array $noPermission = ['itemList'];

    protected function repository(): RepositoryInterface
    {
        return app(SysDictRepository::class);
    }

    /** 删除字典 */
    #[DeleteMapping('/{id}', 'delete')]
    public function delete($id): JsonResponse
    {
        $model = SysDictModel::findOrFail($id);
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
            $dict = SysDictModel::getDictItems();
            // 缓存字典
            Cache::store('redis')->put('bar', $dict, 600);
        }
        return $this->success($dict);
    }
}
