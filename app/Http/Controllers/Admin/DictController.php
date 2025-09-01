<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\DictRequest;
use App\Models\SysDictModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Xin\AnnoRoute\Attribute\Create;
use Xin\AnnoRoute\Attribute\Delete;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\Query;
use Xin\AnnoRoute\Attribute\RequestMapping;
use Xin\AnnoRoute\Attribute\Update;

/**
 * 字典管理
 */
#[RequestMapping('/system/dict', 'system.dict')]
#[Query, Create, Update, Delete]
class DictController extends BaseController
{
    protected array $noPermission = ['itemList'];
    protected string $formRequest = DictRequest::class;
    protected string $model = SysDictModel::class;
    protected array $quickSearchField = ['id', 'name', 'code'];

    // TODO 删除字典需判断是否有子项 （待完成）

    /** 获取字典 */
    #[GetMapping('/list')]
    public function itemList(): JsonResponse
    {
        if (Cache::has('sys_dict')) {
            $dict = Cache::get('sys_dict');
        } else {
            $model = new SysDictModel();
            $dict = $model->getDictItems();
            // 缓存字典
            Cache::store('redis')->put('bar', $dict, 600);
        }

        return $this->success($dict);
    }
}
