export default {
  // Page title and description
  'mail.page.title': 'Mail Settings',
  'mail.page.description': 'Mail configuration for sending emails, supports failover and round-robin switching',

  // Mode selection
  'mail.mode': 'Mode',
  'mail.mode.single': 'Single',
  'mail.mode.failover': 'Failover',
  'mail.mode.roundrobin': 'Round Robin',

  // Driver
  'mail.driver': 'Mail Driver',
  'mail.driver.smtp': 'SMTP',
  'mail.driver.ses': 'Amazon SES',
  'mail.driver.mailgun': 'Mailgun',
  'mail.driver.postmark': 'Postmark',
  'mail.driver.resend': 'Resend',
  'mail.driver.log': 'Log',
  'mail.driver.array': 'Array (Debug)',

  // SMTP Configuration
  'mail.smtp.title': 'SMTP Configuration',
  'mail.host': 'Host',
  'mail.host.placeholder': 'smtp.example.com',
  'mail.port': 'Port',
  'mail.port.placeholder': '587',
  'mail.username': 'Username',
  'mail.username.placeholder': 'your-email@example.com',
  'mail.password': 'Password',
  'mail.password.placeholder': 'Your email password or app password',

  // Log Configuration
  'mail.log.title': 'Log Configuration',
  'mail.log.channel': 'Log Channel',
  'mail.log.channel.placeholder': 'mail',

  // Postmark Configuration
  'mail.postmark.title': 'Postmark Configuration',
  'mail.postmark.token': 'Postmark Token',
  'mail.postmark.token.placeholder': 'Your Postmark API token',

  // Resend Configuration
  'mail.resend.title': 'Resend Configuration',
  'mail.resend.key': 'Resend API Key',
  'mail.resend.key.placeholder': 'Your Resend API key',

  // Mailgun Configuration
  'mail.mailgun.title': 'Mailgun Configuration',
  'mail.mailgun.domain': 'Mailgun Domain',
  'mail.mailgun.domain.placeholder': 'mg.yourdomain.com',
  'mail.mailgun.secret': 'Mailgun Secret',
  'mail.mailgun.secret.placeholder': 'Your Mailgun API key',
  'mail.mailgun.endpoint': 'Mailgun Endpoint',
  'mail.mailgun.endpoint.placeholder': 'api.mailgun.net',

  // SES Configuration
  'mail.ses.title': 'SES (Amazon) Configuration',
  'mail.ses.key': 'AWS Access Key',
  'mail.ses.key.placeholder': 'AKIA...',
  'mail.ses.secret': 'AWS Secret Key',
  'mail.ses.secret.placeholder': 'Your AWS secret key',
  'mail.ses.region': 'AWS Region',
  'mail.ses.region.placeholder': 'us-east-1',
  'mail.ses.token': 'AWS Session Token',
  'mail.ses.token.placeholder': 'Optional session token',

  // From settings
  'mail.from_address': 'From Address',
  'mail.from_address.placeholder': 'noreply@example.com',
  'mail.from_name': 'From Name',
  'mail.from_name.placeholder': 'Your Application Name',

  // Test mail
  'mail.test.title': 'Send Test Email',
  'mail.test.to': 'Recipient Email',
  'mail.test.to.placeholder': 'receiver@example.com',
  'mail.test.send': 'Send Test',
  'mail.test.success': 'Test email sent successfully',
  'mail.test.failed': 'Failed to send test email',

  // Save
  'mail.save.success': 'Configuration saved successfully',
  'mail.save.failed': 'Failed to save configuration',
};
