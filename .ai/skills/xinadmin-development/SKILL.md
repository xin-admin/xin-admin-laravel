---
name: xinadmin-development
description: "TRIGGER when building a new CRUD module or feature in XinAdmin. Covers the full-stack development flow: database migrations, backend controller/model/form-request with AnnoRoute attribute routing, XinForm/XinTable frontend pages, and menu rules and permissions in seeder. Also activate when the user references AnnoRoute attribute routing, #[GetRoute]/#[PostRoute]/#[PutRoute]/#[DeleteRoute] attributes, XinAdmin controller patterns, or XinAdmin CRUD page development."
license: MIT
---

# XinAdmin Development

Best practices for building features in XinAdmin, organized by topic. Each rule teaches what to do and why.

## Consistency First

Before applying any rule, check what the application already does. XinAdmin has established patterns — the best choice is the one the codebase already uses, even if another pattern would be theoretically better.

Check sibling controllers, related pages, or existing seed data for established patterns. If one exists, follow it — don't introduce a second way. These rules are defaults for when no pattern exists yet, not overrides.

## Quick Reference

### 1. CRUD Development Workflow → `rules/crud-workflow.md`

End-to-end flow for building a new feature in four phases:

- **Phase 1:** Database migration — `php artisan make:migration`
- **Phase 2:** Backend — Controller with AnnoRoute attributes, Eloquent model, FormRequest
- **Phase 3:** Frontend — Domain types, API wrappers, i18n, XinTable page component
- **Phase 4:** Menu routes, permission rules in `SysUserSeeder`, menu translations

### 2. AnnoRoute Attribute Routing → `rules/annoroute.md`

- `#[RequestAttribute]` on the controller class sets shared prefix and permission prefix
- `#[GetRoute]` / `#[PostRoute]` / `#[PutRoute]` / `#[DeleteRoute]` on methods declare HTTP routes
- `authorize` parameter controls access: `'query'` → ability `prefix.query`, `false` → public route
- `where` parameter for route parameter regex constraints
- Route registration via `AnnoRoute->register(path)` in ServiceProvider `boot()`
- Controller MUST have `#[RequestAttribute]` to be discovered
- Inherit `BaseController` for `success()` / `error()` response helpers

### 3. i18n Locale Conventions → `rules/i18n-conventions.md`

- Locale files mirror page paths: `pages/system/user.tsx` → `locales/zh_CN/system/user.ts`, prefix `system.user`
- `index.tsx` pages drop `/index`: `pages/ai/chat/index.tsx` → `locales/zh_CN/ai/chat.ts`, prefix `ai.chat`
- Shared component translations go in `components/` directory: `xin.form.*`, `xin.table.*`, `xin.crud.*`
- Layout translations go in `layout/` directory: `layout.*`
- Standalone files (`menu.ts`, `login.ts`) stay at locale root
- All keys dot-separated, double quotes, 2-space indent

## How to Apply

Always use a sub-agent to read rule files and explore this skill's content.

1. Identify what you're building (new CRUD → workflow + annoroute; routing only → annoroute)
2. Check sibling files for existing patterns — follow those first per Consistency First
3. Work through the phases in order — each builds on the last