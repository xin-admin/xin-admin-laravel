<?php

namespace App\Http\Admin\Controllers;

use App\Attribute\route\GetMapping;
use App\Attribute\route\PostMapping;
use App\Attribute\route\RequestMapping;
use App\Http\BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\StreamedEvent;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[RequestMapping('/admin')]
class IndexController extends BaseController
{
    protected array $noPermission = ['index', 'openai'];

    /** 测试接口 */
    #[GetMapping]
    public function index(): JsonResponse
    {
        $webSetting = get_setting('web');
        return $this->success(compact('webSetting'), '恭喜你已经成功安装 Xin Admin');
    }

    #[GetMapping('/openai')]
    public function openai(): JsonResponse
    {
        return $this->success(OpenAI::models()->list()->toArray());
    }

    #[PostMapping('/test')]
    public function test(Request $request): StreamedResponse
    {
        $message = $request->getContent();
        return response()->eventStream(function () use ($message) {
            yield $message;
            $msgData = json_decode($message, true);
            $stream = OpenAI::chat()->createStreamed([
                'model' => 'deepseek-chat',
                'messages' => [
                    ['role' => 'user', 'content' => $msgData['message']],
                ]
            ]);
            foreach ($stream as $response) {
                yield $response->choices[0];
            }
        }, endStreamWith: new StreamedEvent(event: 'update', data: 'end'));
    }
}
