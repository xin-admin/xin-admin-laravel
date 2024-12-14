<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

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

    protected $fillable = [
        'user_id',
        'scene',
        'money',
        'describe',
    ];
}
