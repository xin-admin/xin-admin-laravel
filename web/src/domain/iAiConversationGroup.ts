import { IAdminUserList } from '@/domain/iAdminList';

export interface IAiConversationGroup {
  /**
   * 对话组id
   */
  id?: number;
  /**
   * UUID
   */
  uuid?: string;
  /**
   * 对话组名称
   */
  name?: string;
  /**
   * 对话组状态
   */
  status?: number;
  /**
   * 对话组模型
   */
  model?: string;
  /**
   * 对话组温度
   */
  temperature?: number;
  /**
   * 对话组创建时间
   */
  create_time?: string;
  /**
   * 对话组更新时间
   */
  update_time?: string;
  /**
   * 对话关联用户
   */
  user_id?: number;
  /**
   * 对话关联用户
   */
  user?: IAdminUserList;
}
