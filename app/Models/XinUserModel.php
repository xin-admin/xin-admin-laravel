<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class XinUser
 *
 * @property int $id
 * @property string $mobile
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $nickname
 * @property int $avatar_id
 * @property string $gender
 * @property Carbon|null $birthday
 * @property int $group_id
 * @property int $money
 * @property int $score
 * @property string $motto
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @mixin IdeHelperModel
 */
class XinUserModel extends Model
{
    protected $table = 'xin_user';

    protected $casts = [
        'avatar_id' => 'int',
        'birthday' => 'datetime',
        'group_id' => 'int',
        'money' => 'int',
        'score' => 'int',
    ];

    protected $hidden = [
        'password',
    ];

    protected $fillable = [
        'mobile',
        'username',
        'email',
        'password',
        'nickname',
        'avatar_id',
        'gender',
        'birthday',
        'group_id',
        'money',
        'score',
        'motto',
        'status',
    ];
}
