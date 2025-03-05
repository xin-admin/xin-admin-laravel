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
        if (! Schema::hasTable('token')) {
            Schema::create('token', function (Blueprint $table) {
                $table->string('token', 50);
                $table->string('type', 50)->comment('类型');
                $table->integer('user_id')->comment('用户ID');
                $table->integer('expire_time')->comment('过期时间')->nullable();
                $table->integer('create_time')->comment('创建时间')->nullable();
                $table->index(['token']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token');
    }
};
