<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * APP 用户模型
 */
class UserModel extends Authenticatable
{
    protected $table = 'user';

    protected $primaryKey = 'id';

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
