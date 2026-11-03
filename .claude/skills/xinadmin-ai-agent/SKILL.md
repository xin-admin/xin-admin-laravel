---
name: xinadmin-ai-agent
description: "Build AI chat agents for the XinAdmin backend on the laravel/ai SDK. Use when creating a new agent class in app/Ai/Agents, registering it in the agents table (AgentModel), wiring streaming SSE chat endpoints like ChatController, adding conversation memory (RemembersConversations/Conversational), tools, or structured output. Triggers on tasks involving XinChatAgent, AgentModel, /ai/chat routes, or Laravel\\Ai namespaces in this project."
---

# XinAdmin AI 智能体开发

本项目 AI 能力基于 `laravel/ai` SDK（v0.7）。通用 SDK 用法（provider、tools、结构化输出、embeddings 等）先激活 `ai-sdk-development` 技能并用 `search-docs` 核实语法；本技能只覆盖**项目特有**的接入方式。

## 项目结构

- 智能体类：`app/Ai/Agents/`（如 `XinChatAgent`）。
- 智能体注册表：`agents` 表 / `App\Models\AgentModel`，`namespace` 字段存完整类名，`ChatController::send` 按 `agent_id` 动态实例化（`$agentClass::make()`）。
- 聊天接口：`Admin\ChatController`（`#[RequestAttribute('/ai/chat', 'ai.chat')]`），SSE 流式响应。
- 会话存储：`agent_conversations` / `agent_conversation_messages` 表，由 laravel/ai 的 `RemembersConversations` + `HasConversations`（`SysUserModel`）驱动。

## 创建新智能体

```php
<?php

namespace App\Ai\Agents;

use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Promptable;
use Stringable;

class XxxAgent implements Agent, Conversational
{
    use Promptable, RemembersConversations;

    /**
     * 智能体系统提示词
     */
    public function instructions(): Stringable|string
    {
        return '...';
    }
}
```

要点：

- 必须 `implements Agent` 并 `use Promptable`（提供 `::make()` 与 `prompt()/stream()`）。
- 需要多轮会话时额外 `implements Conversational` + `use RemembersConversations`；无状态的一次性智能体不要加。
- 配置（模型、密钥、base url）走 laravel/ai 的 `config/ai.php` 与 `.env`，不要把密钥写死在类里。

## 注册到 agents 表

新智能体需要在 `agents` 表插入记录（参考 `database/seeders/SysAgentSeeder.php`）：

- `namespace`：`App\Ai\Agents\XxxAgent` 完整类名（唯一）。
- `name` / `icon` / `description` / `tags`(JSON) / `enabled`。
- 未注册的智能体无法通过 `/ai/chat/send` 的 `agent_id` 调用，但仍可在代码中直接 `XxxAgent::make()` 使用。

## 聊天端点调用模式

参考 `ChatController::send` 的既有模式，新端点保持一致：

```php
if ($conversationId) {
    $response = $agent->continue($conversationId, as: $user)->stream($message);
} else {
    $response = $agent->forUser($user)->stream($message);
}
return $response; // StreamableAgentResponse（SSE）
```

- 入参校验：`message` required string，`conversation_id` nullable string(36)，`agent_id` nullable exists:agents,id。
- 异常兜底 `catch (\Throwable $e)` 返回 `$this->error('AI 响应失败：...')`，保持项目响应结构。
- 会话列表/消息/删除端点已存在于 `ChatController`，新需求优先扩展而非另起控制器。

## 权限

AI 相关端点权限前缀 `ai.chat.*`（如 `ai.chat.send`、`ai.chat.conversations`），新增端点同步在 `sys_rule` 表登记（见 xinadmin-admin-crud 技能第五步）。
