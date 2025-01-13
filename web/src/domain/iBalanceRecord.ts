import { IUserList } from '@/domain/iUserList';

export interface IBalanceRecord {
  id: number;
  user_id: string;
  user: IUserList;
  scene: string;
  balance: string;
  after: string;
  before: string;
  describe: string;
  remark: string;
  created_at: string;
}
