<?php
namespace App\Service;

use App\Models\User\UserModel;
use App\Models\User\UserMoneyLogModel;
use App\Trait\RequestJson;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserService
{

    use RequestJson;

    /**
     * 用户余额充值
     * @param int $userId 用户ID
     * @param string $amount 金额
     * @param string $mode 增加还是减少
     * @param string $remark 备注
     * @return JsonResponse
     */
    public function recharge(int $userId, string $amount = '0.00', string $mode = 'inc', string $remark = ''): JsonResponse
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
            return $this->success('充值成功');
        } catch (\Exception $e) {
            // 如果发生异常则回滚
            DB::rollBack();
            // 处理异常
            return $this->error('充值失败');
        }
    }

    /**
     * 重置用户密码
     * @param int $userId 用户ID
     * @param string $password 新密码
     * @return JsonResponse
     */
    public function resetPassword(int $userId, string $password = ''): JsonResponse
    {
        $model = UserModel::query()->find($userId);
        $model->password = password_hash($password, PASSWORD_DEFAULT);
        $model->save();
        return $this->success('修改成功');
    }
}