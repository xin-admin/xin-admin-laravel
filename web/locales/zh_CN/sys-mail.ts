export default {
  // 页面标题和描述
  'mail.page.title': '邮件设置',
  'mail.page.description': '邮件配置用于发送邮件，支持故障切换与循环切换',

  // 模式选择
  'mail.mode': '模式切换',
  'mail.mode.single': '单驱动',
  'mail.mode.failover': '故障切换',
  'mail.mode.roundrobin': '循环切换',

  // 驱动
  'mail.driver': '邮件驱动',
  'mail.driver.smtp': 'SMTP',
  'mail.driver.ses': 'Amazon SES',
  'mail.driver.mailgun': 'Mailgun',
  'mail.driver.postmark': 'Postmark',
  'mail.driver.resend': 'Resend',
  'mail.driver.log': '日志',
  'mail.driver.array': 'Array (调试)',

  // SMTP 配置
  'mail.smtp.title': 'SMTP 配置',
  'mail.host': '服务器地址',
  'mail.host.placeholder': 'smtp.example.com',
  'mail.port': '端口',
  'mail.port.placeholder': '587',
  'mail.username': '用户名',
  'mail.username.placeholder': 'your-email@example.com',
  'mail.password': '密码',
  'mail.password.placeholder': '邮箱密码或应用密码',

  // 日志配置
  'mail.log.title': '日志配置',
  'mail.log.channel': '日志驱动',
  'mail.log.channel.placeholder': 'mail',

  // Postmark 配置
  'mail.postmark.title': 'Postmark 配置',
  'mail.postmark.token': 'Postmark Token',
  'mail.postmark.token.placeholder': '您的 Postmark API token',

  // Resend 配置
  'mail.resend.title': 'Resend 配置',
  'mail.resend.key': 'Resend API Key',
  'mail.resend.key.placeholder': '您的 Resend API key',

  // Mailgun 配置
  'mail.mailgun.title': 'Mailgun 配置',
  'mail.mailgun.domain': 'Mailgun Domain',
  'mail.mailgun.domain.placeholder': 'mg.yourdomain.com',
  'mail.mailgun.secret': 'Mailgun Secret',
  'mail.mailgun.secret.placeholder': '您的 Mailgun API key',
  'mail.mailgun.endpoint': 'Mailgun Endpoint',
  'mail.mailgun.endpoint.placeholder': 'api.mailgun.net',

  // SES 配置
  'mail.ses.title': 'SES (Amazon) 配置',
  'mail.ses.key': 'AWS Access Key',
  'mail.ses.key.placeholder': 'AKIA...',
  'mail.ses.secret': 'AWS Secret Key',
  'mail.ses.secret.placeholder': '您的 AWS secret key',
  'mail.ses.region': 'AWS Region',
  'mail.ses.region.placeholder': 'us-east-1',
  'mail.ses.token': 'AWS Session Token',
  'mail.ses.token.placeholder': '可选的会话 token',

  // 发件人设置
  'mail.from_address': '发件人邮箱',
  'mail.from_address.placeholder': 'noreply@example.com',
  'mail.from_name': '发件人名称',
  'mail.from_name.placeholder': '您的应用名称',

  // 测试邮件
  'mail.test.title': '发送测试邮件',
  'mail.test.to': '收件人邮箱',
  'mail.test.to.placeholder': 'receiver@example.com',
  'mail.test.send': '发送测试',
  'mail.test.success': '测试邮件发送成功',
  'mail.test.failed': '测试邮件发送失败',

  // 保存
  'mail.save.success': '配置保存成功',
  'mail.save.failed': '配置保存失败',
};
