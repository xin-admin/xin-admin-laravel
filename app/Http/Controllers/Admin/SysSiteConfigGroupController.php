<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\RepositoryException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\SysSiteConfigGroupFormRequest;
use App\Models\SysSiteConfigGroupModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Xin\AttrRoute\Attribute\DeleteRoute;
use Xin\AttrRoute\Attribute\GetRoute;
use Xin\AttrRoute\Attribute\PostRoute;
use Xin\AttrRoute\Attribute\PutRoute;
use Xin\AttrRoute\Attribute\RequestAttribute;

/**
 * 设置分组控制器
 */
#[RequestAttribute('/system/config/group', 'system.config.group')]
class SysSiteConfigGroupController extends BaseController
{
    /** 查询设置分组列表 */
    #[GetRoute(authorize: 'query')]
    public function query(Request $request): JsonResponse
    {
        $params = $request->all();
        $query = SysSiteConfigGroupModel::query();

        if (!empty($params['keywordSearch'])) {
            $query->whereAny(
                ['title', 'remark', 'key'],
                'like',
                '%' . str_replace('%', '\%', $params['keywordSearch']) . '%'
            );
        }

        $data = $query->get()->toArray();
        return $this->success($data);
    }

    /** 创建设置分组 */
    #[PostRoute(authorize: 'create')]
    public function create(SysSiteConfigGroupFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $model = SysSiteConfigGroupModel::create($validated);
        if (empty($model)) {
            return $this->error();
        }
        return $this->success();
    }

    /** 编辑设置分组 */
    #[PutRoute(
        route: '/{id}',
        authorize: 'update',
        where: ['id' => '[0-9]+']
    )]
    public function update(int $id, SysSiteConfigGroupFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $model = SysSiteConfigGroupModel::find($id);
        if (empty($model)) {
            return $this->error();
        }
        $model->update($validated);
        return $this->success();
    }

    /** 删除设置分组 */
    #[DeleteRoute(
        route: '/{id}',
        authorize: 'delete',
        where: ['id' => '[0-9]+']
    )]
    public function delete(int $id): JsonResponse
    {
        $model = SysSiteConfigGroupModel::find($id);
        if (empty($model)) {
            return $this->error();
        }
        $count = $model->configs()->count();
        if ($count > 0) {
            throw new RepositoryException('当前分组有未删除的设置项！');
        }
        $model->delete();
        return $this->success();
    }
}
