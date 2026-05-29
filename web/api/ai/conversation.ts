import createAxios from '@/utils/request';
import type { IAgentConversation, IAgentMessage } from '@/domain/agentConversation';

interface PaginatorData<T> {
    data: T[];
    total: number;
    pageSize: number;
    current: number;
}

/** 获取会话列表 */
export async function getConversations(params?: Record<string, any>) {
    return createAxios<PaginatorData<IAgentConversation>>({
        url: '/ai/conversation',
        method: 'get',
        params,
    });
}

/** 获取会话详情 */
export async function getConversation(id: string) {
    return createAxios<IAgentConversation>({
        url: `/ai/conversation/${id}`,
        method: 'get',
    });
}

/** 删除会话 */
export async function deleteConversation(id: string) {
    return createAxios({
        url: `/ai/conversation/${id}`,
        method: 'delete',
    });
}

/** 获取会话消息列表 */
export async function getMessages(conversationId: string, params?: Record<string, any>) {
    return createAxios<PaginatorData<IAgentMessage>>({
        url: `/ai/conversation/${conversationId}/messages`,
        method: 'get',
        params,
    });
}
