<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\SystemAgent\Models\AgentModel;

class SysAgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $agents = [
            [
                'namespace' => 'Modules\SystemTool\Ai\Agents\XinChatAgent',
                'name' => 'Xin Chat',
                'icon' => 'https://file.xinadmin.cn/file/favicons.ico',
                'description' => 'XinAdmin 默认 AI 助手，支持多轮对话和上下文记忆。',
                'tags' => ['XinChat', '智能对话'],
                'enabled' => true,
            ],
            [
                'namespace' => 'Modules\SystemTool\Ai\Agents\TestAgent',
                'name' => 'Test Chat',
                'icon' => 'https://file.xinadmin.cn/file/favicons.ico',
                'description' => '测试智能体，用于开发调试。',
                'enabled' => false,
            ],
        ];

        foreach ($agents as $agent) {
            AgentModel::firstOrCreate(
                ['namespace' => $agent['namespace']],
                $agent
            );
        }
    }
}
