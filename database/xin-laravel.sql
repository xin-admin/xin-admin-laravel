/*
 Date: 14/02/2025 14:35:29
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_dept
-- ----------------------------
DROP TABLE IF EXISTS `admin_dept`;
CREATE TABLE `admin_dept`  (
  `dept_id` int NOT NULL AUTO_INCREMENT COMMENT '部门id',
  `parent_id` int NOT NULL DEFAULT 0 COMMENT '父部门id',
  `name` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '' COMMENT '部门名称',
  `sort` int NOT NULL DEFAULT 0 COMMENT '显示顺序',
  `leader` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '负责人',
  `phone` varchar(11) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '联系电话',
  `email` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL COMMENT '邮箱',
  `status` int NULL DEFAULT 1 COMMENT '部门状态（0正常 1停用）',
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`dept_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '部门表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admin_dept
-- ----------------------------
INSERT INTO `admin_dept` VALUES (1, 0, '小刘快跑网络科技有限公司', 0, '小刘', '19999999999', '230@qq.com', 1, '2024-12-31 14:16:23', '2024-12-31 14:16:25');
INSERT INTO `admin_dept` VALUES (2, 1, '小刘洛阳分公司', 0, '小刘', '19999999999', '230@qq.com', 1, '2024-12-31 07:15:41', '2024-12-31 07:15:41');
INSERT INTO `admin_dept` VALUES (3, 1, '小刘郑州分公司', 1, '小张', '19966666666', '230@qq.com', 1, '2024-12-31 07:16:20', '2024-12-31 07:16:20');
INSERT INTO `admin_dept` VALUES (4, 1, '小刘南阳分公司', 1, '小李', '19999999999', '230@qq.com', 1, '2024-12-31 07:58:10', '2024-12-31 07:58:10');
INSERT INTO `admin_dept` VALUES (5, 3, '郑州技术部', 1, '张三', '1999999999', '199@qq.com', 1, '2024-12-31 07:59:05', '2024-12-31 07:59:05');

-- ----------------------------
-- Table structure for admin_role
-- ----------------------------
DROP TABLE IF EXISTS `admin_role`;
CREATE TABLE `admin_role`  (
  `role_id` int NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '角色名称',
  `sort` int NOT NULL DEFAULT 0 COMMENT '排序',
  `rules` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '角色权限',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '描述',
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`role_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '角色表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_role
-- ----------------------------
INSERT INTO `admin_role` VALUES (1, '超级管理员', 0, '*', '超级管理', '2024-12-14 10:07:33', '2024-12-14 10:07:36');
INSERT INTO `admin_role` VALUES (2, '管理员', 1, '20,33,35,37,36,34,21,23,25,24,22,26,27,30,32,31,29,28,38,40,42,41,39,1,2,3,4,5,6,7,8,9,10,11,12,18,19,13,15,14,16,17', '系统管理员', '2024-12-14 10:08:08', '2025-01-13 02:32:55');
INSERT INTO `admin_role` VALUES (3, '财务', 2, '1,2,3,4', '负责管理账单', '2025-01-03 07:58:07', '2025-01-11 00:52:19');
INSERT INTO `admin_role` VALUES (6, '电商总监', 4, '1,2,3,4', '负责电商模块任务', '2025-01-04 06:25:14', '2025-01-11 00:52:25');
INSERT INTO `admin_role` VALUES (7, '市场运营', 5, '5,6,7,8,9,10,11', '负责市场推广业务', '2025-01-04 06:32:27', '2025-01-13 08:07:29');

-- ----------------------------
-- Table structure for admin_rule
-- ----------------------------
DROP TABLE IF EXISTS `admin_rule`;
CREATE TABLE `admin_rule`  (
  `rule_id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '权限ID',
  `parent_id` int NOT NULL DEFAULT 0 COMMENT '父级ID',
  `type` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '类型：1、一级菜单，2、子菜单，3、操作',
  `sort` int NOT NULL DEFAULT 0 COMMENT '排序',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `path` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '路径',
  `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图标',
  `key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '唯一标识',
  `local` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '语言包',
  `status` int NOT NULL DEFAULT 1 COMMENT '状态：1、正常，0、禁用',
  `show` int NOT NULL DEFAULT 1 COMMENT '显示：1、显示，0、隐藏',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`rule_id`) USING BTREE,
  UNIQUE INDEX `admin_rule_key_unique`(`key`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 75 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

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
-- Table structure for admin_user
-- ----------------------------
DROP TABLE IF EXISTS `admin_user`;
CREATE TABLE `admin_user`  (
  `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户昵称',
  `avatar_id` int NOT NULL COMMENT '头像',
  `sex` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '性别',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `status` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态:0=禁用,1=启用',
  `role_id` int NOT NULL COMMENT '角色ID',
  `dept_id` int NULL DEFAULT NULL COMMENT '部门ID',
  `password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
  `login_ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '最后登录IP',
  `login_date` datetime NULL DEFAULT NULL COMMENT '最后登录时间',
  `created_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `deleted_at` datetime NULL DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`user_id`) USING BTREE,
  INDEX `username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '管理员表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admin_user
-- ----------------------------
INSERT INTO `admin_user` VALUES (1, 'admin', 'Admin', 45, '0', 'admin@xinadmin.cn', '1888888888', '1', 1, 1, '$2y$10$6u8Yqd90Qpc4P/xJ3F5J1.5.NiCB2CZ8JgC9MkEzCcGCQ0esDExCC', NULL, NULL, NULL, '2024-12-31 07:29:10', NULL);
INSERT INTO `admin_user` VALUES (2, 'test', 'text', 46, '0', '2@qq.cin', '15899999999', '1', 6, 3, '$2y$10$c2uQdzrxDBrYRGo8NNhAwuKx.pXT1AtdzF2PKux9F./zlL2vSguym', NULL, NULL, '2024-09-27 08:18:39', '2025-01-04 06:31:17', NULL);
INSERT INTO `admin_user` VALUES (3, 'test', '张三', 43, '0', '230@qq.com', '19999999999', '1', 2, 2, '', NULL, NULL, '2024-12-31 03:17:49', '2025-01-04 01:07:15', NULL);

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cache
-- ----------------------------
INSERT INTO `cache` VALUES ('last_cache_cleanup_time', 'i:1731907689;', 2047267689);

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of cache_locks
-- ----------------------------

-- ----------------------------
-- Table structure for captcha_code
-- ----------------------------
DROP TABLE IF EXISTS `captcha_code`;
CREATE TABLE `captcha_code`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '类型',
  `code` int UNSIGNED NOT NULL COMMENT '验证码',
  `status` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态0：未发送 1：已发送 2：已验证',
  `interval` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '有效期',
  `data` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '接收方',
  `created_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '验证码记录表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of captcha_code
-- ----------------------------

-- ----------------------------
-- Table structure for dict
-- ----------------------------
DROP TABLE IF EXISTS `dict`;
CREATE TABLE `dict`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '字典名',
  `type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default' COMMENT '类型',
  `describe` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '字典描述',
  `code` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '字典编码',
  `created_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `code`(`code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '数据字典表' ROW_FORMAT = DYNAMIC;

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
-- Table structure for dict_item
-- ----------------------------
DROP TABLE IF EXISTS `dict_item`;
CREATE TABLE `dict_item`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `dict_id` int UNSIGNED NOT NULL COMMENT '字典ID',
  `label` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '字典项名称',
  `value` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '数据值',
  `switch` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '是否启用：0：禁用，1：启用',
  `status` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default' COMMENT '状态：（default,success,error,processing,warning）',
  `created_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 80 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '字典项列表' ROW_FORMAT = DYNAMIC;

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
-- Table structure for file
-- ----------------------------
DROP TABLE IF EXISTS `file`;
CREATE TABLE `file`  (
  `file_id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `group_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '文件分组ID',
  `channel` int UNSIGNED NOT NULL DEFAULT 10 COMMENT '上传来源(10商户后台 20用户端)',
  `disk` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '存储方式',
  `domain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '存储域名',
  `file_type` int UNSIGNED NOT NULL DEFAULT 10 COMMENT '文件类型(10图片 20附件 30视频)',
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件名称(仅显示)',
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件路径',
  `file_size` int NOT NULL DEFAULT 0 COMMENT '文件大小(字节)',
  `file_ext` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件扩展名',
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件封面',
  `uploader_id` int NOT NULL DEFAULT 0 COMMENT '上传者用户ID',
  `deleted_at` datetime NULL DEFAULT NULL COMMENT '是否在回收站',
  `created_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`file_id`) USING BTREE,
  INDEX `group_id`(`group_id`) USING BTREE,
  INDEX `is_recycle`(`deleted_at`) USING BTREE,
  CONSTRAINT `group` FOREIGN KEY (`group_id`) REFERENCES `file_group` (`group_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 48 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '文件库记录表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of file
-- ----------------------------
INSERT INTO `file` VALUES (42, 14, 20, 'public', '', 10, '回答.png', 'file/5XEZnuOQqqmxAo3bT1WCqM5PQC2sZrNf20Z543Q9.png', 4601, 'png', '', 1, NULL, '2024-10-22 05:31:23', '2024-10-22 05:31:23');
INSERT INTO `file` VALUES (43, 14, 20, 'public', '', 10, '屏幕截图 2024-12-30 101735.png', 'file/HlR3EEv3Qvv6hQeNtn6gHOyTI3Er0TIwClRgJPKv.png', 345677, 'png', '', 1, NULL, '2024-12-31 03:15:12', '2024-12-31 03:15:12');
INSERT INTO `file` VALUES (44, 14, 20, 'public', '', 10, '屏幕截图 2024-12-18 135532.png', 'file/B2lF1hdJfkQTJiX95swkSqegIZLYlz0UL4zWH1cQ.png', 596321, 'png', '', 1, NULL, '2024-12-31 03:18:04', '2024-12-31 03:18:04');
INSERT INTO `file` VALUES (45, 14, 20, 'public', '', 10, '屏幕截图 2024-12-30 101735.png', 'file/R9hVdqe4HQSgrkCEFvB01Lc6VeLSLY8EmfE0yGXQ.png', 120469, 'png', '', 1, NULL, '2024-12-31 07:25:18', '2024-12-31 07:25:18');
INSERT INTO `file` VALUES (46, 14, 20, 'public', '', 10, '孟诜.png', 'file/e3MKzMICN20EreouO0abjEpEh5VnrwyGDdlyCeVs.png', 157142, 'png', '', 1, NULL, '2025-01-04 06:24:08', '2025-01-04 06:24:08');
INSERT INTO `file` VALUES (47, 14, 20, 'public', '', 10, '孟诜.png', 'file/fDNackU5kQ9HEGgNcbx6z810j77GcUgw3oJeGSgO.png', 157142, 'png', '', 1, NULL, '2025-01-13 01:05:18', '2025-01-13 01:05:18');

-- ----------------------------
-- Table structure for file_group
-- ----------------------------
DROP TABLE IF EXISTS `file_group`;
CREATE TABLE `file_group`  (
  `group_id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '分组ID',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分组名称',
  `sort` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序(数字越小越靠前)',
  `describe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '描述',
  `created_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`group_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '文件库分组记录表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of file_group
-- ----------------------------
INSERT INTO `file_group` VALUES (0, '默认文件夹', 0, '系统默认文件夹', '2025-02-06 16:42:31', '2025-02-06 16:42:34');
INSERT INTO `file_group` VALUES (14, '头像文件夹', 0, '用户头像文件夹', '2025-02-06 16:33:38', '2025-02-06 16:33:46');
INSERT INTO `file_group` VALUES (15, '附件文件夹', 0, '附件文件夹', '2025-02-06 16:33:41', '2025-02-06 16:33:49');
INSERT INTO `file_group` VALUES (16, '视频文件夹', 0, '视频文件夹', '2025-02-06 16:33:44', '2025-02-06 16:33:50');
INSERT INTO `file_group` VALUES (18, '测试分组', 3, '2131231', '2025-02-06 08:39:38', '2025-02-06 08:39:47');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2025_01_10_074839_create_admin_rule_table', 1);
INSERT INTO `migrations` VALUES (3, '2025_01_10_083355_insert_admin_rule_data', 2);

-- ----------------------------
-- Table structure for monitor
-- ----------------------------
DROP TABLE IF EXISTS `monitor`;
CREATE TABLE `monitor`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '操作名称',
  `action` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `ip` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求IP',
  `host` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '当前访问域名或者IP',
  `address` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '地址',
  `url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '请求地址',
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'POST参数',
  `params` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'Params参数',
  `user_id` int NOT NULL COMMENT '管理员ID',
  `created_at` datetime NULL DEFAULT NULL COMMENT '访问时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '数据监控表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of monitor
-- ----------------------------
INSERT INTO `monitor` VALUES (1, '管理员登录', 'App\\Http\\Admin\\Controllers\\AdminUserController@login', '127.0.0.1', 'localhost', '未知', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2025-01-14 01:40:16');
INSERT INTO `monitor` VALUES (2, '管理员登录', 'App\\Http\\Admin\\Controllers\\AdminUserController@login', '127.0.0.1', 'localhost', '未知', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2025-01-14 02:47:27');
INSERT INTO `monitor` VALUES (3, '管理员登录', 'App\\Http\\Admin\\Controllers\\AdminUserController@login', '127.0.0.1', 'localhost', '未知', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2025-01-15 01:09:16');
INSERT INTO `monitor` VALUES (4, '管理员登录', 'App\\Http\\Admin\\Controllers\\AdminUserController@login', '127.0.0.1', 'localhost', '未知', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2025-01-15 07:08:54');
INSERT INTO `monitor` VALUES (5, '管理员登录', 'App\\Http\\Admin\\Controllers\\AdminUserController@login', '127.0.0.1', 'localhost', '未知', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2025-01-20 06:37:36');
INSERT INTO `monitor` VALUES (6, '管理员登录', 'App\\Http\\Admin\\Controllers\\AdminUserController@login', '127.0.0.1', 'localhost', '未知', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2025-01-20 08:58:51');
INSERT INTO `monitor` VALUES (7, '管理员登录', 'App\\Http\\Admin\\Controllers\\AdminUserController@login', '127.0.0.1', 'localhost', '未知', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2025-01-21 01:16:01');
INSERT INTO `monitor` VALUES (8, '管理员登录', 'App\\Http\\Admin\\Controllers\\AdminUserController@login', '127.0.0.1', 'localhost', '未知', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2025-01-25 02:30:15');
INSERT INTO `monitor` VALUES (9, '管理员登录', 'App\\Http\\Admin\\Controllers\\AdminUserController@login', '127.0.0.1', 'localhost', '未知', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2025-02-06 08:23:14');
INSERT INTO `monitor` VALUES (10, '管理员登录', 'App\\Http\\Admin\\Controllers\\AdminUserController@login', '127.0.0.1', 'localhost', '未知', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2025-02-07 08:35:10');
INSERT INTO `monitor` VALUES (11, '管理员登录', 'App\\Http\\Admin\\Controllers\\AdminUserController@login', '127.0.0.1', 'localhost', '未知', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2025-02-08 06:00:55');
INSERT INTO `monitor` VALUES (12, '管理员登录', 'App\\Http\\Admin\\Controllers\\AdminUserController@login', '127.0.0.1', 'localhost', '未知', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2025-02-10 01:05:29');
INSERT INTO `monitor` VALUES (13, '管理员登录', 'App\\Http\\Admin\\Controllers\\AdminUserController@login', '127.0.0.1', 'localhost', '未知', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2025-02-10 02:05:44');
INSERT INTO `monitor` VALUES (14, '管理员登录', 'App\\Http\\Admin\\Controllers\\AdminUserController@login', '127.0.0.1', 'localhost', '未知', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2025-02-10 03:15:13');
INSERT INTO `monitor` VALUES (15, '管理员登录', 'App\\Http\\Admin\\Controllers\\AdminUserController@login', '127.0.0.1', 'localhost', '未知', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2025-02-10 05:35:19');
INSERT INTO `monitor` VALUES (16, '管理员登录', 'App\\Http\\Admin\\Controllers\\AdminUserController@login', '127.0.0.1', 'localhost', '未知', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2025-02-10 06:47:11');
INSERT INTO `monitor` VALUES (17, '管理员登录', 'App\\Http\\Admin\\Controllers\\AdminUserController@login', '127.0.0.1', 'localhost', '未知', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2025-02-11 00:47:51');
INSERT INTO `monitor` VALUES (18, '管理员登录', 'App\\Http\\Admin\\Controllers\\AdminUserController@login', '127.0.0.1', 'localhost', '未知', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2025-02-11 01:52:06');
INSERT INTO `monitor` VALUES (19, '管理员登录', 'App\\Http\\Admin\\Controllers\\AdminUserController@login', '127.0.0.1', 'localhost', '未知', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2025-02-14 03:14:59');

-- ----------------------------
-- Table structure for online_table
-- ----------------------------
DROP TABLE IF EXISTS `online_table`;
CREATE TABLE `online_table`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '在线开发ID',
  `table_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '表格名',
  `data_table` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '数据表',
  `columns` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '表头Json',
  `crud_config` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'crud配置',
  `table_config` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '基础配置',
  `describe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `created_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '在线开发记录表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of online_table
-- ----------------------------
INSERT INTO `online_table` VALUES (8, 'test', 'user', '[{\"key\":\"id1729586493134\",\"valueType\":\"text\",\"title\":\"自增主键0\",\"dataIndex\":\"id0\",\"select\":\"=\",\"validation\":[],\"hideInForm\":true,\"hideInSearch\":false,\"hideInTable\":false,\"isKey\":true,\"null\":true,\"autoIncrement\":true,\"sqlType\":\"int\"},{\"key\":\"text1729586493574\",\"valueType\":\"text\",\"title\":\"文本框1\",\"dataIndex\":\"text1\",\"select\":\"like\",\"validation\":[],\"hideInForm\":false,\"hideInSearch\":false,\"hideInTable\":false,\"defaultValue\":\"empty string\",\"isKey\":false,\"null\":false,\"sqlLength\":50,\"sqlType\":\"varchar\"}]', '{\"sqlTableName\":\"123123\",\"sqlTableRemark\":\"123123\",\"name\":\"TableName\",\"controllerPath\":\"\",\"modelPath\":\"\",\"validatePath\":\"\",\"pagePath\":\"\",\"autoDeletetime\":false}', '{\"headerTitle\":\"表格标题\",\"tooltip\":\"表格 tooltip\",\"size\":\"middle\",\"rowSelectionShow\":true,\"addShow\":false,\"deleteShow\":false,\"editShow\":false,\"bordered\":false,\"showHeader\":false,\"searchShow\":true,\"search\":{\"resetText\":\"重置\",\"searchText\":\"查询\",\"span\":6,\"layout\":\"vertical\",\"filterType\":\"query\"},\"optionsShow\":true,\"options\":{\"reload\":true,\"density\":true,\"search\":false,\"fullScreen\":false,\"setting\":true},\"paginationShow\":true,\"pagination\":{\"size\":\"default\"}}', 'test', '2024-10-22 08:41:26', '2024-10-25 09:02:36');

-- ----------------------------
-- Table structure for setting
-- ----------------------------
DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '设置ID',
  `key` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '设置项标示',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设置标题',
  `describe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设置项描述',
  `values` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设置值',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设置类型',
  `options` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'options配置',
  `props` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'options配置',
  `group_id` int NOT NULL DEFAULT 0 COMMENT '分组ID',
  `sort` int NOT NULL DEFAULT 0 COMMENT '排序',
  `created_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `key`(`key`) USING BTREE,
  INDEX `group_id`(`group_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '设置记录表' ROW_FORMAT = DYNAMIC;

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
-- Table structure for setting_group
-- ----------------------------
DROP TABLE IF EXISTS `setting_group`;
CREATE TABLE `setting_group`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '设置分组ID',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '分组标题',
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '分组KEY',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '备注描述',
  `created_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `key`(`key`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '设置分组表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of setting_group
-- ----------------------------
INSERT INTO `setting_group` VALUES (3, '网站设置', 'web', '网站基础设置', NULL, '2025-02-10 03:21:51');
INSERT INTO `setting_group` VALUES (4, '邮箱设置', 'mail', '网站邮箱设置', NULL, '2025-02-10 03:21:59');

-- ----------------------------
-- Table structure for token
-- ----------------------------
DROP TABLE IF EXISTS `token`;
CREATE TABLE `token`  (
  `token` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Token',
  `type` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '类型',
  `user_id` int UNSIGNED NOT NULL COMMENT '用户ID',
  `expire_time` int NULL DEFAULT NULL COMMENT '过期时间',
  `create_time` int NULL DEFAULT NULL COMMENT '创建时间',
  INDEX `token`(`token`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户Token表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of token
-- ----------------------------
INSERT INTO `token` VALUES ('a4c06ee1aa99cc224de611d4cfc2c3e0e3e7065c', 'admin-refresh', 1, 1739506499, 1739502899);
INSERT INTO `token` VALUES ('51a1a5fdca568021e5720cf5020d304e52f77bd0', 'admin', 1, 1739503499, 1739502899);

-- ----------------------------
-- Table structure for xin_balance_record
-- ----------------------------
DROP TABLE IF EXISTS `xin_balance_record`;
CREATE TABLE `xin_balance_record`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '记录ID',
  `user_id` int UNSIGNED NOT NULL COMMENT '用户ID',
  `scene` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '余额变动场景',
  `balance` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '变动金额',
  `before` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '变动前',
  `after` decimal(10, 2) NOT NULL COMMENT '变动后',
  `describe` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述/说明',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '操作人',
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户余额变动明细表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of xin_balance_record
-- ----------------------------
INSERT INTO `xin_balance_record` VALUES (1, 1, '0', 123.00, 246.00, 369.00, '123', '2025-01-13 02:01:39', '2025-01-13 02:01:39');

-- ----------------------------
-- Table structure for xin_user
-- ----------------------------
DROP TABLE IF EXISTS `xin_user`;
CREATE TABLE `xin_user`  (
  `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '手机号',
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `avatar_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '头像ID',
  `gender` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '性别',
  `birthday` date NULL DEFAULT NULL COMMENT '生日',
  `group_id` int UNSIGNED NOT NULL DEFAULT 1 COMMENT '分组ID',
  `balance` decimal(10, 2) NOT NULL DEFAULT 0.00 COMMENT '用户余额',
  `score` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '积分',
  `motto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '签名',
  `status` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态',
  `created_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`user_id`) USING BTREE,
  INDEX `group_id`(`group_id`) USING BTREE,
  INDEX `username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户列表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of xin_user
-- ----------------------------
INSERT INTO `xin_user` VALUES (1, '15999999999', 'user', 'liu@xinadmin.cn', '$2y$10$UnYcdOkscv.98UDyQ91Bwezj70acn9g5HGFMxsWYfnegBQvRE8W6y', '小刘同学', 47, '0', '2025-01-13', 1, 369.00, 0, '131231', '1', '2025-01-13 08:50:28', '2025-01-13 02:01:39');

SET FOREIGN_KEY_CHECKS = 1;
