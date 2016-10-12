/*
Navicat MySQL Data Transfer

Source Server         : 192.168.80.154-razor
Source Server Version : 50624
Source Host           : 192.168.80.154:3306
Source Database       : razor

Target Server Type    : MYSQL
Target Server Version : 50624
File Encoding         : 65001

Date: 2015-12-25 17:39:47
*/

SET FOREIGN_KEY_CHECKS=0;

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
  KEY `insertdate` (`insertdate`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
