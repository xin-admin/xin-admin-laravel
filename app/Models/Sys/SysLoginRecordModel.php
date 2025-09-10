<?php

namespace App\Models\Sys;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 系统用户登录日志模型
 */
class SysLoginRecordModel extends Model
{
    protected $table = 'sys_login_record';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'username',
        'ipaddr',
        'login_location',
        'browser',
        'os',
        'status',
        'msg',
        'login_time'
    ];

    protected $casts = [
        'user_id' => 'integer',
        'login_time' => 'datetime'
    ];

    /**
     * 定义与用户的关联关系
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(SysUserModel::class, 'user_id', 'id');
    }
}