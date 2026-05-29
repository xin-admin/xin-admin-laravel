# XinAdmin

XinAdmin is a full-stack development framework: PHP8.2 + Laravel12 + React19 + TypeScript + Ant Design6 + Zustand + Tailwind CSS4. Licensed under MIT, free for commercial use without authorization.

## Built-in Features

- Dashboard: Echarts-based dashboards with demo pages
- Administrators: Backend user management with groups, permissions, and settings
- Role & Department Management: Role-based menu permission control, enterprise org structure
- System Settings: Visual form-based server variable configuration
- File Management: Backend file manager with folders, multi-select, grouping
- Dictionary Management: Maintenance of frequently used static data
- Mail & Storage Configuration: Visual config and testing for Laravel mail/filesystem
- AI Configuration: Visual config and testing for Laravel AI SDK
- Frontend Members: Permission, grouping, lists, balance records

# AnnoRoute

AnnoRoute is a PHP 8 Attribute-based route registration module. Routes are declared via controller annotations — no manual route files needed. Sanctum auth and permission verification are auto-integrated.

## Usage

- `#[RequestAttribute]` on the Controller class defines the common route prefix and permission prefix
- `#[GetRoute]` / `#[PostRoute]` / `#[PutRoute]` / `#[DeleteRoute]` on methods declare HTTP routes
- The `authorize` parameter controls access: `'query'` becomes ability `prefix.query`, `false` makes a route public

## Route Registration

In your ServiceProvider `boot()`:

```php
use Modules\AnnoRoute\AnnoRoute;

public function boot(AnnoRoute $annoRoute): void
{
    $annoRoute->register(base_path('modules/YourModule/Http/Controllers'));
}
```

## Controller Example

```php
use Modules\AnnoRoute\Attribute\{RequestAttribute, GetRoute, PostRoute, PutRoute, DeleteRoute};

#[RequestAttribute('/system/user', 'system.user')]
class SysUserController extends BaseController
{
    #[GetRoute(authorize: 'query')]
    public function query(Request $request): JsonResponse { }

    #[PostRoute(authorize: 'create')]
    public function create(FormRequest $request): JsonResponse { }

    #[PutRoute(route: '/{id}', authorize: 'update', where: ['id' => '[0-9]+'])]
    public function update(int $id, FormRequest $request): JsonResponse { }

    #[DeleteRoute(route: '/{id}', authorize: 'delete', where: ['id' => '[0-9]+'])]
    public function delete(int $id): JsonResponse { }
}
```

- `#[RequestAttribute]` params: `routePrefix`, `abilitiesPrefix`, `middleware` (string|array), `authGuard` (?string)
- Method attributes share: `route` (path appended to prefix), `authorize` (string|bool), `middleware`, `where` (regex array)
- Final route = `routePrefix + route`; final ability = `abilitiesPrefix + '.' + authorize`
- When `authorize` is not falsy, `auth:sanctum` + `authGuard` + `abilities:` middleware are auto-assembled
- Extend `Modules\Common\Http\Controllers\BaseController` for `success()` / `error()` response helpers

# Frontend

Source lives in `web/`. Bundled with Vite, outputting to `public/`. Package manager: pnpm.

## Directory Structure

| Directory | Purpose |
|-----------|---------|
| `web/api/` | Typed Axios wrappers per backend module |
| `web/components/` | Reusable UI: `AuthButton`, `DictTag`, `IconFont`, `XinForm`, `XinTable` |
| `web/domain/` | TypeScript interfaces for API models |
| `web/hooks/` | `useAuth`, `useLanguage`, `useMobile`, `useRequest` |
| `web/layout/` | Layout engine (4 modes), menu, header, breadcrumbs, theme |
| `web/locales/` | i18n (i18next), zh_CN + en_US |
| `web/pages/` | Auto-discovered page components (file-system routing) |
| `web/router/` | React Router v7 `createBrowserRouter` |
| `web/stores/` | Zustand: `global` (app/theme), `user` (auth/perms), `dict` (cache) |
| `web/utils/` | Axios instance with dedup, auth headers, error handling |

## Key Conventions

- Path alias `@/` → `web/`
- Zustand stores: `State` + `Actions` → `persist` + `devtools` → localStorage
- Access stores via selector: `useXxxStore(state => state.field)`
- All user-facing text via `useTranslation()` (react-i18next)
- Permission checks: `<AuthButton auth="system.user.create">` or `useAuth().auth('permission')`
- HTTP client auto-attaches `Authorization: Bearer`, `User-Language`, handles 401 auto-logout
- Pages in `web/pages/` are auto-routed; `index.tsx` maps to parent directory; root `/` → `/dashboard/analysis`
- Pages outside layout: add to `excludePaths` array in router config

# Layout

Wraps authenticated pages. Supports 4 modes set via global store: `side` (default), `top`, `mix`, `columns`.

Menus are fetched from `/system/menu` and stored in `LayoutContext` (React Context). Menu type: `'menu'` (folder), `'route'` (page), `'rule'` (perm-only). Server filters by user role — no client-side filtering needed. Labels support i18n via `node.local`.

Theme tokens (20+ properties) are managed in `web/layout/theme.ts`, applied via Ant Design `<ConfigProvider>`, persisted to localStorage under `global-storage`.

# XinForm And XinTable

Two declarative JSON-driven CRUD components. Define columns once with metadata — the same definition drives table display, search form, and create/edit forms.

## XinForm

Use for settings/config pages or standalone forms. Supports 3 layout modes: `'Form'` (inline), `'ModalForm'`, `'DrawerForm'`.

```tsx
<XinForm
  columns={[
    { dataIndex: 'username', title: 'Username', valueType: 'text', rules: [{ required: true }] },
    { dataIndex: 'role_id', title: 'Role', valueType: 'select', fieldProps: { options: roleOptions } },
  ]}
  layoutType="ModalForm"
  grid
  trigger={<Button type="primary">New</Button>}
  modalProps={{ title: 'Create User', width: 600 }}
  onFinish={async (values) => { await save(values); return true; }}
/>
```

Key `FormColumn` fields: `dataIndex` (supports nested paths `['a','b']`), `valueType` (26 types: `text`, `password`, `select`, `date`, `switch`, etc.), `fieldProps`, `fieldRender` (custom render), `dependency` (field linkage: `{ dependencies, visible?, disabled?, fieldProps? }`), `hideIn*` visibility flags.

`formRef` exposes `open()`, `close()`, `isOpen()`, `setLoading()` plus all Ant Design `FormInstance` methods.

## XinTable

Use for standard CRUD pages (list + create + update + delete). Auto-handles API calls, permissions, search, and toolbar.

```tsx
<XinTable<ISysUser>
  api="/system/user"
  columns={[
    { title: 'ID', dataIndex: 'id', hideInForm: true, width: 80 },
    { title: 'Username', dataIndex: 'username', valueType: 'text', rules: [{ required: true }] },
    { title: 'Status', dataIndex: 'status', valueType: 'radio', render: (v) => <Tag>{v === 1 ? 'Active' : 'Inactive'}</Tag> },
  ]}
  rowKey="id"
  accessName="system.user"
  formProps={{ grid: true, colProps: { span: 12 }, layout: 'vertical' }}
  modalProps={{ width: 800 }}
/>
```

Required props: `api` (REST endpoint), `accessName` (permission prefix), `rowKey` (PK field), `columns`.

Default REST behavior — `GET {api}` for list, `POST {api}` for create, `PUT {api}/{id}` for update, `DELETE {api}/{id}` for delete. Add/edit/delete buttons auto-wrapped in `<AuthButton auth={accessName + '.create'}>`.

Customize with: `handleRequest` (full custom fetch), `requestParams` (transform before send), `handleFinish` (custom submit), `actionBarRender` / `toolBarRender` / `operateRender` (slot overrides).

## Choosing Between Them

- **XinTable**: Full CRUD pages (users, roles, dicts, files)
- **XinForm** (inline/ModalForm): Settings pages with a single form (mail, storage, AI config)
- **XinForm** (ModalForm + trigger): Add/edit without a table (dept management)
- Use `hideInForm` / `hideInTable` / `hideInSearch` to control per-context visibility