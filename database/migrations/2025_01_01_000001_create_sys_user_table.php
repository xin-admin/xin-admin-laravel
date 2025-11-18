<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 系统用户相关数据表
     */
    public function up(): void
    {
        // 系统用户表
        if (! Schema::hasTable('sys_user')) {
            Schema::create('sys_user', function (Blueprint $table) {
                $table->increments('id')->comment('系统用户ID');
                $table->string('username', 20)->unique()->comment('用户名');
                $table->string('password', 100)->comment('密码');
                $table->string('nickname', 20)->default('')->comment('昵称');
                $table->integer('avatar_id')->nullable()->comment('头像');
                $table->integer('sex')->default(0)->comment('性别（男、女）');
                $table->string('bio', 255)->default('')->nullable()->comment('个人简介');
                $table->string('mobile', 20)->default('')->comment('手机号');
                $table->string('email', 50)->unique()->comment('邮箱');
                $table->timestamp('email_verified_at')->nullable();
                $table->integer('dept_id')->default(0)->comment('部门ID');
                $table->string('login_ip', 60)->default('')->comment('最后登录IP');
                $table->timestamp('login_time')->nullable()->comment('最后登录时间');
                $table->integer('status')->default(1)->comment('状态（1正常 0停用）');
                $table->rememberToken();
                $table->timestamps();
                $table->softDeletes();
                $table->comment('系统用户表');
            });
        }

        // 系统用户角色表
        if (! Schema::hasTable('sys_role')) {
            Schema::create('sys_role', function (Blueprint $table) {
                $table->increments('id')->comment('角色ID');
                $table->string('name', 20)->default('')->comment('角色名称');
                $table->integer('sort')->default(0)->comment('排序');
                $table->string('description', 100)->default('')->comment('角色描述');
                $table->integer('status')->default(1)->comment('状态（1正常 0停用）');
                $table->timestamps();
                $table->comment('系统用户角色表');
            });
        }

        // 系统用户角色中间表
        if (! Schema::hasTable('sys_user_role')) {
            Schema::create('sys_user_role', function (Blueprint $table) {
                $table->integer('user_id')->comment('用户ID');
                $table->integer('role_id')->comment('角色ID');
                $table->unique(['user_id', 'role_id'], 'user_role_unique');
                $table->comment('系统用户角色关联表');
            });
        }

        // 系统用户部门表
        if (! Schema::hasTable('sys_dept')) {
            Schema::create('sys_dept', function (Blueprint $table) {
                $table->increments('id')->comment('部门ID');
                $table->integer('parent_id')->default(0)->comment('父级ID');
                $table->string('name', 100)->default('')->comment('部门名称');
                $table->string('code', 100)->unique()->default('')->comment('部门编码');
                $table->tinyInteger('type')->default(0)->comment('部门类型 0：公司 1：部门 2：岗位');
                $table->integer('sort')->default(0)->comment('排序');
                $table->string('phone', '20')->default('')->nullable()->comment('部门电话');
                $table->string('email', '50')->default('')->nullable()->comment('部门邮箱');
                $table->string('address', '255')->default('')->nullable()->comment('部门地址');
                $table->string('remark', '255')->default('')->nullable()->comment('备注');
                $table->integer('status')->default(0)->comment('部门状态（0正常 1停用）');
                $table->timestamps();
                $table->softDeletes();
                $table->comment('系统用户部门表');
            });
        }
        
        // 系统用户权限表
        if (! Schema::hasTable('sys_rule')) {
            Schema::create('sys_rule', function (Blueprint $table) {
                $table->increments('id')->comment('权限ID');
                $table->integer('parent_id')->default(0)->comment('父级ID');
                $table->string('type', 20)->comment("类型：'menu' | 'route' | 'nested-route' | 'rule'");
                $table->string('key', 100)->unique()->comment('唯一标识');
                $table->string('name', 100)->comment('名称');
                $table->string('path', 100)->nullable()->comment('路径');
                $table->string('icon', 100)->nullable()->comment('图标');
                $table->string('elementPath', 255)->nullable()->comment('路由组件的路径，当类型值为 route 时该配置有效');
                $table->integer('order')->default(0)->comment('排序');
                $table->string('local', 100)->nullable()->comment('语言包');
                $table->integer('status')->default(1)->comment('状态：1、正常，0、禁用');
                $table->integer('hidden')->default(1)->comment('显示：1、显示，0、隐藏');
                $table->integer('link')->default(0)->comment('是否外链：1、是，0、否');
                $table->timestamps();
                $table->comment('系统用户权限表');
            });
        }

        // 角色权限关联表
        if (! Schema::hasTable('sys_role_rule')) {
            Schema::create('sys_role_rule', function (Blueprint $table) {
                $table->integer('role_id')->comment('角色ID');
                $table->integer('rule_id')->comment('权限ID');
                $table->primary(['role_id', 'rule_id'], 'role_rule_primary');
                $table->comment('角色权限关联表');
            });
        }

        // 登录日志表
        if (! Schema::hasTable('sys_login_record')) {
            Schema::create('sys_login_record', function (Blueprint $table) {
                $table->increments('id')->comment('记录ID');
                $table->string('username', 20)->default('')->comment('用户名');
                $table->integer('user_id')->comment('用户ID');
                $table->string('ipaddr', 60)->default('')->comment('登录IP');
                $table->string('login_location', 255)->default('')->comment('登录地点');
                $table->string('browser', 255)->default('')->comment('浏览器');
                $table->string('os', 255)->default('')->comment('操作系统');
                $table->string('status', 1)->default('0')->comment('登录状态（0成功 1失败）');
                $table->string('msg', 255)->default('')->comment('提示消息');
                $table->timestamp('login_time')->comment('登录时间');
                $table->comment('系统用户登录日志表');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_user');
        Schema::dropIfExists('sys_rule');
        Schema::dropIfExists('sys_dept');
        Schema::dropIfExists('sys_role');
        Schema::dropIfExists('sys_user_role');
        Schema::dropIfExists('sys_role_rule');
        Schema::dropIfExists('sys_login_record');
    }
};
