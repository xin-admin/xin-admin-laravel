<?php

namespace App\Service\User;

use App\Models\User\UserModel;

/**
 * 重置用户密码
 */
class UserResetPasswordService
{
    public static function reset($userId, $password = ''): true
    {
        $model = UserModel::query()->find($userId);
        $model->password = password_hash($password, PASSWORD_DEFAULT);
        $model->save();
        return true;
    }
}
