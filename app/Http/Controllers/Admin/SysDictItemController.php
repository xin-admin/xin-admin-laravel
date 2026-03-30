<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\SysDictItemFormRequest;
use App\Models\System\SysDictItemModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Xin\AnnoRoute\Attribute\DeleteRoute;
use Xin\AnnoRoute\Attribute\GetRoute;
use Xin\AnnoRoute\Attribute\PostRoute;
use Xin\AnnoRoute\Attribute\PutRoute;
use Xin\AnnoRoute\Attribute\RequestAttribute;

/**
 * 字典项控制器
 */
#[RequestAttribute('/system/dict/item', 'system.dict.item')]
class SysDictItemController extends BaseController
{
    protected array $quickSearchField = ['label', 'value'];
    protected array $searchField = [
        'dict_id' => '=',
        'status' => '='
    ];

    public function __construct() {}

    /** 查询字典项列表 */
    #[GetRoute(authorize: 'query')]
    public function query(Request $request): JsonResponse
    {
        $params = $request->all();
        $pageSize = $params['pageSize'] ?? 10;
        $query = SysDictItemModel::query();
        $data = $this->buildSearch($params, $query)
            ->paginate($pageSize)
            ->toArray();
        return $this->success($data);
    }

    /** 创建字典项 */
    #[PostRoute(authorize: 'create')]
    public function create(SysDictItemFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $model = SysDictItemModel::create($validated);
        if (empty($model)) {
            return $this->error();
        }
        return $this->success();
    }

    /** 编辑字典项 */
    #[PutRoute(
        route: '/{id}',
        authorize: 'update',
        where: ['id' => '[0-9]+']
    )]
    public function update(int $id, SysDictItemFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $model = SysDictItemModel::find($id);
        if (empty($model)) {
            return $this->error();
        }
        $model->update($validated);
        return $this->success();
    }

    /** 删除字典项 */
    #[DeleteRoute(
        route: '/{id}',
        authorize: 'delete',
        where: ['id' => '[0-9]+']
    )]
    public function delete(int $id): JsonResponse
    {
        $model = SysDictItemModel::find($id);
        if (empty($model)) {
            return $this->error();
        }
        $model->delete();
        return $this->success();
    }
}
