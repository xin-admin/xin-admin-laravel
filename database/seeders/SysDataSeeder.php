<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SysDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $date = date('Y-m-d H:i:s');
        // 系统设置初始数据
        DB::table('sys_setting_group')->insert([
             ['id' => 1, 'title' => '网站设置', 'key' => 'web', 'remark' => '网站基础设置', 'created_at' => $date, 'updated_at' => $date],
             ['id' => 2, 'title' => '邮件配置', 'key' => 'mail', 'remark' => '邮件服务配置，用于发送系统邮件', 'created_at' => $date, 'updated_at' => $date],
             ['id' => 3, 'title' => '存储配置', 'key' => 'storage', 'remark' => '文件存储配置，支持本地存储和S3兼容对象存储', 'created_at' => $date, 'updated_at' => $date],
        ]);
        DB::table('sys_setting_items')->insert([
            ['id' => 1, 'group_id' => 1, 'key' => 'title', 'title' => '网站标题', 'describe' => '网站标题，用于展示在网站logo旁边和登录页面以及网页title中', 'values' => 'Xin Admin', 'type' => 'Input','options' => "", 'sort' => 0, 'created_at' => $date, 'updated_at' => $date,],
            ['id' => 2, 'group_id' => 1, 'key' => 'logo', 'title' => '网站LOGO', 'describe' => '网站的LOGO，用于标识网站', 'values' => 'https://file.xinadmin.cn/file/favicons.ico', 'type' => 'Input','options' => "", 'sort' => 1, 'created_at' => $date, 'updated_at' => $date,],
            ['id' => 3, 'group_id' => 1, 'key' => 'subtitle', 'title' => '网站副标题', 'describe' => '网站副标题，展示在登录页面标题的下面', 'values' => 'Xin Admin 快速开发框架', 'type' => 'Input','options' => "", 'sort' => 2, 'created_at' => $date, 'updated_at' => $date,],
            ['id' => 4, 'group_id' => 1, 'key' => 'describe', 'title' => '网站描述', 'describe' => '网站的基本描述', 'values' => '没有描述', 'type' => 'TextArea','options' => "", 'sort' => 2, 'created_at' => $date, 'updated_at' => $date,],
            // 邮件配置项
            ['id' => 5, 'group_id' => 2, 'key' => 'mailer', 'title' => '邮件驱动', 'describe' => '邮件发送驱动类型，推荐使用 smtp', 'values' => 'smtp', 'type' => 'Radio', 'options' => "smtp=SMTP", 'sort' => 0, 'created_at' => $date, 'updated_at' => $date,],
            ['id' => 6, 'group_id' => 2, 'key' => 'host', 'title' => 'SMTP服务器', 'describe' => 'SMTP邮件服务器地址，如：smtp.qq.com、smtp.163.com', 'values' => '', 'type' => 'Input',  'options' => "", 'sort' => 1, 'created_at' => $date, 'updated_at' => $date,],
            ['id' => 7, 'group_id' => 2, 'key' => 'port', 'title' => 'SMTP端口', 'describe' => 'SMTP服务器端口，常用：25(无加密)、465(SSL)、587(TLS)', 'values' => '465', 'type' => 'InputNumber','options' => "", 'sort' => 2, 'created_at' => $date, 'updated_at' => $date,],
            ['id' => 8, 'group_id' => 2, 'key' => 'username', 'title' => 'SMTP用户名', 'describe' => 'SMTP认证用户名，通常为发件人邮箱地址', 'values' => '', 'type' => 'Input','options' => "", 'sort' => 3, 'created_at' => $date, 'updated_at' => $date,],
            ['id' => 9, 'group_id' => 2, 'key' => 'password', 'title' => 'SMTP密码/授权码', 'describe' => 'SMTP认证密码或授权码（QQ邮箱、163邮箱等需使用授权码）', 'values' => '', 'type' => 'Input','options' => "", 'sort' => 4, 'created_at' => $date, 'updated_at' => $date,],
            ['id' => 10, 'group_id' => 2, 'key' => 'encryption', 'title' => '加密方式', 'describe' => '邮件传输加密方式：ssl(465端口)、tls(587端口) 或无加密', 'values' => 'ssl', 'type' => 'Radio', 'options' => "ssl=SSL (465端口)\ntls=TLS (587端口)\nnull=无加密", 'sort' => 5, 'created_at' => $date, 'updated_at' => $date,],
            ['id' => 11, 'group_id' => 2, 'key' => 'from_address', 'title' => '发件人邮箱', 'describe' => '发送邮件时显示的发件人邮箱地址', 'values' => '', 'type' => 'Input','options' => "", 'sort' => 6, 'created_at' => $date, 'updated_at' => $date,],
            ['id' => 12, 'group_id' => 2, 'key' => 'from_name', 'title' => '发件人名称', 'describe' => '发送邮件时显示的发件人名称', 'values' => 'Xin Admin', 'type' => 'Input','options' => "", 'sort' => 7, 'created_at' => $date, 'updated_at' => $date,],
            // 存储配置项
            ['id' => 13, 'group_id' => 3, 'key' => 'disk', 'title' => '存储驱动', 'describe' => '默认文件存储驱动，local(本地)、s3(对象存储)', 'values' => 'public', 'type' => 'Radio', 'options' => "local=本地\ns3=对象存储(S3)", 'sort' => 0, 'created_at' => $date, 'updated_at' => $date,],
            ['id' => 14, 'group_id' => 3, 'key' => 's3_key', 'title' => 'Access Key ID', 'describe' => 'S3兼容存储的Access Key ID（阿里云OSS、腾讯COS、七牛、MinIO等）', 'values' => '', 'type' => 'Input', 'options' => '', 'sort' => 1, 'created_at' => $date, 'updated_at' => $date,],
            ['id' => 15, 'group_id' => 3, 'key' => 's3_secret', 'title' => 'Secret Access Key', 'describe' => 'S3兼容存储的Secret Access Key', 'values' => '', 'type' => 'Input', 'options' => '', 'sort' => 2, 'created_at' => $date, 'updated_at' => $date,],
            ['id' => 16, 'group_id' => 3, 'key' => 's3_region', 'title' => '存储区域', 'describe' => '存储区域，如：us-east-1、oss-cn-hangzhou、ap-guangzhou', 'values' => '', 'type' => 'Input', 'options' => '', 'sort' => 3, 'created_at' => $date, 'updated_at' => $date,],
            ['id' => 17, 'group_id' => 3, 'key' => 's3_bucket', 'title' => '存储桶', 'describe' => 'Bucket名称', 'values' => '', 'type' => 'Input', 'options' => '', 'sort' => 4, 'created_at' => $date, 'updated_at' => $date,],
            ['id' => 18, 'group_id' => 3, 'key' => 's3_url', 'title' => 'CDN/访问地址', 'describe' => '文件访问URL前缀，如CDN地址或Bucket域名，例如：https://cdn.example.com', 'values' => '', 'type' => 'Input', 'options' => '', 'sort' => 5, 'created_at' => $date, 'updated_at' => $date,],
            ['id' => 19, 'group_id' => 3, 'key' => 's3_endpoint', 'title' => 'Endpoint端点', 'describe' => 'S3兼容存储端点，如：https://oss-cn-hangzhou.aliyuncs.com', 'values' => '', 'type' => 'Input', 'options' => '', 'sort' => 6, 'created_at' => $date, 'updated_at' => $date,],
        ]);
        // 字典初始数据
        DB::table('sys_dict')->insert([
            ['id' => 1, 'name' => '性别', 'type' => 'default', 'describe' => '性别字典', 'code' => 'sex', 'created_at' => $date, 'updated_at' => $date,],
            ['id' => 2, 'name' => '状态', 'type' => 'default', 'describe' => '状态字典', 'code' => 'status', 'created_at' => $date, 'updated_at' => $date,],
            ['id' => 3, 'name' => '权限类型', 'type' => 'tag', 'describe' => '系统权限类型字典', 'code' => 'ruleType', 'created_at' => $date, 'updated_at' => $date,],
        ]);
        DB::table('sys_dict_item')->insert([
            ['id' => 1, 'dict_id' => 1, 'label' => '男', 'value' => '0', 'switch' => '1', 'status' => 'default', 'created_at' => $date, 'updated_at' => $date],
            ['id' => 2, 'dict_id' => 1, 'label' => '女', 'value' => '1', 'switch' => '1', 'status' => 'default', 'created_at' => $date, 'updated_at' => $date],
            ['id' => 3, 'dict_id' => 1, 'label' => '其它', 'value' => '2', 'switch' => '1', 'status' => 'default', 'created_at' => $date, 'updated_at' => $date],
            ['id' => 4, 'dict_id' => 2, 'label' => '开启', 'value' => '1', 'switch' => '1', 'status' => 'success', 'created_at' => $date, 'updated_at' => $date],
            ['id' => 5, 'dict_id' => 2, 'label' => '关闭', 'value' => '0', 'switch' => '1', 'status' => 'error', 'created_at' => $date, 'updated_at' => $date],
            ['id' => 6, 'dict_id' => 3, 'label' => '一级菜单', 'value' => '0', 'switch' => '1', 'status' => 'processing', 'created_at' => $date, 'updated_at' => $date],
            ['id' => 7, 'dict_id' => 3, 'label' => '子菜单', 'value' => '1', 'switch' => '1', 'status' => 'success', 'created_at' => $date, 'updated_at' => $date],
            ['id' => 8, 'dict_id' => 3, 'label' => '按钮/API', 'value' => '2', 'switch' => '1', 'status' => 'default', 'created_at' => $date, 'updated_at' => $date],
        ]);
        // 文件初始化数据
        DB::table('sys_file_group')->insert([
            ['id' => 1, 'name' => '默认分组', 'sort' => 0, 'describe' => '默认分组', 'created_at' => $date, 'updated_at' => $date],
            ['id' => 2, 'name' => '用户头像', 'sort' => 1, 'describe' => '用户头像分组', 'created_at' => $date, 'updated_at' => $date],
            ['id' => 3, 'name' => '系统图片', 'sort' => 2, 'describe' => '系统图片分组', 'created_at' => $date, 'updated_at' => $date],
            ['id' => 4, 'name' => '用户上传', 'sort' => 3, 'describe' => '用户上传分组', 'created_at' => $date, 'updated_at' => $date],
            ['id' => 5, 'name' => '系统附件', 'sort' => 4, 'describe' => '系统附件分组', 'created_at' => $date, 'updated_at' => $date],
            ['id' => 6, 'name' => '其他文件', 'sort' => 5, 'describe' => '其他文件分组', 'created_at' => $date, 'updated_at' => $date],
            ['id' => 7, 'name' => '临时文件', 'sort' => 6, 'describe' => '临时文件分组，用于存放临时上传的文件', 'created_at' => $date, 'updated_at' => $date],
        ]);
    }
}