<?php

namespace App\Service;

use App\Models\AiConversationGroupModel;
use App\Models\AiConversationModel;
use Illuminate\Http\StreamedEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Xin\OpenAI\OpenAIFacades;

class AiConversationService
{

    /**
     * 创建会话
     * @param string $name 会话名称
     * @param string $system 系统提示
     * @param float $temperature 温度
     *
     * @return string 会话ID
     */
    public function createConversation(string $name, string $system = 'You are a helpful assistant', float $temperature = 1.2): string
    {
        $uuid = (string) Str::uuid();
        $model = new AiConversationGroupModel();
        $model->name = $name;
        $model->uuid = $uuid;
        $model->model = 'deepseek-chat';
        $model->user_id = auth()->id();
        $model->temperature = $temperature;
        $model->save();
        $aiMessageModel = new AiConversationModel();
        $aiMessageModel->role = 'system';
        $aiMessageModel->message = $system;
        $model->conversation()->save($aiMessageModel);
        return $uuid;
    }

    /**
     * 发送消息
     * @param string $message 消息
     * @param string $uuid 会话ID
     *
     * @return StreamedResponse 回复
     */
    public function save(string $message, string $uuid): StreamedResponse
    {
        return response()->eventStream(function () use ($message, $uuid) {
            // 获取上下文信息
            $model = AiConversationGroupModel::where('uuid', $uuid)->first();
            $messages = $model->conversation()
                ->orderBy('id', 'asc')
                ->select('role', 'message as content')
                ->get()
                ->toArray();
            $messages[] = ['role' => 'user', 'content' => $message];
            // 储存消息
            $aiMessageModel = new AiConversationModel();
            $aiMessageModel->role = 'user';
            $aiMessageModel->message = $message;
            $model->conversation()->save($aiMessageModel);
            // 记录日志
            Log::log('info', $messages);
            $stream = OpenAIFacades::chat()->createStreamed([
                'model' => $model->model,
                'messages' => $messages
            ]);
            $message = '';
            foreach ($stream as $response) {
                yield $response->choices[0];
                $message .= $response->choices[0]->delta->content;
            }
            $aiMessageModel = new AiConversationModel();
            $aiMessageModel->role = 'assistant';
            $aiMessageModel->message = $message;
            $model->conversation()->save($aiMessageModel);
        }, endStreamWith: new StreamedEvent(event: 'update', data: 'end'));
    }
}