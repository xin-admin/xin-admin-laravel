<?php

namespace App\Models\Admin;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * 管理员分组模型
 */
class AdminGroupModel extends BaseModel
{
    protected $table = 'admin_group';

    protected $primaryKey = 'id';

    protected $fillable = ['type', 'name'];

    protected function rules(): Attribute
    {
        return new Attribute(
            get: function ($value) {
                if ($value == '*') {
                    return AdminRuleModel::query()
                        ->where('status', 1)
                        ->pluck('id')
                        ->toArray();
                } else {
                    return array_map('intval', explode(',', $value));
                }
            },
        );
    }

}
