<?php

namespace Modules\SystemTool\Settings;

use Modules\SystemTool\Attributes\Setting;
use Modules\SystemTool\Base\SettingsDefinition;
use Modules\SystemTool\Enum\ESettingType;

/**
 * AI 服务配置定义
 *
 * 对应 config/ai.php 中的可配置项。
 * 敏感值（API Key、Token）使用 EncryptedString 类型存储。
 */
#[Setting(config: 'ai.default', type: ESettingType::String, description: '默认 AI 供应商')]
#[Setting(config: 'ai.default_for_images', type: ESettingType::String, description: '默认图片生成供应商')]
#[Setting(config: 'ai.default_for_audio', type: ESettingType::String, description: '默认音频生成供应商')]
#[Setting(config: 'ai.default_for_transcription', type: ESettingType::String, description: '默认语音转文字供应商')]

// Anthropic
#[Setting(config: 'ai.providers.anthropic.key', type: ESettingType::EncryptedString, description: 'Anthropic API Key')]
#[Setting(config: 'ai.providers.anthropic.url', type: ESettingType::String, description: 'Anthropic API URL')]

// Azure
#[Setting(config: 'ai.providers.azure.key', type: ESettingType::EncryptedString, description: 'Azure OpenAI API Key')]
#[Setting(config: 'ai.providers.azure.url', type: ESettingType::String, description: 'Azure OpenAI URL')]
#[Setting(config: 'ai.providers.azure.api_version', type: ESettingType::String, description: 'Azure API 版本')]
#[Setting(config: 'ai.providers.azure.deployment', type: ESettingType::String, description: 'Azure 模型部署名')]
#[Setting(config: 'ai.providers.azure.embedding_deployment', type: ESettingType::String, description: 'Azure Embedding 模型部署名')]
#[Setting(config: 'ai.providers.azure.image_deployment', type: ESettingType::String, description: 'Azure 图片模型部署名')]

// Bedrock
#[Setting(config: 'ai.providers.bedrock.region', type: ESettingType::String, description: 'Bedrock 区域')]
#[Setting(config: 'ai.providers.bedrock.key', type: ESettingType::EncryptedString, description: 'Bedrock API Key')]
#[Setting(config: 'ai.providers.bedrock.access_key_id', type: ESettingType::EncryptedString, description: 'Bedrock Access Key ID')]
#[Setting(config: 'ai.providers.bedrock.secret_access_key', type: ESettingType::EncryptedString, description: 'Bedrock Secret Access Key')]
#[Setting(config: 'ai.providers.bedrock.session_token', type: ESettingType::EncryptedString, description: 'Bedrock Session Token')]

// Cohere
#[Setting(config: 'ai.providers.cohere.key', type: ESettingType::EncryptedString, description: 'Cohere API Key')]

// DeepSeek
#[Setting(config: 'ai.providers.deepseek.key', type: ESettingType::EncryptedString, description: 'DeepSeek API Key')]

// ElevenLabs
#[Setting(config: 'ai.providers.eleven.key', type: ESettingType::EncryptedString, description: 'ElevenLabs API Key')]

// Gemini
#[Setting(config: 'ai.providers.gemini.key', type: ESettingType::EncryptedString, description: 'Gemini API Key')]
#[Setting(config: 'ai.providers.gemini.url', type: ESettingType::String, description: 'Gemini API URL')]

// Groq
#[Setting(config: 'ai.providers.groq.key', type: ESettingType::EncryptedString, description: 'Groq API Key')]

// Jina
#[Setting(config: 'ai.providers.jina.key', type: ESettingType::EncryptedString, description: 'Jina API Key')]

// Mistral
#[Setting(config: 'ai.providers.mistral.key', type: ESettingType::EncryptedString, description: 'Mistral API Key')]

// Ollama
#[Setting(config: 'ai.providers.ollama.key', type: ESettingType::EncryptedString, description: 'Ollama API Key')]
#[Setting(config: 'ai.providers.ollama.url', type: ESettingType::String, description: 'Ollama API URL')]

// OpenAI
#[Setting(config: 'ai.providers.openai.key', type: ESettingType::EncryptedString, description: 'OpenAI API Key')]
#[Setting(config: 'ai.providers.openai.url', type: ESettingType::String, description: 'OpenAI API URL')]

// OpenRouter
#[Setting(config: 'ai.providers.openrouter.key', type: ESettingType::EncryptedString, description: 'OpenRouter API Key')]

// VoyageAI
#[Setting(config: 'ai.providers.voyageai.key', type: ESettingType::EncryptedString, description: 'VoyageAI API Key')]

// xAI
#[Setting(config: 'ai.providers.xai.key', type: ESettingType::EncryptedString, description: 'xAI API Key')]
class AiSettings extends SettingsDefinition
{
}
