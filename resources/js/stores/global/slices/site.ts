/**
 * 网站基础信息 Slice
 * 管理 logo、标题、描述等网站基础信息
 */

import type { StateCreator } from 'zustand';
import type { GlobalStore, SiteState, SiteAction } from '@/stores/types';
import { getWebInfo } from "@/api";

export type SiteSlice = SiteState & SiteAction;

/**
 * 网站信息初始状态
 */
export const initialSiteState: SiteState = {
  logo: "https://file.xinadmin.cn/file/favicons.ico",
  title: "Xin Admin",
  subtitle: "基于 Ant Design 的后台管理框架",
  describe: "Xin Admin 是一个基于 Ant Design 的后台管理框架",
  documentTitle: "Xin Admin",
};

/**
 * 创建网站信息 Slice
 */
export const createSiteSlice: StateCreator<GlobalStore, [], [], SiteSlice> = (set) => ({
  ...initialSiteState,
  
  initWebInfo: async () => {
    try {
      const response = await getWebInfo();
      if (response.data.data) {
        set(response.data.data);
      }
    } catch (error) {
      console.error('获取网站信息失败:', error);
    }
  },
  
  setDocumentTitle: (documentTitle: string) => {
    set({ documentTitle });
  },
});
