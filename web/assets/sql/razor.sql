/*
Navicat MySQL Data Transfer

Source Server         : localhost-test
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : razor

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2015-12-23 11:07:47
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
-- Records of razor_alert
-- ----------------------------
INSERT INTO `razor_alert` VALUES ('1', '1', '1', '50', 't_newUser', '1', 'wangguoqing@longtugame.com;wangguoqing@ogresugar.com');

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
-- Records of razor_alertdetail
-- ----------------------------

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
-- Records of razor_cell_towers
-- ----------------------------

-- ----------------------------
-- Table structure for razor_channel
-- ----------------------------
DROP TABLE IF EXISTS `razor_channel`;
CREATE TABLE `razor_channel` (
  `channel_id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_name` varchar(255) NOT NULL DEFAULT '',
  `create_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(11) NOT NULL DEFAULT '1',
  `type` enum('system','user') NOT NULL DEFAULT 'user',
  `platform` int(10) NOT NULL,
  `active` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`channel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_channel
-- ----------------------------
INSERT INTO `razor_channel` VALUES ('1', '安卓市场', '2011-11-22 13:54:39', '1', 'system', '1', '1');
INSERT INTO `razor_channel` VALUES ('2', '机锋市场', '2011-11-22 13:54:47', '1', 'system', '1', '1');
INSERT INTO `razor_channel` VALUES ('3', '安智市场', '2011-11-22 13:54:57', '1', 'system', '1', '1');
INSERT INTO `razor_channel` VALUES ('4', 'XDA市场', '2011-11-22 13:55:03', '1', 'system', '1', '1');
INSERT INTO `razor_channel` VALUES ('5', 'AppStore', '2011-12-03 13:49:25', '1', 'system', '2', '1');
INSERT INTO `razor_channel` VALUES ('6', 'Windows Phone Store', '2011-12-03 13:49:25', '1', 'system', '3', '1');
INSERT INTO `razor_channel` VALUES ('7', '测试一', '2015-12-17 16:10:41', '1', 'user', '1', '1');

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_channel_product
-- ----------------------------
INSERT INTO `razor_channel_product` VALUES ('1', '', '', '', '', '', '2015-12-14 18:51:50', 'f0e1815b61767ffa9206d4dc1048b26e', '0', '1', '1', '1');
INSERT INTO `razor_channel_product` VALUES ('2', '', '', '', '', '', '2015-12-14 18:52:12', 'fc3a385376da10c1757ae5a2874b941e', '0', '1', '2', '6');
INSERT INTO `razor_channel_product` VALUES ('3', '', '', '', '', '', '2015-12-14 18:52:27', 'e023aff4d19fb4c09a9437f246adac95', '0', '1', '3', '5');
INSERT INTO `razor_channel_product` VALUES ('4', null, '', '', '', '', '2015-12-18 18:43:46', 'cc58b7e837bfa1cd2aa060d144f8335d', '0', '1', '1', '2');
INSERT INTO `razor_channel_product` VALUES ('5', null, '', '', '', '', '2015-12-18 18:43:48', '623c1514234da6b3768419ab1c0b502d', '0', '1', '1', '3');
INSERT INTO `razor_channel_product` VALUES ('6', null, '', '', '', '', '2015-12-18 18:43:50', '35c617c51da70145a3a8edb18968684a', '0', '1', '1', '4');

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
-- Records of razor_ci_sessions
-- ----------------------------
INSERT INTO `razor_ci_sessions` VALUES ('25a30c9e7950d2ff5a49891a9ecea165', '0.0.0.0', 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.155 Safari/537.36', '1450837356', 0x613A393A7B733A393A22757365725F64617461223B733A303A22223B733A373A22757365725F6964223B733A313A2231223B733A383A22757365726E616D65223B733A353A2261646D696E223B733A363A22737461747573223B733A313A2231223B733A31353A22636F6D7061726550726F6475637473223B4E3B733A31343A2263757272656E7450726F64756374223B4F3A383A22737464436C617373223A31303A7B733A323A226964223B733A313A2231223B733A343A226E616D65223B733A31343A2253616D706C655F616E64726F6964223B733A31313A226465736372697074696F6E223B733A303A22223B733A343A2264617465223B733A31393A22323031352D31322D31342031383A35313A3530223B733A373A22757365725F6964223B733A313A2231223B733A31333A226368616E6E656C5F636F756E74223B733A313A2234223B733A31313A2270726F647563745F6B6579223B733A33323A226630653138313562363137363766666139323036643464633130343862323665223B733A31363A2270726F647563745F706C6174666F726D223B733A313A2231223B733A383A2263617465676F7279223B733A323A223333223B733A363A22616374697665223B733A313A2231223B7D733A373A226368616E6E656C223B733A333A22616C6C223B733A363A22736572766572223B733A333A22616C6C223B733A373A2276657273696F6E223B733A333A22616C6C223B7D);

-- ----------------------------
-- Table structure for razor_clientdata
-- ----------------------------
DROP TABLE IF EXISTS `razor_clientdata`;
CREATE TABLE `razor_clientdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `serviceversion` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `version` varchar(50) DEFAULT NULL,
  `server_id` int(11) DEFAULT NULL,
  `platform` varchar(50) DEFAULT NULL,
  `osversion` varchar(50) DEFAULT NULL,
  `osaddtional` varchar(50) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `resolution` varchar(50) DEFAULT NULL,
  `ismobiledevice` varchar(50) DEFAULT NULL,
  `devicename` varchar(50) DEFAULT NULL,
  `deviceid` varchar(200) DEFAULT NULL,
  `defaultbrowser` varchar(50) DEFAULT NULL,
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
  `date` datetime NOT NULL,
  `clientip` varchar(50) NOT NULL,
  `productkey` varchar(50) NOT NULL,
  `service_supplier` varchar(64) DEFAULT NULL,
  `country` varchar(50) DEFAULT 'unknown',
  `region` varchar(50) DEFAULT 'unknown',
  `city` varchar(50) DEFAULT 'unknown',
  `street` varchar(500) DEFAULT NULL,
  `streetno` varchar(50) DEFAULT NULL,
  `postcode` varchar(50) DEFAULT NULL,
  `network` varchar(128) NOT NULL DEFAULT '1',
  `isjailbroken` int(10) NOT NULL DEFAULT '0',
  `insertdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `useridentifier` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `insertdate` (`insertdate`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_clientdata
-- ----------------------------
INSERT INTO `razor_clientdata` VALUES ('1', null, null, '1.0', '1', 'android', '4.4', null, 'zh', '480x800', '1', 'Unknown sdk', '9401a87f322c05f9c93272bc7ed69d10', '', '', '', 'sdk', '000000000000000', '310260000000000', null, 'false', '0', '0', '0', '', '', '', '2015-12-14 22:31:07', '192.168.87.86', 'f0e1815b61767ffa9206d4dc1048b26e', '310260', '局域网', '局域网', 'unknown', '', '', '', 'epc.tmobile.com', '0', '2015-12-15 11:35:36', null);
INSERT INTO `razor_clientdata` VALUES ('2', null, null, '1.0', '1', 'android', '4.4', null, 'zh', '480x800', '1', 'Unknown sdk', '9401a87f322c05f9c93272bc7ed69d10', '', '', '', 'sdk', '000000000000000', '310260000000000', null, 'false', '0', '0', '0', '', '', '', '2015-12-14 22:31:07', '192.168.87.86', 'f0e1815b61767ffa9206d4dc1048b26e', '310260', '局域网', '局域网', 'unknown', '', '', '', 'epc.tmobile.com', '0', '2015-12-15 13:50:05', null);
INSERT INTO `razor_clientdata` VALUES ('3', null, null, '1.0', '1', 'android', '4.4', null, 'zh', '480x800', '1', 'Unknown sdk', '9401a87f322c05f9c93272bc7ed69d10', '', '', '', 'sdk', '000000000000000', '310260000000000', null, 'false', '0', '0', '0', '', '', '', '2015-12-15 00:50:51', '192.168.87.86', 'f0e1815b61767ffa9206d4dc1048b26e', '310260', '局域网', '局域网', 'unknown', '', '', '', 'epc.tmobile.com', '0', '2015-12-15 13:50:15', null);
INSERT INTO `razor_clientdata` VALUES ('4', null, null, '1.0', '1', 'android', '4.4', null, 'zh', '480x800', '1', 'Unknown sdk', '9401a87f322c05f9c93272bc7ed69d10', '', '', '', 'sdk', '000000000000000', '310260000000000', null, 'false', '0', '0', '0', '', '', '', '2015-12-14 22:31:07', '192.168.87.86', 'f0e1815b61767ffa9206d4dc1048b26e', '310260', '局域网', '局域网', 'unknown', '', '', '', 'epc.tmobile.com', '0', '2015-12-16 14:57:35', null);
INSERT INTO `razor_clientdata` VALUES ('5', null, null, '1.0', '1', 'android', '4.4', null, 'zh', '480x800', '1', 'Unknown sdk', '9401a87f322c05f9c93272bc7ed69d10', '', '', '', 'sdk', '000000000000000', '310260000000000', null, 'false', '0', '0', '0', '', '', '', '2015-12-15 00:50:51', '192.168.87.86', 'f0e1815b61767ffa9206d4dc1048b26e', '310260', '局域网', '局域网', 'unknown', '', '', '', 'epc.tmobile.com', '0', '2015-12-16 14:57:36', null);
INSERT INTO `razor_clientdata` VALUES ('6', null, null, '1.0', '1', 'android', '4.4', null, 'zh', '480x800', '1', 'Unknown sdk', '9401a87f322c05f9c93272bc7ed69d10', '', '', '', 'sdk', '000000000000000', '310260000000000', null, 'false', '0', '0', '0', '', '', '', '2015-12-16 01:58:22', '192.168.87.86', 'f0e1815b61767ffa9206d4dc1048b26e', '310260', '局域网', '局域网', 'unknown', '', '', '', 'epc.tmobile.com', '0', '2015-12-16 14:59:40', null);
INSERT INTO `razor_clientdata` VALUES ('7', null, null, '1.0', '1', 'android', '4.4', null, 'zh', '480x800', '1', 'Unknown sdk', '9401a87f322c05f9c93272bc7ed69d10', '', '', '', 'sdk', '000000000000000', '310260000000000', null, 'false', '0', '0', '0', '', '', '', '2015-12-14 22:31:07', '192.168.87.86', 'f0e1815b61767ffa9206d4dc1048b26e', '310260', '局域网', '局域网', 'unknown', '', '', '', 'epc.tmobile.com', '0', '2015-12-16 14:59:40', null);
INSERT INTO `razor_clientdata` VALUES ('8', null, null, '1.0', '1', 'android', '4.4', null, 'zh', '480x800', '1', 'Unknown sdk', '9401a87f322c05f9c93272bc7ed69d10', '', '', '', 'sdk', '000000000000000', '310260000000000', null, 'false', '0', '0', '0', '', '', '', '2015-12-15 00:50:51', '192.168.87.86', 'f0e1815b61767ffa9206d4dc1048b26e', '310260', '局域网', '局域网', 'unknown', '', '', '', 'epc.tmobile.com', '0', '2015-12-16 14:59:40', null);
INSERT INTO `razor_clientdata` VALUES ('9', null, null, '1.0', '1', 'android', '4.4', null, 'zh', '480x800', '1', 'Unknown sdk', '9401a87f322c05f9c93272bc7ed69d10', '', '', '', 'sdk', '000000000000000', '310260000000000', null, 'false', '0', '0', '0', '', '', '', '2015-12-16 01:58:22', '192.168.87.86', 'f0e1815b61767ffa9206d4dc1048b26e', '310260', '局域网', '局域网', 'unknown', '', '', '', 'epc.tmobile.com', '0', '2015-12-16 14:59:40', null);
INSERT INTO `razor_clientdata` VALUES ('10', null, null, '1.0', '1', 'android', '4.4', null, 'zh', '480x800', '1', 'Unknown sdk', '9401a87f322c05f9c93272bc7ed69d10', '', '', '', 'sdk', '000000000000000', '310260000000000', null, 'false', '0', '0', '0', '', '', '', '2015-12-16 02:00:26', '192.168.87.86', 'f0e1815b61767ffa9206d4dc1048b26e', '310260', '局域网', '局域网', 'unknown', '', '', '', 'epc.tmobile.com', '0', '2015-12-16 14:59:48', null);

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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_clientusinglog
-- ----------------------------
INSERT INTO `razor_clientusinglog` VALUES ('1', '184a81e8be4d685aaa509d139f45a62b', '2015-12-14 22:36:25', '2015-12-14 22:36:25', '68', '.CobubSampleActivity', 'f0e1815b61767ffa9206d4dc1048b26e', '1.0', '2015-12-15 13:50:05');
INSERT INTO `razor_clientusinglog` VALUES ('2', '184a81e8be4d685aaa509d139f45a62b', '2015-12-14 22:31:14', '2015-12-14 22:36:24', '309053', '.CobubSampleActivity', 'f0e1815b61767ffa9206d4dc1048b26e', '1.0', '2015-12-15 13:50:05');
INSERT INTO `razor_clientusinglog` VALUES ('3', '184a81e8be4d685aaa509d139f45a62b', '2015-12-14 22:31:14', '2015-12-14 22:36:24', '309053', '.CobubSampleActivity', 'f0e1815b61767ffa9206d4dc1048b26e', '1.0', '2015-12-15 13:50:05');
INSERT INTO `razor_clientusinglog` VALUES ('4', '06a9f0c1f9567105b962089256d1f8f4', '2015-12-15 00:50:57', '2015-12-15 00:50:57', '107', '.CobubSampleActivity', 'f0e1815b61767ffa9206d4dc1048b26e', '1.0', '2015-12-15 13:50:15');
INSERT INTO `razor_clientusinglog` VALUES ('5', '06a9f0c1f9567105b962089256d1f8f4', '2015-12-15 00:50:59', '2015-12-15 00:51:00', '1363', '.CobubSampleActivity', 'f0e1815b61767ffa9206d4dc1048b26e', '1.0', '2015-12-16 14:57:35');
INSERT INTO `razor_clientusinglog` VALUES ('6', '184a81e8be4d685aaa509d139f45a62b', '2015-12-14 22:31:14', '2015-12-14 22:36:24', '309053', '.CobubSampleActivity', 'f0e1815b61767ffa9206d4dc1048b26e', '1.0', '2015-12-16 14:57:36');
INSERT INTO `razor_clientusinglog` VALUES ('7', '06a9f0c1f9567105b962089256d1f8f4', '2015-12-15 00:50:57', '2015-12-15 00:50:57', '107', '.CobubSampleActivity', 'f0e1815b61767ffa9206d4dc1048b26e', '1.0', '2015-12-16 14:57:36');
INSERT INTO `razor_clientusinglog` VALUES ('8', '8dc5c86a8ae18c7b9aac1f2f5078de9b', '2015-12-16 01:58:29', '2015-12-16 01:58:29', '43', '.CobubSampleActivity', 'f0e1815b61767ffa9206d4dc1048b26e', '1.0', '2015-12-16 14:59:40');
INSERT INTO `razor_clientusinglog` VALUES ('9', '184a81e8be4d685aaa509d139f45a62b', '2015-12-14 22:31:14', '2015-12-14 22:36:24', '309053', '.CobubSampleActivity', 'f0e1815b61767ffa9206d4dc1048b26e', '1.0', '2015-12-16 14:59:40');
INSERT INTO `razor_clientusinglog` VALUES ('10', '06a9f0c1f9567105b962089256d1f8f4', '2015-12-15 00:50:57', '2015-12-15 00:50:57', '107', '.CobubSampleActivity', 'f0e1815b61767ffa9206d4dc1048b26e', '1.0', '2015-12-16 14:59:40');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_config
-- ----------------------------
INSERT INTO `razor_config` VALUES ('1', '1', '1', '1', '30', '1');
INSERT INTO `razor_config` VALUES ('2', '1', '1', '2', '30', '1');
INSERT INTO `razor_config` VALUES ('3', '1', '1', '3', '30', '1');

-- ----------------------------
-- Table structure for razor_createrole
-- ----------------------------
DROP TABLE IF EXISTS `razor_createrole`;
CREATE TABLE `razor_createrole` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `create_role_date` date NOT NULL,
  `chId` varchar(255) NOT NULL,
  `subSrvId` varchar(255) NOT NULL,
  `srvId` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL,
  `userId` varchar(255) NOT NULL,
  `productkey` varchar(255) NOT NULL,
  `deviceid` varchar(255) NOT NULL,
  `create_role_time` datetime NOT NULL,
  `roleName` varchar(255) NOT NULL,
  `roleSex` varchar(255) NOT NULL,
  `roleClass` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `idx_date` (`create_role_date`) USING BTREE,
  KEY `idx_srvid` (`srvId`) USING BTREE,
  KEY `idx_rolename` (`roleName`) USING BTREE,
  KEY `idx_chId` (`chId`) USING BTREE,
  KEY `idx_userid` (`userId`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_createrole
-- ----------------------------
INSERT INTO `razor_createrole` VALUES ('1', '2015-12-15', '1', '', '1', '1.0', 'qihoo_1431218909', 'f0e1815b61767ffa9206d4dc1048b26e', '9401a87f322c05f9c93272bc7ed69d10', '2015-12-15 23:55:17', '酷仔', '0', '0\n');
INSERT INTO `razor_createrole` VALUES ('2', '2015-12-15', '1', '', '1', '1.0', 'qihoo_509325425', 'f0e1815b61767ffa9206d4dc1048b26e', '9401a87f322c05f9c93272bc7ed69d10', '2015-12-15 23:55:26', '火瀑弥尔顿', '0', '0\n');

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
-- Records of razor_device_tag
-- ----------------------------

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_errorlog
-- ----------------------------
INSERT INTO `razor_errorlog` VALUES ('1', 'f0e1815b61767ffa9206d4dc1048b26e', 'Unknown sdk', '4.4', '.CobubSampleActivity', '2015-12-14 22:36:26', 'java.lang.ArithmeticException: divide by zero\n	at com.wbtech.test_sample.CobubSampleActivity$2.onClick(CobubSampleActivity.java:154)\n	at android.view.View.performClick(View.java:4424)', 'java.lang.ArithmeticException: divide by zero\n	at com.wbtech.test_sample.CobubSampleActivity$2.onClick(CobubSampleActivity.java:154)\n	at android.view.View.performClick(View.java:4424)\n	java.lang.ArithmeticException: divide by zero\n	at com.wbtech.test_sample.CobubSampleActivity$2.onClick(CobubSampleActivity.java:154)\n	at android.view.View.performClick(View.java:4424)\n	at android.view.View$PerformClick.run(View.java:18383)\n	at android.os.Handler.handleCallback(Handler.java:733)\n	at android.os.Handler.dispatchMessage(Handler.java:95)\n	at android.os.Looper.loop(Looper.java:137)\n	at android.app.ActivityThread.main(ActivityThread.java:4998)\n	at java.lang.reflect.Method.invokeNative(Native Method)\n	at java.lang.reflect.Method.invoke(Method.java:515)\n	at com.android.internal.os.ZygoteInit$MethodAndArgsCaller.run(ZygoteInit.java:777)\n	at com.android.internal.os.ZygoteInit.main(ZygoteInit.java:593)\n	at dalvik.system.NativeStart.main(Native Method)\n', '1.0', null, '2015-12-15 13:50:05');
INSERT INTO `razor_errorlog` VALUES ('2', 'f0e1815b61767ffa9206d4dc1048b26e', 'Unknown sdk', '4.4', '.CobubSampleActivity', '2015-12-14 22:36:26', 'java.lang.ArithmeticException: divide by zero\n	at com.wbtech.test_sample.CobubSampleActivity$2.onClick(CobubSampleActivity.java:154)\n	at android.view.View.performClick(View.java:4424)', 'java.lang.ArithmeticException: divide by zero\n	at com.wbtech.test_sample.CobubSampleActivity$2.onClick(CobubSampleActivity.java:154)\n	at android.view.View.performClick(View.java:4424)\n	java.lang.ArithmeticException: divide by zero\n	at com.wbtech.test_sample.CobubSampleActivity$2.onClick(CobubSampleActivity.java:154)\n	at android.view.View.performClick(View.java:4424)\n	at android.view.View$PerformClick.run(View.java:18383)\n	at android.os.Handler.handleCallback(Handler.java:733)\n	at android.os.Handler.dispatchMessage(Handler.java:95)\n	at android.os.Looper.loop(Looper.java:137)\n	at android.app.ActivityThread.main(ActivityThread.java:4998)\n	at java.lang.reflect.Method.invokeNative(Native Method)\n	at java.lang.reflect.Method.invoke(Method.java:515)\n	at com.android.internal.os.ZygoteInit$MethodAndArgsCaller.run(ZygoteInit.java:777)\n	at com.android.internal.os.ZygoteInit.main(ZygoteInit.java:593)\n	at dalvik.system.NativeStart.main(Native Method)\n', '1.0', null, '2015-12-16 14:57:35');
INSERT INTO `razor_errorlog` VALUES ('3', 'f0e1815b61767ffa9206d4dc1048b26e', 'Unknown sdk', '4.4', '.CobubSampleActivity', '2015-12-15 00:51:00', 'java.lang.ArithmeticException: divide by zero\n	at com.wbtech.test_sample.CobubSampleActivity$2.onClick(CobubSampleActivity.java:154)\n	at android.view.View.performClick(View.java:4424)', 'java.lang.ArithmeticException: divide by zero\n	at com.wbtech.test_sample.CobubSampleActivity$2.onClick(CobubSampleActivity.java:154)\n	at android.view.View.performClick(View.java:4424)\n	java.lang.ArithmeticException: divide by zero\n	at com.wbtech.test_sample.CobubSampleActivity$2.onClick(CobubSampleActivity.java:154)\n	at android.view.View.performClick(View.java:4424)\n	at android.view.View$PerformClick.run(View.java:18383)\n	at android.os.Handler.handleCallback(Handler.java:733)\n	at android.os.Handler.dispatchMessage(Handler.java:95)\n	at android.os.Looper.loop(Looper.java:137)\n	at android.app.ActivityThread.main(ActivityThread.java:4998)\n	at java.lang.reflect.Method.invokeNative(Native Method)\n	at java.lang.reflect.Method.invoke(Method.java:515)\n	at com.android.internal.os.ZygoteInit$MethodAndArgsCaller.run(ZygoteInit.java:777)\n	at com.android.internal.os.ZygoteInit.main(ZygoteInit.java:593)\n	at dalvik.system.NativeStart.main(Native Method)\n', '1.0', null, '2015-12-16 14:57:35');
INSERT INTO `razor_errorlog` VALUES ('4', 'f0e1815b61767ffa9206d4dc1048b26e', 'Unknown sdk', '4.4', '.CobubSampleActivity', '2015-12-14 22:36:26', 'java.lang.ArithmeticException: divide by zero\n	at com.wbtech.test_sample.CobubSampleActivity$2.onClick(CobubSampleActivity.java:154)\n	at android.view.View.performClick(View.java:4424)', 'java.lang.ArithmeticException: divide by zero\n	at com.wbtech.test_sample.CobubSampleActivity$2.onClick(CobubSampleActivity.java:154)\n	at android.view.View.performClick(View.java:4424)\n	java.lang.ArithmeticException: divide by zero\n	at com.wbtech.test_sample.CobubSampleActivity$2.onClick(CobubSampleActivity.java:154)\n	at android.view.View.performClick(View.java:4424)\n	at android.view.View$PerformClick.run(View.java:18383)\n	at android.os.Handler.handleCallback(Handler.java:733)\n	at android.os.Handler.dispatchMessage(Handler.java:95)\n	at android.os.Looper.loop(Looper.java:137)\n	at android.app.ActivityThread.main(ActivityThread.java:4998)\n	at java.lang.reflect.Method.invokeNative(Native Method)\n	at java.lang.reflect.Method.invoke(Method.java:515)\n	at com.android.internal.os.ZygoteInit$MethodAndArgsCaller.run(ZygoteInit.java:777)\n	at com.android.internal.os.ZygoteInit.main(ZygoteInit.java:593)\n	at dalvik.system.NativeStart.main(Native Method)\n', '1.0', null, '2015-12-16 14:59:40');
INSERT INTO `razor_errorlog` VALUES ('5', 'f0e1815b61767ffa9206d4dc1048b26e', 'Unknown sdk', '4.4', '.CobubSampleActivity', '2015-12-15 00:51:00', 'java.lang.ArithmeticException: divide by zero\n	at com.wbtech.test_sample.CobubSampleActivity$2.onClick(CobubSampleActivity.java:154)\n	at android.view.View.performClick(View.java:4424)', 'java.lang.ArithmeticException: divide by zero\n	at com.wbtech.test_sample.CobubSampleActivity$2.onClick(CobubSampleActivity.java:154)\n	at android.view.View.performClick(View.java:4424)\n	java.lang.ArithmeticException: divide by zero\n	at com.wbtech.test_sample.CobubSampleActivity$2.onClick(CobubSampleActivity.java:154)\n	at android.view.View.performClick(View.java:4424)\n	at android.view.View$PerformClick.run(View.java:18383)\n	at android.os.Handler.handleCallback(Handler.java:733)\n	at android.os.Handler.dispatchMessage(Handler.java:95)\n	at android.os.Looper.loop(Looper.java:137)\n	at android.app.ActivityThread.main(ActivityThread.java:4998)\n	at java.lang.reflect.Method.invokeNative(Native Method)\n	at java.lang.reflect.Method.invoke(Method.java:515)\n	at com.android.internal.os.ZygoteInit$MethodAndArgsCaller.run(ZygoteInit.java:777)\n	at com.android.internal.os.ZygoteInit.main(ZygoteInit.java:593)\n	at dalvik.system.NativeStart.main(Native Method)\n', '1.0', null, '2015-12-16 14:59:40');
INSERT INTO `razor_errorlog` VALUES ('6', 'f0e1815b61767ffa9206d4dc1048b26e', 'Unknown sdk', '4.4', '.CobubSampleActivity', '2015-12-16 01:58:30', 'java.lang.ArithmeticException: divide by zero\n	at com.wbtech.test_sample.CobubSampleActivity$2.onClick(CobubSampleActivity.java:154)\n	at android.view.View.performClick(View.java:4424)', 'java.lang.ArithmeticException: divide by zero\n	at com.wbtech.test_sample.CobubSampleActivity$2.onClick(CobubSampleActivity.java:154)\n	at android.view.View.performClick(View.java:4424)\n	java.lang.ArithmeticException: divide by zero\n	at com.wbtech.test_sample.CobubSampleActivity$2.onClick(CobubSampleActivity.java:154)\n	at android.view.View.performClick(View.java:4424)\n	at android.view.View$PerformClick.run(View.java:18383)\n	at android.os.Handler.handleCallback(Handler.java:733)\n	at android.os.Handler.dispatchMessage(Handler.java:95)\n	at android.os.Looper.loop(Looper.java:137)\n	at android.app.ActivityThread.main(ActivityThread.java:4998)\n	at java.lang.reflect.Method.invokeNative(Native Method)\n	at java.lang.reflect.Method.invoke(Method.java:515)\n	at com.android.internal.os.ZygoteInit$MethodAndArgsCaller.run(ZygoteInit.java:777)\n	at com.android.internal.os.ZygoteInit.main(ZygoteInit.java:593)\n	at dalvik.system.NativeStart.main(Native Method)\n', '1.0', null, '2015-12-16 14:59:40');

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_eventdata
-- ----------------------------
INSERT INTO `razor_eventdata` VALUES ('1', null, null, '.CobubSampleActivity', '', null, '2015-12-14 22:36:20', 'f0e1815b61767ffa9206d4dc1048b26e', '1', '2', '1.0', '2015-12-15 11:35:36');
INSERT INTO `razor_eventdata` VALUES ('2', null, null, '.CobubSampleActivity', '', null, '2015-12-14 22:31:14', 'f0e1815b61767ffa9206d4dc1048b26e', '1', '1', '1.0', '2015-12-15 11:35:36');
INSERT INTO `razor_eventdata` VALUES ('3', null, null, '.CobubSampleActivity', '', null, '2015-12-14 22:36:22', 'f0e1815b61767ffa9206d4dc1048b26e', '1', '3', '1.0', '2015-12-15 11:35:38');
INSERT INTO `razor_eventdata` VALUES ('4', null, null, '.CobubSampleActivity', '', null, '2015-12-15 00:50:59', 'f0e1815b61767ffa9206d4dc1048b26e', '1', '1', '1.0', '2015-12-15 13:50:15');
INSERT INTO `razor_eventdata` VALUES ('5', null, null, '.CobubSampleActivity', '', null, '2015-12-16 02:00:33', 'f0e1815b61767ffa9206d4dc1048b26e', '1', '1', '1.0', '2015-12-16 14:59:48');

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
-- Records of razor_event_defination
-- ----------------------------
INSERT INTO `razor_event_defination` VALUES ('1', 'login', 'f0e1815b61767ffa9206d4dc1048b26e', 'login', '1', '1', '1', '2015-12-15 10:20:22', '1');
INSERT INTO `razor_event_defination` VALUES ('2', 'click', 'f0e1815b61767ffa9206d4dc1048b26e', 'click', '1', '1', '1', '2015-12-15 10:20:57', '1');
INSERT INTO `razor_event_defination` VALUES ('3', 'quit', 'f0e1815b61767ffa9206d4dc1048b26e', 'quit', '1', '1', '1', '2015-12-15 10:21:13', '1');

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
-- Records of razor_getui_product
-- ----------------------------

-- ----------------------------
-- Table structure for razor_install
-- ----------------------------
DROP TABLE IF EXISTS `razor_install`;
CREATE TABLE `razor_install` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `install_date` date NOT NULL,
  `chId` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL,
  `userId` varchar(255) NOT NULL,
  `productkey` varchar(255) NOT NULL,
  `deviceid` varchar(255) NOT NULL,
  `install_time` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `idx_date` (`install_date`) USING BTREE,
  KEY `idx_chId` (`chId`) USING BTREE,
  KEY `idx_userid` (`userId`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_install
-- ----------------------------
INSERT INTO `razor_install` VALUES ('1', '2015-12-15', '1', '1.0', 'qihoo_1431218909', 'f0e1815b61767ffa9206d4dc1048b26e', '9401a87f322c05f9c93272bc7ed69d10', '2015-12-15 21:55:17');
INSERT INTO `razor_install` VALUES ('2', '2015-12-15', '1', '1.0', 'qihoo_509325425', 'f0e1815b61767ffa9206d4dc1048b26e', '9401a87f322c05f9c93272bc7ed69d10', '2015-12-15 22:55:26');

-- ----------------------------
-- Table structure for razor_login
-- ----------------------------
DROP TABLE IF EXISTS `razor_login`;
CREATE TABLE `razor_login` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `login_date` date NOT NULL,
  `chId` varchar(255) NOT NULL,
  `subSrvId` varchar(255) NOT NULL,
  `srvId` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL,
  `userId` varchar(255) NOT NULL,
  `productkey` varchar(255) NOT NULL,
  `login_time` datetime NOT NULL,
  `deviceModel` varchar(255) NOT NULL,
  `deviceName` varchar(255) NOT NULL,
  `deviceOS` varchar(255) NOT NULL,
  `deviceid` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `invitedUserId` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `idx_logindate` (`login_date`) USING BTREE,
  KEY `idx_srvid` (`srvId`) USING BTREE,
  KEY `idx_userid` (`userId`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_login
-- ----------------------------
INSERT INTO `razor_login` VALUES ('1', '2015-12-15', '1', '', '1', '1.0', 'qihoo_1431218909', 'f0e1815b61767ffa9206d4dc1048b26e', '2015-12-15 23:56:17', 'Xiaomi MI 3', '<unknown>', 'Android OS 4.4.4 / API-19 (KTU84P/V6.2.1.0.KXCCNBK)', '9401a87f322c05f9c93272bc7ed69d10', '8.8.8.8', '');
INSERT INTO `razor_login` VALUES ('2', '2015-12-15', '1', '', '1', '1.0', 'qihoo_509325425', 'f0e1815b61767ffa9206d4dc1048b26e', '2015-12-15 23:56:26', 'Xiaomi MI 3', '<unknown>', 'Android OS 4.4.4 / API-19 (KTU84P/V6.2.1.0.KXCCNBK)', '9401a87f322c05f9c93272bc7ed69d10', '8.8.8.8', '');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_login_attempts
-- ----------------------------

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
-- Records of razor_markevent
-- ----------------------------

-- ----------------------------
-- Table structure for razor_networktype
-- ----------------------------
DROP TABLE IF EXISTS `razor_networktype`;
CREATE TABLE `razor_networktype` (
  `id` int(8) NOT NULL,
  `type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_networktype
-- ----------------------------
INSERT INTO `razor_networktype` VALUES ('1', 'WIFI');
INSERT INTO `razor_networktype` VALUES ('2', '2G/3G');
INSERT INTO `razor_networktype` VALUES ('3', '1xRTT');
INSERT INTO `razor_networktype` VALUES ('4', 'CDMA');
INSERT INTO `razor_networktype` VALUES ('5', 'EDGE');
INSERT INTO `razor_networktype` VALUES ('6', 'EVDO_0');
INSERT INTO `razor_networktype` VALUES ('7', 'EVDO_A');
INSERT INTO `razor_networktype` VALUES ('8', 'GPRS');
INSERT INTO `razor_networktype` VALUES ('9', 'HSDPA');
INSERT INTO `razor_networktype` VALUES ('10', 'HSPA');
INSERT INTO `razor_networktype` VALUES ('11', 'HSUPA');
INSERT INTO `razor_networktype` VALUES ('12', 'UMTS');
INSERT INTO `razor_networktype` VALUES ('13', 'EHRPD');
INSERT INTO `razor_networktype` VALUES ('14', 'EVDO_B');
INSERT INTO `razor_networktype` VALUES ('15', 'HSPAP');
INSERT INTO `razor_networktype` VALUES ('16', 'IDEN');
INSERT INTO `razor_networktype` VALUES ('17', 'LTE');
INSERT INTO `razor_networktype` VALUES ('18', 'UNKNOWN');

-- ----------------------------
-- Table structure for razor_pay
-- ----------------------------
DROP TABLE IF EXISTS `razor_pay`;
CREATE TABLE `razor_pay` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `pay_date` date NOT NULL,
  `chId` varchar(255) NOT NULL,
  `subSrvId` varchar(255) NOT NULL,
  `srvId` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL,
  `userId` varchar(255) NOT NULL,
  `productkey` varchar(255) NOT NULL,
  `deviceid` varchar(255) NOT NULL,
  `pay_time` datetime NOT NULL,
  `pay` varchar(255) NOT NULL,
  `roleName` varchar(255) NOT NULL,
  `roleLevel` int(16) NOT NULL DEFAULT '0',
  `pay_type` varchar(255) NOT NULL,
  `item` varchar(255) NOT NULL,
  `amount` int(16) NOT NULL,
  `orderId` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `idx_pay_date` (`pay_date`) USING BTREE,
  KEY `idx_pay_srvid` (`srvId`) USING BTREE,
  KEY `idx_pay_rolename` (`roleName`) USING BTREE,
  KEY `idx_pay_chid` (`chId`) USING BTREE,
  KEY `idx_pay_userid` (`userId`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_pay
-- ----------------------------
INSERT INTO `razor_pay` VALUES ('1', '2015-12-15', '1', '', '1', '1.0', 'qihoo_1431218909', 'f0e1815b61767ffa9206d4dc1048b26e', '9401a87f322c05f9c93272bc7ed69d10', '2015-12-15 23:57:17', 'pay', '酷仔', '10', 'recharge', 'com.xxx.mi', '100', '3c06b3239b8b316c_01011_2015031523_000000\n');
INSERT INTO `razor_pay` VALUES ('2', '2015-12-15', '1', '', '1', '1.0', 'qihoo_509325425', 'f0e1815b61767ffa9206d4dc1048b26e', '9401a87f322c05f9c93272bc7ed69d10', '2015-12-15 23:57:26', 'pay', '火瀑弥尔顿', '20', 'recharge', 'com.xxx.mi', '200', '3c06b3239b8b316c_01011_2015031523_000001');

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
-- Records of razor_platform
-- ----------------------------
INSERT INTO `razor_platform` VALUES ('1', 'Android');
INSERT INTO `razor_platform` VALUES ('2', 'iOS');
INSERT INTO `razor_platform` VALUES ('3', 'Windows Phone');

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
-- Records of razor_plugins
-- ----------------------------

-- ----------------------------
-- Table structure for razor_product
-- ----------------------------
DROP TABLE IF EXISTS `razor_product`;
CREATE TABLE `razor_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(5000) NOT NULL,
  `date` datetime NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '1',
  `channel_count` int(11) NOT NULL DEFAULT '0',
  `product_key` varchar(50) NOT NULL,
  `product_platform` int(50) NOT NULL DEFAULT '1',
  `category` int(50) NOT NULL DEFAULT '1',
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_product
-- ----------------------------
INSERT INTO `razor_product` VALUES ('1', 'Sample_android', '', '2015-12-14 18:51:50', '1', '4', 'f0e1815b61767ffa9206d4dc1048b26e', '1', '33', '1');
INSERT INTO `razor_product` VALUES ('2', 'Sample_wp', '', '2015-12-14 18:52:12', '1', '1', 'fc3a385376da10c1757ae5a2874b941e', '3', '33', '1');
INSERT INTO `razor_product` VALUES ('3', 'Sample_ios', '', '2015-12-14 18:52:27', '1', '1', 'e023aff4d19fb4c09a9437f246adac95', '2', '33', '1');

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
-- Records of razor_productfiles
-- ----------------------------

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
-- Records of razor_product_category
-- ----------------------------
INSERT INTO `razor_product_category` VALUES ('1', '报刊杂志', '1', '0', '1');
INSERT INTO `razor_product_category` VALUES ('2', '社交', '1', '0', '1');
INSERT INTO `razor_product_category` VALUES ('3', '商业', '1', '0', '1');
INSERT INTO `razor_product_category` VALUES ('4', '财务', '1', '0', '1');
INSERT INTO `razor_product_category` VALUES ('5', '参考', '1', '0', '1');
INSERT INTO `razor_product_category` VALUES ('6', '导航', '1', '0', '1');
INSERT INTO `razor_product_category` VALUES ('7', '工具', '1', '0', '1');
INSERT INTO `razor_product_category` VALUES ('8', '健康健美', '1', '0', '1');
INSERT INTO `razor_product_category` VALUES ('9', '教育', '1', '0', '1');
INSERT INTO `razor_product_category` VALUES ('10', '旅行', '1', '0', '1');
INSERT INTO `razor_product_category` VALUES ('11', '摄影与录像', '1', '0', '1');
INSERT INTO `razor_product_category` VALUES ('12', '生活', '1', '0', '1');
INSERT INTO `razor_product_category` VALUES ('13', '体育', '1', '0', '1');
INSERT INTO `razor_product_category` VALUES ('14', '天气', '1', '0', '1');
INSERT INTO `razor_product_category` VALUES ('15', '图书', '1', '0', '1');
INSERT INTO `razor_product_category` VALUES ('16', '效率', '1', '0', '1');
INSERT INTO `razor_product_category` VALUES ('17', '新闻', '1', '0', '1');
INSERT INTO `razor_product_category` VALUES ('18', '音乐', '1', '0', '1');
INSERT INTO `razor_product_category` VALUES ('19', '医疗', '1', '0', '1');
INSERT INTO `razor_product_category` VALUES ('32', '娱乐', '1', '0', '1');
INSERT INTO `razor_product_category` VALUES ('33', '游戏', '1', '0', '1');

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
-- Records of razor_product_version
-- ----------------------------

-- ----------------------------
-- Table structure for razor_register
-- ----------------------------
DROP TABLE IF EXISTS `razor_register`;
CREATE TABLE `razor_register` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `register_date` date NOT NULL,
  `chId` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL,
  `userId` varchar(255) NOT NULL,
  `productkey` varchar(255) NOT NULL,
  `deviceid` varchar(255) NOT NULL,
  `register_time` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `idx_date` (`register_date`) USING BTREE,
  KEY `idx_chId` (`chId`) USING BTREE,
  KEY `idx_userid` (`userId`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_register
-- ----------------------------
INSERT INTO `razor_register` VALUES ('1', '2015-12-15', '1', '1.0', 'qihoo_1431218909', 'f0e1815b61767ffa9206d4dc1048b26e', '9401a87f322c05f9c93272bc7ed69d10', '2015-12-15 22:55:17');
INSERT INTO `razor_register` VALUES ('2', '2015-12-15', '1', '1.0', 'qihoo_509325425', 'f0e1815b61767ffa9206d4dc1048b26e', '9401a87f322c05f9c93272bc7ed69d10', '2015-12-15 21:55:26');

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
-- Records of razor_reportlayout
-- ----------------------------

-- ----------------------------
-- Table structure for razor_server
-- ----------------------------
DROP TABLE IF EXISTS `razor_server`;
CREATE TABLE `razor_server` (
  `server_id` int(11) NOT NULL AUTO_INCREMENT,
  `server_name` varchar(255) NOT NULL DEFAULT '',
  `create_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_id` int(11) NOT NULL DEFAULT '1',
  `active` int(10) NOT NULL DEFAULT '1',
  PRIMARY KEY (`server_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_server
-- ----------------------------
INSERT INTO `razor_server` VALUES ('1', '区服一', '0000-00-00 00:00:00', '1', '1');
INSERT INTO `razor_server` VALUES ('2', '区服二', '0000-00-00 00:00:00', '1', '1');
INSERT INTO `razor_server` VALUES ('3', '区服三', '0000-00-00 00:00:00', '1', '0');
INSERT INTO `razor_server` VALUES ('4', '区服四', '2015-12-17 16:18:31', '1', '0');
INSERT INTO `razor_server` VALUES ('5', '区服三', '2015-12-17 16:21:20', '1', '1');
INSERT INTO `razor_server` VALUES ('6', '区服四', '2015-12-17 16:21:28', '1', '1');

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
-- Records of razor_tag_group
-- ----------------------------

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
-- Records of razor_target
-- ----------------------------
INSERT INTO `razor_target` VALUES ('1', '1', '1', 'lq', null, '1.00', '0', '2015-12-15 18:59:02');

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
-- Records of razor_targetevent
-- ----------------------------
INSERT INTO `razor_targetevent` VALUES ('1', '1', '1', 'login', '1');
INSERT INTO `razor_targetevent` VALUES ('2', '1', '3', 'quit', '2');

-- ----------------------------
-- Table structure for razor_user2product
-- ----------------------------
DROP TABLE IF EXISTS `razor_user2product`;
CREATE TABLE `razor_user2product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_user2product
-- ----------------------------
INSERT INTO `razor_user2product` VALUES ('1', '2', '1');

-- ----------------------------
-- Table structure for razor_user2role
-- ----------------------------
DROP TABLE IF EXISTS `razor_user2role`;
CREATE TABLE `razor_user2role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `roleid` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_user2role
-- ----------------------------
INSERT INTO `razor_user2role` VALUES ('1', '1', '3');
INSERT INTO `razor_user2role` VALUES ('2', '2', '1');

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
-- Records of razor_userkeys
-- ----------------------------
INSERT INTO `razor_userkeys` VALUES ('1', '1', '7417b126dd714e2ea66c39397deefd6f', '0eff814d5fccbcf45bbc7fb7133fc1a7');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_users
-- ----------------------------
INSERT INTO `razor_users` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '924211739@qq.com', '1', '0', null, null, null, null, null, '0.0.0.0', '2015-12-23 10:22:39', '2015-12-14 18:47:06', '2015-12-23 10:22:39', null);
INSERT INTO `razor_users` VALUES ('2', 'wangguoqing', '59e2e4aee45dded592c9eb04e2249ddc', 'wangguoqing@longtugame.com', '1', '0', null, null, null, null, null, '0.0.0.0', '2015-12-18 14:58:32', '2015-12-18 14:58:16', '2015-12-18 14:58:32', null);

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
-- Records of razor_user_autologin
-- ----------------------------

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
-- Records of razor_user_permissions
-- ----------------------------
INSERT INTO `razor_user_permissions` VALUES ('1', '1', '1', '1', '1', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('2', '2', '1', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('3', '3', '1', '1', '1', '1', '1', '1', null);
INSERT INTO `razor_user_permissions` VALUES ('4', '1', '2', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('5', '2', '2', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('6', '3', '2', '1', '1', '1', '1', '1', null);
INSERT INTO `razor_user_permissions` VALUES ('7', '1', '3', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('8', '2', '3', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('9', '3', '3', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('10', '1', '4', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('11', '2', '4', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('12', '3', '4', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('13', '1', '5', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('14', '2', '5', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('15', '3', '5', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('16', '1', '6', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('17', '2', '6', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('18', '3', '6', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('19', '1', '7', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('20', '2', '7', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('21', '3', '7', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('22', '1', '8', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('23', '2', '8', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('24', '3', '8', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('25', '1', '9', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('26', '2', '9', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('27', '3', '9', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('28', '1', '10', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('29', '2', '10', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('30', '3', '10', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('31', '1', '11', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('32', '2', '11', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('33', '3', '11', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('34', '1', '12', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('35', '2', '12', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('36', '3', '12', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('37', '1', '13', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('38', '2', '13', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('39', '3', '13', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('40', '1', '14', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('41', '2', '14', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('42', '3', '14', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('43', '1', '15', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('44', '2', '15', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('45', '3', '15', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('46', '1', '16', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('47', '2', '16', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('48', '3', '16', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('49', '1', '17', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('50', '2', '17', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('51', '3', '17', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('52', '1', '18', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('53', '2', '18', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('54', '3', '18', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('55', '1', '19', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('56', '2', '19', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('57', '3', '19', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('58', '1', '20', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('59', '2', '20', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('60', '3', '20', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('61', '1', '21', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('62', '2', '21', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('63', '3', '21', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('64', '1', '22', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('65', '2', '22', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('66', '3', '22', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('67', '1', '23', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('68', '2', '23', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('69', '3', '23', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('70', '1', '24', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('71', '2', '24', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('72', '3', '24', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('73', '1', '25', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('74', '2', '25', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('75', '3', '25', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('76', '1', '26', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('77', '2', '26', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('78', '3', '26', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('79', '1', '27', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('80', '2', '27', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('81', '3', '27', '1', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('82', '1', '28', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('83', '2', '28', '0', '0', '0', '0', '0', null);
INSERT INTO `razor_user_permissions` VALUES ('84', '3', '28', '1', '0', '0', '0', '0', null);

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_user_profiles
-- ----------------------------
INSERT INTO `razor_user_profiles` VALUES ('1', '1', null, null, null, null, null, null, null, null);
INSERT INTO `razor_user_profiles` VALUES ('2', '2', null, null, null, null, null, null, null, null);

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
-- Records of razor_user_resources
-- ----------------------------
INSERT INTO `razor_user_resources` VALUES ('1', 'test', 'Acl Test Controller', null);
INSERT INTO `razor_user_resources` VALUES ('2', 'User', '用户管理', null);
INSERT INTO `razor_user_resources` VALUES ('3', 'Product', '我的应用', null);
INSERT INTO `razor_user_resources` VALUES ('4', 'errorlogondevice', '错误设备统计', null);
INSERT INTO `razor_user_resources` VALUES ('5', 'productbasic', '基本统计', null);
INSERT INTO `razor_user_resources` VALUES ('6', 'Auth', '用户', null);
INSERT INTO `razor_user_resources` VALUES ('7', 'Autoupdate', '自动更新', null);
INSERT INTO `razor_user_resources` VALUES ('8', 'Channel', '渠道', null);
INSERT INTO `razor_user_resources` VALUES ('9', 'Device', '设备', null);
INSERT INTO `razor_user_resources` VALUES ('10', 'Event', '事件管理', null);
INSERT INTO `razor_user_resources` VALUES ('11', 'Onlineconfig', '发送策略', null);
INSERT INTO `razor_user_resources` VALUES ('12', 'Operator', '运营商', null);
INSERT INTO `razor_user_resources` VALUES ('13', 'Os', '操作系统统计', null);
INSERT INTO `razor_user_resources` VALUES ('14', 'Profile', '个人资料', null);
INSERT INTO `razor_user_resources` VALUES ('15', 'Resolution', '分辨率统计', null);
INSERT INTO `razor_user_resources` VALUES ('16', 'Usefrequency', '使用频率统计', null);
INSERT INTO `razor_user_resources` VALUES ('17', 'Usetime', '使用时长统计', null);
INSERT INTO `razor_user_resources` VALUES ('18', 'errorlog', '错误日志', null);
INSERT INTO `razor_user_resources` VALUES ('19', 'Eventlist', '事件', null);
INSERT INTO `razor_user_resources` VALUES ('20', 'market', '渠道STATISTICS', null);
INSERT INTO `razor_user_resources` VALUES ('21', 'region', '地域统计', null);
INSERT INTO `razor_user_resources` VALUES ('22', 'errorlogonos', '错误操作系统统计', null);
INSERT INTO `razor_user_resources` VALUES ('23', 'version', '版本统计', null);
INSERT INTO `razor_user_resources` VALUES ('24', 'console', '应用', null);
INSERT INTO `razor_user_resources` VALUES ('25', 'Userremain', '用户留存', null);
INSERT INTO `razor_user_resources` VALUES ('26', 'Pagevisit', '页面访问统计', null);
INSERT INTO `razor_user_resources` VALUES ('27', 'Network', '联网方式统计', null);
INSERT INTO `razor_user_resources` VALUES ('28', 'funnels', '漏斗模型', null);

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
-- Records of razor_user_roles
-- ----------------------------
INSERT INTO `razor_user_roles` VALUES ('1', 'user', 'normal user', null);
INSERT INTO `razor_user_roles` VALUES ('2', 'guest', 'not log in', null);
INSERT INTO `razor_user_roles` VALUES ('3', 'admin', 'system admin', null);

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
-- Records of razor_wifi_towers
-- ----------------------------
