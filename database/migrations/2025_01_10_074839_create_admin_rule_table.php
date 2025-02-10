<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_rule', function (Blueprint $table) {
            $table->increments('rule_id')->comment('权限ID');
            $table->integer('parent_id')->default(0)->comment('父级ID');
            $table->string('type', 1)->default('0')->comment('类型：1、一级菜单，2、子菜单，3、操作');
            $table->integer('sort')->default(0)->comment('排序');
            $table->string('name', 100)->default('')->comment('名称');
            $table->string('path', 100)->default('')->comment('路径');
            $table->string('icon', 100)->default('')->comment('图标');
            $table->string('key', 100)->unique()->comment('唯一标识');
            $table->string('local', 100)->default('')->comment('语言包');
            $table->integer('status')->default(1)->comment('状态：1、正常，0、禁用');
            $table->integer('show')->default(1)->comment('显示：1、显示，0、隐藏');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_rule');
    }
};
