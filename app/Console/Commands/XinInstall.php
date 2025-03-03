<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class XinInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xin:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bar = $this->output->createProgressBar(10);
        $bar->start();
        // 选择使用的语言

        // 输入数据库名称、账户、密码
        $name = $this->ask('What is your DB_DATABASE?');
        $bar->advance();
        $this->newLine();
        $username = $this->ask('What is your DB_USERNAME?');
        $bar->advance();
        $this->newLine();
        $password = $this->secret('What is your DB_PASSWORD?');
        $bar->finish();
        // 复制并移动env文件

        // 写入env文件

        // 执行数据库迁移

        // 执行数据导入

        // 创建文件储存链接
    }
}
