<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "Hello, thank you for using XinAdmin. ";
});

// IndexController
Route::controller(App\Http\Controllers\IndexController::class)->prefix('api')->group(function () {
    Route::get('/index', 'index')->middleware(['auth:sanctum', 'authGuard:users', 'abilities:']);
    Route::post('/login', 'login')->middleware(['auth:sanctum', 'authGuard:users', 'abilities:']);
    Route::post('/register', 'register')->middleware(['auth:sanctum', 'authGuard:users', 'abilities:']);
});

// UserController
Route::controller(App\Http\Controllers\UserController::class)->prefix('api/user')->group(function () {
    Route::get('/', 'getUserInfo')->middleware(['auth:sanctum', 'authGuard:users', 'abilities:']);
    Route::post('/logout', 'logout')->middleware(['auth:sanctum', 'authGuard:users', 'abilities:']);
    Route::put('/', 'setUserInfo')->middleware(['auth:sanctum', 'authGuard:users', 'abilities:']);
    Route::post('/setPwd', 'setPassword')->middleware(['auth:sanctum', 'authGuard:users', 'abilities:']);
});

// SysFileController
Route::controller(Modules\FileManage\Http\Controllers\SysFileController::class)->prefix('system/file/list')->group(function () {
    Route::get('/', 'query')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.file.list.query']);
    Route::post('/upload', 'uploadImage')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.file.list.upload']);
    Route::get('/trashed', 'trashed')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.file.list.trashed']);
    Route::delete('/{id}', 'delete')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.file.list.delete']);
    Route::delete('/batch/delete', 'batchDelete')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.file.list.delete']);
    Route::delete('/force-delete/{id}', 'forceDelete')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.file.list.force-delete']);
    Route::delete('/batch/force-delete', 'batchForceDelete')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.file.list.force-delete']);
    Route::post('/restore/{id}', 'restore')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.file.list.restore']);
    Route::post('/batch/restore', 'batchRestore')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.file.list.restore']);
    Route::post('/copy', 'copy')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.file.list.copy']);
    Route::post('/move', 'move')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.file.list.move']);
    Route::put('/rename/{id}', 'rename')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.file.list.rename']);
    Route::get('/download/{id}', 'download');
    Route::delete('/clean/trashed', 'cleanTrashed')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.file.list.clean-trashed']);
});

// SysFileGroupController
Route::controller(Modules\FileManage\Http\Controllers\SysFileGroupController::class)->prefix('system/file/group')->group(function () {
    Route::get('/', 'list')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.file.group.query']);
    Route::post('/', 'create')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.file.group.create']);
    Route::put('/{id}', 'update')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.file.group.update']);
    Route::delete('/{id}', 'delete')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.file.group.delete']);
});

// SysDictController
Route::controller(Modules\SystemTool\Http\Controllers\SysDictController::class)->prefix('system/dict/list')->group(function () {
    Route::get('/', 'query')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.dict.list.query']);
    Route::post('/', 'create')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.dict.list.create']);
    Route::put('/{id}', 'update')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.dict.list.update']);
    Route::delete('/{id}', 'delete')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.dict.list.delete']);
    Route::get('/all', 'all');
});

// SysDictItemController
Route::controller(Modules\SystemTool\Http\Controllers\SysDictItemController::class)->prefix('system/dict/item')->group(function () {
    Route::get('/', 'query')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.dict.item.query']);
    Route::post('/', 'create')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.dict.item.create']);
    Route::put('/{id}', 'update')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.dict.item.update']);
    Route::delete('/{id}', 'delete')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.dict.item.delete']);
});

// SysIndexController
Route::controller(Modules\SystemTool\Http\Controllers\SysIndexController::class)->group(function () {
    Route::get('/index', 'index');
});

// SysMailController
Route::controller(Modules\SystemTool\Http\Controllers\SysMailController::class)->prefix('system/mail')->group(function () {
    Route::get('/config', 'getConfig')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.mail.config']);
    Route::post('/save', 'saveConfig')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.mail.save']);
    Route::post('/test', 'sendTest')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.mail.test']);
});

// SysSettingGroupController
Route::controller(Modules\SystemTool\Http\Controllers\SysSettingGroupController::class)->prefix('system/setting/group')->group(function () {
    Route::get('/', 'query')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.setting.group.query']);
    Route::post('/', 'create')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.setting.group.create']);
    Route::put('/{id}', 'update')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.setting.group.update']);
    Route::delete('/{id}', 'delete')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.setting.group.delete']);
});

// SysSettingItemsController
Route::controller(Modules\SystemTool\Http\Controllers\SysSettingItemsController::class)->prefix('system/setting/items')->group(function () {
    Route::get('/', 'query')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.setting.items.query']);
    Route::post('/', 'create')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.setting.items.create']);
    Route::put('/{id}', 'update')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.setting.items.update']);
    Route::delete('/{id}', 'delete')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.setting.items.delete']);
    Route::put('/save/{id}', 'save')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.setting.items.save']);
    Route::post('/refreshCache', 'refreshCache')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.setting.items.refresh']);
});

// SysStorageController
Route::controller(Modules\SystemTool\Http\Controllers\SysStorageController::class)->prefix('system/storage')->group(function () {
    Route::get('/config', 'getConfig')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.storage.config']);
    Route::post('/save', 'saveConfig')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.storage.save']);
    Route::post('/test', 'testConnection')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.storage.test']);
});

// IndexController
Route::controller(Modules\SystemUser\Http\Controllers\IndexController::class)->prefix('system')->group(function () {
    Route::post('/login', 'login')->middleware(['login_log']);
    Route::post('/logout', 'logout')->middleware(['auth:sanctum', 'authGuard', 'abilities:']);
    Route::get('/info', 'info')->middleware(['auth:sanctum', 'authGuard', 'abilities:']);
    Route::get('/menu', 'menu')->middleware(['auth:sanctum', 'authGuard', 'abilities:']);
    Route::put('/updateInfo', 'updateInfo')->middleware(['auth:sanctum', 'authGuard', 'abilities:']);
    Route::put('/updatePassword', 'updatePassword')->middleware(['auth:sanctum', 'authGuard', 'abilities:']);
    Route::post('/uploadAvatar', 'uploadAvatar')->middleware(['auth:sanctum', 'authGuard', 'abilities:']);
    Route::get('/loginRecord', 'loginRecord')->middleware(['auth:sanctum', 'authGuard', 'abilities:']);
});

// SysDeptController
Route::controller(Modules\SystemUser\Http\Controllers\SysDeptController::class)->prefix('system/dept')->group(function () {
    Route::get('/', 'query')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.dept.query']);
    Route::post('/', 'create')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.dept.create']);
    Route::put('/{id}', 'update')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.dept.update']);
    Route::delete('/', 'delete')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.dept.delete']);
    Route::get('/users/{id}', 'users')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.dept.users']);
});

// SysRoleController
Route::controller(Modules\SystemUser\Http\Controllers\SysRoleController::class)->prefix('system/role')->group(function () {
    Route::get('/', 'query')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.role.query']);
    Route::post('/', 'create')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.role.create']);
    Route::put('/{id}', 'update')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.role.update']);
    Route::delete('/{id}', 'delete')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.role.delete']);
    Route::get('/users/{id}', 'users')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.role.users']);
    Route::put('/status/{id}', 'status')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.role.status']);
    Route::get('/ruleList', 'ruleList')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.role.ruleList']);
    Route::post('/setRule', 'setRule')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.role.setRule']);
});

// SysRuleController
Route::controller(Modules\SystemUser\Http\Controllers\SysRuleController::class)->prefix('system/rule')->group(function () {
    Route::get('/', 'query')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.rule.query']);
    Route::post('/', 'create')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.rule.create']);
    Route::put('/{id}', 'update')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.rule.update']);
    Route::delete('/{id}', 'delete')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.rule.delete']);
    Route::get('/parent', 'getRulesParent')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.rule.parentQuery']);
    Route::put('/show/{id}', 'show')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.rule.show']);
    Route::put('/status/{id}', 'status')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.rule.status']);
});

// SysUserController
Route::controller(Modules\SystemUser\Http\Controllers\SysUserController::class)->prefix('system/user')->group(function () {
    Route::get('/', 'query')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.user.query']);
    Route::post('/', 'create')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.user.create']);
    Route::put('/{id}', 'update')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.user.update']);
    Route::delete('/{id}', 'delete')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.user.delete']);
    Route::put('/resetPassword', 'resetPassword')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.user.resetPassword']);
    Route::get('/role', 'role')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.user.role']);
    Route::get('/dept', 'dept')->middleware(['auth:sanctum', 'authGuard', 'abilities:system.user.dept']);
});
