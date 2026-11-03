---
paths:
  - 'app/Models/**'
---

# Models

## 模型命名与表约定
模型类必须带 Model 后缀（SysUserModel）并显式声明 protected $table。系统表前缀 sys_。定义 $fillable 与 $casts（状态/时间字段），关联方法带返回类型与中文 PHPDoc。sys_user/sys_role/sys_rule.status 为 1 正常 0 停用，而 sys_dept.status 为 0 正常 1 停用，混用会出 bug。认证模型 SysUserModel 使用 SoftDeletes + HasApiTokens。
