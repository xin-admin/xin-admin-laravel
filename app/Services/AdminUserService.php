<?php

namespace App\Services;

use App\Models\AdminRuleModel;
use App\Models\AdminUserModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminUserService extends \App\Services\BaseService
{
    /** 获取管理员信息 */
    public function getAdminInfo(): JsonResponse
    {
        $info = auth()->user();
        // 权限
        $access = $info['rules'];
        // 菜单
        $menus = AdminRuleModel::where('show', '=', 1)
            ->where('status', '=', 1)
            ->whereIn('key', $access)
            ->whereIn('type', [0, 1])
            ->orderBy('sort', 'asc')
            ->get()
            ->toArray();
        $menus = $this->getTreeData($menus, 'rule_id');

        return $this->success(compact('info', 'menus', 'access'));
    }

    /**
     * 修改密码
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'oldPassword' => 'required|string|min:6|max:20',
            'newPassword' => 'required|string|min:6|max:20',
            'rePassword' => 'required|same:newPassword',
        ]);

        $user_id = auth()->id();
        $user = AdminUserModel::find($user_id);
        if (! password_verify($validated['oldPassword'], $user->password)) {
            return $this->error(__('user.old_password_error'));
        }
        $user->password = $validated['newPassword'];
        $user->save();
        return $this->success('ok');
    }

    /**
     * 修改管理员信息
     */
    public function updateAdmin(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nickname' => 'required',
            'mobile' => 'required',
            'email' => 'required|email',
            'avatar_id' => 'required|exists:file,file_id',
        ]);
        $user_id = auth()->id();
        AdminUserModel::where('user_id', $user_id)->update($validated);

        return $this->success();
    }
}
