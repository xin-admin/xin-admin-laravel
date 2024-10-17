-- MySQL dump 10.13  Distrib 5.7.44, for Linux (x86_64)
--
-- Host: localhost    Database: laravel
-- ------------------------------------------------------
-- Server version	5.7.44-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户昵称',
  `avatar_id` int(11) NOT NULL COMMENT '头像',
  `sex` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '性别',
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `mobile` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `status` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态:0=禁用,1=启用',
  `group_id` int(11) NOT NULL DEFAULT '0' COMMENT '分组ID',
  `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
  `created_at` datetime DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `username` (`username`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='管理员表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin','Admin',39,'0','admin@xinadmin.cn','1888888888','1',1,'$2y$10$6u8Yqd90Qpc4P/xJ3F5J1.5.NiCB2CZ8JgC9MkEzCcGCQ0esDExCC',NULL,'2024-09-28 11:53:05'),(2,'test','text',40,'0','2@qq.cin','15899999999','1',2,'$2y$10$c2uQdzrxDBrYRGo8NNhAwuKx.pXT1AtdzF2PKux9F./zlL2vSguym','2024-09-27 08:18:39','2024-09-28 11:54:29');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_group`
--

DROP TABLE IF EXISTS `admin_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分组ID',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分组名称',
  `rules` text COLLATE utf8mb4_unicode_ci COMMENT '权限ID',
  `created_at` datetime DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='管理分组表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_group`
--

LOCK TABLES `admin_group` WRITE;
/*!40000 ALTER TABLE `admin_group` DISABLE KEYS */;
INSERT INTO `admin_group` VALUES (1,0,'系统管理员','*',NULL,NULL),(2,1,'二级管理员','1,9,10,11,12',NULL,NULL),(3,1,'三级管理员','43,44,48,54,55,56,57,58,59,60,84,118',NULL,'2024-09-25 08:13:50'),(4,4,'哇哈哈1',NULL,'2024-09-25 07:13:14','2024-09-25 07:18:38');
/*!40000 ALTER TABLE `admin_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_rule`
--

DROP TABLE IF EXISTS `admin_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父ID',
  `type` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '类型 0：页面 1：数据 2：按钮',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `path` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '路由地址',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图标',
  `key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '权限标识',
  `local` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '国际化标识',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '启用状态',
  `show` int(11) NOT NULL DEFAULT '1' COMMENT '显示状态',
  `created_at` datetime DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `key` (`key`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='管理员权限规则表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_rule`
--

LOCK TABLES `admin_rule` WRITE;
/*!40000 ALTER TABLE `admin_rule` DISABLE KEYS */;
INSERT INTO `admin_rule` VALUES (2,0,'0',998,'示例组件','/data','icon-daichuzhishijianzongshu','data','menu.components',1,1,NULL,NULL),(3,2,'1',0,'定义列表','/data/descriptions','','data.descriptions','menu.components.descriptions',1,1,NULL,NULL),(7,92,'1',0,'管理员列表','/admin/list',NULL,'admin.list','menu.admin.list',1,1,NULL,NULL),(8,92,'1',1,'管理员分组','/admin/group',NULL,'admin.group','menu.admin.group',1,1,NULL,NULL),(9,92,'1',2,'权限菜单管理','/admin/rule','PieChartOutlined','admin.rule','menu.admin.rule',1,1,NULL,'2024-09-25 03:12:13'),(10,0,'0',995,'系统管理','/system','icon-henjiqingli','system','menu.system',1,1,NULL,NULL),(11,10,'1',3,'字典管理','/system/dict',NULL,'system.dict','menu.system.dict',1,1,NULL,NULL),(12,11,'2',0,'字典新建',NULL,NULL,'system.dict.add',NULL,1,1,NULL,NULL),(13,11,'2',0,'字典删除',NULL,NULL,'system.dict.delete',NULL,1,1,NULL,NULL),(14,11,'2',0,'字典编辑',NULL,NULL,'system.dict.edit',NULL,1,1,NULL,NULL),(15,11,'2',0,'字典查看',NULL,NULL,'system.dict.list',NULL,1,1,NULL,NULL),(16,7,'2',0,'查看管理员列表',NULL,NULL,'admin.list.list',NULL,1,1,NULL,NULL),(17,7,'2',0,'新增管理员',NULL,NULL,'admin.list.add',NULL,1,1,NULL,NULL),(18,7,'2',0,'编辑管理员',NULL,NULL,'admin.list.edit',NULL,1,1,NULL,NULL),(19,7,'2',0,'删除管理员',NULL,NULL,'admin.list.delete',NULL,1,1,NULL,NULL),(20,8,'2',0,'管理员分组查看',NULL,NULL,'admin.group.list',NULL,1,1,NULL,NULL),(21,8,'2',0,'管理员分组新增',NULL,NULL,'admin.group.add',NULL,1,1,NULL,NULL),(22,8,'2',0,'管理员分组编辑',NULL,NULL,'admin.group.edit',NULL,1,1,NULL,NULL),(23,8,'2',0,'管理员分组删除',NULL,NULL,'admin.group.delete',NULL,1,1,NULL,NULL),(24,8,'2',0,'分组权限查看',NULL,NULL,'admin.group.rule',NULL,1,1,NULL,NULL),(25,8,'2',0,'管理员权限修改',NULL,NULL,'admin.group.ruleEdit',NULL,1,1,NULL,NULL),(26,9,'2',0,'权限管理查看',NULL,NULL,'admin.rule.list',NULL,1,1,NULL,NULL),(27,9,'2',0,'权限管理新增',NULL,NULL,'admin.rule.add',NULL,1,1,NULL,NULL),(28,9,'2',0,'权限管理编辑',NULL,NULL,'admin.rule.edit',NULL,1,1,NULL,NULL),(29,9,'2',0,'权限管理删除',NULL,NULL,'admin.rule.delete',NULL,1,1,NULL,NULL),(30,11,'2',0,'字典配置',NULL,NULL,'system.dict.item.list',NULL,1,1,NULL,NULL),(31,11,'2',0,'字典配置新增',NULL,NULL,'system.dict.item.add',NULL,1,1,NULL,NULL),(32,11,'2',0,'字典配置编辑',NULL,NULL,'system.dict.item.edit',NULL,1,1,NULL,NULL),(33,11,'2',0,'字典配置删除',NULL,NULL,'system.dict.item.delete',NULL,1,1,NULL,NULL),(35,2,'1',0,'高级列表','/data/list',NULL,'data.list','menu.components.list',1,1,NULL,NULL),(36,2,'1',0,'单选卡片','/data/checkcard',NULL,'data.checkcard','menu.components.checkcard',1,1,NULL,NULL),(39,0,'0',997,'会员管理','/user','icon-hexinzichan','user','menu.user',1,1,NULL,NULL),(40,39,'1',0,'会员列表','/user/list',NULL,'user.list','menu.user.list',1,1,NULL,NULL),(43,0,'0',994,'在线开发','/online','icon-weixieqingbao','online','menu.online',1,1,NULL,NULL),(44,43,'1',0,'表格设计','/online/table',NULL,'online.table','menu.online.table',1,1,NULL,NULL),(48,0,'0',99,'Xin Admin','https://xinadmin.cn/','WindowsFilled','xinadmin','menu.xinadmin',1,1,NULL,'2024-09-25 03:12:46'),(49,10,'1',5,'系统信息','/system/info',NULL,'system.info','menu.system.info',1,1,NULL,NULL),(50,10,'1',4,'系统设置','/system/setting',NULL,'system.setting','menu.system.setting',1,1,NULL,NULL),(51,50,'2',0,'设置分组查看',NULL,NULL,'system.setting.querySettingGroup',NULL,1,1,NULL,NULL),(52,50,'2',1,'设置分组新增',NULL,NULL,'system.setting.addGroup',NULL,1,1,NULL,NULL),(53,50,'2',3,'查询设置父 ID',NULL,NULL,'system.setting.querySettingPid',NULL,1,1,NULL,NULL),(54,44,'2',0,'表格设计查询',NULL,NULL,'online.table.list',NULL,1,1,NULL,NULL),(55,44,'2',1,'表格设计编辑',NULL,NULL,'online.table.edit',NULL,1,1,NULL,NULL),(56,44,'2',2,'表格设计删除',NULL,NULL,'online.table.delete',NULL,1,1,NULL,NULL),(57,44,'2',3,'表格设计',NULL,NULL,'online.table.devise',NULL,1,1,NULL,NULL),(58,44,'2',4,'CRUD 保存',NULL,NULL,'online.table.saveData',NULL,1,1,NULL,NULL),(59,44,'2',5,'获取 CRUD 数据',NULL,NULL,'online.table.getData',NULL,1,1,NULL,NULL),(60,44,'2',6,'CRUD 保存并生成',NULL,NULL,'online.table.crud',NULL,1,1,NULL,NULL),(61,50,'2',3,'获取设置列表',NULL,NULL,'system.setting.list',NULL,1,1,NULL,NULL),(62,50,'2',4,'新增设置',NULL,NULL,'system.setting.add',NULL,1,1,NULL,NULL),(63,50,'2',5,'编辑设置',NULL,NULL,'system.setting.edit',NULL,1,1,NULL,NULL),(64,50,'2',6,'删除设置',NULL,NULL,'system.setting.delete',NULL,1,1,NULL,NULL),(69,39,'1',2,'会员分组','/user/group',NULL,'user.group','menu.user.group',1,1,NULL,NULL),(70,39,'1',2,'权限管理','/user/rule',NULL,'user.rule','menu.user.rule',1,1,NULL,NULL),(71,40,'2',1,'会员列表查询',NULL,NULL,'user.list.list',NULL,1,1,NULL,NULL),(72,40,'2',2,'会员列表编辑',NULL,NULL,'user.list.edit',NULL,1,1,NULL,NULL),(73,40,'2',3,'会员列表新增',NULL,NULL,'user.list.add',NULL,1,1,NULL,NULL),(74,40,'2',4,'会员列表删除',NULL,NULL,'user.list.delete',NULL,1,1,NULL,NULL),(75,69,'2',1,'会员分组查询',NULL,NULL,'user.group.list',NULL,1,1,NULL,NULL),(76,69,'2',2,'会员分组新增',NULL,NULL,'user.group.add',NULL,1,1,NULL,NULL),(77,69,'2',3,'会员分组编辑',NULL,NULL,'user.group.edit',NULL,1,1,NULL,NULL),(78,69,'2',4,'会员分组删除',NULL,NULL,'user.group.delete',NULL,1,1,NULL,NULL),(79,69,'2',5,'分组权限查看',NULL,NULL,'user.group.rule',NULL,1,1,NULL,NULL),(80,69,'2',6,'分组权限修改',NULL,NULL,'user.group.ruleEdit',NULL,1,1,NULL,NULL),(81,39,'1',4,'会员余额记录','/user/moneyLog',NULL,'user.moneyLog','menu.user.moneyLog',1,1,NULL,NULL),(82,81,'2',0,'会员余额记录查询',NULL,NULL,'user.moneyLog.list',NULL,1,1,NULL,NULL),(83,81,'2',2,'修改用户余额',NULL,NULL,'user.moneyLog.add',NULL,1,1,NULL,NULL),(84,44,'2',0,'表格设计新增',NULL,NULL,'online.table.add',NULL,1,1,NULL,NULL),(85,81,'2',3,'会员余额记录删除',NULL,NULL,'user.moneyLog.delete',NULL,1,1,NULL,NULL),(86,2,'1',0,'表单示例','/data/form',NULL,'data.form','menu.components.form',1,1,NULL,NULL),(87,7,'2',1,'修改管理员密码',NULL,NULL,'admin.list.updatePassword',NULL,1,1,NULL,'2024-09-27 08:33:03'),(88,0,'0',999,'仪表盘','/dashboard','icon-gongjizhe','dashboard','menu.dashboard',1,1,NULL,NULL),(89,88,'1',10,'分析页','/dashboard/analysis','icon-fuwuqi','dashboard.analysis','menu.dashboard.analysis',1,1,NULL,'2024-09-25 02:19:27'),(90,88,'1',1,'监控页','/dashboard/monitor','RadarChartOutlined','dashboard.monitor','menu.dashboard.monitor',1,1,NULL,NULL),(91,88,'1',2,'工作台','/dashboard/workplace','RadarChartOutlined','dashboard.workplace','menu.dashboard.workplace',1,1,NULL,NULL),(92,0,'0',996,'管理员','/admin','icon-jiangshizhuji','admin','menu.admin',1,1,NULL,NULL),(93,2,'1',5,'高级表格','/data/table',NULL,'data.table','menu.components.table',1,1,NULL,NULL),(94,2,'1',6,'图标选择','/data/icon',NULL,'data.icon','menu.components.iconForm',1,1,NULL,NULL),(102,10,'1',4,'文件管理','/system/file',NULL,'system.file','menu.File',1,1,NULL,NULL),(103,102,'2',0,'文件分组列表',NULL,NULL,'file.group.list',NULL,1,1,NULL,NULL),(104,102,'2',1,'新增文件分组',NULL,NULL,'file.group.add',NULL,1,1,NULL,NULL),(105,102,'2',2,'编辑文件分组',NULL,NULL,'file.group.edit',NULL,1,1,NULL,NULL),(106,102,'2',3,'删除文件分组',NULL,NULL,'file.group.delete',NULL,1,1,NULL,NULL),(107,102,'2',4,'获取文件列表',NULL,NULL,'file.file.list',NULL,1,1,NULL,NULL),(108,102,'2',5,'删除文件',NULL,NULL,'file.file.delete',NULL,1,1,NULL,NULL),(109,102,'2',6,'上传图片文件',NULL,NULL,'file.upload.image',NULL,1,1,NULL,NULL),(110,102,'2',7,'上传视频文件',NULL,NULL,'file.upload.video',NULL,1,1,NULL,NULL),(111,102,'2',8,'上传压缩文件',NULL,NULL,'file.upload.zip',NULL,1,1,NULL,NULL),(112,102,'2',9,'上传音频文件',NULL,NULL,'file.upload.mp3',NULL,1,1,NULL,NULL),(113,102,'2',10,'上传其它文件',NULL,NULL,'file.upload.annex',NULL,1,1,NULL,NULL),(114,70,'2',99,'权限列表',NULL,NULL,'user.rule.list',NULL,1,1,NULL,NULL),(115,70,'2',88,'会员权限新增',NULL,NULL,'user.rule.add',NULL,1,1,NULL,NULL),(116,70,'2',60,'会员权限删除',NULL,NULL,'user.rule.delete',NULL,1,1,NULL,NULL),(117,70,'2',0,'会员权限编辑',NULL,NULL,'user.rule.edit',NULL,1,1,NULL,NULL),(118,0,'0',100,'用户设置','/admin/setting','icon-WEBweihu','admin.setting',NULL,1,0,NULL,NULL),(119,10,'1',5,'系统监控','/system/monitor',NULL,'system.monitor','menu.system.monitor',1,1,NULL,NULL),(120,119,'2',5,'监控列表',NULL,NULL,'system.monitor.list',NULL,1,1,NULL,NULL),(121,7,'2',0,'重置密码','','','admin.list.resetPassword','',1,1,'2024-09-27 08:30:06','2024-09-27 08:32:23'),(122,40,'2',0,'用户充值','','','user.list.recharge','',1,1,'2024-09-29 15:51:27','2024-09-29 15:52:09'),(123,40,'2',0,'重置用户密码','','','user.list.resetPassword','',1,1,'2024-09-30 02:18:00','2024-09-30 02:21:22');
/*!40000 ALTER TABLE `admin_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('last_cache_cleanup_time','i:1727622168;',2042982168);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dict`
--

DROP TABLE IF EXISTS `dict`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dict` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '字典名',
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default' COMMENT '类型',
  `describe` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '字典描述',
  `code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '字典编码',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `code` (`code`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='数据字典表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dict`
--

LOCK TABLES `dict` WRITE;
/*!40000 ALTER TABLE `dict` DISABLE KEYS */;
INSERT INTO `dict` VALUES (12,'性别','default','性别','sex',1726887616,1726887616),(13,'人物','default','任务','pop',1726887616,1726887616),(14,'状态','default','状态','status',1726887616,1726887616),(16,'权限类型','tag','权限类型','ruleType',1726887616,1726887616),(17,'字段类型','default','前端表单类型字典，请不要修改','valueType',1726887616,1726887616),(19,'查询操作符','default','系统查询操作符，请不要修改','select',1726887616,1726887616),(20,'验证规则','default','CRUD 验证规则，请不要修改','validation',1726887616,1726887616),(21,'余额变动记录类型','tag','余额变动记录类型','moneyLog',1726887616,1726887616);
/*!40000 ALTER TABLE `dict` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dict_item`
--

DROP TABLE IF EXISTS `dict_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dict_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `dict_id` int(10) unsigned NOT NULL COMMENT '字典ID',
  `label` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '字典项名称',
  `value` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '数据值',
  `switch` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '是否启用：0：禁用，1：启用',
  `status` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default' COMMENT '状态：（default,success,error,processing,warning）',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='字典项列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dict_item`
--

LOCK TABLES `dict_item` WRITE;
/*!40000 ALTER TABLE `dict_item` DISABLE KEYS */;
INSERT INTO `dict_item` VALUES (1,14,'男','0','1','default',1726887616,1726887616),(2,14,'女','1','1','default',1726887616,1726887616),(3,12,'男','0','1','success',1726887616,1726887616),(5,12,'女','1','1','error',1726887616,1726887616),(6,14,'变态','3','1','default',1726887616,1726887616),(7,16,'一级菜单','0','1','processing',1726887616,1726887616),(8,16,'子菜单','1','1','success',1726887616,1726887616),(9,16,'按钮','2','1','default',1726887616,1726887616),(10,17,'文本框','text','1','default',1726887616,1726887616),(11,17,'数字输入框','digit','1','default',1726887616,1726887616),(12,17,'日期','date','1','default',1726887616,1726887616),(13,17,'金额输入框','money','1','default',1726887616,1726887616),(14,17,'文本域','textarea','1','default',1726887616,1726887616),(15,17,'下拉框','select','1','default',1726887616,1726887616),(17,17,'多选框','checkbox','1','default',1726887616,1726887616),(18,17,'星级组件','rate','1','default',1726887616,1726887616),(19,17,'单选框','radio','1','default',1726887616,1726887616),(20,17,'按钮单选框','radioButton','1','default',1726887616,1726887616),(21,17,'开关','switch','1','default',1726887616,1726887616),(22,17,'日期时间','dateTime','1','default',1726887616,1726887616),(23,18,'字符串(TEXT)','text','1','default',1726887616,1726887616),(24,18,'字符型(CHAR)','char','1','default',1726887616,1726887616),(25,18,'变长字符型(VARCHAR)','varchar','1','default',1726887616,1726887616),(26,18,'整数型(INT)','int','1','default',1726887616,1726887616),(27,18,'长整数型(BIGINT)','bigint','1','default',1726887616,1726887616),(28,18,'小数型(DECIMAL)','decimal','1','default',1726887616,1726887616),(29,18,'浮点型(FLOAT)','float','1','default',1726887616,1726887616),(30,18,'双精度浮点型(DOUBLE)','double','1','default',1726887616,1726887616),(31,18,'布尔型(BOOLEAN)','boolean','1','default',1726887616,1726887616),(32,18,'日期型(DATE)','date','1','default',1726887616,1726887616),(33,18,'时间型(TIME)','time','1','default',1726887616,1726887616),(34,18,'日期时间型(DATETIME)','datetime','1','default',1726887616,1726887616),(35,18,'时间戳(TIMESTAMP)','timestamp','1','default',1726887616,1726887616),(36,18,'二进制 large 对象 (BLOB)','blob','1','default',1726887616,1726887616),(37,18,'字符 large 对象 (CLOB)','clob','1','default',1726887616,1726887616),(42,19,'等于','=','1','default',1726887616,1726887616),(43,19,'大于','>','1','default',1726887616,1726887616),(44,19,'小于','<','1','default',1726887616,1726887616),(45,19,'大于等于','>=','1','default',1726887616,1726887616),(46,19,'小于等于','<=','1','default',1726887616,1726887616),(47,19,'不等于','<>','1','default',1726887616,1726887616),(48,19,'包含','like','1','default',1726887616,1726887616),(49,19,'日期查询','date','1','default',1726887616,1726887616),(50,20,'必填','verifyRequired','1','default',1726887616,1726887616),(51,20,'纯数字','verifyNumber','1','default',1726887616,1726887616),(52,20,'邮箱','verifyEmail','1','default',1726887616,1726887616),(53,20,'Url','verifyUrl','1','default',1726887616,1726887616),(54,20,'整数','verifyInteger','1','default',1726887616,1726887616),(55,20,'手机号','verifyMobile','1','default',1726887616,1726887616),(56,20,'身份证','verifyIdCard','1','default',1726887616,1726887616),(57,20,'字符串','verifyString','1','default',1726887616,1726887616),(58,17,'自增主键','id','1','default',1726887616,1726887616),(61,21,'管理员操作','0','1','processing',1726887616,1726887616),(62,21,'消费','1','1','error',1726887616,1726887616),(63,21,'签到奖励','2','1','success',1726887616,1726887616),(64,17,'密码框','password','1','default',1726887616,1726887616),(65,17,'月','dateMonth','1','default',1726887616,1726887616),(66,17,'季度','dateQuarter','1','default',1726887616,1726887616),(67,17,'年','dateYear','1','default',1726887616,1726887616),(68,17,'颜色选择器','color','1','default',1726887616,1726887616);
/*!40000 ALTER TABLE `dict_item` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `file`
--

DROP TABLE IF EXISTS `file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `file` (
  `file_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件分组ID',
  `channel` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '上传来源(10商户后台 20用户端)',
  `storage` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '存储方式',
  `domain` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '存储域名',
  `file_type` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '文件类型(10图片 20附件 30视频)',
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件名称(仅显示)',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件路径',
  `file_size` int(11) NOT NULL DEFAULT '0' COMMENT '文件大小(字节)',
  `file_ext` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件扩展名',
  `cover` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件封面',
  `uploader_id` int(11) NOT NULL DEFAULT '0' COMMENT '上传者用户ID',
  `is_recycle` int(11) NOT NULL DEFAULT '0' COMMENT '是否在回收站',
  `created_at` datetime DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`file_id`) USING BTREE,
  KEY `group_id` (`group_id`) USING BTREE,
  KEY `is_recycle` (`is_recycle`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='文件库记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `file`
--

LOCK TABLES `file` WRITE;
/*!40000 ALTER TABLE `file` DISABLE KEYS */;
INSERT INTO `file` VALUES (1,0,10,'public','',10,'微信图片_20240913160532.jpg','file/yWYXNxicbUrpx8tc7GqyOhyi6aOGxUtm6P9gcE1Y.jpg',58302,'jpg','',1,0,'2024-09-21 08:57:44','2024-09-21 08:57:44'),(2,0,10,'public','',10,'微信图片_20240913160532.jpg','file/m8Z9zUbUSJAb4HlFT0p9027o2eZxACXRBw6JmwT6.jpg',58302,'jpg','',1,0,'2024-09-21 08:58:56','2024-09-21 08:58:56'),(3,0,10,'public','',10,'微信图片_20240913160532.jpg','file/a1UVxuI5TdGZzDqiBDnZ47HMhrbF25M9FpkQV14G.jpg',58302,'jpg','',1,0,'2024-09-21 09:00:43','2024-09-21 09:00:43'),(4,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/SCnbDoFSAQ2Rw6bqW2NiH4FAT50eTjFn0lqLbSaq.jpg',12251,'jpg','',1,0,'2024-09-23 02:12:30','2024-09-23 02:12:30'),(5,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/bcfD136n5ef6Uygndyjb49tQk0DQJsieyXLefReg.jpg',12251,'jpg','',1,0,'2024-09-23 02:12:46','2024-09-23 02:12:46'),(6,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/DHFMSZeCkhpAZaG673R7BZ7NNVT3FVjLJWchRvFl.jpg',12251,'jpg','',1,0,'2024-09-23 02:16:43','2024-09-23 02:16:43'),(7,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/Qc1Wvd8rOWki2QEd9fgEBtzZPOdh23nwYvJtiraY.jpg',12251,'jpg','',1,0,'2024-09-23 02:55:39','2024-09-23 02:55:39'),(8,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/FB2Q3eKyiytz9nhkXGHRKh2qJONALRQv8YgDUy5M.jpg',12251,'jpg','',1,0,'2024-09-23 02:55:50','2024-09-23 02:55:50'),(9,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/klHb61DP0id9imS5AGJQOgBt0MCype8RvW1DfihM.jpg',12251,'jpg','',1,0,'2024-09-23 02:56:26','2024-09-23 02:56:26'),(10,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/o9uoNabvzkRUY2cfNDQgtcvjSVfddfUlFelLnrd5.jpg',12251,'jpg','',1,0,'2024-09-23 02:57:05','2024-09-23 02:57:05'),(11,0,10,'public','',10,'微信图片_20240913160532.jpg','file/0XO8c5UwPufjsFS8rJkhr9ZvXbW6rcdqIVKf4wbj.jpg',58302,'jpg','',1,0,'2024-09-23 02:58:41','2024-09-23 02:58:41'),(12,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/DTkiHTesC7zZ2AD2bcQbHa95fugGR53K39lGPGU0.jpg',12251,'jpg','',1,0,'2024-09-23 02:58:51','2024-09-23 02:58:51'),(13,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/Z0BMQxRtZxjPsh4aj15QLqIsLcNoTTt4cds2fGPO.jpg',12251,'jpg','',1,0,'2024-09-23 03:53:31','2024-09-23 03:53:31'),(14,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/HFf0wuALPXgPGuwG6YbiATrbfqP3MpK68KBcYu3X.jpg',12251,'jpg','',1,0,'2024-09-23 03:53:43','2024-09-23 03:53:43'),(15,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/tM1yakhNXDftXjx0zkfDVkUinSaAfW2Q63hqJu2t.jpg',12251,'jpg','',1,0,'2024-09-23 03:55:36','2024-09-23 03:55:36'),(16,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/H2qdFtB1Pa76hZF6XMt07zsWF14QZLxFp5rn0RK3.jpg',12251,'jpg','',1,0,'2024-09-23 03:55:45','2024-09-23 03:55:45'),(17,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/2T4AEGq7yEwafSkUxbTADtLYsZbAfITh8aRLcx4C.jpg',12251,'jpg','',1,0,'2024-09-23 03:56:38','2024-09-23 03:56:38'),(18,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/TmR4uO56L8Pjt7qW3mhUW1n0voqRbtRqlDplPUv7.jpg',12251,'jpg','',1,0,'2024-09-23 04:01:01','2024-09-23 04:01:01'),(19,0,10,'public','',10,'微信图片_20240913160532.jpg','file/bkJyq9mOnAkQpwOo4QxgNIjn223jkzMae32M08gv.jpg',58302,'jpg','',1,0,'2024-09-23 04:01:22','2024-09-23 04:01:22'),(20,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/GgTKojkaf0wzWUYX2njrZDW3hXN7yxd5xO6QDT88.jpg',12251,'jpg','',1,0,'2024-09-23 04:02:16','2024-09-23 04:02:16'),(21,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/Bz5ovklbpGwQhDj0Fmx9Zhh8BC6ZozLALpRmzSBP.jpg',12251,'jpg','',1,0,'2024-09-23 04:25:00','2024-09-23 04:25:00'),(22,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/3004AyHWpXTSeaActYMEAToAF4FCkXsBHZBtgyjb.jpg',12251,'jpg','',1,0,'2024-09-23 05:07:47','2024-09-23 05:07:47'),(23,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/vBrvhTys6J2cwyvtsBrURrX5rcoVE6dAYTMmdXHG.jpg',12251,'jpg','',1,0,'2024-09-23 05:08:29','2024-09-23 05:08:29'),(24,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/4lLwiu1UxXgToeCsY2jN8WtVKyoz3uqhuewGqMAL.jpg',12251,'jpg','',1,0,'2024-09-23 05:09:26','2024-09-23 05:09:26'),(25,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/5PvZEqjSG5gyXt3w0oFzNsnVPTqT440PPcyN2EOK.jpg',12251,'jpg','',1,0,'2024-09-23 05:11:11','2024-09-23 05:11:11'),(26,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/DewxJHU1jvu2wwHBQqRCQqo59dRDL33XmzYHbqka.jpg',12251,'jpg','',1,0,'2024-09-23 05:13:25','2024-09-23 05:13:25'),(27,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/yXQRXqcbXt7tlWeF23PNEUvsAFuh0fFxs211kGqD.jpg',12251,'jpg','',1,0,'2024-09-23 05:14:17','2024-09-23 05:14:17'),(28,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/acFlQDvl81lnPnv0QkAPRIrXiPWP4C24YsugpoGD.jpg',12251,'jpg','',1,0,NULL,NULL),(29,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/WXcQK0eyOcB4k62dtzsyQQkBXJNNWZCAiBkvkrq6.jpg',12251,'jpg','',1,0,NULL,NULL),(30,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/Gs5tk0pGj8WQizwuOXf16g96iEyPzHGzeMGwL8Ot.jpg',12251,'jpg','',1,0,NULL,NULL),(31,0,10,'public','',10,'zfb_SN1510100110132045.jpg','file/dbpPJkuJ5g4avIxRzEt9a3EQS1GcNcWNixBTa59G.jpg',12251,'jpg','',1,0,'2024-09-23 05:24:05','2024-09-23 05:24:05'),(32,0,10,'public','',10,'微信图片_20240913160532.jpg','file/V2JTqQyt7Mlw8L1QoK467gU1kz8G1VFE6m0nfr8S.jpg',58882,'jpg','',1,0,'2024-09-23 05:42:06','2024-09-23 05:42:06'),(33,0,10,'public','',10,'f571a6810d1ef316bff7fcc5abb09de.jpg','file/BsqWGY2zrPUvEKSWMqtmq23DBO2U6wexXnsQfBZT.jpg',35505,'jpg','',1,0,'2024-09-24 05:44:21','2024-09-24 05:44:21'),(34,1,20,'public','',10,'2.jpg','file/zNaz7W566h4gOP5upJ9AH45A9vt0n40WTFx7mlcH.jpg',34921,'jpg','',1,0,'2024-09-27 07:59:29','2024-09-27 07:59:29'),(35,1,20,'public','',10,'5.jpg','file/2KrduT29eeFN8SemJtYrzT1qFprgzb6N1gcMmCzo.jpg',38563,'jpg','',1,0,'2024-09-27 08:09:59','2024-09-27 08:09:59'),(36,1,20,'public','',10,'五红花胶.jpg','file/Ktd7TR34dVKr8DmrML0b0DQ74eO1ABNnnkrQbkH4.jpg',30076,'jpg','',1,0,'2024-09-27 08:14:51','2024-09-27 08:14:51'),(37,1,20,'public','',10,'2.jpg','file/3DI1pRI8kmqMUvbyerIN3QeBlQKfXDmexHDkPWnr.jpg',34921,'jpg','',1,0,'2024-09-27 08:15:40','2024-09-27 08:15:40'),(38,1,20,'public','',10,'新建项目 (1).png','file/zdM76ewpH3DpS2uZD0e5dUMuP4loQrsdjkNdhRsD.png',827328,'png','',1,0,'2024-09-28 11:52:47','2024-09-28 11:52:47'),(39,1,20,'public','',10,'新建项目.png','file/9pkKavyTtiaXyAvvj6VVqh3DE5AzReKNy6A4uaFE.png',795778,'png','',1,0,'2024-09-28 11:53:04','2024-09-28 11:53:04'),(40,1,20,'public','',10,'新建项目 (1).png','file/gaQc9mqy36Nlx3FmsVQujHlaB1tWnPdYoLGn7RL0.png',827328,'png','',1,0,'2024-09-28 11:54:28','2024-09-28 11:54:28');
/*!40000 ALTER TABLE `file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `file_group`
--

DROP TABLE IF EXISTS `file_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `file_group` (
  `group_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '分组ID',
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分组名称',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分组ID',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序(数字越小越靠前)',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`group_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='文件库分组记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `file_group`
--

LOCK TABLES `file_group` WRITE;
/*!40000 ALTER TABLE `file_group` DISABLE KEYS */;
INSERT INTO `file_group` VALUES (14,'头像文件夹',0,0,1726887616,1726887616),(15,'附件文件夹',0,0,1726887616,1726887616),(16,'视频文件夹',0,0,1726887616,1726887616);
/*!40000 ALTER TABLE `file_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000001_create_cache_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `monitor`
--

DROP TABLE IF EXISTS `monitor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `monitor` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '操作名称',
  `controller` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '控制器',
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求方法',
  `ip` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '请求IP',
  `host` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '当前访问域名或者IP',
  `address` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '地址',
  `url` text COLLATE utf8mb4_unicode_ci COMMENT '请求地址',
  `data` text COLLATE utf8mb4_unicode_ci COMMENT 'POST参数',
  `params` text COLLATE utf8mb4_unicode_ci COMMENT 'Params参数',
  `user_id` int(11) NOT NULL COMMENT '管理员ID',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '访问时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='数据监控表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `monitor`
--

LOCK TABLES `monitor` WRITE;
/*!40000 ALTER TABLE `monitor` DISABLE KEYS */;
INSERT INTO `monitor` VALUES (1,'管理员登录','App\\Http\\Controllers\\Admin\\IndexController','App\\Http\\Controllers\\Admin\\IndexController@login','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/index/login','{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}','[]',1,1727077415),(2,'管理员登录','App\\Http\\Controllers\\Admin\\IndexController','App\\Http\\Controllers\\Admin\\IndexController@login','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/index/login','{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}','[]',1,1727077510),(3,'管理员登录','App\\Http\\Controllers\\Admin\\IndexController','App\\Http\\Controllers\\Admin\\IndexController@login','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/index/login','{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}','[]',1,1727077992),(4,'管理员登录','App\\Controllers\\Admin\\IndexController','App\\Controllers\\Admin\\IndexController@login','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/index/login','{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}','[]',1,1727157447),(5,'管理员登录','App\\Controllers\\Admin\\IndexController','App\\Controllers\\Admin\\IndexController@login','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/index/login','{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}','[]',1,1727158229),(6,'设置分组权限','App\\Controllers\\Admin\\AdminGroupController','App\\Controllers\\Admin\\AdminGroupController@setGroupRule','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/adminGroup/setGroupRule','{\"id\":3,\"rule_ids\":[10,11,12,13,14,15,30,31,32,33,43,44,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,84,88,89,90,91,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113]}','[]',1,1727250555),(7,'设置分组权限','App\\Controllers\\Admin\\AdminGroupController','App\\Controllers\\Admin\\AdminGroupController@setGroupRule','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/adminGroup/setGroupRule','{\"id\":3,\"rule_ids\":[10,11,12,13,14,15,30,31,32,33,43,44,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,84,88,89,90,91,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113]}','[]',1,1727250651),(8,'设置分组权限','App\\Controllers\\Admin\\AdminGroupController','App\\Controllers\\Admin\\AdminGroupController@setGroupRule','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/adminGroup/setGroupRule','{\"id\":3,\"rule_ids\":[10,11,12,13,14,15,30,31,32,33,43,44,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,84,88,89,90,91,102,103,104,105,106,107,108,109,110,111,112,113,119,120,2,94,93,3,35,36,86,39,81,69,70,40,74,73,72,71,80,79,78,77,76,75,114,115,116,117,85,83,82,92,9,8,7,87,16,17,18,19,20,21,22,23,24,25,26,27,28,29,118]}','[]',1,1727250670),(9,'设置分组权限','App\\Controllers\\Admin\\AdminGroupController','App\\Controllers\\Admin\\AdminGroupController@setGroupRule','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/adminGroup/setGroupRule','{\"id\":3,\"rule_ids\":[10,11,12,13,14,15,30,31,32,33,43,44,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,84,88,89,90,91,102,103,104,105,106,107,108,109,110,111,112,113,119,120,2,94,93,3,35,36,86,39,81,69,70,40,74,73,72,71,80,79,78,77,76,75,114,115,116,117,85,83,82,92,9,8,7,87,16,17,18,19,20,21,22,23,24,25,26,27,28,29,118]}','[]',1,1727250671),(10,'设置分组权限','App\\Controllers\\Admin\\AdminGroupController','App\\Controllers\\Admin\\AdminGroupController@setGroupRule','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/adminGroup/setGroupRule','{\"id\":3,\"rule_ids\":[43,44,48,54,55,56,57,58,59,60,84,118]}','[]',1,1727252030),(11,'设置分组权限','App\\Controllers\\Admin\\AdminGroupController','App\\Controllers\\Admin\\AdminGroupController@setGroupRule','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/adminGroup/setGroupRule','{\"id\":3,\"rule_ids\":[43,44,48,54,55,56,57,58,59,60,84,118]}','[]',1,1727252031),(12,'修改管理员信息','App\\Controllers\\Admin\\AdminController','App\\Controllers\\Admin\\AdminController@updateAdmin','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/admin/updateAdmin','{\"username\":\"admin\",\"nickname\":\"Admin\",\"email\":\"admin@xinadmin.cn\",\"mobile\":\"1888888888\",\"avatar_id\":35}','[]',1,1727424733),(13,'修改管理员信息','App\\Controllers\\Admin\\AdminController','App\\Controllers\\Admin\\AdminController@updateAdmin','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/admin/updateAdmin','{\"username\":\"admin\",\"nickname\":\"Admin\",\"email\":\"admin@xinadmin.cn\",\"mobile\":\"1888888888\",\"avatar_id\":35}','[]',1,1727424871),(14,'修改管理员信息','App\\Controllers\\Admin\\AdminController','App\\Controllers\\Admin\\AdminController@updateAdmin','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/admin/updateAdmin','{\"username\":\"admin\",\"nickname\":\"Admin\",\"email\":\"admin@xinadmin.cn\",\"mobile\":\"1888888888\",\"avatar_id\":36}','[]',1,1727424892),(15,'新增管理员','App\\Controllers\\Admin\\AdminController','App\\Controllers\\Admin\\AdminController@add','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/admin/add','{\"username\":\"test\",\"nickname\":\"text\",\"sex\":\"0\",\"email\":\"2@qq.cin\",\"group_id\":2,\"status\":\"1\",\"mobile\":\"15899999999\",\"avatar_id\":37,\"password\":\"123456\",\"rePassword\":\"123456\"}','[]',1,1727424993),(16,'新增管理员','App\\Controllers\\Admin\\AdminController','App\\Controllers\\Admin\\AdminController@add','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/admin/add','{\"username\":\"test\",\"nickname\":\"text\",\"sex\":\"0\",\"email\":\"2@qq.cin\",\"group_id\":2,\"status\":\"1\",\"mobile\":\"15899999999\",\"avatar_id\":37,\"password\":\"123456\",\"rePassword\":\"123456\"}','[]',1,1727425005),(17,'新增管理员','App\\Controllers\\Admin\\AdminController','App\\Controllers\\Admin\\AdminController@add','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/admin/add','{\"username\":\"test\",\"nickname\":\"text\",\"sex\":\"0\",\"email\":\"2@qq.cin\",\"group_id\":2,\"status\":\"1\",\"mobile\":\"15899999999\",\"avatar_id\":37,\"password\":\"123456\",\"rePassword\":\"123456\"}','[]',1,1727425079),(18,'新增管理员','App\\Controllers\\Admin\\AdminController','App\\Controllers\\Admin\\AdminController@add','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/admin/add','{\"username\":\"test\",\"nickname\":\"text\",\"sex\":\"0\",\"email\":\"2@qq.cin\",\"group_id\":2,\"status\":\"1\",\"mobile\":\"15899999999\",\"avatar_id\":37,\"password\":\"123456\",\"rePassword\":\"123456\"}','[]',1,1727425097),(19,'新增管理员','App\\Controllers\\Admin\\AdminController','App\\Controllers\\Admin\\AdminController@add','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/admin/add','{\"username\":\"test\",\"nickname\":\"text\",\"sex\":\"0\",\"email\":\"2@qq.cin\",\"group_id\":2,\"status\":\"1\",\"mobile\":\"15899999999\",\"avatar_id\":37,\"password\":\"123456\",\"rePassword\":\"123456\"}','[]',1,1727425119),(20,'管理员登录','App\\Controllers\\Admin\\IndexController','App\\Controllers\\Admin\\IndexController@login','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/index/login','{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}','[]',1,1727425231),(21,'修改管理员密码','App\\Controllers\\Admin\\AdminController','App\\Controllers\\Admin\\AdminController@updatePassword','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/admin/updatePassword','{\"id\":2,\"password\":\"123456\",\"rePassword\":\"123456\"}','[]',1,1727425286),(22,'修改管理员密码','App\\Controllers\\Admin\\AdminController','App\\Controllers\\Admin\\AdminController@updatePassword','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/admin/updatePassword','{\"id\":2,\"password\":\"123456\",\"rePassword\":\"123456\"}','[]',1,1727425350),(23,'重置密码','App\\Controllers\\Admin\\AdminController','App\\Controllers\\Admin\\AdminController@resetPassword','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/admin/resetPassword','{\"id\":2,\"password\":\"123456\",\"rePassword\":\"123456\"}','[]',1,1727425828),(24,'管理员登录','App\\Controllers\\Admin\\IndexController','App\\Controllers\\Admin\\IndexController@login','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/index/login','{\"username\":\"test\",\"password\":\"123456\",\"loginType\":\"account\"}','[]',2,1727425855),(25,'管理员登录','App\\Controllers\\Admin\\IndexController','App\\Controllers\\Admin\\IndexController@login','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/index/login','{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}','[]',1,1727425901),(26,'修改管理员密码','App\\Controllers\\Admin\\AdminController','App\\Controllers\\Admin\\AdminController@updatePassword','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/admin/updatePassword','{\"id\":1,\"rePassword\":\"1234567\",\"oldPassword\":\"1234561\",\"newPassword\":\"1234567\"}','[]',1,1727426072),(27,'修改管理员密码','App\\Controllers\\Admin\\AdminController','App\\Controllers\\Admin\\AdminController@updatePassword','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/admin/updatePassword','{\"rePassword\":\"1234567\",\"oldPassword\":\"1234561\",\"newPassword\":\"1234567\"}','[]',1,1727426102),(28,'修改管理员密码','App\\Controllers\\Admin\\AdminController','App\\Controllers\\Admin\\AdminController@updatePassword','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/admin/updatePassword','{\"rePassword\":\"1234567\",\"oldPassword\":\"1234561\",\"newPassword\":\"1234567\"}','[]',1,1727426125),(29,'修改管理员密码','App\\Controllers\\Admin\\AdminController','App\\Controllers\\Admin\\AdminController@updatePassword','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/admin/updatePassword','{\"rePassword\":\"1234567\",\"oldPassword\":\"1234561\",\"newPassword\":\"1234567\"}','[]',1,1727426126),(30,'修改管理员密码','App\\Controllers\\Admin\\AdminController','App\\Controllers\\Admin\\AdminController@updatePassword','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/admin/updatePassword','{\"rePassword\":\"1234567\",\"oldPassword\":\"123456\",\"newPassword\":\"1234567\"}','[]',1,1727426132),(31,'管理员登录','App\\Controllers\\Admin\\IndexController','App\\Controllers\\Admin\\IndexController@login','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/index/login','{\"username\":\"admin\",\"password\":\"1234567\",\"loginType\":\"account\"}','[]',1,1727426152),(32,'修改管理员密码','App\\Controllers\\Admin\\AdminController','App\\Controllers\\Admin\\AdminController@updatePassword','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/admin/updatePassword','{\"oldPassword\":\"1234567\",\"newPassword\":\"123456\",\"rePassword\":\"123456\"}','[]',1,1727426173),(33,'修改管理员信息','App\\Controllers\\Admin\\AdminController','App\\Controllers\\Admin\\AdminController@updateAdmin','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/admin/updateAdmin','{\"username\":\"admin\",\"nickname\":\"Admin\",\"email\":\"admin@xinadmin.cna\",\"mobile\":\"1888888888\",\"avatar_id\":36}','[]',1,1727426189),(34,'修改管理员信息','App\\Controllers\\Admin\\AdminController','App\\Controllers\\Admin\\AdminController@updateAdmin','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/admin/updateAdmin','{\"username\":\"admin\",\"nickname\":\"Admin\",\"email\":\"admin@xinadmin.cn\",\"mobile\":\"1888888888\",\"avatar_id\":36}','[]',1,1727426197),(35,'管理员登录','App\\Controllers\\Admin\\IndexController','App\\Controllers\\Admin\\IndexController@login','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/index/login','{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}','[]',1,1727524325),(36,'管理员登录','App\\Http\\Controllers\\Admin\\IndexController','App\\Http\\Controllers\\Admin\\IndexController@login','127.0.0.1','localhost','本机地址 本机地址  ','http://localhost:8000/admin/index/login','{\"username\":\"admin\",\"password\":\"123456\",\"loginType\":\"account\"}','[]',1,1727658726);
/*!40000 ALTER TABLE `monitor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `online_table`
--

DROP TABLE IF EXISTS `online_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `online_table` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '在线开发ID',
  `table_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '表格名',
  `columns` text COLLATE utf8mb4_unicode_ci COMMENT '表头Json',
  `crud_config` text COLLATE utf8mb4_unicode_ci COMMENT 'crud配置',
  `table_config` text COLLATE utf8mb4_unicode_ci COMMENT '基础配置',
  `describe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='在线开发记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `online_table`
--

LOCK TABLES `online_table` WRITE;
/*!40000 ALTER TABLE `online_table` DISABLE KEYS */;
/*!40000 ALTER TABLE `online_table` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `setting`
--

DROP TABLE IF EXISTS `setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `setting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '设置ID',
  `key` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '设置项标示',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设置标题',
  `describe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设置项描述',
  `values` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设置值',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设置类型',
  `options` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'options配置',
  `props` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'options配置',
  `group_id` int(11) NOT NULL DEFAULT '0' COMMENT '分组ID',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `key` (`key`) USING BTREE,
  KEY `group_id` (`group_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='设置记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `setting`
--

LOCK TABLES `setting` WRITE;
/*!40000 ALTER TABLE `setting` DISABLE KEYS */;
INSERT INTO `setting` VALUES (1,'title','网站标题','网站标题，用于展示在网站logo旁边和登录页面以及网页title中','Xin Admin','input','','',3,0,1726887616,1726887616),(4,'logo','网站 LOGO','网站的LOGO，用于标识网站','https://file.xinadmin.cn/file/favicons.ico','input','','',3,0,1726887616,1726887616),(5,'subtitle','网站副标题','网站副标题，展示在登录页面标题的下面','Xin Admin 快速开发框架','input','','',3,0,1726887616,1726887616),(6,'login','邮箱登录','是否开启邮箱登录','0','switch','','',4,99,1726887616,1726887616),(7,'Port','服务器端口','邮箱服务器端口','465','input','','',4,80,1726887616,1726887616),(8,'SMTPSecure','邮箱协议','邮箱协议 TLS 或者ssl协议','ssl','input','','',4,70,1726887616,1726887616),(9,'username','SMTP 用户名','邮箱 SMTP 用户名','','input','','',4,60,1726887616,1726887616),(10,'password','SMTP 密码','邮箱 SMTP 密码','','password','','',4,60,1726887616,1726887616),(11,'smtp','SMTP服务器','SMTP服务器 地址','','input','','',4,50,1726887616,1726887616),(12,'char','邮件编码','邮件编码，UTF-8','UTF-8','input','','',4,50,1726887616,1726887616);
/*!40000 ALTER TABLE `setting` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `setting_group`
--

DROP TABLE IF EXISTS `setting_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `setting_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '设置分组ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '分组标题',
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '分组KEY',
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '分组类型1：设置菜单 2：设置组 ',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `key` (`key`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='设置分组表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `setting_group`
--

LOCK TABLES `setting_group` WRITE;
/*!40000 ALTER TABLE `setting_group` DISABLE KEYS */;
INSERT INTO `setting_group` VALUES (3,0,'网站设置','web','2',1726887616,1726887616),(4,0,'邮箱设置','mail','1',1726887616,1726887616);
/*!40000 ALTER TABLE `setting_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `token`
--

DROP TABLE IF EXISTS `token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `token` (
  `token` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Token',
  `type` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '类型',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `expire_time` int(10) unsigned DEFAULT NULL COMMENT '过期时间',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  KEY `token` (`token`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='用户Token表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `token`
--

LOCK TABLES `token` WRITE;
/*!40000 ALTER TABLE `token` DISABLE KEYS */;
INSERT INTO `token` VALUES ('85909993124a34ab1b91381284f361936be55141','user-refresh',1,1729748706,1727156706),('e39cc9a42d960e2eb5b81ded52be1ab15200228f','admin-refresh',1,1730018152,1727426152),('e9f632b876f6ea1bc94c15cfa3fd9e5860491a2a','admin-refresh',1,1730116324,1727524324),('d0ddffa7a200d6d19ca6d1cca47fa09f4d077fa5','admin',1,1727625828,1727625228),('49cc90025f88e54169473e011cabfc6ce36732f2','admin-refresh',1,1730250725,1727658725),('f85e3493f19f405bc274fc83eb608a30ab086987','user',1,1727663394,1727662794),('8d8d74940d3bc1f11f4f292348f80acab000cfc6','user-refresh',1,1730254794,1727662794),('a5c7e5e802abf0e2e6c19e182adefef1f2f99de5','admin',1,1727686111,1727685511);
/*!40000 ALTER TABLE `token` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `mobile` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '手机号',
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `nickname` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `avatar_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '头像ID',
  `gender` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '性别',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `group_id` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '分组ID',
  `money` int(11) NOT NULL DEFAULT '0' COMMENT '用户余额',
  `score` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `motto` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '签名',
  `status` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '状态',
  `created_at` datetime DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `group_id` (`group_id`) USING BTREE,
  KEY `username` (`username`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='用户列表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'15999999999','user','liu@xinadmin.cn','$2y$10$WzrGZDWBrJFdq671XNm4VutbOso6fEeLWHzlZzU5oa2tnVACsOJQa','小刘同学',33,'0',NULL,1,20000,0,'','1',NULL,'2024-09-30 02:19:29');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_group`
--

DROP TABLE IF EXISTS `user_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分组ID',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分组名称',
  `rules` text COLLATE utf8mb4_unicode_ci COMMENT '权限ID',
  `created_at` datetime DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='会员分组表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_group`
--

LOCK TABLES `user_group` WRITE;
/*!40000 ALTER TABLE `user_group` DISABLE KEYS */;
INSERT INTO `user_group` VALUES (1,0,'普通会员','*',NULL,NULL),(2,0,'访客','1,9,10,11,12',NULL,NULL);
/*!40000 ALTER TABLE `user_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_money_log`
--

DROP TABLE IF EXISTS `user_money_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_money_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '记录ID',
  `user_id` int(10) unsigned NOT NULL COMMENT '用户ID',
  `scene` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '余额变动场景',
  `money` float NOT NULL DEFAULT '0' COMMENT '余额变动场景',
  `describe` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述/说明',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='用户余额变动明细表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_money_log`
--

LOCK TABLES `user_money_log` WRITE;
/*!40000 ALTER TABLE `user_money_log` DISABLE KEYS */;
INSERT INTO `user_money_log` VALUES (1,1,'1',10000,'管理员充值: ceshi','2024-09-29 15:45:04'),(2,1,'1',-6000,'管理员充值: 123','2024-09-29 15:45:46'),(3,1,'1',1000,'123','2024-09-29 15:48:21'),(4,1,'1',30000,'测试','2024-09-29 15:50:51'),(5,1,'1',10000,'133','2024-09-29 15:51:30'),(6,1,'1',30000,'123456','2024-09-30 01:53:04'),(7,1,'1',5000,'123456','2024-09-30 01:53:17'),(8,1,'1',5000,'123456','2024-09-30 01:53:26'),(9,1,'1',5000,'123456','2024-09-30 01:56:19'),(10,1,'1',10000,'123456','2024-09-30 01:56:29'),(11,1,'1',10000,'123456','2024-09-30 01:57:11'),(12,1,'1',10000,'123456','2024-09-30 01:57:21');
/*!40000 ALTER TABLE `user_money_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_rule`
--

DROP TABLE IF EXISTS `user_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父ID',
  `type` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '类型 0：页面 1：数据 2：按钮',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `path` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '路由地址',
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '图标',
  `key` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '权限标识',
  `locale` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '国际化标识',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '启用状态',
  `show` int(11) NOT NULL DEFAULT '1' COMMENT '显示状态',
  `created_at` datetime DEFAULT NULL COMMENT '更新时间',
  `updated_at` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `key` (`key`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='会员权限规则表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_rule`
--

LOCK TABLES `user_rule` WRITE;
/*!40000 ALTER TABLE `user_rule` DISABLE KEYS */;
INSERT INTO `user_rule` VALUES (1,0,'0',99,'首页','/','HomeOutlined','index','menu.index',1,1,NULL,NULL),(9,0,'0',0,'代码仓库','/git','StarOutlined','git','menu.git',1,1,NULL,NULL),(10,9,'1',0,'Github','https://github.com/Xineny-liu/xinadmin',NULL,'xinadmin','menu.github',1,1,NULL,NULL),(11,9,'1',1,'Gitee','https://gitee.com/xineny/xin-admin',NULL,'gitee','menu.gitee',1,1,NULL,NULL),(12,0,'0',0,'官方文档','https://xinadmin.cn','FileSearchOutlined','xinadmin','menu.xinadmin',1,1,NULL,'2024-09-30 08:22:53'),(13,0,'0',98,'会员中心','/user','UserOutlined','user','menu.users',1,1,NULL,NULL),(17,13,'1',99,'个人中心','/user',NULL,'user','menu.user',1,1,NULL,NULL),(18,13,'1',98,'账户设置','/user/userSetting',NULL,'user.userSetting','menu.userSetting',1,1,NULL,NULL),(19,13,'1',97,'修改密码','/user/setPassword',NULL,'user.setPassword','menu.setPassword',1,1,NULL,NULL),(20,13,'1',0,'资产记录','/user/log',NULL,'user.log','menu.log',1,1,NULL,NULL),(21,20,'1',0,'余额记录','/user/log/moneyLog',NULL,'user.log.moneyLog','menu.log.moneyLog',1,1,NULL,NULL);
/*!40000 ALTER TABLE `user_rule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `verification_code`
--

DROP TABLE IF EXISTS `verification_code`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `verification_code` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '类型',
  `code` int(10) unsigned NOT NULL COMMENT '验证码',
  `status` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '状态0：未发送 1：已发送 2：已验证',
  `interval` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '有效期',
  `data` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '接收方',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='验证码记录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `verification_code`
--

LOCK TABLES `verification_code` WRITE;
/*!40000 ALTER TABLE `verification_code` DISABLE KEYS */;
/*!40000 ALTER TABLE `verification_code` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'laravel'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-09-30 16:49:59
