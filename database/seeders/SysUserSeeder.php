<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use function Laravel\Prompts\table;

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
                'order' => 0,
                'type' => 'menu',
                'name' => '仪表盘',
                'key' => 'dashboard',
                'icon' => 'PieChartOutlined',
                'local' => 'menu.dashboard',
                'children' => [
                    [
                        'order' => 0,
                        'type' => 'route',
                        'name' => '分析页',
                        'local' => "menu.analysis",
                        'icon' => "PieChartOutlined",
                        'key' => 'dashboard.analysis',
                        'path' => '/dashboard/analysis',
                        'elementPath' => '/dashboard/analysis',
                    ],
                    [
                        'order' => 1,
                        'type' => 'route',
                        'name' => '监控页',
                        'local' => "menu.monitor",
                        'icon' => "BarChartOutlined",
                        'key' => 'dashboard.monitor',
                        'path' => '/dashboard/monitor',
                        'elementPath' => '/dashboard/monitor',
                    ],
                    [
                        'order' => 1,
                        'type' => 'route',
                        'name' => '工作台',
                        'local' => "menu.workplace",
                        'icon' => "LineChartOutlined",
                        'key' => 'dashboard.workplace',
                        'path' => '/dashboard/workplace',
                        'elementPath' => '/dashboard/workplace',
                    ]
                ]
            ],
            [
                'order' => 1,
                'type' => 'menu',
                'name' => '结果页面',
                'key' => 'result',
                'icon' => 'CheckCircleOutlined',
                'local' => 'menu.result',
                'children' => [
                    [
                        'order' => 0,
                        'type' => 'route',
                        'name' => '成功页',
                        'local' => "menu.result.success",
                        'icon' => "CheckCircleOutlined",
                        'key' => 'result.success',
                        'path' => '/result/success',
                        'elementPath' => '/result/success',
                    ],
                    [
                        'order' => 1,
                        'type' => 'route',
                        'name' => '失败页',
                        'local' => "menu.result.fail",
                        'icon' => "CloseCircleOutlined",
                        'key' => 'result.fail',
                        'path' => '/result/fail',
                        'elementPath' => '/result/fail',
                    ],
                    [
                        'order' => 2,
                        'type' => 'route',
                        'name' => '警告页',
                        'local' => "menu.result.warning",
                        'icon' => "WarningOutlined",
                        'key' => 'result.warning',
                        'path' => '/result/warning',
                        'elementPath' => '/result/warning',
                    ],
                    [
                        'order' => 3,
                        'type' => 'route',
                        'name' => '信息页',
                        'local' => "menu.result.info",
                        'icon' => "ExclamationCircleOutlined",
                        'key' => 'result.info',
                        'path' => '/result/info',
                        'elementPath' => '/result/info',
                    ]
                ]
            ],
            [
                'order' => 2,
                'type' => 'menu',
                'name' => '异常页面',
                'key' => 'exception',
                'icon' => 'AlertOutlined',
                'local' => 'menu.exception',
                'children' => [
                    [
                        'order' => 0,
                        'type' => 'route',
                        'name' => '403',
                        'local' => "menu.exception.403",
                        'icon' => "AppstoreOutlined",
                        'key' => 'exception.403',
                        'path' => '/exception/403',
                        'elementPath' => '/exception/403',
                    ],
                    [
                        'order' => 1,
                        'type' => 'route',
                        'name' => '404',
                        'local' => "menu.exception.404",
                        'icon' => "AppstoreOutlined",
                        'key' => 'exception.404',
                        'path' => '/exception/404',
                        'elementPath' => '/exception/404',
                    ],
                    [
                        'order' => 2,
                        'type' => 'route',
                        'name' => '500',
                        'local' => "menu.exception.500",
                        'icon' => "AppstoreOutlined",
                        'key' => 'exception.500',
                        'path' => '/exception/500',
                        'elementPath' => '/exception/500',
                    ]
                ]
            ],
            [
                'order' => 3,
                'type' => 'menu',
                'name' => '权限管理',
                'key' => 'auth',
                'local' => "menu.auth",
                'icon' => "SafetyCertificateOutlined",
                'children' => [
                    [
                        'order' => 0,
                        'type' => 'route',
                        'name' => '页面权限',
                        'local' => "menu.auth.page",
                        'icon' => "AppstoreOutlined",
                        'key' => 'auth.page',
                        'path' => '/auth/page',
                        'elementPath' => '/auth/page',
                    ],
                    [
                        'order' => 1,
                        'type' => 'route',
                        'name' => '按钮权限',
                        'local' => "menu.auth.button",
                        'icon' => "AppstoreOutlined",
                        'key' => 'auth.button',
                        'path' => '/auth/button',
                        'elementPath' => '/auth/button',
                        'children' => [
                            [
                                'order' => 0,
                                'type' => 'rule',
                                'name' => '新增权限',
                                'key' => 'auth.button.create'
                            ],
                            [
                                'order' => 1,
                                'type' => 'rule',
                                'name' => '编辑权限',
                                'key' => 'auth.button.update'
                            ],
                            [
                                'order' => 2,
                                'type' => 'rule',
                                'name' => '删除权限',
                                'key' => 'auth.button.delete'
                            ],
                            [
                                'order' => 3,
                                'type' => 'rule',
                                'name' => '查询权限',
                                'key' => 'auth.button.query'
                            ]
                        ]
                    ],
                ]
            ],
            [
                'order' => 4,
                'type' => 'menu',
                'name' => '多级菜单',
                'key' => 'multi-menu',
                'local' => "menu.multi-menu",
                'icon' => "MenuOutlined",
                'children' => [
                    [
                        'order' => 0,
                        'type' => 'route',
                        'name' => '二级页面',
                        'local' => "menu.multi-menu.first",
                        'icon' => "MenuOutlined",
                        'key' => 'multi-menu.first',
                        'path' => '/multi-menu/first',
                        'elementPath' => '/multi-menu/first',
                    ],
                    [
                        'order' => 1,
                        'type' => 'menu',
                        'name' => '二级菜单',
                        'local' => "menu.multi-menu.two",
                        'icon' => "MenuOutlined",
                        'key' => 'multi-menu.two',
                        'children' => [
                            [
                                'order' => 0,
                                'type' => 'route',
                                'name' => '三级页面',
                                'local' => "menu.multi-menu.two.second",
                                'icon' => "MenuOutlined",
                                'key' => 'multi-menu.two.second',
                                'path' => '/multi-menu/two/second',
                                'elementPath' => '/multi-menu/second',
                            ],
                            [
                                'order' => 1,
                                'type' => 'menu',
                                'name' => '三级菜单',
                                'local' => "menu.multi-menu.two.three",
                                'icon' => "MenuOutlined",
                                'key' => 'multi-menu.two.three',
                                'children' => [
                                    [
                                        'order' => 0,
                                        'type' => 'route',
                                        'name' => '四级页面',
                                        'local' => "menu.multi-menu.two.three.third",
                                        'icon' => "MenuOutlined",
                                        'key' => 'multi-menu.two.three.third',
                                        'path' => '/multi-menu/two/three/third',
                                        'elementPath' => '/multi-menu/third',
                                    ]
                                ]
                            ]
                        ]
                    ],
                ]
            ],
            [
                'order' => 5,
                'type' => 'menu',
                'name' => '页面布局',
                'local' => "menu.page-layout",
                'icon' => "LayoutOutlined",
                'key' => 'page-layout',
                'children' => [
                    [
                        'order' => 0,
                        'type' => 'route',
                        'name' => '基础布局',
                        'local' => "menu.page-layout.base-layout",
                        'icon' => "AppstoreOutlined",
                        'key' => 'page-layout.base-layout',
                        'path' => '/page-layout/base-layout',
                        'elementPath' => '/page-layout/base-layout',
                    ],
                    [
                        'order' => 1,
                        'type' => 'route',
                        'name' => '固定头部',
                        'local' => "menu.page-layout.fix-header",
                        'icon' => "AppstoreOutlined",
                        'key' => 'page-layout.fix-header',
                        'path' => '/page-layout/fix-header',
                        'elementPath' => '/page-layout/fix-header',
                    ],
                    [
                        'order' => 2,
                        'type' => 'route',
                        'name' => '页面描述',
                        'local' => "menu.page-layout.descriptions",
                        'icon' => "AppstoreOutlined",
                        'key' => 'page-layout.descriptions',
                        'path' => '/page-layout/descriptions',
                        'elementPath' => '/page-layout/descriptions',
                    ]
                ]
            ],
            [
                'type' => 'menu',
                'key' => "example",
                'name' => "组件示例",
                'path' => "",
                'icon' => "AppstoreOutlined",
                'elementPath' => "",
                'order' => 5,
                'local' => "menu.example",
                'hidden' => 1,
                'link' => 0,
                'children' => [
                    [
                        'type' => "route",
                        'key' => "example.user-selector",
                        'name' => "用户选择器",
                        'path' => "/example/user-selector",
                        'icon' => "AppstoreOutlined",
                        'elementPath' => "/example/user-selector",
                        'order' => 0,
                        'local' => "menu.example.user-selector",
                        'hidden' => 1,
                        'link' => 0
                    ],
                    [
                        'type' => "route",
                        'key' => "example.icon-selector",
                        'name' => "图标",
                        'path' => "/example/icon-selector",
                        'icon' => "AppstoreOutlined",
                        'elementPath' => "/example/icon-selector",
                        'order' => 1,
                        'local' => "menu.example.icon-selector",
                        'hidden' => 1,
                        'link' => 0
                    ],
                    [
                        'type' => "route",
                        'key' => "example.image-uploader",
                        'name' => "图片上传器",
                        'path' => "/example/image-uploader",
                        'icon' => "PictureOutlined",
                        'elementPath' => "/example/image-uploader",
                        'order' => 2,
                        'local' => "menu.example.image-uploader",
                        'hidden' => 1,
                        'link' => 0 
                    ]
                ]
            ],
            [
                'order' => 7,
                'type' => 'route',
                'name' => '用户设置',
                'local' => "menu.user.setting",
                'icon' => "UserSwitchOutlined",
                'key' => 'user.setting',
                'path' => '/user/setting',
                'elementPath' => '/user/setting/index',
                'children' => [
                    [
                        'order' => 0,
                        'type' => 'nested-route',
                        'name' => '基本信息',
                        'key' => 'user.setting.info',
                        'path' => '/user/setting',
                        'elementPath' => '/user/setting/info',
                    ],
                    [
                        'order' => 1,
                        'type' => 'nested-route',
                        'name' => '安全设置',
                        'key' => 'user.setting.security',
                        'path' => '/user/setting/security',
                        'elementPath' => '/user/setting/security',
                    ],
                    [
                        'order' => 2,
                        'type' => 'nested-route',
                        'name' => '实名认证',
                        'key' => 'user.setting.verification',
                        'path' => '/user/setting/verification',
                        'elementPath' => '/user/setting/verification',
                    ],
                    [
                        'order' => 3,
                        'type' => 'nested-route',
                        'name' => '登录日志',
                        'key' => 'user.setting.loginlog',
                        'path' => '/user/setting/loginlog',
                        'elementPath' => '/user/setting/loginlog',
                    ]
                ]
            ],
            [
                'order' => 8,
                'type' => 'menu',
                'name' => '系统用户',
                'local' => "menu.sys-user",
                'icon' => "UserOutlined",
                'key' => 'sys-user',
                'children' => [
                    [
                        'type' => "route",
                        'key' => "sys-user.list",
                        'name' => "用户列表",
                        'path' => "/sys-user/list",
                        'icon' => "",
                        'elementPath' => "/sys-user/list",
                        'order' => 1,
                        'local' => "menu.sys-user.list",
                        'children' => [
                            [
                                'order' => 0,
                                'type' => 'rule',
                                'name' => '查询列表',
                                'key' => 'sys-user.list.query'
                            ],
                            [
                                'order' => 1,
                                'type' => 'rule',
                                'name' => '新增用户',
                                'key' => 'sys-user.list.create'
                            ],
                            [
                                'order' => 2,
                                'type' => 'rule',
                                'name' => '修改用户',
                                'key' => 'sys-user.list.update'
                            ],
                            [
                                'order' => 3,
                                'type' => 'rule',
                                'name' => '删除用户',
                                'key' => 'sys-user.list.delete'
                            ],
                            [
                                'order' => 4,
                                'type' => 'rule',
                                'name' => '重置用户密码',
                                'key' => 'sys-user.list.resetPassword'
                            ],
                            [
                                'order' => 5,
                                'type' => 'rule',
                                'name' => '修改用户状态',
                                'key' => 'sys-user.list.resetStatus'
                            ],
                            [
                                'order' => 6,
                                'type' => 'rule',
                                'name' => '获取角色选项',
                                'key' => 'sys-user.list.getRole'
                            ],
                            [
                                'order' => 7,
                                'type' => 'rule',
                                'name' => '获取部门选项',
                                'key' => 'sys-user.list.getDept'
                            ],
                        ]
                    ],
                    [
                        'type' => "route",
                        'key' => "sys-user.dept",
                        'name' => "用户部门",
                        'path' => "/sys-user/dept",
                        'icon' => "",
                        'elementPath' => "/sys-user/dept",
                        'order' => 1,
                        'local' => "menu.sys-user.dept",
                        'children' => [
                            [
                                'order' => 0,
                                'type' => 'rule',
                                'name' => '获取部门列表',
                                'key' => 'sys-user.dept.query'
                            ],
                            [
                                'order' => 1,
                                'type' => 'rule',
                                'name' => '新建部门',
                                'key' => 'sys-user.dept.create'
                            ],
                            [
                                'order' => 2,
                                'type' => 'rule',
                                'name' => '更新部门信息',
                                'key' => 'sys-user.dept.update'
                            ],
                            [
                                'order' => 3,
                                'type' => 'rule',
                                'name' => '删除部门',
                                'key' => 'sys-user.dept.delete'
                            ],
                            [
                                'order' => 3,
                                'type' => 'rule',
                                'name' => '获取部门用户',
                                'key' => 'sys-user.dept.users'
                            ],
                        ]
                    ],
                    [
                        'type' => "route",
                        'key' => "sys-user.role",
                        'name' => "用户角色",
                        'path' => "/sys-user/role",
                        'icon' => "",
                        'elementPath' => "/sys-user/role",
                        'order' => 1,
                        'local' => "menu.sys-user.role",
                        'children' => [
                            [
                                'order' => 0,
                                'type' => 'rule',
                                'name' => '新增角色',
                                'key' => 'sys-user.role.create'
                            ],
                            [
                                'order' => 1,
                                'type' => 'rule',
                                'name' => '查询角色列表',
                                'key' => 'sys-user.role.query'
                            ],
                            [
                                'order' => 2,
                                'type' => 'rule',
                                'name' => '更新角色信息',
                                'key' => 'sys-user.role.update'
                            ],
                            [
                                'order' => 3,
                                'type' => 'rule',
                                'name' => '删除角色',
                                'key' => 'sys-user.role.delete'
                            ],
                            [
                                'order' => 4,
                                'type' => 'rule',
                                'name' => '设置角色权限',
                                'key' => 'sys-user.role.rule'
                            ],
                            [
                                'order' => 5,
                                'type' => 'rule',
                                'name' => '获取权限选项',
                                'key' => 'sys-user.role.rule.list'
                            ],
                            [
                                'order' => 6,
                                'type' => 'rule',
                                'name' => '设置启用状态',
                                'key' => 'sys-user.role.rule.status'
                            ],
                            [
                                'order' => 7,
                                'type' => 'rule',
                                'name' => '获取角色用户',
                                'key' => 'sys-user.role.rule.users'
                            ],
                        ]
                    ],
                    [
                        'type' => "route",
                        'key' => "sys-user.rule",
                        'name' => "用户权限",
                        'path' => "/sys-user/rule",
                        'icon' => "",
                        'elementPath' => "/sys-user/rule",
                        'order' => 1,
                        'local' => "menu.sys-user.rule",
                        'children' => [
                            [
                                'order' => 0,
                                'type' => 'rule',
                                'name' => '获取权限列表',
                                'key' => 'sys-user.rule.query'
                            ],
                            [
                                'order' => 1,
                                'type' => 'rule',
                                'name' => '创建权限规则',
                                'key' => 'sys-user.rule.create'
                            ],
                            [
                                'order' => 2,
                                'type' => 'rule',
                                'name' => '更新权限规则',
                                'key' => 'sys-user.rule.update'
                            ],
                            [
                                'order' => 3,
                                'type' => 'rule',
                                'name' => '删除权限规则',
                                'key' => 'sys-user.rule.delete'
                            ],
                            [
                                'order' => 4,
                                'type' => 'rule',
                                'name' => '获取父级权限',
                                'key' => 'sys-user.rule.parentQuery'
                            ],
                            [
                                'order' => 5,
                                'type' => 'rule',
                                'name' => '设置显示状态',
                                'key' => 'sys-user.rule.show'
                            ],
                            [
                                'order' => 6,
                                'type' => 'rule',
                                'name' => '设置启用状态',
                                'key' => 'sys-user.rule.status'
                            ],
                        ]
                    ],
                ]
            ],
            [
                'order' => 9,
                'type' => "menu",
                'name' => "系统设置",
                'local' => "menu.system",
                'icon' => "SettingOutlined",
                'key' => "system",
                'children' => [
                    [
                        'order' => 0,
                        'type' => "route",
                        'name' => "系统信息",
                        'local' => "menu.system.info",
                        'key' => "system.info",
                        'path' => "/system/info",
                        'elementPath' => "/system/info",
                    ],
                    [
                        'order' => 2,
                        'type' => "route",
                        'name' => "文件管理",
                        'local' => "menu.system.file",
                        'key' => "system.file",
                        'path' => "/system/file",
                        'elementPath' => "/system/file",
                        'children' => [
                            [
                                'order' => 1,
                                'type' => 'rule',
                                'name' => '获取文件夹',
                                'key' => 'system.file.group.query'
                            ],
                            [
                                'order' => 2,
                                'type' => 'rule',
                                'name' => '新增文件夹',
                                'key' => 'system.file.group.create'
                            ],
                            [
                                'order' => 3,
                                'type' => 'rule',
                                'name' => '编辑文件夹',
                                'key' => 'system.file.group.update'
                            ],
                            [
                                'order' => 4,
                                'type' => 'rule',
                                'name' => '删除文件夹',
                                'key' => 'system.file.group.delete'
                            ],
                            [
                                'order' => 5,
                                'type' => 'rule',
                                'name' => '查询文件列表',
                                'key' => 'system.file.list.query'
                            ],
                            [
                                'order' => 6,
                                'type' => 'rule',
                                'name' => '删除文件',
                                'key' => 'system.file.list.delete'
                            ],
                            [
                                'order' => 7,
                                'type' => 'rule',
                                'name' => '永久删除文件',
                                'key' => 'system.file.list.force-delete'
                            ],
                            [
                                'order' => 8,
                                'type' => 'rule',
                                'name' => '上传文件',
                                'key' => 'system.file.list.upload'
                            ],
                            [
                                'order' => 9,
                                'type' => 'rule',
                                'name' => '下载文件',
                                'key' => 'system.file.list.download'
                            ]
                        ],
                    ],
                    [
                        'order' => 3,
                        'type' => "route",
                        'name' => "系统字典",
                        'local' => "menu.system.dict",
                        'key' => "system.dict",
                        'path' => "/system/dict",
                        'elementPath' => "/system/dict",
                        'children' => [
                            [
                                'order' => 1,
                                'type' => 'rule',
                                'name' => '字典列表',
                                'key' => 'system.dict.list.query'
                            ],
                            [
                                'order' => 2,
                                'type' => 'rule',
                                'name' => '新增字典',
                                'key' => 'system.dict.list.create'
                            ],
                            [
                                'order' => 3,
                                'type' => 'rule',
                                'name' => '删除字典',
                                'key' => 'system.dict.list.delete'
                            ],
                            [
                                'order' => 4,
                                'type' => 'rule',
                                'name' => '更新字典',
                                'key' => 'system.dict.list.update'
                            ],
                            [
                                'order' => 5,
                                'type' => 'rule',
                                'name' => '字典项列表',
                                'key' => 'system.dict.item.query'
                            ],
                            [
                                'order' => 6,
                                'type' => 'rule',
                                'name' => '字典项新增',
                                'key' => 'system.dict.item.create'
                            ],
                            [
                                'order' => 7,
                                'type' => 'rule',
                                'name' => '字典项编辑',
                                'key' => 'system.dict.item.update'
                            ],
                            [
                                'order' => 8,
                                'type' => 'rule',
                                'name' => '字典项删除',
                                'key' => 'system.dict.item.delete'
                            ]
                        ]
                    ],
                    [
                        'order' => 4,
                        'type' => "menu",
                        'name' => "系统日志",
                        'local' => "menu.system.watcher",
                        'key' => "system.watcher",
                        'children' => [
                            [
                                'order' => 0,
                                'type' => 'route',
                                'name' => '请求记录',
                                'local' => "menu.system.watcher.request",
                                'key' => "system.watcher.request",
                                'elementPath' => "/system/watcher/request",
                                'path' => "/system/watcher/request",
                            ],
                            [
                                'order' => 1,
                                'type' => 'route',
                                'name' => 'SQL记录',
                                'local' => "menu.system.watcher.query",
                                'key' => "system.watcher.query",
                                'elementPath' => "/system/watcher/query",
                                'path' => "/system/watcher/query",
                            ],
                            [
                                'order' => 2,
                                'type' => 'route',
                                'name' => '缓存记录',
                                'local' => "menu.system.watcher.cache",
                                'key' => "system.watcher.cache",
                                'elementPath' => "/system/watcher/cache",
                                'path' => "/system/watcher/cache",
                            ],
                            [
                                'order' => 3,
                                'type' => 'route',
                                'name' => 'Redis记录',
                                'local' => "menu.system.watcher.redis",
                                'key' => "system.watcher.redis",
                                'elementPath' => "/system/watcher/redis",
                                'path' => "/system/watcher/redis",
                            ],
                        ]
                    ]
                ]
            ],
            [
                'order' => 10,
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
        foreach ($rules as $rule) {
            // 准备插入数据
            $insertData = [
                'parent_id' => $pid,
                'type' => $rule['type'],
                'key' => $rule['key'],
                'name' => $rule['name'],
                'path' => $rule['path'] ?? '',
                'icon' => $rule['icon'] ?? '',
                'elementPath' => $rule['elementPath'] ?? '',
                'order' => $rule['order'] ?? 0,
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
