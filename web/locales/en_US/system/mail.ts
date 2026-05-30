export default {
  // Page title and description
  'system.mail.page.title': 'Mail Settings',
  'system.mail.page.description': 'Mail configuration for sending emails, supports failover and round-robin switching',

  // Mode selection
  'system.mail.mode': 'Mode',
  'system.mail.mode.single': 'Single',
  'system.mail.mode.failover': 'Failover',
  'system.mail.mode.roundrobin': 'Round Robin',

  // Driver
  'system.mail.driver': 'Mail Driver',
  'system.mail.driver.smtp': 'SMTP',
  'system.mail.driver.ses': 'Amazon SES',
  'system.mail.driver.mailgun': 'Mailgun',
  'system.mail.driver.postmark': 'Postmark',
  'system.mail.driver.resend': 'Resend',
  'system.mail.driver.log': 'Log',
  'system.mail.driver.array': 'Array (Debug)',

  // SMTP Configuration
  'system.mail.smtp.title': 'SMTP Configuration',
  'system.mail.host': 'Host',
  'system.mail.host.placeholder': 'smtp.example.com',
  'system.mail.port': 'Port',
  'system.mail.port.placeholder': '587',
  'system.mail.username': 'Username',
  'system.mail.username.placeholder': 'your-email@example.com',
  'system.mail.password': 'Password',
  'system.mail.password.placeholder': 'Your email password or app password',

  // Log Configuration
  'system.mail.log.title': 'Log Configuration',
  'system.mail.log.channel': 'Log Channel',
  'system.mail.log.channel.placeholder': 'mail',

  // Postmark Configuration
  'system.mail.postmark.title': 'Postmark Configuration',
  'system.mail.postmark.token': 'Postmark Token',
  'system.mail.postmark.token.placeholder': 'Your Postmark API token',

  // Resend Configuration
  'system.mail.resend.title': 'Resend Configuration',
  'system.mail.resend.key': 'Resend API Key',
  'system.mail.resend.key.placeholder': 'Your Resend API key',

  // Mailgun Configuration
  'system.mail.mailgun.title': 'Mailgun Configuration',
  'system.mail.mailgun.domain': 'Mailgun Domain',
  'system.mail.mailgun.domain.placeholder': 'mg.yourdomain.com',
  'system.mail.mailgun.secret': 'Mailgun Secret',
  'system.mail.mailgun.secret.placeholder': 'Your Mailgun API key',
  'system.mail.mailgun.endpoint': 'Mailgun Endpoint',
  'system.mail.mailgun.endpoint.placeholder': 'api.mailgun.net',

  // SES Configuration
  'system.mail.ses.title': 'SES (Amazon) Configuration',
  'system.mail.ses.key': 'AWS Access Key',
  'system.mail.ses.key.placeholder': 'AKIA...',
  'system.mail.ses.secret': 'AWS Secret Key',
  'system.mail.ses.secret.placeholder': 'Your AWS secret key',
  'system.mail.ses.region': 'AWS Region',
  'system.mail.ses.region.placeholder': 'us-east-1',
  'system.mail.ses.token': 'AWS Session Token',
  'system.mail.ses.token.placeholder': 'Optional session token',

  // From settings
  'system.mail.from_address': 'From Address',
  'system.mail.from_address.placeholder': 'noreply@example.com',
  'system.mail.from_name': 'From Name',
  'system.mail.from_name.placeholder': 'Your Application Name',

  // Test mail
  'system.mail.test.title': 'Send Test Email',
  'system.mail.test.to': 'Recipient Email',
  'system.mail.test.to.placeholder': 'receiver@example.com',
  'system.mail.test.send': 'Send Test',
  'system.mail.test.success': 'Test email sent successfully',
  'system.mail.test.failed': 'Failed to send test email',

  // Save
  'system.mail.save.success': 'Configuration saved successfully',
  'system.mail.save.failed': 'Failed to save configuration',
};
