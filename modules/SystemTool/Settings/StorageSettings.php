<?php

namespace Modules\SystemTool\Settings;

use Modules\SystemTool\Attributes\Setting;
use Modules\SystemTool\Base\SettingsDefinition;
use Modules\SystemTool\Enum\ESettingType;

/**
 * 文件存储配置定义
 *
 * 对应 config/filesystems.php 中的可配置项。
 * 敏感值（密钥、密码）使用 EncryptedString 类型存储。
 */
#[Setting(config: 'filesystems.default', type: ESettingType::String, description: '默认存储驱动（local / s3 / ftp / sftp）')]
#[Setting(config: 'filesystems.disks.local.url', type: ESettingType::String, description: '本地存储 URL')]

// S3
#[Setting(config: 'filesystems.disks.s3.key', type: ESettingType::EncryptedString, description: 'S3 Access Key ID')]
#[Setting(config: 'filesystems.disks.s3.secret', type: ESettingType::EncryptedString, description: 'S3 Secret Access Key')]
#[Setting(config: 'filesystems.disks.s3.region', type: ESettingType::String, description: 'S3 区域')]
#[Setting(config: 'filesystems.disks.s3.bucket', type: ESettingType::String, description: 'S3 Bucket')]
#[Setting(config: 'filesystems.disks.s3.url', type: ESettingType::String, description: 'S3 URL')]
#[Setting(config: 'filesystems.disks.s3.endpoint', type: ESettingType::String, description: 'S3 Endpoint')]
#[Setting(config: 'filesystems.disks.s3.use_path_style_endpoint', type: ESettingType::Bool, description: 'S3 使用路径样式端点')]

// FTP
#[Setting(config: 'filesystems.disks.ftp.host', type: ESettingType::String, description: 'FTP 主机')]
#[Setting(config: 'filesystems.disks.ftp.username', type: ESettingType::String, description: 'FTP 用户名')]
#[Setting(config: 'filesystems.disks.ftp.password', type: ESettingType::EncryptedString, description: 'FTP 密码')]
#[Setting(config: 'filesystems.disks.ftp.port', type: ESettingType::Number, description: 'FTP 端口')]
#[Setting(config: 'filesystems.disks.ftp.root', type: ESettingType::String, description: 'FTP 根目录')]
#[Setting(config: 'filesystems.disks.ftp.passive', type: ESettingType::Bool, description: 'FTP 被动模式')]
#[Setting(config: 'filesystems.disks.ftp.ssl', type: ESettingType::Bool, description: 'FTP SSL')]
#[Setting(config: 'filesystems.disks.ftp.timeout', type: ESettingType::Number, description: 'FTP 超时（秒）')]

// SFTP
#[Setting(config: 'filesystems.disks.sftp.host', type: ESettingType::String, description: 'SFTP 主机')]
#[Setting(config: 'filesystems.disks.sftp.username', type: ESettingType::String, description: 'SFTP 用户名')]
#[Setting(config: 'filesystems.disks.sftp.password', type: ESettingType::EncryptedString, description: 'SFTP 密码')]
#[Setting(config: 'filesystems.disks.sftp.port', type: ESettingType::Number, description: 'SFTP 端口')]
#[Setting(config: 'filesystems.disks.sftp.root', type: ESettingType::String, description: 'SFTP 根目录')]
#[Setting(config: 'filesystems.disks.sftp.timeout', type: ESettingType::Number, description: 'SFTP 超时（秒）')]
#[Setting(config: 'filesystems.disks.sftp.privateKey', type: ESettingType::EncryptedString, description: 'SFTP 私钥')]
#[Setting(config: 'filesystems.disks.sftp.passphrase', type: ESettingType::EncryptedString, description: 'SFTP 私钥口令')]
class StorageSettings extends SettingsDefinition
{
}
