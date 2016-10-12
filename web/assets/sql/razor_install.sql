/*
Navicat MySQL Data Transfer

Source Server         : 192.168.80.154-razor
Source Server Version : 50624
Source Host           : 192.168.80.154:3306
Source Database       : razor

Target Server Type    : MYSQL
Target Server Version : 50624
File Encoding         : 65001

Date: 2015-12-25 17:39:34
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for razor_install
-- ----------------------------
DROP TABLE IF EXISTS `razor_install`;
CREATE TABLE `razor_install` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `install_date` date NOT NULL COMMENT '安装日期',
  `chId` varchar(255) NOT NULL COMMENT '渠道编号',
  `version` varchar(255) NOT NULL COMMENT '版本号',
  `userId` varchar(255) NOT NULL COMMENT 'Razor用户编号',
  `productkey` varchar(255) NOT NULL COMMENT '应用key值',
  `deviceid` varchar(255) NOT NULL COMMENT '设备编号',
  `install_time` datetime NOT NULL COMMENT '安装时间',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '入库时间',
  PRIMARY KEY (`ID`),
  KEY `insertdate` (`insertdate`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
