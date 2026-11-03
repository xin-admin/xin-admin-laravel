---
paths:
  - 'database/migrations/**'
---

# Migrations

## 迁移文件约定
每张表用 if (! Schema::hasTable(...)) 包裹，down() 用 dropIfExists。主键统一 $table->increments('id')，所有字段与表必须写中文 ->comment()。一个迁移文件可建同模块多张关联表。状态字段语义以表内注释为准（不同表 0/1 含义不同）。
