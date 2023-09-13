-- MySQL dump 10.13  Distrib 5.7.20, for Win64 (x86_64)
--
-- Host: localhost    Database: zonectf
-- ------------------------------------------------------
-- Server version	5.7.20-log

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
-- Table structure for table `fa_poster`
--

DROP TABLE IF EXISTS `fa_poster`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fa_poster` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '海报名称',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '背景图片',
  `qrcode_size` varchar(255) NOT NULL DEFAULT '' COMMENT '二维码大小{"w":"","h":""}',
  `qrcode_position` varchar(255) NOT NULL DEFAULT '' COMMENT '二维码坐标{"x":"","y":""}',
  `is_avatar` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否显示头像:0=否,1=是',
  `avatar_size` varchar(255) DEFAULT NULL COMMENT '头像大小{"w":"","h":""}',
  `avatar_position` varchar(255) DEFAULT NULL COMMENT '头像坐标{"x":"","y":""}',
  `is_font` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否显示邀请码:0=否,1=是',
  `font_size` varchar(255) DEFAULT NULL COMMENT '邀请码设置{"size":"大小","color":"r,g,b"}',
  `font_position` varchar(255) DEFAULT NULL COMMENT '邀请码坐标{"x":"","y":""}',
  `is_nickname` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否显示昵称:0=否,1=是',
  `nickname_size` varchar(255) DEFAULT NULL COMMENT '昵称设置{"size":"大小","color":"r,g,b"}',
  `nickname_position` varchar(255) DEFAULT NULL COMMENT '昵称坐标{"x":"","y":""}',
  `weigh` int(11) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` enum('hidden','normal') NOT NULL DEFAULT 'hidden' COMMENT '状态:hidden=隐藏,normal=正常',
  `createtime` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='海报管理';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_action`
--

DROP TABLE IF EXISTS `tp_action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_action` (
  `id` mediumint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号自增',
  `uid` int(255) unsigned DEFAULT NULL,
  `action_title` varchar(255) NOT NULL COMMENT '标题',
  `action_text` text COMMENT '正文',
  `action_type` enum('1','0') NOT NULL DEFAULT '0' COMMENT '类型:0=公告,1=动态',
  `action_count` int(255) NOT NULL DEFAULT '0',
  `action_time` datetime NOT NULL DEFAULT '2022-03-06 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_admin`
--

DROP TABLE IF EXISTS `tp_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(30) NOT NULL DEFAULT '' COMMENT '密码盐',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `loginfailure` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '失败次数',
  `logintime` int(10) DEFAULT NULL COMMENT '登录时间',
  `loginip` varchar(50) DEFAULT NULL COMMENT '登录IP',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `token` varchar(59) NOT NULL DEFAULT '' COMMENT 'Session标识',
  `status` varchar(30) NOT NULL DEFAULT 'normal' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='管理员表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_admin_log`
--

DROP TABLE IF EXISTS `tp_admin_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_admin_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `username` varchar(30) NOT NULL DEFAULT '' COMMENT '管理员名字',
  `url` varchar(1500) NOT NULL DEFAULT '' COMMENT '操作页面',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '日志标题',
  `content` text NOT NULL COMMENT '内容',
  `ip` varchar(50) NOT NULL DEFAULT '' COMMENT 'IP',
  `useragent` varchar(255) NOT NULL DEFAULT '' COMMENT 'User-Agent',
  `createtime` int(10) DEFAULT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`),
  KEY `name` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=1132 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='管理员日志表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_answer`
--

DROP TABLE IF EXISTS `tp_answer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_answer` (
  `id` mediumint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '编号自增',
  `tid` int(50) unsigned NOT NULL COMMENT '题目ID',
  `uid` int(50) unsigned NOT NULL COMMENT '用户ID',
  `img` int(255) DEFAULT NULL,
  `score` int(50) unsigned NOT NULL,
  `team_id` int(50) unsigned DEFAULT NULL COMMENT '战队ID',
  `type` varchar(255) NOT NULL COMMENT '题目类型',
  `create_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_area`
--

DROP TABLE IF EXISTS `tp_area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_area` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pid` int(10) DEFAULT NULL COMMENT '父id',
  `shortname` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '简称',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '名称',
  `mergename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '全称',
  `level` tinyint(4) DEFAULT NULL COMMENT '层级:1=省,2=市,3=区/县',
  `pinyin` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '拼音',
  `code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '长途区号',
  `zip` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '邮编',
  `first` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '首字母',
  `lng` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '经度',
  `lat` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '纬度',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='地区表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_article`
--

DROP TABLE IF EXISTS `tp_article`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_article` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '发文用户ID',
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文章标题',
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文章正文',
  `browsing` int(11) DEFAULT '0' COMMENT '浏览量',
  `like_count` int(255) DEFAULT '0' COMMENT '点赞数',
  `comment_count` int(255) DEFAULT '0' COMMENT '评论数',
  `type` int(255) unsigned NOT NULL COMMENT '文章分类',
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci DEFAULT '0' COMMENT '文章状态:0=待审核,1=正常',
  `top` enum('2','1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '属性:0=置顶,1=推荐,2=普通',
  `create_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT '文章创建时间',
  `update_time` datetime DEFAULT '1970-01-01 00:00:00' COMMENT '文章更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_article_type`
--

DROP TABLE IF EXISTS `tp_article_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_article_type` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tid` int(11) NOT NULL,
  `create_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_attachment`
--

DROP TABLE IF EXISTS `tp_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_attachment` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `category` varchar(50) DEFAULT '' COMMENT '类别',
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '物理路径',
  `imagewidth` varchar(30) NOT NULL DEFAULT '' COMMENT '宽度',
  `imageheight` varchar(30) NOT NULL DEFAULT '' COMMENT '高度',
  `imagetype` varchar(30) NOT NULL DEFAULT '' COMMENT '图片类型',
  `imageframes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '图片帧数',
  `filename` varchar(100) DEFAULT '' COMMENT '文件名称',
  `filesize` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `mimetype` varchar(100) NOT NULL DEFAULT '' COMMENT 'mime类型',
  `extparam` varchar(255) NOT NULL DEFAULT '' COMMENT '透传数据',
  `createtime` int(10) DEFAULT NULL COMMENT '创建日期',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `uploadtime` int(10) DEFAULT NULL COMMENT '上传时间',
  `storage` varchar(100) NOT NULL DEFAULT 'local' COMMENT '存储位置',
  `sha1` varchar(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='附件表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_auth_group`
--

DROP TABLE IF EXISTS `tp_auth_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_auth_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父组别',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '组名',
  `rules` text NOT NULL COMMENT '规则ID',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `status` varchar(30) NOT NULL DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='分组表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_auth_group_access`
--

DROP TABLE IF EXISTS `tp_auth_group_access`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_auth_group_access` (
  `uid` int(10) unsigned NOT NULL COMMENT '会员ID',
  `group_id` int(10) unsigned NOT NULL COMMENT '级别ID',
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='权限分组表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_auth_rule`
--

DROP TABLE IF EXISTS `tp_auth_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_auth_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('menu','file') NOT NULL DEFAULT 'file' COMMENT 'menu为菜单,file为权限节点',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '规则名称',
  `route` varchar(255) DEFAULT '' COMMENT '路由规则',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '规则名称',
  `icon` varchar(50) NOT NULL DEFAULT '' COMMENT '图标',
  `condition` varchar(255) NOT NULL DEFAULT '' COMMENT '条件',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `ismenu` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为菜单',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` varchar(30) NOT NULL DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`) USING BTREE,
  KEY `pid` (`pid`),
  KEY `weigh` (`weigh`)
) ENGINE=InnoDB AUTO_INCREMENT=400 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='节点表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_awdlist`
--

DROP TABLE IF EXISTS `tp_awdlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_awdlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_banner`
--

DROP TABLE IF EXISTS `tp_banner`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_banner` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_category`
--

DROP TABLE IF EXISTS `tp_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '栏目类型',
  `name` varchar(30) NOT NULL DEFAULT '',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `flag` set('hot','index','recommend') NOT NULL DEFAULT '',
  `image` varchar(100) NOT NULL DEFAULT '' COMMENT '图片',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `diyname` varchar(30) NOT NULL DEFAULT '' COMMENT '自定义名称',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` varchar(30) NOT NULL DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `weigh` (`weigh`,`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='分类表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_challenge`
--

DROP TABLE IF EXISTS `tp_challenge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_challenge` (
  `id` mediumint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `topic_name` varchar(255) NOT NULL COMMENT '题目名称',
  `topic_type` int(255) NOT NULL DEFAULT '1' COMMENT '题目类型',
  `topic_score` int(10) NOT NULL DEFAULT '1' COMMENT '题目分值',
  `topic_flag` varchar(255) NOT NULL COMMENT '题目flag',
  `topic_key` varchar(255) NOT NULL COMMENT '题目api提交值',
  `topic_difficulty` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '难度:1=容易,2=中等,3=困难',
  `topic_succeed` varchar(255) DEFAULT NULL COMMENT '题目完成人数',
  `topic_content` text COMMENT '题目描述',
  `topic_file` varchar(255) DEFAULT NULL COMMENT '题目附件',
  `topic_status` enum('1','0') DEFAULT '1' COMMENT '状态:0=禁用,1=正常',
  `top_1` varchar(255) DEFAULT NULL COMMENT '一血',
  `top_2` varchar(255) DEFAULT NULL COMMENT '二血',
  `top_3` varchar(255) DEFAULT NULL COMMENT '三血',
  `competition_id` int(50) DEFAULT '0' COMMENT '所属赛事ID',
  `create_time` datetime NOT NULL DEFAULT '2022-03-06 00:00:00' COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_chatype`
--

DROP TABLE IF EXISTS `tp_chatype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_chatype` (
  `id` mediumint(50) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID自增编号',
  `type` varchar(255) NOT NULL COMMENT '题目分类',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_command`
--

DROP TABLE IF EXISTS `tp_command`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_command` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '类型',
  `params` varchar(1500) NOT NULL DEFAULT '' COMMENT '参数',
  `command` varchar(1500) NOT NULL DEFAULT '' COMMENT '命令',
  `content` text COMMENT '返回结果',
  `executetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行时间',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` enum('successed','failured') NOT NULL DEFAULT 'failured' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=170 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='在线命令表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_comment`
--

DROP TABLE IF EXISTS `tp_comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_comment` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(50) NOT NULL COMMENT '文章id',
  `from_uid` int(255) unsigned NOT NULL COMMENT '评论用户id',
  `to_uid` int(255) unsigned NOT NULL COMMENT '文章作者id',
  `content` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '评论内容',
  `like_count` int(255) NOT NULL DEFAULT '0' COMMENT '点赞记录',
  `create_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_comment_reply`
--

DROP TABLE IF EXISTS `tp_comment_reply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_comment_reply` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `reply_id` int(10) unsigned NOT NULL COMMENT '回复目标id',
  `from_id` int(11) NOT NULL COMMENT '回复用户id',
  `to_id` int(11) NOT NULL COMMENT '评论用户id',
  `content` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `like_count` int(255) NOT NULL DEFAULT '0' COMMENT '点赞数',
  `create_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_config`
--

DROP TABLE IF EXISTS `tp_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '变量名',
  `group` varchar(30) DEFAULT '' COMMENT '分组',
  `title` varchar(100) DEFAULT '' COMMENT '变量标题',
  `tip` varchar(100) DEFAULT '' COMMENT '变量描述',
  `type` varchar(30) DEFAULT '' COMMENT '类型:string,text,int,bool,array,datetime,date,file',
  `value` text COMMENT '变量值',
  `content` text COMMENT '变量字典数据',
  `rule` varchar(100) DEFAULT '' COMMENT '验证规则',
  `extend` varchar(255) DEFAULT '' COMMENT '扩展属性',
  `setting` varchar(255) DEFAULT '' COMMENT '配置',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='系统配置';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_ems`
--

DROP TABLE IF EXISTS `tp_ems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_ems` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `event` varchar(30) NOT NULL DEFAULT '' COMMENT '事件',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱',
  `code` varchar(10) NOT NULL DEFAULT '' COMMENT '验证码',
  `times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '验证次数',
  `ip` varchar(30) NOT NULL DEFAULT '' COMMENT 'IP',
  `createtime` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='邮箱验证码表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_friend_links`
--

DROP TABLE IF EXISTS `tp_friend_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_friend_links` (
  `id` mediumint(50) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '友情链接',
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '友情链接logo',
  `create_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_imgframe`
--

DROP TABLE IF EXISTS `tp_imgframe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_imgframe` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `frame_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `frame_img` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_message`
--

DROP TABLE IF EXISTS `tp_message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_message` (
  `id` mediumint(255) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `content` text NOT NULL COMMENT '消息标题',
  `url` varchar(255) NOT NULL,
  `type` enum('1','0') NOT NULL DEFAULT '0' COMMENT '类型:0=通知,1=消息',
  `status` int(255) unsigned NOT NULL DEFAULT '0' COMMENT '消息是否一读，0为未读、1为已读',
  `create_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_navmenu`
--

DROP TABLE IF EXISTS `tp_navmenu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_navmenu` (
  `id` mediumint(255) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(255) NOT NULL DEFAULT '0' COMMENT '父级导航id',
  `nav_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '导航菜单名称',
  `nav_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '导航链接',
  `weigh` int(11) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '菜单状态:0=禁用,1=启用',
  `create_time` datetime DEFAULT '1970-01-01 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_order`
--

DROP TABLE IF EXISTS `tp_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_order` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `shp_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商品ID',
  `uid` int(50) NOT NULL COMMENT '用户id',
  `shp_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商品名称',
  `shp_img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '商品图片',
  `shp_score` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商品单价',
  `shp_unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商品数量',
  `shp_num` int(11) NOT NULL COMMENT '商品总价',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '联系人',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '联系电话',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '详细地址',
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '备注',
  `status` enum('4','3','2','1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '订单状态:0=交易失败,1=兑换商品,2=平台确认,3=平台发货,4=交易完成',
  `ding` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '物流单号',
  `create_time` datetime DEFAULT NULL COMMENT '订单创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_setting`
--

DROP TABLE IF EXISTS `tp_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_setting` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_sms`
--

DROP TABLE IF EXISTS `tp_sms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_sms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `event` varchar(30) NOT NULL DEFAULT '' COMMENT '事件',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `code` varchar(10) NOT NULL DEFAULT '' COMMENT '验证码',
  `times` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '验证次数',
  `ip` varchar(30) NOT NULL DEFAULT '' COMMENT 'IP',
  `createtime` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='短信验证码表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_store`
--

DROP TABLE IF EXISTS `tp_store`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_store` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '商品名称',
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `spe` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '默认' COMMENT '商品规格',
  `stock` int(255) NOT NULL DEFAULT '0' COMMENT '库存',
  `shp_content` text COLLATE utf8mb4_unicode_ci COMMENT '商品描述',
  `score` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '兑换积分',
  `tip` set('正品保证','顺丰包邮','闪电发货','包邮到家','七天无理由退货') COLLATE utf8mb4_unicode_ci DEFAULT '',
  `type` enum('0','1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '类型:0=头像框,1=实物商品,2=卡密',
  `quota` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '限购数量',
  `create_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_teams`
--

DROP TABLE IF EXISTS `tp_teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_teams` (
  `id` mediumint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `team_name` char(50) NOT NULL COMMENT '队伍名称',
  `team_score` int(255) NOT NULL DEFAULT '0' COMMENT '队伍总分',
  `awd_score` int(255) unsigned NOT NULL DEFAULT '0',
  `team_img` varchar(255) NOT NULL DEFAULT '/static/images/icon.png' COMMENT '队伍头像',
  `team_leader` varchar(255) NOT NULL COMMENT '队长',
  `team_number` int(10) NOT NULL DEFAULT '0' COMMENT '队伍人数',
  `team_status` enum('2','1','0') NOT NULL DEFAULT '1' COMMENT '队伍状态:0=待审核,1=正常,2=封禁',
  `team_content` text COMMENT '战队宣言',
  `team_1` varchar(255) DEFAULT NULL,
  `team_2` varchar(255) DEFAULT NULL,
  `team_3` varchar(255) DEFAULT NULL,
  `team_4` varchar(255) DEFAULT NULL,
  `team_5` varchar(255) DEFAULT NULL,
  `team_6` varchar(255) DEFAULT NULL,
  `team_7` varchar(255) DEFAULT NULL,
  `team_8` varchar(255) DEFAULT NULL,
  `team_9` varchar(255) DEFAULT NULL,
  `team_10` varchar(255) DEFAULT NULL,
  `create_time` datetime DEFAULT '1970-01-01 00:00:00' COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_teamusers`
--

DROP TABLE IF EXISTS `tp_teamusers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_teamusers` (
  `id` mediumint(50) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(50) unsigned NOT NULL COMMENT '对象uid',
  `zid` int(50) unsigned NOT NULL COMMENT '战队id',
  `uid_img` varchar(255) NOT NULL COMMENT '队员头像',
  `status` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '队员状态',
  `position` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '职位',
  `score` int(255) unsigned NOT NULL DEFAULT '0' COMMENT '队员总分',
  `text_content` text COMMENT '申请理由',
  `create_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_test`
--

DROP TABLE IF EXISTS `tp_test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_test` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `category_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID(单选)',
  `category_ids` varchar(100) NOT NULL COMMENT '分类ID(多选)',
  `week` enum('monday','tuesday','wednesday') NOT NULL COMMENT '星期(单选):monday=星期一,tuesday=星期二,wednesday=星期三',
  `flag` set('hot','index','recommend') NOT NULL DEFAULT '' COMMENT '标志(多选):hot=热门,index=首页,recommend=推荐',
  `genderdata` enum('male','female') NOT NULL DEFAULT 'male' COMMENT '性别(单选):male=男,female=女',
  `hobbydata` set('music','reading','swimming') NOT NULL COMMENT '爱好(多选):music=音乐,reading=读书,swimming=游泳',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text NOT NULL COMMENT '内容',
  `image` varchar(100) NOT NULL DEFAULT '' COMMENT '图片',
  `images` varchar(1500) NOT NULL DEFAULT '' COMMENT '图片组',
  `attachfile` varchar(100) NOT NULL DEFAULT '' COMMENT '附件',
  `keywords` varchar(100) NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `city` varchar(100) NOT NULL DEFAULT '' COMMENT '省市',
  `json` varchar(255) DEFAULT NULL COMMENT '配置:key=名称,value=值',
  `price` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '价格',
  `views` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击',
  `startdate` date DEFAULT NULL COMMENT '开始日期',
  `activitytime` datetime DEFAULT NULL COMMENT '活动时间(datetime)',
  `year` year(4) DEFAULT NULL COMMENT '年',
  `times` time DEFAULT NULL COMMENT '时间',
  `refreshtime` int(10) DEFAULT NULL COMMENT '刷新时间(int)',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `deletetime` int(10) DEFAULT NULL COMMENT '删除时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `switch` tinyint(1) NOT NULL DEFAULT '0' COMMENT '开关',
  `status` enum('normal','hidden') NOT NULL DEFAULT 'normal' COMMENT '状态',
  `state` enum('0','1','2') NOT NULL DEFAULT '1' COMMENT '状态值:0=禁用,1=正常,2=推荐',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='测试表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_third`
--

DROP TABLE IF EXISTS `tp_third`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_third` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `platform` varchar(30) NOT NULL DEFAULT '' COMMENT '第三方应用',
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT '第三方唯一ID',
  `openname` varchar(50) NOT NULL DEFAULT '' COMMENT '第三方会员昵称',
  `access_token` varchar(255) NOT NULL DEFAULT '' COMMENT 'AccessToken',
  `refresh_token` varchar(255) NOT NULL DEFAULT 'RefreshToken',
  `expires_in` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '有效期',
  `createtime` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `logintime` int(10) unsigned DEFAULT NULL COMMENT '登录时间',
  `expiretime` int(10) unsigned DEFAULT NULL COMMENT '过期时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `platform` (`platform`,`openid`),
  KEY `user_id` (`user_id`,`platform`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='第三方登录表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_topics`
--

DROP TABLE IF EXISTS `tp_topics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_topics` (
  `id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `topic_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_user`
--

DROP TABLE IF EXISTS `tp_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '组别ID',
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `salt` varchar(30) NOT NULL DEFAULT '' COMMENT '密码盐',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机号',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '等级',
  `gender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `bio` varchar(100) NOT NULL DEFAULT '' COMMENT '格言',
  `money` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '余额',
  `score` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '积分',
  `successions` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '连续登录天数',
  `maxsuccessions` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '最大连续登录天数',
  `prevtime` int(10) NOT NULL COMMENT '上次登录时间',
  `logintime` int(10) NOT NULL DEFAULT '0' COMMENT '登录时间',
  `loginip` varchar(50) NOT NULL DEFAULT '' COMMENT '登录IP',
  `loginfailure` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '失败次数',
  `joinip` varchar(50) NOT NULL DEFAULT '' COMMENT '加入IP',
  `jointime` int(10) NOT NULL COMMENT '加入时间',
  `createtime` int(10) NOT NULL COMMENT '创建时间',
  `updatetime` int(10) NOT NULL COMMENT '更新时间',
  `token` varchar(50) NOT NULL DEFAULT '' COMMENT 'Token',
  `status` varchar(30) NOT NULL DEFAULT '' COMMENT '状态',
  `verification` varchar(255) NOT NULL DEFAULT '' COMMENT '验证',
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `mobile` (`mobile`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='会员表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_user_group`
--

DROP TABLE IF EXISTS `tp_user_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_user_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT '' COMMENT '组名',
  `rules` text COMMENT '权限节点',
  `createtime` int(10) DEFAULT NULL COMMENT '添加时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `status` enum('normal','hidden') DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='会员组表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_user_money_log`
--

DROP TABLE IF EXISTS `tp_user_money_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_user_money_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更余额',
  `before` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更前余额',
  `after` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更后余额',
  `memo` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='会员余额变动表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_user_rule`
--

DROP TABLE IF EXISTS `tp_user_rule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_user_rule` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) DEFAULT NULL COMMENT '父ID',
  `name` varchar(50) DEFAULT NULL COMMENT '名称',
  `title` varchar(50) DEFAULT '' COMMENT '标题',
  `remark` varchar(100) DEFAULT NULL COMMENT '备注',
  `ismenu` tinyint(1) DEFAULT NULL COMMENT '是否菜单',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `updatetime` int(10) DEFAULT NULL COMMENT '更新时间',
  `weigh` int(10) DEFAULT '0' COMMENT '权重',
  `status` enum('normal','hidden') DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='会员规则表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_user_score_log`
--

DROP TABLE IF EXISTS `tp_user_score_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_user_score_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `score` int(10) NOT NULL DEFAULT '0' COMMENT '变更积分',
  `before` int(10) NOT NULL DEFAULT '0' COMMENT '变更前积分',
  `after` int(10) NOT NULL DEFAULT '0' COMMENT '变更后积分',
  `memo` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='会员积分变动表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_user_token`
--

DROP TABLE IF EXISTS `tp_user_token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_user_token` (
  `token` varchar(50) NOT NULL COMMENT 'Token',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `createtime` int(10) DEFAULT NULL COMMENT '创建时间',
  `expiretime` int(10) DEFAULT NULL COMMENT '过期时间',
  PRIMARY KEY (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='会员Token表';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_users`
--

DROP TABLE IF EXISTS `tp_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_users` (
  `id` mediumint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自动编号',
  `username` varchar(255) NOT NULL COMMENT '用户名',
  `password` char(40) NOT NULL COMMENT '用户密码',
  `email` varchar(50) NOT NULL COMMENT '邮箱地址',
  `phone` varchar(50) NOT NULL COMMENT '手机号码',
  `img` varchar(255) NOT NULL DEFAULT '/static/images/face.png' COMMENT '用户头像',
  `frame_img` varchar(255) DEFAULT NULL,
  `banner_img` varchar(255) DEFAULT '/static/images/user-banner.jpg' COMMENT '信息页banner',
  `status` enum('2','1','0','') NOT NULL DEFAULT '1' COMMENT '状态:0=待审核,1=正常,2=封禁',
  `team_id` int(50) DEFAULT NULL COMMENT '所属战队',
  `score` int(50) unsigned NOT NULL DEFAULT '0' COMMENT '总分',
  `flag_count` int(50) unsigned NOT NULL DEFAULT '0' COMMENT '解题总数',
  `flag_web` int(50) unsigned NOT NULL DEFAULT '0' COMMENT 'web已解题数',
  `flag_misc` int(50) unsigned NOT NULL DEFAULT '0' COMMENT 'misc已解题数',
  `flag_crypto` int(50) unsigned NOT NULL DEFAULT '0' COMMENT 'crypto已解题数',
  `flag_pwn` int(50) unsigned NOT NULL DEFAULT '0' COMMENT 'pwn已解题数',
  `flag_reverse` int(50) unsigned NOT NULL DEFAULT '0' COMMENT 'reverse已解题数',
  `prev_time` datetime DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `create_time` datetime NOT NULL DEFAULT '1970-01-01 00:00:00' COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tp_version`
--

DROP TABLE IF EXISTS `tp_version`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tp_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `oldversion` varchar(30) NOT NULL DEFAULT '' COMMENT '旧版本号',
  `newversion` varchar(30) NOT NULL DEFAULT '' COMMENT '新版本号',
  `packagesize` varchar(30) NOT NULL DEFAULT '' COMMENT '包大小',
  `content` varchar(500) NOT NULL DEFAULT '' COMMENT '升级内容',
  `downloadurl` varchar(255) NOT NULL DEFAULT '' COMMENT '下载地址',
  `enforce` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '强制更新',
  `createtime` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` varchar(30) NOT NULL DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='版本表';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-09-13 16:46:54
