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
        Schema::create('ai_conversation', function (Blueprint $table) {
            $table->uuid()->unique()->primary();
            $table->integer('group_id')->comment('会话ID');
            $table->string('role')->comment('角色');
            $table->text('message')->comment('消息');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('ai_conversation_group', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid()->unique()->comment('会话ID');
            $table->string('name')->comment('会话名称');
            $table->integer('user_id')->comment('用户ID');
            $table->string('model')->comment('模型');
            $table->integer('temperature');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_conversation');
        Schema::dropIfExists('ai_conversation_group');
    }
};
