/*
Navicat MySQL Data Transfer

Source Server         : 192.168.80.41-点餐系统等
Source Server Version : 50629
Source Host           : 192.168.80.41:3306
Source Database       : razor_dw

Target Server Type    : MYSQL
Target Server Version : 50629
File Encoding         : 65001

Date: 2016-05-25 17:23:39
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for razor_deviceid_pushid
-- ----------------------------
DROP TABLE IF EXISTS `razor_deviceid_pushid`;
CREATE TABLE `razor_deviceid_pushid` (
  `did` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `deviceid` varchar(128) NOT NULL,
  `pushid` varchar(128) NOT NULL,
  PRIMARY KEY (`did`),
  UNIQUE KEY `deviceid` (`deviceid`,`pushid`),
  KEY `pushid` (`pushid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_deviceid_userid
-- ----------------------------
DROP TABLE IF EXISTS `razor_deviceid_userid`;
CREATE TABLE `razor_deviceid_userid` (
  `did` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `deviceid` varchar(128) NOT NULL,
  `userid` varchar(128) NOT NULL,
  PRIMARY KEY (`did`),
  UNIQUE KEY `deviceid` (`deviceid`,`userid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_dim_activity
-- ----------------------------
DROP TABLE IF EXISTS `razor_dim_activity`;
CREATE TABLE `razor_dim_activity` (
  `activity_sk` int(11) NOT NULL AUTO_INCREMENT,
  `activity_name` varchar(512) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`activity_sk`),
  KEY `activity_name` (`activity_name`(255),`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_dim_date
-- ----------------------------
DROP TABLE IF EXISTS `razor_dim_date`;
CREATE TABLE `razor_dim_date` (
  `date_sk` int(11) NOT NULL AUTO_INCREMENT COMMENT '日期编号',
  `datevalue` date NOT NULL COMMENT '日期',
  `year` int(11) NOT NULL COMMENT '年',
  `quarter` int(11) NOT NULL COMMENT '季度',
  `month` int(11) NOT NULL COMMENT '月',
  `week` int(11) NOT NULL COMMENT '周',
  `dayofweek` int(11) NOT NULL COMMENT '每周的第几天',
  `day` int(11) NOT NULL COMMENT '日期中的天+1',
  PRIMARY KEY (`date_sk`),
  KEY `year` (`year`,`month`,`day`),
  KEY `year_2` (`year`,`week`),
  KEY `datevalue` (`datevalue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_dim_devicebrand
-- ----------------------------
DROP TABLE IF EXISTS `razor_dim_devicebrand`;
CREATE TABLE `razor_dim_devicebrand` (
  `devicebrand_sk` int(11) NOT NULL AUTO_INCREMENT,
  `devicebrand_name` varchar(60) NOT NULL,
  PRIMARY KEY (`devicebrand_sk`),
  KEY `devicebrand_name` (`devicebrand_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_dim_devicelanguage
-- ----------------------------
DROP TABLE IF EXISTS `razor_dim_devicelanguage`;
CREATE TABLE `razor_dim_devicelanguage` (
  `devicelanguage_sk` int(11) NOT NULL AUTO_INCREMENT,
  `devicelanguage_name` varchar(60) NOT NULL,
  PRIMARY KEY (`devicelanguage_sk`),
  KEY `devicelanguage_name` (`devicelanguage_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_dim_deviceos
-- ----------------------------
DROP TABLE IF EXISTS `razor_dim_deviceos`;
CREATE TABLE `razor_dim_deviceos` (
  `deviceos_sk` int(11) NOT NULL AUTO_INCREMENT,
  `deviceos_name` varchar(256) NOT NULL,
  PRIMARY KEY (`deviceos_sk`),
  KEY `deviceos_name` (`deviceos_name`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_dim_deviceresolution
-- ----------------------------
DROP TABLE IF EXISTS `razor_dim_deviceresolution`;
CREATE TABLE `razor_dim_deviceresolution` (
  `deviceresolution_sk` int(11) NOT NULL AUTO_INCREMENT,
  `deviceresolution_name` varchar(60) NOT NULL,
  PRIMARY KEY (`deviceresolution_sk`),
  KEY `deviceresolution_name` (`deviceresolution_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_dim_devicesupplier
-- ----------------------------
DROP TABLE IF EXISTS `razor_dim_devicesupplier`;
CREATE TABLE `razor_dim_devicesupplier` (
  `devicesupplier_sk` int(11) NOT NULL AUTO_INCREMENT,
  `mccmnc` varchar(16) NOT NULL,
  `devicesupplier_name` varchar(128) NOT NULL DEFAULT 'unknown',
  `countrycode` varchar(8) DEFAULT NULL,
  `countryname` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`devicesupplier_sk`),
  KEY `devicesupplier_name` (`devicesupplier_name`),
  KEY `mccmnc` (`mccmnc`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_dim_errortitle
-- ----------------------------
DROP TABLE IF EXISTS `razor_dim_errortitle`;
CREATE TABLE `razor_dim_errortitle` (
  `title_sk` int(11) NOT NULL AUTO_INCREMENT,
  `title_name` varchar(512) NOT NULL,
  `isfix` int(11) NOT NULL,
  PRIMARY KEY (`title_sk`),
  KEY `title_name` (`title_name`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_dim_event
-- ----------------------------
DROP TABLE IF EXISTS `razor_dim_event`;
CREATE TABLE `razor_dim_event` (
  `event_sk` int(11) NOT NULL AUTO_INCREMENT,
  `eventidentifier` varchar(50) NOT NULL,
  `eventname` varchar(50) NOT NULL,
  `active` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `event_id` int(11) NOT NULL,
  PRIMARY KEY (`event_sk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_dim_location
-- ----------------------------
DROP TABLE IF EXISTS `razor_dim_location`;
CREATE TABLE `razor_dim_location` (
  `location_sk` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(60) NOT NULL,
  `region` varchar(60) NOT NULL,
  `city` varchar(60) NOT NULL,
  PRIMARY KEY (`location_sk`),
  KEY `country` (`country`,`region`,`city`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_dim_network
-- ----------------------------
DROP TABLE IF EXISTS `razor_dim_network`;
CREATE TABLE `razor_dim_network` (
  `network_sk` int(11) NOT NULL AUTO_INCREMENT,
  `networkname` varchar(256) NOT NULL,
  PRIMARY KEY (`network_sk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_dim_product
-- ----------------------------
DROP TABLE IF EXISTS `razor_dim_product`;
CREATE TABLE `razor_dim_product` (
  `product_sk` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `product_name` varchar(256) NOT NULL COMMENT '产品名称',
  `product_type` varchar(128) NOT NULL COMMENT '产品类型',
  `product_active` tinyint(4) NOT NULL COMMENT '产品是否激活',
  `channel_id` int(11) NOT NULL COMMENT '渠道ID',
  `channel_name` varchar(256) NOT NULL COMMENT '渠道名称',
  `channel_active` tinyint(4) NOT NULL COMMENT '渠道是否激活',
  `product_key` varchar(256) DEFAULT NULL COMMENT '产品key值',
  `version_name` varchar(64) NOT NULL COMMENT '版本名称',
  `version_active` tinyint(4) NOT NULL COMMENT '版本是否激活',
  `userid` int(11) NOT NULL COMMENT 'Razor用户ID',
  `platform` varchar(128) NOT NULL COMMENT '平台ID',
  PRIMARY KEY (`product_sk`),
  UNIQUE KEY `product_id` (`product_id`,`channel_id`,`version_name`,`userid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_dim_segment_launch
-- ----------------------------
DROP TABLE IF EXISTS `razor_dim_segment_launch`;
CREATE TABLE `razor_dim_segment_launch` (
  `segment_sk` int(11) NOT NULL AUTO_INCREMENT,
  `segment_name` varchar(128) NOT NULL,
  `startvalue` int(11) NOT NULL,
  `endvalue` int(11) NOT NULL,
  `effective_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  PRIMARY KEY (`segment_sk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_dim_segment_usinglog
-- ----------------------------
DROP TABLE IF EXISTS `razor_dim_segment_usinglog`;
CREATE TABLE `razor_dim_segment_usinglog` (
  `segment_sk` int(11) NOT NULL AUTO_INCREMENT,
  `segment_name` varchar(128) NOT NULL,
  `startvalue` int(11) NOT NULL,
  `endvalue` int(11) NOT NULL,
  `effective_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  PRIMARY KEY (`segment_sk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_fact_clientdata
-- ----------------------------
DROP TABLE IF EXISTS `razor_fact_clientdata`;
CREATE TABLE `razor_fact_clientdata` (
  `dataid` int(11) NOT NULL AUTO_INCREMENT,
  `product_sk` int(11) NOT NULL,
  `deviceos_sk` int(11) NOT NULL,
  `deviceresolution_sk` int(11) NOT NULL,
  `devicelanguage_sk` int(11) NOT NULL,
  `devicebrand_sk` int(11) NOT NULL,
  `devicesupplier_sk` int(11) NOT NULL,
  `location_sk` int(11) NOT NULL,
  `date_sk` int(11) NOT NULL,
  `deviceidentifier` varchar(256) NOT NULL,
  `clientdataid` int(11) NOT NULL,
  `network_sk` int(11) NOT NULL,
  `hour_sk` int(11) NOT NULL,
  `isnew` tinyint(4) NOT NULL DEFAULT '1',
  `isnew_channel` tinyint(4) NOT NULL DEFAULT '1',
  `useridentifier` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`dataid`),
  KEY `deviceidentifier` (`deviceidentifier`(255)),
  KEY `product_sk` (`product_sk`,`date_sk`,`deviceidentifier`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_fact_errorlog
-- ----------------------------
DROP TABLE IF EXISTS `razor_fact_errorlog`;
CREATE TABLE `razor_fact_errorlog` (
  `errorid` int(11) NOT NULL AUTO_INCREMENT,
  `date_sk` int(11) NOT NULL,
  `product_sk` int(11) NOT NULL,
  `osversion_sk` int(11) NOT NULL,
  `title_sk` int(11) NOT NULL,
  `deviceidentifier` int(11) NOT NULL,
  `activity` varchar(512) NOT NULL,
  `time` datetime NOT NULL,
  `title` varchar(512) NOT NULL,
  `stacktrace` text NOT NULL,
  `isfix` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  PRIMARY KEY (`errorid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_fact_event
-- ----------------------------
DROP TABLE IF EXISTS `razor_fact_event`;
CREATE TABLE `razor_fact_event` (
  `eventid` int(11) NOT NULL AUTO_INCREMENT,
  `event_sk` int(11) NOT NULL,
  `product_sk` int(11) NOT NULL,
  `date_sk` int(11) NOT NULL,
  `deviceid` varchar(50) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `event` varchar(50) NOT NULL,
  `label` varchar(50) DEFAULT NULL,
  `attachment` varchar(50) DEFAULT NULL,
  `clientdate` datetime NOT NULL,
  `number` int(11) NOT NULL,
  PRIMARY KEY (`eventid`),
  KEY `date_sk` (`date_sk`,`product_sk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_fact_launch_daily
-- ----------------------------
DROP TABLE IF EXISTS `razor_fact_launch_daily`;
CREATE TABLE `razor_fact_launch_daily` (
  `launchid` int(11) NOT NULL AUTO_INCREMENT,
  `product_sk` int(11) NOT NULL,
  `date_sk` int(11) NOT NULL,
  `segment_sk` int(11) NOT NULL DEFAULT '0',
  `accesscount` int(11) NOT NULL,
  PRIMARY KEY (`launchid`),
  UNIQUE KEY `product_sk` (`product_sk`,`date_sk`,`segment_sk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_fact_usinglog
-- ----------------------------
DROP TABLE IF EXISTS `razor_fact_usinglog`;
CREATE TABLE `razor_fact_usinglog` (
  `usingid` int(11) NOT NULL AUTO_INCREMENT,
  `product_sk` int(11) NOT NULL,
  `date_sk` int(11) NOT NULL,
  `activity_sk` int(11) NOT NULL,
  `session_id` varchar(64) NOT NULL,
  `duration` int(11) NOT NULL,
  `activities` varchar(512) NOT NULL,
  `starttime` datetime NOT NULL,
  `endtime` datetime NOT NULL,
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`usingid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_fact_usinglog_daily
-- ----------------------------
DROP TABLE IF EXISTS `razor_fact_usinglog_daily`;
CREATE TABLE `razor_fact_usinglog_daily` (
  `usingid` int(11) NOT NULL AUTO_INCREMENT,
  `product_sk` int(11) NOT NULL,
  `date_sk` int(11) NOT NULL,
  `session_id` varchar(64) NOT NULL,
  `segment_sk` int(11) NOT NULL DEFAULT '0',
  `duration` int(11) NOT NULL,
  PRIMARY KEY (`usingid`),
  UNIQUE KEY `product_sk` (`product_sk`,`date_sk`,`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_hour24
-- ----------------------------
DROP TABLE IF EXISTS `razor_hour24`;
CREATE TABLE `razor_hour24` (
  `hour` tinyint(11) NOT NULL,
  PRIMARY KEY (`hour`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_log
-- ----------------------------
DROP TABLE IF EXISTS `razor_log`;
CREATE TABLE `razor_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `op_type` varchar(128) NOT NULL,
  `op_name` varchar(256) NOT NULL,
  `op_starttime` datetime DEFAULT NULL,
  `op_date` datetime DEFAULT NULL,
  `affected_rows` int(11) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_sum_accesslevel
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_accesslevel`;
CREATE TABLE `razor_sum_accesslevel` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `product_sk` int(11) DEFAULT NULL,
  `fromid` int(11) NOT NULL,
  `toid` int(11) NOT NULL,
  `level` int(11) DEFAULT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`pid`),
  UNIQUE KEY `date_sk` (`product_sk`,`fromid`,`toid`,`level`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_sum_accesspath
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_accesspath`;
CREATE TABLE `razor_sum_accesspath` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `product_sk` int(11) DEFAULT NULL,
  `fromid` int(11) NOT NULL,
  `toid` int(11) NOT NULL,
  `jump` int(11) DEFAULT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`pid`),
  UNIQUE KEY `date_sk` (`product_sk`,`fromid`,`toid`,`jump`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_sum_basic_activeusers
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_activeusers`;
CREATE TABLE `razor_sum_basic_activeusers` (
  `product_id` int(11) NOT NULL,
  `week_activeuser` int(11) NOT NULL DEFAULT '0',
  `month_activeuser` int(11) NOT NULL DEFAULT '0',
  `week_percent` float NOT NULL DEFAULT '0',
  `month_percent` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_sum_basic_activity
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_activity`;
CREATE TABLE `razor_sum_basic_activity` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `type` enum('newserver','operating') NOT NULL DEFAULT 'newserver' COMMENT 'newserver-新服活动,operating-运营活动',
  `activity_issue` varchar(255) NOT NULL COMMENT '活动期号',
  `activity_name` varchar(128) NOT NULL COMMENT '活动名',
  `startdate` date NOT NULL COMMENT '开始时间',
  `enddate` date NOT NULL COMMENT '结束时间',
  `validuser` int(11) NOT NULL COMMENT '有效用户数',
  `joinuser` int(11) NOT NULL COMMENT '参与人数',
  `joinrate` decimal(11,2) NOT NULL COMMENT '参与率',
  `coinconsume` int(11) NOT NULL COMMENT '金币消耗(付费货币消耗)',
  `useravgconsume` decimal(11,2) NOT NULL COMMENT '人均消耗',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`product_id`,`version_name`,`channel_name`,`server_name`,`type`,`activity_issue`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1635 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_activity_details
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_activity_details`;
CREATE TABLE `razor_sum_basic_activity_details` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `type` enum('newserver','operating') NOT NULL DEFAULT 'newserver' COMMENT 'newserver-新服活动,operating-运营活动',
  `activity_issue` varchar(255) NOT NULL COMMENT '活动期号',
  `detailstype` enum('output','consume','action') NOT NULL DEFAULT 'output' COMMENT 'output-产出,consume-消耗,action-行为',
  `propid` varchar(128) NOT NULL COMMENT '道具/行为ID',
  `propname` varchar(128) NOT NULL COMMENT '道具/行为名称',
  `proptype` varchar(128) NOT NULL COMMENT '道具/行为类型',
  `propcount` int(11) NOT NULL COMMENT '道具/行为数量',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`product_id`,`version_name`,`channel_name`,`server_name`,`type`,`activity_issue`,`detailstype`,`propid`,`propname`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_activity_distributeddetails
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_activity_distributeddetails`;
CREATE TABLE `razor_sum_basic_activity_distributeddetails` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `type` enum('newserver','operating') NOT NULL DEFAULT 'newserver' COMMENT 'newserver-新服活动,operating-运营活动',
  `activity_issue` varchar(128) NOT NULL COMMENT '活动期号',
  `detailstype` enum('output','consume','action') NOT NULL DEFAULT 'output' COMMENT 'output-产出,consume-消耗,action-行为',
  `propid` varchar(128) NOT NULL COMMENT '道具ID',
  `actionid` varchar(128) NOT NULL COMMENT '行为ID',
  `actionname` varchar(128) NOT NULL COMMENT '行为名称',
  `actioncount` int(11) NOT NULL COMMENT '行为数量',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`product_id`,`version_name`,`channel_name`,`server_name`,`type`,`activity_issue`,`detailstype`,`propid`,`actionid`,`actionname`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_borderintervaltime
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_borderintervaltime`;
CREATE TABLE `razor_sum_basic_borderintervaltime` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` varchar(11) NOT NULL COMMENT '开始日期编号',
  `enddate_sk` varchar(11) NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `logininterval` varchar(128) NOT NULL COMMENT '登录间隔',
  `rolecount` int(11) NOT NULL COMMENT '人数',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `productid` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`logininterval`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=30961 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_byhour
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_byhour`;
CREATE TABLE `razor_sum_basic_byhour` (
  `fid` int(11) NOT NULL AUTO_INCREMENT,
  `product_sk` int(11) NOT NULL,
  `date_sk` int(11) NOT NULL,
  `hour_sk` tinyint(11) NOT NULL,
  `sessions` int(11) NOT NULL DEFAULT '0',
  `startusers` int(11) NOT NULL DEFAULT '0',
  `newusers` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fid`),
  UNIQUE KEY `product_sk` (`product_sk`,`date_sk`,`hour_sk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_sum_basic_channel
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_channel`;
CREATE TABLE `razor_sum_basic_channel` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `date_sk` int(11) NOT NULL,
  `sessions` int(11) NOT NULL DEFAULT '0',
  `startusers` int(11) NOT NULL DEFAULT '0',
  `newusers` int(11) NOT NULL DEFAULT '0',
  `upgradeusers` int(11) NOT NULL DEFAULT '0',
  `allusers` int(11) NOT NULL DEFAULT '0',
  `allsessions` int(11) NOT NULL DEFAULT '0',
  `usingtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sid`),
  UNIQUE KEY `channel_id` (`product_id`,`channel_id`,`date_sk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_sum_basic_channel_activeusers
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_channel_activeusers`;
CREATE TABLE `razor_sum_basic_channel_activeusers` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `date_sk` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `activeuser` int(11) NOT NULL DEFAULT '0',
  `percent` float NOT NULL DEFAULT '0',
  `flag` int(11) NOT NULL,
  PRIMARY KEY (`pid`),
  UNIQUE KEY `date_sk` (`date_sk`,`product_id`,`channel_id`,`flag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_sum_basic_channelimportamount
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_channelimportamount`;
CREATE TABLE `razor_sum_basic_channelimportamount` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `newdevice` int(11) NOT NULL COMMENT '新增设备',
  `newregisterdevice` int(11) NOT NULL COMMENT '新增注册设备数',
  `deviceactivation` int(11) NOT NULL COMMENT '设备激活',
  `newregister` int(11) NOT NULL COMMENT '新增注册',
  `newuser` int(11) NOT NULL COMMENT '新增用户',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`channel_name`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`channel_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_channelincome
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_channelincome`;
CREATE TABLE `razor_sum_basic_channelincome` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `dauuser` int(11) NOT NULL COMMENT '活跃用户',
  `payuser` int(11) NOT NULL COMMENT '付费人数',
  `payamount` int(11) NOT NULL COMMENT '付费金额',
  `arpu` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT 'arpu',
  `arppu` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT 'arppu',
  `payrate` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '付费率',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`channel_name`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`channel_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_channelquality
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_channelquality`;
CREATE TABLE `razor_sum_basic_channelquality` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `dayavgdau` int(11) NOT NULL COMMENT '日均活跃',
  `daudegree` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '活跃度',
  `day1avgremain` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '次日均留存率',
  `day3avgremain` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '3日均留存率',
  `day7avgremain` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '7日均留存率',
  `day14avgremain` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '14日均留存率',
  `day30avgremain` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '30日均留存率',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`channel_name`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`channel_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_customremain_daily
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_customremain_daily`;
CREATE TABLE `razor_sum_basic_customremain_daily` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `type` enum('newuser','payuser','dauuser') NOT NULL DEFAULT 'newuser' COMMENT '过滤条件类型',
  `logintimes` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '登录次数',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本名称',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `count` int(11) NOT NULL DEFAULT '0' COMMENT '用户/设备数',
  `day1` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '1日留存',
  `day2` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '2日留存',
  `day3` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '3日留存',
  `day4` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '4日留存',
  `day5` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '5日留存',
  `day6` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '6日留存',
  `day7` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '7日留存',
  `day14` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '4日留存',
  `day30` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '30日留存',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `productid` (`startdate_sk`,`enddate_sk`,`type`,`logintimes`,`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `type` (`type`) USING BTREE,
  KEY `logintimes` (`logintimes`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=45314 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_customremain_monthly
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_customremain_monthly`;
CREATE TABLE `razor_sum_basic_customremain_monthly` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `type` enum('newuser','payuser','dauuser') NOT NULL DEFAULT 'newuser' COMMENT '过滤条件类型',
  `logintimes` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '登录次数',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本名称',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `count` int(11) NOT NULL DEFAULT '0' COMMENT '用户/设备数',
  `month1` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '1月留存',
  `month2` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '2月留存',
  `month3` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '3月留存',
  `month4` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '4月留存',
  `month5` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '5月留存',
  `month6` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '6月留存',
  `month7` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '7月留存',
  `month14` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '8月留存',
  `month30` decimal(11,4) NOT NULL,
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `id` (`startdate_sk`,`enddate_sk`,`type`,`logintimes`,`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `type` (`type`) USING BTREE,
  KEY `logintimes` (`logintimes`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=85508 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_customremain_weekly
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_customremain_weekly`;
CREATE TABLE `razor_sum_basic_customremain_weekly` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `type` enum('newuser','payuser','dauuser') NOT NULL DEFAULT 'newuser' COMMENT '过滤条件类型',
  `logintimes` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '登录次数',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本名称',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `count` int(11) NOT NULL DEFAULT '0' COMMENT '用户/设备数',
  `week1` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '1周留存',
  `week2` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '2周留存',
  `week3` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '3周留存',
  `week4` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '4周留存',
  `week5` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '5周留存',
  `week6` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '6周留存',
  `week7` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '7周留存',
  `week14` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '8周留存',
  `week30` decimal(11,4) NOT NULL,
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `id` (`startdate_sk`,`enddate_sk`,`type`,`logintimes`,`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `type` (`type`) USING BTREE,
  KEY `logintimes` (`logintimes`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=170907 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_dauusers
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_dauusers`;
CREATE TABLE `razor_sum_basic_dauusers` (
  `sid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `product_id` int(11) NOT NULL COMMENT '产品编号',
  `channel_name` varchar(64) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(64) NOT NULL COMMENT '区服名称',
  `version_name` varchar(64) NOT NULL COMMENT '版本名称',
  `date_sk` date NOT NULL COMMENT '日期编号',
  `newuser` int(11) NOT NULL DEFAULT '0' COMMENT '新增用户',
  `payuser` int(11) NOT NULL DEFAULT '0' COMMENT '付费用户',
  `notpayuser` int(11) NOT NULL DEFAULT '0' COMMENT '非付费用户',
  `daydau` int(11) NOT NULL DEFAULT '0' COMMENT '日活跃用户',
  `weekdau` int(11) NOT NULL DEFAULT '0' COMMENT '周活跃用户',
  `monthdau` int(11) NOT NULL DEFAULT '0' COMMENT '月活跃用户',
  `useractiverate` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '转化率',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`sid`),
  UNIQUE KEY `productid` (`product_id`,`channel_name`,`server_name`,`version_name`,`date_sk`) USING BTREE,
  KEY `server` (`product_id`,`channel_name`,`server_name`,`version_name`) USING BTREE,
  KEY `date` (`date_sk`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4958 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_sum_basic_dayonline
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_dayonline`;
CREATE TABLE `razor_sum_basic_dayonline` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` varchar(11) NOT NULL COMMENT '开始日期编号',
  `enddate_sk` varchar(11) NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `acu` decimal(11,2) NOT NULL COMMENT 'ACU',
  `pcu` int(11) NOT NULL COMMENT 'PCU',
  `ccu` decimal(11,2) NOT NULL COMMENT '（ACU/PCU）CCU比率',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `productid` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3850 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_deviceremain_daily
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_deviceremain_daily`;
CREATE TABLE `razor_sum_basic_deviceremain_daily` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` varchar(11) NOT NULL COMMENT '开始日期编号',
  `enddate_sk` varchar(11) NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本名称',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `devicecount` int(11) NOT NULL DEFAULT '0' COMMENT '新增设备',
  `day1` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '1日留存',
  `day2` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '2日留存',
  `day3` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '3日留存',
  `day4` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '4日留存',
  `day5` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '5日留存',
  `day6` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '6日留存',
  `day7` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '7日留存',
  `day14` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '14日留存',
  `day30` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '30日留存',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `productid` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1021 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_errorcodeanaly
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_errorcodeanaly`;
CREATE TABLE `razor_sum_basic_errorcodeanaly` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `errorid` int(11) NOT NULL COMMENT '错误码ID',
  `errorname` varchar(128) NOT NULL COMMENT '错误码',
  `erroruser` int(11) NOT NULL COMMENT '触发人数',
  `errorcount` int(11) NOT NULL COMMENT '触发次数',
  `errorrate` decimal(11,4) NOT NULL COMMENT '触发率',
  `useravgcount` decimal(11,2) NOT NULL COMMENT '人均次数',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`errorid`,`errorname`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3281 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_errorcodeanaly_action
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_errorcodeanaly_action`;
CREATE TABLE `razor_sum_basic_errorcodeanaly_action` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `errorid` int(11) NOT NULL COMMENT '错误码ID',
  `actionid` varchar(128) NOT NULL COMMENT '行为ID',
  `actionname` varchar(128) NOT NULL COMMENT '行为名',
  `actionuser` int(11) NOT NULL COMMENT '人数',
  `actioncount` int(11) NOT NULL COMMENT '次数',
  `useravgcount` decimal(11,2) NOT NULL COMMENT '人均次数',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`errorid`,`actionid`,`actionname`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_firstpayanaly_amount
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_firstpayanaly_amount`;
CREATE TABLE `razor_sum_basic_firstpayanaly_amount` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `firstpayamount` varchar(128) NOT NULL COMMENT '首付金额',
  `payusers` int(11) NOT NULL COMMENT '付费人数',
  `payusersrate` decimal(11,4) NOT NULL COMMENT '百分比',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `id` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`firstpayamount`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=25894 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_firstpayanaly_level
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_firstpayanaly_level`;
CREATE TABLE `razor_sum_basic_firstpayanaly_level` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `firstpaylevel` varchar(128) NOT NULL COMMENT '首付等级',
  `payusers` int(11) NOT NULL COMMENT '付费人数',
  `payusersrate` decimal(11,4) NOT NULL COMMENT '百分比',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `id` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`firstpaylevel`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=32959 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_firstpayanaly_time
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_firstpayanaly_time`;
CREATE TABLE `razor_sum_basic_firstpayanaly_time` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `firstpayname` varchar(128) NOT NULL COMMENT '首付时间',
  `payusers` int(11) NOT NULL COMMENT '付费人数',
  `payusersrate` decimal(11,4) NOT NULL COMMENT '百分比',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `id` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`firstpayname`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=45159 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_frequencytime_countday
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_frequencytime_countday`;
CREATE TABLE `razor_sum_basic_frequencytime_countday` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` varchar(11) NOT NULL COMMENT '开始日期编号',
  `enddate_sk` varchar(11) NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `usertype` enum('dauuser','payuser','unpayuser') NOT NULL DEFAULT 'dauuser',
  `counttype` enum('gamecount','gameday') NOT NULL DEFAULT 'gamecount' COMMENT '游戏次数、游戏天数',
  `countdatetype` enum('day','week','month') NOT NULL DEFAULT 'day' COMMENT '日、周、月',
  `field` varchar(128) NOT NULL COMMENT '游戏次数',
  `rolecount` int(11) NOT NULL COMMENT '用户数量',
  `rate` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '百分比',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `productid` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`counttype`,`countdatetype`,`usertype`,`field`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=137335 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_frequencytime_gametime
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_frequencytime_gametime`;
CREATE TABLE `razor_sum_basic_frequencytime_gametime` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` varchar(11) NOT NULL COMMENT '开始日期编号',
  `enddate_sk` varchar(11) NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `usertype` enum('dauuser','payuser','unpayuser') NOT NULL DEFAULT 'dauuser',
  `countdatetype` enum('day','week','onecount') NOT NULL DEFAULT 'day' COMMENT '日、周、单次',
  `field` varchar(128) NOT NULL COMMENT '游戏时长',
  `rolecount` int(11) NOT NULL COMMENT '用户数量',
  `rate` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '百分比',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `productid` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`countdatetype`,`field`,`usertype`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=92497 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_frequencytime_timefield
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_frequencytime_timefield`;
CREATE TABLE `razor_sum_basic_frequencytime_timefield` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` varchar(11) NOT NULL COMMENT '开始日期编号',
  `enddate_sk` varchar(11) NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `usertype` enum('dauuser','payuser','unpayuser') NOT NULL DEFAULT 'dauuser',
  `field` varchar(128) NOT NULL COMMENT '游戏时段',
  `rolecount` int(11) NOT NULL COMMENT '用户数量',
  `rate` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '百分比',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `productid` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`field`,`usertype`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=121825 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_leavecount
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_leavecount`;
CREATE TABLE `razor_sum_basic_leavecount` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `date_sk` date NOT NULL COMMENT '日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `usertag` enum('payuser','alluser') NOT NULL DEFAULT 'alluser' COMMENT '用户统计类型(所有用户、付费用户)',
  `sevenleaverate` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '7日流失率',
  `fourteenleaverate` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '14日流失率',
  `thirtyleaverate` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '30日流失率',
  `sevenreturnrate` int(11) NOT NULL DEFAULT '0' COMMENT '7日回流率',
  `fourteenreturnrate` int(11) NOT NULL DEFAULT '0' COMMENT '14日回流率',
  `thirtyreturnrate` int(11) NOT NULL DEFAULT '0' COMMENT '30日回流率',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `id` (`date_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`usertag`) USING BTREE,
  KEY `date` (`date_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6801 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_leavecount_levelaly
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_leavecount_levelaly`;
CREATE TABLE `razor_sum_basic_leavecount_levelaly` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `date_sk` date NOT NULL COMMENT '日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `usertag` enum('payuser','alluser') NOT NULL DEFAULT 'alluser' COMMENT '用户统计类型(所有用户、付费用户)',
  `levelfield` int(128) NOT NULL COMMENT '等级',
  `type` varchar(128) NOT NULL COMMENT '未登录类型(包括7日、14日、30日)',
  `users` int(11) NOT NULL DEFAULT '0' COMMENT '所有用户',
  `leveldistribitionrate` decimal(11,4) NOT NULL COMMENT '等级分布率',
  `payamount` int(11) NOT NULL DEFAULT '0' COMMENT '付费金额',
  `payusers` decimal(11,0) NOT NULL DEFAULT '0' COMMENT '7日回流率',
  `userrate` decimal(11,4) NOT NULL COMMENT '人数占比',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `id` (`date_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`usertag`,`levelfield`,`type`) USING BTREE,
  KEY `date` (`date_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=899874 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_levelanaly
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_levelanaly`;
CREATE TABLE `razor_sum_basic_levelanaly` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `levelfield` varchar(64) NOT NULL COMMENT '等级段',
  `newusers` int(11) NOT NULL,
  `newuserrate` decimal(11,4) NOT NULL,
  `dauusers` int(11) NOT NULL COMMENT '活跃用户',
  `dauuserrate` decimal(11,4) NOT NULL COMMENT '活跃用户百分比',
  `gamecount` int(11) NOT NULL COMMENT '游戏次数',
  `gamecountrate` decimal(11,4) NOT NULL COMMENT '游戏次数百分比',
  `gamestoprate` decimal(11,4) NOT NULL COMMENT '停滞率',
  `avglevelupgrade` decimal(32,2) NOT NULL DEFAULT '0.00' COMMENT '平均升级时长(单位：分钟)',
  `daylevelupgrade` decimal(32,2) NOT NULL COMMENT '日升级时长(单位：分钟)',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`levelfield`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=109587 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_levelanaly_totalupgradetime
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_levelanaly_totalupgradetime`;
CREATE TABLE `razor_sum_basic_levelanaly_totalupgradetime` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `levelfield` varchar(64) NOT NULL COMMENT '等级段',
  `upgradetime` varchar(64) NOT NULL COMMENT '升级时长',
  `dauusers` int(11) NOT NULL COMMENT '活跃角色',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`levelfield`,`upgradetime`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=411091 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_levelleave
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_levelleave`;
CREATE TABLE `razor_sum_basic_levelleave` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `date_sk` date NOT NULL COMMENT '日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `rolelevel` varchar(128) NOT NULL COMMENT '等级',
  `levelleaverate` decimal(11,4) NOT NULL COMMENT '等级流失率',
  `avgupdatetime` decimal(11,2) NOT NULL COMMENT '平均升级时长',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `id` (`date_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`rolelevel`) USING BTREE,
  KEY `date` (`date_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=154335 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_newcreaterole
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_newcreaterole`;
CREATE TABLE `razor_sum_basic_newcreaterole` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `newregister` int(11) NOT NULL COMMENT '新增注册',
  `newuser` int(11) NOT NULL COMMENT '新增用户',
  `newcreaterole` int(11) NOT NULL COMMENT '新增创角',
  `mixcreaterole` int(11) NOT NULL COMMENT '滚服用户创角',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=291 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_newpay
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_newpay`;
CREATE TABLE `razor_sum_basic_newpay` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `date_sk` varchar(11) NOT NULL COMMENT '日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `firstdaypayuser` int(11) NOT NULL DEFAULT '0' COMMENT '首日-付费人数',
  `firstdaypaycount` decimal(11,4) NOT NULL COMMENT '首日-付费率',
  `firstweekpayuser` int(11) NOT NULL DEFAULT '0' COMMENT '首周-付费人数',
  `firstweekpaycount` decimal(11,4) NOT NULL COMMENT '首周-付费率',
  `firstmonthpayuser` int(11) NOT NULL DEFAULT '0' COMMENT '首月-付费人数',
  `firstmonthpaycount` decimal(11,4) NOT NULL COMMENT '首月-付费率',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `date_sk` (`date_sk`,`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE,
  KEY `date` (`date_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3401 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_newuserprogress
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_newuserprogress`;
CREATE TABLE `razor_sum_basic_newuserprogress` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `step` int(11) NOT NULL COMMENT '步骤ID',
  `stepname` varchar(255) DEFAULT NULL,
  `stepcount` int(11) NOT NULL COMMENT '步骤次数',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`step`,`stepname`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=580683 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_newusers
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_newusers`;
CREATE TABLE `razor_sum_basic_newusers` (
  `sid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `channel_name` varchar(64) NOT NULL COMMENT '渠道名称',
  `version_name` varchar(64) NOT NULL COMMENT '版本名称',
  `date_sk` varchar(11) NOT NULL COMMENT '日期编号',
  `deviceactivations` int(11) NOT NULL DEFAULT '0' COMMENT '设备激活',
  `newusers` int(11) NOT NULL DEFAULT '0' COMMENT '新增用户',
  `newdevices` int(11) NOT NULL DEFAULT '0' COMMENT '新增设备',
  `userconversion` decimal(11,4) NOT NULL,
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`sid`),
  UNIQUE KEY `id` (`product_id`,`channel_name`,`version_name`,`date_sk`) USING BTREE,
  KEY `date` (`date_sk`) USING BTREE,
  KEY `server` (`product_id`,`channel_name`,`version_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1292 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_sum_basic_payanaly_arpu_daily
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_payanaly_arpu_daily`;
CREATE TABLE `razor_sum_basic_payanaly_arpu_daily` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `dayarpu` decimal(11,4) NOT NULL COMMENT '日ARPU',
  `dayarppu` decimal(11,4) NOT NULL COMMENT '日ARPPU',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3928 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_payanaly_arpu_monthly
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_payanaly_arpu_monthly`;
CREATE TABLE `razor_sum_basic_payanaly_arpu_monthly` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `montharpu` decimal(11,4) NOT NULL COMMENT '月ARPU',
  `montharppu` decimal(11,4) NOT NULL COMMENT '月ARPPU',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1078 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_payanaly_paycount
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_payanaly_paycount`;
CREATE TABLE `razor_sum_basic_payanaly_paycount` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `type` enum('month','week','day') DEFAULT 'day',
  `paycount` varchar(128) NOT NULL COMMENT '付费次数',
  `payusers` int(11) NOT NULL COMMENT '日付费人数',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `id` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`paycount`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=74067 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_payanaly_payfield
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_payanaly_payfield`;
CREATE TABLE `razor_sum_basic_payanaly_payfield` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `type` enum('month','week','day') DEFAULT 'day',
  `payfield` varchar(128) NOT NULL COMMENT '付费区间',
  `payusers` int(11) NOT NULL COMMENT '日付费人数',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `id` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`payfield`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`)
) ENGINE=InnoDB AUTO_INCREMENT=40530 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_paydata
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_paydata`;
CREATE TABLE `razor_sum_basic_paydata` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `date_sk` varchar(11) NOT NULL COMMENT '日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `daupayamount` int(11) NOT NULL DEFAULT '0' COMMENT '活跃用户-付费金额',
  `daupayuser` int(11) NOT NULL DEFAULT '0' COMMENT '活跃用户-付费人数',
  `daupaycount` int(11) NOT NULL DEFAULT '0' COMMENT '活跃用户-付费次数',
  `firstpayamount` int(11) NOT NULL DEFAULT '0' COMMENT '首次付费用户-付费金额',
  `firstpayuser` int(11) NOT NULL DEFAULT '0' COMMENT '首次付费用户-付费人数',
  `firstpaycount` int(11) NOT NULL DEFAULT '0' COMMENT '首次付费用户-付费次数',
  `firstdaypayamount` int(11) NOT NULL DEFAULT '0' COMMENT '首日付费用户-付费金额',
  `firstdaypayuser` int(11) NOT NULL DEFAULT '0' COMMENT '首日付费用户-付费人数',
  `firstdaypaycount` int(11) NOT NULL DEFAULT '0' COMMENT '首日付费用户-付费次数',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `date_sk` (`date_sk`,`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE,
  KEY `date` (`date_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3984 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_payinterval_first
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_payinterval_first`;
CREATE TABLE `razor_sum_basic_payinterval_first` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `firstpaytime` varchar(128) NOT NULL COMMENT '首充时间',
  `payusers` int(11) NOT NULL COMMENT '付费人数',
  `payusersrate` decimal(11,4) NOT NULL COMMENT '百分比',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `id` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`firstpaytime`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=56191 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_payinterval_second
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_payinterval_second`;
CREATE TABLE `razor_sum_basic_payinterval_second` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `firstpaytime` varchar(128) NOT NULL COMMENT '二充时间间隔',
  `payusers` int(11) NOT NULL COMMENT '付费人数',
  `payusersrate` decimal(11,4) NOT NULL COMMENT '百分比',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `id` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`firstpaytime`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=54631 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_payinterval_third
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_payinterval_third`;
CREATE TABLE `razor_sum_basic_payinterval_third` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `firstpaytime` varchar(128) NOT NULL COMMENT '三充时间间隔',
  `payusers` int(11) NOT NULL COMMENT '付费人数',
  `payusersrate` decimal(11,4) NOT NULL COMMENT '百分比',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `id` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`firstpaytime`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=53077 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_paylevel
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_paylevel`;
CREATE TABLE `razor_sum_basic_paylevel` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` varchar(11) DEFAULT NULL COMMENT '开始日期编号',
  `enddate_sk` varchar(11) DEFAULT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `rolelevel` int(11) NOT NULL COMMENT '玩家角色等级',
  `payamount` int(11) NOT NULL COMMENT '付费金额',
  `paycount` int(11) NOT NULL COMMENT '付费次数',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `id` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`rolelevel`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=171333 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_payrank
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_payrank`;
CREATE TABLE `razor_sum_basic_payrank` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `ranks` int(11) NOT NULL COMMENT '排名',
  `roleId` varchar(255) NOT NULL,
  `account` varchar(128) NOT NULL COMMENT '帐号',
  `rolelevel` int(11) NOT NULL COMMENT '等级',
  `roleviplevel` int(11) NOT NULL COMMENT 'VIP等级',
  `registerdate` date NOT NULL COMMENT '注册日期',
  `firstpaydate` date NOT NULL COMMENT '首付日期',
  `totalpayamount` int(11) NOT NULL COMMENT '总付费',
  `paycount` int(11) NOT NULL COMMENT '付费次数',
  `onlinedays` int(11) NOT NULL COMMENT '在线天数',
  `gametime` int(11) NOT NULL COMMENT '游戏时长',
  `gamecount` int(11) NOT NULL COMMENT '游戏次数',
  `tag` varchar(128) NOT NULL COMMENT '趋势',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `id` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`ranks`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_payrank_trend
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_payrank_trend`;
CREATE TABLE `razor_sum_basic_payrank_trend` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` int(11) NOT NULL COMMENT '开始日期编号',
  `enddate_sk` int(11) NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `roleId` varchar(255) NOT NULL,
  `gametimemin` int(11) NOT NULL COMMENT '在线时长min',
  `logintimes` int(11) NOT NULL COMMENT '登录次数',
  `payamount` int(11) NOT NULL COMMENT '付费金额',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`roleId`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_payrate_daily
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_payrate_daily`;
CREATE TABLE `razor_sum_basic_payrate_daily` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` varchar(11) NOT NULL COMMENT '开始日期编号',
  `enddate_sk` varchar(11) NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `daypayrate` decimal(11,4) NOT NULL COMMENT '日付费率',
  `totalpayrate` decimal(11,4) NOT NULL COMMENT '累计付费率',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4738 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_payrate_monthly
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_payrate_monthly`;
CREATE TABLE `razor_sum_basic_payrate_monthly` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `monthpayrate` decimal(11,4) NOT NULL COMMENT '月付费率',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `id` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1858 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_payrate_weekly
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_payrate_weekly`;
CREATE TABLE `razor_sum_basic_payrate_weekly` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `weekpayrate` decimal(11,4) NOT NULL COMMENT '周付费率',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `id` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2148 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_product
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_product`;
CREATE TABLE `razor_sum_basic_product` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `date_sk` int(11) NOT NULL,
  `sessions` int(11) NOT NULL DEFAULT '0',
  `startusers` int(11) NOT NULL DEFAULT '0',
  `newusers` int(11) NOT NULL DEFAULT '0',
  `upgradeusers` int(11) NOT NULL DEFAULT '0',
  `allusers` int(11) NOT NULL DEFAULT '0',
  `allsessions` int(11) NOT NULL DEFAULT '0',
  `usingtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sid`),
  UNIQUE KEY `product_id` (`product_id`,`date_sk`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_sum_basic_product_version
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_product_version`;
CREATE TABLE `razor_sum_basic_product_version` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `date_sk` int(11) NOT NULL,
  `version_name` varchar(64) NOT NULL,
  `sessions` int(11) NOT NULL DEFAULT '0',
  `startusers` int(11) NOT NULL DEFAULT '0',
  `newusers` int(11) NOT NULL DEFAULT '0',
  `upgradeusers` int(11) NOT NULL DEFAULT '0',
  `allusers` int(11) NOT NULL DEFAULT '0',
  `allsessions` int(11) NOT NULL DEFAULT '0',
  `usingtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sid`),
  UNIQUE KEY `product_id` (`product_id`,`date_sk`,`version_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_sum_basic_propanaly
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_propanaly`;
CREATE TABLE `razor_sum_basic_propanaly` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `type` enum('newuser','dauuser','payuser') NOT NULL DEFAULT 'newuser' COMMENT 'newuser-新增用户,dauuser-活跃用户,payuser-付费用户',
  `prop_name` varchar(128) NOT NULL COMMENT '道具名称',
  `prop_type` varchar(128) NOT NULL COMMENT '道具类型',
  `shopbuy` int(11) NOT NULL COMMENT '商城购买道具数量',
  `shopbuyuser` int(11) NOT NULL COMMENT '商城购买道具人数',
  `systemdonate` int(11) NOT NULL COMMENT '系统赠送道具数量',
  `systemdonateuser` int(11) NOT NULL COMMENT '系统赠送道具人数',
  `functiongaincount` int(11) NOT NULL COMMENT '功能产出道具数量',
  `functiongaincountuser` int(11) NOT NULL COMMENT '功能产出道具人数',
  `activitygaincount` int(11) NOT NULL COMMENT '活动产出道具数量',
  `activitygaincountuser` int(11) NOT NULL COMMENT '活动产出道具人数',
  `totalgaincount` int(11) NOT NULL COMMENT '总产出道具数量',
  `totalconsumecount` int(11) NOT NULL COMMENT '总消耗道具数量',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`type`,`prop_name`,`prop_type`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=322063 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_propanaly_gainconsume
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_propanaly_gainconsume`;
CREATE TABLE `razor_sum_basic_propanaly_gainconsume` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `type` enum('newuser','dauuser','payuser') NOT NULL DEFAULT 'newuser' COMMENT 'newuser-新增用户,dauuser-活跃用户,payuser-付费用户',
  `prop_name` varchar(128) NOT NULL COMMENT '道具名称',
  `product_type` enum('gain','consume') NOT NULL DEFAULT 'gain' COMMENT 'gain-道具产出,consume-道具消耗',
  `action_name` varchar(128) NOT NULL COMMENT '功能名称',
  `prop_count` varchar(128) NOT NULL COMMENT '道具数量',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`type`,`prop_name`,`product_type`,`action_name`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=17363 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_propanaly_vipuser
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_propanaly_vipuser`;
CREATE TABLE `razor_sum_basic_propanaly_vipuser` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `type` enum('newuser','dauuser','payuser') NOT NULL DEFAULT 'newuser' COMMENT 'newuser-新增用户,dauuser-活跃用户,payuser-付费用户',
  `prop_name` varchar(128) NOT NULL COMMENT '道具名称',
  `action_type` varchar(128) NOT NULL COMMENT '行为类型：商城购买、系统赠送、功能产出、活动产出;total-总消耗产出',
  `viplevel` varchar(128) NOT NULL COMMENT 'VIP等级',
  `rolecount` int(11) NOT NULL COMMENT '角色数',
  `propgaincount` int(11) NOT NULL COMMENT '道具获得数量',
  `propconsumecount` int(11) NOT NULL COMMENT '道具消耗数量',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`type`,`prop_name`,`action_type`,`viplevel`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=31206 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_realtimeroleinfo
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_realtimeroleinfo`;
CREATE TABLE `razor_sum_basic_realtimeroleinfo` (
  `sid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `product_id` int(11) NOT NULL COMMENT '产品编号',
  `channel_name` varchar(64) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(64) NOT NULL COMMENT '区服名称',
  `version_name` varchar(64) NOT NULL COMMENT '版本名称',
  `count_date` date NOT NULL COMMENT '统计日期',
  `count_time` datetime NOT NULL COMMENT '统计时间',
  `onlineusers` int(11) DEFAULT NULL COMMENT '在线用户',
  `newusers` int(16) DEFAULT NULL COMMENT '新增用户',
  `dauusers` int(16) DEFAULT NULL COMMENT '活跃用户',
  `payamount` int(16) DEFAULT NULL COMMENT '付费金额',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`sid`),
  UNIQUE KEY `productid` (`product_id`,`channel_name`,`server_name`,`version_name`,`count_date`,`count_time`) USING BTREE,
  KEY `server` (`product_id`,`channel_name`,`server_name`,`version_name`) USING BTREE,
  KEY `date` (`count_date`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=470901 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_sa_activity
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_sa_activity`;
CREATE TABLE `razor_sum_basic_sa_activity` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `activity_name` varchar(128) NOT NULL COMMENT '活动名',
  `activity_issue` varchar(128) NOT NULL COMMENT '活动期号',
  `startdate` date NOT NULL COMMENT '开始时间',
  `enddate` date NOT NULL COMMENT '结束时间',
  `selectuser` int(11) NOT NULL COMMENT '查看人数',
  `joinuser` int(11) NOT NULL COMMENT '参与人数',
  `joinrate` decimal(11,4) NOT NULL COMMENT '参与率',
  `goldconsume` int(11) NOT NULL COMMENT '金币消耗',
  `goldconsumeavg` decimal(11,4) NOT NULL COMMENT '人均金币消耗',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`activity_name`,`activity_issue`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_sa_activity_action
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_sa_activity_action`;
CREATE TABLE `razor_sum_basic_sa_activity_action` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `activity_name` varchar(128) NOT NULL COMMENT '活动名',
  `activity_issue` varchar(128) NOT NULL COMMENT '活动期号',
  `prop_id` varchar(128) NOT NULL COMMENT '道具ID',
  `action_id` varchar(128) NOT NULL COMMENT '行为ID',
  `action_name` varchar(128) NOT NULL COMMENT '行为名',
  `action_count` int(11) NOT NULL COMMENT '数量',
  `action_rate` decimal(11,4) NOT NULL COMMENT '占比',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`activity_name`,`activity_issue`,`prop_id`,`action_id`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_sa_activity_prop
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_sa_activity_prop`;
CREATE TABLE `razor_sum_basic_sa_activity_prop` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `activity_name` varchar(128) NOT NULL COMMENT '活动名',
  `activity_issue` varchar(128) NOT NULL COMMENT '活动期号',
  `prop_id` varchar(128) NOT NULL COMMENT '道具ID',
  `prop_name` varchar(128) NOT NULL COMMENT '道具名称',
  `prop_type` varchar(128) NOT NULL COMMENT '道具类型',
  `prop_count` int(11) NOT NULL COMMENT '道具数量',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`activity_name`,`activity_issue`,`prop_id`,`prop_name`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_sa_function
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_sa_function`;
CREATE TABLE `razor_sum_basic_sa_function` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `function` varchar(128) NOT NULL COMMENT '功能',
  `function_useuser` int(11) NOT NULL COMMENT '使用人数',
  `function_usecount` int(11) NOT NULL COMMENT '使用次数',
  `function_userate` decimal(11,4) NOT NULL COMMENT '使用率',
  `function_goldoutput` bigint(32) NOT NULL COMMENT '金币产出',
  `function_goldoutputrate` decimal(11,4) NOT NULL COMMENT '金币产出占比',
  `function_goldconsume` bigint(32) NOT NULL COMMENT '金币消耗',
  `function_goldconsumerate` decimal(11,4) NOT NULL COMMENT '金币消耗占比',
  `function_sliveroutput` bigint(32) NOT NULL COMMENT '银币产出',
  `function_sliveroutputrate` decimal(11,4) NOT NULL COMMENT '银币产出占比',
  `function_sliverconsume` bigint(32) NOT NULL COMMENT '银币消耗',
  `function_sliverconsumerate` decimal(11,4) NOT NULL COMMENT '银币消耗占比',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`function`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=32736 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_sa_function_behaviour
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_sa_function_behaviour`;
CREATE TABLE `razor_sum_basic_sa_function_behaviour` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `function` varchar(128) NOT NULL COMMENT '功能',
  `prop_id` int(128) NOT NULL COMMENT '道具ID',
  `behaviour_id` int(128) NOT NULL COMMENT '行为ID',
  `behaviour_name` varchar(128) NOT NULL COMMENT '行为名',
  `behaviour_gaincount` bigint(32) NOT NULL,
  `behaviour_gainrate` decimal(11,4) NOT NULL COMMENT '占比',
  `behaviour_consumecount` bigint(32) NOT NULL,
  `behaviour_consumerate` decimal(11,4) NOT NULL,
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`function`,`prop_id`,`behaviour_id`,`behaviour_name`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=168105 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_sa_function_prop
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_sa_function_prop`;
CREATE TABLE `razor_sum_basic_sa_function_prop` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `function` varchar(128) NOT NULL COMMENT '功能',
  `type` enum('action','propconsume','propgain') NOT NULL DEFAULT 'propgain',
  `prop_id` int(128) NOT NULL COMMENT '道具ID/行为ID',
  `prop_name` varchar(128) NOT NULL COMMENT '道具名/行为名',
  `prop_type` varchar(128) NOT NULL COMMENT '道具类型',
  `prop_count` bigint(32) NOT NULL COMMENT '道具产出、消耗数量/行为次',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`function`,`type`,`prop_id`,`prop_name`,`prop_type`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=175767 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_sa_nature
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_sa_nature`;
CREATE TABLE `razor_sum_basic_sa_nature` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `virtualmoney` varchar(128) NOT NULL COMMENT '虚拟币',
  `virtualmoney_nature` varchar(128) NOT NULL COMMENT '角色性质',
  `virtualmoney_usercount` int(11) NOT NULL COMMENT '人数',
  `virtualmoney_outputcount` int(11) NOT NULL COMMENT '产出数量',
  `virtualmoney_consumecount` int(11) NOT NULL COMMENT '消耗数量',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`virtualmoney`,`virtualmoney_nature`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_sa_taskanalysis_step
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_sa_taskanalysis_step`;
CREATE TABLE `razor_sum_basic_sa_taskanalysis_step` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `tasktype` enum('newuser','main','branch','general') NOT NULL DEFAULT 'newuser' COMMENT '任务类型',
  `task` varchar(64) NOT NULL COMMENT '任务',
  `step` int(11) NOT NULL COMMENT '步骤',
  `step_desc` varchar(64) NOT NULL COMMENT '步骤描述',
  `stepactiveuser` int(11) NOT NULL COMMENT '步骤激活人数',
  `steppassuser` int(11) NOT NULL COMMENT '步骤通过人数',
  `steppassrate` decimal(11,4) NOT NULL COMMENT '步骤通过率',
  `stepstaycount` int(11) NOT NULL COMMENT '步骤停留次数',
  `stepstayrate` decimal(11,4) NOT NULL COMMENT '步骤停留率',
  `steptotalstayrate` decimal(11,4) NOT NULL COMMENT '步骤累计停留率',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`tasktype`,`task`,`step`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1129085 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_sa_taskanalysis_task
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_sa_taskanalysis_task`;
CREATE TABLE `razor_sum_basic_sa_taskanalysis_task` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `tasktype` enum('newuser','main','branch','general') NOT NULL DEFAULT 'newuser' COMMENT '任务类型',
  `task` varchar(64) NOT NULL COMMENT '任务',
  `stepcount` int(11) NOT NULL COMMENT '步骤数',
  `taskactivecount` int(11) NOT NULL COMMENT '任务激活次数',
  `taskdonecount` int(11) NOT NULL COMMENT '任务完成次数',
  `taskdonerate` decimal(11,4) NOT NULL COMMENT '任务完成率',
  `taskactiveuser` int(11) NOT NULL COMMENT '任务激活人数',
  `taskstayuser` int(11) NOT NULL COMMENT '任务停留人数',
  `taskstayrate` decimal(11,4) NOT NULL COMMENT '任务停留率',
  `taskpassuser` int(11) NOT NULL COMMENT '任务通过人数',
  `taskpassrate` decimal(11,4) NOT NULL COMMENT '任务通过率',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`tasktype`,`task`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=383627 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_sa_tollgateanalysis_big
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_sa_tollgateanalysis_big`;
CREATE TABLE `razor_sum_basic_sa_tollgateanalysis_big` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `tollgate_bigcategory` varchar(128) NOT NULL COMMENT '关卡大类',
  `tollgate_totalcount` int(11) NOT NULL COMMENT '关卡总数',
  `tollgate_attackcount` int(11) NOT NULL COMMENT '攻打次数',
  `tollgate_successcount` int(11) NOT NULL COMMENT '胜利次数',
  `tollgate_successrate` decimal(11,4) NOT NULL COMMENT '胜率',
  `tollgate_attackuser` int(11) NOT NULL COMMENT '攻打人数',
  `tollgate_passuser` int(11) NOT NULL COMMENT '通过人数',
  `tollgate_passrate` decimal(11,4) NOT NULL COMMENT '通关率',
  `tollgate_sweepcount` int(11) NOT NULL COMMENT '扫荡次数',
  `tollgate_sweepuser` int(11) NOT NULL COMMENT '扫荡人数',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`tollgate_bigcategory`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7636 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_sa_tollgateanalysis_small
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_sa_tollgateanalysis_small`;
CREATE TABLE `razor_sum_basic_sa_tollgateanalysis_small` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `tollgate_bigcategory` varchar(128) NOT NULL COMMENT '关卡大类',
  `tollgate_id` varchar(128) NOT NULL COMMENT '小关卡',
  `tollgate_name` varchar(128) NOT NULL COMMENT '小关卡名称',
  `tollgate_attackcount` int(11) NOT NULL COMMENT '攻打次数',
  `tollgate_successcount` int(11) NOT NULL COMMENT '胜利次数',
  `tollgate_failcount` int(11) NOT NULL COMMENT '失败次数',
  `tollgate_successrate` decimal(11,4) NOT NULL COMMENT '胜率',
  `tollgate_attackuser` int(11) NOT NULL COMMENT '攻打人数',
  `tollgate_passuser` int(11) NOT NULL COMMENT '通过人数',
  `tollgate_passrate` decimal(11,4) NOT NULL COMMENT '通关率',
  `tollgate_sweepcount` int(11) NOT NULL COMMENT '扫荡次数',
  `tollgate_sweepuser` int(11) NOT NULL COMMENT '扫荡人数',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`tollgate_bigcategory`,`tollgate_id`,`tollgate_name`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=479770 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_sa_virtualmoney
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_sa_virtualmoney`;
CREATE TABLE `razor_sum_basic_sa_virtualmoney` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `virtualmoney` varchar(128) NOT NULL COMMENT '虚拟币',
  `virtualmoney_output` bigint(32) NOT NULL COMMENT '产出+',
  `virtualmoney_consume` bigint(32) NOT NULL COMMENT '消耗-',
  `virtualmoney_outputuser` bigint(32) NOT NULL COMMENT '产出角色',
  `virtualmoney_consumeuser` bigint(32) NOT NULL COMMENT '消耗角色',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`virtualmoney`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11342 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_sa_virtualmoney_outputway
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_sa_virtualmoney_outputway`;
CREATE TABLE `razor_sum_basic_sa_virtualmoney_outputway` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `virtualmoney` varchar(128) NOT NULL COMMENT '虚拟币',
  `type` enum('consume','gain') DEFAULT 'gain',
  `virtualmoney_outputway` varchar(128) NOT NULL COMMENT '产出途径',
  `virtualmoney_output` varchar(128) NOT NULL COMMENT '产出',
  `virtualmoney_outputrate` decimal(32,4) NOT NULL COMMENT '产出占比',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`virtualmoney`,`virtualmoney_outputway`,`type`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=30052 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_sa_virtualmoney_viplevel
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_sa_virtualmoney_viplevel`;
CREATE TABLE `razor_sum_basic_sa_virtualmoney_viplevel` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `virtualmoney` varchar(128) NOT NULL COMMENT '虚拟币',
  `virtualmoney_viplevel` varchar(128) NOT NULL COMMENT '角色性质',
  `virtualmoney_usercount` bigint(32) NOT NULL COMMENT '人数',
  `virtualmoney_outputcount` bigint(32) NOT NULL COMMENT '产出数量',
  `virtualmoney_consumecount` bigint(32) NOT NULL COMMENT '消耗数量',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`virtualmoney`,`virtualmoney_viplevel`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11339 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_start_login_peoplesbycount
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_start_login_peoplesbycount`;
CREATE TABLE `razor_sum_basic_start_login_peoplesbycount` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` varchar(11) NOT NULL COMMENT '开始日期编号',
  `enddate_sk` varchar(11) NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `counts` varchar(11) NOT NULL COMMENT '次数',
  `startpeoples` int(11) NOT NULL COMMENT '启动人数',
  `loginpeoples` int(11) NOT NULL COMMENT '登录人数',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `productid` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`counts`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=13979 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_start_login_peoplesbytime
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_start_login_peoplesbytime`;
CREATE TABLE `razor_sum_basic_start_login_peoplesbytime` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` varchar(11) NOT NULL COMMENT '开始日期编号',
  `enddate_sk` varchar(11) NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `timefield` varchar(11) NOT NULL COMMENT '时间点',
  `startpeoples` int(11) NOT NULL COMMENT '启动人数',
  `loginpeoples` int(11) NOT NULL COMMENT '登录人数',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `productid` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`timefield`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=7681 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_start_login_times
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_start_login_times`;
CREATE TABLE `razor_sum_basic_start_login_times` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` varchar(11) NOT NULL COMMENT '开始日期编号',
  `enddate_sk` varchar(11) NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `starttimes` int(11) NOT NULL COMMENT '启动次数',
  `logintimes` int(11) NOT NULL COMMENT '登录次数',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `productid` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2039 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_time_count_avg
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_time_count_avg`;
CREATE TABLE `razor_sum_basic_time_count_avg` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` varchar(11) NOT NULL COMMENT '开始日期编号',
  `enddate_sk` varchar(11) NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `datetype` enum('day','week','month') NOT NULL DEFAULT 'day' COMMENT '日周月过滤类型',
  `playertype` enum('dauuser','payuser','unpayuser') NOT NULL DEFAULT 'dauuser' COMMENT '活跃、付费、非付费玩家过滤类型',
  `avggamecount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '每用户平均游戏次数',
  `avggametime` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '每用户平均游戏时长',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `productid` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`datetype`,`playertype`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12685 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_userltv
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_userltv`;
CREATE TABLE `razor_sum_basic_userltv` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `newuser` int(11) NOT NULL COMMENT '新增用户',
  `day1` decimal(11,2) NOT NULL COMMENT '1日',
  `day2` decimal(11,2) NOT NULL COMMENT '2日',
  `day3` decimal(11,2) NOT NULL COMMENT '3日',
  `day4` decimal(11,2) NOT NULL COMMENT '4日',
  `day5` decimal(11,2) NOT NULL COMMENT '5日',
  `day6` decimal(11,2) NOT NULL COMMENT '6日',
  `day7` decimal(11,2) NOT NULL COMMENT '7日',
  `day14` decimal(11,2) NOT NULL COMMENT '14日',
  `day30` decimal(11,2) NOT NULL COMMENT '30日',
  `day60` decimal(11,2) NOT NULL COMMENT '60日',
  `day90` decimal(11,2) NOT NULL COMMENT '90日',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4501 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_userltv_timefield
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_userltv_timefield`;
CREATE TABLE `razor_sum_basic_userltv_timefield` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `newuser` int(11) NOT NULL COMMENT '新增用户',
  `timefield` varchar(128) NOT NULL COMMENT '时间进度',
  `date_sk` date NOT NULL COMMENT '日期',
  `payuser` int(11) NOT NULL COMMENT '付费人数',
  `payamount` int(11) NOT NULL COMMENT '付费金额',
  `ltv` decimal(11,2) NOT NULL COMMENT 'LTV',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`newuser`,`timefield`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=409501 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_userremain_daily
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_userremain_daily`;
CREATE TABLE `razor_sum_basic_userremain_daily` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本名称',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `usercount` int(11) NOT NULL DEFAULT '0' COMMENT '新增用户',
  `day1` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '1日留存',
  `day2` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '2日留存',
  `day3` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '3日留存',
  `day4` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '4日留存',
  `day5` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '5日留存',
  `day6` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '6日留存',
  `day7` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '7日留存',
  `day14` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '14日留存',
  `day30` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '30日留存',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `productid` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5101 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_vipremain
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_vipremain`;
CREATE TABLE `razor_sum_basic_vipremain` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `viplevel` int(11) NOT NULL COMMENT 'vip等级',
  `usercount` int(11) NOT NULL COMMENT '用户数',
  `day1` decimal(11,4) NOT NULL COMMENT '次日',
  `day2` decimal(11,4) NOT NULL COMMENT '2日',
  `day3` decimal(11,4) NOT NULL COMMENT '3日',
  `day4` decimal(11,4) NOT NULL COMMENT '4日',
  `day5` decimal(11,4) NOT NULL COMMENT '5日',
  `day6` decimal(11,4) NOT NULL COMMENT '6日',
  `day7` decimal(11,4) NOT NULL COMMENT '7日',
  `day14` decimal(11,4) NOT NULL COMMENT '14日',
  `day30` decimal(11,4) NOT NULL COMMENT '30日',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`viplevel`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4851 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_viprole_leavealany
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_viprole_leavealany`;
CREATE TABLE `razor_sum_basic_viprole_leavealany` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `viplevel` int(11) NOT NULL COMMENT 'vip等级',
  `day3leave` int(11) NOT NULL COMMENT '3日流失',
  `day3leaverate` decimal(11,4) NOT NULL COMMENT '3日流失率',
  `day7leave` int(11) NOT NULL COMMENT '7日流失',
  `day7leaverate` decimal(11,4) NOT NULL COMMENT '7日流失率',
  `day14leave` int(11) NOT NULL COMMENT '14日流失',
  `day14leaverate` decimal(11,4) NOT NULL COMMENT '14日流失率',
  `day30leave` int(11) NOT NULL COMMENT '30日流失',
  `day30leaverate` decimal(11,4) NOT NULL COMMENT '30日流失率',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`viplevel`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3401 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_viprole_leavealany_userlevel
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_viprole_leavealany_userlevel`;
CREATE TABLE `razor_sum_basic_viprole_leavealany_userlevel` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `viplevel` int(11) NOT NULL COMMENT 'vip等级',
  `userlevel` int(11) NOT NULL COMMENT '用户等级',
  `day3leave` int(11) NOT NULL COMMENT '3日流失',
  `day3leaverate` decimal(11,4) NOT NULL COMMENT '3日流失率',
  `day7leave` int(11) NOT NULL COMMENT '7日流失',
  `day7leaverate` decimal(11,4) NOT NULL COMMENT '7日流失率',
  `day14leave` int(11) NOT NULL COMMENT '14日流失',
  `day14leaverate` decimal(11,4) NOT NULL COMMENT '14日流失率',
  `day30leave` int(11) NOT NULL COMMENT '30日流失',
  `day30leaverate` decimal(11,4) NOT NULL COMMENT '30日流失率',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`viplevel`,`userlevel`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=314841 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_viprole_payalany
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_viprole_payalany`;
CREATE TABLE `razor_sum_basic_viprole_payalany` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `viplevel` int(11) NOT NULL COMMENT 'vip等级',
  `payamount` int(11) NOT NULL COMMENT '付费金额',
  `payuser` int(11) NOT NULL COMMENT '付费人数',
  `paycount` int(11) NOT NULL COMMENT '付费次数',
  `dayavgpaycount` decimal(11,2) NOT NULL COMMENT '日均付费次数',
  `dayavgpayuser` decimal(11,2) NOT NULL COMMENT '日均付费人数',
  `useravgpaycount` decimal(11,2) NOT NULL COMMENT '人均付费次数',
  `payrate` decimal(11,2) NOT NULL COMMENT '付费率',
  `arpu` decimal(11,2) NOT NULL COMMENT 'ARPU',
  `arppu` decimal(11,2) NOT NULL COMMENT 'ARPPU',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`viplevel`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=611 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_basic_viprole_useralany
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_viprole_useralany`;
CREATE TABLE `razor_sum_basic_viprole_useralany` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `startdate_sk` date NOT NULL COMMENT '开始日期编号',
  `enddate_sk` date NOT NULL COMMENT '结束日期编号',
  `product_id` int(11) NOT NULL COMMENT '产品ID',
  `version_name` varchar(128) NOT NULL COMMENT '版本号',
  `channel_name` varchar(128) NOT NULL COMMENT '渠道名称',
  `server_name` varchar(128) NOT NULL COMMENT '区服名称',
  `viplevel` int(11) NOT NULL COMMENT 'vip等级',
  `newvip` int(11) NOT NULL COMMENT '新增vip',
  `outvip` int(11) NOT NULL COMMENT '去掉vip',
  `currentvip` int(11) NOT NULL COMMENT '当前vip',
  `dauvip` int(11) NOT NULL COMMENT '活跃vip',
  `dayavggamecount` decimal(11,2) NOT NULL COMMENT '日均游戏次数',
  `pergametime` decimal(11,2) NOT NULL COMMENT '每次游戏时长',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`,`server_name`,`viplevel`) USING BTREE,
  KEY `startdate` (`startdate_sk`) USING BTREE,
  KEY `server` (`product_id`,`version_name`,`channel_name`,`server_name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=611 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for razor_sum_devicebrand
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_devicebrand`;
CREATE TABLE `razor_sum_devicebrand` (
  `did` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `date_sk` int(11) NOT NULL,
  `devicebrand_sk` int(11) NOT NULL,
  `sessions` int(11) NOT NULL DEFAULT '0',
  `newusers` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`did`),
  UNIQUE KEY `index_devicebrand` (`product_id`,`date_sk`,`devicebrand_sk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_sum_devicenetwork
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_devicenetwork`;
CREATE TABLE `razor_sum_devicenetwork` (
  `did` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `date_sk` int(11) NOT NULL,
  `devicenetwork_sk` int(11) NOT NULL,
  `sessions` int(11) NOT NULL DEFAULT '0',
  `newusers` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`did`),
  UNIQUE KEY `index_devicenetwork` (`product_id`,`date_sk`,`devicenetwork_sk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_sum_deviceos
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_deviceos`;
CREATE TABLE `razor_sum_deviceos` (
  `did` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `date_sk` int(11) NOT NULL,
  `deviceos_sk` int(11) NOT NULL,
  `sessions` int(11) NOT NULL DEFAULT '0',
  `newusers` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`did`),
  UNIQUE KEY `index_deviceos` (`product_id`,`date_sk`,`deviceos_sk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_sum_deviceresolution
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_deviceresolution`;
CREATE TABLE `razor_sum_deviceresolution` (
  `did` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `date_sk` int(11) NOT NULL,
  `deviceresolution_sk` int(11) NOT NULL,
  `sessions` int(11) NOT NULL DEFAULT '0',
  `newusers` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`did`),
  UNIQUE KEY `index_deviceresolution` (`product_id`,`date_sk`,`deviceresolution_sk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_sum_devicesupplier
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_devicesupplier`;
CREATE TABLE `razor_sum_devicesupplier` (
  `did` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `date_sk` int(11) NOT NULL,
  `devicesupplier_sk` int(11) NOT NULL,
  `sessions` int(11) NOT NULL DEFAULT '0',
  `newusers` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`did`),
  UNIQUE KEY `index_devicesupplier` (`product_id`,`date_sk`,`devicesupplier_sk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_sum_event
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_event`;
CREATE TABLE `razor_sum_event` (
  `eid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_sk` int(11) NOT NULL,
  `date_sk` int(11) NOT NULL,
  `event_sk` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  PRIMARY KEY (`eid`),
  UNIQUE KEY `product_sk` (`product_sk`,`date_sk`,`event_sk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_sum_location
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_location`;
CREATE TABLE `razor_sum_location` (
  `lid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `date_sk` int(11) NOT NULL,
  `location_sk` int(11) NOT NULL,
  `sessions` int(11) NOT NULL DEFAULT '0',
  `newusers` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lid`),
  UNIQUE KEY `index_location` (`product_id`,`date_sk`,`location_sk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_sum_reserveusers_daily
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_reserveusers_daily`;
CREATE TABLE `razor_sum_reserveusers_daily` (
  `rid` int(11) NOT NULL AUTO_INCREMENT,
  `startdate_sk` int(11) NOT NULL,
  `enddate_sk` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `version_name` varchar(128) NOT NULL,
  `channel_name` varchar(128) NOT NULL,
  `usercount` int(11) NOT NULL DEFAULT '0',
  `day1` int(11) NOT NULL DEFAULT '0',
  `day2` int(11) NOT NULL DEFAULT '0',
  `day3` int(11) NOT NULL DEFAULT '0',
  `day4` int(11) NOT NULL DEFAULT '0',
  `day5` int(11) NOT NULL DEFAULT '0',
  `day6` int(11) NOT NULL DEFAULT '0',
  `day7` int(11) NOT NULL DEFAULT '0',
  `day8` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_sum_reserveusers_monthly
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_reserveusers_monthly`;
CREATE TABLE `razor_sum_reserveusers_monthly` (
  `rid` int(11) NOT NULL AUTO_INCREMENT,
  `startdate_sk` int(11) NOT NULL,
  `enddate_sk` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `version_name` varchar(128) NOT NULL,
  `channel_name` varchar(128) NOT NULL,
  `usercount` int(11) NOT NULL DEFAULT '0',
  `month1` int(11) NOT NULL DEFAULT '0',
  `month2` int(11) NOT NULL DEFAULT '0',
  `month3` int(11) NOT NULL DEFAULT '0',
  `month4` int(11) NOT NULL DEFAULT '0',
  `month5` int(11) NOT NULL DEFAULT '0',
  `month6` int(11) NOT NULL DEFAULT '0',
  `month7` int(11) NOT NULL DEFAULT '0',
  `month8` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_sum_reserveusers_weekly
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_reserveusers_weekly`;
CREATE TABLE `razor_sum_reserveusers_weekly` (
  `rid` int(11) NOT NULL AUTO_INCREMENT,
  `startdate_sk` int(11) NOT NULL,
  `enddate_sk` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `version_name` varchar(128) NOT NULL,
  `channel_name` varchar(128) NOT NULL,
  `usercount` int(11) NOT NULL DEFAULT '0',
  `week1` int(11) NOT NULL DEFAULT '0',
  `week2` int(11) NOT NULL DEFAULT '0',
  `week3` int(11) NOT NULL DEFAULT '0',
  `week4` int(11) NOT NULL DEFAULT '0',
  `week5` int(11) NOT NULL DEFAULT '0',
  `week6` int(11) NOT NULL DEFAULT '0',
  `week7` int(11) NOT NULL DEFAULT '0',
  `week8` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rid`),
  UNIQUE KEY `startdate_sk` (`startdate_sk`,`enddate_sk`,`product_id`,`version_name`,`channel_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for razor_sum_usinglog_activity
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_usinglog_activity`;
CREATE TABLE `razor_sum_usinglog_activity` (
  `usingid` int(11) NOT NULL AUTO_INCREMENT,
  `date_sk` int(11) NOT NULL,
  `product_sk` int(11) NOT NULL,
  `activity_sk` int(11) DEFAULT NULL,
  `accesscount` int(11) NOT NULL DEFAULT '0',
  `totaltime` int(11) NOT NULL DEFAULT '0',
  `exitcount` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`usingid`),
  UNIQUE KEY `date_sk` (`date_sk`,`product_sk`,`activity_sk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- View structure for VIEW_razor_product_channel_version_server
-- ----------------------------
DROP VIEW IF EXISTS `VIEW_razor_product_channel_version_server`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `VIEW_razor_product_channel_version_server` AS select `p`.`product_sk` AS `product_sk`,`p`.`product_id` AS `product_id`,`p`.`product_name` AS `product_name`,`p`.`product_type` AS `product_type`,`p`.`product_active` AS `product_active`,`p`.`channel_id` AS `channel_id`,`p`.`channel_name` AS `channel_name`,`p`.`channel_active` AS `channel_active`,`p`.`product_key` AS `product_key`,`p`.`version_name` AS `version_name`,`p`.`version_active` AS `version_active`,`p`.`userid` AS `userid`,`p`.`platform` AS `platform`,`s`.`server_id` AS `server_id`,`s`.`server_name` AS `server_name` from (`razor_dw`.`razor_dim_product` `p` join `razor`.`razor_server` `s` on((`p`.`product_id` = `s`.`product_id`))) where (`s`.`active` = 1) ;

-- ----------------------------
-- View structure for VIEW_razor_sum_basic_capacityview_perserver
-- ----------------------------
DROP VIEW IF EXISTS `VIEW_razor_sum_basic_capacityview_perserver`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `VIEW_razor_sum_basic_capacityview_perserver` AS select `d`.`startdate_sk` AS `startdate_sk`,`d`.`product_id` AS `product_id`,`d`.`channel_name` AS `channel_name`,`d`.`version_name` AS `version_name`,`d`.`server_name` AS `server_name`,ifnull(`d`.`pcu`,0) AS `curdate_pcu`,ifnull(`s`.`server_capacity`,0) AS `server_capacity`,ifnull((`d`.`pcu` / `s`.`server_capacity`),0) AS `userate` from (`razor_dw`.`razor_sum_basic_dayonline` `d` left join `razor`.`razor_server` `s` on(((`d`.`product_id` = `s`.`product_id`) and (`s`.`active` = 1) and (`d`.`server_name` = `s`.`server_name`)))) where (`d`.`server_name` <> 'all') ;

-- ----------------------------
-- View structure for VIEW_razor_sum_basic_capacityview_totalserver
-- ----------------------------
DROP VIEW IF EXISTS `VIEW_razor_sum_basic_capacityview_totalserver`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `VIEW_razor_sum_basic_capacityview_totalserver` AS select `d`.`startdate_sk` AS `startdate_sk`,`d`.`product_id` AS `product_id`,`d`.`version_name` AS `version_name`,`d`.`channel_name` AS `channel_name`,ifnull(sum(`d`.`pcu`),0) AS `totalpcu`,ifnull(sum(`s`.`server_capacity`),0) AS `totalcapacity`,ifnull((sum(`d`.`pcu`) / sum(`s`.`server_capacity`)),0.00) AS `userate` from (`razor_dw`.`razor_sum_basic_dayonline` `d` left join `razor`.`razor_server` `s` on(((`d`.`product_id` = `s`.`product_id`) and (`s`.`active` = 1) and (`d`.`server_name` = `s`.`server_name`)))) where (`d`.`server_name` <> 'all') group by `d`.`startdate_sk`,`d`.`product_id`,`d`.`version_name`,`d`.`channel_name` ;
