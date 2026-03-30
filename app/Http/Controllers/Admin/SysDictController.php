<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\RepositoryException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\SysDictFormRequest;
use App\Models\System\SysDictModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Xin\AnnoRoute\Attribute\DeleteRoute;
use Xin\AnnoRoute\Attribute\GetRoute;
use Xin\AnnoRoute\Attribute\PostRoute;
use Xin\AnnoRoute\Attribute\PutRoute;
use Xin\AnnoRoute\Attribute\RequestAttribute;

/**
 * 字典管理
 */
#[RequestAttribute('/system/dict/list', 'system.dict.list')]
class SysDictController extends BaseController
{
    protected array $quickSearchField = ['name', 'code', 'describe'];
    protected array $searchField = [
        'status' => '='
    ];

    /** 查询字典列表 */
    #[GetRoute(authorize: 'query')]
    public function query(Request $request): JsonResponse
    {
        $params = $request->all();
        $pageSize = $params['pageSize'] ?? 10;
        $query = SysDictModel::query();
        $data = $this->buildSearch($params, $query)
            ->paginate($pageSize)
            ->toArray();
        return $this->success($data);
    }

    /** 创建字典 */
    #[PostRoute(authorize: 'create')]
    public function create(SysDictFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $model = SysDictModel::create($validated);
        if (empty($model)) {
            return $this->error();
        }
        return $this->success();
    }

    /** 编辑字典 */
    #[PutRoute(
        route: '/{id}',
        authorize: 'update',
        where: ['id' => '[0-9]+']
    )]
    public function update(int $id, SysDictFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $model = SysDictModel::find($id);
        if (empty($model)) {
            return $this->error();
        }
        $model->update($validated);
        return $this->success();
    }

    /** 删除字典 */
    #[DeleteRoute(
        route: '/{id}',
        authorize: 'delete',
        where: ['id' => '[0-9]+']
    )]
    public function delete(int $id): JsonResponse
    {
        $model = SysDictModel::find($id);
        if (empty($model)) {
            throw new RepositoryException('字典不存在');
        }
        $count = $model->dictItems()->count();
        if ($count > 0) {
            throw new RepositoryException('字典包含子项，请先删除子项！');
        }
        $model->delete();
        return $this->success();
    }

    /** 获取所有字典数据 */
    #[GetRoute('/all', false)]
    public function all(): JsonResponse
    {
        $data = SysDictModel::getAllDictWithItems();
        return $this->success($data);
    }
}
