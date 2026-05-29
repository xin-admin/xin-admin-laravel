<?php

namespace Modules\SystemTool\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Ai\Models\Conversation;
use Modules\AnnoRoute\Attribute\DeleteRoute;
use Modules\AnnoRoute\Attribute\GetRoute;
use Modules\AnnoRoute\Attribute\RequestAttribute;
use Modules\Common\Http\Controllers\BaseController;
use Modules\SystemUser\Models\SysUserModel;

#[RequestAttribute('/ai/conversation', 'ai.conversation')]
class ConversationController extends BaseController
{
    protected array $searchField = [
        'title' => 'like',
    ];

    protected array $quickSearchField = ['title'];

    /**
     * 会话列表
     */
    #[GetRoute(authorize: 'query')]
    public function query(Request $request): JsonResponse
    {
        $params = $request->all();
        $perPage = (int) ($params['pageSize'] ?? 10);
        $query = Conversation::query()->withCount('messages');

        $data = $this->buildSearch($params, $query)
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);

        $userIds = $data->pluck('user_id')->filter()->unique();
        $users = SysUserModel::whereIn('id', $userIds)->pluck('username', 'id');

        $data = $data->through(function ($conversation) use ($users) {
            return [
                'id' => $conversation->id,
                'user_id' => $conversation->user_id,
                'username' => $users[$conversation->user_id] ?? '',
                'title' => $conversation->title,
                'message_count' => $conversation->messages_count,
                'created_at' => $conversation->created_at?->toISOString(),
                'updated_at' => $conversation->updated_at?->toISOString(),
            ];
        });

        return $this->success($data->toArray());
    }

    /**
     * 删除会话
     */
    #[DeleteRoute(route: '/{id}', authorize: 'delete', where: ['id' => '[a-zA-Z0-9\-]+'])]
    public function delete(string $id): JsonResponse
    {
        $conversation = Conversation::find($id);

        if (! $conversation) {
            return $this->error('会话不存在');
        }

        $conversation->delete();

        return $this->success('会话已删除');
    }

    /**
     * 获取会话消息列表
     */
    #[GetRoute('/{id}/messages', authorize: 'query', where: ['id' => '[a-zA-Z0-9\-]+'])]
    public function messages(string $id, Request $request): JsonResponse
    {
        $conversation = Conversation::find($id);

        if (! $conversation) {
            return $this->error('会话不存在');
        }

        $perPage = (int) $request->input('pageSize', 20);
        $data = $conversation->messages()
            ->orderBy('created_at')
            ->paginate($perPage)
            ->toArray();

        return $this->success($data);
    }

    /**
     * 获取会话详情
     */
    #[GetRoute('/{id}', authorize: 'query', where: ['id' => '[a-zA-Z0-9\-]+'])]
    public function show(string $id): JsonResponse
    {
        $conversation = Conversation::withCount('messages')->find($id);

        if (! $conversation) {
            return $this->error('会话不存在');
        }

        $user = $conversation->user_id
            ? SysUserModel::find($conversation->user_id)
            : null;

        return $this->success([
            'id' => $conversation->id,
            'user_id' => $conversation->user_id,
            'username' => $user?->username ?? '',
            'title' => $conversation->title,
            'message_count' => $conversation->messages_count,
            'created_at' => $conversation->created_at?->toISOString(),
            'updated_at' => $conversation->updated_at?->toISOString(),
        ]);
    }
}
