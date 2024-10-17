<?php

namespace App\Models;

use App\Models\Admin\AdminModel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MonitorModel extends BaseModel
{
    protected $table = 'monitor';

    protected $primaryKey = 'id';

    protected $with = ['user'];

    /**
     * 关联会员记录表
     */
    public function user(): HasOne
    {
        return $this->hasOne(AdminModel::class, 'id', 'user_id');
    }
}
