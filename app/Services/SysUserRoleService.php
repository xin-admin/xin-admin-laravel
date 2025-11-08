<?php

namespace App\Services;

use App\Models\Sys\SysRoleModel;
use Illuminate\Http\JsonResponse;

class SysUserRoleService extends Service
{

    /**
     * 设置状态
     */
    public function setStatus($id): JsonResponse
    {
        $model = SysRoleModel::find($id);
        if (! $model) {
            return $this->error(__('system.data_not_exist'));
        }
        $model->status = $model->status ? 0 : 1;
        $model->save();
        return $this->success();
    }

}