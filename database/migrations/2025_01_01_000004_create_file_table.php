<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 文件数据表
     */
    public function up(): void
    {
        if (! Schema::hasTable('sys_file')) {
            Schema::create('sys_file', function (Blueprint $table) {
                $table->increments('id')->comment('文件ID');
                $table->integer('group_id')->comment('文件分组ID');
                $table->integer('channel')->comment('上传来源(10商户后台 20用户端)');
                $table->string('disk', 10)->comment('存储方式');
                $table->integer('file_type')->comment('文件类型');
                $table->string('file_name', 255)->comment('文件名称');
                $table->string('file_path', 255)->comment('文件路径');
                $table->integer('file_size')->comment('文件大小（字节）');
                $table->string('file_ext', 20)->comment('文件扩展名');
                $table->integer('uploader_id')->comment('上传者用户ID');
                $table->softDeletes();
                $table->timestamps();
                $table->comment('文件表');
            });
        }
        if (! Schema::hasTable('sys_file_group')) {
            Schema::create('file_group', function (Blueprint $table) {
                $table->increments('id')->comment('文件分组ID');
                $table->string('name', 50)->comment('文件名称');
                $table->integer('sort')->comment('分组排序');
                $table->string('describe', 500)->comment('分组描述');
                $table->timestamps();
                $table->comment('文件分组表');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_file');
        Schema::dropIfExists('sys_file_group');
    }
};
