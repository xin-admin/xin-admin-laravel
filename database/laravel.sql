/*
 Navicat Premium Data Transfer

 Source Server         : 本地
 Source Server Type    : MySQL
 Source Server Version : 80031
 Source Host           : localhost:3306
 Source Schema         : laravel

 Target Server Type    : MySQL
 Target Server Version : 80031
 File Encoding         : 65001

 Date: 13/10/2024 23:23:59
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户昵称',
  `avatar_id` int NOT NULL COMMENT '头像',
  `sex` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '性别',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `status` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态:0=禁用,1=启用',
  `group_id` int NOT NULL DEFAULT 0 COMMENT '分组ID',
  `password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
  `created_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '管理员表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES (1, 'admin', 'Admin', 41, '0', 'admin@xinadmin.cn', '1888888888', '1', 1, '$2y$10$6u8Yqd90Qpc4P/xJ3F5J1.5.NiCB2CZ8JgC9MkEzCcGCQ0esDExCC', NULL, '2024-10-10 06:11:59');
INSERT INTO `admin` VALUES (2, 'test', 'text', 40, '0', '2@qq.cin', '15899999999', '1', 2, '$2y$10$c2uQdzrxDBrYRGo8NNhAwuKx.pXT1AtdzF2PKux9F./zlL2vSguym', '2024-09-27 08:18:39', '2024-09-28 11:54:29');

-- ----------------------------
-- Table structure for admin_group
-- ----------------------------
DROP TABLE IF EXISTS `admin_group`;
CREATE TABLE `admin_group`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '上级分组ID',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分组名称',
  `rules` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '权限ID',
  `created_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '管理分组表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admin_group
-- ----------------------------
INSERT INTO `admin_group` VALUES (1, 0, '系统管理员', '*', NULL, NULL);
INSERT INTO `admin_group` VALUES (2, 1, '二级管理员', '1,9,10,11,12', NULL, NULL);
INSERT INTO `admin_group` VALUES (3, 1, '三级管理员', '43,44,48,54,55,56,57,58,59,60,84,118', NULL, '2024-09-25 08:13:50');
INSERT INTO `admin_group` VALUES (4, 4, '哇哈哈1', NULL, '2024-09-25 07:13:14', '2024-09-25 07:18:38');

-- ----------------------------
-- Table structure for admin_rule
-- ----------------------------
DROP TABLE IF EXISTS `admin_rule`;
CREATE TABLE `admin_rule`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int NOT NULL DEFAULT 0 COMMENT '父ID',
  `type` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '类型 0：页面 1：数据 2：按钮',
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
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `key`(`key`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 123 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '管理员权限规则表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of admin_rule
-- ----------------------------
INSERT INTO `admin_rule` VALUES (2, 0, '0', 998, '示例组件', '/data', 'icon-daichuzhishijianzongshu', 'data', 'menu.components', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (3, 2, '1', 0, '定义列表', '/data/descriptions', '', 'data.descriptions', 'menu.components.descriptions', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (7, 92, '1', 0, '管理员列表', '/admin/list', NULL, 'admin.list', 'menu.admin.list', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (8, 92, '1', 1, '管理员分组', '/admin/group', NULL, 'admin.group', 'menu.admin.group', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (9, 92, '1', 2, '权限菜单管理', '/admin/rule', 'PieChartOutlined', 'admin.rule', 'menu.admin.rule', 1, 1, NULL, '2024-09-25 03:12:13');
INSERT INTO `admin_rule` VALUES (10, 0, '0', 995, '系统管理', '/system', 'icon-henjiqingli', 'system', 'menu.system', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (11, 10, '1', 3, '字典管理', '/system/dict', NULL, 'system.dict', 'menu.system.dict', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (12, 11, '2', 0, '字典新建', NULL, NULL, 'system.dict.add', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (13, 11, '2', 0, '字典删除', NULL, NULL, 'system.dict.delete', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (14, 11, '2', 0, '字典编辑', NULL, NULL, 'system.dict.edit', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (15, 11, '2', 0, '字典查看', NULL, NULL, 'system.dict.list', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (16, 7, '2', 0, '查看管理员列表', NULL, NULL, 'admin.list.list', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (17, 7, '2', 0, '新增管理员', NULL, NULL, 'admin.list.add', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (18, 7, '2', 0, '编辑管理员', NULL, NULL, 'admin.list.edit', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (19, 7, '2', 0, '删除管理员', NULL, NULL, 'admin.list.delete', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (20, 8, '2', 0, '管理员分组查看', NULL, NULL, 'admin.group.list', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (21, 8, '2', 0, '管理员分组新增', NULL, NULL, 'admin.group.add', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (22, 8, '2', 0, '管理员分组编辑', NULL, NULL, 'admin.group.edit', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (23, 8, '2', 0, '管理员分组删除', NULL, NULL, 'admin.group.delete', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (24, 8, '2', 0, '分组权限查看', NULL, NULL, 'admin.group.rule', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (25, 8, '2', 0, '管理员权限修改', NULL, NULL, 'admin.group.ruleEdit', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (26, 9, '2', 0, '权限管理查看', NULL, NULL, 'admin.rule.list', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (27, 9, '2', 0, '权限管理新增', NULL, NULL, 'admin.rule.add', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (28, 9, '2', 0, '权限管理编辑', NULL, NULL, 'admin.rule.edit', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (29, 9, '2', 0, '权限管理删除', NULL, NULL, 'admin.rule.delete', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (30, 11, '2', 0, '字典配置', NULL, NULL, 'system.dict.item.list', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (31, 11, '2', 0, '字典配置新增', NULL, NULL, 'system.dict.item.add', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (32, 11, '2', 0, '字典配置编辑', NULL, NULL, 'system.dict.item.edit', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (33, 11, '2', 0, '字典配置删除', NULL, NULL, 'system.dict.item.delete', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (35, 2, '1', 0, '高级列表', '/data/list', NULL, 'data.list', 'menu.components.list', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (36, 2, '1', 0, '单选卡片', '/data/checkcard', NULL, 'data.checkcard', 'menu.components.checkcard', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (39, 0, '0', 997, '会员管理', '/user', 'icon-hexinzichan', 'user', 'menu.user', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (40, 39, '1', 0, '会员列表', '/user/list', NULL, 'user.list', 'menu.user.list', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (43, 0, '0', 994, '在线开发', '/online', 'icon-weixieqingbao', 'online', 'menu.online', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (44, 43, '1', 0, '表格设计', '/online/table', NULL, 'online.table', 'menu.online.table', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (48, 0, '0', 99, 'Xin Admin', 'https://xinadmin.cn/', 'WindowsFilled', 'xinadmin', 'menu.xinadmin', 1, 1, NULL, '2024-09-25 03:12:46');
INSERT INTO `admin_rule` VALUES (49, 10, '1', 5, '系统信息', '/system/info', NULL, 'system.info', 'menu.system.info', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (50, 10, '1', 4, '系统设置', '/system/setting', NULL, 'system.setting', 'menu.system.setting', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (51, 50, '2', 0, '设置分组查看', NULL, NULL, 'system.setting.querySettingGroup', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (52, 50, '2', 1, '设置分组新增', NULL, NULL, 'system.setting.addGroup', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (53, 50, '2', 3, '查询设置父 ID', NULL, NULL, 'system.setting.querySettingPid', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (54, 44, '2', 0, '表格设计查询', NULL, NULL, 'online.table.list', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (55, 44, '2', 1, '表格设计编辑', NULL, NULL, 'online.table.edit', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (56, 44, '2', 2, '表格设计删除', NULL, NULL, 'online.table.delete', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (57, 44, '2', 3, '表格设计', NULL, NULL, 'online.table.devise', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (58, 44, '2', 4, 'CRUD 保存', NULL, NULL, 'online.table.saveData', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (59, 44, '2', 5, '获取 CRUD 数据', NULL, NULL, 'online.table.getData', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (60, 44, '2', 6, 'CRUD 保存并生成', NULL, NULL, 'online.table.crud', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (61, 50, '2', 3, '获取设置列表', NULL, NULL, 'system.setting.list', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (62, 50, '2', 4, '新增设置', NULL, NULL, 'system.setting.add', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (63, 50, '2', 5, '编辑设置', NULL, NULL, 'system.setting.edit', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (64, 50, '2', 6, '删除设置', NULL, NULL, 'system.setting.delete', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (69, 39, '1', 2, '会员分组', '/user/group', NULL, 'user.group', 'menu.user.group', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (70, 39, '1', 2, '权限管理', '/user/rule', NULL, 'user.rule', 'menu.user.rule', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (71, 40, '2', 1, '会员列表查询', NULL, NULL, 'user.list.list', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (72, 40, '2', 2, '会员列表编辑', NULL, NULL, 'user.list.edit', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (73, 40, '2', 3, '会员列表新增', NULL, NULL, 'user.list.add', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (74, 40, '2', 4, '会员列表删除', NULL, NULL, 'user.list.delete', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (75, 69, '2', 1, '会员分组查询', NULL, NULL, 'user.group.list', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (76, 69, '2', 2, '会员分组新增', NULL, NULL, 'user.group.add', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (77, 69, '2', 3, '会员分组编辑', NULL, NULL, 'user.group.edit', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (78, 69, '2', 4, '会员分组删除', NULL, NULL, 'user.group.delete', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (79, 69, '2', 5, '分组权限查看', NULL, NULL, 'user.group.rule', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (80, 69, '2', 6, '分组权限修改', NULL, NULL, 'user.group.ruleEdit', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (81, 39, '1', 4, '会员余额记录', '/user/moneyLog', NULL, 'user.moneyLog', 'menu.user.moneyLog', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (82, 81, '2', 0, '会员余额记录查询', NULL, NULL, 'user.moneyLog.list', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (83, 81, '2', 2, '修改用户余额', NULL, NULL, 'user.moneyLog.add', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (84, 44, '2', 0, '表格设计新增', NULL, NULL, 'online.table.add', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (85, 81, '2', 3, '会员余额记录删除', NULL, NULL, 'user.moneyLog.delete', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (86, 2, '1', 0, '表单示例', '/data/form', NULL, 'data.form', 'menu.components.form', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (87, 7, '2', 1, '修改管理员密码', NULL, NULL, 'admin.list.updatePassword', NULL, 1, 1, NULL, '2024-09-27 08:33:03');
INSERT INTO `admin_rule` VALUES (88, 0, '0', 999, '仪表盘', '/dashboard', 'icon-gongjizhe', 'dashboard', 'menu.dashboard', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (89, 88, '1', 10, '分析页', '/dashboard/analysis', 'icon-fuwuqi', 'dashboard.analysis', 'menu.dashboard.analysis', 1, 1, NULL, '2024-09-25 02:19:27');
INSERT INTO `admin_rule` VALUES (90, 88, '1', 1, '监控页', '/dashboard/monitor', 'RadarChartOutlined', 'dashboard.monitor', 'menu.dashboard.monitor', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (91, 88, '1', 2, '工作台', '/dashboard/workplace', 'RadarChartOutlined', 'dashboard.workplace', 'menu.dashboard.workplace', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (92, 0, '0', 996, '管理员', '/admin', 'icon-jiangshizhuji', 'admin', 'menu.admin', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (93, 2, '1', 5, '高级表格', '/data/table', NULL, 'data.table', 'menu.components.table', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (94, 2, '1', 6, '图标选择', '/data/icon', NULL, 'data.icon', 'menu.components.iconForm', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (102, 10, '1', 4, '文件管理', '/system/file', NULL, 'system.file', 'menu.File', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (103, 102, '2', 0, '文件分组列表', NULL, NULL, 'file.group.list', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (104, 102, '2', 1, '新增文件分组', NULL, NULL, 'file.group.add', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (105, 102, '2', 2, '编辑文件分组', NULL, NULL, 'file.group.edit', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (106, 102, '2', 3, '删除文件分组', NULL, NULL, 'file.group.delete', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (107, 102, '2', 4, '获取文件列表', NULL, NULL, 'file.file.list', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (108, 102, '2', 5, '删除文件', NULL, NULL, 'file.file.delete', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (109, 102, '2', 6, '上传图片文件', NULL, NULL, 'file.upload.image', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (110, 102, '2', 7, '上传视频文件', NULL, NULL, 'file.upload.video', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (111, 102, '2', 8, '上传压缩文件', NULL, NULL, 'file.upload.zip', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (112, 102, '2', 9, '上传音频文件', NULL, NULL, 'file.upload.mp3', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (113, 102, '2', 10, '上传其它文件', NULL, NULL, 'file.upload.annex', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (114, 70, '2', 99, '权限列表', NULL, NULL, 'user.rule.list', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (115, 70, '2', 88, '会员权限新增', NULL, NULL, 'user.rule.add', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (116, 70, '2', 60, '会员权限删除', NULL, NULL, 'user.rule.delete', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (117, 70, '2', 0, '会员权限编辑', NULL, NULL, 'user.rule.edit', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (118, 0, '0', 100, '用户设置', '/admin/setting', 'icon-WEBweihu', 'admin.setting', NULL, 1, 0, NULL, NULL);
INSERT INTO `admin_rule` VALUES (119, 10, '1', 5, '系统监控', '/system/monitor', NULL, 'system.monitor', 'menu.system.monitor', 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (120, 119, '2', 5, '监控列表', NULL, NULL, 'system.monitor.list', NULL, 1, 1, NULL, NULL);
INSERT INTO `admin_rule` VALUES (121, 7, '2', 0, '重置密码', '', '', 'admin.list.resetPassword', '', 1, 1, '2024-09-27 08:30:06', '2024-09-27 08:32:23');
INSERT INTO `admin_rule` VALUES (122, 40, '2', 0, '用户充值', '', '', 'user.list.recharge', '', 1, 1, '2024-09-29 15:51:27', '2024-09-29 15:52:09');

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
INSERT INTO `cache` VALUES ('last_cache_cleanup_time', 'i:1728825204;', 2044185204);

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
INSERT INTO `dict_item` VALUES (9, 16, '按钮', '2', '1', 'default', NULL, NULL);
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
) ENGINE = InnoDB AUTO_INCREMENT = 42 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '文件库记录表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of file
-- ----------------------------
INSERT INTO `file` VALUES (1, 0, 10, 'public', '', 10, '微信图片_20240913160532.jpg', 'file/yWYXNxicbUrpx8tc7GqyOhyi6aOGxUtm6P9gcE1Y.jpg', 58302, 'jpg', '', 1, 0, '2024-09-21 08:57:44', '2024-09-21 08:57:44');
INSERT INTO `file` VALUES (2, 0, 10, 'public', '', 10, '微信图片_20240913160532.jpg', 'file/m8Z9zUbUSJAb4HlFT0p9027o2eZxACXRBw6JmwT6.jpg', 58302, 'jpg', '', 1, 0, '2024-09-21 08:58:56', '2024-09-21 08:58:56');
INSERT INTO `file` VALUES (3, 0, 10, 'public', '', 10, '微信图片_20240913160532.jpg', 'file/a1UVxuI5TdGZzDqiBDnZ47HMhrbF25M9FpkQV14G.jpg', 58302, 'jpg', '', 1, 0, '2024-09-21 09:00:43', '2024-09-21 09:00:43');
INSERT INTO `file` VALUES (4, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/SCnbDoFSAQ2Rw6bqW2NiH4FAT50eTjFn0lqLbSaq.jpg', 12251, 'jpg', '', 1, 0, '2024-09-23 02:12:30', '2024-09-23 02:12:30');
INSERT INTO `file` VALUES (5, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/bcfD136n5ef6Uygndyjb49tQk0DQJsieyXLefReg.jpg', 12251, 'jpg', '', 1, 0, '2024-09-23 02:12:46', '2024-09-23 02:12:46');
INSERT INTO `file` VALUES (6, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/DHFMSZeCkhpAZaG673R7BZ7NNVT3FVjLJWchRvFl.jpg', 12251, 'jpg', '', 1, 0, '2024-09-23 02:16:43', '2024-09-23 02:16:43');
INSERT INTO `file` VALUES (7, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/Qc1Wvd8rOWki2QEd9fgEBtzZPOdh23nwYvJtiraY.jpg', 12251, 'jpg', '', 1, 0, '2024-09-23 02:55:39', '2024-09-23 02:55:39');
INSERT INTO `file` VALUES (8, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/FB2Q3eKyiytz9nhkXGHRKh2qJONALRQv8YgDUy5M.jpg', 12251, 'jpg', '', 1, 0, '2024-09-23 02:55:50', '2024-09-23 02:55:50');
INSERT INTO `file` VALUES (9, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/klHb61DP0id9imS5AGJQOgBt0MCype8RvW1DfihM.jpg', 12251, 'jpg', '', 1, 0, '2024-09-23 02:56:26', '2024-09-23 02:56:26');
INSERT INTO `file` VALUES (10, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/o9uoNabvzkRUY2cfNDQgtcvjSVfddfUlFelLnrd5.jpg', 12251, 'jpg', '', 1, 0, '2024-09-23 02:57:05', '2024-09-23 02:57:05');
INSERT INTO `file` VALUES (11, 0, 10, 'public', '', 10, '微信图片_20240913160532.jpg', 'file/0XO8c5UwPufjsFS8rJkhr9ZvXbW6rcdqIVKf4wbj.jpg', 58302, 'jpg', '', 1, 0, '2024-09-23 02:58:41', '2024-09-23 02:58:41');
INSERT INTO `file` VALUES (12, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/DTkiHTesC7zZ2AD2bcQbHa95fugGR53K39lGPGU0.jpg', 12251, 'jpg', '', 1, 0, '2024-09-23 02:58:51', '2024-09-23 02:58:51');
INSERT INTO `file` VALUES (13, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/Z0BMQxRtZxjPsh4aj15QLqIsLcNoTTt4cds2fGPO.jpg', 12251, 'jpg', '', 1, 0, '2024-09-23 03:53:31', '2024-09-23 03:53:31');
INSERT INTO `file` VALUES (14, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/HFf0wuALPXgPGuwG6YbiATrbfqP3MpK68KBcYu3X.jpg', 12251, 'jpg', '', 1, 0, '2024-09-23 03:53:43', '2024-09-23 03:53:43');
INSERT INTO `file` VALUES (15, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/tM1yakhNXDftXjx0zkfDVkUinSaAfW2Q63hqJu2t.jpg', 12251, 'jpg', '', 1, 0, '2024-09-23 03:55:36', '2024-09-23 03:55:36');
INSERT INTO `file` VALUES (16, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/H2qdFtB1Pa76hZF6XMt07zsWF14QZLxFp5rn0RK3.jpg', 12251, 'jpg', '', 1, 0, '2024-09-23 03:55:45', '2024-09-23 03:55:45');
INSERT INTO `file` VALUES (17, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/2T4AEGq7yEwafSkUxbTADtLYsZbAfITh8aRLcx4C.jpg', 12251, 'jpg', '', 1, 0, '2024-09-23 03:56:38', '2024-09-23 03:56:38');
INSERT INTO `file` VALUES (18, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/TmR4uO56L8Pjt7qW3mhUW1n0voqRbtRqlDplPUv7.jpg', 12251, 'jpg', '', 1, 0, '2024-09-23 04:01:01', '2024-09-23 04:01:01');
INSERT INTO `file` VALUES (19, 0, 10, 'public', '', 10, '微信图片_20240913160532.jpg', 'file/bkJyq9mOnAkQpwOo4QxgNIjn223jkzMae32M08gv.jpg', 58302, 'jpg', '', 1, 0, '2024-09-23 04:01:22', '2024-09-23 04:01:22');
INSERT INTO `file` VALUES (20, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/GgTKojkaf0wzWUYX2njrZDW3hXN7yxd5xO6QDT88.jpg', 12251, 'jpg', '', 1, 0, '2024-09-23 04:02:16', '2024-09-23 04:02:16');
INSERT INTO `file` VALUES (21, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/Bz5ovklbpGwQhDj0Fmx9Zhh8BC6ZozLALpRmzSBP.jpg', 12251, 'jpg', '', 1, 0, '2024-09-23 04:25:00', '2024-09-23 04:25:00');
INSERT INTO `file` VALUES (22, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/3004AyHWpXTSeaActYMEAToAF4FCkXsBHZBtgyjb.jpg', 12251, 'jpg', '', 1, 0, '2024-09-23 05:07:47', '2024-09-23 05:07:47');
INSERT INTO `file` VALUES (23, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/vBrvhTys6J2cwyvtsBrURrX5rcoVE6dAYTMmdXHG.jpg', 12251, 'jpg', '', 1, 0, '2024-09-23 05:08:29', '2024-09-23 05:08:29');
INSERT INTO `file` VALUES (24, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/4lLwiu1UxXgToeCsY2jN8WtVKyoz3uqhuewGqMAL.jpg', 12251, 'jpg', '', 1, 0, '2024-09-23 05:09:26', '2024-09-23 05:09:26');
INSERT INTO `file` VALUES (25, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/5PvZEqjSG5gyXt3w0oFzNsnVPTqT440PPcyN2EOK.jpg', 12251, 'jpg', '', 1, 0, '2024-09-23 05:11:11', '2024-09-23 05:11:11');
INSERT INTO `file` VALUES (26, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/DewxJHU1jvu2wwHBQqRCQqo59dRDL33XmzYHbqka.jpg', 12251, 'jpg', '', 1, 0, '2024-09-23 05:13:25', '2024-09-23 05:13:25');
INSERT INTO `file` VALUES (27, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/yXQRXqcbXt7tlWeF23PNEUvsAFuh0fFxs211kGqD.jpg', 12251, 'jpg', '', 1, 0, '2024-09-23 05:14:17', '2024-09-23 05:14:17');
INSERT INTO `file` VALUES (28, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/acFlQDvl81lnPnv0QkAPRIrXiPWP4C24YsugpoGD.jpg', 12251, 'jpg', '', 1, 0, NULL, NULL);
INSERT INTO `file` VALUES (29, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/WXcQK0eyOcB4k62dtzsyQQkBXJNNWZCAiBkvkrq6.jpg', 12251, 'jpg', '', 1, 0, NULL, NULL);
INSERT INTO `file` VALUES (30, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/Gs5tk0pGj8WQizwuOXf16g96iEyPzHGzeMGwL8Ot.jpg', 12251, 'jpg', '', 1, 0, NULL, NULL);
INSERT INTO `file` VALUES (31, 0, 10, 'public', '', 10, 'zfb_SN1510100110132045.jpg', 'file/dbpPJkuJ5g4avIxRzEt9a3EQS1GcNcWNixBTa59G.jpg', 12251, 'jpg', '', 1, 0, '2024-09-23 05:24:05', '2024-09-23 05:24:05');
INSERT INTO `file` VALUES (32, 0, 10, 'public', '', 10, '微信图片_20240913160532.jpg', 'file/V2JTqQyt7Mlw8L1QoK467gU1kz8G1VFE6m0nfr8S.jpg', 58882, 'jpg', '', 1, 0, '2024-09-23 05:42:06', '2024-09-23 05:42:06');
INSERT INTO `file` VALUES (33, 0, 10, 'public', '', 10, 'f571a6810d1ef316bff7fcc5abb09de.jpg', 'file/BsqWGY2zrPUvEKSWMqtmq23DBO2U6wexXnsQfBZT.jpg', 35505, 'jpg', '', 1, 0, '2024-09-24 05:44:21', '2024-09-24 05:44:21');
INSERT INTO `file` VALUES (34, 1, 20, 'public', '', 10, '2.jpg', 'file/zNaz7W566h4gOP5upJ9AH45A9vt0n40WTFx7mlcH.jpg', 34921, 'jpg', '', 1, 0, '2024-09-27 07:59:29', '2024-09-27 07:59:29');
INSERT INTO `file` VALUES (35, 1, 20, 'public', '', 10, '5.jpg', 'file/2KrduT29eeFN8SemJtYrzT1qFprgzb6N1gcMmCzo.jpg', 38563, 'jpg', '', 1, 0, '2024-09-27 08:09:59', '2024-09-27 08:09:59');
INSERT INTO `file` VALUES (36, 1, 20, 'public', '', 10, '五红花胶.jpg', 'file/Ktd7TR34dVKr8DmrML0b0DQ74eO1ABNnnkrQbkH4.jpg', 30076, 'jpg', '', 1, 0, '2024-09-27 08:14:51', '2024-09-27 08:14:51');
INSERT INTO `file` VALUES (37, 1, 20, 'public', '', 10, '2.jpg', 'file/3DI1pRI8kmqMUvbyerIN3QeBlQKfXDmexHDkPWnr.jpg', 34921, 'jpg', '', 1, 0, '2024-09-27 08:15:40', '2024-09-27 08:15:40');
INSERT INTO `file` VALUES (38, 1, 20, 'public', '', 10, '新建项目 (1).png', 'file/zdM76ewpH3DpS2uZD0e5dUMuP4loQrsdjkNdhRsD.png', 827328, 'png', '', 1, 0, '2024-09-28 11:52:47', '2024-09-28 11:52:47');
INSERT INTO `file` VALUES (39, 1, 20, 'public', '', 10, '新建项目.png', 'file/9pkKavyTtiaXyAvvj6VVqh3DE5AzReKNy6A4uaFE.png', 795778, 'png', '', 1, 0, '2024-09-28 11:53:04', '2024-09-28 11:53:04');
INSERT INTO `file` VALUES (40, 1, 20, 'public', '', 10, '新建项目 (1).png', 'file/gaQc9mqy36Nlx3FmsVQujHlaB1tWnPdYoLGn7RL0.png', 827328, 'png', '', 1, 0, '2024-09-28 11:54:28', '2024-09-28 11:54:28');
INSERT INTO `file` VALUES (41, 1, 20, 'public', '', 10, 'abc-123.jpg', 'file/JPWbyTi01c0VsL5tphotwaNjRAMi5vav2vnAj4Mq.jpg', 263901, 'jpg', '', 1, 0, '2024-10-10 06:11:57', '2024-10-10 06:11:57');

-- ----------------------------
-- Table structure for file_group
-- ----------------------------
DROP TABLE IF EXISTS `file_group`;
CREATE TABLE `file_group`  (
  `group_id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '分组ID',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分组名称',
  `parent_id` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '上级分组ID',
  `sort` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序(数字越小越靠前)',
  `create_time` int UNSIGNED NULL DEFAULT NULL COMMENT '更新时间',
  `update_time` int UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`group_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '文件库分组记录表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of file_group
-- ----------------------------
INSERT INTO `file_group` VALUES (14, '头像文件夹', 0, 0, 1726887616, 1726887616);
INSERT INTO `file_group` VALUES (15, '附件文件夹', 0, 0, 1726887616, 1726887616);
INSERT INTO `file_group` VALUES (16, '视频文件夹', 0, 0, 1726887616, 1726887616);

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
) ENGINE = InnoDB AUTO_INCREMENT = 39 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '数据监控表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of monitor
-- ----------------------------
INSERT INTO `monitor` VALUES (1, '管理员登录', 'App\\Http\\Controllers\\Admin\\IndexController@login', '127.0.0.1', 'localhost', '本机地址 本机地址  ', 'http://localhost:8000/admin/index/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-10-08 13:09:43');
INSERT INTO `monitor` VALUES (36, '管理员登录', 'App\\Http\\Controllers\\Admin\\IndexController@login', '127.0.0.1', 'localhost', '本机地址 本机地址  ', 'http://localhost:8000/admin/index/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-10-09 06:31:29');
INSERT INTO `monitor` VALUES (37, '修改管理员信息', 'App\\Http\\Controllers\\Admin\\AdminController@updateAdmin', '127.0.0.1', 'localhost', '本机地址 本机地址  ', 'http://localhost:8000/admin/admin/updateAdmin', '{\"username\":\"admin\",\"nickname\":\"Admin\",\"email\":\"admin@xinadmin.cn\",\"mobile\":\"1888888888\",\"avatar_id\":41}', '[]', 1, '2024-10-10 06:11:59');
INSERT INTO `monitor` VALUES (38, '管理员登录', 'App\\Http\\Controllers\\Admin\\IndexController@login', '127.0.0.1', 'localhost', '本机地址 本机地址  ', 'http://localhost:8000/admin/index/login', '{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}', '[]', 1, '2024-10-10 12:02:31');

-- ----------------------------
-- Table structure for online_table
-- ----------------------------
DROP TABLE IF EXISTS `online_table`;
CREATE TABLE `online_table`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '在线开发ID',
  `table_name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '表格名',
  `columns` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '表头Json',
  `crud_config` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'crud配置',
  `table_config` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '基础配置',
  `describe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `created_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '在线开发记录表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of online_table
-- ----------------------------
INSERT INTO `online_table` VALUES (5, '123', '[{\"key\":\"text1728648555592\",\"valueType\":\"text\",\"title\":\"文本框0\",\"dataIndex\":\"text0\",\"select\":\"like\",\"validation\":[\"verifyRequired\"],\"hideInForm\":true,\"hideInSearch\":true,\"hideInTable\":true,\"enum\":\"\",\"defaultValue\":\"empty string\",\"isKey\":false,\"null\":false,\"autoIncrement\":false,\"unsign\":false,\"mock\":\"@cword(3, 5)\",\"sqlLength\":50,\"sqlType\":\"varchar\"},{\"key\":\"date1728648556011\",\"valueType\":\"date\",\"title\":\"日期1\",\"dataIndex\":\"date1\",\"select\":\"=\",\"validation\":[\"verifyRequired\"],\"hideInForm\":true,\"hideInSearch\":true,\"hideInTable\":true,\"isKey\":false,\"null\":false,\"autoIncrement\":false,\"unsign\":false,\"mock\":\"@date\",\"sqlType\":\"date\",\"defaultValue\":\"null\"},{\"key\":\"id1728648556415\",\"valueType\":\"text\",\"title\":\"自增主键2\",\"dataIndex\":\"id2\",\"select\":\"=\",\"validation\":[\"verifyRequired\"],\"hideInForm\":true,\"hideInSearch\":true,\"hideInTable\":true,\"isKey\":true,\"null\":true,\"autoIncrement\":true,\"unsign\":true,\"mock\":\"@integer(60, 100)\",\"sqlLength\":10,\"sqlType\":\"int\",\"defaultValue\":\"null\"},{\"key\":\"dateMonth1728648563799\",\"valueType\":\"dateMonth\",\"title\":\"月3\",\"dataIndex\":\"dateMonth3\",\"select\":\"=\",\"validation\":[\"verifyRequired\"],\"hideInForm\":true,\"hideInSearch\":true,\"hideInTable\":true,\"isKey\":false,\"null\":false,\"autoIncrement\":false,\"unsign\":false,\"mock\":\"@date(\\\"y-M\\\")\",\"sqlLength\":50,\"sqlType\":\"varchar\",\"defaultValue\":\"null\"},{\"key\":\"dateQuarter1728648565113\",\"valueType\":\"dateQuarter\",\"title\":\"季度输入4\",\"dataIndex\":\"dateQuarter4\",\"select\":\"=\",\"validation\":[\"verifyRequired\"],\"hideInForm\":true,\"hideInSearch\":true,\"hideInTable\":true,\"isKey\":false,\"null\":false,\"autoIncrement\":false,\"unsign\":false,\"mock\":\"@date\",\"sqlLength\":50,\"sqlType\":\"varchar\",\"defaultValue\":\"null\"},{\"key\":\"dateYear1728648565832\",\"valueType\":\"dateYear\",\"title\":\"年份输入5\",\"dataIndex\":\"dateYear5\",\"select\":\"=\",\"validation\":[\"verifyRequired\"],\"hideInForm\":true,\"hideInSearch\":true,\"hideInTable\":true,\"isKey\":false,\"null\":false,\"autoIncrement\":false,\"unsign\":false,\"mock\":\"@date(\\\"y\\\")\",\"sqlType\":\"varchar\",\"defaultValue\":\"null\"},{\"key\":\"digit1728648566228\",\"valueType\":\"digit\",\"title\":\"数字输入框6\",\"dataIndex\":\"digit6\",\"select\":\"=\",\"validation\":[\"verifyEmail\"],\"hideInForm\":true,\"hideInSearch\":true,\"hideInTable\":true,\"enum\":\"\",\"defaultValue\":\"null\",\"isKey\":false,\"null\":false,\"autoIncrement\":false,\"unsign\":false,\"mock\":\"@integer(60, 100)\",\"sqlLength\":10,\"sqlType\":\"int\"},{\"key\":\"switch1728648566563\",\"valueType\":\"switch\",\"title\":\"开关7\",\"dataIndex\":\"switch7\",\"select\":\"=\",\"validation\":[\"verifyNumber\"],\"hideInForm\":true,\"hideInSearch\":true,\"hideInTable\":true,\"enum\":\"\",\"defaultValue\":\"null\",\"isKey\":false,\"null\":false,\"autoIncrement\":false,\"unsign\":false,\"mock\":\"@boolean\",\"sqlLength\":1,\"sqlType\":\"int\"},{\"key\":\"radio1728648566864\",\"valueType\":\"radio\",\"title\":\"单选框8\",\"dataIndex\":\"radio8\",\"select\":\"=\",\"validation\":[\"verifyNumber\"],\"hideInForm\":true,\"hideInSearch\":true,\"hideInTable\":true,\"enum\":\"1:one\\n2:two\\n3:three\",\"defaultValue\":\"1\",\"isKey\":false,\"null\":false,\"autoIncrement\":false,\"unsign\":false,\"mock\":\"@integer(1, 3)\",\"sqlLength\":2,\"sqlType\":\"int\"},{\"key\":\"radioButton1728648567213\",\"valueType\":\"radioButton\",\"title\":\"按钮单选框9\",\"dataIndex\":\"radioButton9\",\"select\":\"=\",\"validation\":[\"verifyRequired\"],\"hideInForm\":true,\"hideInSearch\":true,\"hideInTable\":true,\"enum\":\"1:one\\n2:two\\n3:three\",\"defaultValue\":\"1\",\"isKey\":false,\"null\":false,\"autoIncrement\":false,\"unsign\":false,\"mock\":\"@integer(1, 3)\",\"sqlLength\":2,\"sqlType\":\"int\"}]', '{\"name\":\"TableName\",\"controllerPath\":\"\",\"modelPath\":\"\",\"validatePath\":\"\",\"pagePath\":\"\"}', '{\"bordered\":false,\"size\":\"middle\",\"showHeader\":false,\"rowSelectionShow\":false,\"addShow\":false,\"deleteShow\":false,\"editShow\":false,\"optionsShow\":true,\"options\":{\"density\":true,\"search\":false,\"fullScreen\":false,\"setting\":true,\"reload\":true},\"headerTitle\":\"表格标题\",\"tooltip\":\"表格 tooltip\",\"searchShow\":true,\"search\":{\"searchText\":\"查询\",\"resetText\":\"重置\",\"span\":6,\"layout\":\"vertical\",\"filterType\":\"query\"},\"paginationShow\":true,\"pagination\":{\"size\":\"default\"}}', '123', '2024-10-10 14:17:00', '2024-10-13 15:18:07');

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
  `create_time` int UNSIGNED NULL DEFAULT NULL COMMENT '更新时间',
  `update_time` int UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `key`(`key`) USING BTREE,
  INDEX `group_id`(`group_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '设置记录表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of setting
-- ----------------------------
INSERT INTO `setting` VALUES (1, 'title', '网站标题', '网站标题，用于展示在网站logo旁边和登录页面以及网页title中', 'Xin Admin', 'input', '', '', 3, 0, 1726887616, 1726887616);
INSERT INTO `setting` VALUES (4, 'logo', '网站 LOGO', '网站的LOGO，用于标识网站', 'https://file.xinadmin.cn/file/favicons.ico', 'input', '', '', 3, 0, 1726887616, 1726887616);
INSERT INTO `setting` VALUES (5, 'subtitle', '网站副标题', '网站副标题，展示在登录页面标题的下面', 'Xin Admin 快速开发框架', 'input', '', '', 3, 0, 1726887616, 1726887616);
INSERT INTO `setting` VALUES (6, 'login', '邮箱登录', '是否开启邮箱登录', '0', 'switch', '', '', 4, 99, 1726887616, 1726887616);
INSERT INTO `setting` VALUES (7, 'Port', '服务器端口', '邮箱服务器端口', '465', 'input', '', '', 4, 80, 1726887616, 1726887616);
INSERT INTO `setting` VALUES (8, 'SMTPSecure', '邮箱协议', '邮箱协议 TLS 或者ssl协议', 'ssl', 'input', '', '', 4, 70, 1726887616, 1726887616);
INSERT INTO `setting` VALUES (9, 'username', 'SMTP 用户名', '邮箱 SMTP 用户名', '', 'input', '', '', 4, 60, 1726887616, 1726887616);
INSERT INTO `setting` VALUES (10, 'password', 'SMTP 密码', '邮箱 SMTP 密码', '', 'password', '', '', 4, 60, 1726887616, 1726887616);
INSERT INTO `setting` VALUES (11, 'smtp', 'SMTP服务器', 'SMTP服务器 地址', '', 'input', '', '', 4, 50, 1726887616, 1726887616);
INSERT INTO `setting` VALUES (12, 'char', '邮件编码', '邮件编码，UTF-8', 'UTF-8', 'input', '', '', 4, 50, 1726887616, 1726887616);

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
  `create_time` int UNSIGNED NULL DEFAULT NULL COMMENT '更新时间',
  `update_time` int UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `key`(`key`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '设置分组表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of setting_group
-- ----------------------------
INSERT INTO `setting_group` VALUES (3, 0, '网站设置', 'web', '2', 1726887616, 1726887616);
INSERT INTO `setting_group` VALUES (4, 0, '邮箱设置', 'mail', '1', 1726887616, 1726887616);

-- ----------------------------
-- Table structure for token
-- ----------------------------
DROP TABLE IF EXISTS `token`;
CREATE TABLE `token`  (
  `token` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Token',
  `type` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '类型',
  `user_id` int UNSIGNED NOT NULL COMMENT '用户ID',
  `expire_time` int UNSIGNED NULL DEFAULT NULL COMMENT '过期时间',
  `create_time` int UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  INDEX `token`(`token`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户Token表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of token
-- ----------------------------
INSERT INTO `token` VALUES ('85909993124a34ab1b91381284f361936be55141', 'user-refresh', 1, 1729748706, 1727156706);
INSERT INTO `token` VALUES ('0a4a86fa59d4dc50d17ae076a7f38b0093fcdc7b', 'admin-refresh', 1, 1730984958, 1728392958);
INSERT INTO `token` VALUES ('06df4af8b6118fa056d727a15e90f6627df92a54', 'admin-refresh', 1, 1730984961, 1728392961);
INSERT INTO `token` VALUES ('d33ca58245d13b33ae72284465e90e289c0a9b2f', 'admin-refresh', 1, 1730984966, 1728392966);
INSERT INTO `token` VALUES ('7e8533e0e2ac07822bffb1fee15fb39dc18f651f', 'admin-refresh', 1, 1730984983, 1728392983);
INSERT INTO `token` VALUES ('7bd47fd646e4f7a97af246fcd3f5a42163b6155d', 'admin-refresh', 1, 1731047489, 1728455489);
INSERT INTO `token` VALUES ('e82b034b02729ac944e0f6b153b46206c691445d', 'admin-refresh', 1, 1731153751, 1728561751);
INSERT INTO `token` VALUES ('2a5d113e4ed282e436a8f5d69e8874dda62bc930', 'admin', 1, 1728833403, 1728832803);

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
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
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (1, '15999999999', 'user', 'liu@xinadmin.cn', '$2y$10$uT69rqj3E65JG4K.eYpFduGtw.zfJUNVvatouqlgmx2BDdlexkaeu', '小刘同学', 33, '0', NULL, 1, 19900, 0, '', '1', NULL, '2024-10-08 12:59:55');

-- ----------------------------
-- Table structure for user_group
-- ----------------------------
DROP TABLE IF EXISTS `user_group`;
CREATE TABLE `user_group`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '上级分组ID',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分组名称',
  `rules` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '权限ID',
  `create_time` int UNSIGNED NULL DEFAULT NULL COMMENT '更新时间',
  `update_time` int UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '会员分组表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of user_group
-- ----------------------------
INSERT INTO `user_group` VALUES (1, 0, '普通会员', '*', 1726887616, 1726887616);
INSERT INTO `user_group` VALUES (2, 0, '访客', '1,9,10,11,12', 1726887616, 1726887616);

-- ----------------------------
-- Table structure for user_money_log
-- ----------------------------
DROP TABLE IF EXISTS `user_money_log`;
CREATE TABLE `user_money_log`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '记录ID',
  `user_id` int UNSIGNED NOT NULL COMMENT '用户ID',
  `scene` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '余额变动场景',
  `money` float NOT NULL DEFAULT 0 COMMENT '余额变动场景',
  `describe` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述/说明',
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户余额变动明细表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of user_money_log
-- ----------------------------
INSERT INTO `user_money_log` VALUES (1, 1, '1', 10000, '管理员充值: ceshi', '2024-09-29 15:45:04');
INSERT INTO `user_money_log` VALUES (2, 1, '1', -6000, '管理员充值: 123', '2024-09-29 15:45:46');
INSERT INTO `user_money_log` VALUES (3, 1, '1', 1000, '123', '2024-09-29 15:48:21');
INSERT INTO `user_money_log` VALUES (4, 1, '1', 30000, '测试', '2024-09-29 15:50:51');
INSERT INTO `user_money_log` VALUES (5, 1, '1', 10000, '133', '2024-09-29 15:51:30');
INSERT INTO `user_money_log` VALUES (6, 1, '1', 9900, '123', '2024-10-08 12:59:55');

-- ----------------------------
-- Table structure for user_rule
-- ----------------------------
DROP TABLE IF EXISTS `user_rule`;
CREATE TABLE `user_rule`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int NOT NULL DEFAULT 0 COMMENT '父ID',
  `type` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '类型 0：页面 1：数据 2：按钮',
  `sort` int NOT NULL DEFAULT 0 COMMENT '排序',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `path` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '路由地址',
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '图标',
  `key` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '权限标识',
  `locale` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT '' COMMENT '国际化标识',
  `status` int NOT NULL DEFAULT 1 COMMENT '启用状态',
  `show` int NOT NULL DEFAULT 1 COMMENT '显示状态',
  `create_time` int UNSIGNED NULL DEFAULT NULL COMMENT '更新时间',
  `update_time` int UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `key`(`key`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 22 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '会员权限规则表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of user_rule
-- ----------------------------
INSERT INTO `user_rule` VALUES (1, 0, '0', 99, '首页', '/', 'HomeOutlined', 'index', 'menu.index', 1, 1, 1726887616, 1726887616);
INSERT INTO `user_rule` VALUES (9, 0, '0', 0, '代码仓库', '/git', 'StarOutlined', 'git', 'menu.git', 1, 1, 1726887616, 1726887616);
INSERT INTO `user_rule` VALUES (10, 9, '1', 0, 'Github', 'https://github.com/Xineny-liu/xinadmin', NULL, 'xinadmin', 'menu.github', 1, 1, 1726887616, 1726887616);
INSERT INTO `user_rule` VALUES (11, 9, '1', 1, 'Gitee', 'https://gitee.com/xineny/xin-admin', NULL, 'gitee', 'menu.gitee', 1, 1, 1726887616, 1726887616);
INSERT INTO `user_rule` VALUES (12, 0, '0', 0, '官方文档', 'https://xinadmin.cn', 'FileSearchOutlined', 'ttps:..xinadmin.cn', 'menu.xinadmin', 1, 1, 1726887616, 1726887616);
INSERT INTO `user_rule` VALUES (13, 0, '0', 98, '会员中心', '/user', 'UserOutlined', 'user', 'menu.users', 1, 1, 1726887616, 1726887616);
INSERT INTO `user_rule` VALUES (17, 13, '1', 99, '个人中心', '/user', NULL, 'user', 'menu.user', 1, 1, 1726887616, 1726887616);
INSERT INTO `user_rule` VALUES (18, 13, '1', 98, '账户设置', '/user/userSetting', NULL, 'user.userSetting', 'menu.userSetting', 1, 1, 1726887616, 1726887616);
INSERT INTO `user_rule` VALUES (19, 13, '1', 97, '修改密码', '/user/setPassword', NULL, 'user.setPassword', 'menu.setPassword', 1, 1, 1726887616, 1726887616);
INSERT INTO `user_rule` VALUES (20, 13, '1', 0, '资产记录', '/user/log', NULL, 'user.log', 'menu.log', 1, 1, 1726887616, 1726887616);
INSERT INTO `user_rule` VALUES (21, 20, '1', 0, '余额记录', '/user/log/moneyLog', NULL, 'user.log.moneyLog', 'menu.log.moneyLog', 1, 1, 1726887616, 1726887616);

-- ----------------------------
-- Table structure for verification_code
-- ----------------------------
DROP TABLE IF EXISTS `verification_code`;
CREATE TABLE `verification_code`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '类型',
  `code` int UNSIGNED NOT NULL COMMENT '验证码',
  `status` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态0：未发送 1：已发送 2：已验证',
  `interval` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '有效期',
  `data` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '接收方',
  `create_time` int UNSIGNED NULL DEFAULT NULL COMMENT '更新时间',
  `update_time` int UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '验证码记录表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of verification_code
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
