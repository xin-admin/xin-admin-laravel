<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\route\DeleteMapping;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\RequestMapping;
use App\Http\BaseController;
use App\Models\AiConversationGroupModel;
use App\Service\impl\AiConversationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

#[AdminController]
#[RequestMapping('/system/ai/group')]
class AiConversationGroupController extends BaseController
{
    public function __construct()
    {
        $this->model = new AiConversationGroupModel();
    }

    /** 获取会话组列表 */
    #[GetMapping] #[Authorize('system.ai.group.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse();
    }

    /** 获取用户会话列表 #[Authorize('system.ai.group.listByUser')] */
    #[GetMapping('/byUser')]
    public function listByUser(): JsonResponse
    {
        $data = $this->model
            ->where('user_id', customAuth()->id())
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
        return $this->success($data);
    }

    /** 添加会话 #[Authorize('system.ai.group.add')]*/
    #[PostMapping]
    public function add(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $service = new AiConversationService();
        $uuid = $service->createConversation($data['name']);
        return $this->success(['uuid' => $uuid]);
    }

    /** 删除会话 */
    #[DeleteMapping] #[Authorize('system.ai.group.delete')]
    public function delete(): JsonResponse
    {
        return $this->deleteResponse();
    }
}