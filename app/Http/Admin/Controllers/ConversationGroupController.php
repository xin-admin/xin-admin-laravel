<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\route\DeleteMapping;
use App\Attribute\route\GetMapping;
use App\Attribute\route\RequestMapping;
use App\Http\BaseController;
use App\Models\AiConversationGroupModel;
use Illuminate\Http\JsonResponse;

#[AdminController]
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