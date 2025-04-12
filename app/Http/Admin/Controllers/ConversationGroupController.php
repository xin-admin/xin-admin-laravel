<?php

namespace App\Http\Admin\Controllers;

use App\Http\BaseController;
use App\Models\AiConversationGroupModel;
use Illuminate\Http\JsonResponse;
use Xin\AnnoRoute\Attribute\Authorize;
use Xin\AnnoRoute\Attribute\DeleteMapping;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;

/**
 * 会话组管理控制器
 */
#[RequestMapping('/system/conversation/group')]
class ConversationGroupController extends BaseController
{
    public function __construct()
    {
        $this->model = new AiConversationGroupModel();
    }

    /** 获取会话组列表 */
    #[GetMapping] #[Authorize('system.conversation.group.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse();
    }

    /** 删除会话 */
    #[DeleteMapping] #[Authorize('system.conversation.group.delete')]
    public function delete(): JsonResponse
    {
        return $this->deleteResponse();
    }
}