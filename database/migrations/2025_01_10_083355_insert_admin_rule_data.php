<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /** 仪表盘 */
        $this->insertFirst(1, 0, '仪表盘', 'dashboard', 'menu.dashboard', 'PieChartOutlined', '/dashboard');
        $this->insertMenu(2, 1, 0, '分析页', 'dashboard.analysis', 'menu.dashboard.analysis', 'StockOutlined', '/dashboard/analysis');
        $this->insertMenu(3, 1, 1, '监控页', 'dashboard.monitor', 'menu.dashboard.monitor', 'BarChartOutlined', '/dashboard/monitor');
        $this->insertMenu(4, 1, 2, '工作台', 'dashboard.workplace', 'menu.dashboard.workplace', 'RadarChartOutlined', '/dashboard/workplace');
        /** 示例组件 */
        $this->insertFirst(5, 1, '示例组件', 'data', 'menu.components', 'GoldOutlined', '/data');
        $this->insertMenu(6, 5, 0, '单选卡片', 'data.checkcard', 'menu.components.checkcard', 'CreditCardOutlined', '/data/checkcard');
        $this->insertMenu(7, 5, 1, '定义列表', 'data.descriptions', 'menu.components.descriptions', 'BarsOutlined', '/data/descriptions');
        $this->insertMenu(8, 5, 2, '高级表单', 'data.form', 'menu.components.form', 'BarsOutlined', '/data/form');
        $this->insertMenu(9, 5, 3, '图标选择', 'data.icon', 'menu.components.iconForm', 'SmileOutlined', '/data/icon');
        $this->insertMenu(10, 5, 4, '高级列表', 'data.list', 'menu.components.list', 'ProfileOutlined', '/data/list');
        $this->insertMenu(11, 5, 5, '高级表格', 'data.table', 'menu.components.table', 'ProfileOutlined', '/data/table');
        /** 会员管理 */
        $this->insertFirst(12, 2, '会员管理', 'user', 'menu.user', 'UserOutlined', '/user');
        /** 会员列表 */
        $this->insertMenu(13, 12, 0, '会员列表', 'user.list', 'menu.user.list', 'TeamOutlined', '/user/list');
        $this->insertRule(14, 13, 1, '会员列表查询', 'user.list.list');
        $this->insertRule(15, 13, 2, '会员列表编辑', 'user.list.edit');
        $this->insertRule(16, 13, 3, '会员列表充值', 'user.list.recharge');
        $this->insertRule(17, 13, 4, '会员重置密码', 'user.list.resetPassword');
        /** 余额记录 */
        $this->insertMenu(18, 12, 1, '余额记录', 'user.balance', 'menu.user.balance', 'CreditCardOutlined', '/user/balance');
        $this->insertRule(19, 18, 0, '余额记录查询', 'user.balance.list');
        /** 管理员 */
        $this->insertFirst(20, 3, '管理员', 'admin', 'menu.admin', 'BankOutlined', '/admin');
        /** 用户列表 */
        $this->insertMenu(21, 20, 0, '用户列表', 'admin.list', 'menu.admin.list', 'UserOutlined', '/admin/list');
        $this->insertRule(22, 21, 0, '用户列表查询', 'admin.list.list');
        $this->insertRule(23, 21, 1, '用户列表新增', 'admin.list.add');
        $this->insertRule(24, 21, 2, '用户列表编辑', 'admin.list.edit');
        $this->insertRule(25, 21, 3, '用户列表删除', 'admin.list.delete');
        $this->insertRule(26, 21, 4, '重置用户密码', 'admin.list.resetPassword');
        /** 角色管理 */
        $this->insertMenu(27, 20, 1, '角色管理', 'admin.role', 'menu.admin.role', 'DeploymentUnitOutlined', '/admin/role');
        $this->insertRule(28, 27, 0, '角色管理查询', 'admin.role.list');
        $this->insertRule(29, 27, 1, '角色管理详情', 'admin.role.get');
        $this->insertRule(30, 27, 2, '角色管理新增', 'admin.role.add');
        $this->insertRule(31, 27, 3, '角色管理编辑', 'admin.role.edit');
        $this->insertRule(32, 27, 4, '角色管理删除', 'admin.role.delete');
        /** 部门管理 */
        $this->insertMenu(33, 20, 2, '部门管理', 'admin.dept', 'menu.admin.dept', 'ClusterOutlined', '/admin/dept');
        $this->insertRule(34, 33, 0, '部门管理查询', 'admin.dept.list');
        $this->insertRule(35, 33, 1, '部门管理新增', 'admin.dept.add');
        $this->insertRule(36, 33, 2, '部门管理编辑', 'admin.dept.edit');
        $this->insertRule(37, 33, 3, '部门管理删除', 'admin.dept.delete');
        /** 权限管理 */
        $this->insertMenu(38, 20, 3, '权限管理', 'admin.rule', 'menu.admin.rule', 'DeleteRowOutlined', '/admin/rule');
        $this->insertRule(39, 38, 0, '权限管理查询', 'admin.rule.list');
        $this->insertRule(40, 38, 1, '权限管理新增', 'admin.rule.add');
        $this->insertRule(41, 38, 2, '权限管理编辑', 'admin.rule.edit');
        $this->insertRule(42, 38, 3, '权限管理删除', 'admin.rule.delete');
    }

    private function insertFirst(
        int $ruleId,
        int $sort,
        string $name,
        string $key,
        string $local = '',
        string $icon = '',
        string $path = '',
    ): void {
        DB::table('admin_rule')->insert([
            'rule_id' => $ruleId,
            'parent_id' => 0,
            'type' => '0',
            'sort' => $sort,
            'name' => $name,
            'path' => $path,
            'icon' => $icon,
            'key' => $key,
            'local' => $local,
            'status' => 1,
            'show' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function insertMenu(
        int $ruleId,
        int $parentId,
        int $sort,
        string $name,
        string $key,
        string $local,
        string $icon,
        string $path,
    ): void {
        DB::table('admin_rule')->insert([
            'rule_id' => $ruleId,
            'parent_id' => $parentId,
            'type' => '1',
            'sort' => $sort,
            'name' => $name,
            'path' => $path,
            'icon' => $icon,
            'key' => $key,
            'local' => $local,
            'status' => 1,
            'show' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function insertRule(
        int $ruleId,
        int $parentId,
        int $sort,
        string $name,
        string $key
    ): void {
        DB::table('admin_rule')->insert([
            'rule_id' => $ruleId,
            'parent_id' => $parentId,
            'type' => '2',
            'sort' => $sort,
            'name' => $name,
            'key' => $key,
            'status' => 1,
            'show' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
