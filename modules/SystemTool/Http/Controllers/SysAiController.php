<?php

namespace Modules\SystemTool\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Ai\Enums\Lab;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Laravel\Ai\Responses\AgentResponse;
use Laravel\Ai\Responses\StreamableAgentResponse;
use Modules\AnnoRoute\Attribute\GetRoute;
use Modules\AnnoRoute\Attribute\PostRoute;
use Modules\AnnoRoute\Attribute\RequestAttribute;
use Modules\Common\Http\Controllers\BaseController;
use Modules\SystemTool\Ai\Agents\TestAgent;
use Modules\SystemTool\Settings\AiSettings;

/**
 * AI 服务配置管理
 */
#[RequestAttribute('/system/ai', 'system.ai')]
class SysAiController extends BaseController
{
    /** 获取支持的AI */
    #[GetRoute('/list', 'list')]
    public function index(): JsonResponse
    {
        $default = [Lab::Anthropic, Lab::OpenAI, Lab::Gemini, Lab::Azure, Lab::Bedrock, Lab::Groq, Lab::xAI, Lab::DeepSeek, Lab::Mistral, Lab::Ollama, Lab::OpenRouter ];
        return $this->success(compact('default'));
    }

    /**
     * 获取 AI 配置（从 DB 加载已保存的值，fallback 到 config 文件）
     */
    #[GetRoute('/config', 'config')]
    public function getConfig(): JsonResponse
    {
        $ai = config('ai');

        return $this->success([
            'default' => $ai['default'] ?? 'openai',
            'providers' => $ai['providers'] ?? [],
        ]);
    }

    /**
     * 保存 AI 配置到数据库
     */
    #[PostRoute('/save', 'save')]
    public function saveConfig(): JsonResponse
    {
        $data = request()->all();

        try {
            // 默认驱动
            AiSettings::set('ai.default', $data['default'] ?? 'openai');

            // 各供应商配置
            $providers = $data['providers'] ?? [];

            // Anthropic
            if (isset($providers['anthropic'])) {
                $p = $providers['anthropic'];
                AiSettings::set('ai.providers.anthropic.key', $p['key'] ?? '');
                if (isset($p['url'])) {
                    AiSettings::set('ai.providers.anthropic.url', $p['url']);
                }
            }

            // Azure
            if (isset($providers['azure'])) {
                $p = $providers['azure'];
                AiSettings::set('ai.providers.azure.key', $p['key'] ?? '');
                AiSettings::set('ai.providers.azure.url', $p['url'] ?? '');
                AiSettings::set('ai.providers.azure.api_version', $p['api_version'] ?? '2025-04-01-preview');
                AiSettings::set('ai.providers.azure.deployment', $p['deployment'] ?? 'gpt-4o');
                AiSettings::set('ai.providers.azure.embedding_deployment', $p['embedding_deployment'] ?? 'text-embedding-3-small');
                AiSettings::set('ai.providers.azure.image_deployment', $p['image_deployment'] ?? 'gpt-image-1');
            }

            // Bedrock
            if (isset($providers['bedrock'])) {
                $p = $providers['bedrock'];
                AiSettings::set('ai.providers.bedrock.region', $p['region'] ?? 'us-east-1');
                AiSettings::set('ai.providers.bedrock.key', $p['key'] ?? '');
                AiSettings::set('ai.providers.bedrock.access_key_id', $p['access_key_id'] ?? '');
                AiSettings::set('ai.providers.bedrock.secret_access_key', $p['secret_access_key'] ?? '');
                AiSettings::set('ai.providers.bedrock.session_token', $p['session_token'] ?? '');
            }

            // Cohere
            if (isset($providers['cohere'])) {
                AiSettings::set('ai.providers.cohere.key', $providers['cohere']['key'] ?? '');
            }

            // DeepSeek
            if (isset($providers['deepseek'])) {
                AiSettings::set('ai.providers.deepseek.key', $providers['deepseek']['key'] ?? '');
            }

            // ElevenLabs
            if (isset($providers['eleven'])) {
                AiSettings::set('ai.providers.eleven.key', $providers['eleven']['key'] ?? '');
            }

            // Gemini
            if (isset($providers['gemini'])) {
                $p = $providers['gemini'];
                AiSettings::set('ai.providers.gemini.key', $p['key'] ?? '');
                if (isset($p['url'])) {
                    AiSettings::set('ai.providers.gemini.url', $p['url']);
                }
            }

            // Groq
            if (isset($providers['groq'])) {
                AiSettings::set('ai.providers.groq.key', $providers['groq']['key'] ?? '');
            }

            // Jina
            if (isset($providers['jina'])) {
                AiSettings::set('ai.providers.jina.key', $providers['jina']['key'] ?? '');
            }

            // Mistral
            if (isset($providers['mistral'])) {
                AiSettings::set('ai.providers.mistral.key', $providers['mistral']['key'] ?? '');
            }

            // Ollama
            if (isset($providers['ollama'])) {
                $p = $providers['ollama'];
                AiSettings::set('ai.providers.ollama.key', $p['key'] ?? '');
                AiSettings::set('ai.providers.ollama.url', $p['url'] ?? 'http://localhost:11434');
            }

            // OpenAI
            if (isset($providers['openai'])) {
                $p = $providers['openai'];
                AiSettings::set('ai.providers.openai.key', $p['key'] ?? '');
                if (isset($p['url'])) {
                    AiSettings::set('ai.providers.openai.url', $p['url']);
                }
            }

            // OpenRouter
            if (isset($providers['openrouter'])) {
                AiSettings::set('ai.providers.openrouter.key', $providers['openrouter']['key'] ?? '');
            }

            // VoyageAI
            if (isset($providers['voyageai'])) {
                AiSettings::set('ai.providers.voyageai.key', $providers['voyageai']['key'] ?? '');
            }

            // xAI
            if (isset($providers['xai'])) {
                AiSettings::set('ai.providers.xai.key', $providers['xai']['key'] ?? '');
            }

            // 清除 Laravel 配置缓存
            Artisan::call('config:clear');

            return $this->success('保存成功');
        } catch (\Throwable $e) {
            return $this->error('保存失败：' . $e->getMessage());
        }
    }

    /**
     * 测试 AI 供应商连接
     */
    #[PostRoute('/test', 'test')]
    public function testConnection(Request $request): StreamableAgentResponse | JsonResponse
    {
        try {
            $agent = TestAgent::make()->forUser($request->user());
            return $agent->stream('Hello, Who are you?');
        } catch (\Throwable $e) {
            return $this->error('连接测试失败: ' . $e->getMessage());
        }
    }
}
