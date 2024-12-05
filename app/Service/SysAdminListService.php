<?php

namespace App\Service;

use App\Models\Admin\AdminModel;
use App\Trait\RequestJson;
use Illuminate\Http\JsonResponse;

class SysAdminListService
{
    use RequestJson;

    /**
     * 重置密码
     */
    public function resetPassword(array $data): JsonResponse
    {
        $model = AdminModel::query()->find($data['id']);
        $model->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $model->save();

        return $this->success('ok');
    }
}
