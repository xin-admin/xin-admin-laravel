<?php
namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class SettingGroup
 */
class SettingGroupModel extends Model
{
    protected $table = 'setting_group';

    protected $fillable = [
        'title',
        'key',
        'remark',
    ];

    /**
     * 关联设置项目
     * @return HasMany
     */
    public function settings(): HasMany
    {
        return $this->hasMany(SettingModel::class ,'group_id', 'id');
    }

}
