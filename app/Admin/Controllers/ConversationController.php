<?php

namespace App\Admin\Controllers;

use App\BaseController;
use App\Common\Models\AiConversationGroupModel;
use App\Common\Models\AiConversationModel;
use Illuminate\Http\JsonResponse;
use Xin\AnnoRoute\Attribute\Delete;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\Query;
use Xin\AnnoRoute\Attribute\RequestMapping;

/**
 * 对话管理控制器
 */
#[RequestMapping('/system/conversation', 'system.conversation')]
#[Query, Delete]
class ConversationController extends BaseController
{
    protected string $model = AiConversationModel::class;

    /** 通过UUID获取 对话内容 */
    #[GetMapping('/uuid/{uuid}', 'query')]
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