<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasOne;

class AdminLoginLogModel  extends Model
{

    public $timestamps = false;
    protected $table = 'admin_login_log';
    protected $primaryKey = 'log_id';

    protected $fillable = [
        'username',
        'ipaddr',
        'login_location',
        'browser',
        'os',
        'status',
        'msg',
        'login_time',
    ];

    /**
     * 关联用户
     */
    public function users(): hasOne
    {
        return $this->hasOne(AdminUserModel::class, 'user_id', 'user_id');
    }

}