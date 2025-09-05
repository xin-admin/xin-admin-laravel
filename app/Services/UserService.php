<?php

namespace App\Services;

use App\Models\UserModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserService extends Service
{
    /**
     * 重置用户密码
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => 'required|exists:user,id',
            'password' => 'required|string|min:6|max:20',
            'rePassword' => 'required|same:password',
        ]);
        UserModel::find($data['user_id'])->update([
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ]);
        return $this->success(__('user.reset_password'));
    }
}
