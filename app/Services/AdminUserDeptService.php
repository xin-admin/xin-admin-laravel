<?php

namespace App\Services;

use App\Models\AdminDeptModel;
use App\Support\Trait\RequestJson;
use Illuminate\Http\JsonResponse;

class AdminUserDeptService extends \App\Services\BaseService
{
    use RequestJson;

    /**
     * 获取部门树状列表
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $model = new AdminDeptModel;
        $data = $model->orderBy('sort', 'desc')->get()->toArray();
        $data = $this->getTreeData($data, 'dept_id');

        return $this->success(compact('data'));
    }
}
