<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('sys_app_settings')) {
            Schema::create('sys_app_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique()->index()->comment('配置键（点号表示法）');
                $table->tinyInteger('type')->index()->comment('类型枚举：10=String 15=Bool 20=Number 30=Array 40=Object 50=EncryptedString');
                $table->integer('n')->nullable()->comment('数字/布尔值');
                $table->string('s')->nullable()->comment('字符串值');
                $table->text('e')->nullable()->comment('扩展值：JSON/序列化/加密');
                $table->text('description')->nullable()->comment('描述');
                $table->timestamps();
                $table->comment('应用配置表（el-settings 风格列式类型存储）');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sys_app_settings');
    }
};
