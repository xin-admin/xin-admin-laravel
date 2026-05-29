export interface IAgentConversation {
    id?: string;
    user_id?: number | null;
    username?: string;
    title?: string;
    message_count?: number;
    created_at?: string;
    updated_at?: string;
}

export interface IAgentMessage {
    id?: string;
    conversation_id?: string;
    user_id?: number | null;
    agent?: string;
    role?: string;
    content?: string;
    attachments?: Record<string, any> | null;
    tool_calls?: Record<string, any> | null;
    tool_results?: Record<string, any> | null;
    usage?: Record<string, any> | null;
    meta?: Record<string, any> | null;
    created_at?: string;
    updated_at?: string;
}
