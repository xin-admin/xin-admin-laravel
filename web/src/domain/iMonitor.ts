import { IAdminUserList } from '@/domain/iAdminList';

export interface IMonitor {
  id?: number
  name?: string
  controller?: string
  action?: string
  ip?: string
  host?: string
  url?: string
  data?: string
  params?: string
  user_id?: number
  address?: string
  user?: IAdminUserList
  create_time?: string
}
