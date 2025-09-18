<?php

namespace App\Services;

use App\Models\Sys\SysDeptModel;
use App\Support\Trait\RequestJson;
use Illuminate\Http\JsonResponse;

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
        $data = $this->getTreeData($data, 'id');

        return $this->success(compact('data'));
    }
}
