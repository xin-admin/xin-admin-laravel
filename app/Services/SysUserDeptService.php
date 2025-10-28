<?php

namespace App\Services;

use App\Models\Sys\SysDeptModel;
use App\Support\Trait\RequestJson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SysUserDeptService extends Service
{
    use RequestJson;

    /**
     * 获取部门树状列表
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $model = new SysDeptModel();
        $data = $model->orderBy('sort', 'desc')->get()->toArray();
        $data = $this->getTreeData($data);

        return $this->success($data);
    }

    /**
     * 批量删除部门
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:sys_dept,id'
        ]);

        $ids = $request->input('ids');

        // 检查是否有下级部门
        $departmentsWithChildren = SysDeptModel::whereIn('id', $ids)
            ->whereHas('children')
            ->get();

        if ($departmentsWithChildren->isNotEmpty()) {
            return $this->error('存在下级部门的部门无法删除');
        }

        // 执行删除
        SysDeptModel::whereIn('id', $ids)->delete();

        return $this->success('部门删除成功');
    }

    /**
     * 获取部门用户列表
     * @param int $id
     * @return JsonResponse
     */
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
