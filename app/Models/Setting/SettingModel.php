<?php

namespace App\Models\Setting;

use App\Models\BaseModel;

/**
 * 设置模型
 */
class SettingModel extends BaseModel
{
    protected $table = 'setting';

    protected $fillable = ['title', 'key', 'group_id', 'type'];
}
