<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SysUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $date = date('Y-m-d H:i:s');
        DB::table('sys_user')->insert([
            [
                'id' => 1,
                'username' => 'admin',
                'nickname' => '管理员',
                'email' => Str::random(10).'@example.com',
                'password' => Hash::make('123456'),
                'dept_id' => 1,
                'avatar_id' => 1,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'created_at' => $date,
                'updated_at' => $date,
            ],
            [
                'id' => 2,
                'username' => 'user',
                'nickname' => '财务',
                'email' => Str::random(10).'@example.com',
                'password' => Hash::make('123456'),
                'dept_id' => 2,
                'avatar_id' => 1,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'created_at' => $date,
                'updated_at' => $date,
            ]
        ]);
        DB::table('sys_role')->insert([
            ['id' => 1, 'name' => '超级管理员', 'created_at' => $date, 'updated_at' => $date],
            ['id' => 2, 'name' => '财务', 'created_at' => $date, 'updated_at' => $date],
            ['id' => 3, 'name' => '电商总监', 'created_at' => $date, 'updated_at' => $date],
            ['id' => 4, 'name' => '市场运营', 'created_at' => $date, 'updated_at' => $date],
        ]);
        DB::table('sys_dept')->insert([
            [
                'id' => 1,
                'name' => '新时代股份有限公司',
                'code' => 'A01',
                'type' => 0,
                'parent_id' => 0,
                'sort' => 0,
                'phone' => '19999999999',
                'email' => Str::random(10).'@example.com',
                'address' => '北京市海淀区某某街道103号',
                'remark' => '总公司',
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'id' => 2,
                'name' => '新时代软件技术（洛阳）有限公司',
                'code' => 'A01-B01',
                'type' => 0,
                'parent_id' => 1,
                'sort' => 0,
                'phone' => '19999999999',
                'email' => Str::random(10).'@example.com',
                'address' => '河南省洛阳市龙门区某某街道99号',
                'remark' => '洛阳市分公司',
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'id' => 3,
                'name' => '新时代智能科技（郑州）有限公司',
                'code' => 'A01-B02',
                'type' => 0,
                'parent_id' => 1,
                'sort' => 0,
                'phone' => '19999999999',
                'email' => Str::random(10).'@example.com',
                'address' => '河南省郑州市二七区某某街道69号',
                'remark' => '郑州市分公司',
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'id' => 4,
                'name' => '新征程科技（南阳）有限公司',
                'code' => 'A01-B03',
                'type' => 0,
                'parent_id' => 1,
                'sort' => 2,
                'phone' => '19999999999',
                'email' => Str::random(10).'@example.com',
                'address' => '河南省南阳市卧龙区某某街道77号',
                'remark' => '南阳市分公司',
                'created_at' => $date,
                'updated_at' => $date
            ],
            [
                'id' => 5,
                'name' => '新时代投资发展有限公司',
                'code' => 'B01',
                'type' => 0,
                'parent_id' => 0,
                'sort' => 2,
                'phone' => '19999999999',
                'email' => Str::random(10).'@example.com',
                'address' => '北京市海淀区人民路666号',
                'remark' => '我们坚信，卓越的投资在于发现价值，而卓越的投资管理在于创造价值。我们立志成为科技创业者身边最懂业务、最能赋能、最长情的资本伙伴，共同将创新的火种，转化为引领行业的参天大树。',
                'created_at' => $date,
                'updated_at' => $date
            ],
        ]);

        $rules = [
            [
                'type' => 'menu',
                'name' => '仪表盘',
                'key' => 'dashboard',
                'icon' => 'PieChartOutlined',
                'local' => 'menu.dashboard',
                'children' => [
                    [
                        'type' => 'route',
                        'name' => '分析页',
                        'local' => "menu.analysis",
                        'key' => 'dashboard.analysis',
                        'path' => '/dashboard/analysis',
                    ],
                    [
                        'type' => 'route',
                        'name' => '监控页',
                        'local' => "menu.monitor",
                        'key' => 'dashboard.monitor',
                        'path' => '/dashboard/monitor',
                    ],
                    [
                        'type' => 'route',
                        'name' => '工作台',
                        'local' => "menu.workplace",
                        'key' => 'dashboard.workplace',
                        'path' => '/dashboard/workplace',
                    ]
                ]
            ],
            [
                'type' => 'menu',
                'name' => '结果页面',
                'key' => 'result',
                'icon' => 'CheckCircleOutlined',
                'local' => 'menu.result',
                'children' => [
                    [
                        'type' => 'route',
                        'name' => '成功页',
                        'local' => "menu.result.success",
                        'key' => 'result.success',
                        'path' => '/result/success',
                    ],
                    [
                        'type' => 'route',
                        'name' => '失败页',
                        'local' => "menu.result.fail",
                        'key' => 'result.fail',
                        'path' => '/result/fail',
                    ],
                    [
                        'type' => 'route',
                        'name' => '警告页',
                        'local' => "menu.result.warning",
                        'key' => 'result.warning',
                        'path' => '/result/warning',
                    ],
                    [
                        'type' => 'route',
                        'name' => '信息页',
                        'local' => "menu.result.info",
                        'key' => 'result.info',
                        'path' => '/result/info',
                    ]
                ]
            ],
            [
                'type' => 'menu',
                'name' => '异常页面',
                'key' => 'exception',
                'icon' => 'AlertOutlined',
                'local' => 'menu.exception',
                'children' => [
                    [
                        'type' => 'route',
                        'name' => '403',
                        'local' => "menu.exception.403",
                        'key' => 'exception.403',
                        'path' => '/exception/403',
                    ],
                    [
                        'type' => 'route',
                        'name' => '404',
                        'local' => "menu.exception.404",
                        'key' => 'exception.404',
                        'path' => '/exception/404',
                    ],
                    [
                        'type' => 'route',
                        'name' => '500',
                        'local' => "menu.exception.500",
                        'key' => 'exception.500',
                        'path' => '/exception/500',
                    ]
                ]
            ],
            [
                'type' => 'menu',
                'name' => '多级菜单',
                'key' => 'multi-menu',
                'local' => "menu.multi-menu",
                'icon' => "MenuOutlined",
                'children' => [
                    [
                        'type' => 'route',
                        'name' => '二级页面',
                        'local' => "menu.multi-menu.first",
                        'key' => 'multi-menu.first',
                        'path' => '/multi-menu/first',
                    ],
                    [
                        'type' => 'menu',
                        'name' => '二级菜单',
                        'local' => "menu.multi-menu.two",
                        'key' => 'multi-menu.two',
                        'children' => [
                            [
                                'type' => 'route',
                                'name' => '三级页面',
                                'local' => "menu.multi-menu.two.second",
                                'key' => 'multi-menu.two.second',
                                'path' => '/multi-menu/second',
                            ],
                            [
                                'type' => 'menu',
                                'name' => '三级菜单',
                                'local' => "menu.multi-menu.two.three",
                                'key' => 'multi-menu.two.three',
                                'children' => [
                                    [
                                        'type' => 'route',
                                        'name' => '四级页面',
                                        'local' => "menu.multi-menu.two.three.third",
                                        'key' => 'multi-menu.two.three.third',
                                        'path' => '/multi-menu/third',
                                    ]
                                ]
                            ]
                        ]
                    ],
                ]
            ],
            [
                'type' => 'menu',
                'name' => '页面布局',
                'local' => "menu.page-layout",
                'icon' => "LayoutOutlined",
                'key' => 'page-layout',
                'children' => [
                    [
                        'type' => 'route',
                        'name' => '基础布局',
                        'local' => "menu.page-layout.base-layout",
                        'key' => 'page-layout.base-layout',
                        'path' => '/page-layout/base-layout',
                    ],
                    [
                        'type' => 'route',
                        'name' => '固定头部',
                        'local' => "menu.page-layout.fix-header",
                        'key' => 'page-layout.fix-header',
                        'path' => '/page-layout/fix-header',
                    ],
                    [
                        'type' => 'route',
                        'name' => '页面描述',
                        'local' => "menu.page-layout.descriptions",
                        'key' => 'page-layout.descriptions',
                        'path' => '/page-layout/descriptions',
                    ]
                ]
            ],
            [
                'type' => 'menu',
                'key' => "example",
                'name' => "组件示例",
                'path' => "",
                'icon' => "AppstoreOutlined",
                'local' => "menu.example",
                'hidden' => 1,
                'link' => 0,
                'children' => [
                    [
                        'type' => "route",
                        'key' => "example.user-selector",
                        'name' => "用户选择器",
                        'path' => "/example/user-selector",
                        'local' => "menu.example.user-selector",
                        'hidden' => 1,
                        'link' => 0
                    ],
                    [
                        'type' => "route",
                        'key' => "example.icon-selector",
                        'name' => "图标",
                        'path' => "/example/icon-selector",
                        'local' => "menu.example.icon-selector",
                        'hidden' => 1,
                        'link' => 0
                    ],
                    [
                        'type' => "route",
                        'key' => "example.image-uploader",
                        'name' => "图片上传器",
                        'path' => "/example/image-uploader",
                        'local' => "menu.example.image-uploader",
                        'hidden' => 1,
                        'link' => 0
                    ],
                    [
                        'type' => "route",
                        'key' => "example.xin-form",
                        'name' => "XinForm 表单",
                        'path' => "/example/xin-form",
                        'local' => "menu.example.xin-form",
                        'hidden' => 1,
                        'link' => 0
                    ],
                    [
                        'type' => "route",
                        'key' => "example.xin-table",
                        'name' => "XinTable 表格",
                        'path' => "/example/xin-table",
                        'local' => "menu.example.xin-table",
                        'hidden' => 1,
                        'link' => 0
                    ]
                ]
            ],
            [
                'type' => "menu",
                'name' => "系统管理",
                'local' => "menu.system",
                'icon' => "SettingOutlined",
                'key' => "system",
                'children' => [
                    [
                        'type' => "route",
                        'key' => "system.user",
                        'name' => "用户管理",
                        'path' => "/system/user",
                        'local' => "menu.system.user",
                        'children' => [
                            ['type' => 'rule', 'name' => '查询列表', 'key' => 'system.user.query'],
                            ['type' => 'rule', 'name' => '新增用户', 'key' => 'system.user.create'],
                            ['type' => 'rule', 'name' => '修改用户', 'key' => 'system.user.update'],
                            ['type' => 'rule', 'name' => '删除用户', 'key' => 'system.user.delete'],
                            ['type' => 'rule', 'name' => '重置用户密码', 'key' => 'system.user.resetPassword'],
                            ['type' => 'rule', 'name' => '获取角色选项', 'key' => 'system.user.role'],
                            ['type' => 'rule', 'name' => '获取部门选项', 'key' => 'system.user.dept'],
                        ]
                    ],
                    [
                        'type' => "route",
                        'key' => "system.dept",
                        'name' => "部门管理",
                        'path' => "/system/dept",
                        'local' => "menu.system.dept",
                        'children' => [
                            ['type' => 'rule', 'name' => '获取部门列表', 'key' => 'system.dept.query'],
                            ['type' => 'rule', 'name' => '新建部门', 'key' => 'system.dept.create'],
                            ['type' => 'rule', 'name' => '更新部门信息', 'key' => 'system.dept.update'],
                            ['type' => 'rule', 'name' => '删除部门', 'key' => 'system.dept.delete'],
                            ['type' => 'rule', 'name' => '获取部门用户', 'key' => 'system.dept.users'],
                        ]
                    ],
                    [
                        'type' => "route",
                        'key' => "system.role",
                        'name' => "角色管理",
                        'path' => "/system/role",
                        'local' => "menu.system.role",
                        'children' => [
                            ['type' => 'rule', 'name' => '新增角色', 'key' => 'system.role.create'],
                            ['type' => 'rule', 'name' => '查询角色列表', 'key' => 'system.role.query'],
                            ['type' => 'rule', 'name' => '更新角色信息', 'key' => 'system.role.update'],
                            ['type' => 'rule', 'name' => '删除角色', 'key' => 'system.role.delete'],
                            ['type' => 'rule', 'name' => '设置启用状态', 'key' => 'system.role.status'],
                            ['type' => 'rule', 'name' => '获取角色用户', 'key' => 'system.role.users'],
                            ['type' => 'rule', 'name' => '设置角色权限', 'key' => 'system.role.setRule'],
                            ['type' => 'rule', 'name' => '获取权限选项', 'key' => 'system.role.ruleList'],
                        ]
                    ],
                    [
                        'type' => "route",
                        'key' => "system.rule",
                        'name' => "菜单管理",
                        'path' => "/system/rule",
                        'local' => "menu.system.rule",
                        'children' => [
                            ['type' => 'rule', 'name' => '获取权限列表', 'key' => 'system.rule.query'],
                            ['type' => 'rule', 'name' => '创建权限规则', 'key' => 'system.rule.create'],
                            ['type' => 'rule', 'name' => '更新权限规则', 'key' => 'system.rule.update'],
                            ['type' => 'rule', 'name' => '删除权限规则', 'key' => 'system.rule.delete'],
                            ['type' => 'rule', 'name' => '获取父级权限', 'key' => 'system.rule.parentQuery'],
                            ['type' => 'rule', 'name' => '设置显示状态', 'key' => 'system.rule.show'],
                            ['type' => 'rule', 'name' => '设置启用状态', 'key' => 'system.rule.status'],
                        ]
                    ],
                    [
                        'type' => "route",
                        'name' => "文件管理",
                        'local' => "menu.system.file",
                        'key' => "system.file",
                        'path' => "/system/file",
                        'children' => [
                            ['type' => 'rule', 'name' => '获取文件夹', 'key' => 'system.file.group.query'],
                            ['type' => 'rule', 'name' => '新增文件夹', 'key' => 'system.file.group.create'],
                            ['type' => 'rule', 'name' => '编辑文件夹', 'key' => 'system.file.group.update'],
                            ['type' => 'rule', 'name' => '删除文件夹', 'key' => 'system.file.group.delete'],
                            ['type' => 'rule', 'name' => '查询文件列表', 'key' => 'system.file.list.query'],
                            ['type' => 'rule', 'name' => '上传文件', 'key' => 'system.file.list.upload'],
                            ['type' => 'rule', 'name' => '下载文件', 'key' => 'system.file.list.download'],
                            ['type' => 'rule', 'name' => '删除文件', 'key' => 'system.file.list.delete'],
                            ['type' => 'rule', 'name' => '永久删除文件', 'key' => 'system.file.list.force-delete'],
                            ['type' => 'rule', 'name' => '恢复文件', 'key' => 'system.file.list.restore'],
                            ['type' => 'rule', 'name' => '查看回收站', 'key' => 'system.file.list.trashed'],
                            ['type' => 'rule', 'name' => '清空回收站', 'key' => 'system.file.list.clean-trashed'],
                            ['type' => 'rule', 'name' => '复制文件', 'key' => 'system.file.list.copy'],
                            ['type' => 'rule', 'name' => '移动文件', 'key' => 'system.file.list.move'],
                            ['type' => 'rule', 'name' => '重命名文件', 'key' => 'system.file.list.rename']
                        ],
                    ],
                    [
                        'type' => "route",
                        'name' => "系统字典",
                        'local' => "menu.system.dict",
                        'key' => "system.dict",
                        'path' => "/system/dict",
                        'children' => [
                            ['type' => 'rule', 'name' => '字典列表', 'key' => 'system.dict.list.query'],
                            ['type' => 'rule', 'name' => '新增字典', 'key' => 'system.dict.list.create'],
                            ['type' => 'rule', 'name' => '删除字典', 'key' => 'system.dict.list.delete'],
                            ['type' => 'rule', 'name' => '更新字典', 'key' => 'system.dict.list.update'],
                            ['type' => 'rule', 'name' => '字典项列表', 'key' => 'system.dict.item.query'],
                            ['type' => 'rule', 'name' => '字典项新增', 'key' => 'system.dict.item.create'],
                            ['type' => 'rule', 'name' => '字典项编辑', 'key' => 'system.dict.item.update'],
                            ['type' => 'rule', 'name' => '字典项删除', 'key' => 'system.dict.item.delete'],
                        ]
                    ],
                    [
                        'type' => "route",
                        'name' => "系统配置",
                        'local' => "menu.system.setting",
                        'key' => "system.setting",
                        'path' => "/system/setting",
                        'children' => [
                            ['type' => 'rule', 'name' => '配置列表', 'key' => 'system.setting.items.query'],
                            ['type' => 'rule', 'name' => '新增配置', 'key' => 'system.setting.items.create'],
                            ['type' => 'rule', 'name' => '编辑配置', 'key' => 'system.setting.items.update'],
                            ['type' => 'rule', 'name' => '删除配置', 'key' => 'system.setting.items.delete'],
                            ['type' => 'rule', 'name' => '保存配置', 'key' => 'system.setting.items.save'],
                            ['type' => 'rule', 'name' => '刷新配置', 'key' => 'system.setting.items.refresh'],
                            ['type' => 'rule', 'name' => '配置组编辑', 'key' => 'system.setting.group.update'],
                            ['type' => 'rule', 'name' => '配置组删除', 'key' => 'system.setting.items.item.delete'],
                            ['type' => 'rule', 'name' => '配置组列表', 'key' => 'system.setting.group.query'],
                            ['type' => 'rule', 'name' => '配置组新增', 'key' => 'system.setting.group.create'],
                        ]
                    ],
                    [
                        'type' => 'route',
                        'key' => 'system.mail',
                        'name' => '邮件配置',
                        'path' => '/system/mail',
                        'local' => 'menu.system.mail',
                        'children' => [
                            ['type' => 'rule', 'name' => '获取配置', 'key' => 'system.mail.config'],
                            ['type' => 'rule', 'name' => '保存配置', 'key' => 'system.mail.save'],
                            ['type' => 'rule', 'name' => '发送测试', 'key' => 'system.mail.test'],
                        ]
                    ],
                    [
                        'type' => "route",
                        'name' => "系统信息",
                        'local' => "menu.system.info",
                        'key' => "system.info",
                        'path' => "/system/info",
                    ]
                ]
            ],
            [
                'type' => 'route',
                'name' => 'XinAdmin',
                'local' => "menu.xin-admin",
                'key' => "xin-admin",
                'icon' => "LinkOutlined",
                'link' => 1,
                'path' => 'https://xinadmin.cn',
            ]
        ];

        $this->insertRules($rules);

        DB::table('sys_role_rule')->insertUsing(
            ['role_id', 'rule_id'],
            DB::table('sys_rule')
                ->where('status', 1)
                ->select(DB::raw('1 as role_id'), 'id')
        );

        DB::table('sys_user_role')->insert([
            [
                'user_id' => 1,
                'role_id' => 1,
            ],
            [
                'user_id' => 2,
                'role_id' => 2,
            ]
        ]);
    }


    /**
     * 递归插入权限规则数据
     *
     * @param array $rules 规则数据
     * @param int $pid 父级ID，默认为0（顶级）
     * @return void
     */
    function insertRules(array $rules, int $pid = 0): void
    {
        $order = 0;
        foreach ($rules as $rule) {
            // 准备插入数据
            $insertData = [
                'parent_id' => $pid,
                'type' => $rule['type'],
                'key' => $rule['key'],
                'name' => $rule['name'],
                'path' => $rule['path'] ?? '',
                'icon' => $rule['icon'] ?? '',
                'order' => $order++,
                'local' => $rule['local'] ?? '',
                'status' => 1,
                'hidden' => 1,
                'link' => $rule['link'] ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // 插入数据并获取插入的ID
            $currentId = DB::table('sys_rule')->insertGetId($insertData);

            // 如果有子菜单，递归插入
            if (!empty($rule['children']) && is_array($rule['children'])) {
                $this->insertRules($rule['children'], $currentId);
            }
        }
    }
}
