<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\SysDeptFormRequest;
use App\Models\System\SysDeptModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Xin\AnnoRoute\Attribute\DeleteRoute;
use Xin\AnnoRoute\Attribute\GetRoute;
use Xin\AnnoRoute\Attribute\PostRoute;
use Xin\AnnoRoute\Attribute\PutRoute;
use Xin\AnnoRoute\Attribute\RequestAttribute;

/**
 * 部门管理控制器
 */
#[RequestAttribute('/system/dept', 'system.dept')]
class SysDeptController extends BaseController
{

    /** 部门列表 */
    #[GetRoute(authorize: 'query')]
    public function query(): JsonResponse
    {
        $data = SysDeptModel::orderBy('sort', 'desc')->get()->toArray();
        $data = getTreeData($data);
        return $this->success($data);
    }

    /** 创建部门 */
    #[PostRoute(authorize: 'create')]
    public function create(SysDeptFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $model = SysDeptModel::create($validated);
        if (empty($model)) {
            return $this->error();
        }
        return $this->success();
    }

    /** 编辑部门 */
    #[PutRoute(
        route: '/{id}',
        authorize: 'update',
        where: ['id' => '[0-9]+']
    )]
    public function update(int $id, SysDeptFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $model = SysDeptModel::find($id);
        if (empty($model)) {
            return $this->error();
        }
        $model->update($validated);
        return $this->success();
    }

    /** 删除部门 */
    #[DeleteRoute(authorize: 'delete')]
    public function delete(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:sys_dept,id'
        ]);

        $ids = $request->input('ids');

        $departmentsWithChildren = SysDeptModel::whereIn('id', $ids)
            ->whereHas('children')
            ->get();

        if ($departmentsWithChildren->isNotEmpty()) {
            return $this->error('存在下级部门的部门无法删除');
        }

        SysDeptModel::whereIn('id', $ids)->delete();
        return $this->success('部门删除成功');
    }

    /** 获取部门用户列表 */
    #[GetRoute('/users/{id}', 'users')]
    public function users(int $id): JsonResponse
    {
        $model = SysDeptModel::query()->find($id);
        if (empty($model)) {
            return $this->error('部门不存在');
        }
        $pageSize = request()->input('pageSize') ?? 10;
        $data = $model->users()
            ->select(['id', 'username', 'nickname', 'email', 'mobile', 'status'])
            ->paginate($pageSize)
            ->toArray();
        return $this->success($data);
    }
}
