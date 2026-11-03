---
paths:
  - 'app/Ai/**'
---

# Ai

## AI 智能体约定（laravel/ai）
智能体类放 app/Ai/Agents，implements Laravel\Ai\Contracts\Agent 并 use Promptable；多轮会话加 Conversational + RemembersConversations。需在 agents 表（AgentModel.namespace 存完整类名）注册后才能通过 /ai/chat/send 的 agent_id 动态调用。模型与密钥配置走 .env 与 laravel/ai 的 config/ai.php（vendor 配置），禁止硬编码密钥，不做数据库在线配置。
