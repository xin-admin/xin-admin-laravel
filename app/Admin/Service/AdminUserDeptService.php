<?php

namespace App\Admin\Service;

use App\Common\Models\AdminDeptModel;
use App\Common\Service\BaseService;
use App\Common\Trait\RequestJson;
use Illuminate\Http\JsonResponse;

class AdminUserDeptService extends BaseService
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
