<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AiConversationGroupModel extends Model
{
    use SoftDeletes;

    protected $table = 'ai_conversation_group';
    protected $primaryKey = 'id';

    // 关联对话表
    public function conversation(): HasMany
    {
        return $this->hasMany(AiConversationModel::class, 'group_id', 'id');
    }
}
