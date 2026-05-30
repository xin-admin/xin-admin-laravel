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

        if(! Schema::hasTable('agents')){
            Schema::create('agents', function (Blueprint $table) {
                $table->id();
                $table->string('namespace', 500)->unique()->comment('完整类命名空间');
                $table->string('icon', 100)->nullable()->comment('图标');
                $table->string('name', 100)->comment('显示名称');
                $table->text('description')->nullable()->comment('描述');
                $table->json('tags')->nullable()->comment('标签');
                $table->boolean('enabled')->default(true)->comment('是否启用');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('agent_conversations')) {
            Schema::create('agent_conversations', function (Blueprint $table) {
                $table->string('id', 36)->primary();
                $table->foreignId('user_id')->nullable();
                $table->string('title');
                $table->timestamps();

                $table->index(['user_id', 'updated_at']);
            });
        }

        if (! Schema::hasTable('agent_conversation_messages')) {
            Schema::create('agent_conversation_messages', function (Blueprint $table) {
                $table->string('id', 36)->primary();
                $table->string('conversation_id', 36)->index();
                $table->foreignId('user_id')->nullable();
                $table->string('agent');
                $table->string('role', 25);
                $table->text('content');
                $table->text('attachments');
                $table->text('tool_calls');
                $table->text('tool_results');
                $table->text('usage');
                $table->text('meta');
                $table->timestamps();

                $table->index(['conversation_id', 'user_id', 'updated_at'], 'conversation_index');
                $table->index(['user_id']);
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
        Schema::dropIfExists('agent_conversations');
        Schema::dropIfExists('agent_conversation_messages');
    }
};
