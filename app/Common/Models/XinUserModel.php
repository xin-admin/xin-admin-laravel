<?php
namespace App\Common\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class XinUser
 */
class XinUserModel extends Authenticatable
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
