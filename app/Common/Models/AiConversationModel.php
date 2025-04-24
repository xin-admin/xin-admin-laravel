<?php

namespace App\Common\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AiConversationModel extends Model
{
    use SoftDeletes;
    protected $table = 'ai_conversation';
    protected $primaryKey = 'id';

    // 关联分组表
    public function group(): BelongsTo
    {
        return $this->belongsTo(AiConversationGroupModel::class, 'id', 'group_id');
    }
}
