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
 * @mixin IdeHelperModel
 */
class XinUserMoneyRecordModel extends Model
{
    protected $table = 'xin_user_money_record';

    public $timestamps = false;

    protected $casts = [
        'user_id' => 'int',
        'money' => 'float',
    ];

    protected $with = ['user'];

    protected $fillable = [
        'user_id',
        'scene',
        'money',
        'describe',
    ];

    /**
     * 关联用户
     */
    public function user(): HasOne
    {
        return $this->HasOne(XinUserModel::class, 'id', 'user_id');
    }
}
