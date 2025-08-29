<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\AiConversationGroupModel;
use Xin\AnnoRoute\Attribute\Delete;
use Xin\AnnoRoute\Attribute\Query;
use Xin\AnnoRoute\Attribute\RequestMapping;

/**
 * 会话组管理控制器
 */
#[RequestMapping('/system/conversation/group', 'system.conversation.group')]
#[Query, Delete]
class ConversationGroupController extends BaseController
{
    protected string $model = AiConversationGroupModel::class;

}