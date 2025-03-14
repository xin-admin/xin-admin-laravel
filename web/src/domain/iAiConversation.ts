import { IAdminUserList } from '@/domain/iAdminList';

/**
 * AI 对话
 */
export interface IAiConversation {
  /**
   * 对话id
   */
  id?: number;
  /**
   * UUID
   */
  uuid?: string;
  /**
   * 对话组id
   */
  group_id?: number;
  /**
   * 对话角色
   */
  role?: string;
  /**
   * 对话内容
   */
  message?: string;
  /**
   * 对话创建时间
   */
  create_time?: string;
  /**
   * 对话更新时间
   */
  update_time?: string;
}
