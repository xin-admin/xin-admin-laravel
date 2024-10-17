<?php
namespace App\Http\Controllers\Admin\File;

use App\Attribute\Auth;
use App\Http\Controllers\Admin\Controller;
use App\Models\File\FileGroupModel;
use App\Models\File\FileModel;
use Illuminate\Http\JsonResponse;

class GroupController extends Controller
{

    protected string $model = FileGroupModel::class;

    protected string $authName = 'file.group';

    /**
     * 文件分组列表
     */
    #[Auth('list')]
    public function list(): JsonResponse
    {
        $list = $this->model::query()->get()->toArray();
        $data = $this->getTreeData($list);
        return $this->success($data);
    }

    /**
     * 编辑文件分组
     */
    #[Auth('edit')]
    public function edit(): JsonResponse
    {
        $data = request()->param();
        // 判断上级分组是否为当前子级
        if ($data['parent_id'] > 0) {
            // 获取所有上级id集
            $parentIds = $this->getTopGroupIds($data['parent_id']);
            if (in_array($data['group_id'], $parentIds)) {
                return $this->error('上级分组不允许设置为当前子分组');
            }
            if ($data['group_id'] == $data['parent_id']) {
                return $this->error('上级分组不允许设置为自己');
            }
        }
        $this->model->update($data);
        return $this->success();
    }

    /**
     * 删除文件分组
     */
    #[Auth('delete')]
    public function delete(): JsonResponse
    {
        $data = request()->param();

        // 判断是否存在下级分组
        if ($this->model->where(['parent_id' => $data['group_id']])->find()) {
            return $this->error('当前分组下存在子分组，不允许删除');
        }
        // 更新该分组下的所有文件
        FileModel::query()->where(['group_id' => $data['group_id']])->update(['group_id' => 0]);
        // 删除分组记录
        $delNum = $this->model->destroy($data['group_id']);
        if ($delNum != 0) {
            return $this->success('删除成功，删除了' . $delNum . '条数据');
        } else {
            return $this->warn('没有删除任何数据');
        }
    }

    /**
     * 获取所有上级id集
     * @param int $groupId
     * @param array|null $list
     * @return array
     */
    private function getTopGroupIds(int $groupId, array $list = null): array
    {
        static $parentIds = [];
        is_null($list) && $list = $this->model->order(['sort', 'create_time'])->select();
        foreach ($list as $item) {
            if ($item['group_id'] == $groupId && $item['parent_id'] > 0) {
                $parentIds[] = $item['parent_id'];
                $this->getTopGroupIds($item['parent_id'], $list);
            }
        }
        return $parentIds;
    }


    /**
     * 获取树状列表
     * @param $list
     * @param int $parentId
     * @return array
     */
    protected function getTreeData(&$list, int $parentId = 0): array
    {
        $data = [];
        foreach ($list as $key => $item) {
            if ($item['parent_id'] == $parentId) {
                $children = $this->getTreeData($list, $item['group_id']);
                !empty($children) && $item['children'] = $children;
                $data[] = $item;
                unset($list[$key]);
            }
        }
        return $data;
    }

}
