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
        if (! Schema::hasTable('xin_user')) {
            Schema::create('xin_user', function (Blueprint $table) {
                $table->increments('user_id')->comment('用户ID');
                $table->string('username', 20)->unique()->comment('用户名');
                $table->string('password', 100)->comment('密码');
                $table->string('nickname', 20)->default('')->comment('昵称');
                $table->string('email', 50)->default('')->comment('邮箱');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xin_user');
    }
};
