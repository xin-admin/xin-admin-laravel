<?php

namespace App\Http\Controllers\Admin;

use App\Attribute\Auth;
use App\Attribute\Monitor;
use App\Enum\FileType;
use App\Models\Admin\AdminModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Xin\File;

class AdminController extends Controller
{
    protected string $authName = 'admin.list';

    protected string $model = AdminModel::class;

    protected array $searchField = [
        'group_id' => '=',
        'created_at' => 'date'
    ];

    protected array $quickSearchField = ['username', 'nickname', 'email', 'mobile', 'id'];

    protected array $rule = [
        'username' => 'required',
        'mobile' => 'required',
        'email' => 'required|email',
        'avatar_id' => 'required|exists:file,file_id',
        'group_id' => 'required|exists:admin_group,id',
        'nickname' => 'required',
        'sex' => 'required',
        'status' => 'required|in:1,0',
    ];

    /**
     * 新增管理员
     */
    #[Auth('add'), Monitor('新增管理员')]
    public function add(): JsonResponse
    {
        if (! class_exists($this->model)) {
            return $this->error('当前控制器未设置模型!');
        }
        $data = request()->all();
        $validator = Validator::make($data, array_merge([
            'password' => 'required|string|min:6|max:20',
            'rePassword' => 'required|same:password',
        ], $this->rule), $this->message);
        if ($validator->fails()) {
            return $this->warn($validator->errors()->first());
        }
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $model = new $this->model;
        $model->create($data);

        return $this->success();
    }

    /**
     * 重置密码
     */
    #[Auth('resetPassword'), Monitor('重置密码')]
    public function resetPassword(Request $request): JsonResponse
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'id' => 'required|exists:admin,id',
            'password' => 'required|string|min:6|max:20',
            'rePassword' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return $this->warn($validator->errors()->first());
        }
        $model = $this->model::query()->find($data['id']);
        $model->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $model->save();
        return $this->success('ok');
    }

    /**
     * 修改密码
     */
    #[Auth('updatePassword'), Monitor('修改管理员密码')]
    public function updatePassword(Request $request): JsonResponse
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'oldPassword' => 'required|string|min:6|max:20',
            'newPassword' => 'required|string|min:6|max:20',
            'rePassword' => 'required|same:newPassword',
        ]);
        if ($validator->fails()) {
            return $this->warn($validator->errors()->first());
        }
        $user_id = Auth::getAdminId();
        $model = $this->model::query()->find($user_id);
        if (!password_verify($data['oldPassword'], $model->password)) {
            return $this->error('旧密码不正确');
        }
        $model->password = password_hash($data['newPassword'], PASSWORD_DEFAULT);
        $model->save();
        return $this->success('ok');
    }

    /**
     * 修改管理员信息
     */
    #[Auth, Monitor('修改管理员信息')]
    public function updateAdmin(Request $request): JsonResponse
    {
        $data = $request->all();
        $rule = [];
        foreach ($this->rule as $k => $v) {
            if (isset($data[$k])) {
                $rule[$k] = $v;
            }
        }
        $validator = Validator::make($data, $rule);
        if ($validator->fails()) {
            return $this->warn($validator->errors()->first());
        }
        try {
            $user_id = Auth::getAdminId();
            $this->model::query()->find($user_id)->update($validator->validated());
            return $this->success();
        } catch (ValidationException $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * 上传头像
     */
    #[Auth]
    public function upAvatar(Request $request): JsonResponse
    {
        // 实例化存储驱动
        $storage = new File();
        $user_id = Auth::getAdminId();
        $type = FileType::IMAGE->value;
        // 执行文件上传
        $fileInfo = $storage->upload($type, 'public', 1, $user_id, 20);
        return $this->success(['fileInfo' => $fileInfo], '图片上传成功');
    }
}
