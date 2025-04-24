<?php

namespace App\Admin\Service;

use App\Common\Models\XinUserModel;
use App\Common\Trait\RequestJson;
use Illuminate\Http\JsonResponse;

class XinUserListService
{
    use RequestJson;

    /**
     * 重置用户密码
     *
     * @param  int  $userId  用户ID
     * @param  string  $password  新密码
     */
    public function resetPassword(int $userId, string $password = ''): JsonResponse
    {
        XinUserModel::where('user_id', $userId)->update([
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        return $this->success(__('user.reset_password'));
    }
}
