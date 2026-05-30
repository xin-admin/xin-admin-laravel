export default {
  // 页面标题和描述
  "system.mail.page.title": "邮件设置",
  "system.mail.page.description": "邮件配置用于发送邮件，支持故障切换与循环切换",

  // 模式选择
  "system.mail.mode": "模式切换",
  "system.mail.mode.single": "单驱动",
  "system.mail.mode.failover": "故障切换",
  "system.mail.mode.roundrobin": "循环切换",

  // 驱动
  "system.mail.driver": "邮件驱动",
  "system.mail.driver.smtp": "SMTP",
  "system.mail.driver.ses": "Amazon SES",
  "system.mail.driver.mailgun": "Mailgun",
  "system.mail.driver.postmark": "Postmark",
  "system.mail.driver.resend": "Resend",
  "system.mail.driver.log": "日志",
  "system.mail.driver.array": "Array (调试)",

  // SMTP 配置
  "system.mail.smtp.title": "SMTP 配置",
  "system.mail.host": "服务器地址",
  "system.mail.host.placeholder": "smtp.example.com",
  "system.mail.port": "端口",
  "system.mail.port.placeholder": "587",
  "system.mail.username": "用户名",
  "system.mail.username.placeholder": "your-email@example.com",
  "system.mail.password": "密码",
  "system.mail.password.placeholder": "邮箱密码或应用密码",

  // 日志配置
  "system.mail.log.title": "日志配置",
  "system.mail.log.channel": "日志驱动",
  "system.mail.log.channel.placeholder": "mail",

  // Postmark 配置
  "system.mail.postmark.title": "Postmark 配置",
  "system.mail.postmark.token": "Postmark Token",
  "system.mail.postmark.token.placeholder": "您的 Postmark API token",

  // Resend 配置
  "system.mail.resend.title": "Resend 配置",
  "system.mail.resend.key": "Resend API Key",
  "system.mail.resend.key.placeholder": "您的 Resend API key",

  // Mailgun 配置
  "system.mail.mailgun.title": "Mailgun 配置",
  "system.mail.mailgun.domain": "Mailgun Domain",
  "system.mail.mailgun.domain.placeholder": "mg.yourdomain.com",
  "system.mail.mailgun.secret": "Mailgun Secret",
  "system.mail.mailgun.secret.placeholder": "您的 Mailgun API key",
  "system.mail.mailgun.endpoint": "Mailgun Endpoint",
  "system.mail.mailgun.endpoint.placeholder": "api.mailgun.net",

  // SES 配置
  "system.mail.ses.title": "SES (Amazon) 配置",
  "system.mail.ses.key": "AWS Access Key",
  "system.mail.ses.key.placeholder": "AKIA...",
  "system.mail.ses.secret": "AWS Secret Key",
  "system.mail.ses.secret.placeholder": "您的 AWS secret key",
  "system.mail.ses.region": "AWS Region",
  "system.mail.ses.region.placeholder": "us-east-1",
  "system.mail.ses.token": "AWS Session Token",
  "system.mail.ses.token.placeholder": "可选的会话 token",

  // 发件人设置
  "system.mail.from_address": "发件人邮箱",
  "system.mail.from_address.placeholder": "noreply@example.com",
  "system.mail.from_name": "发件人名称",
  "system.mail.from_name.placeholder": "您的应用名称",

  // 测试邮件
  "system.mail.test.title": "发送测试邮件",
  "system.mail.test.to": "收件人邮箱",
  "system.mail.test.to.placeholder": "receiver@example.com",
  "system.mail.test.send": "发送测试",
  "system.mail.test.success": "测试邮件发送成功",
  "system.mail.test.failed": "测试邮件发送失败",

  // 保存
  "system.mail.save.success": "配置保存成功",
  "system.mail.save.failed": "配置保存失败",
};
