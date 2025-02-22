<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 字典数据表
     */
    public function up(): void
    {
        if (! Schema::hasTable('dict')) {
            Schema::create('dict', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 50)->comment('字典名称');
                $table->string('code', 50)->comment('字典编码');
                $table->string('describe', 255)->nullable()->comment('字典描述');
                $table->string('type', 10)->default('default')->comment('字典类型');
                $table->timestamps();
            });
        }
        if (! Schema::hasTable('dict_item')) {
            Schema::create('dict_item', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedBigInteger('dict_id')->comment('字典ID');
                $table->string('label', 50)->comment('字典项名称');
                $table->string('value', 50)->comment('字典项值');
                $table->unsignedInteger('switch')->default(1)->comment('是否启用：0：禁用，1：启用');
                $table->string('status', 10)->default('default')->comment('状态：（default,success,error,processing,warning）');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dict');
        Schema::dropIfExists('dict_item');
    }
};
