<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class XinUser
 */
class XinUserModel extends Model
{
    protected $table = 'xin_user';

    protected $primaryKey = 'user_id';

    protected $hidden = [
        'password',
    ];

    protected $fillable = [
        'mobile',
        'username',
        'email',
        'password',
        'nickname',
    ];
}
