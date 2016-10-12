/*
Navicat MySQL Data Transfer

Source Server         : 182.254.211.128-外网razor-日志库从库
Source Server Version : 50629
Source Host           : 182.254.211.128:3306
Source Database       : razor

Target Server Type    : MYSQL
Target Server Version : 50629
File Encoding         : 65001

Date: 2016-04-28 11:52:42
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for razor_alert
-- ----------------------------
DROP TABLE IF EXISTS `razor_alert`;
CREATE TABLE `razor_alert` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `userid` int(50) NOT NULL,
  `productid` int(50) NOT NULL,
  `condition` float NOT NULL,
  `label` varchar(50) NOT NULL,
  `active` int(10) NOT NULL DEFAULT '1',
  `emails` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_alertdetail
-- ----------------------------
DROP TABLE IF EXISTS `razor_alertdetail`;
CREATE TABLE `razor_alertdetail` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `alertlabel` int(50) NOT NULL,
  `factdata` int(50) NOT NULL,
  `forecastdata` int(50) NOT NULL,
  `time` datetime NOT NULL,
  `states` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_cell_towers
-- ----------------------------
DROP TABLE IF EXISTS `razor_cell_towers`;
CREATE TABLE `razor_cell_towers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `clientdataid` int(50) NOT NULL,
  `cellid` varchar(50) NOT NULL,
  `lac` varchar(50) NOT NULL,
  `mcc` varchar(50) NOT NULL,
  `mnc` varchar(50) NOT NULL,
  `age` varchar(50) NOT NULL,
  `signalstrength` varchar(50) NOT NULL,
  `timingadvance` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_channel
-- ----------------------------
DROP TABLE IF EXISTS `razor_channel`;
CREATE TABLE `razor_channel` (
  `channel_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '渠道ID',
  `channel_name` varchar(255) NOT NULL DEFAULT '' COMMENT '渠道名称',
  `create_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '渠道创建时间',
  `user_id` int(11) NOT NULL DEFAULT '1' COMMENT 'Razor账户ID',
  `type` enum('system','user') NOT NULL DEFAULT 'system' COMMENT 'Razor账户类型(管理员、用户)',
  `platform` int(10) NOT NULL COMMENT '手机操作系统平台ID',
  `active` int(10) NOT NULL DEFAULT '1' COMMENT '是否激活',
  PRIMARY KEY (`channel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_channel_product
-- ----------------------------
DROP TABLE IF EXISTS `razor_channel_product`;
CREATE TABLE `razor_channel_product` (
  `cp_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(5000) DEFAULT NULL,
  `updateurl` varchar(2000) NOT NULL DEFAULT '',
  `entrypoint` varchar(500) NOT NULL DEFAULT '',
  `location` varchar(500) NOT NULL DEFAULT '',
  `version` varchar(50) NOT NULL DEFAULT '',
  `date` datetime NOT NULL,
  `productkey` varchar(50) NOT NULL,
  `man` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '1',
  `product_id` int(11) NOT NULL DEFAULT '0',
  `channel_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_ci_sessions
-- ----------------------------
DROP TABLE IF EXISTS `razor_ci_sessions`;
CREATE TABLE `razor_ci_sessions` (
  `session_id` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(45) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_clientdata
-- ----------------------------
DROP TABLE IF EXISTS `razor_clientdata`;
CREATE TABLE `razor_clientdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `serviceversion` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `version` varchar(50) NOT NULL COMMENT '版本号',
  `appId` varchar(50) NOT NULL COMMENT '应用编号',
  `platform` enum('3','2','1') NOT NULL DEFAULT '1' COMMENT '手机操作系统编号',
  `osversion` varchar(50) DEFAULT NULL COMMENT '手机操作系统版本',
  `osaddtional` varchar(50) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL COMMENT '语言',
  `resolution` varchar(50) DEFAULT NULL COMMENT '分辨率',
  `ismobiledevice` varchar(50) DEFAULT NULL COMMENT '是否是手机',
  `devicename` varchar(50) DEFAULT NULL COMMENT '设备名称',
  `deviceid` varchar(200) NOT NULL COMMENT '设备编号',
  `defaultbrowser` varchar(50) DEFAULT NULL COMMENT '默认浏览器',
  `javasupport` varchar(50) DEFAULT NULL,
  `flashversion` varchar(50) DEFAULT NULL,
  `modulename` varchar(50) DEFAULT NULL,
  `imei` varchar(50) DEFAULT NULL,
  `imsi` varchar(50) DEFAULT NULL,
  `salt` varchar(64) DEFAULT NULL,
  `havegps` varchar(50) DEFAULT NULL,
  `havebt` varchar(50) DEFAULT NULL,
  `havewifi` varchar(50) DEFAULT NULL,
  `havegravity` varchar(50) DEFAULT NULL,
  `wifimac` varchar(50) DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `date` datetime NOT NULL COMMENT '时间',
  `clientip` varchar(50) DEFAULT NULL COMMENT '设备IP地址',
  `productkey` varchar(50) NOT NULL COMMENT '应用key值',
  `service_supplier` varchar(64) DEFAULT NULL,
  `country` varchar(50) DEFAULT 'unknown' COMMENT '国家',
  `region` varchar(50) DEFAULT 'unknown' COMMENT '地区',
  `city` varchar(50) DEFAULT 'unknown' COMMENT '城市',
  `street` varchar(500) DEFAULT NULL COMMENT '街道',
  `streetno` varchar(50) DEFAULT NULL,
  `postcode` varchar(50) DEFAULT NULL,
  `network` varchar(128) DEFAULT '1',
  `isjailbroken` int(10) DEFAULT '0',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  `useridentifier` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `insertdate` (`insertdate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_clientusinglog
-- ----------------------------
DROP TABLE IF EXISTS `razor_clientusinglog`;
CREATE TABLE `razor_clientusinglog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(500) NOT NULL,
  `start_millis` datetime NOT NULL,
  `end_millis` datetime NOT NULL,
  `duration` int(50) NOT NULL,
  `activities` varchar(500) NOT NULL,
  `appkey` varchar(500) NOT NULL,
  `version` varchar(50) NOT NULL,
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `insertdate` (`insertdate`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_coinconsume
-- ----------------------------
DROP TABLE IF EXISTS `razor_coinconsume`;
CREATE TABLE `razor_coinconsume` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `coinconsume_date` date NOT NULL COMMENT '金币消耗日期',
  `chId` varchar(255) NOT NULL COMMENT '渠道编号。由Razor管理员提供',
  `subSrvId` varchar(255) DEFAULT NULL COMMENT '合区区服编号。由Razor管理员提供',
  `srvId` varchar(255) NOT NULL COMMENT '区服编号。由Razor管理员提供',
  `appId` varchar(255) NOT NULL COMMENT '产品ID。由Razor管理员提供',
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `actionid` varchar(255) NOT NULL COMMENT '行为id',
  `usecount` int(16) NOT NULL DEFAULT '0' COMMENT '使用次数',
  `coinconsume_count` int(16) NOT NULL DEFAULT '0' COMMENT '消耗金币数量',
  `couponconsume_count` int(16) NOT NULL DEFAULT '0' COMMENT '消耗礼券数量',
  `functionid` varchar(255) NOT NULL COMMENT '功能ID。例如：如：100002 表示：神位争霸。',
  `acionttypeid` varchar(255) NOT NULL COMMENT '行为类型ID。例如：如：003 表示：功能产出。',
  `userId` varchar(255) NOT NULL COMMENT '玩家用户ID',
  `productkey` varchar(255) NOT NULL COMMENT '应用key值。由Razor管理员提供',
  `deviceid` varchar(255) NOT NULL COMMENT '设备编号',
  `coinconsume_time` datetime NOT NULL COMMENT '金币消耗时间',
  `roleId` varchar(255) NOT NULL COMMENT '玩家角色编号',
  `roleName` varchar(255) NOT NULL COMMENT '玩家角色名称',
  `roleLevel` int(16) NOT NULL DEFAULT '0' COMMENT '玩家角色等级',
  `roleVip` int(8) DEFAULT NULL COMMENT '玩家角色VIP等级',
  `goldCoin` int(32) DEFAULT NULL COMMENT '金币存量',
  `sliverCoin` int(32) DEFAULT NULL COMMENT '银币存量',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`ID`),
  KEY `insertdate` (`insertdate`) USING BTREE,
  KEY `roleid` (`roleId`) USING BTREE,
  KEY `server` (`chId`,`srvId`,`appId`,`version`) USING BTREE,
  KEY `coinconsume_date` (`coinconsume_date`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_coingain
-- ----------------------------
DROP TABLE IF EXISTS `razor_coingain`;
CREATE TABLE `razor_coingain` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `coingain_date` date NOT NULL COMMENT '金币获得日期',
  `chId` varchar(255) NOT NULL COMMENT '渠道编号。由Razor管理员提供',
  `subSrvId` varchar(255) DEFAULT NULL COMMENT '合区区服编号。由Razor管理员提供',
  `srvId` varchar(255) NOT NULL COMMENT '区服编号。由Razor管理员提供',
  `appId` varchar(255) NOT NULL COMMENT '产品ID。由Razor管理员提供',
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `actionid` varchar(255) NOT NULL COMMENT '行为id',
  `coingain_count` int(16) NOT NULL DEFAULT '0' COMMENT '金币获得数量',
  `coupongain_count` int(16) NOT NULL DEFAULT '0' COMMENT '礼券获得数量',
  `functionid` varchar(255) NOT NULL COMMENT '功能ID。例如：如：100002 表示：神位争霸。',
  `acionttypeid` varchar(255) NOT NULL COMMENT '行为类型ID。例如：如：003 表示：功能产出。',
  `userId` varchar(255) NOT NULL COMMENT '玩家用户ID',
  `productkey` varchar(255) NOT NULL COMMENT '应用key值。由Razor管理员提供',
  `deviceid` varchar(255) NOT NULL COMMENT '设备编号',
  `coingain_time` datetime NOT NULL COMMENT '金币获得时间',
  `roleId` varchar(255) NOT NULL COMMENT '玩家角色编号',
  `roleName` varchar(255) NOT NULL COMMENT '玩家角色名称',
  `roleLevel` int(16) NOT NULL DEFAULT '0' COMMENT '玩家角色等级',
  `roleVip` int(8) DEFAULT NULL COMMENT '玩家角色VIP等级',
  `goldCoin` int(32) DEFAULT NULL COMMENT '金币存量',
  `sliverCoin` int(32) DEFAULT NULL COMMENT '银币存量',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`ID`),
  KEY `insertdate` (`insertdate`) USING BTREE,
  KEY `roleid` (`roleId`) USING BTREE,
  KEY `server` (`chId`,`srvId`,`appId`,`version`) USING BTREE,
  KEY `coingain_date` (`coingain_date`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_config
-- ----------------------------
DROP TABLE IF EXISTS `razor_config`;
CREATE TABLE `razor_config` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `autogetlocation` tinyint(1) NOT NULL DEFAULT '1',
  `updateonlywifi` tinyint(1) NOT NULL DEFAULT '1',
  `product_id` int(50) NOT NULL,
  `sessionmillis` int(50) NOT NULL DEFAULT '30',
  `reportpolicy` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_createrole
-- ----------------------------
DROP TABLE IF EXISTS `razor_createrole`;
CREATE TABLE `razor_createrole` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `create_role_date` date NOT NULL COMMENT '玩家角色创建日期',
  `chId` varchar(255) NOT NULL COMMENT '渠道编号',
  `subSrvId` varchar(255) DEFAULT NULL COMMENT '合区区服编号',
  `srvId` varchar(255) DEFAULT NULL COMMENT '区服编号',
  `appId` varchar(255) NOT NULL COMMENT '应用编号',
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `obligate1` varchar(255) DEFAULT NULL COMMENT '预留字段一',
  `obligate2` varchar(255) DEFAULT NULL COMMENT '预留字段二',
  `obligate3` varchar(255) DEFAULT NULL COMMENT '预留字段三',
  `obligate4` varchar(255) DEFAULT NULL COMMENT '预留字段四',
  `obligate5` varchar(255) DEFAULT NULL COMMENT '预留字段五',
  `obligate6` varchar(255) DEFAULT NULL COMMENT '预留字段六',
  `userId` varchar(255) NOT NULL COMMENT 'Razor用户编号',
  `productkey` varchar(255) NOT NULL COMMENT '应用key值',
  `deviceid` varchar(255) NOT NULL COMMENT '设备编号',
  `create_role_time` datetime NOT NULL COMMENT '玩家角色创建时间',
  `roleId` varchar(255) NOT NULL COMMENT '玩家角色编号',
  `roleName` varchar(255) NOT NULL COMMENT '玩家角色名称',
  `roleLevel` int(8) NOT NULL COMMENT '玩家角色等级',
  `roleSex` varchar(255) DEFAULT NULL COMMENT '玩家角色性别',
  `roleVip` int(8) DEFAULT NULL COMMENT '玩家角色VIP等级',
  `goldCoin` int(32) DEFAULT NULL COMMENT '金币存量',
  `sliverCoin` int(32) DEFAULT NULL COMMENT '银币存量',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`ID`),
  KEY `insertdate` (`insertdate`) USING BTREE,
  KEY `roleid` (`roleId`) USING BTREE,
  KEY `deviceid` (`deviceid`) USING BTREE,
  KEY `date` (`create_role_date`) USING BTREE,
  KEY `server` (`chId`,`srvId`,`appId`,`version`) USING BTREE,
  KEY `rolelevel` (`create_role_time`,`roleLevel`)
) ENGINE=InnoDB AUTO_INCREMENT=60712 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_device_tag
-- ----------------------------
DROP TABLE IF EXISTS `razor_device_tag`;
CREATE TABLE `razor_device_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deviceid` varchar(256) NOT NULL,
  `tags` varchar(1024) DEFAULT NULL,
  `appkey` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_deviceboot
-- ----------------------------
DROP TABLE IF EXISTS `razor_deviceboot`;
CREATE TABLE `razor_deviceboot` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `deviceboot_date` date NOT NULL COMMENT '设备启动日期',
  `chId` varchar(255) NOT NULL COMMENT '渠道编号',
  `appId` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `productkey` varchar(255) NOT NULL COMMENT '应用key值',
  `deviceid` varchar(255) NOT NULL COMMENT '设备编号',
  `deviceboot_time` datetime NOT NULL COMMENT '设备启动时间',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`ID`),
  KEY `insertdate` (`insertdate`) USING BTREE,
  KEY `server` (`chId`,`appId`,`version`) USING BTREE,
  KEY `deviceboot_date` (`deviceboot_date`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5002 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_errorcode
-- ----------------------------
DROP TABLE IF EXISTS `razor_errorcode`;
CREATE TABLE `razor_errorcode` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `errorcode_date` date NOT NULL COMMENT '错误码日期',
  `chId` varchar(255) NOT NULL COMMENT '渠道编号。由Razor管理员提供',
  `subSrvId` varchar(255) DEFAULT NULL COMMENT '合区区服编号。由Razor管理员提供',
  `srvId` varchar(255) NOT NULL COMMENT '区服编号。由Razor管理员提供',
  `appId` varchar(255) NOT NULL COMMENT '产品ID。由Razor管理员提供',
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `errorcodeid` varchar(255) NOT NULL COMMENT '错误码id',
  `aciontid` varchar(255) NOT NULL COMMENT '行为id',
  `functionid` varchar(255) NOT NULL COMMENT '功能ID。例如：如：100002表示：神位争霸',
  `userId` varchar(255) NOT NULL COMMENT '玩家用户ID',
  `productkey` varchar(255) NOT NULL COMMENT '应用key值。由Razor管理员提供',
  `deviceid` varchar(255) NOT NULL COMMENT '设备编号',
  `errorcode_time` datetime NOT NULL COMMENT '错误码时间',
  `roleId` varchar(255) NOT NULL COMMENT '玩家角色编号',
  `roleName` varchar(255) NOT NULL COMMENT '玩家角色名称',
  `roleLevel` int(11) NOT NULL,
  `roleVip` int(11) NOT NULL,
  `goldCoin` int(11) NOT NULL,
  `sliverCoin` int(11) NOT NULL,
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`ID`),
  KEY `insertdate` (`insertdate`) USING BTREE,
  KEY `roleid` (`roleId`) USING BTREE,
  KEY `server` (`chId`,`srvId`,`appId`,`version`) USING BTREE,
  KEY `errorcode_date` (`errorcode_date`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_errorlog
-- ----------------------------
DROP TABLE IF EXISTS `razor_errorlog`;
CREATE TABLE `razor_errorlog` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `appkey` varchar(50) NOT NULL,
  `device` varchar(50) NOT NULL,
  `os_version` varchar(50) NOT NULL,
  `activity` varchar(50) NOT NULL,
  `time` datetime NOT NULL,
  `title` text NOT NULL,
  `stacktrace` text NOT NULL,
  `version` varchar(50) NOT NULL,
  `isfix` int(11) DEFAULT NULL,
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `insertdate` (`insertdate`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_event_defination
-- ----------------------------
DROP TABLE IF EXISTS `razor_event_defination`;
CREATE TABLE `razor_event_defination` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_identifier` varchar(50) NOT NULL,
  `productkey` char(50) NOT NULL,
  `event_name` char(50) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`event_id`),
  UNIQUE KEY `channel_id` (`channel_id`,`product_id`,`user_id`,`event_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_eventdata
-- ----------------------------
DROP TABLE IF EXISTS `razor_eventdata`;
CREATE TABLE `razor_eventdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deviceid` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `event` varchar(50) DEFAULT NULL,
  `label` varchar(50) DEFAULT NULL,
  `attachment` varchar(50) DEFAULT NULL,
  `clientdate` datetime NOT NULL,
  `productkey` varchar(64) NOT NULL DEFAULT 'no_key',
  `num` int(50) NOT NULL DEFAULT '1',
  `event_id` int(50) NOT NULL,
  `version` varchar(50) NOT NULL,
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `insertdate` (`insertdate`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_experiencevariation
-- ----------------------------
DROP TABLE IF EXISTS `razor_experiencevariation`;
CREATE TABLE `razor_experiencevariation` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `experiencevariation_date` date NOT NULL COMMENT '经验变化日期',
  `chId` varchar(255) NOT NULL COMMENT '渠道编号。由Razor管理员提供',
  `subSrvId` varchar(255) DEFAULT NULL COMMENT '合区区服编号。由Razor管理员提供',
  `srvId` varchar(255) NOT NULL COMMENT '区服编号。由Razor管理员提供',
  `appId` varchar(255) NOT NULL COMMENT '产品ID。由Razor管理员提供',
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `actionid` varchar(255) NOT NULL COMMENT '行为id',
  `experience` varchar(255) NOT NULL COMMENT '经验数',
  `userId` varchar(255) NOT NULL COMMENT '玩家用户ID',
  `productkey` varchar(255) NOT NULL COMMENT '应用key值。由Razor管理员提供',
  `deviceid` varchar(255) NOT NULL COMMENT '设备编号',
  `experiencevariation_time` datetime NOT NULL COMMENT '经验变化时间',
  `roleId` varchar(255) NOT NULL COMMENT '玩家角色编号',
  `roleName` varchar(255) NOT NULL COMMENT '玩家角色名称',
  `roleLevel` int(16) NOT NULL DEFAULT '0' COMMENT '玩家角色等级',
  `roleVip` int(8) DEFAULT NULL COMMENT '玩家角色VIP等级',
  `goldCoin` int(32) DEFAULT NULL COMMENT '金币存量',
  `sliverCoin` int(32) DEFAULT NULL COMMENT '银币存量',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`ID`),
  KEY `insertdate` (`insertdate`) USING BTREE,
  KEY `roleid` (`roleId`) USING BTREE,
  KEY `server` (`chId`,`srvId`,`appId`,`version`) USING BTREE,
  KEY `experiencevariation_date` (`experiencevariation_date`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=143912 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_functioncount
-- ----------------------------
DROP TABLE IF EXISTS `razor_functioncount`;
CREATE TABLE `razor_functioncount` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `functioncount_date` date NOT NULL COMMENT '功能统计日期',
  `chId` varchar(255) NOT NULL COMMENT '渠道编号。由Razor管理员提供',
  `subSrvId` varchar(255) DEFAULT NULL COMMENT '合区区服编号。由Razor管理员提供',
  `srvId` varchar(255) NOT NULL COMMENT '区服编号。由Razor管理员提供',
  `appId` varchar(255) NOT NULL COMMENT '产品ID。由Razor管理员提供',
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `eventid` varchar(255) NOT NULL COMMENT '事件id',
  `issue` varchar(255) NOT NULL COMMENT '活动期号',
  `functionid` varchar(255) NOT NULL COMMENT '功能ID。例如：如：100002表示：神位争霸',
  `userId` varchar(255) NOT NULL COMMENT '玩家用户ID',
  `productkey` varchar(255) NOT NULL COMMENT '应用key值。由Razor管理员提供',
  `deviceid` varchar(255) NOT NULL COMMENT '设备编号',
  `functioncount_time` datetime NOT NULL COMMENT '功能统计时间',
  `roleId` varchar(255) NOT NULL COMMENT '玩家角色编号',
  `roleName` varchar(255) NOT NULL COMMENT '玩家角色名称',
  `roleLevel` int(11) NOT NULL,
  `roleVip` int(11) NOT NULL,
  `goldCoin` int(11) NOT NULL,
  `sliverCoin` int(11) NOT NULL,
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`ID`),
  KEY `insertdate` (`insertdate`) USING BTREE,
  KEY `functioncount_date` (`functioncount_date`,`appId`,`chId`,`srvId`,`version`) USING BTREE,
  KEY `functionid` (`functionid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2479082 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_getui_product
-- ----------------------------
DROP TABLE IF EXISTS `razor_getui_product`;
CREATE TABLE `razor_getui_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `is_active` tinyint(4) DEFAULT NULL,
  `app_id` varchar(25) DEFAULT NULL,
  `user_id` int(8) DEFAULT NULL,
  `app_key` varchar(25) NOT NULL,
  `app_secret` varchar(25) NOT NULL,
  `app_mastersecret` varchar(25) NOT NULL,
  `app_identifier` varchar(25) NOT NULL,
  `activate_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_helpiteminfo
-- ----------------------------
DROP TABLE IF EXISTS `razor_helpiteminfo`;
CREATE TABLE `razor_helpiteminfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `helpitemid` int(11) DEFAULT NULL,
  `helptype` varchar(255) DEFAULT NULL COMMENT '术语定义分类',
  `helpitem` varchar(255) DEFAULT NULL COMMENT '术语定义',
  `equation` varchar(255) DEFAULT NULL COMMENT '缩写/公式',
  `statisticalcycle` varchar(255) DEFAULT NULL COMMENT '统计周期',
  `explanationofnouns` varchar(1024) DEFAULT NULL COMMENT '术语解释',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1082 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_install
-- ----------------------------
DROP TABLE IF EXISTS `razor_install`;
CREATE TABLE `razor_install` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `install_date` date NOT NULL COMMENT '安装日期',
  `chId` varchar(255) NOT NULL COMMENT '渠道编号',
  `appId` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `productkey` varchar(255) NOT NULL COMMENT '应用key值',
  `deviceid` varchar(255) NOT NULL COMMENT '设备编号',
  `install_time` datetime NOT NULL COMMENT '安装时间',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`ID`),
  KEY `insertdate` (`insertdate`) USING BTREE,
  KEY `server` (`chId`,`appId`,`version`) USING BTREE,
  KEY `install_date` (`install_date`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_levelupgrade
-- ----------------------------
DROP TABLE IF EXISTS `razor_levelupgrade`;
CREATE TABLE `razor_levelupgrade` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `levelupgrade_date` date NOT NULL COMMENT '玩家等级升级日期',
  `chId` varchar(255) NOT NULL COMMENT '渠道编号',
  `subSrvId` varchar(255) DEFAULT NULL COMMENT '合区区服编号',
  `srvId` varchar(255) NOT NULL COMMENT '区服编号',
  `appId` varchar(255) NOT NULL COMMENT '应用编号',
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `userId` varchar(255) NOT NULL COMMENT 'Razor用户编号',
  `productkey` varchar(255) NOT NULL COMMENT '应用key值',
  `deviceid` varchar(255) NOT NULL COMMENT '设备编号',
  `levelupgrade_time` datetime NOT NULL COMMENT '玩家等级升级时间',
  `roleId` varchar(255) NOT NULL COMMENT '玩家角色编号',
  `roleName` varchar(255) NOT NULL COMMENT '玩家角色名称',
  `roleLevel` int(8) NOT NULL COMMENT '玩家当前角色等级',
  `roleVip` int(8) NOT NULL COMMENT '玩家角色VIP等级',
  `goldCoin` int(32) NOT NULL COMMENT '金币存量',
  `sliverCoin` int(32) NOT NULL COMMENT '银币存量',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`ID`),
  KEY `insertdate` (`insertdate`) USING BTREE,
  KEY `levelupgrade_date` (`levelupgrade_date`) USING BTREE,
  KEY `server` (`chId`,`srvId`,`appId`,`version`) USING BTREE,
  KEY `rolelevel` (`roleLevel`) USING BTREE,
  KEY `levelupgrade_time` (`levelupgrade_time`)
) ENGINE=InnoDB AUTO_INCREMENT=96742 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_login
-- ----------------------------
DROP TABLE IF EXISTS `razor_login`;
CREATE TABLE `razor_login` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `login_date` date NOT NULL COMMENT '登录日期',
  `chId` varchar(255) NOT NULL COMMENT '渠道编号',
  `subSrvId` varchar(255) DEFAULT NULL COMMENT '合区区服编号',
  `srvId` varchar(255) DEFAULT NULL COMMENT '区服编号',
  `appId` varchar(255) NOT NULL COMMENT '应用编号',
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `type` varchar(32) NOT NULL COMMENT '类型(上线、下线)',
  `offlineSettleTime` varchar(32) DEFAULT NULL COMMENT '下线结算时长',
  `obligate1` varchar(255) DEFAULT NULL COMMENT '预留字段一',
  `obligate2` varchar(255) DEFAULT NULL COMMENT '预留字段一',
  `obligate3` varchar(255) DEFAULT NULL COMMENT '预留字段一',
  `obligate4` varchar(255) DEFAULT NULL COMMENT '预留字段一',
  `userId` varchar(255) NOT NULL COMMENT 'Razor用户编号',
  `roleId` varchar(255) NOT NULL COMMENT '玩家角色编号',
  `roleLevel` int(8) NOT NULL COMMENT '玩家角色等级',
  `roleVip` int(8) DEFAULT NULL COMMENT '玩家角色VIP等级',
  `goldCoin` int(16) DEFAULT NULL COMMENT '金币存量',
  `sliverCoin` int(16) DEFAULT NULL COMMENT '银币存量',
  `productkey` varchar(255) NOT NULL COMMENT '应用key值',
  `login_time` datetime NOT NULL COMMENT '登录时间',
  `deviceid` varchar(255) NOT NULL COMMENT '设备编号',
  `ip` varchar(255) NOT NULL COMMENT '设备IP地址',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`ID`),
  KEY `insertdate` (`insertdate`) USING BTREE,
  KEY `roleid` (`roleId`) USING BTREE,
  KEY `deviceid` (`deviceid`) USING BTREE,
  KEY `login_date` (`login_date`) USING BTREE,
  KEY `type` (`type`) USING BTREE,
  KEY `server` (`chId`,`srvId`,`appId`,`version`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=192722 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_login_attempts
-- ----------------------------
DROP TABLE IF EXISTS `razor_login_attempts`;
CREATE TABLE `razor_login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `login` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_mainconfig_action
-- ----------------------------
DROP TABLE IF EXISTS `razor_mainconfig_action`;
CREATE TABLE `razor_mainconfig_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `app_id` int(11) NOT NULL DEFAULT '0' COMMENT '游戏ID（由razor管理员提供）',
  `action_id` int(11) NOT NULL DEFAULT '0' COMMENT '行为ID(编号唯一)',
  `action_name` varchar(255) NOT NULL COMMENT '行为名称',
  `action_desc` varchar(1024) NOT NULL COMMENT '行为描述',
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_action_id` (`app_id`,`action_id`) USING BTREE,
  KEY `action_id` (`action_id`) USING BTREE,
  KEY `app_id` (`app_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=512 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_mainconfig_actiontype
-- ----------------------------
DROP TABLE IF EXISTS `razor_mainconfig_actiontype`;
CREATE TABLE `razor_mainconfig_actiontype` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `app_id` int(11) NOT NULL DEFAULT '0' COMMENT '游戏ID（由razor管理员提供）',
  `actiontype_id` varchar(11) NOT NULL DEFAULT '0' COMMENT '编号(编号唯一)',
  `actiontype_name` varchar(255) NOT NULL COMMENT '名称',
  `action_category` varchar(128) NOT NULL COMMENT '分类',
  `split` enum('0','1') NOT NULL DEFAULT '0' COMMENT '是否拆分',
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_actiontype_id` (`app_id`,`actiontype_id`) USING BTREE,
  KEY `app_id` (`app_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_mainconfig_branchlinetask
-- ----------------------------
DROP TABLE IF EXISTS `razor_mainconfig_branchlinetask`;
CREATE TABLE `razor_mainconfig_branchlinetask` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `app_id` int(11) NOT NULL DEFAULT '0' COMMENT '游戏ID（由razor管理员提供）',
  `task_id` int(11) NOT NULL DEFAULT '0' COMMENT '任务ID',
  `task_name` varchar(255) NOT NULL COMMENT '任务名称',
  `step_id` int(11) NOT NULL DEFAULT '0' COMMENT '步骤',
  `step_name` varchar(255) NOT NULL COMMENT '步骤名称',
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_task_step_id` (`app_id`,`task_id`,`step_id`) USING BTREE,
  KEY `app_id` (`app_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=372 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_mainconfig_errorcode
-- ----------------------------
DROP TABLE IF EXISTS `razor_mainconfig_errorcode`;
CREATE TABLE `razor_mainconfig_errorcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `app_id` int(11) NOT NULL DEFAULT '0' COMMENT '游戏ID（由razor管理员提供）',
  `errorcode_id` int(11) NOT NULL DEFAULT '0' COMMENT '错误码ID',
  `errorcode_name` varchar(255) NOT NULL COMMENT '错误码名称',
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_errorcode_id` (`app_id`,`errorcode_id`) USING BTREE,
  KEY `app_id` (`app_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=322 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_mainconfig_function
-- ----------------------------
DROP TABLE IF EXISTS `razor_mainconfig_function`;
CREATE TABLE `razor_mainconfig_function` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `app_id` int(11) NOT NULL DEFAULT '0' COMMENT '游戏ID（由razor管理员提供）',
  `action_id` int(11) NOT NULL DEFAULT '0' COMMENT '行为编号(编号唯一)',
  `action_name` varchar(255) NOT NULL COMMENT '行为名称',
  `function_id` int(11) NOT NULL DEFAULT '0' COMMENT '功能ID',
  `function_name` varchar(128) NOT NULL COMMENT '功能名称',
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_action_function_id` (`app_id`,`action_id`,`function_id`) USING BTREE,
  KEY `app_id` (`app_id`) USING BTREE,
  KEY `function_id` (`function_id`) USING BTREE,
  KEY `function_name` (`function_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=512 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_mainconfig_generaltask
-- ----------------------------
DROP TABLE IF EXISTS `razor_mainconfig_generaltask`;
CREATE TABLE `razor_mainconfig_generaltask` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `app_id` int(11) NOT NULL DEFAULT '0' COMMENT '游戏ID（由razor管理员提供）',
  `task_id` int(11) NOT NULL DEFAULT '0' COMMENT '任务ID',
  `task_name` varchar(255) NOT NULL COMMENT '任务名称',
  `step_id` int(11) NOT NULL DEFAULT '0' COMMENT '步骤',
  `step_name` varchar(255) NOT NULL COMMENT '步骤名称',
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_task_step_id` (`app_id`,`task_id`,`step_id`) USING BTREE,
  KEY `app_id` (`app_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=352 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_mainconfig_mainlinetask
-- ----------------------------
DROP TABLE IF EXISTS `razor_mainconfig_mainlinetask`;
CREATE TABLE `razor_mainconfig_mainlinetask` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `app_id` int(11) NOT NULL DEFAULT '0' COMMENT '游戏ID（由razor管理员提供）',
  `task_id` int(11) NOT NULL DEFAULT '0' COMMENT '任务ID',
  `task_name` varchar(255) NOT NULL COMMENT '任务名称',
  `step_id` int(11) NOT NULL DEFAULT '0' COMMENT '步骤',
  `step_name` varchar(255) NOT NULL COMMENT '步骤名称',
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_task_step_id` (`app_id`,`task_id`,`step_id`) USING BTREE,
  KEY `app_id` (`app_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1462 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_mainconfig_newserveractivity
-- ----------------------------
DROP TABLE IF EXISTS `razor_mainconfig_newserveractivity`;
CREATE TABLE `razor_mainconfig_newserveractivity` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `app_id` int(11) NOT NULL DEFAULT '0' COMMENT '游戏ID（由razor管理员提供）',
  `newserveractivity_id` int(11) NOT NULL DEFAULT '0' COMMENT '编号(编号唯一)',
  `newserveractivity_name` varchar(255) NOT NULL COMMENT '名称',
  `newserveractivity_issue` varchar(255) NOT NULL DEFAULT '0' COMMENT '期号',
  `startdate` date NOT NULL COMMENT '开始日期',
  `enddate` date NOT NULL COMMENT '结束日期',
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_newserveractivity_id` (`app_id`,`newserveractivity_id`,`newserveractivity_issue`) USING BTREE,
  KEY `app_id` (`app_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_mainconfig_newuserprogress
-- ----------------------------
DROP TABLE IF EXISTS `razor_mainconfig_newuserprogress`;
CREATE TABLE `razor_mainconfig_newuserprogress` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `app_id` int(11) NOT NULL DEFAULT '0' COMMENT '游戏ID（由razor管理员提供）',
  `newuserprogress_id` int(11) NOT NULL DEFAULT '0' COMMENT '步骤ID',
  `newuserprogress_name` varchar(255) NOT NULL COMMENT '步骤名称',
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_newuserprogress_id` (`app_id`,`newuserprogress_id`) USING BTREE,
  KEY `app_id` (`app_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=252 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_mainconfig_newusertask
-- ----------------------------
DROP TABLE IF EXISTS `razor_mainconfig_newusertask`;
CREATE TABLE `razor_mainconfig_newusertask` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `app_id` int(11) NOT NULL DEFAULT '0' COMMENT '游戏ID（由razor管理员提供）',
  `task_id` int(11) NOT NULL DEFAULT '0' COMMENT '任务ID',
  `task_name` varchar(255) NOT NULL COMMENT '任务名称',
  `step_id` int(11) NOT NULL DEFAULT '0' COMMENT '步骤',
  `step_name` varchar(255) NOT NULL COMMENT '步骤名称',
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_task_step_id` (`app_id`,`task_id`,`step_id`) USING BTREE,
  KEY `app_id` (`app_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_mainconfig_operationactivity
-- ----------------------------
DROP TABLE IF EXISTS `razor_mainconfig_operationactivity`;
CREATE TABLE `razor_mainconfig_operationactivity` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `app_id` int(11) NOT NULL DEFAULT '0' COMMENT '游戏ID（由razor管理员提供）',
  `operationactivity_id` int(11) NOT NULL DEFAULT '0' COMMENT '编号(编号唯一)',
  `operationactivity_name` varchar(255) NOT NULL COMMENT '名称',
  `operationactivity_issue` varchar(255) NOT NULL DEFAULT '0' COMMENT '期号',
  `startdate` date NOT NULL COMMENT '开始日期',
  `enddate` date NOT NULL COMMENT '结束日期',
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_operationactivity_id` (`app_id`,`operationactivity_id`,`operationactivity_issue`) USING BTREE,
  KEY `app_id` (`app_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_mainconfig_prop
-- ----------------------------
DROP TABLE IF EXISTS `razor_mainconfig_prop`;
CREATE TABLE `razor_mainconfig_prop` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `app_id` int(11) NOT NULL DEFAULT '0' COMMENT '游戏ID（由razor管理员提供）',
  `prop_id` int(11) NOT NULL DEFAULT '0' COMMENT '编号(编号唯一)',
  `prop_name` varchar(255) NOT NULL COMMENT '道具名称',
  `prop_category` varchar(255) NOT NULL COMMENT '分类',
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_prop_id` (`app_id`,`prop_id`) USING BTREE,
  KEY `prop_id` (`prop_id`) USING BTREE,
  KEY `app_id` (`app_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4832 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_mainconfig_propertymoney
-- ----------------------------
DROP TABLE IF EXISTS `razor_mainconfig_propertymoney`;
CREATE TABLE `razor_mainconfig_propertymoney` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `app_id` int(11) NOT NULL DEFAULT '0' COMMENT '游戏ID（由razor管理员提供）',
  `propertymoney_id` int(11) NOT NULL DEFAULT '0' COMMENT '编号(编号唯一)',
  `propertymoney_name` varchar(255) NOT NULL COMMENT '名称',
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_propertymoney_id` (`app_id`,`propertymoney_id`) USING BTREE,
  KEY `app_id` (`app_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_mainconfig_tollgate
-- ----------------------------
DROP TABLE IF EXISTS `razor_mainconfig_tollgate`;
CREATE TABLE `razor_mainconfig_tollgate` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `app_id` int(11) NOT NULL DEFAULT '0' COMMENT '游戏ID（由razor管理员提供）',
  `tollgate_bigcategory_id` int(11) NOT NULL DEFAULT '0' COMMENT '关卡大类id',
  `tollgate_bigcategory_name` varchar(255) NOT NULL COMMENT '关卡大类名称',
  `tollgate_smallcategory_id` int(11) NOT NULL DEFAULT '0' COMMENT '小关卡id',
  `tollgate_smallcategory_name` varchar(255) NOT NULL COMMENT '小关卡名称',
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_tollgate_id` (`app_id`,`tollgate_bigcategory_id`,`tollgate_smallcategory_id`) USING BTREE,
  KEY `app_id` (`app_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3152 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_markevent
-- ----------------------------
DROP TABLE IF EXISTS `razor_markevent`;
CREATE TABLE `razor_markevent` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `userid` int(50) NOT NULL,
  `productid` int(50) NOT NULL DEFAULT '-1',
  `title` varchar(45) NOT NULL,
  `description` varchar(128) NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT '0',
  `marktime` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_networktype
-- ----------------------------
DROP TABLE IF EXISTS `razor_networktype`;
CREATE TABLE `razor_networktype` (
  `id` int(8) NOT NULL,
  `type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_newuserguide
-- ----------------------------
DROP TABLE IF EXISTS `razor_newuserguide`;
CREATE TABLE `razor_newuserguide` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `newuserguide_date` date NOT NULL COMMENT '新手引导日期',
  `chId` varchar(255) NOT NULL COMMENT '渠道编号。由Razor管理员提供',
  `subSrvId` varchar(255) DEFAULT NULL COMMENT '合区区服编号。由Razor管理员提供',
  `srvId` varchar(255) NOT NULL COMMENT '区服编号。由Razor管理员提供',
  `appId` varchar(255) NOT NULL COMMENT '产品ID。由Razor管理员提供',
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `newuserguide_id` varchar(255) NOT NULL,
  `stepid` varchar(255) NOT NULL COMMENT '新手引导步骤id',
  `userId` varchar(255) NOT NULL COMMENT '玩家用户ID',
  `markid` varchar(255) DEFAULT NULL,
  `productkey` varchar(255) NOT NULL COMMENT '应用key值。由Razor管理员提供',
  `deviceid` varchar(255) NOT NULL COMMENT '设备编号',
  `newuserguide_time` datetime NOT NULL COMMENT '新手引导时间',
  `roleId` varchar(255) NOT NULL COMMENT '玩家角色编号',
  `roleName` varchar(255) NOT NULL COMMENT '玩家角色名称',
  `roleLevel` int(11) DEFAULT NULL,
  `roleVip` int(11) DEFAULT NULL,
  `goldCoin` int(11) DEFAULT NULL,
  `sliverCoin` int(11) DEFAULT NULL,
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`ID`),
  KEY `insertdate` (`insertdate`) USING BTREE,
  KEY `roleid` (`roleId`) USING BTREE,
  KEY `server` (`chId`,`srvId`,`appId`,`version`) USING BTREE,
  KEY `newuserguide_date` (`newuserguide_date`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=152382 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_newuserprogress
-- ----------------------------
DROP TABLE IF EXISTS `razor_newuserprogress`;
CREATE TABLE `razor_newuserprogress` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `newuserprogress_date` date NOT NULL COMMENT '新用户进度日期',
  `chId` varchar(255) NOT NULL COMMENT '渠道编号。由Razor管理员提供',
  `subSrvId` varchar(255) DEFAULT NULL COMMENT '合区区服编号。由Razor管理员提供',
  `srvId` varchar(255) NOT NULL COMMENT '区服编号。由Razor管理员提供',
  `appId` varchar(255) NOT NULL COMMENT '产品ID。由Razor管理员提供',
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `newuserprogressid` varchar(255) NOT NULL COMMENT '新用户进度id',
  `stepid` varchar(255) NOT NULL COMMENT '步骤id。1开始',
  `userId` varchar(255) NOT NULL COMMENT '玩家用户ID',
  `productkey` varchar(255) NOT NULL COMMENT '应用key值。由Razor管理员提供',
  `deviceid` varchar(255) NOT NULL COMMENT '设备编号',
  `newuserprogress_time` datetime NOT NULL COMMENT '新用户进度时间',
  `roleId` varchar(255) NOT NULL COMMENT '玩家角色编号',
  `roleName` varchar(255) NOT NULL COMMENT '玩家角色名称',
  `roleLevel` int(11) NOT NULL,
  `roleVip` int(11) NOT NULL,
  `goldCoin` int(11) NOT NULL,
  `sliverCoin` int(11) NOT NULL,
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`ID`),
  KEY `insertdate` (`insertdate`) USING BTREE,
  KEY `roleid` (`roleId`) USING BTREE,
  KEY `server` (`chId`,`srvId`,`appId`,`version`) USING BTREE,
  KEY `newuserprogress_date` (`newuserprogress_date`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14542 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_pay
-- ----------------------------
DROP TABLE IF EXISTS `razor_pay`;
CREATE TABLE `razor_pay` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `pay_date` date NOT NULL COMMENT '支付日期',
  `chId` varchar(255) NOT NULL COMMENT '渠道编号',
  `subSrvId` varchar(255) DEFAULT NULL COMMENT '合区区服编号',
  `srvId` varchar(255) DEFAULT NULL COMMENT '区服编号',
  `appId` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `obligate1` varchar(255) DEFAULT NULL COMMENT '预留字段一',
  `obligate2` varchar(255) DEFAULT NULL COMMENT '预留字段二',
  `obligate3` varchar(255) DEFAULT NULL COMMENT '预留字段三',
  `obligate4` varchar(255) DEFAULT NULL COMMENT '预留字段四',
  `userId` varchar(255) NOT NULL COMMENT 'Razor用户编号',
  `productkey` varchar(255) NOT NULL COMMENT '应用key值',
  `deviceid` varchar(255) NOT NULL COMMENT '设备编号',
  `pay_time` datetime NOT NULL COMMENT '支付时间',
  `roleId` varchar(255) NOT NULL COMMENT '玩家角色编号',
  `roleName` varchar(255) NOT NULL COMMENT '玩家角色名称',
  `roleLevel` int(16) NOT NULL DEFAULT '0' COMMENT '玩家角色等级',
  `roleVip` int(8) DEFAULT NULL COMMENT '玩家角色VIP等级',
  `goldCoin` int(32) DEFAULT NULL COMMENT '金币存量',
  `sliverCoin` int(32) DEFAULT NULL COMMENT '银币存量',
  `pay_unit` varchar(16) NOT NULL COMMENT '支付单位',
  `pay_type` varchar(255) NOT NULL COMMENT '支付类型',
  `pay_amount` int(16) NOT NULL COMMENT '支付金额',
  `coinAmount` int(32) DEFAULT NULL COMMENT '金币数量',
  `orderId` varchar(255) NOT NULL COMMENT '支付订单编号',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`ID`),
  KEY `insertdate` (`insertdate`) USING BTREE,
  KEY `roleid` (`roleId`) USING BTREE,
  KEY `server` (`chId`,`srvId`,`appId`,`version`) USING BTREE,
  KEY `pay_date` (`pay_date`) USING BTREE,
  KEY `rolelevel` (`roleLevel`) USING BTREE,
  KEY `pay_amount` (`pay_amount`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_platform
-- ----------------------------
DROP TABLE IF EXISTS `razor_platform`;
CREATE TABLE `razor_platform` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_plugins
-- ----------------------------
DROP TABLE IF EXISTS `razor_plugins`;
CREATE TABLE `razor_plugins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identifier` varchar(50) NOT NULL,
  `user_id` int(50) NOT NULL,
  `status` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_product
-- ----------------------------
DROP TABLE IF EXISTS `razor_product`;
CREATE TABLE `razor_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `name` varchar(50) NOT NULL COMMENT '应用名称',
  `description` varchar(5000) NOT NULL COMMENT '应用描述',
  `date` datetime NOT NULL COMMENT '应用创建时间',
  `user_id` int(11) NOT NULL DEFAULT '1' COMMENT 'Razor用户编号',
  `channel_count` int(11) NOT NULL DEFAULT '0' COMMENT '应用渠道数量',
  `product_key` varchar(50) NOT NULL COMMENT '应用key值',
  `product_platform` int(50) NOT NULL DEFAULT '1' COMMENT '应用平台编号',
  `category` int(50) NOT NULL DEFAULT '1' COMMENT '应用分类编号',
  `active` int(11) NOT NULL DEFAULT '1' COMMENT '是否激活',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_product_category
-- ----------------------------
DROP TABLE IF EXISTS `razor_product_category`;
CREATE TABLE `razor_product_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `level` int(50) NOT NULL DEFAULT '1',
  `parentid` int(11) NOT NULL DEFAULT '0',
  `active` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_product_version
-- ----------------------------
DROP TABLE IF EXISTS `razor_product_version`;
CREATE TABLE `razor_product_version` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `version` varchar(50) NOT NULL,
  `product_channel_id` int(50) NOT NULL,
  `updateurl` varchar(2000) NOT NULL,
  `updatetime` datetime NOT NULL,
  `description` varchar(5000) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_productfiles
-- ----------------------------
DROP TABLE IF EXISTS `razor_productfiles`;
CREATE TABLE `razor_productfiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `productid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `version` double NOT NULL,
  `type` varchar(50) NOT NULL,
  `updatedate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_propconsume
-- ----------------------------
DROP TABLE IF EXISTS `razor_propconsume`;
CREATE TABLE `razor_propconsume` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `propconsume_date` date NOT NULL COMMENT '道具消耗日期',
  `chId` varchar(255) NOT NULL COMMENT '渠道编号。由Razor管理员提供',
  `subSrvId` varchar(255) DEFAULT NULL COMMENT '合区区服编号。由Razor管理员提供',
  `srvId` varchar(255) NOT NULL COMMENT '区服编号。由Razor管理员提供',
  `appId` varchar(255) NOT NULL COMMENT '产品ID。由Razor管理员提供',
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `actionid` varchar(255) NOT NULL COMMENT '行为id',
  `propid` varchar(255) NOT NULL COMMENT '道具id',
  `proplevel` int(16) NOT NULL DEFAULT '0' COMMENT '道具等级',
  `propconsume_count` int(16) NOT NULL DEFAULT '0' COMMENT '道具消耗数量',
  `prop_stock` int(16) NOT NULL DEFAULT '0' COMMENT '道具存量',
  `functionid` varchar(255) NOT NULL COMMENT '功能ID。例如：如：100002 表示：神位争霸。',
  `acionttypeid` varchar(255) NOT NULL COMMENT '行为类型ID。例如：如：003 表示：功能产出。',
  `userId` varchar(255) NOT NULL COMMENT '玩家用户ID',
  `productkey` varchar(255) NOT NULL COMMENT '应用key值。由Razor管理员提供',
  `deviceid` varchar(255) NOT NULL COMMENT '设备编号',
  `propconsume_time` datetime NOT NULL COMMENT '道具消耗时间',
  `roleId` varchar(255) NOT NULL COMMENT '玩家角色编号',
  `roleName` varchar(255) NOT NULL COMMENT '玩家角色名称',
  `roleLevel` int(16) NOT NULL DEFAULT '0' COMMENT '玩家角色等级',
  `roleVip` int(8) DEFAULT NULL COMMENT '玩家角色VIP等级',
  `goldCoin` int(32) DEFAULT NULL COMMENT '金币存量',
  `sliverCoin` int(32) DEFAULT NULL COMMENT '银币存量',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`ID`),
  KEY `insertdate` (`insertdate`) USING BTREE,
  KEY `propconsume_date` (`propconsume_date`,`appId`,`chId`,`srvId`,`version`) USING BTREE,
  KEY `propid` (`propid`) USING BTREE,
  KEY `functionid` (`functionid`) USING BTREE,
  KEY `actionid` (`actionid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=834722 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_propertyvariation
-- ----------------------------
DROP TABLE IF EXISTS `razor_propertyvariation`;
CREATE TABLE `razor_propertyvariation` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `propertyvariation_date` date NOT NULL COMMENT '属性变化日期',
  `chId` varchar(255) NOT NULL COMMENT '渠道编号。由Razor管理员提供',
  `subSrvId` varchar(255) DEFAULT NULL COMMENT '合区区服编号。由Razor管理员提供',
  `srvId` varchar(255) NOT NULL COMMENT '区服编号。由Razor管理员提供',
  `appId` varchar(255) NOT NULL COMMENT '产品ID。由Razor管理员提供',
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `actionid` varchar(255) NOT NULL COMMENT '行为id',
  `property` varchar(255) NOT NULL COMMENT '属性。体力贡献值等等',
  `property_variation` int(16) NOT NULL DEFAULT '0' COMMENT '属性加减。0 减 1 加',
  `count` int(16) NOT NULL DEFAULT '0' COMMENT '数量',
  `stock` int(16) NOT NULL DEFAULT '0' COMMENT '当前存量',
  `functionid` varchar(255) NOT NULL COMMENT '功能ID。例如：如：100002 表示：神位争霸。',
  `acionttypeid` varchar(255) NOT NULL COMMENT '行为类型ID。例如：如：003 表示：功能加。 006 表示：功能减',
  `userId` varchar(255) NOT NULL COMMENT '玩家用户ID',
  `productkey` varchar(255) NOT NULL COMMENT '应用key值。由Razor管理员提供',
  `deviceid` varchar(255) NOT NULL COMMENT '设备编号',
  `propertyvariation_time` datetime NOT NULL COMMENT '属性变化时间',
  `roleId` varchar(255) NOT NULL COMMENT '玩家角色编号',
  `roleName` varchar(255) NOT NULL COMMENT '玩家角色名称',
  `roleLevel` int(16) NOT NULL DEFAULT '0' COMMENT '玩家角色等级',
  `roleVip` int(8) DEFAULT NULL COMMENT '玩家角色VIP等级',
  `goldCoin` int(32) DEFAULT NULL COMMENT '金币存量',
  `sliverCoin` int(32) DEFAULT NULL COMMENT '银币存量',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`ID`),
  KEY `insertdate` (`insertdate`) USING BTREE,
  KEY `propertyvariation_date` (`propertyvariation_date`,`appId`,`chId`,`srvId`,`version`) USING BTREE,
  KEY `property` (`property`,`property_variation`) USING BTREE,
  KEY `functionid` (`functionid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1081872 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_propgain
-- ----------------------------
DROP TABLE IF EXISTS `razor_propgain`;
CREATE TABLE `razor_propgain` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `propgain_date` date NOT NULL COMMENT '道具获得日期',
  `chId` varchar(255) NOT NULL COMMENT '渠道编号。由Razor管理员提供',
  `subSrvId` varchar(255) DEFAULT NULL COMMENT '合区区服编号。由Razor管理员提供',
  `srvId` varchar(255) NOT NULL COMMENT '区服编号。由Razor管理员提供',
  `appId` varchar(255) NOT NULL COMMENT '产品ID。由Razor管理员提供',
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `actionid` varchar(255) NOT NULL COMMENT '行为id',
  `propid` varchar(255) NOT NULL COMMENT '道具id',
  `proplevel` int(16) NOT NULL DEFAULT '0' COMMENT '道具等级',
  `propgain_count` int(16) NOT NULL DEFAULT '0' COMMENT '获得道具数量',
  `prop_stock` int(16) NOT NULL DEFAULT '0' COMMENT '道具存量',
  `functionid` varchar(255) NOT NULL COMMENT '功能ID。例如：如：100002 表示：神位争霸。',
  `acionttypeid` varchar(255) NOT NULL COMMENT '行为类型ID。例如：如：003 表示：功能产出。',
  `userId` varchar(255) NOT NULL COMMENT '玩家用户ID',
  `productkey` varchar(255) NOT NULL COMMENT '应用key值。由Razor管理员提供',
  `deviceid` varchar(255) NOT NULL COMMENT '设备编号',
  `propgain_time` datetime NOT NULL COMMENT '道具获得时间',
  `roleId` varchar(255) NOT NULL COMMENT '玩家角色编号',
  `roleName` varchar(255) NOT NULL COMMENT '玩家角色名称',
  `roleLevel` int(16) NOT NULL DEFAULT '0' COMMENT '玩家角色等级',
  `roleVip` int(8) DEFAULT NULL COMMENT '玩家角色VIP等级',
  `goldCoin` int(32) DEFAULT NULL COMMENT '金币存量',
  `sliverCoin` int(32) DEFAULT NULL COMMENT '银币存量',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`ID`),
  KEY `insertdate` (`insertdate`) USING BTREE,
  KEY `propgain_date` (`propgain_date`,`appId`,`chId`,`srvId`,`version`) USING BTREE,
  KEY `propid` (`propid`) USING BTREE,
  KEY `functionid` (`functionid`) USING BTREE,
  KEY `actionid` (`actionid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1061942 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_questionnaire
-- ----------------------------
DROP TABLE IF EXISTS `razor_questionnaire`;
CREATE TABLE `razor_questionnaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `counttime` datetime DEFAULT NULL COMMENT '统计时间',
  `userid` varchar(255) DEFAULT NULL COMMENT '玩家ID',
  `type` enum('per','total') DEFAULT 'total' COMMENT '区分原始和已处理数据(默认发送total即可)',
  `result` varchar(4096) DEFAULT NULL COMMENT '答案。格式:1-A||2-B||3-C||4-D（1-A表示第一题的答案是A）',
  `feedback` varchar(4096) DEFAULT NULL COMMENT '反馈意见',
  `sex` varchar(16) DEFAULT '1' COMMENT '性别（0-女，1-男）',
  `age` int(11) DEFAULT NULL COMMENT '年龄',
  `qqnum` varchar(255) DEFAULT NULL COMMENT 'QQ号',
  `inserttime` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1022 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_realtimeonlineusers
-- ----------------------------
DROP TABLE IF EXISTS `razor_realtimeonlineusers`;
CREATE TABLE `razor_realtimeonlineusers` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `count_date` date NOT NULL COMMENT '统计日期',
  `chId` varchar(255) NOT NULL COMMENT '渠道编号',
  `subSrvId` varchar(255) DEFAULT NULL COMMENT '合区区服编号',
  `srvId` varchar(255) DEFAULT NULL COMMENT '区服编号',
  `appId` varchar(255) NOT NULL COMMENT '应用编号',
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `count_time` datetime NOT NULL COMMENT '统计时间',
  `onlineusers` int(16) DEFAULT NULL COMMENT '实时在线用户数',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  `productkey` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `insertdate` (`insertdate`) USING BTREE,
  KEY `server` (`chId`,`srvId`,`appId`,`version`) USING BTREE,
  KEY `count_date` (`count_date`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=573072 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_realtimeroleinfo
-- ----------------------------
DROP TABLE IF EXISTS `razor_realtimeroleinfo`;
CREATE TABLE `razor_realtimeroleinfo` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `count_date` date NOT NULL COMMENT '统计日期',
  `appId` varchar(255) NOT NULL COMMENT '应用编号',
  `count_time` datetime NOT NULL COMMENT '统计时间',
  `onlineusers` int(11) DEFAULT NULL,
  `newusers` int(16) DEFAULT NULL COMMENT '新增用户',
  `dauusers` int(16) DEFAULT NULL COMMENT '活跃用户',
  `payamount` int(16) DEFAULT NULL COMMENT '付费金额',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `id` (`appId`,`count_time`) USING BTREE,
  KEY `insertdate` (`insertdate`) USING BTREE,
  KEY `server` (`appId`) USING BTREE,
  KEY `count_date` (`count_date`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=121352 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_register
-- ----------------------------
DROP TABLE IF EXISTS `razor_register`;
CREATE TABLE `razor_register` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `register_date` date NOT NULL COMMENT '注册日期',
  `chId` varchar(255) NOT NULL COMMENT '渠道编号',
  `appId` varchar(255) NOT NULL COMMENT '应用编号',
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `obligate1` varchar(255) DEFAULT NULL COMMENT '预留字段一',
  `obligate2` varchar(255) DEFAULT NULL COMMENT '预留字段二',
  `obligate3` varchar(255) DEFAULT NULL COMMENT '预留字段三',
  `obligate4` varchar(255) DEFAULT NULL COMMENT '预留字段四',
  `userId` varchar(255) NOT NULL COMMENT 'Razor用户编号',
  `productkey` varchar(255) NOT NULL COMMENT '应用key值',
  `deviceid` varchar(255) NOT NULL COMMENT '设备编号',
  `register_time` datetime NOT NULL COMMENT '注册时间',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`ID`),
  KEY `insertdate` (`insertdate`) USING BTREE,
  KEY `register_date` (`register_date`) USING BTREE,
  KEY `server` (`chId`,`appId`,`version`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=80322 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_reportlayout
-- ----------------------------
DROP TABLE IF EXISTS `razor_reportlayout`;
CREATE TABLE `razor_reportlayout` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `userid` int(50) NOT NULL,
  `productid` int(50) NOT NULL,
  `reportname` varchar(128) NOT NULL,
  `controller` varchar(128) NOT NULL,
  `method` varchar(45) DEFAULT NULL,
  `height` int(50) NOT NULL,
  `src` varchar(512) NOT NULL,
  `location` int(50) NOT NULL,
  `type` int(10) NOT NULL,
  `createtime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_server
-- ----------------------------
DROP TABLE IF EXISTS `razor_server`;
CREATE TABLE `razor_server` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `server_id` int(11) NOT NULL COMMENT '区服编号',
  `server_name` varchar(255) NOT NULL DEFAULT '' COMMENT '区服名称',
  `create_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '区服创建时间',
  `user_id` int(11) NOT NULL DEFAULT '1' COMMENT 'Razor用户编号',
  `type` enum('user','system') NOT NULL DEFAULT 'system',
  `product_id` int(10) NOT NULL,
  `active` int(10) NOT NULL DEFAULT '1' COMMENT '是否激活',
  `server_capacity` int(11) NOT NULL DEFAULT '1000',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_sliverconsume
-- ----------------------------
DROP TABLE IF EXISTS `razor_sliverconsume`;
CREATE TABLE `razor_sliverconsume` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `sliverconsume_date` date NOT NULL COMMENT '银币消耗日期',
  `chId` varchar(255) NOT NULL COMMENT '渠道编号。由Razor管理员提供',
  `subSrvId` varchar(255) DEFAULT NULL COMMENT '合区区服编号。由Razor管理员提供',
  `srvId` varchar(255) NOT NULL COMMENT '区服编号。由Razor管理员提供',
  `appId` varchar(255) NOT NULL COMMENT '产品ID。由Razor管理员提供',
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `actionid` varchar(255) NOT NULL COMMENT '行为id',
  `usecount` int(16) NOT NULL DEFAULT '0' COMMENT '使用次数',
  `sliverconsume_count` int(16) NOT NULL DEFAULT '0' COMMENT '消耗银币数量',
  `functionid` varchar(255) NOT NULL COMMENT '功能ID。例如：如：100002 表示：神位争霸。',
  `acionttypeid` varchar(255) NOT NULL COMMENT '行为类型ID。例如：如：003 表示：功能产出。',
  `userId` varchar(255) NOT NULL COMMENT '玩家用户ID',
  `productkey` varchar(255) NOT NULL COMMENT '应用key值。由Razor管理员提供',
  `deviceid` varchar(255) NOT NULL COMMENT '设备编号',
  `sliverconsume_time` datetime NOT NULL COMMENT '银币消耗时间',
  `roleId` varchar(255) NOT NULL COMMENT '玩家角色编号',
  `roleName` varchar(255) NOT NULL COMMENT '玩家角色名称',
  `roleLevel` int(16) NOT NULL DEFAULT '0' COMMENT '玩家角色等级',
  `roleVip` int(8) DEFAULT NULL COMMENT '玩家角色VIP等级',
  `goldCoin` int(32) DEFAULT NULL COMMENT '金币存量',
  `sliverCoin` int(32) DEFAULT NULL COMMENT '银币存量',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`ID`),
  KEY `insertdate` (`insertdate`) USING BTREE,
  KEY `roleid` (`roleId`) USING BTREE,
  KEY `server` (`chId`,`srvId`,`appId`,`version`) USING BTREE,
  KEY `sliverconsume_date` (`sliverconsume_date`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_slivergain
-- ----------------------------
DROP TABLE IF EXISTS `razor_slivergain`;
CREATE TABLE `razor_slivergain` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `slivergain_date` date NOT NULL COMMENT '银币获得日期',
  `chId` varchar(255) NOT NULL COMMENT '渠道编号。由Razor管理员提供',
  `subSrvId` varchar(255) DEFAULT NULL COMMENT '合区区服编号。由Razor管理员提供',
  `srvId` varchar(255) NOT NULL COMMENT '区服编号。由Razor管理员提供',
  `appId` varchar(255) NOT NULL COMMENT '产品ID。由Razor管理员提供',
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `actionid` varchar(255) NOT NULL COMMENT '行为id',
  `slivergain_count` int(16) NOT NULL DEFAULT '0' COMMENT '银币获得数量',
  `functionid` varchar(255) NOT NULL COMMENT '功能ID。例如：如：100002 表示：神位争霸。',
  `acionttypeid` varchar(255) NOT NULL COMMENT '行为类型ID。例如：如：003 表示：功能产出。',
  `userId` varchar(255) NOT NULL COMMENT '玩家用户ID',
  `productkey` varchar(255) NOT NULL COMMENT '应用key值。由Razor管理员提供',
  `deviceid` varchar(255) NOT NULL COMMENT '设备编号',
  `slivergain_time` datetime NOT NULL COMMENT '银币获得时间',
  `roleId` varchar(255) NOT NULL COMMENT '玩家角色编号',
  `roleName` varchar(255) NOT NULL COMMENT '玩家角色名称',
  `roleLevel` int(16) NOT NULL DEFAULT '0' COMMENT '玩家角色等级',
  `roleVip` int(8) DEFAULT NULL COMMENT '玩家角色VIP等级',
  `goldCoin` int(32) DEFAULT NULL COMMENT '金币存量',
  `sliverCoin` int(32) DEFAULT NULL COMMENT '银币存量',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`ID`),
  KEY `insertdate` (`insertdate`) USING BTREE,
  KEY `roleid` (`roleId`) USING BTREE,
  KEY `server` (`chId`,`srvId`,`appId`,`version`) USING BTREE,
  KEY `slivergain_date` (`slivergain_date`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_tag_group
-- ----------------------------
DROP TABLE IF EXISTS `razor_tag_group`;
CREATE TABLE `razor_tag_group` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `product_id` int(4) NOT NULL,
  `name` varchar(200) NOT NULL,
  `tags` varchar(5000) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_target
-- ----------------------------
DROP TABLE IF EXISTS `razor_target`;
CREATE TABLE `razor_target` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `targetname` varchar(128) NOT NULL,
  `targettype` int(11) DEFAULT NULL,
  `unitprice` decimal(12,2) NOT NULL,
  `targetstatusc` int(11) NOT NULL,
  `createdate` datetime NOT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_targetevent
-- ----------------------------
DROP TABLE IF EXISTS `razor_targetevent`;
CREATE TABLE `razor_targetevent` (
  `teid` int(11) NOT NULL AUTO_INCREMENT,
  `targetid` int(11) NOT NULL,
  `eventid` int(11) NOT NULL,
  `eventalias` varchar(128) NOT NULL,
  `sequence` int(11) NOT NULL,
  PRIMARY KEY (`teid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_task
-- ----------------------------
DROP TABLE IF EXISTS `razor_task`;
CREATE TABLE `razor_task` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `task_date` date NOT NULL COMMENT '任务日期',
  `chId` varchar(255) NOT NULL COMMENT '渠道编号。由Razor管理员提供',
  `subSrvId` varchar(255) DEFAULT NULL COMMENT '合区区服编号。由Razor管理员提供',
  `srvId` varchar(255) NOT NULL COMMENT '区服编号。由Razor管理员提供',
  `appId` varchar(255) NOT NULL COMMENT '产品ID。由Razor管理员提供',
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `taskid` varchar(255) NOT NULL COMMENT '任务id',
  `stepid` varchar(255) NOT NULL COMMENT '步骤id。1开始',
  `markid` varchar(255) NOT NULL COMMENT '标识id。最后一步发1，非最后一步发0',
  `userId` varchar(255) NOT NULL COMMENT '玩家用户ID',
  `productkey` varchar(255) NOT NULL COMMENT '应用key值。由Razor管理员提供',
  `deviceid` varchar(255) NOT NULL COMMENT '设备编号',
  `task_time` datetime NOT NULL COMMENT '任务时间',
  `roleId` varchar(255) NOT NULL COMMENT '玩家角色编号',
  `roleName` varchar(255) NOT NULL COMMENT '玩家角色名称',
  `roleLevel` int(11) NOT NULL,
  `roleVip` int(11) NOT NULL,
  `goldCoin` int(11) NOT NULL,
  `sliverCoin` int(11) NOT NULL,
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`ID`),
  KEY `insertdate` (`insertdate`) USING BTREE,
  KEY `roleid` (`roleId`) USING BTREE,
  KEY `server` (`chId`,`srvId`,`appId`,`version`) USING BTREE,
  KEY `task_date` (`task_date`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1717282 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_tollgate
-- ----------------------------
DROP TABLE IF EXISTS `razor_tollgate`;
CREATE TABLE `razor_tollgate` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `tollgate_date` date NOT NULL COMMENT '关卡日期',
  `chId` varchar(255) NOT NULL COMMENT '渠道编号。由Razor管理员提供',
  `subSrvId` varchar(255) DEFAULT NULL COMMENT '合区区服编号。由Razor管理员提供',
  `srvId` varchar(255) NOT NULL COMMENT '区服编号。由Razor管理员提供',
  `appId` varchar(255) NOT NULL COMMENT '产品ID。由Razor管理员提供',
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `moduleid` varchar(255) NOT NULL COMMENT '关卡大类id',
  `tollgateid` varchar(255) NOT NULL COMMENT '关卡id',
  `tollgategrade` int(16) NOT NULL DEFAULT '0' COMMENT '关卡评级',
  `tollgatesweep` int(16) NOT NULL COMMENT '是否为扫荡通关。1-扫荡，0-非扫荡',
  `pass` int(16) NOT NULL DEFAULT '1' COMMENT '是否通关。1-通关，2-未通关',
  `passtime` int(16) NOT NULL DEFAULT '0' COMMENT '通关耗时，单位：秒',
  `combattimeout` int(16) NOT NULL DEFAULT '0' COMMENT '是否战斗超时, 0-未超时，1-超时',
  `userId` varchar(255) NOT NULL COMMENT '玩家用户ID',
  `productkey` varchar(255) NOT NULL COMMENT '应用key值。由Razor管理员提供',
  `deviceid` varchar(255) NOT NULL COMMENT '设备编号',
  `tollgate_time` datetime NOT NULL COMMENT '关卡时间',
  `roleId` varchar(255) NOT NULL COMMENT '玩家角色编号',
  `roleName` varchar(255) NOT NULL COMMENT '玩家角色名称',
  `roleLevel` int(11) NOT NULL,
  `roleVip` int(11) NOT NULL,
  `goldCoin` int(11) NOT NULL,
  `sliverCoin` int(11) NOT NULL,
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`ID`),
  KEY `insertdate` (`insertdate`) USING BTREE,
  KEY `roleid` (`roleId`) USING BTREE,
  KEY `server` (`chId`,`srvId`,`appId`,`version`) USING BTREE,
  KEY `tollgate_date` (`tollgate_date`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=262652 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_user_autologin
-- ----------------------------
DROP TABLE IF EXISTS `razor_user_autologin`;
CREATE TABLE `razor_user_autologin` (
  `key_id` char(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_user_permissions
-- ----------------------------
DROP TABLE IF EXISTS `razor_user_permissions`;
CREATE TABLE `razor_user_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` int(11) DEFAULT NULL,
  `resource` int(11) DEFAULT NULL,
  `read` tinyint(1) DEFAULT '0',
  `write` tinyint(1) DEFAULT '0',
  `modify` tinyint(1) DEFAULT '0',
  `delete` tinyint(1) DEFAULT '0',
  `publish` tinyint(1) DEFAULT '0',
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_user_profiles
-- ----------------------------
DROP TABLE IF EXISTS `razor_user_profiles`;
CREATE TABLE `razor_user_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `country` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `website` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `companyname` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `contact` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `telephone` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `QQ` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `MSN` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `Gtalk` varchar(30) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_user_resources
-- ----------------------------
DROP TABLE IF EXISTS `razor_user_resources`;
CREATE TABLE `razor_user_resources` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `parentId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_user_roles
-- ----------------------------
DROP TABLE IF EXISTS `razor_user_roles`;
CREATE TABLE `razor_user_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `parentId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_user2product
-- ----------------------------
DROP TABLE IF EXISTS `razor_user2product`;
CREATE TABLE `razor_user2product` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `user_id` int(11) NOT NULL COMMENT 'Razor用户编号',
  `product_id` int(11) NOT NULL COMMENT '应用编号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_user2role
-- ----------------------------
DROP TABLE IF EXISTS `razor_user2role`;
CREATE TABLE `razor_user2role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `roleid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_userkeys
-- ----------------------------
DROP TABLE IF EXISTS `razor_userkeys`;
CREATE TABLE `razor_userkeys` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(20) NOT NULL,
  `user_key` varchar(50) NOT NULL,
  `user_secret` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_users
-- ----------------------------
DROP TABLE IF EXISTS `razor_users`;
CREATE TABLE `razor_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `new_password_key` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `new_password_requested` datetime DEFAULT NULL,
  `new_email` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `new_email_key` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `last_ip` varchar(40) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `sessionkey` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_viplevelup
-- ----------------------------
DROP TABLE IF EXISTS `razor_viplevelup`;
CREATE TABLE `razor_viplevelup` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `viplevelup_date` date NOT NULL COMMENT '玩家VIP等级升级日期',
  `chId` varchar(255) NOT NULL COMMENT '渠道编号',
  `subSrvId` varchar(255) DEFAULT NULL COMMENT '合区区服编号',
  `srvId` varchar(255) NOT NULL COMMENT '区服编号',
  `appId` varchar(255) NOT NULL COMMENT '应用编号',
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `userId` varchar(255) NOT NULL COMMENT 'Razor用户编号',
  `productkey` varchar(255) NOT NULL COMMENT '应用key值',
  `deviceid` varchar(255) NOT NULL COMMENT '设备编号',
  `viplevelup_time` datetime NOT NULL COMMENT '玩家VIP等级升级时间',
  `roleId` varchar(255) NOT NULL COMMENT '玩家角色编号',
  `roleName` varchar(255) NOT NULL COMMENT '玩家角色名称',
  `roleLevel` int(8) NOT NULL COMMENT '玩家当前角色等级',
  `currentRoleVip` int(8) NOT NULL COMMENT '当前玩家角色VIP等级',
  `beforeRoleVip` int(8) NOT NULL COMMENT '升级前玩家角色VIP等级',
  `goldCoin` int(32) NOT NULL COMMENT '金币存量',
  `sliverCoin` int(32) NOT NULL COMMENT '银币存量',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`ID`),
  KEY `insertdate` (`insertdate`) USING BTREE,
  KEY `viplevelup_date` (`viplevelup_date`) USING BTREE,
  KEY `server` (`chId`,`srvId`,`appId`,`version`) USING BTREE,
  KEY `rolelevel` (`roleLevel`) USING BTREE,
  KEY `viplevelup_time` (`viplevelup_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_wifi_towers
-- ----------------------------
DROP TABLE IF EXISTS `razor_wifi_towers`;
CREATE TABLE `razor_wifi_towers` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `clientdataid` int(50) NOT NULL,
  `mac_address` varchar(50) NOT NULL,
  `signal_strength` varchar(50) NOT NULL,
  `age` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- View structure for VIEW_razor_activity_all
-- ----------------------------
DROP VIEW IF EXISTS `VIEW_razor_activity_all`;
CREATE ALGORITHM=UNDEFINED DEFINER=`razor`@`116.228.9.26` SQL SECURITY DEFINER VIEW `VIEW_razor_activity_all` AS select `razor_mainconfig_newserveractivity`.`id` AS `id`,`razor_mainconfig_newserveractivity`.`app_id` AS `app_id`,`razor_mainconfig_newserveractivity`.`newserveractivity_id` AS `newserveractivity_id`,`razor_mainconfig_newserveractivity`.`newserveractivity_name` AS `newserveractivity_name`,`razor_mainconfig_newserveractivity`.`newserveractivity_issue` AS `newserveractivity_issue`,`razor_mainconfig_newserveractivity`.`startdate` AS `startdate`,`razor_mainconfig_newserveractivity`.`enddate` AS `enddate` from `razor_mainconfig_newserveractivity` union all select `razor_mainconfig_operationactivity`.`id` AS `id`,`razor_mainconfig_operationactivity`.`app_id` AS `app_id`,`razor_mainconfig_operationactivity`.`operationactivity_id` AS `operationactivity_id`,`razor_mainconfig_operationactivity`.`operationactivity_name` AS `operationactivity_name`,`razor_mainconfig_operationactivity`.`operationactivity_issue` AS `operationactivity_issue`,`razor_mainconfig_operationactivity`.`startdate` AS `startdate`,`razor_mainconfig_operationactivity`.`enddate` AS `enddate` from `razor_mainconfig_operationactivity` ;

-- ----------------------------
-- View structure for VIEW_razor_createrole_logintimes
-- ----------------------------
DROP VIEW IF EXISTS `VIEW_razor_createrole_logintimes`;
CREATE ALGORITHM=UNDEFINED DEFINER=`razor`@`127.0.0.1` SQL SECURITY DEFINER VIEW `VIEW_razor_createrole_logintimes` AS select `l`.`login_date` AS `login_date`,`l`.`appId` AS `appId`,`l`.`chId` AS `chId`,`l`.`srvId` AS `srvId`,`l`.`version` AS `version`,`l`.`roleId` AS `roleId`,count(1) AS `logintimes` from `razor_login` `l` where `l`.`roleId` in (select `c`.`roleId` from `razor_createrole` `c`) group by `l`.`login_date`,`l`.`appId`,`l`.`chId`,`l`.`srvId`,`l`.`version`,`l`.`roleId` ;

-- ----------------------------
-- View structure for VIEW_razor_deviceboot_deviceboottimes
-- ----------------------------
DROP VIEW IF EXISTS `VIEW_razor_deviceboot_deviceboottimes`;
CREATE ALGORITHM=UNDEFINED DEFINER=`razor`@`116.228.9.26` SQL SECURITY DEFINER VIEW `VIEW_razor_deviceboot_deviceboottimes` AS select `l`.`deviceboot_date` AS `deviceboot_date`,`l`.`appId` AS `appId`,`l`.`chId` AS `chId`,`l`.`version` AS `version`,`l`.`deviceid` AS `deviceid`,count(1) AS `deviceboottimes` from `razor_deviceboot` `l` group by `l`.`deviceboot_date`,`l`.`appId`,`l`.`chId`,`l`.`version`,`l`.`deviceid` ;

-- ----------------------------
-- View structure for VIEW_razor_levelupgrade_createrole
-- ----------------------------
DROP VIEW IF EXISTS `VIEW_razor_levelupgrade_createrole`;
CREATE ALGORITHM=UNDEFINED DEFINER=`razor`@`116.228.9.26` SQL SECURITY DEFINER VIEW `VIEW_razor_levelupgrade_createrole` AS select `lu`.`levelupgrade_date` AS `levelupgrade_date`,`lu`.`chId` AS `chId`,`lu`.`subSrvId` AS `subSrvId`,`lu`.`srvId` AS `srvId`,`lu`.`appId` AS `appId`,`lu`.`version` AS `version`,'upgrade' AS `type`,`lu`.`userId` AS `userId`,`lu`.`productkey` AS `productkey`,`lu`.`deviceid` AS `deviceid`,`lu`.`levelupgrade_time` AS `levelupgrade_time`,`lu`.`roleId` AS `roleId`,`lu`.`roleLevel` AS `roleLevel`,`lu`.`roleVip` AS `roleVip`,`lu`.`goldCoin` AS `goldCoin`,`lu`.`sliverCoin` AS `sliverCoin` from `razor_levelupgrade` `lu` union all select `lg`.`login_date` AS `login_date`,`lg`.`chId` AS `chId`,`lg`.`subSrvId` AS `subSrvId`,`lg`.`srvId` AS `srvId`,`lg`.`appId` AS `appId`,`lg`.`version` AS `version`,`lg`.`type` AS `type`,`lg`.`userId` AS `userId`,`lg`.`productkey` AS `productkey`,`lg`.`deviceid` AS `deviceid`,`lg`.`login_time` AS `login_time`,`lg`.`roleId` AS `roleId`,`lg`.`roleLevel` AS `roleLevel`,`lg`.`roleVip` AS `roleVip`,`lg`.`goldCoin` AS `goldCoin`,`lg`.`sliverCoin` AS `sliverCoin` from `razor_login` `lg` ;

-- ----------------------------
-- View structure for VIEW_razor_login_datecount
-- ----------------------------
DROP VIEW IF EXISTS `VIEW_razor_login_datecount`;
CREATE ALGORITHM=UNDEFINED DEFINER=`razor`@`116.228.9.26` SQL SECURITY DEFINER VIEW `VIEW_razor_login_datecount` AS select `l`.`appId` AS `appId`,`l`.`chId` AS `chId`,`l`.`srvId` AS `srvId`,`l`.`version` AS `version`,`l`.`roleId` AS `roleId`,count(distinct `l`.`login_date`) AS `datecount` from `razor_login` `l` where (`l`.`type` = 'login') group by `l`.`appId`,`l`.`chId`,`l`.`srvId`,`l`.`version`,`l`.`roleId` ;

-- ----------------------------
-- View structure for VIEW_razor_login_logintimes
-- ----------------------------
DROP VIEW IF EXISTS `VIEW_razor_login_logintimes`;
CREATE ALGORITHM=UNDEFINED DEFINER=`razor`@`116.228.9.26` SQL SECURITY DEFINER VIEW `VIEW_razor_login_logintimes` AS select `l`.`login_date` AS `login_date`,`l`.`appId` AS `appId`,`l`.`chId` AS `chId`,`l`.`version` AS `version`,`l`.`roleId` AS `roleId`,count(1) AS `logintimes` from `razor_login` `l` where (`l`.`type` = 'login') group by `l`.`login_date`,`l`.`appId`,`l`.`chId`,`l`.`version`,`l`.`roleId` ;

-- ----------------------------
-- View structure for VIEW_razor_login_logintimes_srvid
-- ----------------------------
DROP VIEW IF EXISTS `VIEW_razor_login_logintimes_srvid`;
CREATE ALGORITHM=UNDEFINED DEFINER=`razor`@`116.228.9.26` SQL SECURITY DEFINER VIEW `VIEW_razor_login_logintimes_srvid` AS select `l`.`login_date` AS `login_date`,`l`.`appId` AS `appId`,`l`.`chId` AS `chId`,`l`.`srvId` AS `srvId`,`l`.`version` AS `version`,`l`.`roleId` AS `roleId`,count(1) AS `logintimes` from `razor_login` `l` where (`l`.`type` = 'login') group by `l`.`login_date`,`l`.`appId`,`l`.`chId`,`l`.`srvId`,`l`.`version`,`l`.`roleId` ;

-- ----------------------------
-- View structure for VIEW_razor_mainconfig_task
-- ----------------------------
DROP VIEW IF EXISTS `VIEW_razor_mainconfig_task`;
CREATE ALGORITHM=UNDEFINED DEFINER=`razor`@`116.228.9.26` SQL SECURITY DEFINER VIEW `VIEW_razor_mainconfig_task` AS select `nt`.`id` AS `id`,`nt`.`app_id` AS `app_id`,`nt`.`task_id` AS `task_id`,`nt`.`task_name` AS `task_name`,`nt`.`step_id` AS `step_id`,`nt`.`step_name` AS `step_name`,'newuser' AS `tasktype` from `razor_mainconfig_newusertask` `nt` union all select `mt`.`id` AS `id`,`mt`.`app_id` AS `app_id`,`mt`.`task_id` AS `task_id`,`mt`.`task_name` AS `task_name`,`mt`.`step_id` AS `step_id`,`mt`.`step_name` AS `step_name`,'main' AS `tasktype` from `razor_mainconfig_mainlinetask` `mt` union all select `bt`.`id` AS `id`,`bt`.`app_id` AS `app_id`,`bt`.`task_id` AS `task_id`,`bt`.`task_name` AS `task_name`,`bt`.`step_id` AS `step_id`,`bt`.`step_name` AS `step_name`,'branch' AS `tasktype` from `razor_mainconfig_branchlinetask` `bt` union all select `gt`.`id` AS `id`,`gt`.`app_id` AS `app_id`,`gt`.`task_id` AS `task_id`,`gt`.`task_name` AS `task_name`,`gt`.`step_id` AS `step_id`,`gt`.`step_name` AS `step_name`,'general' AS `tasktype` from `razor_mainconfig_generaltask` `gt` ;

-- ----------------------------
-- View structure for VIEW_razor_pay_amount_count
-- ----------------------------
DROP VIEW IF EXISTS `VIEW_razor_pay_amount_count`;
CREATE ALGORITHM=UNDEFINED DEFINER=`razor`@`127.0.0.1` SQL SECURITY DEFINER VIEW `VIEW_razor_pay_amount_count` AS select `p`.`pay_date` AS `pay_date`,`p`.`appId` AS `appId`,`p`.`chId` AS `chId`,`p`.`srvId` AS `srvId`,`p`.`version` AS `version`,`p`.`roleId` AS `roleId`,sum(`p`.`pay_amount`) AS `pay_amount`,count(1) AS `pay_count` from `razor_pay` `p` group by `p`.`pay_date`,`p`.`appId`,`p`.`chId`,`p`.`srvId`,`p`.`version`,`p`.`roleId` ;

-- ----------------------------
-- View structure for VIEW_razor_pay_count
-- ----------------------------
DROP VIEW IF EXISTS `VIEW_razor_pay_count`;
CREATE ALGORITHM=UNDEFINED DEFINER=`razor`@`127.0.0.1` SQL SECURITY DEFINER VIEW `VIEW_razor_pay_count` AS select `p`.`roleId` AS `roleId`,count(1) AS `pay_count` from `razor_pay` `p` group by `p`.`roleId` order by `pay_count` desc ;

-- ----------------------------
-- View structure for VIEW_razor_pay_logintimes
-- ----------------------------
DROP VIEW IF EXISTS `VIEW_razor_pay_logintimes`;
CREATE ALGORITHM=UNDEFINED DEFINER=`razor`@`127.0.0.1` SQL SECURITY DEFINER VIEW `VIEW_razor_pay_logintimes` AS select `l`.`login_date` AS `login_date`,`l`.`appId` AS `appId`,`l`.`chId` AS `chId`,`l`.`srvId` AS `srvId`,`l`.`version` AS `version`,`l`.`roleId` AS `roleId`,count(1) AS `logintimes` from `razor_login` `l` where `l`.`roleId` in (select `p`.`roleId` from `razor_pay` `p`) group by `l`.`login_date`,`l`.`appId`,`l`.`chId`,`l`.`srvId`,`l`.`version`,`l`.`roleId` ;

-- ----------------------------
-- View structure for VIEW_razor_pay_rolelevel
-- ----------------------------
DROP VIEW IF EXISTS `VIEW_razor_pay_rolelevel`;
CREATE ALGORITHM=UNDEFINED DEFINER=`razor`@`127.0.0.1` SQL SECURITY DEFINER VIEW `VIEW_razor_pay_rolelevel` AS select `p`.`pay_date` AS `pay_date`,`p`.`appId` AS `appId`,`p`.`chId` AS `chId`,`p`.`srvId` AS `srvId`,`p`.`version` AS `version`,`p`.`roleLevel` AS `roleLevel`,sum(`p`.`pay_amount`) AS `pay_amount`,count(1) AS `pay_count` from `razor_pay` `p` group by `p`.`pay_date`,`p`.`appId`,`p`.`chId`,`p`.`srvId`,`p`.`version`,`p`.`roleLevel` ;

-- ----------------------------
-- View structure for VIEW_razor_prop_all
-- ----------------------------
DROP VIEW IF EXISTS `VIEW_razor_prop_all`;
CREATE ALGORITHM=UNDEFINED DEFINER=`razor`@`116.228.9.26` SQL SECURITY DEFINER VIEW `VIEW_razor_prop_all` AS select `c`.`ID` AS `ID`,`c`.`propconsume_date` AS `propconsume_date`,`c`.`chId` AS `chId`,`c`.`subSrvId` AS `subSrvId`,`c`.`srvId` AS `srvId`,`c`.`appId` AS `appId`,`c`.`version` AS `version`,`c`.`actionid` AS `actionid`,`c`.`propid` AS `propid`,`c`.`proplevel` AS `proplevel`,`c`.`propconsume_count` AS `propconsume_count`,`c`.`prop_stock` AS `prop_stock`,`c`.`functionid` AS `functionid`,`c`.`acionttypeid` AS `acionttypeid`,`c`.`userId` AS `userId`,`c`.`productkey` AS `productkey`,`c`.`deviceid` AS `deviceid`,`c`.`propconsume_time` AS `propconsume_time`,`c`.`roleId` AS `roleId`,`c`.`roleName` AS `roleName`,`c`.`roleLevel` AS `roleLevel`,`c`.`roleVip` AS `roleVip`,`c`.`goldCoin` AS `goldCoin`,`c`.`sliverCoin` AS `sliverCoin`,`c`.`insertdate` AS `insertdate`,'consume' AS `tag` from `razor_propconsume` `c` union all select `g`.`ID` AS `ID`,`g`.`propgain_date` AS `propgain_date`,`g`.`chId` AS `chId`,`g`.`subSrvId` AS `subSrvId`,`g`.`srvId` AS `srvId`,`g`.`appId` AS `appId`,`g`.`version` AS `version`,`g`.`actionid` AS `actionid`,`g`.`propid` AS `propid`,`g`.`proplevel` AS `proplevel`,`g`.`propgain_count` AS `propgain_count`,`g`.`prop_stock` AS `prop_stock`,`g`.`functionid` AS `functionid`,`g`.`acionttypeid` AS `acionttypeid`,`g`.`userId` AS `userId`,`g`.`productkey` AS `productkey`,`g`.`deviceid` AS `deviceid`,`g`.`propgain_time` AS `propgain_time`,`g`.`roleId` AS `roleId`,`g`.`roleName` AS `roleName`,`g`.`roleLevel` AS `roleLevel`,`g`.`roleVip` AS `roleVip`,`g`.`goldCoin` AS `goldCoin`,`g`.`sliverCoin` AS `sliverCoin`,`g`.`insertdate` AS `insertdate`,'gain' AS `tag` from `razor_propgain` `g` ;

-- ----------------------------
-- View structure for VIEW_razor_task_newuserguide
-- ----------------------------
DROP VIEW IF EXISTS `VIEW_razor_task_newuserguide`;
CREATE ALGORITHM=UNDEFINED DEFINER=`razor`@`116.228.9.26` SQL SECURITY DEFINER VIEW `VIEW_razor_task_newuserguide` AS select `t`.`task_date` AS `task_date`,`t`.`chId` AS `chId`,`t`.`subSrvId` AS `subSrvId`,`t`.`srvId` AS `srvId`,`t`.`appId` AS `appId`,`t`.`version` AS `version`,`t`.`taskid` AS `taskid`,`t`.`stepid` AS `stepid`,`t`.`markid` AS `markid`,`t`.`userId` AS `userId`,`t`.`productkey` AS `productkey`,`t`.`deviceid` AS `deviceid`,`t`.`task_time` AS `task_time`,`t`.`roleId` AS `roleId`,`t`.`roleName` AS `roleName`,`t`.`roleLevel` AS `roleLevel`,`t`.`roleVip` AS `roleVip`,`t`.`goldCoin` AS `goldCoin`,`t`.`sliverCoin` AS `sliverCoin`,`t`.`insertdate` AS `insertdate` from `razor_task` `t` union all select `n`.`newuserguide_date` AS `newuserguide_date`,`n`.`chId` AS `chId`,`n`.`subSrvId` AS `subSrvId`,`n`.`srvId` AS `srvId`,`n`.`appId` AS `appId`,`n`.`version` AS `version`,`n`.`newuserguide_id` AS `newuserguide_id`,`n`.`stepid` AS `stepid`,`n`.`markid` AS `markid`,`n`.`userId` AS `userId`,`n`.`productkey` AS `productkey`,`n`.`deviceid` AS `deviceid`,`n`.`newuserguide_time` AS `newuserguide_time`,`n`.`roleId` AS `roleId`,`n`.`roleName` AS `roleName`,`n`.`roleLevel` AS `roleLevel`,`n`.`roleVip` AS `roleVip`,`n`.`goldCoin` AS `goldCoin`,`n`.`sliverCoin` AS `sliverCoin`,`n`.`insertdate` AS `insertdate` from `razor_newuserguide` `n` ;
