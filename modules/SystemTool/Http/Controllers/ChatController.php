<?php

namespace Modules\SystemTool\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\AnnoRoute\Attribute\DeleteRoute;
use Modules\AnnoRoute\Attribute\GetRoute;
use Modules\AnnoRoute\Attribute\PostRoute;
use Modules\AnnoRoute\Attribute\RequestAttribute;
use Modules\Common\Http\Controllers\BaseController;
use Modules\SystemTool\Ai\Agents\XinChatAgent;
use Laravel\Ai\Responses\StreamableAgentResponse;

#[RequestAttribute('/ai/chat', 'ai.chat')]
class ChatController extends BaseController
{
    /**
     * 发送消息并返回 SSE 流式响应
     */
    #[PostRoute('/send', 'send')]
    public function send(Request $request): StreamableAgentResponse|JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:10000',
            'conversation_id' => 'nullable|string|max:36',
        ]);

        $message = $request->input('message');
        $conversationId = $request->input('conversation_id');
        $user = $request->user();

        try {
            $agent = XinChatAgent::make();

            if ($conversationId) {
                // 继续已有会话
                $response = $agent
                    ->continue($conversationId, as: $user)
                    ->stream($message);
            } else {
                // 新建会话
                $response = $agent
                    ->forUser($user)
                    ->stream($message);
            }

            return $response;
        } catch (\Throwable $e) {
            return $this->error('AI 响应失败：' . $e->getMessage());
        }
    }

    /**
     * 获取当前用户的会话列表
     */
    #[GetRoute('/conversations', 'conversations')]
    public function conversations(Request $request): JsonResponse
    {
        $user = $request->user();

        $conversations = $user->conversations()
            ->latest('updated_at')
            ->get()
            ->map(fn ($conversation) => [
                'key' => $conversation->id,
                'label' => $conversation->title,
                'updated_at' => $conversation->updated_at->toISOString(),
            ]);

        return $this->success($conversations->toArray());
    }

    /**
     * 获取指定会话的消息列表
     */
    #[GetRoute('/messages/{conversationId}', 'messages')]
    public function messages(Request $request, string $conversationId): JsonResponse
    {
        $user = $request->user();

        $conversation = $user->conversations()->find($conversationId);

        if (! $conversation) {
            return $this->error('会话不存在');
        }

        $messages = $conversation->messages()
            ->oldest()
            ->get()
            ->map(fn ($msg) => [
                'key' => $msg->id,
                'role' => $msg->role,
                'content' => $msg->content,
                'created_at' => $msg->created_at->toISOString(),
            ]);

        return $this->success($messages->toArray());
    }

    /**
     * 删除指定会话
     */
    #[DeleteRoute('/messages/{conversationId}', 'delete')]
    public function deleteConversation(Request $request, string $conversationId): JsonResponse
    {
        $user = $request->user();

        $conversation = $user->conversations()->find($conversationId);

        if (! $conversation) {
            return $this->error('会话不存在');
        }

        $conversation->delete();

        return $this->success('会话已删除');
    }
}
