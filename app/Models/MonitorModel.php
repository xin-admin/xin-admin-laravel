<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Monitor
 *
 * @property int $id
 * @property string $name
 * @property string $action
 * @property string $ip
 * @property string $host
 * @property string $address
 * @property string|null $url
 * @property string|null $data
 * @property string|null $params
 * @property int $user_id
 * @property Carbon|null $created_at
 *
 * @mixin IdeHelperModel
 */
class MonitorModel extends Model
{
    protected $table = 'monitor';

    public $timestamps = false;

    protected $casts = [
        'user_id' => 'int',
    ];

    protected $fillable = [
        'name',
        'action',
        'ip',
        'host',
        'address',
        'url',
        'data',
        'params',
        'user_id',
    ];

    protected $with = ['user'];

    /**
     * 关联会员记录表
     */
    public function user(): HasOne
    {
        return $this->hasOne(AdminUserModel::class, 'user_id', 'user_id');
    }
}
