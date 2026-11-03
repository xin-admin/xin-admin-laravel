---
paths:
  - 'app/Http/Controllers/**'
---

# Controllers

## 控制器必须继承 BaseController 并使用属性路由
所有控制器继承 App\Http\Controllers\BaseController 并使用 RequestJson trait 返回响应（success/error/warn/throwError）。路由只用 PHP 8 属性声明：类级 #[RequestAttribute(前缀, 权限前缀)] + 方法级 #[GetRoute]/#[PostRoute]/#[PutRoute]/#[DeleteRoute](authorize: '动作')。禁止创建 routes/web.php、routes/api.php 或在其中写 Route:: 注册。权限串为 Sanctum abilities（如 system.user.query），新权限需录入 sys_rule 表。标准 CRUD 方法名固定为 query/create/update/delete。
