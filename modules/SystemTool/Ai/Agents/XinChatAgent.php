<?php

namespace Modules\SystemTool\Ai\Agents;

use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Promptable;
use Stringable;

class XinChatAgent implements Agent, Conversational
{
    use Promptable, RemembersConversations;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return 'You are a helpful, friendly AI assistant. Answer questions clearly and concisely. '
            . 'If you don\'t know something, be honest about it. '
            . 'Use markdown formatting when it helps readability.';
    }
}
