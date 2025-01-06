<?php

namespace App\Service\impl;

use App\Models\XinBalanceRecordModel;
use App\Models\XinUserModel;
use App\Trait\RequestJson;
use Illuminate\Http\JsonResponse;

class XinUserListService
{
    use RequestJson;

    /**
     * 用户余额充值
     *
     * @param  int  $userId  用户ID
     * @param  string  $amount  金额
     * @param  string  $mode  增加还是减少
     * @param  string  $remark  备注
     */
    public function recharge(int $userId, string $amount = '0.00', string $mode = 'inc', string $remark = ''): JsonResponse
    {
        $user = XinUserModel::where('user_id', $userId)->first();
        if ($mode === 'inc') {
            $diff = round($amount, 2);
        } elseif ($mode === 'dec') {
            $diff = -round($amount, 2);
        } else {
            $diff = bcsub($amount, $user->balance, 2);
        }
        // 变动后
        $diffBalance = bcadd($diff, $user->balance, 2);
        XinBalanceRecordModel::create([
            'user_id' => $userId,
            'balance' => $diff,
            'after' => $diffBalance,
            'before' => $user->balance,
            'describe' => $remark,
            'scene' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => auth()->id(),
        ]);
        XinUserModel::where('user_id', $userId)->update([
            'balance' => $diffBalance,
        ]);

        return $this->success('充值成功');
    }

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

        return $this->success('修改成功');
    }
}
