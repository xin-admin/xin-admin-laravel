<?php

namespace App\Service\User;

use App\Enum\ShowType;
use App\Exception\HttpResponseException;
use App\Models\User\UserModel;
use App\Models\User\UserMoneyLogModel;
use Illuminate\Support\Facades\DB;

/**
 * 用户余额充值
 */
class UserRechargeService
{
    public static function recharge($userId, $amount = '0.00', $mode = 'inc', $remark = ''): true
    {
        try {
            DB::beginTransaction();
            $amount = bcmul($amount, '100', 0);
            $user = UserModel::query()->find($userId);
            if ($mode === 'inc') {
                $diffMoney = $amount;
            } elseif ($mode === 'dec') {
                $diffMoney = -$amount;
            } else {
                $diffMoney = bcsub($amount, bcmul($user->money, '100', 0), 0);
            }
            UserMoneyLogModel::query()->create([
                'user_id' => $userId,
                'money' => $amount,
                'describe' => $remark,
                'scene' => 1
            ]);
            $user->money = bcadd(bcmul($user->money, '100', 0), $diffMoney, 0 );
            $user->save();
            // 提交事务
            DB::commit();
            return true;
        } catch (\Exception $e) {
            // 如果发生异常则回滚
            DB::rollBack();
            // 处理异常
            throw new HttpResponseException([
                'success' => false,
                'msg' => $e->getMessage(),
                'showType' => ShowType::ERROR_MESSAGE->value,
            ]);
        }
    }
}
