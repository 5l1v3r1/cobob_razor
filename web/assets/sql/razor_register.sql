/*
Navicat MySQL Data Transfer

Source Server         : 192.168.80.154-razor
Source Server Version : 50624
Source Host           : 192.168.80.154:3306
Source Database       : razor

Target Server Type    : MYSQL
Target Server Version : 50624
File Encoding         : 65001

Date: 2015-12-25 17:39:55
*/

SET FOREIGN_KEY_CHECKS=0;

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
  KEY `insertdate` (`insertdate`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
