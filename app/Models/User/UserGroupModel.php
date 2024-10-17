<?php

namespace App\Models\User;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * 用户分组模型
 */
class UserGroupModel extends BaseModel
{
    protected $table = 'user_group';

    protected $fillable = ['type', 'name'];

    protected function rules(): Attribute
    {
        return new Attribute(
            get: function ($value) {
                if ($value == '*') {
                    return UserRuleModel::query()
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
