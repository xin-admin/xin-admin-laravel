import createAxios from '@/utils/request';
import type {AiList} from "@/domain/iAi.ts";


/** 获取 AI 列表 */
export async function getAiList() {
  return createAxios<AiList>({
    url: '/system/ai/list',
    method: 'get',
  });
}

/** 获取 AI 配置 */
export async function getAiConfig() {
  return createAxios({
    url: '/system/ai/config',
    method: 'get',
  });
}

/** 保存 AI 配置 */
export async function saveAiConfig(data: Record<string, any>) {
  return createAxios({
    url: '/system/ai/save',
    method: 'post',
    data,
  });
}

/** 测试 AI 供应商连接 */
export async function testAiConnection(provider: string) {
  return createAxios({
    url: '/system/ai/test',
    method: 'post',
    timeout: 60000,
    data: { provider },
  });
}
