<?php

namespace App\Service;

use App\Models\AdminUserModel;
use App\Trait\RequestJson;
use Illuminate\Http\JsonResponse;

class AdminUserListService
{
    use RequestJson;

    /**
     * 重置密码
     */
    public function resetPassword(array $data): JsonResponse
    {
        AdminUserModel::where('user_id', $data['id'])->update([
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ]);
        return $this->success('ok');
    }
}
