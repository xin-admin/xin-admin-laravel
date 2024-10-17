<?php

namespace App\Models\User;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\hasOne;

/**
 * 用户余额记录模型
 */
class UserMoneyLogModel extends BaseModel
{
    protected $table = 'user_money_log';

    const UPDATED_AT = null;

    protected $fillable = ['user_id', 'money', 'scene', 'describe'];

    protected $with = ['user'];

    /**
     * 关联会员记录表
     */
    public function user(): hasOne
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id');
    }

    protected function money(): Attribute
    {
        return new Attribute(
            get: function ($data) {
                return number_format($data / 100, 2, '.', '');
            },
        );
    }
}
