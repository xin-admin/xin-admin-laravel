import createAxios from '@/utils/request';
import type { IAgentMessage } from '@/domain/iAgents.ts';

interface PaginatorData<T> {
    data: T[];
    total: number;
    pageSize: number;
    current: number;
}

/** 获取会话消息列表 */
export async function getMessages(conversationId: string, params?: Record<string, any>) {
    return createAxios<PaginatorData<IAgentMessage>>({
        url: `/ai/conversation/${conversationId}/messages`,
        method: 'get',
        params,
    });
}
