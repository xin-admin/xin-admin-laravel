import createAxios from '@/utils/request';

/** 获取邮件配置 */
export async function getMailConfig() {
  return createAxios({
    url: '/system/mail/config',
    method: 'get',
  });
}

/** 保存邮件配置 */
export async function saveMailConfig(data: Record<string, any>) {
  return createAxios({
    url: '/system/mail/save',
    method: 'post',
    data,
  });
}

/** 发送测试邮件 */
export async function sendTestMail(to: string) {
  return createAxios({
    url: '/system/mail/test',
    method: 'post',
    data: { to },
  });
}
