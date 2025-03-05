SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Records of admin_dept
-- ----------------------------
INSERT INTO `admin_dept` VALUES (1, 0, '小刘快跑网络科技有限公司', 0, '小刘', '19999999999', '230@qq.com', 1, '2024-12-31 14:16:23', '2024-12-31 14:16:25');
INSERT INTO `admin_dept` VALUES (2, 1, '小刘洛阳分公司', 0, '小刘', '19999999999', '230@qq.com', 1, '2024-12-31 07:15:41', '2024-12-31 07:15:41');
INSERT INTO `admin_dept` VALUES (3, 1, '小刘郑州分公司', 1, '小张', '19966666666', '230@qq.com', 1, '2024-12-31 07:16:20', '2024-12-31 07:16:20');
INSERT INTO `admin_dept` VALUES (4, 1, '小刘南阳分公司', 1, '小李', '19999999999', '230@qq.com', 1, '2024-12-31 07:58:10', '2024-12-31 07:58:10');
INSERT INTO `admin_dept` VALUES (5, 3, '郑州技术部', 1, '张三', '1999999999', '199@qq.com', 1, '2024-12-31 07:59:05', '2024-12-31 07:59:05');

-- ----------------------------
-- Records of admin_role
-- ----------------------------
INSERT INTO `admin_role` VALUES (1, '超级管理员', 0, '*', '超级管理', '2024-12-14 10:07:33', '2024-12-14 10:07:36');
INSERT INTO `admin_role` VALUES (2, '管理员', 1, '20,33,35,37,36,34,21,23,25,24,22,26,27,30,32,31,29,28,38,40,42,41,39,1,2,3,4,5,6,7,8,9,10,11,12,18,19,13,15,14,16,17', '系统管理员', '2024-12-14 10:08:08', '2025-01-13 02:32:55');
INSERT INTO `admin_role` VALUES (3, '财务', 2, '1,2,3,4', '负责管理账单', '2025-01-03 07:58:07', '2025-01-11 00:52:19');
INSERT INTO `admin_role` VALUES (6, '电商总监', 4, '1,2,3,4', '负责电商模块任务', '2025-01-04 06:25:14', '2025-01-11 00:52:25');
INSERT INTO `admin_role` VALUES (7, '市场运营', 5, '5,6,7,8,9,10,11', '负责市场推广业务', '2025-01-04 06:32:27', '2025-01-13 08:07:29');

-- ----------------------------
-- Records of admin_rule
-- ----------------------------
INSERT INTO `admin_rule` VALUES (1, 0, '0', 0, '仪表盘', '/dashboard', 'PieChartOutlined', 'dashboard', 'menu.dashboard', 1, 1, '2025-02-07 09:13:05', '2025-02-07 09:13:05');
INSERT INTO `admin_rule` VALUES (2, 1, '1', 0, '分析页', '/dashboard/analysis', 'StockOutlined', 'dashboard.analysis', 'menu.dashboard.analysis', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (3, 1, '1', 1, '监控页', '/dashboard/monitor', 'BarChartOutlined', 'dashboard.monitor', 'menu.dashboard.monitor', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (4, 1, '1', 2, '工作台', '/dashboard/workplace', 'RadarChartOutlined', 'dashboard.workplace', 'menu.dashboard.workplace', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (5, 0, '0', 1, '示例组件', '/data', 'GoldOutlined', 'data', 'menu.components', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (6, 5, '1', 0, '单选卡片', '/data/checkcard', 'CreditCardOutlined', 'data.checkcard', 'menu.components.checkcard', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (7, 5, '1', 1, '定义列表', '/data/descriptions', 'BarsOutlined', 'data.descriptions', 'menu.components.descriptions', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (8, 5, '1', 2, '高级表单', '/data/form', 'BarsOutlined', 'data.form', 'menu.components.form', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (9, 5, '1', 3, '图标选择', '/data/icon', 'SmileOutlined', 'data.icon', 'menu.components.iconForm', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (10, 5, '1', 4, '高级列表', '/data/list', 'ProfileOutlined', 'data.list', 'menu.components.list', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (11, 5, '1', 5, '高级表格', '/data/table', 'ProfileOutlined', 'data.table', 'menu.components.table', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (12, 0, '0', 2, '会员管理', '/user', 'UserOutlined', 'user', 'menu.user', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (13, 12, '1', 0, '会员列表', '/user/list', 'TeamOutlined', 'user.list', 'menu.user.list', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (14, 13, '2', 1, '会员列表查询', '', '', 'user.list.list', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (15, 13, '2', 2, '会员列表编辑', '', '', 'user.list.edit', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (16, 13, '2', 3, '会员列表充值', '', '', 'user.list.recharge', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (17, 13, '2', 4, '会员重置密码', '', '', 'user.list.resetPassword', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (18, 12, '1', 1, '余额记录', '/user/balance', 'CreditCardOutlined', 'user.balance', 'menu.user.balance', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (19, 18, '2', 0, '余额记录查询', '', '', 'user.balance.list', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (20, 0, '0', 3, '管理员', '/admin', 'BankOutlined', 'admin', 'menu.admin', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (21, 20, '1', 0, '用户列表', '/admin/list', 'UserOutlined', 'admin.list', 'menu.admin.list', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (22, 21, '2', 0, '用户列表查询', '', '', 'admin.list.list', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (23, 21, '2', 1, '用户列表新增', '', '', 'admin.list.add', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (24, 21, '2', 2, '用户列表编辑', '', '', 'admin.list.edit', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (25, 21, '2', 3, '用户列表删除', '', '', 'admin.list.delete', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (26, 21, '2', 4, '重置用户密码', '', '', 'admin.list.resetPassword', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (27, 20, '1', 1, '角色管理', '/admin/role', 'DeploymentUnitOutlined', 'admin.role', 'menu.admin.role', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (28, 27, '2', 0, '角色管理查询', '', '', 'admin.role.list', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (29, 27, '2', 1, '角色管理详情', '', '', 'admin.role.get', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (30, 27, '2', 2, '角色管理新增', '', '', 'admin.role.add', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (31, 27, '2', 3, '角色管理编辑', '', '', 'admin.role.edit', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (32, 27, '2', 4, '角色管理删除', '', '', 'admin.role.delete', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (33, 20, '1', 2, '部门管理', '/admin/dept', 'ClusterOutlined', 'admin.dept', 'menu.admin.dept', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (34, 33, '2', 0, '部门管理查询', '', '', 'admin.dept.list', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (35, 33, '2', 1, '部门管理新增', '', '', 'admin.dept.add', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (36, 33, '2', 2, '部门管理编辑', '', '', 'admin.dept.edit', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (37, 33, '2', 3, '部门管理删除', '', '', 'admin.dept.delete', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (38, 20, '1', 3, '权限管理', '/admin/rule', 'DeleteRowOutlined', 'admin.rule', 'menu.admin.rule', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (39, 38, '2', 0, '权限管理查询', '', '', 'admin.rule.list', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (40, 38, '2', 1, '权限管理新增', '', '', 'admin.rule.add', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (41, 38, '2', 2, '权限管理编辑', '', '', 'admin.rule.edit', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (42, 38, '2', 3, '权限管理删除', '', '', 'admin.rule.delete', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (43, 0, '0', 4, '系统管理', '/system', 'ClusterOutlined', 'system', 'menu.system', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (44, 43, '1', 0, '字典管理', '/system/dict', 'DeleteRowOutlined', 'system.dict', 'menu.system.dict', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (45, 44, '2', 0, '字典管理查询', '', '', 'system.dict.list', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (46, 44, '2', 1, '字典管理新增', '', '', 'system.dict.add', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (47, 44, '2', 2, '字典管理编辑', '', '', 'system.dict.edit', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (48, 44, '2', 3, '字典管理删除', '', '', 'system.dict.delete', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (49, 44, '2', 4, '字典项查询', '', '', 'system.dict.item.list', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (50, 44, '2', 5, '字典项新增', '', '', 'system.dict.item.add', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (51, 44, '2', 6, '字典项编辑', '', '', 'system.dict.item.edit', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (52, 44, '2', 7, '字典项删除', '', '', 'system.dict.item.delete', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (53, 43, '1', 1, '系统详情', '/system/info', 'InfoOutlined', 'system.info', 'menu.system.info', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (54, 43, '1', 2, '系统监控', '/system/monitor', 'MonitorOutlined', 'system.monitor', 'menu.system.monitor', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (55, 54, '2', 0, '系统监控查询', '', '', 'system.monitor.list', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (56, 0, '0', 5, '文件管理', '/file', 'FileOutlined', 'file', 'menu.file', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (57, 56, '1', 0, '文件管理', '/file/list', 'FileOutlined', 'file.list', 'menu.file.list', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (58, 57, '2', 0, '文件列表查询', '', '', 'file.list.list', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (59, 57, '2', 1, '文件列表删除', '', '', 'file.list.delete', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (60, 57, '2', 2, '文件列表编辑', '', '', 'file.list.edit', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (61, 57, '2', 3, '文件列表下载', '', '', 'file.list.download', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (62, 56, '1', 1, '文件分组', '/file/group', 'FileOutlined', 'file.group', 'menu.file.group', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (63, 62, '2', 0, '文件分组查询', '', '', 'file.group.list', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (64, 62, '2', 1, '文件分组新增', '', '', 'file.group.add', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (65, 62, '2', 2, '文件分组编辑', '', '', 'file.group.edit', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (66, 62, '2', 3, '文件分组删除', '', '', 'file.group.delete', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (67, 43, '1', 3, '系统设置', '/system/setting', 'SettingOutlined', 'system.setting', 'menu.system.setting', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (68, 67, '2', 0, '系统设置查询', '', '', 'system.setting.list', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (69, 67, '2', 1, '系统设置新增', '', '', 'system.setting.add', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (70, 67, '2', 2, '系统设置编辑', '', '', 'system.setting.edit', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (71, 67, '2', 3, '系统设置删除', '', '', 'system.setting.delete', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (72, 67, '2', 4, '系统设置分组查询', '', '', 'system.setting.group.list', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (73, 67, '2', 5, '系统设置分组新增', '', '', 'system.setting.group.add', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (74, 67, '2', 6, '系统设置分组编辑', '', '', 'system.setting.group.edit', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');
INSERT INTO `admin_rule` VALUES (75, 67, '2', 7, '系统设置分组删除', '', '', 'system.setting.group.delete', '', 1, 1, '2025-02-07 09:13:06', '2025-02-07 09:13:06');

-- ----------------------------
-- Records of admin_user
-- ----------------------------
INSERT INTO `admin_user` VALUES (1, 'admin', 'Admin', 45, '0', 'admin@xinadmin.cn', '1888888888', '1', 1, 1, '$2y$10$6u8Yqd90Qpc4P/xJ3F5J1.5.NiCB2CZ8JgC9MkEzCcGCQ0esDExCC', NULL, NULL, NULL, '2024-12-31 07:29:10', NULL);

-- ----------------------------
-- Records of dict
-- ----------------------------
INSERT INTO `dict` VALUES (12, '性别', 'default', '性别', 'sex', '2024-10-13 13:32:48', '2024-10-13 13:32:48');
INSERT INTO `dict` VALUES (13, '人物', 'default', '任务', 'pop', '2024-10-13 13:32:48', '2024-10-13 13:32:48');
INSERT INTO `dict` VALUES (14, '状态', 'default', '状态', 'status', '2024-10-13 13:32:48', '2024-10-13 13:32:48');
INSERT INTO `dict` VALUES (16, '权限类型', 'tag', '权限类型', 'ruleType', '2024-10-13 13:32:48', '2024-10-13 13:32:48');
INSERT INTO `dict` VALUES (17, '字段类型', 'default', '前端表单类型字典，请不要修改', 'valueType', '2024-10-13 13:32:48', '2024-10-13 13:32:48');
INSERT INTO `dict` VALUES (19, '查询操作符', 'default', '系统查询操作符，请不要修改', 'select', '2024-10-13 13:32:48', '2024-10-13 13:32:48');
INSERT INTO `dict` VALUES (20, '验证规则', 'default', 'CRUD 验证规则，请不要修改', 'validation', '2024-10-13 13:32:48', '2024-10-13 13:32:48');
INSERT INTO `dict` VALUES (21, '余额变动记录类型', 'tag', '余额变动记录类型', 'moneyLog', '2024-10-13 13:32:48', '2024-10-13 13:32:48');
INSERT INTO `dict` VALUES (25, '数据库类型', 'default', 'CRUD数据库类型', 'sqlType', '2024-10-13 13:32:48', '2024-10-13 13:32:48');

-- ----------------------------
-- Records of dict_item
-- ----------------------------
INSERT INTO `dict_item` VALUES (1, 14, '男', '0', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (2, 14, '女', '1', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (3, 12, '男', '0', '1', 'success', NULL, NULL);
INSERT INTO `dict_item` VALUES (5, 12, '女', '1', '1', 'error', NULL, NULL);
INSERT INTO `dict_item` VALUES (7, 16, '一级菜单', '0', '1', 'processing', NULL, NULL);
INSERT INTO `dict_item` VALUES (8, 16, '子菜单', '1', '1', 'success', NULL, NULL);
INSERT INTO `dict_item` VALUES (9, 16, '按钮/API', '2', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (10, 17, '文本框', 'text', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (11, 17, '数字输入框', 'digit', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (12, 17, '日期', 'date', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (13, 17, '金额输入框', 'money', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (14, 17, '文本域', 'textarea', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (15, 17, '下拉框', 'select', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (17, 17, '多选框', 'checkbox', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (18, 17, '星级组件', 'rate', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (19, 17, '单选框', 'radio', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (20, 17, '按钮单选框', 'radioButton', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (21, 17, '开关', 'switch', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (22, 17, '日期时间', 'dateTime', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (23, 18, '字符串(TEXT)', 'text', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (24, 18, '字符型(CHAR)', 'char', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (25, 18, '变长字符型(VARCHAR)', 'varchar', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (26, 18, '整数型(INT)', 'int', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (27, 18, '长整数型(BIGINT)', 'bigint', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (28, 18, '小数型(DECIMAL)', 'decimal', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (29, 18, '浮点型(FLOAT)', 'float', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (30, 18, '双精度浮点型(DOUBLE)', 'double', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (31, 18, '布尔型(BOOLEAN)', 'boolean', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (32, 18, '日期型(DATE)', 'date', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (33, 18, '时间型(TIME)', 'time', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (34, 18, '日期时间型(DATETIME)', 'datetime', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (35, 18, '时间戳(TIMESTAMP)', 'timestamp', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (36, 18, '二进制 large 对象 (BLOB)', 'blob', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (37, 18, '字符 large 对象 (CLOB)', 'clob', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (42, 19, '等于', '=', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (43, 19, '大于', '>', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (44, 19, '小于', '<', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (45, 19, '大于等于', '>=', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (46, 19, '小于等于', '<=', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (47, 19, '不等于', '<>', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (48, 19, '包含', 'like', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (49, 19, '日期查询', 'date', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (50, 20, '必填', 'verifyRequired', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (51, 20, '纯数字', 'verifyNumber', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (52, 20, '邮箱', 'verifyEmail', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (53, 20, 'Url', 'verifyUrl', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (54, 20, '整数', 'verifyInteger', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (55, 20, '手机号', 'verifyMobile', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (56, 20, '身份证', 'verifyIdCard', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (57, 20, '字符串', 'verifyString', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (58, 17, '自增主键', 'id', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (61, 21, '管理员操作', '0', '1', 'processing', NULL, NULL);
INSERT INTO `dict_item` VALUES (62, 21, '消费', '1', '1', 'error', NULL, NULL);
INSERT INTO `dict_item` VALUES (63, 21, '签到奖励', '2', '1', 'success', NULL, NULL);
INSERT INTO `dict_item` VALUES (64, 17, '密码框', 'password', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (65, 17, '月', 'dateMonth', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (66, 17, '季度', 'dateQuarter', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (67, 17, '年', 'dateYear', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (68, 17, '颜色选择器', 'color', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (69, 24, 'ceshi', '1', '1', 'error', '2024-10-10 04:54:46', '2024-10-10 04:56:49');
INSERT INTO `dict_item` VALUES (70, 25, '整数（INT）', 'int', '1', 'default', '2024-10-13 13:34:12', '2024-10-13 13:34:12');
INSERT INTO `dict_item` VALUES (71, 25, '字符（VARCHAR）', 'varchar', '1', 'default', '2024-10-13 13:34:32', '2024-10-13 13:34:32');
INSERT INTO `dict_item` VALUES (72, 25, '长文本（TEXT）', 'text', '1', 'default', '2024-10-13 13:34:54', '2024-10-13 13:34:54');
INSERT INTO `dict_item` VALUES (73, 25, '日期（DATE）', 'date', '1', 'default', '2024-10-13 13:35:12', '2024-10-13 13:35:12');
INSERT INTO `dict_item` VALUES (74, 25, '日期时间（DATETIME）', 'datetime', '1', 'default', '2024-10-13 13:35:26', '2024-10-13 13:35:26');
INSERT INTO `dict_item` VALUES (75, 25, '定点小数（DECIMAL）', 'decimal', '1', 'default', '2024-10-13 13:36:15', '2024-10-13 13:36:15');
INSERT INTO `dict_item` VALUES (76, 25, '布尔（BOOLEAN）', 'boolean', '1', 'default', '2024-10-13 13:36:37', '2024-10-13 13:36:37');
INSERT INTO `dict_item` VALUES (77, 25, '枚举（ENUM）', 'enum', '1', 'default', '2024-10-13 13:36:57', '2024-10-13 13:36:57');
INSERT INTO `dict_item` VALUES (78, 25, '集合（SET）', 'set', '1', 'default', '2024-10-13 13:37:14', '2024-10-13 13:37:14');

-- ----------------------------
-- Records of file_group
-- ----------------------------
INSERT INTO `file_group` VALUES (0, '默认文件夹', 0, '系统默认文件夹', '2025-02-06 16:42:31', '2025-02-06 16:42:34');
INSERT INTO `file_group` VALUES (14, '头像文件夹', 0, '用户头像文件夹', '2025-02-06 16:33:38', '2025-02-06 16:33:46');
INSERT INTO `file_group` VALUES (15, '附件文件夹', 0, '附件文件夹', '2025-02-06 16:33:41', '2025-02-06 16:33:49');
INSERT INTO `file_group` VALUES (16, '视频文件夹', 0, '视频文件夹', '2025-02-06 16:33:44', '2025-02-06 16:33:50');
INSERT INTO `file_group` VALUES (18, '测试分组', 3, '2131231', '2025-02-06 08:39:38', '2025-02-06 08:39:47');

-- ----------------------------
-- Records of setting
-- ----------------------------
INSERT INTO `setting` VALUES (1, 'title', '网站标题', '网站标题，用于展示在网站logo旁边和登录页面以及网页title中', 'Xin Admin', 'input', '', '', 3, 0, NULL, '2025-02-10 03:36:40');
INSERT INTO `setting` VALUES (4, 'logo', '网站 LOGO', '网站的LOGO，用于标识网站', 'https://file.xinadmin.cn/file/favicons.ico', 'input', '', '', 3, 0, NULL, '2025-02-10 03:36:40');
INSERT INTO `setting` VALUES (5, 'subtitle', '网站副标题', '网站副标题，展示在登录页面标题的下面', 'Xin Admin 快速开发框架', 'input', '', '', 3, 0, NULL, '2025-02-10 03:36:40');
INSERT INTO `setting` VALUES (6, 'login', '邮箱登录', '是否开启邮箱登录', '0', 'switch', '', '', 4, 99, NULL, NULL);
INSERT INTO `setting` VALUES (7, 'Port', '服务器端口', '邮箱服务器端口', '465', 'input', '', '', 4, 80, NULL, NULL);
INSERT INTO `setting` VALUES (8, 'SMTPSecure', '邮箱协议', '邮箱协议 TLS 或者ssl协议', 'ssl', 'input', '', '', 4, 70, NULL, NULL);
INSERT INTO `setting` VALUES (9, 'username', 'SMTP 用户名', '邮箱 SMTP 用户名', '', 'input', '', '', 4, 60, NULL, NULL);
INSERT INTO `setting` VALUES (10, 'password', 'SMTP 密码', '邮箱 SMTP 密码', '', 'password', '', '', 4, 60, NULL, NULL);
INSERT INTO `setting` VALUES (11, 'smtp', 'SMTP服务器', 'SMTP服务器 地址', '', 'input', '', '', 4, 50, NULL, NULL);
INSERT INTO `setting` VALUES (12, 'char', '邮件编码', '邮件编码，UTF-8', 'UTF-8', 'input', '', '', 4, 50, NULL, NULL);

-- ----------------------------
-- Records of setting_group
-- ----------------------------
INSERT INTO `setting_group` VALUES (3, '网站设置', 'web', '网站基础设置', NULL, '2025-02-10 03:21:51');
INSERT INTO `setting_group` VALUES (4, '邮箱设置', 'mail', '网站邮箱设置', NULL, '2025-02-10 03:21:59');

-- ----------------------------
-- Records of xin_user
-- ----------------------------
INSERT INTO `xin_user` VALUES (1, '15999999999', 'user', 'liu@xinadmin.cn', '$2y$10$UnYcdOkscv.98UDyQ91Bwezj70acn9g5HGFMxsWYfnegBQvRE8W6y', '小刘同学', 47, '0', '2025-01-13', 1, 369.00, 0, '131231', '1', '2025-01-13 08:50:28', '2025-01-13 02:01:39');

SET FOREIGN_KEY_CHECKS = 1;
