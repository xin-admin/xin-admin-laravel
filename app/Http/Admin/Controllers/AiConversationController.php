<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\AdminController;
use App\Attribute\Authorize;
use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\RequestMapping;
use App\Http\BaseController;
use App\Models\AiConversationGroupModel;
use App\Models\AiConversationModel;
use App\Service\impl\AiConversationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[AdminController]
#[RequestMapping('/system/ai')]
class AiConversationController extends BaseController
{
    public function __construct()
    {
        $this->model = new AiConversationModel();
    }

    /** 获取字典列表 */
    #[GetMapping] #[Authorize('system.ai.list')]
    public function list(): JsonResponse
    {
        return $this->listResponse();
    }

    /** 通过UUID获取 对话 #[Authorize('system.ai.list')]*/
    #[GetMapping('/{uuid}')]
    public function listByUuid($uuid): JsonResponse
    {
        $model = new AiConversationGroupModel();
        $model = $model->where('uuid', $uuid)->first();
        if (! $model) {
            return $this->error('会话组不存在');
        }
        if ($model->user_id !== customAuth()->id()) {
            return $this->error('您没有权限操作该会话组');
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
    #[PostMapping]
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