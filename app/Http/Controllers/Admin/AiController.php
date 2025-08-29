<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\AiConversationGroupModel;
use App\Services\AiConversationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Xin\AnnoRoute\Attribute\GetMapping;
use Xin\AnnoRoute\Attribute\PostMapping;
use Xin\AnnoRoute\Attribute\RequestMapping;

/**
 * AI对话控制器
 */
#[RequestMapping('/ai', 'ai')]
class AiController extends BaseController
{
    /** 添加会话 */
    #[PostMapping(authorize: 'create')]
    public function add(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $service = new AiConversationService();
        $uuid = $service->createConversation($data['name']);
        return $this->success(['uuid' => $uuid]);
    }

    /** 获取用户会话列表 */
    #[GetMapping(authorize: 'query')]
    public function query(Request $request): JsonResponse
    {
        $data = AiConversationGroupModel::where('user_id', auth()->id())
            ->orderBy('id', 'desc')
            ->get()
            ->toArray();
        return $this->success($data);
    }

    /** 通过UUID获取 对话 */
    #[GetMapping('/{uuid}', 'query.uuid')]
    public function listByUuid($uuid): JsonResponse
    {
        $model = AiConversationGroupModel::where('uuid', $uuid)->first();
        if (! $model) {
            return $this->error('会话组不存在');
        }
        if ($model->user_id !== auth()->id()) {
            return $this->error('您没有权限操作该会话组!');
        }
        $data = $model->conversation()->where('role', '<>', 'system')->orderBy('id', 'asc')->get()->toArray();
        return $this->success($data);
    }

    /**
     * 发送消息
     * @param Request $request
     * @return StreamedResponse|JsonResponse
     * @throws ValidationException
     */
    #[PostMapping('/send', 'send')]
    public function send(Request $request): StreamedResponse | JsonResponse
    {
        $content = $request->getContent();
        $contentData = json_decode($content, true);
        $validator = Validator::make($contentData, [
            'message' => 'required|string',
            'uuid' => 'required|uuid',
        ]);
        if($validator->stopOnFirstFailure()->fails()) {
            return $this->error($validator->errors()->first());
        }
        $validated = $validator->validated();
        $service = new AiConversationService();
        return $service->save($validated['message'], $validated['uuid']);
    }

}