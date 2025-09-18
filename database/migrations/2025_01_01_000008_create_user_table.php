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
        if (! Schema::hasTable('user')) {
            Schema::create('user', function (Blueprint $table) {
                $table->increments('id')->comment('用户ID');
                $table->string('username', 20)->unique()->comment('用户名');
                $table->string('password', 100)->comment('密码');
                $table->string('nickname', 20)->default('')->comment('昵称');
                $table->string('email', 50)->default('')->comment('邮箱');
                $table->timestamp('email_verified_at')->nullable();
                $table->rememberToken();
                $table->timestamps();
                $table->comment('APP用户表');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
