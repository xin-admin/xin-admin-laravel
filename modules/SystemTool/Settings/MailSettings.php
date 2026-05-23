<?php

namespace Modules\SystemTool\Settings;

use Modules\SystemTool\Attributes\Setting;
use Modules\SystemTool\Base\SettingsDefinition;
use Modules\SystemTool\Enum\ESettingType;

/**
 * 邮件与服务配置定义
 *
 * 对应 config/mail.php 和 config/services.php 中的可配置项。
 * 敏感值（密码、Token、API Key）使用 EncryptedString 类型存储。
 */
#[Setting(config: 'mail.default', type: ESettingType::String, description: '默认邮件驱动（smtp / failover / roundrobin / log 等）')]
#[Setting(config: 'mail.mailers.smtp.host', type: ESettingType::String, description: 'SMTP 主机')]
#[Setting(config: 'mail.mailers.smtp.port', type: ESettingType::Number, description: 'SMTP 端口')]
#[Setting(config: 'mail.mailers.smtp.username', type: ESettingType::String, description: 'SMTP 用户名')]
#[Setting(config: 'mail.mailers.smtp.password', type: ESettingType::EncryptedString, description: 'SMTP 密码')]
#[Setting(config: 'mail.from.address', type: ESettingType::String, description: '发件人邮箱')]
#[Setting(config: 'mail.from.name', type: ESettingType::String, description: '发件人名称')]
#[Setting(config: 'mail.mailers.log.channel', type: ESettingType::String, description: '日志驱动通道')]

// Failover / RoundRobin
#[Setting(config: 'mail.mailers.failover.mailers', type: ESettingType::Array, description: 'Failover 邮件驱动列表')]
#[Setting(config: 'mail.mailers.roundrobin.mailers', type: ESettingType::Array, description: 'RoundRobin 邮件驱动列表')]

// 第三方服务
#[Setting(config: 'services.postmark.token', type: ESettingType::EncryptedString, description: 'Postmark Token')]
#[Setting(config: 'services.ses.key', type: ESettingType::EncryptedString, description: 'SES Access Key ID')]
#[Setting(config: 'services.ses.secret', type: ESettingType::EncryptedString, description: 'SES Secret Access Key')]
#[Setting(config: 'services.ses.region', type: ESettingType::String, description: 'SES 区域')]
#[Setting(config: 'services.ses.token', type: ESettingType::EncryptedString, description: 'SES Session Token')]
#[Setting(config: 'services.resend.key', type: ESettingType::EncryptedString, description: 'Resend API Key')]
#[Setting(config: 'services.mailgun.domain', type: ESettingType::String, description: 'Mailgun 域名')]
#[Setting(config: 'services.mailgun.secret', type: ESettingType::EncryptedString, description: 'Mailgun Secret')]
#[Setting(config: 'services.mailgun.endpoint', type: ESettingType::String, description: 'Mailgun Endpoint')]
class MailSettings extends SettingsDefinition
{
}
