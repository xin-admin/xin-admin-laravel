<?php

namespace App\Admin\Services;

use App\Common\Exceptions\RepositoryException;
use App\Common\Models\System\SysDictModel;
use App\Common\Services\BaseService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class SysDictService extends BaseService
{
    protected SysDictModel $model;
    protected array $quickSearchField = ['name', 'code', 'describe'];
    protected array $searchField = [
        'status' => '='
    ];

    /**
     * 字典缓存键
     */
    const CACHE_KEY = 'sys:dict:all';
    const CACHE_TTL = 86400; // 24小时

    protected function rules(): array
    {
        if($this->isUpdate()) {
            $id = request()->route('id');
            return [
                'name' => 'required|max:100',
                'code' => [
                    'required',
                    'max:100',
                    Rule::unique('sys_dict', 'code')->ignore($id)
                ],
                'render_type' => 'required|in:text,tag,badge,switch',
                'describe' => 'nullable|max:500',
                'status' => 'required|in:0,1',
                'sort' => 'nullable|integer|min:0',
            ];
        } else {
            return [
                'name' => 'required|max:100',
                'code' => 'required|max:100|unique:sys_dict,code',
                'render_type' => 'required|in:text,tag,badge,switch',
                'describe' => 'nullable|max:500',
                'status' => 'required|in:0,1',
                'sort' => 'nullable|integer|min:0',
            ];
        }
    }

    protected function messages(): array
    {
        return [
            'name.required' => '字典名称不能为空',
            'name.max' => '字典名称不能超过100个字符',
            'code.required' => '字典编码不能为空',
            'code.max' => '字典编码不能超过100个字符',
            'code.unique' => '字典编码已存在',
            'render_type.required' => '渲染类型不能为空',
            'render_type.in' => '渲染类型格式错误',
            'status.required' => '状态不能为空',
            'status.in' => '状态格式错误',
            'sort.integer' => '排序必须为整数',
            'sort.min' => '排序不能小于0',
        ];
    }

    public function delete(int $id): bool
    {
        $model = $this->model::find($id);
        if (!$model) {
            throw new RepositoryException("字典不存在");
        }
        $count = $model->dictItems()->count();
        if ($count > 0) {
            throw new RepositoryException("字典包含子项，请先删除子项！");
        }
        $result = $model->delete();
        $this->clearCache();
        return $result;
    }

    /**
     * 获取所有字典数据（带缓存）
     */
    public function getAllDict(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return SysDictModel::getAllDictWithItems();
        });
    }

    /**
     * 根据字典编码获取字典项（带缓存）
     */
    public function getItemsByCode(string $code): array
    {
        $cacheKey = "sys:dict:code:{$code}";
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($code) {
            return SysDictModel::getItemsByCode($code);
        });
    }

    /**
     * 刷新字典缓存
     */
    public function refreshCache(): bool
    {
        $this->clearCache();
        // 预热缓存
        $this->getAllDict();
        return true;
    }

    /**
     * 清除字典缓存
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        // 清除所有字典编码缓存
        $dicts = SysDictModel::all('code');
        foreach ($dicts as $dict) {
            Cache::forget("sys:dict:code:{$dict->code}");
        }
    }
}
