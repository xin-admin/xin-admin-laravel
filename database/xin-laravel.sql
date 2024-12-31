/*
 Navicat Premium Data Transfer

 Source Server         : 本地
 Source Server Type    : MySQL
 Source Server Version : 80031
 Source Host           : localhost:3306
 Source Schema         : xin-laravel

 Target Server Type    : MySQL
 Target Server Version : 80031
 File Encoding         : 65001

 Date: 31/12/2024 16:06:39
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
) ENGINE = InnoDB AUTO_INCREMENT = 111 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci COMMENT = '部门表' ROW_FORMAT = DYNAMIC;

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
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '角色表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_role
-- ----------------------------
INSERT INTO `admin_role` VALUES (1, '超级管理员', 0, '*', NULL, '2024-12-14 10:07:33', '2024-12-14 10:07:36');
INSERT INTO `admin_role` VALUES (2, '管理员', 1, '*', NULL, '2024-12-14 10:08:08', '2024-12-14 10:08:11');

-- ----------------------------
-- Table structure for admin_rule
-- ----------------------------
DROP TABLE IF EXISTS `admin_rule`;
CREATE TABLE `admin_rule`  (
  `rule_id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `parent_id` int NOT NULL DEFAULT 0 COMMENT '父ID',
  `type` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '类型 字典：ruleType',
  `sort` int NOT NULL DEFAULT 0 COMMENT '排序',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `path` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '路由地址',
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '图标',
  `key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '权限标识',
  `local` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '国际化标识',
  `status` int NOT NULL DEFAULT 1 COMMENT '启用状态',
  `show` int NOT NULL DEFAULT 1 COMMENT '显示状态',
  `created_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`rule_id`) USING BTREE,
  UNIQUE INDEX `key`(`key`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 135 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '权限规则表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admin_rule
-- ----------------------------
INSERT INTO `admin_rule` VALUES (2, 0, '0', 998, '示例组件', '/data', 'GoldOutlined', 'data', 'menu.components', 1, 1, '2024-12-13 01:19:11', '2024-12-26 00:59:35');
INSERT INTO `admin_rule` VALUES (3, 2, '1', 0, '定义列表', '/data/descriptions', '', 'data.descriptions', 'menu.components.descriptions', 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (7, 92, '1', 8, '管理员列表', '/admin/list', 'RobotOutlined', 'admin.list', 'menu.admin.list', 1, 1, '2024-12-13 01:19:11', '2024-12-31 07:37:15');
INSERT INTO `admin_rule` VALUES (8, 92, '1', 1, '管理员角色', '/admin/role', 'DeploymentUnitOutlined', 'admin.role', 'menu.admin.role', 1, 1, '2024-12-13 01:19:11', '2024-12-31 07:37:30');
INSERT INTO `admin_rule` VALUES (9, 92, '1', 2, '权限菜单管理', '/admin/rule', 'DeleteRowOutlined', 'admin.rule', 'menu.admin.rule', 1, 1, '2024-12-13 01:19:11', '2024-12-31 07:34:38');
INSERT INTO `admin_rule` VALUES (10, 0, '0', 995, '系统管理', '/system', 'ClusterOutlined', 'system', 'menu.system', 1, 1, '2024-12-13 01:19:11', '2024-12-13 05:58:19');
INSERT INTO `admin_rule` VALUES (11, 10, '1', 3, '字典管理', '/system/dict', '', 'system.dict', 'menu.system.dict', 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (12, 11, '2', 0, '字典新建', NULL, '', 'system.dict.add', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (13, 11, '2', 0, '字典删除', NULL, '', 'system.dict.delete', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (14, 11, '2', 0, '字典编辑', NULL, '', 'system.dict.edit', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (15, 11, '2', 0, '字典查看', NULL, '', 'system.dict.list', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (16, 7, '2', 0, '查看管理员列表', NULL, '', 'admin.list.list', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (17, 7, '2', 0, '新增管理员', NULL, '', 'admin.list.add', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (18, 7, '2', 0, '编辑管理员', NULL, '', 'admin.list.edit', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (19, 7, '2', 0, '删除管理员', NULL, '', 'admin.list.delete', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (20, 8, '2', 0, '管理员分组查看', NULL, '', 'admin.group.list', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (21, 8, '2', 0, '管理员分组新增', NULL, '', 'admin.group.add', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (22, 8, '2', 0, '管理员分组编辑', NULL, '', 'admin.group.edit', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (23, 8, '2', 0, '管理员分组删除', NULL, '', 'admin.group.delete', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (24, 8, '2', 0, '分组权限查看', NULL, '', 'admin.group.rule', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (25, 8, '2', 0, '管理员权限修改', NULL, '', 'admin.group.ruleEdit', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (26, 9, '2', 0, '权限管理查看', NULL, '', 'admin.rule.list', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (27, 9, '2', 0, '权限管理新增', NULL, '', 'admin.rule.add', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (28, 9, '2', 0, '权限管理编辑', NULL, '', 'admin.rule.edit', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (29, 9, '2', 0, '权限管理删除', NULL, '', 'admin.rule.delete', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (30, 11, '2', 0, '字典配置', NULL, '', 'system.dict.item.list', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (31, 11, '2', 0, '字典配置新增', NULL, '', 'system.dict.item.add', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (32, 11, '2', 0, '字典配置编辑', NULL, '', 'system.dict.item.edit', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (33, 11, '2', 0, '字典配置删除', NULL, '', 'system.dict.item.delete', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (35, 2, '1', 0, '高级列表', '/data/list', '', 'data.list', 'menu.components.list', 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (36, 2, '1', 0, '单选卡片', '/data/checkcard', '', 'data.checkcard', 'menu.components.checkcard', 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (39, 0, '0', 997, '会员管理', '/user', 'UserOutlined', 'user', 'menu.user', 1, 1, '2024-12-13 01:19:11', '2024-12-13 05:57:34');
INSERT INTO `admin_rule` VALUES (40, 39, '1', 0, '会员列表', '/user/list', '', 'user.list', 'menu.user.list', 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (43, 0, '0', 994, '在线开发', '/online', 'CodeOutlined', 'online', 'menu.online', 1, 1, '2024-12-13 01:19:11', '2024-12-13 05:58:46');
INSERT INTO `admin_rule` VALUES (44, 43, '1', 0, '表格设计', '/online/table', '', 'online.table', 'menu.online.table', 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (48, 0, '0', 99, 'Xin Admin', 'https://xinadmin.cn/', 'ItalicOutlined', 'xinadmin', 'menu.xinadmin', 1, 1, '2024-12-13 01:19:11', '2024-12-13 05:59:16');
INSERT INTO `admin_rule` VALUES (49, 10, '1', 5, '系统信息', '/system/info', '', 'system.info', 'menu.system.info', 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (50, 10, '1', 4, '系统设置', '/system/setting', '', 'system.setting', 'menu.system.setting', 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (51, 50, '2', 0, '设置分组查看', NULL, '', 'system.setting.querySettingGroup', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (52, 50, '2', 1, '设置分组新增', NULL, '', 'system.setting.addGroup', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (53, 50, '2', 3, '查询设置父 ID', NULL, '', 'system.setting.querySettingPid', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (54, 44, '2', 0, '表格设计查询', NULL, '', 'online.table.list', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (55, 44, '2', 1, '表格设计编辑', NULL, '', 'online.table.edit', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (56, 44, '2', 2, '表格设计删除', NULL, '', 'online.table.delete', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (57, 44, '2', 3, '表格设计', NULL, '', 'online.tableDevise', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (58, 44, '2', 4, 'CRUD 保存', NULL, '', 'online.table.saveData', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (59, 44, '2', 5, '获取 CRUD 数据', NULL, '', 'online.table.getData', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (60, 44, '2', 6, 'CRUD 保存并生成', NULL, '', 'online.table.crud', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (61, 50, '2', 3, '获取设置列表', NULL, '', 'system.setting.list', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (62, 50, '2', 4, '新增设置', NULL, '', 'system.setting.add', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (63, 50, '2', 5, '编辑设置', NULL, '', 'system.setting.edit', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (64, 50, '2', 6, '删除设置', NULL, '', 'system.setting.delete', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (69, 39, '1', 2, '会员分组', '/user/group', '', 'user.group', 'menu.user.group', 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (70, 39, '1', 2, '权限管理', '/user/rule', '', 'user.rule', 'menu.user.rule', 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (71, 40, '2', 1, '会员列表查询', NULL, '', 'user.list.list', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (72, 40, '2', 2, '会员列表编辑', NULL, '', 'user.list.edit', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (73, 40, '2', 3, '会员列表新增', NULL, '', 'user.list.add', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (74, 40, '2', 4, '会员列表删除', NULL, '', 'user.list.delete', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (75, 69, '2', 1, '会员分组查询', NULL, '', 'user.group.list', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (76, 69, '2', 2, '会员分组新增', NULL, '', 'user.group.add', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (77, 69, '2', 3, '会员分组编辑', NULL, '', 'user.group.edit', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (78, 69, '2', 4, '会员分组删除', NULL, '', 'user.group.delete', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (79, 69, '2', 5, '分组权限查看', NULL, '', 'user.group.rule', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (80, 69, '2', 6, '分组权限修改', NULL, '', 'user.group.ruleEdit', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (81, 39, '1', 4, '会员余额记录', '/user/moneyLog', '', 'user.moneyLog', 'menu.user.moneyLog', 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (82, 81, '2', 0, '会员余额记录查询', NULL, '', 'user.moneyLog.list', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (83, 81, '2', 2, '修改用户余额', NULL, '', 'user.moneyLog.add', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (84, 44, '2', 0, '表格设计新增', NULL, '', 'online.table.add', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (85, 81, '2', 3, '会员余额记录删除', NULL, '', 'user.moneyLog.delete', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (86, 2, '1', 0, '表单示例', '/data/form', '', 'data.form', 'menu.components.form', 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (87, 7, '2', 1, '修改管理员密码', NULL, '', 'admin.list.updatePassword', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (88, 0, '0', 999, '仪表盘', '/dashboard', 'PieChartOutlined', 'dashboard', 'menu.dashboard', 1, 1, '2024-12-13 01:19:11', '2024-12-31 01:02:11');
INSERT INTO `admin_rule` VALUES (89, 88, '1', 10, '分析页', '/dashboard/analysis', '', 'dashboard.analysis', 'menu.dashboard.analysis', 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (90, 88, '1', 1, '监控页', '/dashboard/monitor', '', 'dashboard.monitor', 'menu.dashboard.monitor', 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (91, 88, '1', 2, '工作台', '/dashboard/workplace', '', 'dashboard.workplace', 'menu.dashboard.workplace', 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (92, 0, '0', 996, '管理员', '/admin', 'BankOutlined', 'admin', 'menu.admin', 1, 1, '2024-12-13 01:19:11', '2024-12-13 05:58:10');
INSERT INTO `admin_rule` VALUES (93, 2, '1', 5, '高级表格', '/data/table', '', 'data.table', 'menu.components.table', 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (94, 2, '1', 6, '图标选择', '/data/icon', '', 'data.icon', 'menu.components.iconForm', 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (102, 10, '1', 4, '文件管理', '/system/file', '', 'system.file', 'menu.File', 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (103, 102, '2', 0, '文件分组列表', NULL, '', 'file.group.list', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (104, 102, '2', 1, '新增文件分组', NULL, '', 'file.group.add', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (105, 102, '2', 2, '编辑文件分组', NULL, '', 'file.group.edit', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (106, 102, '2', 3, '删除文件分组', NULL, '', 'file.group.delete', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (107, 102, '2', 4, '获取文件列表', NULL, '', 'file.file.list', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (108, 102, '2', 5, '删除文件', NULL, '', 'file.file.delete', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (109, 102, '2', 6, '上传图片文件', NULL, '', 'file.upload.image', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (110, 102, '2', 7, '上传视频文件', NULL, '', 'file.upload.video', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (111, 102, '2', 8, '上传压缩文件', NULL, '', 'file.upload.zip', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (112, 102, '2', 9, '上传音频文件', NULL, '', 'file.upload.mp3', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (113, 102, '2', 10, '上传其它文件', NULL, '', 'file.upload.annex', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (114, 70, '2', 99, '权限列表', NULL, '', 'user.rule.list', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (115, 70, '2', 88, '会员权限新增', NULL, '', 'user.rule.add', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (116, 70, '2', 60, '会员权限删除', NULL, '', 'user.rule.delete', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (117, 70, '2', 0, '会员权限编辑', NULL, '', 'user.rule.edit', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (118, 0, '0', 100, '用户设置', '/admin/setting', '', 'admin.setting', NULL, 1, 0, '2024-12-13 01:19:11', '2024-12-23 09:09:30');
INSERT INTO `admin_rule` VALUES (119, 10, '1', 5, '系统监控', '/system/monitor', '', 'system.monitor', 'menu.system.monitor', 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (120, 119, '2', 5, '监控列表', NULL, '', 'system.monitor.list', NULL, 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (121, 7, '2', 0, '重置密码', '', '', 'admin.list.resetPassword', '', 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (122, 40, '2', 0, '用户充值', '', '', 'user.list.recharge', '', 1, 1, '2024-12-13 01:19:11', '2024-12-13 01:19:11');
INSERT INTO `admin_rule` VALUES (135, 92, '1', 5, '部门管理', '/admin/dept', 'ClusterOutlined', 'admin.dept', 'menu.admin.dept', 1, 1, '2024-12-31 06:53:35', '2024-12-31 07:35:57');

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
INSERT INTO `admin_user` VALUES (2, 'test', 'text', 40, '0', '2@qq.cin', '15899999999', '1', 1, 3, '$2y$10$c2uQdzrxDBrYRGo8NNhAwuKx.pXT1AtdzF2PKux9F./zlL2vSguym', NULL, NULL, '2024-09-27 08:18:39', '2024-12-31 07:31:37', NULL);
INSERT INTO `admin_user` VALUES (3, 'test', '张三', 43, '0', '230@qq.com', '19999999999', '1', 1, 2, '', NULL, NULL, '2024-12-31 03:17:49', '2024-12-31 07:31:42', NULL);

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
INSERT INTO `dict` VALUES (12, '性别', 'default', '性别', 'sex', NULL, NULL);
INSERT INTO `dict` VALUES (13, '人物', 'default', '任务', 'pop', NULL, NULL);
INSERT INTO `dict` VALUES (14, '状态', 'default', '状态', 'status', NULL, NULL);
INSERT INTO `dict` VALUES (16, '权限类型', 'tag', '权限类型', 'ruleType', NULL, NULL);
INSERT INTO `dict` VALUES (17, '字段类型', 'default', '前端表单类型字典，请不要修改', 'valueType', NULL, NULL);
INSERT INTO `dict` VALUES (19, '查询操作符', 'default', '系统查询操作符，请不要修改', 'select', NULL, NULL);
INSERT INTO `dict` VALUES (20, '验证规则', 'default', 'CRUD 验证规则，请不要修改', 'validation', NULL, NULL);
INSERT INTO `dict` VALUES (21, '余额变动记录类型', 'tag', '余额变动记录类型', 'moneyLog', NULL, NULL);
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
) ENGINE = InnoDB AUTO_INCREMENT = 79 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '字典项列表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of dict_item
-- ----------------------------
INSERT INTO `dict_item` VALUES (1, 14, '男', '0', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (2, 14, '女', '1', '1', 'default', NULL, NULL);
INSERT INTO `dict_item` VALUES (3, 12, '男', '0', '1', 'success', NULL, NULL);
INSERT INTO `dict_item` VALUES (5, 12, '女', '1', '1', 'error', NULL, NULL);
INSERT INTO `dict_item` VALUES (6, 14, '变态', '3', '1', 'default', NULL, NULL);
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
  `storage` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '存储方式',
  `domain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '存储域名',
  `file_type` int UNSIGNED NOT NULL DEFAULT 10 COMMENT '文件类型(10图片 20附件 30视频)',
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件名称(仅显示)',
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件路径',
  `file_size` int NOT NULL DEFAULT 0 COMMENT '文件大小(字节)',
  `file_ext` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件扩展名',
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件封面',
  `uploader_id` int NOT NULL DEFAULT 0 COMMENT '上传者用户ID',
  `is_recycle` int NOT NULL DEFAULT 0 COMMENT '是否在回收站',
  `created_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`file_id`) USING BTREE,
  INDEX `group_id`(`group_id`) USING BTREE,
  INDEX `is_recycle`(`is_recycle`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 43 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '文件库记录表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of file
-- ----------------------------
INSERT INTO `file` VALUES (34, 1, 20, 'public', '', 10, '2.jpg', 'file/zNaz7W566h4gOP5upJ9AH45A9vt0n40WTFx7mlcH.jpg', 34921, 'jpg', '', 1, 0, '2024-09-27 07:59:29', '2024-09-27 07:59:29');
INSERT INTO `file` VALUES (35, 1, 20, 'public', '', 10, '5.jpg', 'file/2KrduT29eeFN8SemJtYrzT1qFprgzb6N1gcMmCzo.jpg', 38563, 'jpg', '', 1, 0, '2024-09-27 08:09:59', '2024-09-27 08:09:59');
INSERT INTO `file` VALUES (36, 1, 20, 'public', '', 10, '五红花胶.jpg', 'file/Ktd7TR34dVKr8DmrML0b0DQ74eO1ABNnnkrQbkH4.jpg', 30076, 'jpg', '', 1, 0, '2024-09-27 08:14:51', '2024-09-27 08:14:51');
INSERT INTO `file` VALUES (37, 1, 20, 'public', '', 10, '2.jpg', 'file/3DI1pRI8kmqMUvbyerIN3QeBlQKfXDmexHDkPWnr.jpg', 34921, 'jpg', '', 1, 0, '2024-09-27 08:15:40', '2024-09-27 08:15:40');
INSERT INTO `file` VALUES (38, 1, 20, 'public', '', 10, '新建项目 (1).png', 'file/zdM76ewpH3DpS2uZD0e5dUMuP4loQrsdjkNdhRsD.png', 827328, 'png', '', 1, 0, '2024-09-28 11:52:47', '2024-09-28 11:52:47');
INSERT INTO `file` VALUES (39, 1, 20, 'public', '', 10, '新建项目.png', 'file/9pkKavyTtiaXyAvvj6VVqh3DE5AzReKNy6A4uaFE.png', 795778, 'png', '', 1, 0, '2024-09-28 11:53:04', '2024-09-28 11:53:04');
INSERT INTO `file` VALUES (40, 1, 20, 'public', '', 10, '新建项目 (1).png', 'file/gaQc9mqy36Nlx3FmsVQujHlaB1tWnPdYoLGn7RL0.png', 827328, 'png', '', 1, 0, '2024-09-28 11:54:28', '2024-09-28 11:54:28');
INSERT INTO `file` VALUES (41, 1, 20, 'public', '', 10, 'abc-123.jpg', 'file/JPWbyTi01c0VsL5tphotwaNjRAMi5vav2vnAj4Mq.jpg', 263901, 'jpg', '', 1, 0, '2024-10-10 06:11:57', '2024-10-10 06:11:57');
INSERT INTO `file` VALUES (42, 0, 20, 'public', '', 10, '回答.png', 'file/5XEZnuOQqqmxAo3bT1WCqM5PQC2sZrNf20Z543Q9.png', 4601, 'png', '', 1, 0, '2024-10-22 05:31:23', '2024-10-22 05:31:23');
INSERT INTO `file` VALUES (43, 0, 20, 'public', '', 10, '屏幕截图 2024-12-30 101735.png', 'file/HlR3EEv3Qvv6hQeNtn6gHOyTI3Er0TIwClRgJPKv.png', 345677, 'png', '', 1, 0, '2024-12-31 03:15:12', '2024-12-31 03:15:12');
INSERT INTO `file` VALUES (44, 0, 20, 'public', '', 10, '屏幕截图 2024-12-18 135532.png', 'file/B2lF1hdJfkQTJiX95swkSqegIZLYlz0UL4zWH1cQ.png', 596321, 'png', '', 1, 0, '2024-12-31 03:18:04', '2024-12-31 03:18:04');
INSERT INTO `file` VALUES (45, 0, 20, 'public', '', 10, '屏幕截图 2024-12-30 101735.png', 'file/R9hVdqe4HQSgrkCEFvB01Lc6VeLSLY8EmfE0yGXQ.png', 120469, 'png', '', 1, 0, '2024-12-31 07:25:18', '2024-12-31 07:25:18');

-- ----------------------------
-- Table structure for file_group
-- ----------------------------
DROP TABLE IF EXISTS `file_group`;
CREATE TABLE `file_group`  (
  `group_id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '分组ID',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分组名称',
  `parent_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '上级分组ID',
  `sort` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序(数字越小越靠前)',
  `created_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`group_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '文件库分组记录表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of file_group
-- ----------------------------
INSERT INTO `file_group` VALUES (14, '头像文件夹', 0, 0, NULL, NULL);
INSERT INTO `file_group` VALUES (15, '附件文件夹', 0, 0, NULL, NULL);
INSERT INTO `file_group` VALUES (16, '视频文件夹', 0, 0, NULL, NULL);

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '0001_01_01_000001_create_cache_table', 1);

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
) ENGINE = InnoDB AUTO_INCREMENT = 57 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '数据监控表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of monitor
-- ----------------------------
INSERT INTO `monitor` VALUES (1, '管理员登录', 'App\\Http\\Controllers\\Admin\\IndexController@login', '127.0.0.1', 'localhost', '本机地址 本机地址  ', 'http://localhost:8000/admin/index/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-10-08 13:09:43');
INSERT INTO `monitor` VALUES (36, '管理员登录', 'App\\Http\\Controllers\\Admin\\IndexController@login', '127.0.0.1', 'localhost', '本机地址 本机地址  ', 'http://localhost:8000/admin/index/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-10-09 06:31:29');
INSERT INTO `monitor` VALUES (37, '修改管理员信息', 'App\\Http\\Controllers\\Admin\\AdminController@updateAdmin', '127.0.0.1', 'localhost', '本机地址 本机地址  ', 'http://localhost:8000/admin/admin/updateAdmin', '{\"username\":\"admin\",\"nickname\":\"Admin\",\"email\":\"admin@xinadmin.cn\",\"mobile\":\"1888888888\",\"avatar_id\":41}', '[]', 1, '2024-10-10 06:11:59');
INSERT INTO `monitor` VALUES (38, '管理员登录', 'App\\Http\\Controllers\\Admin\\IndexController@login', '127.0.0.1', 'localhost', '本机地址 本机地址  ', 'http://localhost:8000/admin/index/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-10-10 12:02:31');
INSERT INTO `monitor` VALUES (39, '管理员登录', 'App\\Http\\Controllers\\Admin\\IndexController@login', '127.0.0.1', 'localhost', '本机地址 本机地址  ', 'http://localhost:8000/admin/index/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-10-14 00:50:13');
INSERT INTO `monitor` VALUES (40, '管理员登录', 'App\\Http\\Controllers\\Admin\\IndexController@login', '127.0.0.1', 'localhost', '本机地址 本机地址  ', 'http://localhost:8000/admin/index/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-11-18 05:28:10');
INSERT INTO `monitor` VALUES (41, '管理员登录', 'App\\Http\\Admin\\Controllers\\SysUserController@login', '127.0.0.1', 'localhost', '本机地址 本机地址  ', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-11-28 07:42:28');
INSERT INTO `monitor` VALUES (42, '管理员登录', 'App\\Http\\Admin\\Controllers\\SysUserController@login', '127.0.0.1', 'localhost', '本机地址 本机地址  ', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-12-05 00:47:52');
INSERT INTO `monitor` VALUES (43, '管理员登录', 'App\\Http\\Admin\\Controllers\\SysUserController@login', '127.0.0.1', 'localhost', '本机地址 本机地址  ', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-12-11 08:12:48');
INSERT INTO `monitor` VALUES (44, '管理员登录', 'App\\Http\\Admin\\Controllers\\SysUserController@login', '127.0.0.1', 'localhost', '本机地址 本机地址  ', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-12-11 08:14:50');
INSERT INTO `monitor` VALUES (45, '管理员登录', 'App\\Http\\Admin\\Controllers\\SysUserController@login', '127.0.0.1', 'localhost', '本机地址 本机地址  ', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-12-11 08:16:08');
INSERT INTO `monitor` VALUES (46, '管理员登录', 'App\\Http\\Admin\\Controllers\\SysUserController@login', '127.0.0.1', 'localhost', '本机地址 本机地址  ', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-12-11 08:16:43');
INSERT INTO `monitor` VALUES (47, '管理员登录', 'App\\Http\\Admin\\Controllers\\SysUserController@login', '127.0.0.1', 'localhost', '本机地址 本机地址  ', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-12-11 08:19:25');
INSERT INTO `monitor` VALUES (48, '管理员登录', 'App\\Http\\Admin\\Controllers\\SysUserController@login', '127.0.0.1', 'localhost', '未知', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-12-12 02:16:33');
INSERT INTO `monitor` VALUES (49, '管理员登录', 'App\\Http\\Admin\\Controllers\\SysUserController@login', '127.0.0.1', 'localhost', '本机地址 本机地址  ', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-12-12 02:17:06');
INSERT INTO `monitor` VALUES (50, '管理员登录', 'App\\Http\\Admin\\Controllers\\SysUserController@login', '127.0.0.1', 'localhost', '本机地址 本机地址  ', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-12-12 02:17:26');
INSERT INTO `monitor` VALUES (51, '管理员登录', 'App\\Http\\Admin\\Controllers\\SysUserController@login', '127.0.0.1', 'localhost', '本机地址 本机地址  ', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-12-12 07:48:27');
INSERT INTO `monitor` VALUES (52, '管理员登录', 'App\\Http\\Admin\\Controllers\\SysUserController@login', '127.0.0.1', 'localhost', '本机地址 本机地址  ', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-12-12 07:53:01');
INSERT INTO `monitor` VALUES (53, '管理员登录', 'App\\Http\\Admin\\Controllers\\SysUserController@login', '127.0.0.1', 'localhost', '本机地址 本机地址  ', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-12-12 07:54:07');
INSERT INTO `monitor` VALUES (54, '管理员登录', 'App\\Http\\Admin\\Controllers\\SysUserController@login', '127.0.0.1', 'localhost', '未知', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-12-13 06:53:46');
INSERT INTO `monitor` VALUES (55, '管理员登录', 'App\\Http\\Admin\\Controllers\\AdminUserController@login', '127.0.0.1', 'localhost', '未知', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-12-19 08:13:32');
INSERT INTO `monitor` VALUES (56, '管理员登录', 'App\\Http\\Admin\\Controllers\\AdminUserController@login', '127.0.0.1', 'localhost', '未知', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-12-19 08:16:07');
INSERT INTO `monitor` VALUES (57, '管理员登录', 'App\\Http\\Admin\\Controllers\\AdminUserController@login', '127.0.0.1', 'localhost', '未知', 'http://localhost:8000/admin/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-12-19 08:16:49');

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
INSERT INTO `setting` VALUES (1, 'title', '网站标题', '网站标题，用于展示在网站logo旁边和登录页面以及网页title中', 'Xin Admin', 'input', '', '', 3, 0, NULL, NULL);
INSERT INTO `setting` VALUES (4, 'logo', '网站 LOGO', '网站的LOGO，用于标识网站', 'https://file.xinadmin.cn/file/favicons.ico', 'input', '', '', 3, 0, NULL, NULL);
INSERT INTO `setting` VALUES (5, 'subtitle', '网站副标题', '网站副标题，展示在登录页面标题的下面', 'Xin Admin 快速开发框架', 'input', '', '', 3, 0, NULL, NULL);
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
  `pid` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '父ID',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '分组标题',
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '分组KEY',
  `type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '分组类型1：设置菜单 2：设置组 ',
  `created_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `key`(`key`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '设置分组表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of setting_group
-- ----------------------------
INSERT INTO `setting_group` VALUES (3, 0, '网站设置', 'web', '2', NULL, NULL);
INSERT INTO `setting_group` VALUES (4, 0, '邮箱设置', 'mail', '1', NULL, NULL);

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
INSERT INTO `token` VALUES ('3892b3c46dca8f25aab64d488f9d3e58ad6b8863', 'admin-refresh', 1, 1737188012, 1734596012);
INSERT INTO `token` VALUES ('83a5832596cb46e5b57bc7e46ef84b08de397e8c', 'admin-refresh', 1, 1737188167, 1734596167);
INSERT INTO `token` VALUES ('3efd238f1bbe7d5c958b6b2b085964c6ae42f46d', 'admin-refresh', 1, 1737188209, 1734596209);
INSERT INTO `token` VALUES ('15d44542dc55388f52c829bc7aea8253b8bf0bcb', 'admin', 1, 1735632551, 1735631951);

-- ----------------------------
-- Table structure for xin_user
-- ----------------------------
DROP TABLE IF EXISTS `xin_user`;
CREATE TABLE `xin_user`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '手机号',
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `avatar_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '头像ID',
  `gender` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '性别',
  `birthday` date NULL DEFAULT NULL COMMENT '生日',
  `group_id` int UNSIGNED NOT NULL DEFAULT 1 COMMENT '分组ID',
  `money` int NOT NULL DEFAULT 0 COMMENT '用户余额',
  `score` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '积分',
  `motto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '签名',
  `status` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态',
  `created_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `group_id`(`group_id`) USING BTREE,
  INDEX `username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户列表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of xin_user
-- ----------------------------
INSERT INTO `xin_user` VALUES (1, '15999999999', 'user', 'liu@xinadmin.cn', '$2y$10$uT69rqj3E65JG4K.eYpFduGtw.zfJUNVvatouqlgmx2BDdlexkaeu', '小刘同学', 33, '0', NULL, 1, 57000, 0, '', '1', NULL, '2024-11-19 05:56:08');

-- ----------------------------
-- Table structure for xin_user_money_record
-- ----------------------------
DROP TABLE IF EXISTS `xin_user_money_record`;
CREATE TABLE `xin_user_money_record`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '记录ID',
  `user_id` int UNSIGNED NOT NULL COMMENT '用户ID',
  `scene` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '余额变动场景',
  `money` float NOT NULL DEFAULT 0 COMMENT '余额变动场景',
  `describe` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述/说明',
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户余额变动明细表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of xin_user_money_record
-- ----------------------------
INSERT INTO `xin_user_money_record` VALUES (1, 1, '1', 10000, '管理员充值: ceshi', '2024-09-29 15:45:04');
INSERT INTO `xin_user_money_record` VALUES (2, 1, '1', -6000, '管理员充值: 123', '2024-09-29 15:45:46');
INSERT INTO `xin_user_money_record` VALUES (3, 1, '1', 1000, '123', '2024-09-29 15:48:21');
INSERT INTO `xin_user_money_record` VALUES (4, 1, '1', 30000, '测试', '2024-09-29 15:50:51');
INSERT INTO `xin_user_money_record` VALUES (5, 1, '1', 10000, '133', '2024-09-29 15:51:30');
INSERT INTO `xin_user_money_record` VALUES (6, 1, '1', 9900, '123', '2024-10-08 12:59:55');
INSERT INTO `xin_user_money_record` VALUES (7, 1, '1', 100, '1', '2024-11-19 02:12:26');
INSERT INTO `xin_user_money_record` VALUES (8, 1, '1', 12300, '123', '2024-11-19 03:10:41');
INSERT INTO `xin_user_money_record` VALUES (9, 1, '1', 100, '1', '2024-11-19 03:12:34');
INSERT INTO `xin_user_money_record` VALUES (10, 1, '1', 12300, '12', '2024-11-19 03:24:15');
INSERT INTO `xin_user_money_record` VALUES (11, 1, '1', 12300, '1', '2024-11-19 05:56:08');

SET FOREIGN_KEY_CHECKS = 1;
