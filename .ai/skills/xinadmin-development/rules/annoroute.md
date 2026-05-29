# AnnoRoute Attribute Routing

AnnoRoute is a PHP 8 Attribute-based route registration module built into XinAdmin. Routes are declared via controller annotations, with automatic Sanctum authentication and permission verification integration.

## Core Concepts

### Route Composition

The final route URL is: `RequestAttribute.routePrefix` + `methodAttribute.route`

```php
#[RequestAttribute('/system/user', 'system.user')]
class SysUserController extends BaseController
{
    #[GetRoute('/role', 'role')]
    public function role(): JsonResponse { }
}
// Final route: GET /system/user/role
```

The final permission string is: `RequestAttribute.abilitiesPrefix` + `.` + `methodAttribute.authorize`

```php
// abilitiesPrefix = "system.user", authorize = "role"
// Final ability: "system.user.role"
```

### Route Registration

Routes are registered by scanning directories for `*Controller.php` files. In your ServiceProvider:

```php
use Modules\AnnoRoute\AnnoRoute;

public function boot(AnnoRoute $annoRoute): void
{
    $annoRoute->register(base_path('modules/YourModule/Http/Controllers'));
}
```

The scanner reads each controller file, extracts namespace + class name, then uses reflection to find and register attributes. Only classes with `#[RequestAttribute]` are registered.

## Attribute Reference

### Class-Level: `#[RequestAttribute]`

Defines the shared prefix and auth configuration for all routes within the controller.

```php
use Modules\AnnoRoute\Attribute\RequestAttribute;

#[RequestAttribute(
    routePrefix: '/admin/user',
    abilitiesPrefix: 'admin.user',
    middleware: 'log',              // string or array — additional middleware for all routes
    authGuard: 'admin',             // optional — Sanctum guard provider
)]
class UserController { }
```

| Parameter         | Type             | Default | Description                                      |
|-------------------|------------------|---------|--------------------------------------------------|
| `routePrefix`     | `string`         | `''`    | URL prefix shared by all routes in this controller |
| `abilitiesPrefix` | `string`         | `''`    | Prefix for permission ability strings            |
| `middleware`      | `string\|array`  | `''`    | Additional middleware applied to every route      |
| `authGuard`       | `?string`        | `null`  | Sanctum auth guard provider name                 |

### Method-Level Attributes

#### `#[GetRoute]` / `#[PostRoute]` / `#[PutRoute]` / `#[DeleteRoute]`

All method attributes share an identical constructor signature:

```php
use Modules\AnnoRoute\Attribute\{GetRoute, PostRoute, PutRoute, DeleteRoute};

#[GetRoute(
    route: '/{id}',
    authorize: 'update',
    middleware: 'throttle:10,1',
    where: ['id' => '[0-9]+'],
)]
public function show(int $id): JsonResponse { }
```

| Parameter    | Type             | Default | Description                                                    |
|--------------|------------------|---------|----------------------------------------------------------------|
| `route`      | `string`         | `''`    | Route path appended to the class `routePrefix`                 |
| `authorize`  | `string\|bool`   | `true`  | Permission ability string; `false` disables auth entirely      |
| `middleware` | `string\|array`  | `''`    | Route-specific middleware                                       |
| `where`      | `array`          | `[]`    | Regex constraints for route parameters, e.g. `['id' => '[0-9]+']` |

### Authorization Behavior

When `authorize` is not `false` or empty, these middleware are automatically added:

1. `auth:sanctum` — Sanctum authentication
2. `authGuard:{guard}` — guard check (or `authGuard` without a specific guard)
3. `abilities:{prefix}.{authorize}` — permission check

When `authorize` is `false`, no auth middleware is applied (public route).

## Usage Examples

### Basic CRUD Controller

```php
use Modules\AnnoRoute\Attribute\{RequestAttribute, GetRoute, PostRoute, PutRoute, DeleteRoute};

#[RequestAttribute('/system/dict', 'system.dict')]
class SysDictController extends BaseController
{
    #[GetRoute(authorize: 'query')]
    public function query(Request $request): JsonResponse
    {
        // GET /system/dict — ability: system.dict.query
        return $this->success($data);
    }

    #[PostRoute(authorize: 'create')]
    public function create(FormRequest $request): JsonResponse
    {
        // POST /system/dict — ability: system.dict.create
        return $this->success();
    }

    #[PutRoute(route: '/{id}', authorize: 'update', where: ['id' => '[0-9]+'])]
    public function update(int $id, FormRequest $request): JsonResponse
    {
        // PUT /system/dict/123 — ability: system.dict.update
        return $this->success();
    }

    #[DeleteRoute(route: '/{id}', authorize: 'delete', where: ['id' => '[0-9]+'])]
    public function delete(int $id): JsonResponse
    {
        // DELETE /system/dict/123 — ability: system.dict.delete
        return $this->success();
    }
}
```

### Public Routes (No Auth)

```php
#[GetRoute('/public-data', authorize: false)]
public function publicData(): JsonResponse
{
    return $this->success($data);
}
```

### Custom Route Path

When the method route is empty string (default), the controller routePrefix is the full route:

```php
#[RequestAttribute('/dashboard', 'dashboard')]
class DashboardController extends BaseController
{
    #[GetRoute(authorize: 'index')]
    public function index(): JsonResponse
    {
        // GET /dashboard — ability: dashboard.index
    }
}
```

### Additional Middleware

```php
#[GetRoute('/export', 'export', middleware: 'throttle:5,1')]
public function export(): JsonResponse { }

#[PostRoute('/batch', 'batch', middleware: ['log', 'transaction'])]
public function batchProcess(): JsonResponse { }
```

### Route with Multiple Parameters

```php
#[GetRoute(
    route: '/{deptId}/user/{userId}',
    authorize: 'detail',
    where: ['deptId' => '[0-9]+', 'userId' => '[0-9]+'],
)]
public function detail(int $deptId, int $userId): JsonResponse { }
```

## Key Rules

- Namespace: `Modules\AnnoRoute\Attribute\`
- Controller classes MUST use `#[RequestAttribute]` to be discovered; methods are only registered when the class has this attribute
- Method attributes: `GetRoute`, `PostRoute`, `PutRoute`, `DeleteRoute`
- Route registration: `AnnoRoute->register(path)` in ServiceProvider `boot()`
- Scanner only looks for `*Controller.php` files
- All auth middleware is auto-assembled — only declare `authorize` strings, not auth middleware directly
- Inherit `BaseController` for the `success()` / `error()` response helpers
- Controllers must return `Illuminate\Http\JsonResponse`

## Common Pitfalls

### Forgetting #[RequestAttribute] on the Class

If the class attribute is missing, no routes from that controller will be registered — regardless of method attributes.

### Duplicate Route Prefix

The final route is `routePrefix + route`. Convention is leading slash on both — they concatenate directly (no double slash).

### authorize vs abilitiesPrefix

The full permission string is `abilitiesPrefix.authorize`. If `abilitiesPrefix` is empty, the raw `authorize` value is used. Omitting `abilitiesPrefix` means you must pass the full ability string in each method's `authorize`.
