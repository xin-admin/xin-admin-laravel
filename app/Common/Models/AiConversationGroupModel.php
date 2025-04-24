<?php

namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class AiConversationGroupModel extends Model
{
    use SoftDeletes;

    protected $table = 'ai_conversation_group';
    protected $primaryKey = 'id';

    protected $with = ['user'];

    // 关联对话表
    public function conversation(): HasMany
    {
        return $this->hasMany(AiConversationModel::class, 'group_id', 'id');
    }

    // 关联用户表
    public function user(): HasOne
    {
        return $this->hasOne(AdminUserModel::class, 'user_id', 'user_id');
    }
}
