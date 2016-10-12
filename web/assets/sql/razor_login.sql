/*
Navicat MySQL Data Transfer

Source Server         : 192.168.80.154-razor
Source Server Version : 50624
Source Host           : 192.168.80.154:3306
Source Database       : razor

Target Server Type    : MYSQL
Target Server Version : 50624
File Encoding         : 65001

Date: 2015-12-25 17:39:40
*/

SET FOREIGN_KEY_CHECKS=0;

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
  KEY `insertdate` (`insertdate`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
