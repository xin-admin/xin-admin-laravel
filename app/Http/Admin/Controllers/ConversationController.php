<?php

namespace App\Http\Admin\Controllers;

use App\Http\BaseController;
use App\Models\AiConversationGroupModel;
use App\Models\AiConversationModel;
use Illuminate\Http\JsonResponse;
use Xin\AnnoRoute\Attribute\Authorize;
use Xin\AnnoRoute\Attribute\DeleteMapping;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;

/**
 * 对话管理控制器
 */
#[RequestMapping('/system/conversation')]
class ConversationController extends BaseController
{
    public function __construct()
    {
        $this->model = new AiConversationModel();
    }

    /** 获取对话列表 */
    #[GetMapping] #[Authorize('system.conversation.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse();
    }

    /** 删除消息 */
    #[DeleteMapping] #[Authorize('system.conversation.delete')]
    public function delete(): JsonResponse
    {
        return $this->deleteResponse();
    }

    /** 通过UUID获取 对话内容 */
    #[GetMapping('/uuid/{uuid}')] #[Authorize('system.conversation.list')]
    public function listByUuid($uuid): JsonResponse
    {
        $model = new AiConversationGroupModel();
        $model = $model->where('uuid', $uuid)->first();
        if (! $model) {
            return $this->error('会话组不存在');
        }
        $data = $model->conversation()->orderBy('id', 'asc')->get()->toArray();
        return $this->success($data);
    }
}