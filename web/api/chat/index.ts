import createAxios from '@/utils/request';
import type {BubbleItemType, ConversationItemType} from "@ant-design/x";

/** 获取会话列表 */
export async function getConversations() {
  return createAxios<ConversationItemType[]>({
    url: '/chat/conversations',
    method: 'get',
  });
}

/** 获取会话消息 */
export async function getMessages(conversationId: string) {
  return createAxios<BubbleItemType[]>({
    url: `/chat/messages/${conversationId}`,
    method: 'get',
  });
}

/** 删除会话 */
export async function deleteConversation(conversationId: string) {
  return createAxios({
    url: `/chat/conversations/${conversationId}`,
    method: 'delete',
  });
}
