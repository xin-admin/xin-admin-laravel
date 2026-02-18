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
        if (! Schema::hasTable('sys_dict')) {
            Schema::create('sys_dict', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 100)->comment('字典名称');
                $table->string('code', 100)->unique()->comment('字典编码');
                $table->string('render_type', 20)->default('text')->comment('渲染类型：text纯文本,tag标签,badge徽标');
                $table->string('describe', 500)->nullable()->comment('字典描述');
                $table->unsignedTinyInteger('status')->default(0)->comment('状态：0正常 1停用');
                $table->unsignedInteger('sort')->default(0)->comment('排序');
                $table->timestamps();
                $table->comment('字典类型表');
            });
        }
        if (! Schema::hasTable('sys_dict_item')) {
            Schema::create('sys_dict_item', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedBigInteger('dict_id')->comment('字典ID');
                $table->string('label', 100)->comment('字典标签');
                $table->string('value', 100)->comment('字典键值');
                $table->string('color', 50)->default('default')->comment('颜色');
                $table->unsignedTinyInteger('status')->default(0)->comment('状态：0正常 1停用');
                $table->unsignedInteger('sort')->default(0)->comment('排序');
                $table->timestamps();
                $table->index('dict_id');
                $table->comment('字典数据表');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_dict');
        Schema::dropIfExists('sys_dict_item');
    }
};
