<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class XinUserMoneyRecord
 *
 * @property int $id
 * @property int $user_id
 * @property string $scene
 * @property float $money
 * @property string $describe
 * @property Carbon|null $created_at
 *
 * @mixin IdeHelperModel
 */
class XinBalanceRecordModel extends Model
{
    protected $table = 'xin_balance_record';

    protected $casts = [
        'user_id' => 'int',
        'scene' => 'int',
        'balance' => 'float',
        'before' => 'float',
        'after' => 'float',
    ];

    protected $with = ['user', 'createUser'];

    protected $fillable = [
        'user_id',
        'scene',
        'balance',
        'after',
        'before',
        'describe',
    ];

    /**
     * 关联用户
     */
    public function user(): HasOne
    {
        return $this->HasOne(XinUserModel::class, 'user_id', 'user_id');
    }

    public function createUser(): HasOne
    {
        return $this->HasOne(XinUserModel::class, 'user_id', 'created_by');
    }
}
