import createAxios from '@/utils/request';
import type { IAgent } from '@/domain/iAgents';

export async function getAgentList() {
  return createAxios<IAgent[]>({
    url: '/ai/agent',
    method: 'get',
  });
}

export async function getAgent(id: number) {
  return createAxios<IAgent>({
    url: `/ai/agent/${id}`,
    method: 'get',
  });
}

export async function updateAgent(id: number, data: {enabled: boolean}) {
  return createAxios({
    url: `/ai/agent/${id}`,
    method: 'put',
    data,
  });
}
