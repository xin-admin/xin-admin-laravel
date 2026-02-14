# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Xin Admin is an enterprise-grade PHP full-stack rapid development framework built with:
- **Backend**: Laravel 12 + PHP 8.2+
- **Frontend**: React 19 + TypeScript + Ant Design
- **Database**: MySQL 5.7+
- **Cache/Session**: Redis

The project follows a strict frontend/backend separation architecture - the backend serves only JSON APIs, and the frontend is a standalone React SPA.

## Common Commands

### Full Stack Development
- `composer run dev` - Starts Laravel server, queue worker, and Vite dev server concurrently
- `php artisan serve` - Start Laravel development server only
- `php artisan queue:listen --tries=1 --timeout=0` - Start queue worker

### Frontend (React/Vite)
- `npm run dev` - Start Vite dev server for frontend development
- `npm run build` - Build frontend for production (outputs to `public/build/`)
- `npm run lint` - Run ESLint on the `web/` directory
- `npm run preview` - Preview production build

### Backend (Laravel)
- `php artisan migrate` - Run database migrations
- `php artisan db:seed` - Seed the database
- `php artisan test` - Run PHPUnit tests
- `php artisan migrate:fresh --seed` - Fresh migration with seeding
- `php artisan key:generate` - Generate application key

### Dependencies
- `composer install` - Install PHP dependencies
- `npm install` - Install Node.js dependencies

## Architecture

### Directory Structure

```
app/
├── Api/          # Public API controllers and requests (Sanctum auth)
├── Admin/        # Admin panel controllers and requests (role-based auth)
└── Common/       # Shared services, helpers, utilities

web/              # Frontend React application (NOT public/)
├── api/          # Frontend API service layer
├── components/   # Reusable React components
├── domain/       # Business logic
├── hooks/        # Custom React hooks
├── layout/       # Layout components
├── locales/      # i18next translation files
├── pages/        # Page components (route handlers)
├── router/       # React Router configuration
├── stores/       # Zustand state management
├── types/        # TypeScript type definitions
└── utils/        # Utility functions
```

### Key Architectural Patterns

1. **Dual Controller Structure**
   - `app/Api/` - Public endpoints for client applications, authenticated via Laravel Sanctum
   - `app/Admin/` - Admin panel endpoints with role-based permission checks

2. **Path Aliases**
   - TypeScript: `@/*` maps to `./web/*`
   - PHP: `web_path('path/to/file')` helper generates paths to the web directory

3. **State Management**
   - Zustand stores with localStorage persistence
   - User authentication state, menu state, and global app state

4. **Permission System**
   - PHP 8 attributes for API-level permission validation
   - Button-level permissions in the UI
   - Dynamic menus based on user roles
   - Group permissions with inherit disable support

5. **Routing**
   - Frontend: React Router with file-based routing configuration in `web/router/`
   - Backend: Laravel routes in `routes/api.php` and `routes/admin.php`

6. **Internationalization**
   - i18next for frontend translations (files in `web/locales/`)
   - Laravel's localization for backend

## Important Helper Functions

- `setting('key')` - Retrieve system configuration values from the database
- `web_path('path')` - Generate absolute paths to files in the web directory

## Code Style

- PHP: 4 spaces indentation
- TypeScript/JavaScript: 2 spaces indentation
- ESLint configured for the `web/` directory
- TypeScript strict mode enabled

## Frontend Build Notes

- Frontend source code is in `web/` directory (NOT `public/`)
- Vite entry point: `web/main.tsx`
- Build output: `public/build/` by default
- The app serves as a SPA from `index.html`