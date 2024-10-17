<?php


namespace App\Models\Setting;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 设置分组模型
 */
class SettingGroupModel extends BaseModel
{
    protected $table = 'setting_group';

    protected $fillable = ['title', 'key'];

    public function setting(): HasMany
    {
        return $this->hasMany(SettingModel::class, 'group_id');
    }
}
