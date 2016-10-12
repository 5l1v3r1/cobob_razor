/*
Navicat MySQL Data Transfer

Source Server         : localhost-test
Source Server Version : 50611
Source Host           : localhost:3306
Source Database       : razor_dw

Target Server Type    : MYSQL
Target Server Version : 50611
File Encoding         : 65001

Date: 2015-12-23 11:08:14
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
-- Records of razor_deviceid_pushid
-- ----------------------------

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
-- Records of razor_deviceid_userid
-- ----------------------------

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_dim_activity
-- ----------------------------
INSERT INTO `razor_dim_activity` VALUES ('1', '.CobubSampleActivity', '1');

-- ----------------------------
-- Table structure for razor_dim_date
-- ----------------------------
DROP TABLE IF EXISTS `razor_dim_date`;
CREATE TABLE `razor_dim_date` (
  `date_sk` int(11) NOT NULL AUTO_INCREMENT,
  `datevalue` date NOT NULL,
  `year` int(11) NOT NULL,
  `quarter` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `week` int(11) NOT NULL,
  `dayofweek` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  PRIMARY KEY (`date_sk`),
  KEY `year` (`year`,`month`,`day`),
  KEY `year_2` (`year`,`week`),
  KEY `datevalue` (`datevalue`)
) ENGINE=InnoDB AUTO_INCREMENT=4019 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_dim_date
-- ----------------------------
INSERT INTO `razor_dim_date` VALUES ('1828', '2015-01-01', '2015', '1', '1', '0', '4', '2');
INSERT INTO `razor_dim_date` VALUES ('1829', '2015-01-02', '2015', '1', '1', '0', '5', '3');
INSERT INTO `razor_dim_date` VALUES ('1830', '2015-01-03', '2015', '1', '1', '1', '6', '4');
INSERT INTO `razor_dim_date` VALUES ('1831', '2015-01-04', '2015', '1', '1', '1', '0', '5');
INSERT INTO `razor_dim_date` VALUES ('1832', '2015-01-05', '2015', '1', '1', '1', '1', '6');
INSERT INTO `razor_dim_date` VALUES ('1833', '2015-01-06', '2015', '1', '1', '1', '2', '7');
INSERT INTO `razor_dim_date` VALUES ('1834', '2015-01-07', '2015', '1', '1', '1', '3', '8');
INSERT INTO `razor_dim_date` VALUES ('1835', '2015-01-08', '2015', '1', '1', '1', '4', '9');
INSERT INTO `razor_dim_date` VALUES ('1836', '2015-01-09', '2015', '1', '1', '1', '5', '10');
INSERT INTO `razor_dim_date` VALUES ('1837', '2015-01-10', '2015', '1', '1', '2', '6', '11');
INSERT INTO `razor_dim_date` VALUES ('1838', '2015-01-11', '2015', '1', '1', '2', '0', '12');
INSERT INTO `razor_dim_date` VALUES ('1839', '2015-01-12', '2015', '1', '1', '2', '1', '13');
INSERT INTO `razor_dim_date` VALUES ('1840', '2015-01-13', '2015', '1', '1', '2', '2', '14');
INSERT INTO `razor_dim_date` VALUES ('1841', '2015-01-14', '2015', '1', '1', '2', '3', '15');
INSERT INTO `razor_dim_date` VALUES ('1842', '2015-01-15', '2015', '1', '1', '2', '4', '16');
INSERT INTO `razor_dim_date` VALUES ('1843', '2015-01-16', '2015', '1', '1', '2', '5', '17');
INSERT INTO `razor_dim_date` VALUES ('1844', '2015-01-17', '2015', '1', '1', '3', '6', '18');
INSERT INTO `razor_dim_date` VALUES ('1845', '2015-01-18', '2015', '1', '1', '3', '0', '19');
INSERT INTO `razor_dim_date` VALUES ('1846', '2015-01-19', '2015', '1', '1', '3', '1', '20');
INSERT INTO `razor_dim_date` VALUES ('1847', '2015-01-20', '2015', '1', '1', '3', '2', '21');
INSERT INTO `razor_dim_date` VALUES ('1848', '2015-01-21', '2015', '1', '1', '3', '3', '22');
INSERT INTO `razor_dim_date` VALUES ('1849', '2015-01-22', '2015', '1', '1', '3', '4', '23');
INSERT INTO `razor_dim_date` VALUES ('1850', '2015-01-23', '2015', '1', '1', '3', '5', '24');
INSERT INTO `razor_dim_date` VALUES ('1851', '2015-01-24', '2015', '1', '1', '4', '6', '25');
INSERT INTO `razor_dim_date` VALUES ('1852', '2015-01-25', '2015', '1', '1', '4', '0', '26');
INSERT INTO `razor_dim_date` VALUES ('1853', '2015-01-26', '2015', '1', '1', '4', '1', '27');
INSERT INTO `razor_dim_date` VALUES ('1854', '2015-01-27', '2015', '1', '1', '4', '2', '28');
INSERT INTO `razor_dim_date` VALUES ('1855', '2015-01-28', '2015', '1', '1', '4', '3', '29');
INSERT INTO `razor_dim_date` VALUES ('1856', '2015-01-29', '2015', '1', '1', '4', '4', '30');
INSERT INTO `razor_dim_date` VALUES ('1857', '2015-01-30', '2015', '1', '1', '4', '5', '31');
INSERT INTO `razor_dim_date` VALUES ('1858', '2015-01-31', '2015', '1', '2', '5', '6', '1');
INSERT INTO `razor_dim_date` VALUES ('1859', '2015-02-01', '2015', '1', '2', '5', '0', '2');
INSERT INTO `razor_dim_date` VALUES ('1860', '2015-02-02', '2015', '1', '2', '5', '1', '3');
INSERT INTO `razor_dim_date` VALUES ('1861', '2015-02-03', '2015', '1', '2', '5', '2', '4');
INSERT INTO `razor_dim_date` VALUES ('1862', '2015-02-04', '2015', '1', '2', '5', '3', '5');
INSERT INTO `razor_dim_date` VALUES ('1863', '2015-02-05', '2015', '1', '2', '5', '4', '6');
INSERT INTO `razor_dim_date` VALUES ('1864', '2015-02-06', '2015', '1', '2', '5', '5', '7');
INSERT INTO `razor_dim_date` VALUES ('1865', '2015-02-07', '2015', '1', '2', '6', '6', '8');
INSERT INTO `razor_dim_date` VALUES ('1866', '2015-02-08', '2015', '1', '2', '6', '0', '9');
INSERT INTO `razor_dim_date` VALUES ('1867', '2015-02-09', '2015', '1', '2', '6', '1', '10');
INSERT INTO `razor_dim_date` VALUES ('1868', '2015-02-10', '2015', '1', '2', '6', '2', '11');
INSERT INTO `razor_dim_date` VALUES ('1869', '2015-02-11', '2015', '1', '2', '6', '3', '12');
INSERT INTO `razor_dim_date` VALUES ('1870', '2015-02-12', '2015', '1', '2', '6', '4', '13');
INSERT INTO `razor_dim_date` VALUES ('1871', '2015-02-13', '2015', '1', '2', '6', '5', '14');
INSERT INTO `razor_dim_date` VALUES ('1872', '2015-02-14', '2015', '1', '2', '7', '6', '15');
INSERT INTO `razor_dim_date` VALUES ('1873', '2015-02-15', '2015', '1', '2', '7', '0', '16');
INSERT INTO `razor_dim_date` VALUES ('1874', '2015-02-16', '2015', '1', '2', '7', '1', '17');
INSERT INTO `razor_dim_date` VALUES ('1875', '2015-02-17', '2015', '1', '2', '7', '2', '18');
INSERT INTO `razor_dim_date` VALUES ('1876', '2015-02-18', '2015', '1', '2', '7', '3', '19');
INSERT INTO `razor_dim_date` VALUES ('1877', '2015-02-19', '2015', '1', '2', '7', '4', '20');
INSERT INTO `razor_dim_date` VALUES ('1878', '2015-02-20', '2015', '1', '2', '7', '5', '21');
INSERT INTO `razor_dim_date` VALUES ('1879', '2015-02-21', '2015', '1', '2', '8', '6', '22');
INSERT INTO `razor_dim_date` VALUES ('1880', '2015-02-22', '2015', '1', '2', '8', '0', '23');
INSERT INTO `razor_dim_date` VALUES ('1881', '2015-02-23', '2015', '1', '2', '8', '1', '24');
INSERT INTO `razor_dim_date` VALUES ('1882', '2015-02-24', '2015', '1', '2', '8', '2', '25');
INSERT INTO `razor_dim_date` VALUES ('1883', '2015-02-25', '2015', '1', '2', '8', '3', '26');
INSERT INTO `razor_dim_date` VALUES ('1884', '2015-02-26', '2015', '1', '2', '8', '4', '27');
INSERT INTO `razor_dim_date` VALUES ('1885', '2015-02-27', '2015', '1', '2', '8', '5', '28');
INSERT INTO `razor_dim_date` VALUES ('1886', '2015-02-28', '2015', '1', '3', '9', '6', '1');
INSERT INTO `razor_dim_date` VALUES ('1887', '2015-03-01', '2015', '1', '3', '9', '0', '2');
INSERT INTO `razor_dim_date` VALUES ('1888', '2015-03-02', '2015', '1', '3', '9', '1', '3');
INSERT INTO `razor_dim_date` VALUES ('1889', '2015-03-03', '2015', '1', '3', '9', '2', '4');
INSERT INTO `razor_dim_date` VALUES ('1890', '2015-03-04', '2015', '1', '3', '9', '3', '5');
INSERT INTO `razor_dim_date` VALUES ('1891', '2015-03-05', '2015', '1', '3', '9', '4', '6');
INSERT INTO `razor_dim_date` VALUES ('1892', '2015-03-06', '2015', '1', '3', '9', '5', '7');
INSERT INTO `razor_dim_date` VALUES ('1893', '2015-03-07', '2015', '1', '3', '10', '6', '8');
INSERT INTO `razor_dim_date` VALUES ('1894', '2015-03-08', '2015', '1', '3', '10', '0', '9');
INSERT INTO `razor_dim_date` VALUES ('1895', '2015-03-09', '2015', '1', '3', '10', '1', '10');
INSERT INTO `razor_dim_date` VALUES ('1896', '2015-03-10', '2015', '1', '3', '10', '2', '11');
INSERT INTO `razor_dim_date` VALUES ('1897', '2015-03-11', '2015', '1', '3', '10', '3', '12');
INSERT INTO `razor_dim_date` VALUES ('1898', '2015-03-12', '2015', '1', '3', '10', '4', '13');
INSERT INTO `razor_dim_date` VALUES ('1899', '2015-03-13', '2015', '1', '3', '10', '5', '14');
INSERT INTO `razor_dim_date` VALUES ('1900', '2015-03-14', '2015', '1', '3', '11', '6', '15');
INSERT INTO `razor_dim_date` VALUES ('1901', '2015-03-15', '2015', '1', '3', '11', '0', '16');
INSERT INTO `razor_dim_date` VALUES ('1902', '2015-03-16', '2015', '1', '3', '11', '1', '17');
INSERT INTO `razor_dim_date` VALUES ('1903', '2015-03-17', '2015', '1', '3', '11', '2', '18');
INSERT INTO `razor_dim_date` VALUES ('1904', '2015-03-18', '2015', '1', '3', '11', '3', '19');
INSERT INTO `razor_dim_date` VALUES ('1905', '2015-03-19', '2015', '1', '3', '11', '4', '20');
INSERT INTO `razor_dim_date` VALUES ('1906', '2015-03-20', '2015', '1', '3', '11', '5', '21');
INSERT INTO `razor_dim_date` VALUES ('1907', '2015-03-21', '2015', '1', '3', '12', '6', '22');
INSERT INTO `razor_dim_date` VALUES ('1908', '2015-03-22', '2015', '1', '3', '12', '0', '23');
INSERT INTO `razor_dim_date` VALUES ('1909', '2015-03-23', '2015', '1', '3', '12', '1', '24');
INSERT INTO `razor_dim_date` VALUES ('1910', '2015-03-24', '2015', '1', '3', '12', '2', '25');
INSERT INTO `razor_dim_date` VALUES ('1911', '2015-03-25', '2015', '1', '3', '12', '3', '26');
INSERT INTO `razor_dim_date` VALUES ('1912', '2015-03-26', '2015', '1', '3', '12', '4', '27');
INSERT INTO `razor_dim_date` VALUES ('1913', '2015-03-27', '2015', '1', '3', '12', '5', '28');
INSERT INTO `razor_dim_date` VALUES ('1914', '2015-03-28', '2015', '1', '3', '13', '6', '29');
INSERT INTO `razor_dim_date` VALUES ('1915', '2015-03-29', '2015', '1', '3', '13', '0', '30');
INSERT INTO `razor_dim_date` VALUES ('1916', '2015-03-30', '2015', '1', '3', '13', '1', '31');
INSERT INTO `razor_dim_date` VALUES ('1917', '2015-03-31', '2015', '2', '4', '13', '2', '1');
INSERT INTO `razor_dim_date` VALUES ('1918', '2015-04-01', '2015', '2', '4', '13', '3', '2');
INSERT INTO `razor_dim_date` VALUES ('1919', '2015-04-02', '2015', '2', '4', '13', '4', '3');
INSERT INTO `razor_dim_date` VALUES ('1920', '2015-04-03', '2015', '2', '4', '13', '5', '4');
INSERT INTO `razor_dim_date` VALUES ('1921', '2015-04-04', '2015', '2', '4', '14', '6', '5');
INSERT INTO `razor_dim_date` VALUES ('1922', '2015-04-05', '2015', '2', '4', '14', '0', '6');
INSERT INTO `razor_dim_date` VALUES ('1923', '2015-04-06', '2015', '2', '4', '14', '1', '7');
INSERT INTO `razor_dim_date` VALUES ('1924', '2015-04-07', '2015', '2', '4', '14', '2', '8');
INSERT INTO `razor_dim_date` VALUES ('1925', '2015-04-08', '2015', '2', '4', '14', '3', '9');
INSERT INTO `razor_dim_date` VALUES ('1926', '2015-04-09', '2015', '2', '4', '14', '4', '10');
INSERT INTO `razor_dim_date` VALUES ('1927', '2015-04-10', '2015', '2', '4', '14', '5', '11');
INSERT INTO `razor_dim_date` VALUES ('1928', '2015-04-11', '2015', '2', '4', '15', '6', '12');
INSERT INTO `razor_dim_date` VALUES ('1929', '2015-04-12', '2015', '2', '4', '15', '0', '13');
INSERT INTO `razor_dim_date` VALUES ('1930', '2015-04-13', '2015', '2', '4', '15', '1', '14');
INSERT INTO `razor_dim_date` VALUES ('1931', '2015-04-14', '2015', '2', '4', '15', '2', '15');
INSERT INTO `razor_dim_date` VALUES ('1932', '2015-04-15', '2015', '2', '4', '15', '3', '16');
INSERT INTO `razor_dim_date` VALUES ('1933', '2015-04-16', '2015', '2', '4', '15', '4', '17');
INSERT INTO `razor_dim_date` VALUES ('1934', '2015-04-17', '2015', '2', '4', '15', '5', '18');
INSERT INTO `razor_dim_date` VALUES ('1935', '2015-04-18', '2015', '2', '4', '16', '6', '19');
INSERT INTO `razor_dim_date` VALUES ('1936', '2015-04-19', '2015', '2', '4', '16', '0', '20');
INSERT INTO `razor_dim_date` VALUES ('1937', '2015-04-20', '2015', '2', '4', '16', '1', '21');
INSERT INTO `razor_dim_date` VALUES ('1938', '2015-04-21', '2015', '2', '4', '16', '2', '22');
INSERT INTO `razor_dim_date` VALUES ('1939', '2015-04-22', '2015', '2', '4', '16', '3', '23');
INSERT INTO `razor_dim_date` VALUES ('1940', '2015-04-23', '2015', '2', '4', '16', '4', '24');
INSERT INTO `razor_dim_date` VALUES ('1941', '2015-04-24', '2015', '2', '4', '16', '5', '25');
INSERT INTO `razor_dim_date` VALUES ('1942', '2015-04-25', '2015', '2', '4', '17', '6', '26');
INSERT INTO `razor_dim_date` VALUES ('1943', '2015-04-26', '2015', '2', '4', '17', '0', '27');
INSERT INTO `razor_dim_date` VALUES ('1944', '2015-04-27', '2015', '2', '4', '17', '1', '28');
INSERT INTO `razor_dim_date` VALUES ('1945', '2015-04-28', '2015', '2', '4', '17', '2', '29');
INSERT INTO `razor_dim_date` VALUES ('1946', '2015-04-29', '2015', '2', '4', '17', '3', '30');
INSERT INTO `razor_dim_date` VALUES ('1947', '2015-04-30', '2015', '2', '5', '17', '4', '1');
INSERT INTO `razor_dim_date` VALUES ('1948', '2015-05-01', '2015', '2', '5', '17', '5', '2');
INSERT INTO `razor_dim_date` VALUES ('1949', '2015-05-02', '2015', '2', '5', '18', '6', '3');
INSERT INTO `razor_dim_date` VALUES ('1950', '2015-05-03', '2015', '2', '5', '18', '0', '4');
INSERT INTO `razor_dim_date` VALUES ('1951', '2015-05-04', '2015', '2', '5', '18', '1', '5');
INSERT INTO `razor_dim_date` VALUES ('1952', '2015-05-05', '2015', '2', '5', '18', '2', '6');
INSERT INTO `razor_dim_date` VALUES ('1953', '2015-05-06', '2015', '2', '5', '18', '3', '7');
INSERT INTO `razor_dim_date` VALUES ('1954', '2015-05-07', '2015', '2', '5', '18', '4', '8');
INSERT INTO `razor_dim_date` VALUES ('1955', '2015-05-08', '2015', '2', '5', '18', '5', '9');
INSERT INTO `razor_dim_date` VALUES ('1956', '2015-05-09', '2015', '2', '5', '19', '6', '10');
INSERT INTO `razor_dim_date` VALUES ('1957', '2015-05-10', '2015', '2', '5', '19', '0', '11');
INSERT INTO `razor_dim_date` VALUES ('1958', '2015-05-11', '2015', '2', '5', '19', '1', '12');
INSERT INTO `razor_dim_date` VALUES ('1959', '2015-05-12', '2015', '2', '5', '19', '2', '13');
INSERT INTO `razor_dim_date` VALUES ('1960', '2015-05-13', '2015', '2', '5', '19', '3', '14');
INSERT INTO `razor_dim_date` VALUES ('1961', '2015-05-14', '2015', '2', '5', '19', '4', '15');
INSERT INTO `razor_dim_date` VALUES ('1962', '2015-05-15', '2015', '2', '5', '19', '5', '16');
INSERT INTO `razor_dim_date` VALUES ('1963', '2015-05-16', '2015', '2', '5', '20', '6', '17');
INSERT INTO `razor_dim_date` VALUES ('1964', '2015-05-17', '2015', '2', '5', '20', '0', '18');
INSERT INTO `razor_dim_date` VALUES ('1965', '2015-05-18', '2015', '2', '5', '20', '1', '19');
INSERT INTO `razor_dim_date` VALUES ('1966', '2015-05-19', '2015', '2', '5', '20', '2', '20');
INSERT INTO `razor_dim_date` VALUES ('1967', '2015-05-20', '2015', '2', '5', '20', '3', '21');
INSERT INTO `razor_dim_date` VALUES ('1968', '2015-05-21', '2015', '2', '5', '20', '4', '22');
INSERT INTO `razor_dim_date` VALUES ('1969', '2015-05-22', '2015', '2', '5', '20', '5', '23');
INSERT INTO `razor_dim_date` VALUES ('1970', '2015-05-23', '2015', '2', '5', '21', '6', '24');
INSERT INTO `razor_dim_date` VALUES ('1971', '2015-05-24', '2015', '2', '5', '21', '0', '25');
INSERT INTO `razor_dim_date` VALUES ('1972', '2015-05-25', '2015', '2', '5', '21', '1', '26');
INSERT INTO `razor_dim_date` VALUES ('1973', '2015-05-26', '2015', '2', '5', '21', '2', '27');
INSERT INTO `razor_dim_date` VALUES ('1974', '2015-05-27', '2015', '2', '5', '21', '3', '28');
INSERT INTO `razor_dim_date` VALUES ('1975', '2015-05-28', '2015', '2', '5', '21', '4', '29');
INSERT INTO `razor_dim_date` VALUES ('1976', '2015-05-29', '2015', '2', '5', '21', '5', '30');
INSERT INTO `razor_dim_date` VALUES ('1977', '2015-05-30', '2015', '2', '5', '22', '6', '31');
INSERT INTO `razor_dim_date` VALUES ('1978', '2015-05-31', '2015', '2', '6', '22', '0', '1');
INSERT INTO `razor_dim_date` VALUES ('1979', '2015-06-01', '2015', '2', '6', '22', '1', '2');
INSERT INTO `razor_dim_date` VALUES ('1980', '2015-06-02', '2015', '2', '6', '22', '2', '3');
INSERT INTO `razor_dim_date` VALUES ('1981', '2015-06-03', '2015', '2', '6', '22', '3', '4');
INSERT INTO `razor_dim_date` VALUES ('1982', '2015-06-04', '2015', '2', '6', '22', '4', '5');
INSERT INTO `razor_dim_date` VALUES ('1983', '2015-06-05', '2015', '2', '6', '22', '5', '6');
INSERT INTO `razor_dim_date` VALUES ('1984', '2015-06-06', '2015', '2', '6', '23', '6', '7');
INSERT INTO `razor_dim_date` VALUES ('1985', '2015-06-07', '2015', '2', '6', '23', '0', '8');
INSERT INTO `razor_dim_date` VALUES ('1986', '2015-06-08', '2015', '2', '6', '23', '1', '9');
INSERT INTO `razor_dim_date` VALUES ('1987', '2015-06-09', '2015', '2', '6', '23', '2', '10');
INSERT INTO `razor_dim_date` VALUES ('1988', '2015-06-10', '2015', '2', '6', '23', '3', '11');
INSERT INTO `razor_dim_date` VALUES ('1989', '2015-06-11', '2015', '2', '6', '23', '4', '12');
INSERT INTO `razor_dim_date` VALUES ('1990', '2015-06-12', '2015', '2', '6', '23', '5', '13');
INSERT INTO `razor_dim_date` VALUES ('1991', '2015-06-13', '2015', '2', '6', '24', '6', '14');
INSERT INTO `razor_dim_date` VALUES ('1992', '2015-06-14', '2015', '2', '6', '24', '0', '15');
INSERT INTO `razor_dim_date` VALUES ('1993', '2015-06-15', '2015', '2', '6', '24', '1', '16');
INSERT INTO `razor_dim_date` VALUES ('1994', '2015-06-16', '2015', '2', '6', '24', '2', '17');
INSERT INTO `razor_dim_date` VALUES ('1995', '2015-06-17', '2015', '2', '6', '24', '3', '18');
INSERT INTO `razor_dim_date` VALUES ('1996', '2015-06-18', '2015', '2', '6', '24', '4', '19');
INSERT INTO `razor_dim_date` VALUES ('1997', '2015-06-19', '2015', '2', '6', '24', '5', '20');
INSERT INTO `razor_dim_date` VALUES ('1998', '2015-06-20', '2015', '2', '6', '25', '6', '21');
INSERT INTO `razor_dim_date` VALUES ('1999', '2015-06-21', '2015', '2', '6', '25', '0', '22');
INSERT INTO `razor_dim_date` VALUES ('2000', '2015-06-22', '2015', '2', '6', '25', '1', '23');
INSERT INTO `razor_dim_date` VALUES ('2001', '2015-06-23', '2015', '2', '6', '25', '2', '24');
INSERT INTO `razor_dim_date` VALUES ('2002', '2015-06-24', '2015', '2', '6', '25', '3', '25');
INSERT INTO `razor_dim_date` VALUES ('2003', '2015-06-25', '2015', '2', '6', '25', '4', '26');
INSERT INTO `razor_dim_date` VALUES ('2004', '2015-06-26', '2015', '2', '6', '25', '5', '27');
INSERT INTO `razor_dim_date` VALUES ('2005', '2015-06-27', '2015', '2', '6', '26', '6', '28');
INSERT INTO `razor_dim_date` VALUES ('2006', '2015-06-28', '2015', '2', '6', '26', '0', '29');
INSERT INTO `razor_dim_date` VALUES ('2007', '2015-06-29', '2015', '2', '6', '26', '1', '30');
INSERT INTO `razor_dim_date` VALUES ('2008', '2015-06-30', '2015', '3', '7', '26', '2', '1');
INSERT INTO `razor_dim_date` VALUES ('2009', '2015-07-01', '2015', '3', '7', '26', '3', '2');
INSERT INTO `razor_dim_date` VALUES ('2010', '2015-07-02', '2015', '3', '7', '26', '4', '3');
INSERT INTO `razor_dim_date` VALUES ('2011', '2015-07-03', '2015', '3', '7', '26', '5', '4');
INSERT INTO `razor_dim_date` VALUES ('2012', '2015-07-04', '2015', '3', '7', '27', '6', '5');
INSERT INTO `razor_dim_date` VALUES ('2013', '2015-07-05', '2015', '3', '7', '27', '0', '6');
INSERT INTO `razor_dim_date` VALUES ('2014', '2015-07-06', '2015', '3', '7', '27', '1', '7');
INSERT INTO `razor_dim_date` VALUES ('2015', '2015-07-07', '2015', '3', '7', '27', '2', '8');
INSERT INTO `razor_dim_date` VALUES ('2016', '2015-07-08', '2015', '3', '7', '27', '3', '9');
INSERT INTO `razor_dim_date` VALUES ('2017', '2015-07-09', '2015', '3', '7', '27', '4', '10');
INSERT INTO `razor_dim_date` VALUES ('2018', '2015-07-10', '2015', '3', '7', '27', '5', '11');
INSERT INTO `razor_dim_date` VALUES ('2019', '2015-07-11', '2015', '3', '7', '28', '6', '12');
INSERT INTO `razor_dim_date` VALUES ('2020', '2015-07-12', '2015', '3', '7', '28', '0', '13');
INSERT INTO `razor_dim_date` VALUES ('2021', '2015-07-13', '2015', '3', '7', '28', '1', '14');
INSERT INTO `razor_dim_date` VALUES ('2022', '2015-07-14', '2015', '3', '7', '28', '2', '15');
INSERT INTO `razor_dim_date` VALUES ('2023', '2015-07-15', '2015', '3', '7', '28', '3', '16');
INSERT INTO `razor_dim_date` VALUES ('2024', '2015-07-16', '2015', '3', '7', '28', '4', '17');
INSERT INTO `razor_dim_date` VALUES ('2025', '2015-07-17', '2015', '3', '7', '28', '5', '18');
INSERT INTO `razor_dim_date` VALUES ('2026', '2015-07-18', '2015', '3', '7', '29', '6', '19');
INSERT INTO `razor_dim_date` VALUES ('2027', '2015-07-19', '2015', '3', '7', '29', '0', '20');
INSERT INTO `razor_dim_date` VALUES ('2028', '2015-07-20', '2015', '3', '7', '29', '1', '21');
INSERT INTO `razor_dim_date` VALUES ('2029', '2015-07-21', '2015', '3', '7', '29', '2', '22');
INSERT INTO `razor_dim_date` VALUES ('2030', '2015-07-22', '2015', '3', '7', '29', '3', '23');
INSERT INTO `razor_dim_date` VALUES ('2031', '2015-07-23', '2015', '3', '7', '29', '4', '24');
INSERT INTO `razor_dim_date` VALUES ('2032', '2015-07-24', '2015', '3', '7', '29', '5', '25');
INSERT INTO `razor_dim_date` VALUES ('2033', '2015-07-25', '2015', '3', '7', '30', '6', '26');
INSERT INTO `razor_dim_date` VALUES ('2034', '2015-07-26', '2015', '3', '7', '30', '0', '27');
INSERT INTO `razor_dim_date` VALUES ('2035', '2015-07-27', '2015', '3', '7', '30', '1', '28');
INSERT INTO `razor_dim_date` VALUES ('2036', '2015-07-28', '2015', '3', '7', '30', '2', '29');
INSERT INTO `razor_dim_date` VALUES ('2037', '2015-07-29', '2015', '3', '7', '30', '3', '30');
INSERT INTO `razor_dim_date` VALUES ('2038', '2015-07-30', '2015', '3', '7', '30', '4', '31');
INSERT INTO `razor_dim_date` VALUES ('2039', '2015-07-31', '2015', '3', '8', '30', '5', '1');
INSERT INTO `razor_dim_date` VALUES ('2040', '2015-08-01', '2015', '3', '8', '31', '6', '2');
INSERT INTO `razor_dim_date` VALUES ('2041', '2015-08-02', '2015', '3', '8', '31', '0', '3');
INSERT INTO `razor_dim_date` VALUES ('2042', '2015-08-03', '2015', '3', '8', '31', '1', '4');
INSERT INTO `razor_dim_date` VALUES ('2043', '2015-08-04', '2015', '3', '8', '31', '2', '5');
INSERT INTO `razor_dim_date` VALUES ('2044', '2015-08-05', '2015', '3', '8', '31', '3', '6');
INSERT INTO `razor_dim_date` VALUES ('2045', '2015-08-06', '2015', '3', '8', '31', '4', '7');
INSERT INTO `razor_dim_date` VALUES ('2046', '2015-08-07', '2015', '3', '8', '31', '5', '8');
INSERT INTO `razor_dim_date` VALUES ('2047', '2015-08-08', '2015', '3', '8', '32', '6', '9');
INSERT INTO `razor_dim_date` VALUES ('2048', '2015-08-09', '2015', '3', '8', '32', '0', '10');
INSERT INTO `razor_dim_date` VALUES ('2049', '2015-08-10', '2015', '3', '8', '32', '1', '11');
INSERT INTO `razor_dim_date` VALUES ('2050', '2015-08-11', '2015', '3', '8', '32', '2', '12');
INSERT INTO `razor_dim_date` VALUES ('2051', '2015-08-12', '2015', '3', '8', '32', '3', '13');
INSERT INTO `razor_dim_date` VALUES ('2052', '2015-08-13', '2015', '3', '8', '32', '4', '14');
INSERT INTO `razor_dim_date` VALUES ('2053', '2015-08-14', '2015', '3', '8', '32', '5', '15');
INSERT INTO `razor_dim_date` VALUES ('2054', '2015-08-15', '2015', '3', '8', '33', '6', '16');
INSERT INTO `razor_dim_date` VALUES ('2055', '2015-08-16', '2015', '3', '8', '33', '0', '17');
INSERT INTO `razor_dim_date` VALUES ('2056', '2015-08-17', '2015', '3', '8', '33', '1', '18');
INSERT INTO `razor_dim_date` VALUES ('2057', '2015-08-18', '2015', '3', '8', '33', '2', '19');
INSERT INTO `razor_dim_date` VALUES ('2058', '2015-08-19', '2015', '3', '8', '33', '3', '20');
INSERT INTO `razor_dim_date` VALUES ('2059', '2015-08-20', '2015', '3', '8', '33', '4', '21');
INSERT INTO `razor_dim_date` VALUES ('2060', '2015-08-21', '2015', '3', '8', '33', '5', '22');
INSERT INTO `razor_dim_date` VALUES ('2061', '2015-08-22', '2015', '3', '8', '34', '6', '23');
INSERT INTO `razor_dim_date` VALUES ('2062', '2015-08-23', '2015', '3', '8', '34', '0', '24');
INSERT INTO `razor_dim_date` VALUES ('2063', '2015-08-24', '2015', '3', '8', '34', '1', '25');
INSERT INTO `razor_dim_date` VALUES ('2064', '2015-08-25', '2015', '3', '8', '34', '2', '26');
INSERT INTO `razor_dim_date` VALUES ('2065', '2015-08-26', '2015', '3', '8', '34', '3', '27');
INSERT INTO `razor_dim_date` VALUES ('2066', '2015-08-27', '2015', '3', '8', '34', '4', '28');
INSERT INTO `razor_dim_date` VALUES ('2067', '2015-08-28', '2015', '3', '8', '34', '5', '29');
INSERT INTO `razor_dim_date` VALUES ('2068', '2015-08-29', '2015', '3', '8', '35', '6', '30');
INSERT INTO `razor_dim_date` VALUES ('2069', '2015-08-30', '2015', '3', '8', '35', '0', '31');
INSERT INTO `razor_dim_date` VALUES ('2070', '2015-08-31', '2015', '3', '9', '35', '1', '1');
INSERT INTO `razor_dim_date` VALUES ('2071', '2015-09-01', '2015', '3', '9', '35', '2', '2');
INSERT INTO `razor_dim_date` VALUES ('2072', '2015-09-02', '2015', '3', '9', '35', '3', '3');
INSERT INTO `razor_dim_date` VALUES ('2073', '2015-09-03', '2015', '3', '9', '35', '4', '4');
INSERT INTO `razor_dim_date` VALUES ('2074', '2015-09-04', '2015', '3', '9', '35', '5', '5');
INSERT INTO `razor_dim_date` VALUES ('2075', '2015-09-05', '2015', '3', '9', '36', '6', '6');
INSERT INTO `razor_dim_date` VALUES ('2076', '2015-09-06', '2015', '3', '9', '36', '0', '7');
INSERT INTO `razor_dim_date` VALUES ('2077', '2015-09-07', '2015', '3', '9', '36', '1', '8');
INSERT INTO `razor_dim_date` VALUES ('2078', '2015-09-08', '2015', '3', '9', '36', '2', '9');
INSERT INTO `razor_dim_date` VALUES ('2079', '2015-09-09', '2015', '3', '9', '36', '3', '10');
INSERT INTO `razor_dim_date` VALUES ('2080', '2015-09-10', '2015', '3', '9', '36', '4', '11');
INSERT INTO `razor_dim_date` VALUES ('2081', '2015-09-11', '2015', '3', '9', '36', '5', '12');
INSERT INTO `razor_dim_date` VALUES ('2082', '2015-09-12', '2015', '3', '9', '37', '6', '13');
INSERT INTO `razor_dim_date` VALUES ('2083', '2015-09-13', '2015', '3', '9', '37', '0', '14');
INSERT INTO `razor_dim_date` VALUES ('2084', '2015-09-14', '2015', '3', '9', '37', '1', '15');
INSERT INTO `razor_dim_date` VALUES ('2085', '2015-09-15', '2015', '3', '9', '37', '2', '16');
INSERT INTO `razor_dim_date` VALUES ('2086', '2015-09-16', '2015', '3', '9', '37', '3', '17');
INSERT INTO `razor_dim_date` VALUES ('2087', '2015-09-17', '2015', '3', '9', '37', '4', '18');
INSERT INTO `razor_dim_date` VALUES ('2088', '2015-09-18', '2015', '3', '9', '37', '5', '19');
INSERT INTO `razor_dim_date` VALUES ('2089', '2015-09-19', '2015', '3', '9', '38', '6', '20');
INSERT INTO `razor_dim_date` VALUES ('2090', '2015-09-20', '2015', '3', '9', '38', '0', '21');
INSERT INTO `razor_dim_date` VALUES ('2091', '2015-09-21', '2015', '3', '9', '38', '1', '22');
INSERT INTO `razor_dim_date` VALUES ('2092', '2015-09-22', '2015', '3', '9', '38', '2', '23');
INSERT INTO `razor_dim_date` VALUES ('2093', '2015-09-23', '2015', '3', '9', '38', '3', '24');
INSERT INTO `razor_dim_date` VALUES ('2094', '2015-09-24', '2015', '3', '9', '38', '4', '25');
INSERT INTO `razor_dim_date` VALUES ('2095', '2015-09-25', '2015', '3', '9', '38', '5', '26');
INSERT INTO `razor_dim_date` VALUES ('2096', '2015-09-26', '2015', '3', '9', '39', '6', '27');
INSERT INTO `razor_dim_date` VALUES ('2097', '2015-09-27', '2015', '3', '9', '39', '0', '28');
INSERT INTO `razor_dim_date` VALUES ('2098', '2015-09-28', '2015', '3', '9', '39', '1', '29');
INSERT INTO `razor_dim_date` VALUES ('2099', '2015-09-29', '2015', '3', '9', '39', '2', '30');
INSERT INTO `razor_dim_date` VALUES ('2100', '2015-09-30', '2015', '4', '10', '39', '3', '1');
INSERT INTO `razor_dim_date` VALUES ('2101', '2015-10-01', '2015', '4', '10', '39', '4', '2');
INSERT INTO `razor_dim_date` VALUES ('2102', '2015-10-02', '2015', '4', '10', '39', '5', '3');
INSERT INTO `razor_dim_date` VALUES ('2103', '2015-10-03', '2015', '4', '10', '40', '6', '4');
INSERT INTO `razor_dim_date` VALUES ('2104', '2015-10-04', '2015', '4', '10', '40', '0', '5');
INSERT INTO `razor_dim_date` VALUES ('2105', '2015-10-05', '2015', '4', '10', '40', '1', '6');
INSERT INTO `razor_dim_date` VALUES ('2106', '2015-10-06', '2015', '4', '10', '40', '2', '7');
INSERT INTO `razor_dim_date` VALUES ('2107', '2015-10-07', '2015', '4', '10', '40', '3', '8');
INSERT INTO `razor_dim_date` VALUES ('2108', '2015-10-08', '2015', '4', '10', '40', '4', '9');
INSERT INTO `razor_dim_date` VALUES ('2109', '2015-10-09', '2015', '4', '10', '40', '5', '10');
INSERT INTO `razor_dim_date` VALUES ('2110', '2015-10-10', '2015', '4', '10', '41', '6', '11');
INSERT INTO `razor_dim_date` VALUES ('2111', '2015-10-11', '2015', '4', '10', '41', '0', '12');
INSERT INTO `razor_dim_date` VALUES ('2112', '2015-10-12', '2015', '4', '10', '41', '1', '13');
INSERT INTO `razor_dim_date` VALUES ('2113', '2015-10-13', '2015', '4', '10', '41', '2', '14');
INSERT INTO `razor_dim_date` VALUES ('2114', '2015-10-14', '2015', '4', '10', '41', '3', '15');
INSERT INTO `razor_dim_date` VALUES ('2115', '2015-10-15', '2015', '4', '10', '41', '4', '16');
INSERT INTO `razor_dim_date` VALUES ('2116', '2015-10-16', '2015', '4', '10', '41', '5', '17');
INSERT INTO `razor_dim_date` VALUES ('2117', '2015-10-17', '2015', '4', '10', '42', '6', '18');
INSERT INTO `razor_dim_date` VALUES ('2118', '2015-10-18', '2015', '4', '10', '42', '0', '19');
INSERT INTO `razor_dim_date` VALUES ('2119', '2015-10-19', '2015', '4', '10', '42', '1', '20');
INSERT INTO `razor_dim_date` VALUES ('2120', '2015-10-20', '2015', '4', '10', '42', '2', '21');
INSERT INTO `razor_dim_date` VALUES ('2121', '2015-10-21', '2015', '4', '10', '42', '3', '22');
INSERT INTO `razor_dim_date` VALUES ('2122', '2015-10-22', '2015', '4', '10', '42', '4', '23');
INSERT INTO `razor_dim_date` VALUES ('2123', '2015-10-23', '2015', '4', '10', '42', '5', '24');
INSERT INTO `razor_dim_date` VALUES ('2124', '2015-10-24', '2015', '4', '10', '43', '6', '25');
INSERT INTO `razor_dim_date` VALUES ('2125', '2015-10-25', '2015', '4', '10', '43', '0', '26');
INSERT INTO `razor_dim_date` VALUES ('2126', '2015-10-26', '2015', '4', '10', '43', '1', '27');
INSERT INTO `razor_dim_date` VALUES ('2127', '2015-10-27', '2015', '4', '10', '43', '2', '28');
INSERT INTO `razor_dim_date` VALUES ('2128', '2015-10-28', '2015', '4', '10', '43', '3', '29');
INSERT INTO `razor_dim_date` VALUES ('2129', '2015-10-29', '2015', '4', '10', '43', '4', '30');
INSERT INTO `razor_dim_date` VALUES ('2130', '2015-10-30', '2015', '4', '10', '43', '5', '31');
INSERT INTO `razor_dim_date` VALUES ('2131', '2015-10-31', '2015', '4', '11', '44', '6', '1');
INSERT INTO `razor_dim_date` VALUES ('2132', '2015-11-01', '2015', '4', '11', '44', '0', '2');
INSERT INTO `razor_dim_date` VALUES ('2133', '2015-11-02', '2015', '4', '11', '44', '1', '3');
INSERT INTO `razor_dim_date` VALUES ('2134', '2015-11-03', '2015', '4', '11', '44', '2', '4');
INSERT INTO `razor_dim_date` VALUES ('2135', '2015-11-04', '2015', '4', '11', '44', '3', '5');
INSERT INTO `razor_dim_date` VALUES ('2136', '2015-11-05', '2015', '4', '11', '44', '4', '6');
INSERT INTO `razor_dim_date` VALUES ('2137', '2015-11-06', '2015', '4', '11', '44', '5', '7');
INSERT INTO `razor_dim_date` VALUES ('2138', '2015-11-07', '2015', '4', '11', '45', '6', '8');
INSERT INTO `razor_dim_date` VALUES ('2139', '2015-11-08', '2015', '4', '11', '45', '0', '9');
INSERT INTO `razor_dim_date` VALUES ('2140', '2015-11-09', '2015', '4', '11', '45', '1', '10');
INSERT INTO `razor_dim_date` VALUES ('2141', '2015-11-10', '2015', '4', '11', '45', '2', '11');
INSERT INTO `razor_dim_date` VALUES ('2142', '2015-11-11', '2015', '4', '11', '45', '3', '12');
INSERT INTO `razor_dim_date` VALUES ('2143', '2015-11-12', '2015', '4', '11', '45', '4', '13');
INSERT INTO `razor_dim_date` VALUES ('2144', '2015-11-13', '2015', '4', '11', '45', '5', '14');
INSERT INTO `razor_dim_date` VALUES ('2145', '2015-11-14', '2015', '4', '11', '46', '6', '15');
INSERT INTO `razor_dim_date` VALUES ('2146', '2015-11-15', '2015', '4', '11', '46', '0', '16');
INSERT INTO `razor_dim_date` VALUES ('2147', '2015-11-16', '2015', '4', '11', '46', '1', '17');
INSERT INTO `razor_dim_date` VALUES ('2148', '2015-11-17', '2015', '4', '11', '46', '2', '18');
INSERT INTO `razor_dim_date` VALUES ('2149', '2015-11-18', '2015', '4', '11', '46', '3', '19');
INSERT INTO `razor_dim_date` VALUES ('2150', '2015-11-19', '2015', '4', '11', '46', '4', '20');
INSERT INTO `razor_dim_date` VALUES ('2151', '2015-11-20', '2015', '4', '11', '46', '5', '21');
INSERT INTO `razor_dim_date` VALUES ('2152', '2015-11-21', '2015', '4', '11', '47', '6', '22');
INSERT INTO `razor_dim_date` VALUES ('2153', '2015-11-22', '2015', '4', '11', '47', '0', '23');
INSERT INTO `razor_dim_date` VALUES ('2154', '2015-11-23', '2015', '4', '11', '47', '1', '24');
INSERT INTO `razor_dim_date` VALUES ('2155', '2015-11-24', '2015', '4', '11', '47', '2', '25');
INSERT INTO `razor_dim_date` VALUES ('2156', '2015-11-25', '2015', '4', '11', '47', '3', '26');
INSERT INTO `razor_dim_date` VALUES ('2157', '2015-11-26', '2015', '4', '11', '47', '4', '27');
INSERT INTO `razor_dim_date` VALUES ('2158', '2015-11-27', '2015', '4', '11', '47', '5', '28');
INSERT INTO `razor_dim_date` VALUES ('2159', '2015-11-28', '2015', '4', '11', '48', '6', '29');
INSERT INTO `razor_dim_date` VALUES ('2160', '2015-11-29', '2015', '4', '11', '48', '0', '30');
INSERT INTO `razor_dim_date` VALUES ('2161', '2015-11-30', '2015', '4', '12', '48', '1', '1');
INSERT INTO `razor_dim_date` VALUES ('2162', '2015-12-01', '2015', '4', '12', '48', '2', '2');
INSERT INTO `razor_dim_date` VALUES ('2163', '2015-12-02', '2015', '4', '12', '48', '3', '3');
INSERT INTO `razor_dim_date` VALUES ('2164', '2015-12-03', '2015', '4', '12', '48', '4', '4');
INSERT INTO `razor_dim_date` VALUES ('2165', '2015-12-04', '2015', '4', '12', '48', '5', '5');
INSERT INTO `razor_dim_date` VALUES ('2166', '2015-12-05', '2015', '4', '12', '49', '6', '6');
INSERT INTO `razor_dim_date` VALUES ('2167', '2015-12-06', '2015', '4', '12', '49', '0', '7');
INSERT INTO `razor_dim_date` VALUES ('2168', '2015-12-07', '2015', '4', '12', '49', '1', '8');
INSERT INTO `razor_dim_date` VALUES ('2169', '2015-12-08', '2015', '4', '12', '49', '2', '9');
INSERT INTO `razor_dim_date` VALUES ('2170', '2015-12-09', '2015', '4', '12', '49', '3', '10');
INSERT INTO `razor_dim_date` VALUES ('2171', '2015-12-10', '2015', '4', '12', '49', '4', '11');
INSERT INTO `razor_dim_date` VALUES ('2172', '2015-12-11', '2015', '4', '12', '49', '5', '12');
INSERT INTO `razor_dim_date` VALUES ('2173', '2015-12-12', '2015', '4', '12', '50', '6', '13');
INSERT INTO `razor_dim_date` VALUES ('2174', '2015-12-13', '2015', '4', '12', '50', '0', '14');
INSERT INTO `razor_dim_date` VALUES ('2175', '2015-12-14', '2015', '4', '12', '50', '1', '15');
INSERT INTO `razor_dim_date` VALUES ('2176', '2015-12-15', '2015', '4', '12', '50', '2', '16');
INSERT INTO `razor_dim_date` VALUES ('2177', '2015-12-16', '2015', '4', '12', '50', '3', '17');
INSERT INTO `razor_dim_date` VALUES ('2178', '2015-12-17', '2015', '4', '12', '50', '4', '18');
INSERT INTO `razor_dim_date` VALUES ('2179', '2015-12-18', '2015', '4', '12', '50', '5', '19');
INSERT INTO `razor_dim_date` VALUES ('2180', '2015-12-19', '2015', '4', '12', '51', '6', '20');
INSERT INTO `razor_dim_date` VALUES ('2181', '2015-12-20', '2015', '4', '12', '51', '0', '21');
INSERT INTO `razor_dim_date` VALUES ('2182', '2015-12-21', '2015', '4', '12', '51', '1', '22');
INSERT INTO `razor_dim_date` VALUES ('2183', '2015-12-22', '2015', '4', '12', '51', '2', '23');
INSERT INTO `razor_dim_date` VALUES ('2184', '2015-12-23', '2015', '4', '12', '51', '3', '24');
INSERT INTO `razor_dim_date` VALUES ('2185', '2015-12-24', '2015', '4', '12', '51', '4', '25');
INSERT INTO `razor_dim_date` VALUES ('2186', '2015-12-25', '2015', '4', '12', '51', '5', '26');
INSERT INTO `razor_dim_date` VALUES ('2187', '2015-12-26', '2015', '4', '12', '52', '6', '27');
INSERT INTO `razor_dim_date` VALUES ('2188', '2015-12-27', '2015', '4', '12', '52', '0', '28');
INSERT INTO `razor_dim_date` VALUES ('2189', '2015-12-28', '2015', '4', '12', '52', '1', '29');
INSERT INTO `razor_dim_date` VALUES ('2190', '2015-12-29', '2015', '4', '12', '52', '2', '30');
INSERT INTO `razor_dim_date` VALUES ('2191', '2015-12-30', '2015', '4', '12', '52', '3', '31');
INSERT INTO `razor_dim_date` VALUES ('2192', '2015-12-31', '2016', '1', '1', '0', '4', '1');
INSERT INTO `razor_dim_date` VALUES ('2193', '2016-01-01', '2016', '1', '1', '0', '5', '2');
INSERT INTO `razor_dim_date` VALUES ('2194', '2016-01-02', '2016', '1', '1', '1', '6', '3');
INSERT INTO `razor_dim_date` VALUES ('2195', '2016-01-03', '2016', '1', '1', '1', '0', '4');
INSERT INTO `razor_dim_date` VALUES ('2196', '2016-01-04', '2016', '1', '1', '1', '1', '5');
INSERT INTO `razor_dim_date` VALUES ('2197', '2016-01-05', '2016', '1', '1', '1', '2', '6');
INSERT INTO `razor_dim_date` VALUES ('2198', '2016-01-06', '2016', '1', '1', '1', '3', '7');
INSERT INTO `razor_dim_date` VALUES ('2199', '2016-01-07', '2016', '1', '1', '1', '4', '8');
INSERT INTO `razor_dim_date` VALUES ('2200', '2016-01-08', '2016', '1', '1', '1', '5', '9');
INSERT INTO `razor_dim_date` VALUES ('2201', '2016-01-09', '2016', '1', '1', '2', '6', '10');
INSERT INTO `razor_dim_date` VALUES ('2202', '2016-01-10', '2016', '1', '1', '2', '0', '11');
INSERT INTO `razor_dim_date` VALUES ('2203', '2016-01-11', '2016', '1', '1', '2', '1', '12');
INSERT INTO `razor_dim_date` VALUES ('2204', '2016-01-12', '2016', '1', '1', '2', '2', '13');
INSERT INTO `razor_dim_date` VALUES ('2205', '2016-01-13', '2016', '1', '1', '2', '3', '14');
INSERT INTO `razor_dim_date` VALUES ('2206', '2016-01-14', '2016', '1', '1', '2', '4', '15');
INSERT INTO `razor_dim_date` VALUES ('2207', '2016-01-15', '2016', '1', '1', '2', '5', '16');
INSERT INTO `razor_dim_date` VALUES ('2208', '2016-01-16', '2016', '1', '1', '3', '6', '17');
INSERT INTO `razor_dim_date` VALUES ('2209', '2016-01-17', '2016', '1', '1', '3', '0', '18');
INSERT INTO `razor_dim_date` VALUES ('2210', '2016-01-18', '2016', '1', '1', '3', '1', '19');
INSERT INTO `razor_dim_date` VALUES ('2211', '2016-01-19', '2016', '1', '1', '3', '2', '20');
INSERT INTO `razor_dim_date` VALUES ('2212', '2016-01-20', '2016', '1', '1', '3', '3', '21');
INSERT INTO `razor_dim_date` VALUES ('2213', '2016-01-21', '2016', '1', '1', '3', '4', '22');
INSERT INTO `razor_dim_date` VALUES ('2214', '2016-01-22', '2016', '1', '1', '3', '5', '23');
INSERT INTO `razor_dim_date` VALUES ('2215', '2016-01-23', '2016', '1', '1', '4', '6', '24');
INSERT INTO `razor_dim_date` VALUES ('2216', '2016-01-24', '2016', '1', '1', '4', '0', '25');
INSERT INTO `razor_dim_date` VALUES ('2217', '2016-01-25', '2016', '1', '1', '4', '1', '26');
INSERT INTO `razor_dim_date` VALUES ('2218', '2016-01-26', '2016', '1', '1', '4', '2', '27');
INSERT INTO `razor_dim_date` VALUES ('2219', '2016-01-27', '2016', '1', '1', '4', '3', '28');
INSERT INTO `razor_dim_date` VALUES ('2220', '2016-01-28', '2016', '1', '1', '4', '4', '29');
INSERT INTO `razor_dim_date` VALUES ('2221', '2016-01-29', '2016', '1', '1', '4', '5', '30');
INSERT INTO `razor_dim_date` VALUES ('2222', '2016-01-30', '2016', '1', '1', '5', '6', '31');
INSERT INTO `razor_dim_date` VALUES ('2223', '2016-01-31', '2016', '1', '2', '5', '0', '1');
INSERT INTO `razor_dim_date` VALUES ('2224', '2016-02-01', '2016', '1', '2', '5', '1', '2');
INSERT INTO `razor_dim_date` VALUES ('2225', '2016-02-02', '2016', '1', '2', '5', '2', '3');
INSERT INTO `razor_dim_date` VALUES ('2226', '2016-02-03', '2016', '1', '2', '5', '3', '4');
INSERT INTO `razor_dim_date` VALUES ('2227', '2016-02-04', '2016', '1', '2', '5', '4', '5');
INSERT INTO `razor_dim_date` VALUES ('2228', '2016-02-05', '2016', '1', '2', '5', '5', '6');
INSERT INTO `razor_dim_date` VALUES ('2229', '2016-02-06', '2016', '1', '2', '6', '6', '7');
INSERT INTO `razor_dim_date` VALUES ('2230', '2016-02-07', '2016', '1', '2', '6', '0', '8');
INSERT INTO `razor_dim_date` VALUES ('2231', '2016-02-08', '2016', '1', '2', '6', '1', '9');
INSERT INTO `razor_dim_date` VALUES ('2232', '2016-02-09', '2016', '1', '2', '6', '2', '10');
INSERT INTO `razor_dim_date` VALUES ('2233', '2016-02-10', '2016', '1', '2', '6', '3', '11');
INSERT INTO `razor_dim_date` VALUES ('2234', '2016-02-11', '2016', '1', '2', '6', '4', '12');
INSERT INTO `razor_dim_date` VALUES ('2235', '2016-02-12', '2016', '1', '2', '6', '5', '13');
INSERT INTO `razor_dim_date` VALUES ('2236', '2016-02-13', '2016', '1', '2', '7', '6', '14');
INSERT INTO `razor_dim_date` VALUES ('2237', '2016-02-14', '2016', '1', '2', '7', '0', '15');
INSERT INTO `razor_dim_date` VALUES ('2238', '2016-02-15', '2016', '1', '2', '7', '1', '16');
INSERT INTO `razor_dim_date` VALUES ('2239', '2016-02-16', '2016', '1', '2', '7', '2', '17');
INSERT INTO `razor_dim_date` VALUES ('2240', '2016-02-17', '2016', '1', '2', '7', '3', '18');
INSERT INTO `razor_dim_date` VALUES ('2241', '2016-02-18', '2016', '1', '2', '7', '4', '19');
INSERT INTO `razor_dim_date` VALUES ('2242', '2016-02-19', '2016', '1', '2', '7', '5', '20');
INSERT INTO `razor_dim_date` VALUES ('2243', '2016-02-20', '2016', '1', '2', '8', '6', '21');
INSERT INTO `razor_dim_date` VALUES ('2244', '2016-02-21', '2016', '1', '2', '8', '0', '22');
INSERT INTO `razor_dim_date` VALUES ('2245', '2016-02-22', '2016', '1', '2', '8', '1', '23');
INSERT INTO `razor_dim_date` VALUES ('2246', '2016-02-23', '2016', '1', '2', '8', '2', '24');
INSERT INTO `razor_dim_date` VALUES ('2247', '2016-02-24', '2016', '1', '2', '8', '3', '25');
INSERT INTO `razor_dim_date` VALUES ('2248', '2016-02-25', '2016', '1', '2', '8', '4', '26');
INSERT INTO `razor_dim_date` VALUES ('2249', '2016-02-26', '2016', '1', '2', '8', '5', '27');
INSERT INTO `razor_dim_date` VALUES ('2250', '2016-02-27', '2016', '1', '2', '9', '6', '28');
INSERT INTO `razor_dim_date` VALUES ('2251', '2016-02-28', '2016', '1', '2', '9', '0', '29');
INSERT INTO `razor_dim_date` VALUES ('2252', '2016-02-29', '2016', '1', '3', '9', '1', '1');
INSERT INTO `razor_dim_date` VALUES ('2253', '2016-03-01', '2016', '1', '3', '9', '2', '2');
INSERT INTO `razor_dim_date` VALUES ('2254', '2016-03-02', '2016', '1', '3', '9', '3', '3');
INSERT INTO `razor_dim_date` VALUES ('2255', '2016-03-03', '2016', '1', '3', '9', '4', '4');
INSERT INTO `razor_dim_date` VALUES ('2256', '2016-03-04', '2016', '1', '3', '9', '5', '5');
INSERT INTO `razor_dim_date` VALUES ('2257', '2016-03-05', '2016', '1', '3', '10', '6', '6');
INSERT INTO `razor_dim_date` VALUES ('2258', '2016-03-06', '2016', '1', '3', '10', '0', '7');
INSERT INTO `razor_dim_date` VALUES ('2259', '2016-03-07', '2016', '1', '3', '10', '1', '8');
INSERT INTO `razor_dim_date` VALUES ('2260', '2016-03-08', '2016', '1', '3', '10', '2', '9');
INSERT INTO `razor_dim_date` VALUES ('2261', '2016-03-09', '2016', '1', '3', '10', '3', '10');
INSERT INTO `razor_dim_date` VALUES ('2262', '2016-03-10', '2016', '1', '3', '10', '4', '11');
INSERT INTO `razor_dim_date` VALUES ('2263', '2016-03-11', '2016', '1', '3', '10', '5', '12');
INSERT INTO `razor_dim_date` VALUES ('2264', '2016-03-12', '2016', '1', '3', '11', '6', '13');
INSERT INTO `razor_dim_date` VALUES ('2265', '2016-03-13', '2016', '1', '3', '11', '0', '14');
INSERT INTO `razor_dim_date` VALUES ('2266', '2016-03-14', '2016', '1', '3', '11', '1', '15');
INSERT INTO `razor_dim_date` VALUES ('2267', '2016-03-15', '2016', '1', '3', '11', '2', '16');
INSERT INTO `razor_dim_date` VALUES ('2268', '2016-03-16', '2016', '1', '3', '11', '3', '17');
INSERT INTO `razor_dim_date` VALUES ('2269', '2016-03-17', '2016', '1', '3', '11', '4', '18');
INSERT INTO `razor_dim_date` VALUES ('2270', '2016-03-18', '2016', '1', '3', '11', '5', '19');
INSERT INTO `razor_dim_date` VALUES ('2271', '2016-03-19', '2016', '1', '3', '12', '6', '20');
INSERT INTO `razor_dim_date` VALUES ('2272', '2016-03-20', '2016', '1', '3', '12', '0', '21');
INSERT INTO `razor_dim_date` VALUES ('2273', '2016-03-21', '2016', '1', '3', '12', '1', '22');
INSERT INTO `razor_dim_date` VALUES ('2274', '2016-03-22', '2016', '1', '3', '12', '2', '23');
INSERT INTO `razor_dim_date` VALUES ('2275', '2016-03-23', '2016', '1', '3', '12', '3', '24');
INSERT INTO `razor_dim_date` VALUES ('2276', '2016-03-24', '2016', '1', '3', '12', '4', '25');
INSERT INTO `razor_dim_date` VALUES ('2277', '2016-03-25', '2016', '1', '3', '12', '5', '26');
INSERT INTO `razor_dim_date` VALUES ('2278', '2016-03-26', '2016', '1', '3', '13', '6', '27');
INSERT INTO `razor_dim_date` VALUES ('2279', '2016-03-27', '2016', '1', '3', '13', '0', '28');
INSERT INTO `razor_dim_date` VALUES ('2280', '2016-03-28', '2016', '1', '3', '13', '1', '29');
INSERT INTO `razor_dim_date` VALUES ('2281', '2016-03-29', '2016', '1', '3', '13', '2', '30');
INSERT INTO `razor_dim_date` VALUES ('2282', '2016-03-30', '2016', '1', '3', '13', '3', '31');
INSERT INTO `razor_dim_date` VALUES ('2283', '2016-03-31', '2016', '2', '4', '13', '4', '1');
INSERT INTO `razor_dim_date` VALUES ('2284', '2016-04-01', '2016', '2', '4', '13', '5', '2');
INSERT INTO `razor_dim_date` VALUES ('2285', '2016-04-02', '2016', '2', '4', '14', '6', '3');
INSERT INTO `razor_dim_date` VALUES ('2286', '2016-04-03', '2016', '2', '4', '14', '0', '4');
INSERT INTO `razor_dim_date` VALUES ('2287', '2016-04-04', '2016', '2', '4', '14', '1', '5');
INSERT INTO `razor_dim_date` VALUES ('2288', '2016-04-05', '2016', '2', '4', '14', '2', '6');
INSERT INTO `razor_dim_date` VALUES ('2289', '2016-04-06', '2016', '2', '4', '14', '3', '7');
INSERT INTO `razor_dim_date` VALUES ('2290', '2016-04-07', '2016', '2', '4', '14', '4', '8');
INSERT INTO `razor_dim_date` VALUES ('2291', '2016-04-08', '2016', '2', '4', '14', '5', '9');
INSERT INTO `razor_dim_date` VALUES ('2292', '2016-04-09', '2016', '2', '4', '15', '6', '10');
INSERT INTO `razor_dim_date` VALUES ('2293', '2016-04-10', '2016', '2', '4', '15', '0', '11');
INSERT INTO `razor_dim_date` VALUES ('2294', '2016-04-11', '2016', '2', '4', '15', '1', '12');
INSERT INTO `razor_dim_date` VALUES ('2295', '2016-04-12', '2016', '2', '4', '15', '2', '13');
INSERT INTO `razor_dim_date` VALUES ('2296', '2016-04-13', '2016', '2', '4', '15', '3', '14');
INSERT INTO `razor_dim_date` VALUES ('2297', '2016-04-14', '2016', '2', '4', '15', '4', '15');
INSERT INTO `razor_dim_date` VALUES ('2298', '2016-04-15', '2016', '2', '4', '15', '5', '16');
INSERT INTO `razor_dim_date` VALUES ('2299', '2016-04-16', '2016', '2', '4', '16', '6', '17');
INSERT INTO `razor_dim_date` VALUES ('2300', '2016-04-17', '2016', '2', '4', '16', '0', '18');
INSERT INTO `razor_dim_date` VALUES ('2301', '2016-04-18', '2016', '2', '4', '16', '1', '19');
INSERT INTO `razor_dim_date` VALUES ('2302', '2016-04-19', '2016', '2', '4', '16', '2', '20');
INSERT INTO `razor_dim_date` VALUES ('2303', '2016-04-20', '2016', '2', '4', '16', '3', '21');
INSERT INTO `razor_dim_date` VALUES ('2304', '2016-04-21', '2016', '2', '4', '16', '4', '22');
INSERT INTO `razor_dim_date` VALUES ('2305', '2016-04-22', '2016', '2', '4', '16', '5', '23');
INSERT INTO `razor_dim_date` VALUES ('2306', '2016-04-23', '2016', '2', '4', '17', '6', '24');
INSERT INTO `razor_dim_date` VALUES ('2307', '2016-04-24', '2016', '2', '4', '17', '0', '25');
INSERT INTO `razor_dim_date` VALUES ('2308', '2016-04-25', '2016', '2', '4', '17', '1', '26');
INSERT INTO `razor_dim_date` VALUES ('2309', '2016-04-26', '2016', '2', '4', '17', '2', '27');
INSERT INTO `razor_dim_date` VALUES ('2310', '2016-04-27', '2016', '2', '4', '17', '3', '28');
INSERT INTO `razor_dim_date` VALUES ('2311', '2016-04-28', '2016', '2', '4', '17', '4', '29');
INSERT INTO `razor_dim_date` VALUES ('2312', '2016-04-29', '2016', '2', '4', '17', '5', '30');
INSERT INTO `razor_dim_date` VALUES ('2313', '2016-04-30', '2016', '2', '5', '18', '6', '1');
INSERT INTO `razor_dim_date` VALUES ('2314', '2016-05-01', '2016', '2', '5', '18', '0', '2');
INSERT INTO `razor_dim_date` VALUES ('2315', '2016-05-02', '2016', '2', '5', '18', '1', '3');
INSERT INTO `razor_dim_date` VALUES ('2316', '2016-05-03', '2016', '2', '5', '18', '2', '4');
INSERT INTO `razor_dim_date` VALUES ('2317', '2016-05-04', '2016', '2', '5', '18', '3', '5');
INSERT INTO `razor_dim_date` VALUES ('2318', '2016-05-05', '2016', '2', '5', '18', '4', '6');
INSERT INTO `razor_dim_date` VALUES ('2319', '2016-05-06', '2016', '2', '5', '18', '5', '7');
INSERT INTO `razor_dim_date` VALUES ('2320', '2016-05-07', '2016', '2', '5', '19', '6', '8');
INSERT INTO `razor_dim_date` VALUES ('2321', '2016-05-08', '2016', '2', '5', '19', '0', '9');
INSERT INTO `razor_dim_date` VALUES ('2322', '2016-05-09', '2016', '2', '5', '19', '1', '10');
INSERT INTO `razor_dim_date` VALUES ('2323', '2016-05-10', '2016', '2', '5', '19', '2', '11');
INSERT INTO `razor_dim_date` VALUES ('2324', '2016-05-11', '2016', '2', '5', '19', '3', '12');
INSERT INTO `razor_dim_date` VALUES ('2325', '2016-05-12', '2016', '2', '5', '19', '4', '13');
INSERT INTO `razor_dim_date` VALUES ('2326', '2016-05-13', '2016', '2', '5', '19', '5', '14');
INSERT INTO `razor_dim_date` VALUES ('2327', '2016-05-14', '2016', '2', '5', '20', '6', '15');
INSERT INTO `razor_dim_date` VALUES ('2328', '2016-05-15', '2016', '2', '5', '20', '0', '16');
INSERT INTO `razor_dim_date` VALUES ('2329', '2016-05-16', '2016', '2', '5', '20', '1', '17');
INSERT INTO `razor_dim_date` VALUES ('2330', '2016-05-17', '2016', '2', '5', '20', '2', '18');
INSERT INTO `razor_dim_date` VALUES ('2331', '2016-05-18', '2016', '2', '5', '20', '3', '19');
INSERT INTO `razor_dim_date` VALUES ('2332', '2016-05-19', '2016', '2', '5', '20', '4', '20');
INSERT INTO `razor_dim_date` VALUES ('2333', '2016-05-20', '2016', '2', '5', '20', '5', '21');
INSERT INTO `razor_dim_date` VALUES ('2334', '2016-05-21', '2016', '2', '5', '21', '6', '22');
INSERT INTO `razor_dim_date` VALUES ('2335', '2016-05-22', '2016', '2', '5', '21', '0', '23');
INSERT INTO `razor_dim_date` VALUES ('2336', '2016-05-23', '2016', '2', '5', '21', '1', '24');
INSERT INTO `razor_dim_date` VALUES ('2337', '2016-05-24', '2016', '2', '5', '21', '2', '25');
INSERT INTO `razor_dim_date` VALUES ('2338', '2016-05-25', '2016', '2', '5', '21', '3', '26');
INSERT INTO `razor_dim_date` VALUES ('2339', '2016-05-26', '2016', '2', '5', '21', '4', '27');
INSERT INTO `razor_dim_date` VALUES ('2340', '2016-05-27', '2016', '2', '5', '21', '5', '28');
INSERT INTO `razor_dim_date` VALUES ('2341', '2016-05-28', '2016', '2', '5', '22', '6', '29');
INSERT INTO `razor_dim_date` VALUES ('2342', '2016-05-29', '2016', '2', '5', '22', '0', '30');
INSERT INTO `razor_dim_date` VALUES ('2343', '2016-05-30', '2016', '2', '5', '22', '1', '31');
INSERT INTO `razor_dim_date` VALUES ('2344', '2016-05-31', '2016', '2', '6', '22', '2', '1');
INSERT INTO `razor_dim_date` VALUES ('2345', '2016-06-01', '2016', '2', '6', '22', '3', '2');
INSERT INTO `razor_dim_date` VALUES ('2346', '2016-06-02', '2016', '2', '6', '22', '4', '3');
INSERT INTO `razor_dim_date` VALUES ('2347', '2016-06-03', '2016', '2', '6', '22', '5', '4');
INSERT INTO `razor_dim_date` VALUES ('2348', '2016-06-04', '2016', '2', '6', '23', '6', '5');
INSERT INTO `razor_dim_date` VALUES ('2349', '2016-06-05', '2016', '2', '6', '23', '0', '6');
INSERT INTO `razor_dim_date` VALUES ('2350', '2016-06-06', '2016', '2', '6', '23', '1', '7');
INSERT INTO `razor_dim_date` VALUES ('2351', '2016-06-07', '2016', '2', '6', '23', '2', '8');
INSERT INTO `razor_dim_date` VALUES ('2352', '2016-06-08', '2016', '2', '6', '23', '3', '9');
INSERT INTO `razor_dim_date` VALUES ('2353', '2016-06-09', '2016', '2', '6', '23', '4', '10');
INSERT INTO `razor_dim_date` VALUES ('2354', '2016-06-10', '2016', '2', '6', '23', '5', '11');
INSERT INTO `razor_dim_date` VALUES ('2355', '2016-06-11', '2016', '2', '6', '24', '6', '12');
INSERT INTO `razor_dim_date` VALUES ('2356', '2016-06-12', '2016', '2', '6', '24', '0', '13');
INSERT INTO `razor_dim_date` VALUES ('2357', '2016-06-13', '2016', '2', '6', '24', '1', '14');
INSERT INTO `razor_dim_date` VALUES ('2358', '2016-06-14', '2016', '2', '6', '24', '2', '15');
INSERT INTO `razor_dim_date` VALUES ('2359', '2016-06-15', '2016', '2', '6', '24', '3', '16');
INSERT INTO `razor_dim_date` VALUES ('2360', '2016-06-16', '2016', '2', '6', '24', '4', '17');
INSERT INTO `razor_dim_date` VALUES ('2361', '2016-06-17', '2016', '2', '6', '24', '5', '18');
INSERT INTO `razor_dim_date` VALUES ('2362', '2016-06-18', '2016', '2', '6', '25', '6', '19');
INSERT INTO `razor_dim_date` VALUES ('2363', '2016-06-19', '2016', '2', '6', '25', '0', '20');
INSERT INTO `razor_dim_date` VALUES ('2364', '2016-06-20', '2016', '2', '6', '25', '1', '21');
INSERT INTO `razor_dim_date` VALUES ('2365', '2016-06-21', '2016', '2', '6', '25', '2', '22');
INSERT INTO `razor_dim_date` VALUES ('2366', '2016-06-22', '2016', '2', '6', '25', '3', '23');
INSERT INTO `razor_dim_date` VALUES ('2367', '2016-06-23', '2016', '2', '6', '25', '4', '24');
INSERT INTO `razor_dim_date` VALUES ('2368', '2016-06-24', '2016', '2', '6', '25', '5', '25');
INSERT INTO `razor_dim_date` VALUES ('2369', '2016-06-25', '2016', '2', '6', '26', '6', '26');
INSERT INTO `razor_dim_date` VALUES ('2370', '2016-06-26', '2016', '2', '6', '26', '0', '27');
INSERT INTO `razor_dim_date` VALUES ('2371', '2016-06-27', '2016', '2', '6', '26', '1', '28');
INSERT INTO `razor_dim_date` VALUES ('2372', '2016-06-28', '2016', '2', '6', '26', '2', '29');
INSERT INTO `razor_dim_date` VALUES ('2373', '2016-06-29', '2016', '2', '6', '26', '3', '30');
INSERT INTO `razor_dim_date` VALUES ('2374', '2016-06-30', '2016', '3', '7', '26', '4', '1');
INSERT INTO `razor_dim_date` VALUES ('2375', '2016-07-01', '2016', '3', '7', '26', '5', '2');
INSERT INTO `razor_dim_date` VALUES ('2376', '2016-07-02', '2016', '3', '7', '27', '6', '3');
INSERT INTO `razor_dim_date` VALUES ('2377', '2016-07-03', '2016', '3', '7', '27', '0', '4');
INSERT INTO `razor_dim_date` VALUES ('2378', '2016-07-04', '2016', '3', '7', '27', '1', '5');
INSERT INTO `razor_dim_date` VALUES ('2379', '2016-07-05', '2016', '3', '7', '27', '2', '6');
INSERT INTO `razor_dim_date` VALUES ('2380', '2016-07-06', '2016', '3', '7', '27', '3', '7');
INSERT INTO `razor_dim_date` VALUES ('2381', '2016-07-07', '2016', '3', '7', '27', '4', '8');
INSERT INTO `razor_dim_date` VALUES ('2382', '2016-07-08', '2016', '3', '7', '27', '5', '9');
INSERT INTO `razor_dim_date` VALUES ('2383', '2016-07-09', '2016', '3', '7', '28', '6', '10');
INSERT INTO `razor_dim_date` VALUES ('2384', '2016-07-10', '2016', '3', '7', '28', '0', '11');
INSERT INTO `razor_dim_date` VALUES ('2385', '2016-07-11', '2016', '3', '7', '28', '1', '12');
INSERT INTO `razor_dim_date` VALUES ('2386', '2016-07-12', '2016', '3', '7', '28', '2', '13');
INSERT INTO `razor_dim_date` VALUES ('2387', '2016-07-13', '2016', '3', '7', '28', '3', '14');
INSERT INTO `razor_dim_date` VALUES ('2388', '2016-07-14', '2016', '3', '7', '28', '4', '15');
INSERT INTO `razor_dim_date` VALUES ('2389', '2016-07-15', '2016', '3', '7', '28', '5', '16');
INSERT INTO `razor_dim_date` VALUES ('2390', '2016-07-16', '2016', '3', '7', '29', '6', '17');
INSERT INTO `razor_dim_date` VALUES ('2391', '2016-07-17', '2016', '3', '7', '29', '0', '18');
INSERT INTO `razor_dim_date` VALUES ('2392', '2016-07-18', '2016', '3', '7', '29', '1', '19');
INSERT INTO `razor_dim_date` VALUES ('2393', '2016-07-19', '2016', '3', '7', '29', '2', '20');
INSERT INTO `razor_dim_date` VALUES ('2394', '2016-07-20', '2016', '3', '7', '29', '3', '21');
INSERT INTO `razor_dim_date` VALUES ('2395', '2016-07-21', '2016', '3', '7', '29', '4', '22');
INSERT INTO `razor_dim_date` VALUES ('2396', '2016-07-22', '2016', '3', '7', '29', '5', '23');
INSERT INTO `razor_dim_date` VALUES ('2397', '2016-07-23', '2016', '3', '7', '30', '6', '24');
INSERT INTO `razor_dim_date` VALUES ('2398', '2016-07-24', '2016', '3', '7', '30', '0', '25');
INSERT INTO `razor_dim_date` VALUES ('2399', '2016-07-25', '2016', '3', '7', '30', '1', '26');
INSERT INTO `razor_dim_date` VALUES ('2400', '2016-07-26', '2016', '3', '7', '30', '2', '27');
INSERT INTO `razor_dim_date` VALUES ('2401', '2016-07-27', '2016', '3', '7', '30', '3', '28');
INSERT INTO `razor_dim_date` VALUES ('2402', '2016-07-28', '2016', '3', '7', '30', '4', '29');
INSERT INTO `razor_dim_date` VALUES ('2403', '2016-07-29', '2016', '3', '7', '30', '5', '30');
INSERT INTO `razor_dim_date` VALUES ('2404', '2016-07-30', '2016', '3', '7', '31', '6', '31');
INSERT INTO `razor_dim_date` VALUES ('2405', '2016-07-31', '2016', '3', '8', '31', '0', '1');
INSERT INTO `razor_dim_date` VALUES ('2406', '2016-08-01', '2016', '3', '8', '31', '1', '2');
INSERT INTO `razor_dim_date` VALUES ('2407', '2016-08-02', '2016', '3', '8', '31', '2', '3');
INSERT INTO `razor_dim_date` VALUES ('2408', '2016-08-03', '2016', '3', '8', '31', '3', '4');
INSERT INTO `razor_dim_date` VALUES ('2409', '2016-08-04', '2016', '3', '8', '31', '4', '5');
INSERT INTO `razor_dim_date` VALUES ('2410', '2016-08-05', '2016', '3', '8', '31', '5', '6');
INSERT INTO `razor_dim_date` VALUES ('2411', '2016-08-06', '2016', '3', '8', '32', '6', '7');
INSERT INTO `razor_dim_date` VALUES ('2412', '2016-08-07', '2016', '3', '8', '32', '0', '8');
INSERT INTO `razor_dim_date` VALUES ('2413', '2016-08-08', '2016', '3', '8', '32', '1', '9');
INSERT INTO `razor_dim_date` VALUES ('2414', '2016-08-09', '2016', '3', '8', '32', '2', '10');
INSERT INTO `razor_dim_date` VALUES ('2415', '2016-08-10', '2016', '3', '8', '32', '3', '11');
INSERT INTO `razor_dim_date` VALUES ('2416', '2016-08-11', '2016', '3', '8', '32', '4', '12');
INSERT INTO `razor_dim_date` VALUES ('2417', '2016-08-12', '2016', '3', '8', '32', '5', '13');
INSERT INTO `razor_dim_date` VALUES ('2418', '2016-08-13', '2016', '3', '8', '33', '6', '14');
INSERT INTO `razor_dim_date` VALUES ('2419', '2016-08-14', '2016', '3', '8', '33', '0', '15');
INSERT INTO `razor_dim_date` VALUES ('2420', '2016-08-15', '2016', '3', '8', '33', '1', '16');
INSERT INTO `razor_dim_date` VALUES ('2421', '2016-08-16', '2016', '3', '8', '33', '2', '17');
INSERT INTO `razor_dim_date` VALUES ('2422', '2016-08-17', '2016', '3', '8', '33', '3', '18');
INSERT INTO `razor_dim_date` VALUES ('2423', '2016-08-18', '2016', '3', '8', '33', '4', '19');
INSERT INTO `razor_dim_date` VALUES ('2424', '2016-08-19', '2016', '3', '8', '33', '5', '20');
INSERT INTO `razor_dim_date` VALUES ('2425', '2016-08-20', '2016', '3', '8', '34', '6', '21');
INSERT INTO `razor_dim_date` VALUES ('2426', '2016-08-21', '2016', '3', '8', '34', '0', '22');
INSERT INTO `razor_dim_date` VALUES ('2427', '2016-08-22', '2016', '3', '8', '34', '1', '23');
INSERT INTO `razor_dim_date` VALUES ('2428', '2016-08-23', '2016', '3', '8', '34', '2', '24');
INSERT INTO `razor_dim_date` VALUES ('2429', '2016-08-24', '2016', '3', '8', '34', '3', '25');
INSERT INTO `razor_dim_date` VALUES ('2430', '2016-08-25', '2016', '3', '8', '34', '4', '26');
INSERT INTO `razor_dim_date` VALUES ('2431', '2016-08-26', '2016', '3', '8', '34', '5', '27');
INSERT INTO `razor_dim_date` VALUES ('2432', '2016-08-27', '2016', '3', '8', '35', '6', '28');
INSERT INTO `razor_dim_date` VALUES ('2433', '2016-08-28', '2016', '3', '8', '35', '0', '29');
INSERT INTO `razor_dim_date` VALUES ('2434', '2016-08-29', '2016', '3', '8', '35', '1', '30');
INSERT INTO `razor_dim_date` VALUES ('2435', '2016-08-30', '2016', '3', '8', '35', '2', '31');
INSERT INTO `razor_dim_date` VALUES ('2436', '2016-08-31', '2016', '3', '9', '35', '3', '1');
INSERT INTO `razor_dim_date` VALUES ('2437', '2016-09-01', '2016', '3', '9', '35', '4', '2');
INSERT INTO `razor_dim_date` VALUES ('2438', '2016-09-02', '2016', '3', '9', '35', '5', '3');
INSERT INTO `razor_dim_date` VALUES ('2439', '2016-09-03', '2016', '3', '9', '36', '6', '4');
INSERT INTO `razor_dim_date` VALUES ('2440', '2016-09-04', '2016', '3', '9', '36', '0', '5');
INSERT INTO `razor_dim_date` VALUES ('2441', '2016-09-05', '2016', '3', '9', '36', '1', '6');
INSERT INTO `razor_dim_date` VALUES ('2442', '2016-09-06', '2016', '3', '9', '36', '2', '7');
INSERT INTO `razor_dim_date` VALUES ('2443', '2016-09-07', '2016', '3', '9', '36', '3', '8');
INSERT INTO `razor_dim_date` VALUES ('2444', '2016-09-08', '2016', '3', '9', '36', '4', '9');
INSERT INTO `razor_dim_date` VALUES ('2445', '2016-09-09', '2016', '3', '9', '36', '5', '10');
INSERT INTO `razor_dim_date` VALUES ('2446', '2016-09-10', '2016', '3', '9', '37', '6', '11');
INSERT INTO `razor_dim_date` VALUES ('2447', '2016-09-11', '2016', '3', '9', '37', '0', '12');
INSERT INTO `razor_dim_date` VALUES ('2448', '2016-09-12', '2016', '3', '9', '37', '1', '13');
INSERT INTO `razor_dim_date` VALUES ('2449', '2016-09-13', '2016', '3', '9', '37', '2', '14');
INSERT INTO `razor_dim_date` VALUES ('2450', '2016-09-14', '2016', '3', '9', '37', '3', '15');
INSERT INTO `razor_dim_date` VALUES ('2451', '2016-09-15', '2016', '3', '9', '37', '4', '16');
INSERT INTO `razor_dim_date` VALUES ('2452', '2016-09-16', '2016', '3', '9', '37', '5', '17');
INSERT INTO `razor_dim_date` VALUES ('2453', '2016-09-17', '2016', '3', '9', '38', '6', '18');
INSERT INTO `razor_dim_date` VALUES ('2454', '2016-09-18', '2016', '3', '9', '38', '0', '19');
INSERT INTO `razor_dim_date` VALUES ('2455', '2016-09-19', '2016', '3', '9', '38', '1', '20');
INSERT INTO `razor_dim_date` VALUES ('2456', '2016-09-20', '2016', '3', '9', '38', '2', '21');
INSERT INTO `razor_dim_date` VALUES ('2457', '2016-09-21', '2016', '3', '9', '38', '3', '22');
INSERT INTO `razor_dim_date` VALUES ('2458', '2016-09-22', '2016', '3', '9', '38', '4', '23');
INSERT INTO `razor_dim_date` VALUES ('2459', '2016-09-23', '2016', '3', '9', '38', '5', '24');
INSERT INTO `razor_dim_date` VALUES ('2460', '2016-09-24', '2016', '3', '9', '39', '6', '25');
INSERT INTO `razor_dim_date` VALUES ('2461', '2016-09-25', '2016', '3', '9', '39', '0', '26');
INSERT INTO `razor_dim_date` VALUES ('2462', '2016-09-26', '2016', '3', '9', '39', '1', '27');
INSERT INTO `razor_dim_date` VALUES ('2463', '2016-09-27', '2016', '3', '9', '39', '2', '28');
INSERT INTO `razor_dim_date` VALUES ('2464', '2016-09-28', '2016', '3', '9', '39', '3', '29');
INSERT INTO `razor_dim_date` VALUES ('2465', '2016-09-29', '2016', '3', '9', '39', '4', '30');
INSERT INTO `razor_dim_date` VALUES ('2466', '2016-09-30', '2016', '4', '10', '39', '5', '1');
INSERT INTO `razor_dim_date` VALUES ('2467', '2016-10-01', '2016', '4', '10', '40', '6', '2');
INSERT INTO `razor_dim_date` VALUES ('2468', '2016-10-02', '2016', '4', '10', '40', '0', '3');
INSERT INTO `razor_dim_date` VALUES ('2469', '2016-10-03', '2016', '4', '10', '40', '1', '4');
INSERT INTO `razor_dim_date` VALUES ('2470', '2016-10-04', '2016', '4', '10', '40', '2', '5');
INSERT INTO `razor_dim_date` VALUES ('2471', '2016-10-05', '2016', '4', '10', '40', '3', '6');
INSERT INTO `razor_dim_date` VALUES ('2472', '2016-10-06', '2016', '4', '10', '40', '4', '7');
INSERT INTO `razor_dim_date` VALUES ('2473', '2016-10-07', '2016', '4', '10', '40', '5', '8');
INSERT INTO `razor_dim_date` VALUES ('2474', '2016-10-08', '2016', '4', '10', '41', '6', '9');
INSERT INTO `razor_dim_date` VALUES ('2475', '2016-10-09', '2016', '4', '10', '41', '0', '10');
INSERT INTO `razor_dim_date` VALUES ('2476', '2016-10-10', '2016', '4', '10', '41', '1', '11');
INSERT INTO `razor_dim_date` VALUES ('2477', '2016-10-11', '2016', '4', '10', '41', '2', '12');
INSERT INTO `razor_dim_date` VALUES ('2478', '2016-10-12', '2016', '4', '10', '41', '3', '13');
INSERT INTO `razor_dim_date` VALUES ('2479', '2016-10-13', '2016', '4', '10', '41', '4', '14');
INSERT INTO `razor_dim_date` VALUES ('2480', '2016-10-14', '2016', '4', '10', '41', '5', '15');
INSERT INTO `razor_dim_date` VALUES ('2481', '2016-10-15', '2016', '4', '10', '42', '6', '16');
INSERT INTO `razor_dim_date` VALUES ('2482', '2016-10-16', '2016', '4', '10', '42', '0', '17');
INSERT INTO `razor_dim_date` VALUES ('2483', '2016-10-17', '2016', '4', '10', '42', '1', '18');
INSERT INTO `razor_dim_date` VALUES ('2484', '2016-10-18', '2016', '4', '10', '42', '2', '19');
INSERT INTO `razor_dim_date` VALUES ('2485', '2016-10-19', '2016', '4', '10', '42', '3', '20');
INSERT INTO `razor_dim_date` VALUES ('2486', '2016-10-20', '2016', '4', '10', '42', '4', '21');
INSERT INTO `razor_dim_date` VALUES ('2487', '2016-10-21', '2016', '4', '10', '42', '5', '22');
INSERT INTO `razor_dim_date` VALUES ('2488', '2016-10-22', '2016', '4', '10', '43', '6', '23');
INSERT INTO `razor_dim_date` VALUES ('2489', '2016-10-23', '2016', '4', '10', '43', '0', '24');
INSERT INTO `razor_dim_date` VALUES ('2490', '2016-10-24', '2016', '4', '10', '43', '1', '25');
INSERT INTO `razor_dim_date` VALUES ('2491', '2016-10-25', '2016', '4', '10', '43', '2', '26');
INSERT INTO `razor_dim_date` VALUES ('2492', '2016-10-26', '2016', '4', '10', '43', '3', '27');
INSERT INTO `razor_dim_date` VALUES ('2493', '2016-10-27', '2016', '4', '10', '43', '4', '28');
INSERT INTO `razor_dim_date` VALUES ('2494', '2016-10-28', '2016', '4', '10', '43', '5', '29');
INSERT INTO `razor_dim_date` VALUES ('2495', '2016-10-29', '2016', '4', '10', '44', '6', '30');
INSERT INTO `razor_dim_date` VALUES ('2496', '2016-10-30', '2016', '4', '10', '44', '0', '31');
INSERT INTO `razor_dim_date` VALUES ('2497', '2016-10-31', '2016', '4', '11', '44', '1', '1');
INSERT INTO `razor_dim_date` VALUES ('2498', '2016-11-01', '2016', '4', '11', '44', '2', '2');
INSERT INTO `razor_dim_date` VALUES ('2499', '2016-11-02', '2016', '4', '11', '44', '3', '3');
INSERT INTO `razor_dim_date` VALUES ('2500', '2016-11-03', '2016', '4', '11', '44', '4', '4');
INSERT INTO `razor_dim_date` VALUES ('2501', '2016-11-04', '2016', '4', '11', '44', '5', '5');
INSERT INTO `razor_dim_date` VALUES ('2502', '2016-11-05', '2016', '4', '11', '45', '6', '6');
INSERT INTO `razor_dim_date` VALUES ('2503', '2016-11-06', '2016', '4', '11', '45', '0', '7');
INSERT INTO `razor_dim_date` VALUES ('2504', '2016-11-07', '2016', '4', '11', '45', '1', '8');
INSERT INTO `razor_dim_date` VALUES ('2505', '2016-11-08', '2016', '4', '11', '45', '2', '9');
INSERT INTO `razor_dim_date` VALUES ('2506', '2016-11-09', '2016', '4', '11', '45', '3', '10');
INSERT INTO `razor_dim_date` VALUES ('2507', '2016-11-10', '2016', '4', '11', '45', '4', '11');
INSERT INTO `razor_dim_date` VALUES ('2508', '2016-11-11', '2016', '4', '11', '45', '5', '12');
INSERT INTO `razor_dim_date` VALUES ('2509', '2016-11-12', '2016', '4', '11', '46', '6', '13');
INSERT INTO `razor_dim_date` VALUES ('2510', '2016-11-13', '2016', '4', '11', '46', '0', '14');
INSERT INTO `razor_dim_date` VALUES ('2511', '2016-11-14', '2016', '4', '11', '46', '1', '15');
INSERT INTO `razor_dim_date` VALUES ('2512', '2016-11-15', '2016', '4', '11', '46', '2', '16');
INSERT INTO `razor_dim_date` VALUES ('2513', '2016-11-16', '2016', '4', '11', '46', '3', '17');
INSERT INTO `razor_dim_date` VALUES ('2514', '2016-11-17', '2016', '4', '11', '46', '4', '18');
INSERT INTO `razor_dim_date` VALUES ('2515', '2016-11-18', '2016', '4', '11', '46', '5', '19');
INSERT INTO `razor_dim_date` VALUES ('2516', '2016-11-19', '2016', '4', '11', '47', '6', '20');
INSERT INTO `razor_dim_date` VALUES ('2517', '2016-11-20', '2016', '4', '11', '47', '0', '21');
INSERT INTO `razor_dim_date` VALUES ('2518', '2016-11-21', '2016', '4', '11', '47', '1', '22');
INSERT INTO `razor_dim_date` VALUES ('2519', '2016-11-22', '2016', '4', '11', '47', '2', '23');
INSERT INTO `razor_dim_date` VALUES ('2520', '2016-11-23', '2016', '4', '11', '47', '3', '24');
INSERT INTO `razor_dim_date` VALUES ('2521', '2016-11-24', '2016', '4', '11', '47', '4', '25');
INSERT INTO `razor_dim_date` VALUES ('2522', '2016-11-25', '2016', '4', '11', '47', '5', '26');
INSERT INTO `razor_dim_date` VALUES ('2523', '2016-11-26', '2016', '4', '11', '48', '6', '27');
INSERT INTO `razor_dim_date` VALUES ('2524', '2016-11-27', '2016', '4', '11', '48', '0', '28');
INSERT INTO `razor_dim_date` VALUES ('2525', '2016-11-28', '2016', '4', '11', '48', '1', '29');
INSERT INTO `razor_dim_date` VALUES ('2526', '2016-11-29', '2016', '4', '11', '48', '2', '30');
INSERT INTO `razor_dim_date` VALUES ('2527', '2016-11-30', '2016', '4', '12', '48', '3', '1');
INSERT INTO `razor_dim_date` VALUES ('2528', '2016-12-01', '2016', '4', '12', '48', '4', '2');
INSERT INTO `razor_dim_date` VALUES ('2529', '2016-12-02', '2016', '4', '12', '48', '5', '3');
INSERT INTO `razor_dim_date` VALUES ('2530', '2016-12-03', '2016', '4', '12', '49', '6', '4');
INSERT INTO `razor_dim_date` VALUES ('2531', '2016-12-04', '2016', '4', '12', '49', '0', '5');
INSERT INTO `razor_dim_date` VALUES ('2532', '2016-12-05', '2016', '4', '12', '49', '1', '6');
INSERT INTO `razor_dim_date` VALUES ('2533', '2016-12-06', '2016', '4', '12', '49', '2', '7');
INSERT INTO `razor_dim_date` VALUES ('2534', '2016-12-07', '2016', '4', '12', '49', '3', '8');
INSERT INTO `razor_dim_date` VALUES ('2535', '2016-12-08', '2016', '4', '12', '49', '4', '9');
INSERT INTO `razor_dim_date` VALUES ('2536', '2016-12-09', '2016', '4', '12', '49', '5', '10');
INSERT INTO `razor_dim_date` VALUES ('2537', '2016-12-10', '2016', '4', '12', '50', '6', '11');
INSERT INTO `razor_dim_date` VALUES ('2538', '2016-12-11', '2016', '4', '12', '50', '0', '12');
INSERT INTO `razor_dim_date` VALUES ('2539', '2016-12-12', '2016', '4', '12', '50', '1', '13');
INSERT INTO `razor_dim_date` VALUES ('2540', '2016-12-13', '2016', '4', '12', '50', '2', '14');
INSERT INTO `razor_dim_date` VALUES ('2541', '2016-12-14', '2016', '4', '12', '50', '3', '15');
INSERT INTO `razor_dim_date` VALUES ('2542', '2016-12-15', '2016', '4', '12', '50', '4', '16');
INSERT INTO `razor_dim_date` VALUES ('2543', '2016-12-16', '2016', '4', '12', '50', '5', '17');
INSERT INTO `razor_dim_date` VALUES ('2544', '2016-12-17', '2016', '4', '12', '51', '6', '18');
INSERT INTO `razor_dim_date` VALUES ('2545', '2016-12-18', '2016', '4', '12', '51', '0', '19');
INSERT INTO `razor_dim_date` VALUES ('2546', '2016-12-19', '2016', '4', '12', '51', '1', '20');
INSERT INTO `razor_dim_date` VALUES ('2547', '2016-12-20', '2016', '4', '12', '51', '2', '21');
INSERT INTO `razor_dim_date` VALUES ('2548', '2016-12-21', '2016', '4', '12', '51', '3', '22');
INSERT INTO `razor_dim_date` VALUES ('2549', '2016-12-22', '2016', '4', '12', '51', '4', '23');
INSERT INTO `razor_dim_date` VALUES ('2550', '2016-12-23', '2016', '4', '12', '51', '5', '24');
INSERT INTO `razor_dim_date` VALUES ('2551', '2016-12-24', '2016', '4', '12', '52', '6', '25');
INSERT INTO `razor_dim_date` VALUES ('2552', '2016-12-25', '2016', '4', '12', '52', '0', '26');
INSERT INTO `razor_dim_date` VALUES ('2553', '2016-12-26', '2016', '4', '12', '52', '1', '27');
INSERT INTO `razor_dim_date` VALUES ('2554', '2016-12-27', '2016', '4', '12', '52', '2', '28');
INSERT INTO `razor_dim_date` VALUES ('2555', '2016-12-28', '2016', '4', '12', '52', '3', '29');
INSERT INTO `razor_dim_date` VALUES ('2556', '2016-12-29', '2016', '4', '12', '52', '4', '30');
INSERT INTO `razor_dim_date` VALUES ('2557', '2016-12-30', '2016', '4', '12', '52', '5', '31');
INSERT INTO `razor_dim_date` VALUES ('2558', '2016-12-31', '2017', '1', '1', '1', '6', '1');
INSERT INTO `razor_dim_date` VALUES ('2559', '2017-01-01', '2017', '1', '1', '1', '0', '2');
INSERT INTO `razor_dim_date` VALUES ('2560', '2017-01-02', '2017', '1', '1', '1', '1', '3');
INSERT INTO `razor_dim_date` VALUES ('2561', '2017-01-03', '2017', '1', '1', '1', '2', '4');
INSERT INTO `razor_dim_date` VALUES ('2562', '2017-01-04', '2017', '1', '1', '1', '3', '5');
INSERT INTO `razor_dim_date` VALUES ('2563', '2017-01-05', '2017', '1', '1', '1', '4', '6');
INSERT INTO `razor_dim_date` VALUES ('2564', '2017-01-06', '2017', '1', '1', '1', '5', '7');
INSERT INTO `razor_dim_date` VALUES ('2565', '2017-01-07', '2017', '1', '1', '2', '6', '8');
INSERT INTO `razor_dim_date` VALUES ('2566', '2017-01-08', '2017', '1', '1', '2', '0', '9');
INSERT INTO `razor_dim_date` VALUES ('2567', '2017-01-09', '2017', '1', '1', '2', '1', '10');
INSERT INTO `razor_dim_date` VALUES ('2568', '2017-01-10', '2017', '1', '1', '2', '2', '11');
INSERT INTO `razor_dim_date` VALUES ('2569', '2017-01-11', '2017', '1', '1', '2', '3', '12');
INSERT INTO `razor_dim_date` VALUES ('2570', '2017-01-12', '2017', '1', '1', '2', '4', '13');
INSERT INTO `razor_dim_date` VALUES ('2571', '2017-01-13', '2017', '1', '1', '2', '5', '14');
INSERT INTO `razor_dim_date` VALUES ('2572', '2017-01-14', '2017', '1', '1', '3', '6', '15');
INSERT INTO `razor_dim_date` VALUES ('2573', '2017-01-15', '2017', '1', '1', '3', '0', '16');
INSERT INTO `razor_dim_date` VALUES ('2574', '2017-01-16', '2017', '1', '1', '3', '1', '17');
INSERT INTO `razor_dim_date` VALUES ('2575', '2017-01-17', '2017', '1', '1', '3', '2', '18');
INSERT INTO `razor_dim_date` VALUES ('2576', '2017-01-18', '2017', '1', '1', '3', '3', '19');
INSERT INTO `razor_dim_date` VALUES ('2577', '2017-01-19', '2017', '1', '1', '3', '4', '20');
INSERT INTO `razor_dim_date` VALUES ('2578', '2017-01-20', '2017', '1', '1', '3', '5', '21');
INSERT INTO `razor_dim_date` VALUES ('2579', '2017-01-21', '2017', '1', '1', '4', '6', '22');
INSERT INTO `razor_dim_date` VALUES ('2580', '2017-01-22', '2017', '1', '1', '4', '0', '23');
INSERT INTO `razor_dim_date` VALUES ('2581', '2017-01-23', '2017', '1', '1', '4', '1', '24');
INSERT INTO `razor_dim_date` VALUES ('2582', '2017-01-24', '2017', '1', '1', '4', '2', '25');
INSERT INTO `razor_dim_date` VALUES ('2583', '2017-01-25', '2017', '1', '1', '4', '3', '26');
INSERT INTO `razor_dim_date` VALUES ('2584', '2017-01-26', '2017', '1', '1', '4', '4', '27');
INSERT INTO `razor_dim_date` VALUES ('2585', '2017-01-27', '2017', '1', '1', '4', '5', '28');
INSERT INTO `razor_dim_date` VALUES ('2586', '2017-01-28', '2017', '1', '1', '5', '6', '29');
INSERT INTO `razor_dim_date` VALUES ('2587', '2017-01-29', '2017', '1', '1', '5', '0', '30');
INSERT INTO `razor_dim_date` VALUES ('2588', '2017-01-30', '2017', '1', '1', '5', '1', '31');
INSERT INTO `razor_dim_date` VALUES ('2589', '2017-01-31', '2017', '1', '2', '5', '2', '1');
INSERT INTO `razor_dim_date` VALUES ('2590', '2017-02-01', '2017', '1', '2', '5', '3', '2');
INSERT INTO `razor_dim_date` VALUES ('2591', '2017-02-02', '2017', '1', '2', '5', '4', '3');
INSERT INTO `razor_dim_date` VALUES ('2592', '2017-02-03', '2017', '1', '2', '5', '5', '4');
INSERT INTO `razor_dim_date` VALUES ('2593', '2017-02-04', '2017', '1', '2', '6', '6', '5');
INSERT INTO `razor_dim_date` VALUES ('2594', '2017-02-05', '2017', '1', '2', '6', '0', '6');
INSERT INTO `razor_dim_date` VALUES ('2595', '2017-02-06', '2017', '1', '2', '6', '1', '7');
INSERT INTO `razor_dim_date` VALUES ('2596', '2017-02-07', '2017', '1', '2', '6', '2', '8');
INSERT INTO `razor_dim_date` VALUES ('2597', '2017-02-08', '2017', '1', '2', '6', '3', '9');
INSERT INTO `razor_dim_date` VALUES ('2598', '2017-02-09', '2017', '1', '2', '6', '4', '10');
INSERT INTO `razor_dim_date` VALUES ('2599', '2017-02-10', '2017', '1', '2', '6', '5', '11');
INSERT INTO `razor_dim_date` VALUES ('2600', '2017-02-11', '2017', '1', '2', '7', '6', '12');
INSERT INTO `razor_dim_date` VALUES ('2601', '2017-02-12', '2017', '1', '2', '7', '0', '13');
INSERT INTO `razor_dim_date` VALUES ('2602', '2017-02-13', '2017', '1', '2', '7', '1', '14');
INSERT INTO `razor_dim_date` VALUES ('2603', '2017-02-14', '2017', '1', '2', '7', '2', '15');
INSERT INTO `razor_dim_date` VALUES ('2604', '2017-02-15', '2017', '1', '2', '7', '3', '16');
INSERT INTO `razor_dim_date` VALUES ('2605', '2017-02-16', '2017', '1', '2', '7', '4', '17');
INSERT INTO `razor_dim_date` VALUES ('2606', '2017-02-17', '2017', '1', '2', '7', '5', '18');
INSERT INTO `razor_dim_date` VALUES ('2607', '2017-02-18', '2017', '1', '2', '8', '6', '19');
INSERT INTO `razor_dim_date` VALUES ('2608', '2017-02-19', '2017', '1', '2', '8', '0', '20');
INSERT INTO `razor_dim_date` VALUES ('2609', '2017-02-20', '2017', '1', '2', '8', '1', '21');
INSERT INTO `razor_dim_date` VALUES ('2610', '2017-02-21', '2017', '1', '2', '8', '2', '22');
INSERT INTO `razor_dim_date` VALUES ('2611', '2017-02-22', '2017', '1', '2', '8', '3', '23');
INSERT INTO `razor_dim_date` VALUES ('2612', '2017-02-23', '2017', '1', '2', '8', '4', '24');
INSERT INTO `razor_dim_date` VALUES ('2613', '2017-02-24', '2017', '1', '2', '8', '5', '25');
INSERT INTO `razor_dim_date` VALUES ('2614', '2017-02-25', '2017', '1', '2', '9', '6', '26');
INSERT INTO `razor_dim_date` VALUES ('2615', '2017-02-26', '2017', '1', '2', '9', '0', '27');
INSERT INTO `razor_dim_date` VALUES ('2616', '2017-02-27', '2017', '1', '2', '9', '1', '28');
INSERT INTO `razor_dim_date` VALUES ('2617', '2017-02-28', '2017', '1', '3', '9', '2', '1');
INSERT INTO `razor_dim_date` VALUES ('2618', '2017-03-01', '2017', '1', '3', '9', '3', '2');
INSERT INTO `razor_dim_date` VALUES ('2619', '2017-03-02', '2017', '1', '3', '9', '4', '3');
INSERT INTO `razor_dim_date` VALUES ('2620', '2017-03-03', '2017', '1', '3', '9', '5', '4');
INSERT INTO `razor_dim_date` VALUES ('2621', '2017-03-04', '2017', '1', '3', '10', '6', '5');
INSERT INTO `razor_dim_date` VALUES ('2622', '2017-03-05', '2017', '1', '3', '10', '0', '6');
INSERT INTO `razor_dim_date` VALUES ('2623', '2017-03-06', '2017', '1', '3', '10', '1', '7');
INSERT INTO `razor_dim_date` VALUES ('2624', '2017-03-07', '2017', '1', '3', '10', '2', '8');
INSERT INTO `razor_dim_date` VALUES ('2625', '2017-03-08', '2017', '1', '3', '10', '3', '9');
INSERT INTO `razor_dim_date` VALUES ('2626', '2017-03-09', '2017', '1', '3', '10', '4', '10');
INSERT INTO `razor_dim_date` VALUES ('2627', '2017-03-10', '2017', '1', '3', '10', '5', '11');
INSERT INTO `razor_dim_date` VALUES ('2628', '2017-03-11', '2017', '1', '3', '11', '6', '12');
INSERT INTO `razor_dim_date` VALUES ('2629', '2017-03-12', '2017', '1', '3', '11', '0', '13');
INSERT INTO `razor_dim_date` VALUES ('2630', '2017-03-13', '2017', '1', '3', '11', '1', '14');
INSERT INTO `razor_dim_date` VALUES ('2631', '2017-03-14', '2017', '1', '3', '11', '2', '15');
INSERT INTO `razor_dim_date` VALUES ('2632', '2017-03-15', '2017', '1', '3', '11', '3', '16');
INSERT INTO `razor_dim_date` VALUES ('2633', '2017-03-16', '2017', '1', '3', '11', '4', '17');
INSERT INTO `razor_dim_date` VALUES ('2634', '2017-03-17', '2017', '1', '3', '11', '5', '18');
INSERT INTO `razor_dim_date` VALUES ('2635', '2017-03-18', '2017', '1', '3', '12', '6', '19');
INSERT INTO `razor_dim_date` VALUES ('2636', '2017-03-19', '2017', '1', '3', '12', '0', '20');
INSERT INTO `razor_dim_date` VALUES ('2637', '2017-03-20', '2017', '1', '3', '12', '1', '21');
INSERT INTO `razor_dim_date` VALUES ('2638', '2017-03-21', '2017', '1', '3', '12', '2', '22');
INSERT INTO `razor_dim_date` VALUES ('2639', '2017-03-22', '2017', '1', '3', '12', '3', '23');
INSERT INTO `razor_dim_date` VALUES ('2640', '2017-03-23', '2017', '1', '3', '12', '4', '24');
INSERT INTO `razor_dim_date` VALUES ('2641', '2017-03-24', '2017', '1', '3', '12', '5', '25');
INSERT INTO `razor_dim_date` VALUES ('2642', '2017-03-25', '2017', '1', '3', '13', '6', '26');
INSERT INTO `razor_dim_date` VALUES ('2643', '2017-03-26', '2017', '1', '3', '13', '0', '27');
INSERT INTO `razor_dim_date` VALUES ('2644', '2017-03-27', '2017', '1', '3', '13', '1', '28');
INSERT INTO `razor_dim_date` VALUES ('2645', '2017-03-28', '2017', '1', '3', '13', '2', '29');
INSERT INTO `razor_dim_date` VALUES ('2646', '2017-03-29', '2017', '1', '3', '13', '3', '30');
INSERT INTO `razor_dim_date` VALUES ('2647', '2017-03-30', '2017', '1', '3', '13', '4', '31');
INSERT INTO `razor_dim_date` VALUES ('2648', '2017-03-31', '2017', '2', '4', '13', '5', '1');
INSERT INTO `razor_dim_date` VALUES ('2649', '2017-04-01', '2017', '2', '4', '14', '6', '2');
INSERT INTO `razor_dim_date` VALUES ('2650', '2017-04-02', '2017', '2', '4', '14', '0', '3');
INSERT INTO `razor_dim_date` VALUES ('2651', '2017-04-03', '2017', '2', '4', '14', '1', '4');
INSERT INTO `razor_dim_date` VALUES ('2652', '2017-04-04', '2017', '2', '4', '14', '2', '5');
INSERT INTO `razor_dim_date` VALUES ('2653', '2017-04-05', '2017', '2', '4', '14', '3', '6');
INSERT INTO `razor_dim_date` VALUES ('2654', '2017-04-06', '2017', '2', '4', '14', '4', '7');
INSERT INTO `razor_dim_date` VALUES ('2655', '2017-04-07', '2017', '2', '4', '14', '5', '8');
INSERT INTO `razor_dim_date` VALUES ('2656', '2017-04-08', '2017', '2', '4', '15', '6', '9');
INSERT INTO `razor_dim_date` VALUES ('2657', '2017-04-09', '2017', '2', '4', '15', '0', '10');
INSERT INTO `razor_dim_date` VALUES ('2658', '2017-04-10', '2017', '2', '4', '15', '1', '11');
INSERT INTO `razor_dim_date` VALUES ('2659', '2017-04-11', '2017', '2', '4', '15', '2', '12');
INSERT INTO `razor_dim_date` VALUES ('2660', '2017-04-12', '2017', '2', '4', '15', '3', '13');
INSERT INTO `razor_dim_date` VALUES ('2661', '2017-04-13', '2017', '2', '4', '15', '4', '14');
INSERT INTO `razor_dim_date` VALUES ('2662', '2017-04-14', '2017', '2', '4', '15', '5', '15');
INSERT INTO `razor_dim_date` VALUES ('2663', '2017-04-15', '2017', '2', '4', '16', '6', '16');
INSERT INTO `razor_dim_date` VALUES ('2664', '2017-04-16', '2017', '2', '4', '16', '0', '17');
INSERT INTO `razor_dim_date` VALUES ('2665', '2017-04-17', '2017', '2', '4', '16', '1', '18');
INSERT INTO `razor_dim_date` VALUES ('2666', '2017-04-18', '2017', '2', '4', '16', '2', '19');
INSERT INTO `razor_dim_date` VALUES ('2667', '2017-04-19', '2017', '2', '4', '16', '3', '20');
INSERT INTO `razor_dim_date` VALUES ('2668', '2017-04-20', '2017', '2', '4', '16', '4', '21');
INSERT INTO `razor_dim_date` VALUES ('2669', '2017-04-21', '2017', '2', '4', '16', '5', '22');
INSERT INTO `razor_dim_date` VALUES ('2670', '2017-04-22', '2017', '2', '4', '17', '6', '23');
INSERT INTO `razor_dim_date` VALUES ('2671', '2017-04-23', '2017', '2', '4', '17', '0', '24');
INSERT INTO `razor_dim_date` VALUES ('2672', '2017-04-24', '2017', '2', '4', '17', '1', '25');
INSERT INTO `razor_dim_date` VALUES ('2673', '2017-04-25', '2017', '2', '4', '17', '2', '26');
INSERT INTO `razor_dim_date` VALUES ('2674', '2017-04-26', '2017', '2', '4', '17', '3', '27');
INSERT INTO `razor_dim_date` VALUES ('2675', '2017-04-27', '2017', '2', '4', '17', '4', '28');
INSERT INTO `razor_dim_date` VALUES ('2676', '2017-04-28', '2017', '2', '4', '17', '5', '29');
INSERT INTO `razor_dim_date` VALUES ('2677', '2017-04-29', '2017', '2', '4', '18', '6', '30');
INSERT INTO `razor_dim_date` VALUES ('2678', '2017-04-30', '2017', '2', '5', '18', '0', '1');
INSERT INTO `razor_dim_date` VALUES ('2679', '2017-05-01', '2017', '2', '5', '18', '1', '2');
INSERT INTO `razor_dim_date` VALUES ('2680', '2017-05-02', '2017', '2', '5', '18', '2', '3');
INSERT INTO `razor_dim_date` VALUES ('2681', '2017-05-03', '2017', '2', '5', '18', '3', '4');
INSERT INTO `razor_dim_date` VALUES ('2682', '2017-05-04', '2017', '2', '5', '18', '4', '5');
INSERT INTO `razor_dim_date` VALUES ('2683', '2017-05-05', '2017', '2', '5', '18', '5', '6');
INSERT INTO `razor_dim_date` VALUES ('2684', '2017-05-06', '2017', '2', '5', '19', '6', '7');
INSERT INTO `razor_dim_date` VALUES ('2685', '2017-05-07', '2017', '2', '5', '19', '0', '8');
INSERT INTO `razor_dim_date` VALUES ('2686', '2017-05-08', '2017', '2', '5', '19', '1', '9');
INSERT INTO `razor_dim_date` VALUES ('2687', '2017-05-09', '2017', '2', '5', '19', '2', '10');
INSERT INTO `razor_dim_date` VALUES ('2688', '2017-05-10', '2017', '2', '5', '19', '3', '11');
INSERT INTO `razor_dim_date` VALUES ('2689', '2017-05-11', '2017', '2', '5', '19', '4', '12');
INSERT INTO `razor_dim_date` VALUES ('2690', '2017-05-12', '2017', '2', '5', '19', '5', '13');
INSERT INTO `razor_dim_date` VALUES ('2691', '2017-05-13', '2017', '2', '5', '20', '6', '14');
INSERT INTO `razor_dim_date` VALUES ('2692', '2017-05-14', '2017', '2', '5', '20', '0', '15');
INSERT INTO `razor_dim_date` VALUES ('2693', '2017-05-15', '2017', '2', '5', '20', '1', '16');
INSERT INTO `razor_dim_date` VALUES ('2694', '2017-05-16', '2017', '2', '5', '20', '2', '17');
INSERT INTO `razor_dim_date` VALUES ('2695', '2017-05-17', '2017', '2', '5', '20', '3', '18');
INSERT INTO `razor_dim_date` VALUES ('2696', '2017-05-18', '2017', '2', '5', '20', '4', '19');
INSERT INTO `razor_dim_date` VALUES ('2697', '2017-05-19', '2017', '2', '5', '20', '5', '20');
INSERT INTO `razor_dim_date` VALUES ('2698', '2017-05-20', '2017', '2', '5', '21', '6', '21');
INSERT INTO `razor_dim_date` VALUES ('2699', '2017-05-21', '2017', '2', '5', '21', '0', '22');
INSERT INTO `razor_dim_date` VALUES ('2700', '2017-05-22', '2017', '2', '5', '21', '1', '23');
INSERT INTO `razor_dim_date` VALUES ('2701', '2017-05-23', '2017', '2', '5', '21', '2', '24');
INSERT INTO `razor_dim_date` VALUES ('2702', '2017-05-24', '2017', '2', '5', '21', '3', '25');
INSERT INTO `razor_dim_date` VALUES ('2703', '2017-05-25', '2017', '2', '5', '21', '4', '26');
INSERT INTO `razor_dim_date` VALUES ('2704', '2017-05-26', '2017', '2', '5', '21', '5', '27');
INSERT INTO `razor_dim_date` VALUES ('2705', '2017-05-27', '2017', '2', '5', '22', '6', '28');
INSERT INTO `razor_dim_date` VALUES ('2706', '2017-05-28', '2017', '2', '5', '22', '0', '29');
INSERT INTO `razor_dim_date` VALUES ('2707', '2017-05-29', '2017', '2', '5', '22', '1', '30');
INSERT INTO `razor_dim_date` VALUES ('2708', '2017-05-30', '2017', '2', '5', '22', '2', '31');
INSERT INTO `razor_dim_date` VALUES ('2709', '2017-05-31', '2017', '2', '6', '22', '3', '1');
INSERT INTO `razor_dim_date` VALUES ('2710', '2017-06-01', '2017', '2', '6', '22', '4', '2');
INSERT INTO `razor_dim_date` VALUES ('2711', '2017-06-02', '2017', '2', '6', '22', '5', '3');
INSERT INTO `razor_dim_date` VALUES ('2712', '2017-06-03', '2017', '2', '6', '23', '6', '4');
INSERT INTO `razor_dim_date` VALUES ('2713', '2017-06-04', '2017', '2', '6', '23', '0', '5');
INSERT INTO `razor_dim_date` VALUES ('2714', '2017-06-05', '2017', '2', '6', '23', '1', '6');
INSERT INTO `razor_dim_date` VALUES ('2715', '2017-06-06', '2017', '2', '6', '23', '2', '7');
INSERT INTO `razor_dim_date` VALUES ('2716', '2017-06-07', '2017', '2', '6', '23', '3', '8');
INSERT INTO `razor_dim_date` VALUES ('2717', '2017-06-08', '2017', '2', '6', '23', '4', '9');
INSERT INTO `razor_dim_date` VALUES ('2718', '2017-06-09', '2017', '2', '6', '23', '5', '10');
INSERT INTO `razor_dim_date` VALUES ('2719', '2017-06-10', '2017', '2', '6', '24', '6', '11');
INSERT INTO `razor_dim_date` VALUES ('2720', '2017-06-11', '2017', '2', '6', '24', '0', '12');
INSERT INTO `razor_dim_date` VALUES ('2721', '2017-06-12', '2017', '2', '6', '24', '1', '13');
INSERT INTO `razor_dim_date` VALUES ('2722', '2017-06-13', '2017', '2', '6', '24', '2', '14');
INSERT INTO `razor_dim_date` VALUES ('2723', '2017-06-14', '2017', '2', '6', '24', '3', '15');
INSERT INTO `razor_dim_date` VALUES ('2724', '2017-06-15', '2017', '2', '6', '24', '4', '16');
INSERT INTO `razor_dim_date` VALUES ('2725', '2017-06-16', '2017', '2', '6', '24', '5', '17');
INSERT INTO `razor_dim_date` VALUES ('2726', '2017-06-17', '2017', '2', '6', '25', '6', '18');
INSERT INTO `razor_dim_date` VALUES ('2727', '2017-06-18', '2017', '2', '6', '25', '0', '19');
INSERT INTO `razor_dim_date` VALUES ('2728', '2017-06-19', '2017', '2', '6', '25', '1', '20');
INSERT INTO `razor_dim_date` VALUES ('2729', '2017-06-20', '2017', '2', '6', '25', '2', '21');
INSERT INTO `razor_dim_date` VALUES ('2730', '2017-06-21', '2017', '2', '6', '25', '3', '22');
INSERT INTO `razor_dim_date` VALUES ('2731', '2017-06-22', '2017', '2', '6', '25', '4', '23');
INSERT INTO `razor_dim_date` VALUES ('2732', '2017-06-23', '2017', '2', '6', '25', '5', '24');
INSERT INTO `razor_dim_date` VALUES ('2733', '2017-06-24', '2017', '2', '6', '26', '6', '25');
INSERT INTO `razor_dim_date` VALUES ('2734', '2017-06-25', '2017', '2', '6', '26', '0', '26');
INSERT INTO `razor_dim_date` VALUES ('2735', '2017-06-26', '2017', '2', '6', '26', '1', '27');
INSERT INTO `razor_dim_date` VALUES ('2736', '2017-06-27', '2017', '2', '6', '26', '2', '28');
INSERT INTO `razor_dim_date` VALUES ('2737', '2017-06-28', '2017', '2', '6', '26', '3', '29');
INSERT INTO `razor_dim_date` VALUES ('2738', '2017-06-29', '2017', '2', '6', '26', '4', '30');
INSERT INTO `razor_dim_date` VALUES ('2739', '2017-06-30', '2017', '3', '7', '26', '5', '1');
INSERT INTO `razor_dim_date` VALUES ('2740', '2017-07-01', '2017', '3', '7', '27', '6', '2');
INSERT INTO `razor_dim_date` VALUES ('2741', '2017-07-02', '2017', '3', '7', '27', '0', '3');
INSERT INTO `razor_dim_date` VALUES ('2742', '2017-07-03', '2017', '3', '7', '27', '1', '4');
INSERT INTO `razor_dim_date` VALUES ('2743', '2017-07-04', '2017', '3', '7', '27', '2', '5');
INSERT INTO `razor_dim_date` VALUES ('2744', '2017-07-05', '2017', '3', '7', '27', '3', '6');
INSERT INTO `razor_dim_date` VALUES ('2745', '2017-07-06', '2017', '3', '7', '27', '4', '7');
INSERT INTO `razor_dim_date` VALUES ('2746', '2017-07-07', '2017', '3', '7', '27', '5', '8');
INSERT INTO `razor_dim_date` VALUES ('2747', '2017-07-08', '2017', '3', '7', '28', '6', '9');
INSERT INTO `razor_dim_date` VALUES ('2748', '2017-07-09', '2017', '3', '7', '28', '0', '10');
INSERT INTO `razor_dim_date` VALUES ('2749', '2017-07-10', '2017', '3', '7', '28', '1', '11');
INSERT INTO `razor_dim_date` VALUES ('2750', '2017-07-11', '2017', '3', '7', '28', '2', '12');
INSERT INTO `razor_dim_date` VALUES ('2751', '2017-07-12', '2017', '3', '7', '28', '3', '13');
INSERT INTO `razor_dim_date` VALUES ('2752', '2017-07-13', '2017', '3', '7', '28', '4', '14');
INSERT INTO `razor_dim_date` VALUES ('2753', '2017-07-14', '2017', '3', '7', '28', '5', '15');
INSERT INTO `razor_dim_date` VALUES ('2754', '2017-07-15', '2017', '3', '7', '29', '6', '16');
INSERT INTO `razor_dim_date` VALUES ('2755', '2017-07-16', '2017', '3', '7', '29', '0', '17');
INSERT INTO `razor_dim_date` VALUES ('2756', '2017-07-17', '2017', '3', '7', '29', '1', '18');
INSERT INTO `razor_dim_date` VALUES ('2757', '2017-07-18', '2017', '3', '7', '29', '2', '19');
INSERT INTO `razor_dim_date` VALUES ('2758', '2017-07-19', '2017', '3', '7', '29', '3', '20');
INSERT INTO `razor_dim_date` VALUES ('2759', '2017-07-20', '2017', '3', '7', '29', '4', '21');
INSERT INTO `razor_dim_date` VALUES ('2760', '2017-07-21', '2017', '3', '7', '29', '5', '22');
INSERT INTO `razor_dim_date` VALUES ('2761', '2017-07-22', '2017', '3', '7', '30', '6', '23');
INSERT INTO `razor_dim_date` VALUES ('2762', '2017-07-23', '2017', '3', '7', '30', '0', '24');
INSERT INTO `razor_dim_date` VALUES ('2763', '2017-07-24', '2017', '3', '7', '30', '1', '25');
INSERT INTO `razor_dim_date` VALUES ('2764', '2017-07-25', '2017', '3', '7', '30', '2', '26');
INSERT INTO `razor_dim_date` VALUES ('2765', '2017-07-26', '2017', '3', '7', '30', '3', '27');
INSERT INTO `razor_dim_date` VALUES ('2766', '2017-07-27', '2017', '3', '7', '30', '4', '28');
INSERT INTO `razor_dim_date` VALUES ('2767', '2017-07-28', '2017', '3', '7', '30', '5', '29');
INSERT INTO `razor_dim_date` VALUES ('2768', '2017-07-29', '2017', '3', '7', '31', '6', '30');
INSERT INTO `razor_dim_date` VALUES ('2769', '2017-07-30', '2017', '3', '7', '31', '0', '31');
INSERT INTO `razor_dim_date` VALUES ('2770', '2017-07-31', '2017', '3', '8', '31', '1', '1');
INSERT INTO `razor_dim_date` VALUES ('2771', '2017-08-01', '2017', '3', '8', '31', '2', '2');
INSERT INTO `razor_dim_date` VALUES ('2772', '2017-08-02', '2017', '3', '8', '31', '3', '3');
INSERT INTO `razor_dim_date` VALUES ('2773', '2017-08-03', '2017', '3', '8', '31', '4', '4');
INSERT INTO `razor_dim_date` VALUES ('2774', '2017-08-04', '2017', '3', '8', '31', '5', '5');
INSERT INTO `razor_dim_date` VALUES ('2775', '2017-08-05', '2017', '3', '8', '32', '6', '6');
INSERT INTO `razor_dim_date` VALUES ('2776', '2017-08-06', '2017', '3', '8', '32', '0', '7');
INSERT INTO `razor_dim_date` VALUES ('2777', '2017-08-07', '2017', '3', '8', '32', '1', '8');
INSERT INTO `razor_dim_date` VALUES ('2778', '2017-08-08', '2017', '3', '8', '32', '2', '9');
INSERT INTO `razor_dim_date` VALUES ('2779', '2017-08-09', '2017', '3', '8', '32', '3', '10');
INSERT INTO `razor_dim_date` VALUES ('2780', '2017-08-10', '2017', '3', '8', '32', '4', '11');
INSERT INTO `razor_dim_date` VALUES ('2781', '2017-08-11', '2017', '3', '8', '32', '5', '12');
INSERT INTO `razor_dim_date` VALUES ('2782', '2017-08-12', '2017', '3', '8', '33', '6', '13');
INSERT INTO `razor_dim_date` VALUES ('2783', '2017-08-13', '2017', '3', '8', '33', '0', '14');
INSERT INTO `razor_dim_date` VALUES ('2784', '2017-08-14', '2017', '3', '8', '33', '1', '15');
INSERT INTO `razor_dim_date` VALUES ('2785', '2017-08-15', '2017', '3', '8', '33', '2', '16');
INSERT INTO `razor_dim_date` VALUES ('2786', '2017-08-16', '2017', '3', '8', '33', '3', '17');
INSERT INTO `razor_dim_date` VALUES ('2787', '2017-08-17', '2017', '3', '8', '33', '4', '18');
INSERT INTO `razor_dim_date` VALUES ('2788', '2017-08-18', '2017', '3', '8', '33', '5', '19');
INSERT INTO `razor_dim_date` VALUES ('2789', '2017-08-19', '2017', '3', '8', '34', '6', '20');
INSERT INTO `razor_dim_date` VALUES ('2790', '2017-08-20', '2017', '3', '8', '34', '0', '21');
INSERT INTO `razor_dim_date` VALUES ('2791', '2017-08-21', '2017', '3', '8', '34', '1', '22');
INSERT INTO `razor_dim_date` VALUES ('2792', '2017-08-22', '2017', '3', '8', '34', '2', '23');
INSERT INTO `razor_dim_date` VALUES ('2793', '2017-08-23', '2017', '3', '8', '34', '3', '24');
INSERT INTO `razor_dim_date` VALUES ('2794', '2017-08-24', '2017', '3', '8', '34', '4', '25');
INSERT INTO `razor_dim_date` VALUES ('2795', '2017-08-25', '2017', '3', '8', '34', '5', '26');
INSERT INTO `razor_dim_date` VALUES ('2796', '2017-08-26', '2017', '3', '8', '35', '6', '27');
INSERT INTO `razor_dim_date` VALUES ('2797', '2017-08-27', '2017', '3', '8', '35', '0', '28');
INSERT INTO `razor_dim_date` VALUES ('2798', '2017-08-28', '2017', '3', '8', '35', '1', '29');
INSERT INTO `razor_dim_date` VALUES ('2799', '2017-08-29', '2017', '3', '8', '35', '2', '30');
INSERT INTO `razor_dim_date` VALUES ('2800', '2017-08-30', '2017', '3', '8', '35', '3', '31');
INSERT INTO `razor_dim_date` VALUES ('2801', '2017-08-31', '2017', '3', '9', '35', '4', '1');
INSERT INTO `razor_dim_date` VALUES ('2802', '2017-09-01', '2017', '3', '9', '35', '5', '2');
INSERT INTO `razor_dim_date` VALUES ('2803', '2017-09-02', '2017', '3', '9', '36', '6', '3');
INSERT INTO `razor_dim_date` VALUES ('2804', '2017-09-03', '2017', '3', '9', '36', '0', '4');
INSERT INTO `razor_dim_date` VALUES ('2805', '2017-09-04', '2017', '3', '9', '36', '1', '5');
INSERT INTO `razor_dim_date` VALUES ('2806', '2017-09-05', '2017', '3', '9', '36', '2', '6');
INSERT INTO `razor_dim_date` VALUES ('2807', '2017-09-06', '2017', '3', '9', '36', '3', '7');
INSERT INTO `razor_dim_date` VALUES ('2808', '2017-09-07', '2017', '3', '9', '36', '4', '8');
INSERT INTO `razor_dim_date` VALUES ('2809', '2017-09-08', '2017', '3', '9', '36', '5', '9');
INSERT INTO `razor_dim_date` VALUES ('2810', '2017-09-09', '2017', '3', '9', '37', '6', '10');
INSERT INTO `razor_dim_date` VALUES ('2811', '2017-09-10', '2017', '3', '9', '37', '0', '11');
INSERT INTO `razor_dim_date` VALUES ('2812', '2017-09-11', '2017', '3', '9', '37', '1', '12');
INSERT INTO `razor_dim_date` VALUES ('2813', '2017-09-12', '2017', '3', '9', '37', '2', '13');
INSERT INTO `razor_dim_date` VALUES ('2814', '2017-09-13', '2017', '3', '9', '37', '3', '14');
INSERT INTO `razor_dim_date` VALUES ('2815', '2017-09-14', '2017', '3', '9', '37', '4', '15');
INSERT INTO `razor_dim_date` VALUES ('2816', '2017-09-15', '2017', '3', '9', '37', '5', '16');
INSERT INTO `razor_dim_date` VALUES ('2817', '2017-09-16', '2017', '3', '9', '38', '6', '17');
INSERT INTO `razor_dim_date` VALUES ('2818', '2017-09-17', '2017', '3', '9', '38', '0', '18');
INSERT INTO `razor_dim_date` VALUES ('2819', '2017-09-18', '2017', '3', '9', '38', '1', '19');
INSERT INTO `razor_dim_date` VALUES ('2820', '2017-09-19', '2017', '3', '9', '38', '2', '20');
INSERT INTO `razor_dim_date` VALUES ('2821', '2017-09-20', '2017', '3', '9', '38', '3', '21');
INSERT INTO `razor_dim_date` VALUES ('2822', '2017-09-21', '2017', '3', '9', '38', '4', '22');
INSERT INTO `razor_dim_date` VALUES ('2823', '2017-09-22', '2017', '3', '9', '38', '5', '23');
INSERT INTO `razor_dim_date` VALUES ('2824', '2017-09-23', '2017', '3', '9', '39', '6', '24');
INSERT INTO `razor_dim_date` VALUES ('2825', '2017-09-24', '2017', '3', '9', '39', '0', '25');
INSERT INTO `razor_dim_date` VALUES ('2826', '2017-09-25', '2017', '3', '9', '39', '1', '26');
INSERT INTO `razor_dim_date` VALUES ('2827', '2017-09-26', '2017', '3', '9', '39', '2', '27');
INSERT INTO `razor_dim_date` VALUES ('2828', '2017-09-27', '2017', '3', '9', '39', '3', '28');
INSERT INTO `razor_dim_date` VALUES ('2829', '2017-09-28', '2017', '3', '9', '39', '4', '29');
INSERT INTO `razor_dim_date` VALUES ('2830', '2017-09-29', '2017', '3', '9', '39', '5', '30');
INSERT INTO `razor_dim_date` VALUES ('2831', '2017-09-30', '2017', '4', '10', '40', '6', '1');
INSERT INTO `razor_dim_date` VALUES ('2832', '2017-10-01', '2017', '4', '10', '40', '0', '2');
INSERT INTO `razor_dim_date` VALUES ('2833', '2017-10-02', '2017', '4', '10', '40', '1', '3');
INSERT INTO `razor_dim_date` VALUES ('2834', '2017-10-03', '2017', '4', '10', '40', '2', '4');
INSERT INTO `razor_dim_date` VALUES ('2835', '2017-10-04', '2017', '4', '10', '40', '3', '5');
INSERT INTO `razor_dim_date` VALUES ('2836', '2017-10-05', '2017', '4', '10', '40', '4', '6');
INSERT INTO `razor_dim_date` VALUES ('2837', '2017-10-06', '2017', '4', '10', '40', '5', '7');
INSERT INTO `razor_dim_date` VALUES ('2838', '2017-10-07', '2017', '4', '10', '41', '6', '8');
INSERT INTO `razor_dim_date` VALUES ('2839', '2017-10-08', '2017', '4', '10', '41', '0', '9');
INSERT INTO `razor_dim_date` VALUES ('2840', '2017-10-09', '2017', '4', '10', '41', '1', '10');
INSERT INTO `razor_dim_date` VALUES ('2841', '2017-10-10', '2017', '4', '10', '41', '2', '11');
INSERT INTO `razor_dim_date` VALUES ('2842', '2017-10-11', '2017', '4', '10', '41', '3', '12');
INSERT INTO `razor_dim_date` VALUES ('2843', '2017-10-12', '2017', '4', '10', '41', '4', '13');
INSERT INTO `razor_dim_date` VALUES ('2844', '2017-10-13', '2017', '4', '10', '41', '5', '14');
INSERT INTO `razor_dim_date` VALUES ('2845', '2017-10-14', '2017', '4', '10', '42', '6', '15');
INSERT INTO `razor_dim_date` VALUES ('2846', '2017-10-15', '2017', '4', '10', '42', '0', '16');
INSERT INTO `razor_dim_date` VALUES ('2847', '2017-10-16', '2017', '4', '10', '42', '1', '17');
INSERT INTO `razor_dim_date` VALUES ('2848', '2017-10-17', '2017', '4', '10', '42', '2', '18');
INSERT INTO `razor_dim_date` VALUES ('2849', '2017-10-18', '2017', '4', '10', '42', '3', '19');
INSERT INTO `razor_dim_date` VALUES ('2850', '2017-10-19', '2017', '4', '10', '42', '4', '20');
INSERT INTO `razor_dim_date` VALUES ('2851', '2017-10-20', '2017', '4', '10', '42', '5', '21');
INSERT INTO `razor_dim_date` VALUES ('2852', '2017-10-21', '2017', '4', '10', '43', '6', '22');
INSERT INTO `razor_dim_date` VALUES ('2853', '2017-10-22', '2017', '4', '10', '43', '0', '23');
INSERT INTO `razor_dim_date` VALUES ('2854', '2017-10-23', '2017', '4', '10', '43', '1', '24');
INSERT INTO `razor_dim_date` VALUES ('2855', '2017-10-24', '2017', '4', '10', '43', '2', '25');
INSERT INTO `razor_dim_date` VALUES ('2856', '2017-10-25', '2017', '4', '10', '43', '3', '26');
INSERT INTO `razor_dim_date` VALUES ('2857', '2017-10-26', '2017', '4', '10', '43', '4', '27');
INSERT INTO `razor_dim_date` VALUES ('2858', '2017-10-27', '2017', '4', '10', '43', '5', '28');
INSERT INTO `razor_dim_date` VALUES ('2859', '2017-10-28', '2017', '4', '10', '44', '6', '29');
INSERT INTO `razor_dim_date` VALUES ('2860', '2017-10-29', '2017', '4', '10', '44', '0', '30');
INSERT INTO `razor_dim_date` VALUES ('2861', '2017-10-30', '2017', '4', '10', '44', '1', '31');
INSERT INTO `razor_dim_date` VALUES ('2862', '2017-10-31', '2017', '4', '11', '44', '2', '1');
INSERT INTO `razor_dim_date` VALUES ('2863', '2017-11-01', '2017', '4', '11', '44', '3', '2');
INSERT INTO `razor_dim_date` VALUES ('2864', '2017-11-02', '2017', '4', '11', '44', '4', '3');
INSERT INTO `razor_dim_date` VALUES ('2865', '2017-11-03', '2017', '4', '11', '44', '5', '4');
INSERT INTO `razor_dim_date` VALUES ('2866', '2017-11-04', '2017', '4', '11', '45', '6', '5');
INSERT INTO `razor_dim_date` VALUES ('2867', '2017-11-05', '2017', '4', '11', '45', '0', '6');
INSERT INTO `razor_dim_date` VALUES ('2868', '2017-11-06', '2017', '4', '11', '45', '1', '7');
INSERT INTO `razor_dim_date` VALUES ('2869', '2017-11-07', '2017', '4', '11', '45', '2', '8');
INSERT INTO `razor_dim_date` VALUES ('2870', '2017-11-08', '2017', '4', '11', '45', '3', '9');
INSERT INTO `razor_dim_date` VALUES ('2871', '2017-11-09', '2017', '4', '11', '45', '4', '10');
INSERT INTO `razor_dim_date` VALUES ('2872', '2017-11-10', '2017', '4', '11', '45', '5', '11');
INSERT INTO `razor_dim_date` VALUES ('2873', '2017-11-11', '2017', '4', '11', '46', '6', '12');
INSERT INTO `razor_dim_date` VALUES ('2874', '2017-11-12', '2017', '4', '11', '46', '0', '13');
INSERT INTO `razor_dim_date` VALUES ('2875', '2017-11-13', '2017', '4', '11', '46', '1', '14');
INSERT INTO `razor_dim_date` VALUES ('2876', '2017-11-14', '2017', '4', '11', '46', '2', '15');
INSERT INTO `razor_dim_date` VALUES ('2877', '2017-11-15', '2017', '4', '11', '46', '3', '16');
INSERT INTO `razor_dim_date` VALUES ('2878', '2017-11-16', '2017', '4', '11', '46', '4', '17');
INSERT INTO `razor_dim_date` VALUES ('2879', '2017-11-17', '2017', '4', '11', '46', '5', '18');
INSERT INTO `razor_dim_date` VALUES ('2880', '2017-11-18', '2017', '4', '11', '47', '6', '19');
INSERT INTO `razor_dim_date` VALUES ('2881', '2017-11-19', '2017', '4', '11', '47', '0', '20');
INSERT INTO `razor_dim_date` VALUES ('2882', '2017-11-20', '2017', '4', '11', '47', '1', '21');
INSERT INTO `razor_dim_date` VALUES ('2883', '2017-11-21', '2017', '4', '11', '47', '2', '22');
INSERT INTO `razor_dim_date` VALUES ('2884', '2017-11-22', '2017', '4', '11', '47', '3', '23');
INSERT INTO `razor_dim_date` VALUES ('2885', '2017-11-23', '2017', '4', '11', '47', '4', '24');
INSERT INTO `razor_dim_date` VALUES ('2886', '2017-11-24', '2017', '4', '11', '47', '5', '25');
INSERT INTO `razor_dim_date` VALUES ('2887', '2017-11-25', '2017', '4', '11', '48', '6', '26');
INSERT INTO `razor_dim_date` VALUES ('2888', '2017-11-26', '2017', '4', '11', '48', '0', '27');
INSERT INTO `razor_dim_date` VALUES ('2889', '2017-11-27', '2017', '4', '11', '48', '1', '28');
INSERT INTO `razor_dim_date` VALUES ('2890', '2017-11-28', '2017', '4', '11', '48', '2', '29');
INSERT INTO `razor_dim_date` VALUES ('2891', '2017-11-29', '2017', '4', '11', '48', '3', '30');
INSERT INTO `razor_dim_date` VALUES ('2892', '2017-11-30', '2017', '4', '12', '48', '4', '1');
INSERT INTO `razor_dim_date` VALUES ('2893', '2017-12-01', '2017', '4', '12', '48', '5', '2');
INSERT INTO `razor_dim_date` VALUES ('2894', '2017-12-02', '2017', '4', '12', '49', '6', '3');
INSERT INTO `razor_dim_date` VALUES ('2895', '2017-12-03', '2017', '4', '12', '49', '0', '4');
INSERT INTO `razor_dim_date` VALUES ('2896', '2017-12-04', '2017', '4', '12', '49', '1', '5');
INSERT INTO `razor_dim_date` VALUES ('2897', '2017-12-05', '2017', '4', '12', '49', '2', '6');
INSERT INTO `razor_dim_date` VALUES ('2898', '2017-12-06', '2017', '4', '12', '49', '3', '7');
INSERT INTO `razor_dim_date` VALUES ('2899', '2017-12-07', '2017', '4', '12', '49', '4', '8');
INSERT INTO `razor_dim_date` VALUES ('2900', '2017-12-08', '2017', '4', '12', '49', '5', '9');
INSERT INTO `razor_dim_date` VALUES ('2901', '2017-12-09', '2017', '4', '12', '50', '6', '10');
INSERT INTO `razor_dim_date` VALUES ('2902', '2017-12-10', '2017', '4', '12', '50', '0', '11');
INSERT INTO `razor_dim_date` VALUES ('2903', '2017-12-11', '2017', '4', '12', '50', '1', '12');
INSERT INTO `razor_dim_date` VALUES ('2904', '2017-12-12', '2017', '4', '12', '50', '2', '13');
INSERT INTO `razor_dim_date` VALUES ('2905', '2017-12-13', '2017', '4', '12', '50', '3', '14');
INSERT INTO `razor_dim_date` VALUES ('2906', '2017-12-14', '2017', '4', '12', '50', '4', '15');
INSERT INTO `razor_dim_date` VALUES ('2907', '2017-12-15', '2017', '4', '12', '50', '5', '16');
INSERT INTO `razor_dim_date` VALUES ('2908', '2017-12-16', '2017', '4', '12', '51', '6', '17');
INSERT INTO `razor_dim_date` VALUES ('2909', '2017-12-17', '2017', '4', '12', '51', '0', '18');
INSERT INTO `razor_dim_date` VALUES ('2910', '2017-12-18', '2017', '4', '12', '51', '1', '19');
INSERT INTO `razor_dim_date` VALUES ('2911', '2017-12-19', '2017', '4', '12', '51', '2', '20');
INSERT INTO `razor_dim_date` VALUES ('2912', '2017-12-20', '2017', '4', '12', '51', '3', '21');
INSERT INTO `razor_dim_date` VALUES ('2913', '2017-12-21', '2017', '4', '12', '51', '4', '22');
INSERT INTO `razor_dim_date` VALUES ('2914', '2017-12-22', '2017', '4', '12', '51', '5', '23');
INSERT INTO `razor_dim_date` VALUES ('2915', '2017-12-23', '2017', '4', '12', '52', '6', '24');
INSERT INTO `razor_dim_date` VALUES ('2916', '2017-12-24', '2017', '4', '12', '52', '0', '25');
INSERT INTO `razor_dim_date` VALUES ('2917', '2017-12-25', '2017', '4', '12', '52', '1', '26');
INSERT INTO `razor_dim_date` VALUES ('2918', '2017-12-26', '2017', '4', '12', '52', '2', '27');
INSERT INTO `razor_dim_date` VALUES ('2919', '2017-12-27', '2017', '4', '12', '52', '3', '28');
INSERT INTO `razor_dim_date` VALUES ('2920', '2017-12-28', '2017', '4', '12', '52', '4', '29');
INSERT INTO `razor_dim_date` VALUES ('2921', '2017-12-29', '2017', '4', '12', '52', '5', '30');
INSERT INTO `razor_dim_date` VALUES ('2922', '2017-12-30', '2017', '4', '12', '53', '6', '31');
INSERT INTO `razor_dim_date` VALUES ('2923', '2017-12-31', '2018', '1', '1', '0', '0', '1');
INSERT INTO `razor_dim_date` VALUES ('2924', '2018-01-01', '2018', '1', '1', '0', '1', '2');
INSERT INTO `razor_dim_date` VALUES ('2925', '2018-01-02', '2018', '1', '1', '0', '2', '3');
INSERT INTO `razor_dim_date` VALUES ('2926', '2018-01-03', '2018', '1', '1', '0', '3', '4');
INSERT INTO `razor_dim_date` VALUES ('2927', '2018-01-04', '2018', '1', '1', '0', '4', '5');
INSERT INTO `razor_dim_date` VALUES ('2928', '2018-01-05', '2018', '1', '1', '0', '5', '6');
INSERT INTO `razor_dim_date` VALUES ('2929', '2018-01-06', '2018', '1', '1', '1', '6', '7');
INSERT INTO `razor_dim_date` VALUES ('2930', '2018-01-07', '2018', '1', '1', '1', '0', '8');
INSERT INTO `razor_dim_date` VALUES ('2931', '2018-01-08', '2018', '1', '1', '1', '1', '9');
INSERT INTO `razor_dim_date` VALUES ('2932', '2018-01-09', '2018', '1', '1', '1', '2', '10');
INSERT INTO `razor_dim_date` VALUES ('2933', '2018-01-10', '2018', '1', '1', '1', '3', '11');
INSERT INTO `razor_dim_date` VALUES ('2934', '2018-01-11', '2018', '1', '1', '1', '4', '12');
INSERT INTO `razor_dim_date` VALUES ('2935', '2018-01-12', '2018', '1', '1', '1', '5', '13');
INSERT INTO `razor_dim_date` VALUES ('2936', '2018-01-13', '2018', '1', '1', '2', '6', '14');
INSERT INTO `razor_dim_date` VALUES ('2937', '2018-01-14', '2018', '1', '1', '2', '0', '15');
INSERT INTO `razor_dim_date` VALUES ('2938', '2018-01-15', '2018', '1', '1', '2', '1', '16');
INSERT INTO `razor_dim_date` VALUES ('2939', '2018-01-16', '2018', '1', '1', '2', '2', '17');
INSERT INTO `razor_dim_date` VALUES ('2940', '2018-01-17', '2018', '1', '1', '2', '3', '18');
INSERT INTO `razor_dim_date` VALUES ('2941', '2018-01-18', '2018', '1', '1', '2', '4', '19');
INSERT INTO `razor_dim_date` VALUES ('2942', '2018-01-19', '2018', '1', '1', '2', '5', '20');
INSERT INTO `razor_dim_date` VALUES ('2943', '2018-01-20', '2018', '1', '1', '3', '6', '21');
INSERT INTO `razor_dim_date` VALUES ('2944', '2018-01-21', '2018', '1', '1', '3', '0', '22');
INSERT INTO `razor_dim_date` VALUES ('2945', '2018-01-22', '2018', '1', '1', '3', '1', '23');
INSERT INTO `razor_dim_date` VALUES ('2946', '2018-01-23', '2018', '1', '1', '3', '2', '24');
INSERT INTO `razor_dim_date` VALUES ('2947', '2018-01-24', '2018', '1', '1', '3', '3', '25');
INSERT INTO `razor_dim_date` VALUES ('2948', '2018-01-25', '2018', '1', '1', '3', '4', '26');
INSERT INTO `razor_dim_date` VALUES ('2949', '2018-01-26', '2018', '1', '1', '3', '5', '27');
INSERT INTO `razor_dim_date` VALUES ('2950', '2018-01-27', '2018', '1', '1', '4', '6', '28');
INSERT INTO `razor_dim_date` VALUES ('2951', '2018-01-28', '2018', '1', '1', '4', '0', '29');
INSERT INTO `razor_dim_date` VALUES ('2952', '2018-01-29', '2018', '1', '1', '4', '1', '30');
INSERT INTO `razor_dim_date` VALUES ('2953', '2018-01-30', '2018', '1', '1', '4', '2', '31');
INSERT INTO `razor_dim_date` VALUES ('2954', '2018-01-31', '2018', '1', '2', '4', '3', '1');
INSERT INTO `razor_dim_date` VALUES ('2955', '2018-02-01', '2018', '1', '2', '4', '4', '2');
INSERT INTO `razor_dim_date` VALUES ('2956', '2018-02-02', '2018', '1', '2', '4', '5', '3');
INSERT INTO `razor_dim_date` VALUES ('2957', '2018-02-03', '2018', '1', '2', '5', '6', '4');
INSERT INTO `razor_dim_date` VALUES ('2958', '2018-02-04', '2018', '1', '2', '5', '0', '5');
INSERT INTO `razor_dim_date` VALUES ('2959', '2018-02-05', '2018', '1', '2', '5', '1', '6');
INSERT INTO `razor_dim_date` VALUES ('2960', '2018-02-06', '2018', '1', '2', '5', '2', '7');
INSERT INTO `razor_dim_date` VALUES ('2961', '2018-02-07', '2018', '1', '2', '5', '3', '8');
INSERT INTO `razor_dim_date` VALUES ('2962', '2018-02-08', '2018', '1', '2', '5', '4', '9');
INSERT INTO `razor_dim_date` VALUES ('2963', '2018-02-09', '2018', '1', '2', '5', '5', '10');
INSERT INTO `razor_dim_date` VALUES ('2964', '2018-02-10', '2018', '1', '2', '6', '6', '11');
INSERT INTO `razor_dim_date` VALUES ('2965', '2018-02-11', '2018', '1', '2', '6', '0', '12');
INSERT INTO `razor_dim_date` VALUES ('2966', '2018-02-12', '2018', '1', '2', '6', '1', '13');
INSERT INTO `razor_dim_date` VALUES ('2967', '2018-02-13', '2018', '1', '2', '6', '2', '14');
INSERT INTO `razor_dim_date` VALUES ('2968', '2018-02-14', '2018', '1', '2', '6', '3', '15');
INSERT INTO `razor_dim_date` VALUES ('2969', '2018-02-15', '2018', '1', '2', '6', '4', '16');
INSERT INTO `razor_dim_date` VALUES ('2970', '2018-02-16', '2018', '1', '2', '6', '5', '17');
INSERT INTO `razor_dim_date` VALUES ('2971', '2018-02-17', '2018', '1', '2', '7', '6', '18');
INSERT INTO `razor_dim_date` VALUES ('2972', '2018-02-18', '2018', '1', '2', '7', '0', '19');
INSERT INTO `razor_dim_date` VALUES ('2973', '2018-02-19', '2018', '1', '2', '7', '1', '20');
INSERT INTO `razor_dim_date` VALUES ('2974', '2018-02-20', '2018', '1', '2', '7', '2', '21');
INSERT INTO `razor_dim_date` VALUES ('2975', '2018-02-21', '2018', '1', '2', '7', '3', '22');
INSERT INTO `razor_dim_date` VALUES ('2976', '2018-02-22', '2018', '1', '2', '7', '4', '23');
INSERT INTO `razor_dim_date` VALUES ('2977', '2018-02-23', '2018', '1', '2', '7', '5', '24');
INSERT INTO `razor_dim_date` VALUES ('2978', '2018-02-24', '2018', '1', '2', '8', '6', '25');
INSERT INTO `razor_dim_date` VALUES ('2979', '2018-02-25', '2018', '1', '2', '8', '0', '26');
INSERT INTO `razor_dim_date` VALUES ('2980', '2018-02-26', '2018', '1', '2', '8', '1', '27');
INSERT INTO `razor_dim_date` VALUES ('2981', '2018-02-27', '2018', '1', '2', '8', '2', '28');
INSERT INTO `razor_dim_date` VALUES ('2982', '2018-02-28', '2018', '1', '3', '8', '3', '1');
INSERT INTO `razor_dim_date` VALUES ('2983', '2018-03-01', '2018', '1', '3', '8', '4', '2');
INSERT INTO `razor_dim_date` VALUES ('2984', '2018-03-02', '2018', '1', '3', '8', '5', '3');
INSERT INTO `razor_dim_date` VALUES ('2985', '2018-03-03', '2018', '1', '3', '9', '6', '4');
INSERT INTO `razor_dim_date` VALUES ('2986', '2018-03-04', '2018', '1', '3', '9', '0', '5');
INSERT INTO `razor_dim_date` VALUES ('2987', '2018-03-05', '2018', '1', '3', '9', '1', '6');
INSERT INTO `razor_dim_date` VALUES ('2988', '2018-03-06', '2018', '1', '3', '9', '2', '7');
INSERT INTO `razor_dim_date` VALUES ('2989', '2018-03-07', '2018', '1', '3', '9', '3', '8');
INSERT INTO `razor_dim_date` VALUES ('2990', '2018-03-08', '2018', '1', '3', '9', '4', '9');
INSERT INTO `razor_dim_date` VALUES ('2991', '2018-03-09', '2018', '1', '3', '9', '5', '10');
INSERT INTO `razor_dim_date` VALUES ('2992', '2018-03-10', '2018', '1', '3', '10', '6', '11');
INSERT INTO `razor_dim_date` VALUES ('2993', '2018-03-11', '2018', '1', '3', '10', '0', '12');
INSERT INTO `razor_dim_date` VALUES ('2994', '2018-03-12', '2018', '1', '3', '10', '1', '13');
INSERT INTO `razor_dim_date` VALUES ('2995', '2018-03-13', '2018', '1', '3', '10', '2', '14');
INSERT INTO `razor_dim_date` VALUES ('2996', '2018-03-14', '2018', '1', '3', '10', '3', '15');
INSERT INTO `razor_dim_date` VALUES ('2997', '2018-03-15', '2018', '1', '3', '10', '4', '16');
INSERT INTO `razor_dim_date` VALUES ('2998', '2018-03-16', '2018', '1', '3', '10', '5', '17');
INSERT INTO `razor_dim_date` VALUES ('2999', '2018-03-17', '2018', '1', '3', '11', '6', '18');
INSERT INTO `razor_dim_date` VALUES ('3000', '2018-03-18', '2018', '1', '3', '11', '0', '19');
INSERT INTO `razor_dim_date` VALUES ('3001', '2018-03-19', '2018', '1', '3', '11', '1', '20');
INSERT INTO `razor_dim_date` VALUES ('3002', '2018-03-20', '2018', '1', '3', '11', '2', '21');
INSERT INTO `razor_dim_date` VALUES ('3003', '2018-03-21', '2018', '1', '3', '11', '3', '22');
INSERT INTO `razor_dim_date` VALUES ('3004', '2018-03-22', '2018', '1', '3', '11', '4', '23');
INSERT INTO `razor_dim_date` VALUES ('3005', '2018-03-23', '2018', '1', '3', '11', '5', '24');
INSERT INTO `razor_dim_date` VALUES ('3006', '2018-03-24', '2018', '1', '3', '12', '6', '25');
INSERT INTO `razor_dim_date` VALUES ('3007', '2018-03-25', '2018', '1', '3', '12', '0', '26');
INSERT INTO `razor_dim_date` VALUES ('3008', '2018-03-26', '2018', '1', '3', '12', '1', '27');
INSERT INTO `razor_dim_date` VALUES ('3009', '2018-03-27', '2018', '1', '3', '12', '2', '28');
INSERT INTO `razor_dim_date` VALUES ('3010', '2018-03-28', '2018', '1', '3', '12', '3', '29');
INSERT INTO `razor_dim_date` VALUES ('3011', '2018-03-29', '2018', '1', '3', '12', '4', '30');
INSERT INTO `razor_dim_date` VALUES ('3012', '2018-03-30', '2018', '1', '3', '12', '5', '31');
INSERT INTO `razor_dim_date` VALUES ('3013', '2018-03-31', '2018', '2', '4', '13', '6', '1');
INSERT INTO `razor_dim_date` VALUES ('3014', '2018-04-01', '2018', '2', '4', '13', '0', '2');
INSERT INTO `razor_dim_date` VALUES ('3015', '2018-04-02', '2018', '2', '4', '13', '1', '3');
INSERT INTO `razor_dim_date` VALUES ('3016', '2018-04-03', '2018', '2', '4', '13', '2', '4');
INSERT INTO `razor_dim_date` VALUES ('3017', '2018-04-04', '2018', '2', '4', '13', '3', '5');
INSERT INTO `razor_dim_date` VALUES ('3018', '2018-04-05', '2018', '2', '4', '13', '4', '6');
INSERT INTO `razor_dim_date` VALUES ('3019', '2018-04-06', '2018', '2', '4', '13', '5', '7');
INSERT INTO `razor_dim_date` VALUES ('3020', '2018-04-07', '2018', '2', '4', '14', '6', '8');
INSERT INTO `razor_dim_date` VALUES ('3021', '2018-04-08', '2018', '2', '4', '14', '0', '9');
INSERT INTO `razor_dim_date` VALUES ('3022', '2018-04-09', '2018', '2', '4', '14', '1', '10');
INSERT INTO `razor_dim_date` VALUES ('3023', '2018-04-10', '2018', '2', '4', '14', '2', '11');
INSERT INTO `razor_dim_date` VALUES ('3024', '2018-04-11', '2018', '2', '4', '14', '3', '12');
INSERT INTO `razor_dim_date` VALUES ('3025', '2018-04-12', '2018', '2', '4', '14', '4', '13');
INSERT INTO `razor_dim_date` VALUES ('3026', '2018-04-13', '2018', '2', '4', '14', '5', '14');
INSERT INTO `razor_dim_date` VALUES ('3027', '2018-04-14', '2018', '2', '4', '15', '6', '15');
INSERT INTO `razor_dim_date` VALUES ('3028', '2018-04-15', '2018', '2', '4', '15', '0', '16');
INSERT INTO `razor_dim_date` VALUES ('3029', '2018-04-16', '2018', '2', '4', '15', '1', '17');
INSERT INTO `razor_dim_date` VALUES ('3030', '2018-04-17', '2018', '2', '4', '15', '2', '18');
INSERT INTO `razor_dim_date` VALUES ('3031', '2018-04-18', '2018', '2', '4', '15', '3', '19');
INSERT INTO `razor_dim_date` VALUES ('3032', '2018-04-19', '2018', '2', '4', '15', '4', '20');
INSERT INTO `razor_dim_date` VALUES ('3033', '2018-04-20', '2018', '2', '4', '15', '5', '21');
INSERT INTO `razor_dim_date` VALUES ('3034', '2018-04-21', '2018', '2', '4', '16', '6', '22');
INSERT INTO `razor_dim_date` VALUES ('3035', '2018-04-22', '2018', '2', '4', '16', '0', '23');
INSERT INTO `razor_dim_date` VALUES ('3036', '2018-04-23', '2018', '2', '4', '16', '1', '24');
INSERT INTO `razor_dim_date` VALUES ('3037', '2018-04-24', '2018', '2', '4', '16', '2', '25');
INSERT INTO `razor_dim_date` VALUES ('3038', '2018-04-25', '2018', '2', '4', '16', '3', '26');
INSERT INTO `razor_dim_date` VALUES ('3039', '2018-04-26', '2018', '2', '4', '16', '4', '27');
INSERT INTO `razor_dim_date` VALUES ('3040', '2018-04-27', '2018', '2', '4', '16', '5', '28');
INSERT INTO `razor_dim_date` VALUES ('3041', '2018-04-28', '2018', '2', '4', '17', '6', '29');
INSERT INTO `razor_dim_date` VALUES ('3042', '2018-04-29', '2018', '2', '4', '17', '0', '30');
INSERT INTO `razor_dim_date` VALUES ('3043', '2018-04-30', '2018', '2', '5', '17', '1', '1');
INSERT INTO `razor_dim_date` VALUES ('3044', '2018-05-01', '2018', '2', '5', '17', '2', '2');
INSERT INTO `razor_dim_date` VALUES ('3045', '2018-05-02', '2018', '2', '5', '17', '3', '3');
INSERT INTO `razor_dim_date` VALUES ('3046', '2018-05-03', '2018', '2', '5', '17', '4', '4');
INSERT INTO `razor_dim_date` VALUES ('3047', '2018-05-04', '2018', '2', '5', '17', '5', '5');
INSERT INTO `razor_dim_date` VALUES ('3048', '2018-05-05', '2018', '2', '5', '18', '6', '6');
INSERT INTO `razor_dim_date` VALUES ('3049', '2018-05-06', '2018', '2', '5', '18', '0', '7');
INSERT INTO `razor_dim_date` VALUES ('3050', '2018-05-07', '2018', '2', '5', '18', '1', '8');
INSERT INTO `razor_dim_date` VALUES ('3051', '2018-05-08', '2018', '2', '5', '18', '2', '9');
INSERT INTO `razor_dim_date` VALUES ('3052', '2018-05-09', '2018', '2', '5', '18', '3', '10');
INSERT INTO `razor_dim_date` VALUES ('3053', '2018-05-10', '2018', '2', '5', '18', '4', '11');
INSERT INTO `razor_dim_date` VALUES ('3054', '2018-05-11', '2018', '2', '5', '18', '5', '12');
INSERT INTO `razor_dim_date` VALUES ('3055', '2018-05-12', '2018', '2', '5', '19', '6', '13');
INSERT INTO `razor_dim_date` VALUES ('3056', '2018-05-13', '2018', '2', '5', '19', '0', '14');
INSERT INTO `razor_dim_date` VALUES ('3057', '2018-05-14', '2018', '2', '5', '19', '1', '15');
INSERT INTO `razor_dim_date` VALUES ('3058', '2018-05-15', '2018', '2', '5', '19', '2', '16');
INSERT INTO `razor_dim_date` VALUES ('3059', '2018-05-16', '2018', '2', '5', '19', '3', '17');
INSERT INTO `razor_dim_date` VALUES ('3060', '2018-05-17', '2018', '2', '5', '19', '4', '18');
INSERT INTO `razor_dim_date` VALUES ('3061', '2018-05-18', '2018', '2', '5', '19', '5', '19');
INSERT INTO `razor_dim_date` VALUES ('3062', '2018-05-19', '2018', '2', '5', '20', '6', '20');
INSERT INTO `razor_dim_date` VALUES ('3063', '2018-05-20', '2018', '2', '5', '20', '0', '21');
INSERT INTO `razor_dim_date` VALUES ('3064', '2018-05-21', '2018', '2', '5', '20', '1', '22');
INSERT INTO `razor_dim_date` VALUES ('3065', '2018-05-22', '2018', '2', '5', '20', '2', '23');
INSERT INTO `razor_dim_date` VALUES ('3066', '2018-05-23', '2018', '2', '5', '20', '3', '24');
INSERT INTO `razor_dim_date` VALUES ('3067', '2018-05-24', '2018', '2', '5', '20', '4', '25');
INSERT INTO `razor_dim_date` VALUES ('3068', '2018-05-25', '2018', '2', '5', '20', '5', '26');
INSERT INTO `razor_dim_date` VALUES ('3069', '2018-05-26', '2018', '2', '5', '21', '6', '27');
INSERT INTO `razor_dim_date` VALUES ('3070', '2018-05-27', '2018', '2', '5', '21', '0', '28');
INSERT INTO `razor_dim_date` VALUES ('3071', '2018-05-28', '2018', '2', '5', '21', '1', '29');
INSERT INTO `razor_dim_date` VALUES ('3072', '2018-05-29', '2018', '2', '5', '21', '2', '30');
INSERT INTO `razor_dim_date` VALUES ('3073', '2018-05-30', '2018', '2', '5', '21', '3', '31');
INSERT INTO `razor_dim_date` VALUES ('3074', '2018-05-31', '2018', '2', '6', '21', '4', '1');
INSERT INTO `razor_dim_date` VALUES ('3075', '2018-06-01', '2018', '2', '6', '21', '5', '2');
INSERT INTO `razor_dim_date` VALUES ('3076', '2018-06-02', '2018', '2', '6', '22', '6', '3');
INSERT INTO `razor_dim_date` VALUES ('3077', '2018-06-03', '2018', '2', '6', '22', '0', '4');
INSERT INTO `razor_dim_date` VALUES ('3078', '2018-06-04', '2018', '2', '6', '22', '1', '5');
INSERT INTO `razor_dim_date` VALUES ('3079', '2018-06-05', '2018', '2', '6', '22', '2', '6');
INSERT INTO `razor_dim_date` VALUES ('3080', '2018-06-06', '2018', '2', '6', '22', '3', '7');
INSERT INTO `razor_dim_date` VALUES ('3081', '2018-06-07', '2018', '2', '6', '22', '4', '8');
INSERT INTO `razor_dim_date` VALUES ('3082', '2018-06-08', '2018', '2', '6', '22', '5', '9');
INSERT INTO `razor_dim_date` VALUES ('3083', '2018-06-09', '2018', '2', '6', '23', '6', '10');
INSERT INTO `razor_dim_date` VALUES ('3084', '2018-06-10', '2018', '2', '6', '23', '0', '11');
INSERT INTO `razor_dim_date` VALUES ('3085', '2018-06-11', '2018', '2', '6', '23', '1', '12');
INSERT INTO `razor_dim_date` VALUES ('3086', '2018-06-12', '2018', '2', '6', '23', '2', '13');
INSERT INTO `razor_dim_date` VALUES ('3087', '2018-06-13', '2018', '2', '6', '23', '3', '14');
INSERT INTO `razor_dim_date` VALUES ('3088', '2018-06-14', '2018', '2', '6', '23', '4', '15');
INSERT INTO `razor_dim_date` VALUES ('3089', '2018-06-15', '2018', '2', '6', '23', '5', '16');
INSERT INTO `razor_dim_date` VALUES ('3090', '2018-06-16', '2018', '2', '6', '24', '6', '17');
INSERT INTO `razor_dim_date` VALUES ('3091', '2018-06-17', '2018', '2', '6', '24', '0', '18');
INSERT INTO `razor_dim_date` VALUES ('3092', '2018-06-18', '2018', '2', '6', '24', '1', '19');
INSERT INTO `razor_dim_date` VALUES ('3093', '2018-06-19', '2018', '2', '6', '24', '2', '20');
INSERT INTO `razor_dim_date` VALUES ('3094', '2018-06-20', '2018', '2', '6', '24', '3', '21');
INSERT INTO `razor_dim_date` VALUES ('3095', '2018-06-21', '2018', '2', '6', '24', '4', '22');
INSERT INTO `razor_dim_date` VALUES ('3096', '2018-06-22', '2018', '2', '6', '24', '5', '23');
INSERT INTO `razor_dim_date` VALUES ('3097', '2018-06-23', '2018', '2', '6', '25', '6', '24');
INSERT INTO `razor_dim_date` VALUES ('3098', '2018-06-24', '2018', '2', '6', '25', '0', '25');
INSERT INTO `razor_dim_date` VALUES ('3099', '2018-06-25', '2018', '2', '6', '25', '1', '26');
INSERT INTO `razor_dim_date` VALUES ('3100', '2018-06-26', '2018', '2', '6', '25', '2', '27');
INSERT INTO `razor_dim_date` VALUES ('3101', '2018-06-27', '2018', '2', '6', '25', '3', '28');
INSERT INTO `razor_dim_date` VALUES ('3102', '2018-06-28', '2018', '2', '6', '25', '4', '29');
INSERT INTO `razor_dim_date` VALUES ('3103', '2018-06-29', '2018', '2', '6', '25', '5', '30');
INSERT INTO `razor_dim_date` VALUES ('3104', '2018-06-30', '2018', '3', '7', '26', '6', '1');
INSERT INTO `razor_dim_date` VALUES ('3105', '2018-07-01', '2018', '3', '7', '26', '0', '2');
INSERT INTO `razor_dim_date` VALUES ('3106', '2018-07-02', '2018', '3', '7', '26', '1', '3');
INSERT INTO `razor_dim_date` VALUES ('3107', '2018-07-03', '2018', '3', '7', '26', '2', '4');
INSERT INTO `razor_dim_date` VALUES ('3108', '2018-07-04', '2018', '3', '7', '26', '3', '5');
INSERT INTO `razor_dim_date` VALUES ('3109', '2018-07-05', '2018', '3', '7', '26', '4', '6');
INSERT INTO `razor_dim_date` VALUES ('3110', '2018-07-06', '2018', '3', '7', '26', '5', '7');
INSERT INTO `razor_dim_date` VALUES ('3111', '2018-07-07', '2018', '3', '7', '27', '6', '8');
INSERT INTO `razor_dim_date` VALUES ('3112', '2018-07-08', '2018', '3', '7', '27', '0', '9');
INSERT INTO `razor_dim_date` VALUES ('3113', '2018-07-09', '2018', '3', '7', '27', '1', '10');
INSERT INTO `razor_dim_date` VALUES ('3114', '2018-07-10', '2018', '3', '7', '27', '2', '11');
INSERT INTO `razor_dim_date` VALUES ('3115', '2018-07-11', '2018', '3', '7', '27', '3', '12');
INSERT INTO `razor_dim_date` VALUES ('3116', '2018-07-12', '2018', '3', '7', '27', '4', '13');
INSERT INTO `razor_dim_date` VALUES ('3117', '2018-07-13', '2018', '3', '7', '27', '5', '14');
INSERT INTO `razor_dim_date` VALUES ('3118', '2018-07-14', '2018', '3', '7', '28', '6', '15');
INSERT INTO `razor_dim_date` VALUES ('3119', '2018-07-15', '2018', '3', '7', '28', '0', '16');
INSERT INTO `razor_dim_date` VALUES ('3120', '2018-07-16', '2018', '3', '7', '28', '1', '17');
INSERT INTO `razor_dim_date` VALUES ('3121', '2018-07-17', '2018', '3', '7', '28', '2', '18');
INSERT INTO `razor_dim_date` VALUES ('3122', '2018-07-18', '2018', '3', '7', '28', '3', '19');
INSERT INTO `razor_dim_date` VALUES ('3123', '2018-07-19', '2018', '3', '7', '28', '4', '20');
INSERT INTO `razor_dim_date` VALUES ('3124', '2018-07-20', '2018', '3', '7', '28', '5', '21');
INSERT INTO `razor_dim_date` VALUES ('3125', '2018-07-21', '2018', '3', '7', '29', '6', '22');
INSERT INTO `razor_dim_date` VALUES ('3126', '2018-07-22', '2018', '3', '7', '29', '0', '23');
INSERT INTO `razor_dim_date` VALUES ('3127', '2018-07-23', '2018', '3', '7', '29', '1', '24');
INSERT INTO `razor_dim_date` VALUES ('3128', '2018-07-24', '2018', '3', '7', '29', '2', '25');
INSERT INTO `razor_dim_date` VALUES ('3129', '2018-07-25', '2018', '3', '7', '29', '3', '26');
INSERT INTO `razor_dim_date` VALUES ('3130', '2018-07-26', '2018', '3', '7', '29', '4', '27');
INSERT INTO `razor_dim_date` VALUES ('3131', '2018-07-27', '2018', '3', '7', '29', '5', '28');
INSERT INTO `razor_dim_date` VALUES ('3132', '2018-07-28', '2018', '3', '7', '30', '6', '29');
INSERT INTO `razor_dim_date` VALUES ('3133', '2018-07-29', '2018', '3', '7', '30', '0', '30');
INSERT INTO `razor_dim_date` VALUES ('3134', '2018-07-30', '2018', '3', '7', '30', '1', '31');
INSERT INTO `razor_dim_date` VALUES ('3135', '2018-07-31', '2018', '3', '8', '30', '2', '1');
INSERT INTO `razor_dim_date` VALUES ('3136', '2018-08-01', '2018', '3', '8', '30', '3', '2');
INSERT INTO `razor_dim_date` VALUES ('3137', '2018-08-02', '2018', '3', '8', '30', '4', '3');
INSERT INTO `razor_dim_date` VALUES ('3138', '2018-08-03', '2018', '3', '8', '30', '5', '4');
INSERT INTO `razor_dim_date` VALUES ('3139', '2018-08-04', '2018', '3', '8', '31', '6', '5');
INSERT INTO `razor_dim_date` VALUES ('3140', '2018-08-05', '2018', '3', '8', '31', '0', '6');
INSERT INTO `razor_dim_date` VALUES ('3141', '2018-08-06', '2018', '3', '8', '31', '1', '7');
INSERT INTO `razor_dim_date` VALUES ('3142', '2018-08-07', '2018', '3', '8', '31', '2', '8');
INSERT INTO `razor_dim_date` VALUES ('3143', '2018-08-08', '2018', '3', '8', '31', '3', '9');
INSERT INTO `razor_dim_date` VALUES ('3144', '2018-08-09', '2018', '3', '8', '31', '4', '10');
INSERT INTO `razor_dim_date` VALUES ('3145', '2018-08-10', '2018', '3', '8', '31', '5', '11');
INSERT INTO `razor_dim_date` VALUES ('3146', '2018-08-11', '2018', '3', '8', '32', '6', '12');
INSERT INTO `razor_dim_date` VALUES ('3147', '2018-08-12', '2018', '3', '8', '32', '0', '13');
INSERT INTO `razor_dim_date` VALUES ('3148', '2018-08-13', '2018', '3', '8', '32', '1', '14');
INSERT INTO `razor_dim_date` VALUES ('3149', '2018-08-14', '2018', '3', '8', '32', '2', '15');
INSERT INTO `razor_dim_date` VALUES ('3150', '2018-08-15', '2018', '3', '8', '32', '3', '16');
INSERT INTO `razor_dim_date` VALUES ('3151', '2018-08-16', '2018', '3', '8', '32', '4', '17');
INSERT INTO `razor_dim_date` VALUES ('3152', '2018-08-17', '2018', '3', '8', '32', '5', '18');
INSERT INTO `razor_dim_date` VALUES ('3153', '2018-08-18', '2018', '3', '8', '33', '6', '19');
INSERT INTO `razor_dim_date` VALUES ('3154', '2018-08-19', '2018', '3', '8', '33', '0', '20');
INSERT INTO `razor_dim_date` VALUES ('3155', '2018-08-20', '2018', '3', '8', '33', '1', '21');
INSERT INTO `razor_dim_date` VALUES ('3156', '2018-08-21', '2018', '3', '8', '33', '2', '22');
INSERT INTO `razor_dim_date` VALUES ('3157', '2018-08-22', '2018', '3', '8', '33', '3', '23');
INSERT INTO `razor_dim_date` VALUES ('3158', '2018-08-23', '2018', '3', '8', '33', '4', '24');
INSERT INTO `razor_dim_date` VALUES ('3159', '2018-08-24', '2018', '3', '8', '33', '5', '25');
INSERT INTO `razor_dim_date` VALUES ('3160', '2018-08-25', '2018', '3', '8', '34', '6', '26');
INSERT INTO `razor_dim_date` VALUES ('3161', '2018-08-26', '2018', '3', '8', '34', '0', '27');
INSERT INTO `razor_dim_date` VALUES ('3162', '2018-08-27', '2018', '3', '8', '34', '1', '28');
INSERT INTO `razor_dim_date` VALUES ('3163', '2018-08-28', '2018', '3', '8', '34', '2', '29');
INSERT INTO `razor_dim_date` VALUES ('3164', '2018-08-29', '2018', '3', '8', '34', '3', '30');
INSERT INTO `razor_dim_date` VALUES ('3165', '2018-08-30', '2018', '3', '8', '34', '4', '31');
INSERT INTO `razor_dim_date` VALUES ('3166', '2018-08-31', '2018', '3', '9', '34', '5', '1');
INSERT INTO `razor_dim_date` VALUES ('3167', '2018-09-01', '2018', '3', '9', '35', '6', '2');
INSERT INTO `razor_dim_date` VALUES ('3168', '2018-09-02', '2018', '3', '9', '35', '0', '3');
INSERT INTO `razor_dim_date` VALUES ('3169', '2018-09-03', '2018', '3', '9', '35', '1', '4');
INSERT INTO `razor_dim_date` VALUES ('3170', '2018-09-04', '2018', '3', '9', '35', '2', '5');
INSERT INTO `razor_dim_date` VALUES ('3171', '2018-09-05', '2018', '3', '9', '35', '3', '6');
INSERT INTO `razor_dim_date` VALUES ('3172', '2018-09-06', '2018', '3', '9', '35', '4', '7');
INSERT INTO `razor_dim_date` VALUES ('3173', '2018-09-07', '2018', '3', '9', '35', '5', '8');
INSERT INTO `razor_dim_date` VALUES ('3174', '2018-09-08', '2018', '3', '9', '36', '6', '9');
INSERT INTO `razor_dim_date` VALUES ('3175', '2018-09-09', '2018', '3', '9', '36', '0', '10');
INSERT INTO `razor_dim_date` VALUES ('3176', '2018-09-10', '2018', '3', '9', '36', '1', '11');
INSERT INTO `razor_dim_date` VALUES ('3177', '2018-09-11', '2018', '3', '9', '36', '2', '12');
INSERT INTO `razor_dim_date` VALUES ('3178', '2018-09-12', '2018', '3', '9', '36', '3', '13');
INSERT INTO `razor_dim_date` VALUES ('3179', '2018-09-13', '2018', '3', '9', '36', '4', '14');
INSERT INTO `razor_dim_date` VALUES ('3180', '2018-09-14', '2018', '3', '9', '36', '5', '15');
INSERT INTO `razor_dim_date` VALUES ('3181', '2018-09-15', '2018', '3', '9', '37', '6', '16');
INSERT INTO `razor_dim_date` VALUES ('3182', '2018-09-16', '2018', '3', '9', '37', '0', '17');
INSERT INTO `razor_dim_date` VALUES ('3183', '2018-09-17', '2018', '3', '9', '37', '1', '18');
INSERT INTO `razor_dim_date` VALUES ('3184', '2018-09-18', '2018', '3', '9', '37', '2', '19');
INSERT INTO `razor_dim_date` VALUES ('3185', '2018-09-19', '2018', '3', '9', '37', '3', '20');
INSERT INTO `razor_dim_date` VALUES ('3186', '2018-09-20', '2018', '3', '9', '37', '4', '21');
INSERT INTO `razor_dim_date` VALUES ('3187', '2018-09-21', '2018', '3', '9', '37', '5', '22');
INSERT INTO `razor_dim_date` VALUES ('3188', '2018-09-22', '2018', '3', '9', '38', '6', '23');
INSERT INTO `razor_dim_date` VALUES ('3189', '2018-09-23', '2018', '3', '9', '38', '0', '24');
INSERT INTO `razor_dim_date` VALUES ('3190', '2018-09-24', '2018', '3', '9', '38', '1', '25');
INSERT INTO `razor_dim_date` VALUES ('3191', '2018-09-25', '2018', '3', '9', '38', '2', '26');
INSERT INTO `razor_dim_date` VALUES ('3192', '2018-09-26', '2018', '3', '9', '38', '3', '27');
INSERT INTO `razor_dim_date` VALUES ('3193', '2018-09-27', '2018', '3', '9', '38', '4', '28');
INSERT INTO `razor_dim_date` VALUES ('3194', '2018-09-28', '2018', '3', '9', '38', '5', '29');
INSERT INTO `razor_dim_date` VALUES ('3195', '2018-09-29', '2018', '3', '9', '39', '6', '30');
INSERT INTO `razor_dim_date` VALUES ('3196', '2018-09-30', '2018', '4', '10', '39', '0', '1');
INSERT INTO `razor_dim_date` VALUES ('3197', '2018-10-01', '2018', '4', '10', '39', '1', '2');
INSERT INTO `razor_dim_date` VALUES ('3198', '2018-10-02', '2018', '4', '10', '39', '2', '3');
INSERT INTO `razor_dim_date` VALUES ('3199', '2018-10-03', '2018', '4', '10', '39', '3', '4');
INSERT INTO `razor_dim_date` VALUES ('3200', '2018-10-04', '2018', '4', '10', '39', '4', '5');
INSERT INTO `razor_dim_date` VALUES ('3201', '2018-10-05', '2018', '4', '10', '39', '5', '6');
INSERT INTO `razor_dim_date` VALUES ('3202', '2018-10-06', '2018', '4', '10', '40', '6', '7');
INSERT INTO `razor_dim_date` VALUES ('3203', '2018-10-07', '2018', '4', '10', '40', '0', '8');
INSERT INTO `razor_dim_date` VALUES ('3204', '2018-10-08', '2018', '4', '10', '40', '1', '9');
INSERT INTO `razor_dim_date` VALUES ('3205', '2018-10-09', '2018', '4', '10', '40', '2', '10');
INSERT INTO `razor_dim_date` VALUES ('3206', '2018-10-10', '2018', '4', '10', '40', '3', '11');
INSERT INTO `razor_dim_date` VALUES ('3207', '2018-10-11', '2018', '4', '10', '40', '4', '12');
INSERT INTO `razor_dim_date` VALUES ('3208', '2018-10-12', '2018', '4', '10', '40', '5', '13');
INSERT INTO `razor_dim_date` VALUES ('3209', '2018-10-13', '2018', '4', '10', '41', '6', '14');
INSERT INTO `razor_dim_date` VALUES ('3210', '2018-10-14', '2018', '4', '10', '41', '0', '15');
INSERT INTO `razor_dim_date` VALUES ('3211', '2018-10-15', '2018', '4', '10', '41', '1', '16');
INSERT INTO `razor_dim_date` VALUES ('3212', '2018-10-16', '2018', '4', '10', '41', '2', '17');
INSERT INTO `razor_dim_date` VALUES ('3213', '2018-10-17', '2018', '4', '10', '41', '3', '18');
INSERT INTO `razor_dim_date` VALUES ('3214', '2018-10-18', '2018', '4', '10', '41', '4', '19');
INSERT INTO `razor_dim_date` VALUES ('3215', '2018-10-19', '2018', '4', '10', '41', '5', '20');
INSERT INTO `razor_dim_date` VALUES ('3216', '2018-10-20', '2018', '4', '10', '42', '6', '21');
INSERT INTO `razor_dim_date` VALUES ('3217', '2018-10-21', '2018', '4', '10', '42', '0', '22');
INSERT INTO `razor_dim_date` VALUES ('3218', '2018-10-22', '2018', '4', '10', '42', '1', '23');
INSERT INTO `razor_dim_date` VALUES ('3219', '2018-10-23', '2018', '4', '10', '42', '2', '24');
INSERT INTO `razor_dim_date` VALUES ('3220', '2018-10-24', '2018', '4', '10', '42', '3', '25');
INSERT INTO `razor_dim_date` VALUES ('3221', '2018-10-25', '2018', '4', '10', '42', '4', '26');
INSERT INTO `razor_dim_date` VALUES ('3222', '2018-10-26', '2018', '4', '10', '42', '5', '27');
INSERT INTO `razor_dim_date` VALUES ('3223', '2018-10-27', '2018', '4', '10', '43', '6', '28');
INSERT INTO `razor_dim_date` VALUES ('3224', '2018-10-28', '2018', '4', '10', '43', '0', '29');
INSERT INTO `razor_dim_date` VALUES ('3225', '2018-10-29', '2018', '4', '10', '43', '1', '30');
INSERT INTO `razor_dim_date` VALUES ('3226', '2018-10-30', '2018', '4', '10', '43', '2', '31');
INSERT INTO `razor_dim_date` VALUES ('3227', '2018-10-31', '2018', '4', '11', '43', '3', '1');
INSERT INTO `razor_dim_date` VALUES ('3228', '2018-11-01', '2018', '4', '11', '43', '4', '2');
INSERT INTO `razor_dim_date` VALUES ('3229', '2018-11-02', '2018', '4', '11', '43', '5', '3');
INSERT INTO `razor_dim_date` VALUES ('3230', '2018-11-03', '2018', '4', '11', '44', '6', '4');
INSERT INTO `razor_dim_date` VALUES ('3231', '2018-11-04', '2018', '4', '11', '44', '0', '5');
INSERT INTO `razor_dim_date` VALUES ('3232', '2018-11-05', '2018', '4', '11', '44', '1', '6');
INSERT INTO `razor_dim_date` VALUES ('3233', '2018-11-06', '2018', '4', '11', '44', '2', '7');
INSERT INTO `razor_dim_date` VALUES ('3234', '2018-11-07', '2018', '4', '11', '44', '3', '8');
INSERT INTO `razor_dim_date` VALUES ('3235', '2018-11-08', '2018', '4', '11', '44', '4', '9');
INSERT INTO `razor_dim_date` VALUES ('3236', '2018-11-09', '2018', '4', '11', '44', '5', '10');
INSERT INTO `razor_dim_date` VALUES ('3237', '2018-11-10', '2018', '4', '11', '45', '6', '11');
INSERT INTO `razor_dim_date` VALUES ('3238', '2018-11-11', '2018', '4', '11', '45', '0', '12');
INSERT INTO `razor_dim_date` VALUES ('3239', '2018-11-12', '2018', '4', '11', '45', '1', '13');
INSERT INTO `razor_dim_date` VALUES ('3240', '2018-11-13', '2018', '4', '11', '45', '2', '14');
INSERT INTO `razor_dim_date` VALUES ('3241', '2018-11-14', '2018', '4', '11', '45', '3', '15');
INSERT INTO `razor_dim_date` VALUES ('3242', '2018-11-15', '2018', '4', '11', '45', '4', '16');
INSERT INTO `razor_dim_date` VALUES ('3243', '2018-11-16', '2018', '4', '11', '45', '5', '17');
INSERT INTO `razor_dim_date` VALUES ('3244', '2018-11-17', '2018', '4', '11', '46', '6', '18');
INSERT INTO `razor_dim_date` VALUES ('3245', '2018-11-18', '2018', '4', '11', '46', '0', '19');
INSERT INTO `razor_dim_date` VALUES ('3246', '2018-11-19', '2018', '4', '11', '46', '1', '20');
INSERT INTO `razor_dim_date` VALUES ('3247', '2018-11-20', '2018', '4', '11', '46', '2', '21');
INSERT INTO `razor_dim_date` VALUES ('3248', '2018-11-21', '2018', '4', '11', '46', '3', '22');
INSERT INTO `razor_dim_date` VALUES ('3249', '2018-11-22', '2018', '4', '11', '46', '4', '23');
INSERT INTO `razor_dim_date` VALUES ('3250', '2018-11-23', '2018', '4', '11', '46', '5', '24');
INSERT INTO `razor_dim_date` VALUES ('3251', '2018-11-24', '2018', '4', '11', '47', '6', '25');
INSERT INTO `razor_dim_date` VALUES ('3252', '2018-11-25', '2018', '4', '11', '47', '0', '26');
INSERT INTO `razor_dim_date` VALUES ('3253', '2018-11-26', '2018', '4', '11', '47', '1', '27');
INSERT INTO `razor_dim_date` VALUES ('3254', '2018-11-27', '2018', '4', '11', '47', '2', '28');
INSERT INTO `razor_dim_date` VALUES ('3255', '2018-11-28', '2018', '4', '11', '47', '3', '29');
INSERT INTO `razor_dim_date` VALUES ('3256', '2018-11-29', '2018', '4', '11', '47', '4', '30');
INSERT INTO `razor_dim_date` VALUES ('3257', '2018-11-30', '2018', '4', '12', '47', '5', '1');
INSERT INTO `razor_dim_date` VALUES ('3258', '2018-12-01', '2018', '4', '12', '48', '6', '2');
INSERT INTO `razor_dim_date` VALUES ('3259', '2018-12-02', '2018', '4', '12', '48', '0', '3');
INSERT INTO `razor_dim_date` VALUES ('3260', '2018-12-03', '2018', '4', '12', '48', '1', '4');
INSERT INTO `razor_dim_date` VALUES ('3261', '2018-12-04', '2018', '4', '12', '48', '2', '5');
INSERT INTO `razor_dim_date` VALUES ('3262', '2018-12-05', '2018', '4', '12', '48', '3', '6');
INSERT INTO `razor_dim_date` VALUES ('3263', '2018-12-06', '2018', '4', '12', '48', '4', '7');
INSERT INTO `razor_dim_date` VALUES ('3264', '2018-12-07', '2018', '4', '12', '48', '5', '8');
INSERT INTO `razor_dim_date` VALUES ('3265', '2018-12-08', '2018', '4', '12', '49', '6', '9');
INSERT INTO `razor_dim_date` VALUES ('3266', '2018-12-09', '2018', '4', '12', '49', '0', '10');
INSERT INTO `razor_dim_date` VALUES ('3267', '2018-12-10', '2018', '4', '12', '49', '1', '11');
INSERT INTO `razor_dim_date` VALUES ('3268', '2018-12-11', '2018', '4', '12', '49', '2', '12');
INSERT INTO `razor_dim_date` VALUES ('3269', '2018-12-12', '2018', '4', '12', '49', '3', '13');
INSERT INTO `razor_dim_date` VALUES ('3270', '2018-12-13', '2018', '4', '12', '49', '4', '14');
INSERT INTO `razor_dim_date` VALUES ('3271', '2018-12-14', '2018', '4', '12', '49', '5', '15');
INSERT INTO `razor_dim_date` VALUES ('3272', '2018-12-15', '2018', '4', '12', '50', '6', '16');
INSERT INTO `razor_dim_date` VALUES ('3273', '2018-12-16', '2018', '4', '12', '50', '0', '17');
INSERT INTO `razor_dim_date` VALUES ('3274', '2018-12-17', '2018', '4', '12', '50', '1', '18');
INSERT INTO `razor_dim_date` VALUES ('3275', '2018-12-18', '2018', '4', '12', '50', '2', '19');
INSERT INTO `razor_dim_date` VALUES ('3276', '2018-12-19', '2018', '4', '12', '50', '3', '20');
INSERT INTO `razor_dim_date` VALUES ('3277', '2018-12-20', '2018', '4', '12', '50', '4', '21');
INSERT INTO `razor_dim_date` VALUES ('3278', '2018-12-21', '2018', '4', '12', '50', '5', '22');
INSERT INTO `razor_dim_date` VALUES ('3279', '2018-12-22', '2018', '4', '12', '51', '6', '23');
INSERT INTO `razor_dim_date` VALUES ('3280', '2018-12-23', '2018', '4', '12', '51', '0', '24');
INSERT INTO `razor_dim_date` VALUES ('3281', '2018-12-24', '2018', '4', '12', '51', '1', '25');
INSERT INTO `razor_dim_date` VALUES ('3282', '2018-12-25', '2018', '4', '12', '51', '2', '26');
INSERT INTO `razor_dim_date` VALUES ('3283', '2018-12-26', '2018', '4', '12', '51', '3', '27');
INSERT INTO `razor_dim_date` VALUES ('3284', '2018-12-27', '2018', '4', '12', '51', '4', '28');
INSERT INTO `razor_dim_date` VALUES ('3285', '2018-12-28', '2018', '4', '12', '51', '5', '29');
INSERT INTO `razor_dim_date` VALUES ('3286', '2018-12-29', '2018', '4', '12', '52', '6', '30');
INSERT INTO `razor_dim_date` VALUES ('3287', '2018-12-30', '2018', '4', '12', '52', '0', '31');
INSERT INTO `razor_dim_date` VALUES ('3288', '2018-12-31', '2019', '1', '1', '0', '1', '1');
INSERT INTO `razor_dim_date` VALUES ('3289', '2019-01-01', '2019', '1', '1', '0', '2', '2');
INSERT INTO `razor_dim_date` VALUES ('3290', '2019-01-02', '2019', '1', '1', '0', '3', '3');
INSERT INTO `razor_dim_date` VALUES ('3291', '2019-01-03', '2019', '1', '1', '0', '4', '4');
INSERT INTO `razor_dim_date` VALUES ('3292', '2019-01-04', '2019', '1', '1', '0', '5', '5');
INSERT INTO `razor_dim_date` VALUES ('3293', '2019-01-05', '2019', '1', '1', '1', '6', '6');
INSERT INTO `razor_dim_date` VALUES ('3294', '2019-01-06', '2019', '1', '1', '1', '0', '7');
INSERT INTO `razor_dim_date` VALUES ('3295', '2019-01-07', '2019', '1', '1', '1', '1', '8');
INSERT INTO `razor_dim_date` VALUES ('3296', '2019-01-08', '2019', '1', '1', '1', '2', '9');
INSERT INTO `razor_dim_date` VALUES ('3297', '2019-01-09', '2019', '1', '1', '1', '3', '10');
INSERT INTO `razor_dim_date` VALUES ('3298', '2019-01-10', '2019', '1', '1', '1', '4', '11');
INSERT INTO `razor_dim_date` VALUES ('3299', '2019-01-11', '2019', '1', '1', '1', '5', '12');
INSERT INTO `razor_dim_date` VALUES ('3300', '2019-01-12', '2019', '1', '1', '2', '6', '13');
INSERT INTO `razor_dim_date` VALUES ('3301', '2019-01-13', '2019', '1', '1', '2', '0', '14');
INSERT INTO `razor_dim_date` VALUES ('3302', '2019-01-14', '2019', '1', '1', '2', '1', '15');
INSERT INTO `razor_dim_date` VALUES ('3303', '2019-01-15', '2019', '1', '1', '2', '2', '16');
INSERT INTO `razor_dim_date` VALUES ('3304', '2019-01-16', '2019', '1', '1', '2', '3', '17');
INSERT INTO `razor_dim_date` VALUES ('3305', '2019-01-17', '2019', '1', '1', '2', '4', '18');
INSERT INTO `razor_dim_date` VALUES ('3306', '2019-01-18', '2019', '1', '1', '2', '5', '19');
INSERT INTO `razor_dim_date` VALUES ('3307', '2019-01-19', '2019', '1', '1', '3', '6', '20');
INSERT INTO `razor_dim_date` VALUES ('3308', '2019-01-20', '2019', '1', '1', '3', '0', '21');
INSERT INTO `razor_dim_date` VALUES ('3309', '2019-01-21', '2019', '1', '1', '3', '1', '22');
INSERT INTO `razor_dim_date` VALUES ('3310', '2019-01-22', '2019', '1', '1', '3', '2', '23');
INSERT INTO `razor_dim_date` VALUES ('3311', '2019-01-23', '2019', '1', '1', '3', '3', '24');
INSERT INTO `razor_dim_date` VALUES ('3312', '2019-01-24', '2019', '1', '1', '3', '4', '25');
INSERT INTO `razor_dim_date` VALUES ('3313', '2019-01-25', '2019', '1', '1', '3', '5', '26');
INSERT INTO `razor_dim_date` VALUES ('3314', '2019-01-26', '2019', '1', '1', '4', '6', '27');
INSERT INTO `razor_dim_date` VALUES ('3315', '2019-01-27', '2019', '1', '1', '4', '0', '28');
INSERT INTO `razor_dim_date` VALUES ('3316', '2019-01-28', '2019', '1', '1', '4', '1', '29');
INSERT INTO `razor_dim_date` VALUES ('3317', '2019-01-29', '2019', '1', '1', '4', '2', '30');
INSERT INTO `razor_dim_date` VALUES ('3318', '2019-01-30', '2019', '1', '1', '4', '3', '31');
INSERT INTO `razor_dim_date` VALUES ('3319', '2019-01-31', '2019', '1', '2', '4', '4', '1');
INSERT INTO `razor_dim_date` VALUES ('3320', '2019-02-01', '2019', '1', '2', '4', '5', '2');
INSERT INTO `razor_dim_date` VALUES ('3321', '2019-02-02', '2019', '1', '2', '5', '6', '3');
INSERT INTO `razor_dim_date` VALUES ('3322', '2019-02-03', '2019', '1', '2', '5', '0', '4');
INSERT INTO `razor_dim_date` VALUES ('3323', '2019-02-04', '2019', '1', '2', '5', '1', '5');
INSERT INTO `razor_dim_date` VALUES ('3324', '2019-02-05', '2019', '1', '2', '5', '2', '6');
INSERT INTO `razor_dim_date` VALUES ('3325', '2019-02-06', '2019', '1', '2', '5', '3', '7');
INSERT INTO `razor_dim_date` VALUES ('3326', '2019-02-07', '2019', '1', '2', '5', '4', '8');
INSERT INTO `razor_dim_date` VALUES ('3327', '2019-02-08', '2019', '1', '2', '5', '5', '9');
INSERT INTO `razor_dim_date` VALUES ('3328', '2019-02-09', '2019', '1', '2', '6', '6', '10');
INSERT INTO `razor_dim_date` VALUES ('3329', '2019-02-10', '2019', '1', '2', '6', '0', '11');
INSERT INTO `razor_dim_date` VALUES ('3330', '2019-02-11', '2019', '1', '2', '6', '1', '12');
INSERT INTO `razor_dim_date` VALUES ('3331', '2019-02-12', '2019', '1', '2', '6', '2', '13');
INSERT INTO `razor_dim_date` VALUES ('3332', '2019-02-13', '2019', '1', '2', '6', '3', '14');
INSERT INTO `razor_dim_date` VALUES ('3333', '2019-02-14', '2019', '1', '2', '6', '4', '15');
INSERT INTO `razor_dim_date` VALUES ('3334', '2019-02-15', '2019', '1', '2', '6', '5', '16');
INSERT INTO `razor_dim_date` VALUES ('3335', '2019-02-16', '2019', '1', '2', '7', '6', '17');
INSERT INTO `razor_dim_date` VALUES ('3336', '2019-02-17', '2019', '1', '2', '7', '0', '18');
INSERT INTO `razor_dim_date` VALUES ('3337', '2019-02-18', '2019', '1', '2', '7', '1', '19');
INSERT INTO `razor_dim_date` VALUES ('3338', '2019-02-19', '2019', '1', '2', '7', '2', '20');
INSERT INTO `razor_dim_date` VALUES ('3339', '2019-02-20', '2019', '1', '2', '7', '3', '21');
INSERT INTO `razor_dim_date` VALUES ('3340', '2019-02-21', '2019', '1', '2', '7', '4', '22');
INSERT INTO `razor_dim_date` VALUES ('3341', '2019-02-22', '2019', '1', '2', '7', '5', '23');
INSERT INTO `razor_dim_date` VALUES ('3342', '2019-02-23', '2019', '1', '2', '8', '6', '24');
INSERT INTO `razor_dim_date` VALUES ('3343', '2019-02-24', '2019', '1', '2', '8', '0', '25');
INSERT INTO `razor_dim_date` VALUES ('3344', '2019-02-25', '2019', '1', '2', '8', '1', '26');
INSERT INTO `razor_dim_date` VALUES ('3345', '2019-02-26', '2019', '1', '2', '8', '2', '27');
INSERT INTO `razor_dim_date` VALUES ('3346', '2019-02-27', '2019', '1', '2', '8', '3', '28');
INSERT INTO `razor_dim_date` VALUES ('3347', '2019-02-28', '2019', '1', '3', '8', '4', '1');
INSERT INTO `razor_dim_date` VALUES ('3348', '2019-03-01', '2019', '1', '3', '8', '5', '2');
INSERT INTO `razor_dim_date` VALUES ('3349', '2019-03-02', '2019', '1', '3', '9', '6', '3');
INSERT INTO `razor_dim_date` VALUES ('3350', '2019-03-03', '2019', '1', '3', '9', '0', '4');
INSERT INTO `razor_dim_date` VALUES ('3351', '2019-03-04', '2019', '1', '3', '9', '1', '5');
INSERT INTO `razor_dim_date` VALUES ('3352', '2019-03-05', '2019', '1', '3', '9', '2', '6');
INSERT INTO `razor_dim_date` VALUES ('3353', '2019-03-06', '2019', '1', '3', '9', '3', '7');
INSERT INTO `razor_dim_date` VALUES ('3354', '2019-03-07', '2019', '1', '3', '9', '4', '8');
INSERT INTO `razor_dim_date` VALUES ('3355', '2019-03-08', '2019', '1', '3', '9', '5', '9');
INSERT INTO `razor_dim_date` VALUES ('3356', '2019-03-09', '2019', '1', '3', '10', '6', '10');
INSERT INTO `razor_dim_date` VALUES ('3357', '2019-03-10', '2019', '1', '3', '10', '0', '11');
INSERT INTO `razor_dim_date` VALUES ('3358', '2019-03-11', '2019', '1', '3', '10', '1', '12');
INSERT INTO `razor_dim_date` VALUES ('3359', '2019-03-12', '2019', '1', '3', '10', '2', '13');
INSERT INTO `razor_dim_date` VALUES ('3360', '2019-03-13', '2019', '1', '3', '10', '3', '14');
INSERT INTO `razor_dim_date` VALUES ('3361', '2019-03-14', '2019', '1', '3', '10', '4', '15');
INSERT INTO `razor_dim_date` VALUES ('3362', '2019-03-15', '2019', '1', '3', '10', '5', '16');
INSERT INTO `razor_dim_date` VALUES ('3363', '2019-03-16', '2019', '1', '3', '11', '6', '17');
INSERT INTO `razor_dim_date` VALUES ('3364', '2019-03-17', '2019', '1', '3', '11', '0', '18');
INSERT INTO `razor_dim_date` VALUES ('3365', '2019-03-18', '2019', '1', '3', '11', '1', '19');
INSERT INTO `razor_dim_date` VALUES ('3366', '2019-03-19', '2019', '1', '3', '11', '2', '20');
INSERT INTO `razor_dim_date` VALUES ('3367', '2019-03-20', '2019', '1', '3', '11', '3', '21');
INSERT INTO `razor_dim_date` VALUES ('3368', '2019-03-21', '2019', '1', '3', '11', '4', '22');
INSERT INTO `razor_dim_date` VALUES ('3369', '2019-03-22', '2019', '1', '3', '11', '5', '23');
INSERT INTO `razor_dim_date` VALUES ('3370', '2019-03-23', '2019', '1', '3', '12', '6', '24');
INSERT INTO `razor_dim_date` VALUES ('3371', '2019-03-24', '2019', '1', '3', '12', '0', '25');
INSERT INTO `razor_dim_date` VALUES ('3372', '2019-03-25', '2019', '1', '3', '12', '1', '26');
INSERT INTO `razor_dim_date` VALUES ('3373', '2019-03-26', '2019', '1', '3', '12', '2', '27');
INSERT INTO `razor_dim_date` VALUES ('3374', '2019-03-27', '2019', '1', '3', '12', '3', '28');
INSERT INTO `razor_dim_date` VALUES ('3375', '2019-03-28', '2019', '1', '3', '12', '4', '29');
INSERT INTO `razor_dim_date` VALUES ('3376', '2019-03-29', '2019', '1', '3', '12', '5', '30');
INSERT INTO `razor_dim_date` VALUES ('3377', '2019-03-30', '2019', '1', '3', '13', '6', '31');
INSERT INTO `razor_dim_date` VALUES ('3378', '2019-03-31', '2019', '2', '4', '13', '0', '1');
INSERT INTO `razor_dim_date` VALUES ('3379', '2019-04-01', '2019', '2', '4', '13', '1', '2');
INSERT INTO `razor_dim_date` VALUES ('3380', '2019-04-02', '2019', '2', '4', '13', '2', '3');
INSERT INTO `razor_dim_date` VALUES ('3381', '2019-04-03', '2019', '2', '4', '13', '3', '4');
INSERT INTO `razor_dim_date` VALUES ('3382', '2019-04-04', '2019', '2', '4', '13', '4', '5');
INSERT INTO `razor_dim_date` VALUES ('3383', '2019-04-05', '2019', '2', '4', '13', '5', '6');
INSERT INTO `razor_dim_date` VALUES ('3384', '2019-04-06', '2019', '2', '4', '14', '6', '7');
INSERT INTO `razor_dim_date` VALUES ('3385', '2019-04-07', '2019', '2', '4', '14', '0', '8');
INSERT INTO `razor_dim_date` VALUES ('3386', '2019-04-08', '2019', '2', '4', '14', '1', '9');
INSERT INTO `razor_dim_date` VALUES ('3387', '2019-04-09', '2019', '2', '4', '14', '2', '10');
INSERT INTO `razor_dim_date` VALUES ('3388', '2019-04-10', '2019', '2', '4', '14', '3', '11');
INSERT INTO `razor_dim_date` VALUES ('3389', '2019-04-11', '2019', '2', '4', '14', '4', '12');
INSERT INTO `razor_dim_date` VALUES ('3390', '2019-04-12', '2019', '2', '4', '14', '5', '13');
INSERT INTO `razor_dim_date` VALUES ('3391', '2019-04-13', '2019', '2', '4', '15', '6', '14');
INSERT INTO `razor_dim_date` VALUES ('3392', '2019-04-14', '2019', '2', '4', '15', '0', '15');
INSERT INTO `razor_dim_date` VALUES ('3393', '2019-04-15', '2019', '2', '4', '15', '1', '16');
INSERT INTO `razor_dim_date` VALUES ('3394', '2019-04-16', '2019', '2', '4', '15', '2', '17');
INSERT INTO `razor_dim_date` VALUES ('3395', '2019-04-17', '2019', '2', '4', '15', '3', '18');
INSERT INTO `razor_dim_date` VALUES ('3396', '2019-04-18', '2019', '2', '4', '15', '4', '19');
INSERT INTO `razor_dim_date` VALUES ('3397', '2019-04-19', '2019', '2', '4', '15', '5', '20');
INSERT INTO `razor_dim_date` VALUES ('3398', '2019-04-20', '2019', '2', '4', '16', '6', '21');
INSERT INTO `razor_dim_date` VALUES ('3399', '2019-04-21', '2019', '2', '4', '16', '0', '22');
INSERT INTO `razor_dim_date` VALUES ('3400', '2019-04-22', '2019', '2', '4', '16', '1', '23');
INSERT INTO `razor_dim_date` VALUES ('3401', '2019-04-23', '2019', '2', '4', '16', '2', '24');
INSERT INTO `razor_dim_date` VALUES ('3402', '2019-04-24', '2019', '2', '4', '16', '3', '25');
INSERT INTO `razor_dim_date` VALUES ('3403', '2019-04-25', '2019', '2', '4', '16', '4', '26');
INSERT INTO `razor_dim_date` VALUES ('3404', '2019-04-26', '2019', '2', '4', '16', '5', '27');
INSERT INTO `razor_dim_date` VALUES ('3405', '2019-04-27', '2019', '2', '4', '17', '6', '28');
INSERT INTO `razor_dim_date` VALUES ('3406', '2019-04-28', '2019', '2', '4', '17', '0', '29');
INSERT INTO `razor_dim_date` VALUES ('3407', '2019-04-29', '2019', '2', '4', '17', '1', '30');
INSERT INTO `razor_dim_date` VALUES ('3408', '2019-04-30', '2019', '2', '5', '17', '2', '1');
INSERT INTO `razor_dim_date` VALUES ('3409', '2019-05-01', '2019', '2', '5', '17', '3', '2');
INSERT INTO `razor_dim_date` VALUES ('3410', '2019-05-02', '2019', '2', '5', '17', '4', '3');
INSERT INTO `razor_dim_date` VALUES ('3411', '2019-05-03', '2019', '2', '5', '17', '5', '4');
INSERT INTO `razor_dim_date` VALUES ('3412', '2019-05-04', '2019', '2', '5', '18', '6', '5');
INSERT INTO `razor_dim_date` VALUES ('3413', '2019-05-05', '2019', '2', '5', '18', '0', '6');
INSERT INTO `razor_dim_date` VALUES ('3414', '2019-05-06', '2019', '2', '5', '18', '1', '7');
INSERT INTO `razor_dim_date` VALUES ('3415', '2019-05-07', '2019', '2', '5', '18', '2', '8');
INSERT INTO `razor_dim_date` VALUES ('3416', '2019-05-08', '2019', '2', '5', '18', '3', '9');
INSERT INTO `razor_dim_date` VALUES ('3417', '2019-05-09', '2019', '2', '5', '18', '4', '10');
INSERT INTO `razor_dim_date` VALUES ('3418', '2019-05-10', '2019', '2', '5', '18', '5', '11');
INSERT INTO `razor_dim_date` VALUES ('3419', '2019-05-11', '2019', '2', '5', '19', '6', '12');
INSERT INTO `razor_dim_date` VALUES ('3420', '2019-05-12', '2019', '2', '5', '19', '0', '13');
INSERT INTO `razor_dim_date` VALUES ('3421', '2019-05-13', '2019', '2', '5', '19', '1', '14');
INSERT INTO `razor_dim_date` VALUES ('3422', '2019-05-14', '2019', '2', '5', '19', '2', '15');
INSERT INTO `razor_dim_date` VALUES ('3423', '2019-05-15', '2019', '2', '5', '19', '3', '16');
INSERT INTO `razor_dim_date` VALUES ('3424', '2019-05-16', '2019', '2', '5', '19', '4', '17');
INSERT INTO `razor_dim_date` VALUES ('3425', '2019-05-17', '2019', '2', '5', '19', '5', '18');
INSERT INTO `razor_dim_date` VALUES ('3426', '2019-05-18', '2019', '2', '5', '20', '6', '19');
INSERT INTO `razor_dim_date` VALUES ('3427', '2019-05-19', '2019', '2', '5', '20', '0', '20');
INSERT INTO `razor_dim_date` VALUES ('3428', '2019-05-20', '2019', '2', '5', '20', '1', '21');
INSERT INTO `razor_dim_date` VALUES ('3429', '2019-05-21', '2019', '2', '5', '20', '2', '22');
INSERT INTO `razor_dim_date` VALUES ('3430', '2019-05-22', '2019', '2', '5', '20', '3', '23');
INSERT INTO `razor_dim_date` VALUES ('3431', '2019-05-23', '2019', '2', '5', '20', '4', '24');
INSERT INTO `razor_dim_date` VALUES ('3432', '2019-05-24', '2019', '2', '5', '20', '5', '25');
INSERT INTO `razor_dim_date` VALUES ('3433', '2019-05-25', '2019', '2', '5', '21', '6', '26');
INSERT INTO `razor_dim_date` VALUES ('3434', '2019-05-26', '2019', '2', '5', '21', '0', '27');
INSERT INTO `razor_dim_date` VALUES ('3435', '2019-05-27', '2019', '2', '5', '21', '1', '28');
INSERT INTO `razor_dim_date` VALUES ('3436', '2019-05-28', '2019', '2', '5', '21', '2', '29');
INSERT INTO `razor_dim_date` VALUES ('3437', '2019-05-29', '2019', '2', '5', '21', '3', '30');
INSERT INTO `razor_dim_date` VALUES ('3438', '2019-05-30', '2019', '2', '5', '21', '4', '31');
INSERT INTO `razor_dim_date` VALUES ('3439', '2019-05-31', '2019', '2', '6', '21', '5', '1');
INSERT INTO `razor_dim_date` VALUES ('3440', '2019-06-01', '2019', '2', '6', '22', '6', '2');
INSERT INTO `razor_dim_date` VALUES ('3441', '2019-06-02', '2019', '2', '6', '22', '0', '3');
INSERT INTO `razor_dim_date` VALUES ('3442', '2019-06-03', '2019', '2', '6', '22', '1', '4');
INSERT INTO `razor_dim_date` VALUES ('3443', '2019-06-04', '2019', '2', '6', '22', '2', '5');
INSERT INTO `razor_dim_date` VALUES ('3444', '2019-06-05', '2019', '2', '6', '22', '3', '6');
INSERT INTO `razor_dim_date` VALUES ('3445', '2019-06-06', '2019', '2', '6', '22', '4', '7');
INSERT INTO `razor_dim_date` VALUES ('3446', '2019-06-07', '2019', '2', '6', '22', '5', '8');
INSERT INTO `razor_dim_date` VALUES ('3447', '2019-06-08', '2019', '2', '6', '23', '6', '9');
INSERT INTO `razor_dim_date` VALUES ('3448', '2019-06-09', '2019', '2', '6', '23', '0', '10');
INSERT INTO `razor_dim_date` VALUES ('3449', '2019-06-10', '2019', '2', '6', '23', '1', '11');
INSERT INTO `razor_dim_date` VALUES ('3450', '2019-06-11', '2019', '2', '6', '23', '2', '12');
INSERT INTO `razor_dim_date` VALUES ('3451', '2019-06-12', '2019', '2', '6', '23', '3', '13');
INSERT INTO `razor_dim_date` VALUES ('3452', '2019-06-13', '2019', '2', '6', '23', '4', '14');
INSERT INTO `razor_dim_date` VALUES ('3453', '2019-06-14', '2019', '2', '6', '23', '5', '15');
INSERT INTO `razor_dim_date` VALUES ('3454', '2019-06-15', '2019', '2', '6', '24', '6', '16');
INSERT INTO `razor_dim_date` VALUES ('3455', '2019-06-16', '2019', '2', '6', '24', '0', '17');
INSERT INTO `razor_dim_date` VALUES ('3456', '2019-06-17', '2019', '2', '6', '24', '1', '18');
INSERT INTO `razor_dim_date` VALUES ('3457', '2019-06-18', '2019', '2', '6', '24', '2', '19');
INSERT INTO `razor_dim_date` VALUES ('3458', '2019-06-19', '2019', '2', '6', '24', '3', '20');
INSERT INTO `razor_dim_date` VALUES ('3459', '2019-06-20', '2019', '2', '6', '24', '4', '21');
INSERT INTO `razor_dim_date` VALUES ('3460', '2019-06-21', '2019', '2', '6', '24', '5', '22');
INSERT INTO `razor_dim_date` VALUES ('3461', '2019-06-22', '2019', '2', '6', '25', '6', '23');
INSERT INTO `razor_dim_date` VALUES ('3462', '2019-06-23', '2019', '2', '6', '25', '0', '24');
INSERT INTO `razor_dim_date` VALUES ('3463', '2019-06-24', '2019', '2', '6', '25', '1', '25');
INSERT INTO `razor_dim_date` VALUES ('3464', '2019-06-25', '2019', '2', '6', '25', '2', '26');
INSERT INTO `razor_dim_date` VALUES ('3465', '2019-06-26', '2019', '2', '6', '25', '3', '27');
INSERT INTO `razor_dim_date` VALUES ('3466', '2019-06-27', '2019', '2', '6', '25', '4', '28');
INSERT INTO `razor_dim_date` VALUES ('3467', '2019-06-28', '2019', '2', '6', '25', '5', '29');
INSERT INTO `razor_dim_date` VALUES ('3468', '2019-06-29', '2019', '2', '6', '26', '6', '30');
INSERT INTO `razor_dim_date` VALUES ('3469', '2019-06-30', '2019', '3', '7', '26', '0', '1');
INSERT INTO `razor_dim_date` VALUES ('3470', '2019-07-01', '2019', '3', '7', '26', '1', '2');
INSERT INTO `razor_dim_date` VALUES ('3471', '2019-07-02', '2019', '3', '7', '26', '2', '3');
INSERT INTO `razor_dim_date` VALUES ('3472', '2019-07-03', '2019', '3', '7', '26', '3', '4');
INSERT INTO `razor_dim_date` VALUES ('3473', '2019-07-04', '2019', '3', '7', '26', '4', '5');
INSERT INTO `razor_dim_date` VALUES ('3474', '2019-07-05', '2019', '3', '7', '26', '5', '6');
INSERT INTO `razor_dim_date` VALUES ('3475', '2019-07-06', '2019', '3', '7', '27', '6', '7');
INSERT INTO `razor_dim_date` VALUES ('3476', '2019-07-07', '2019', '3', '7', '27', '0', '8');
INSERT INTO `razor_dim_date` VALUES ('3477', '2019-07-08', '2019', '3', '7', '27', '1', '9');
INSERT INTO `razor_dim_date` VALUES ('3478', '2019-07-09', '2019', '3', '7', '27', '2', '10');
INSERT INTO `razor_dim_date` VALUES ('3479', '2019-07-10', '2019', '3', '7', '27', '3', '11');
INSERT INTO `razor_dim_date` VALUES ('3480', '2019-07-11', '2019', '3', '7', '27', '4', '12');
INSERT INTO `razor_dim_date` VALUES ('3481', '2019-07-12', '2019', '3', '7', '27', '5', '13');
INSERT INTO `razor_dim_date` VALUES ('3482', '2019-07-13', '2019', '3', '7', '28', '6', '14');
INSERT INTO `razor_dim_date` VALUES ('3483', '2019-07-14', '2019', '3', '7', '28', '0', '15');
INSERT INTO `razor_dim_date` VALUES ('3484', '2019-07-15', '2019', '3', '7', '28', '1', '16');
INSERT INTO `razor_dim_date` VALUES ('3485', '2019-07-16', '2019', '3', '7', '28', '2', '17');
INSERT INTO `razor_dim_date` VALUES ('3486', '2019-07-17', '2019', '3', '7', '28', '3', '18');
INSERT INTO `razor_dim_date` VALUES ('3487', '2019-07-18', '2019', '3', '7', '28', '4', '19');
INSERT INTO `razor_dim_date` VALUES ('3488', '2019-07-19', '2019', '3', '7', '28', '5', '20');
INSERT INTO `razor_dim_date` VALUES ('3489', '2019-07-20', '2019', '3', '7', '29', '6', '21');
INSERT INTO `razor_dim_date` VALUES ('3490', '2019-07-21', '2019', '3', '7', '29', '0', '22');
INSERT INTO `razor_dim_date` VALUES ('3491', '2019-07-22', '2019', '3', '7', '29', '1', '23');
INSERT INTO `razor_dim_date` VALUES ('3492', '2019-07-23', '2019', '3', '7', '29', '2', '24');
INSERT INTO `razor_dim_date` VALUES ('3493', '2019-07-24', '2019', '3', '7', '29', '3', '25');
INSERT INTO `razor_dim_date` VALUES ('3494', '2019-07-25', '2019', '3', '7', '29', '4', '26');
INSERT INTO `razor_dim_date` VALUES ('3495', '2019-07-26', '2019', '3', '7', '29', '5', '27');
INSERT INTO `razor_dim_date` VALUES ('3496', '2019-07-27', '2019', '3', '7', '30', '6', '28');
INSERT INTO `razor_dim_date` VALUES ('3497', '2019-07-28', '2019', '3', '7', '30', '0', '29');
INSERT INTO `razor_dim_date` VALUES ('3498', '2019-07-29', '2019', '3', '7', '30', '1', '30');
INSERT INTO `razor_dim_date` VALUES ('3499', '2019-07-30', '2019', '3', '7', '30', '2', '31');
INSERT INTO `razor_dim_date` VALUES ('3500', '2019-07-31', '2019', '3', '8', '30', '3', '1');
INSERT INTO `razor_dim_date` VALUES ('3501', '2019-08-01', '2019', '3', '8', '30', '4', '2');
INSERT INTO `razor_dim_date` VALUES ('3502', '2019-08-02', '2019', '3', '8', '30', '5', '3');
INSERT INTO `razor_dim_date` VALUES ('3503', '2019-08-03', '2019', '3', '8', '31', '6', '4');
INSERT INTO `razor_dim_date` VALUES ('3504', '2019-08-04', '2019', '3', '8', '31', '0', '5');
INSERT INTO `razor_dim_date` VALUES ('3505', '2019-08-05', '2019', '3', '8', '31', '1', '6');
INSERT INTO `razor_dim_date` VALUES ('3506', '2019-08-06', '2019', '3', '8', '31', '2', '7');
INSERT INTO `razor_dim_date` VALUES ('3507', '2019-08-07', '2019', '3', '8', '31', '3', '8');
INSERT INTO `razor_dim_date` VALUES ('3508', '2019-08-08', '2019', '3', '8', '31', '4', '9');
INSERT INTO `razor_dim_date` VALUES ('3509', '2019-08-09', '2019', '3', '8', '31', '5', '10');
INSERT INTO `razor_dim_date` VALUES ('3510', '2019-08-10', '2019', '3', '8', '32', '6', '11');
INSERT INTO `razor_dim_date` VALUES ('3511', '2019-08-11', '2019', '3', '8', '32', '0', '12');
INSERT INTO `razor_dim_date` VALUES ('3512', '2019-08-12', '2019', '3', '8', '32', '1', '13');
INSERT INTO `razor_dim_date` VALUES ('3513', '2019-08-13', '2019', '3', '8', '32', '2', '14');
INSERT INTO `razor_dim_date` VALUES ('3514', '2019-08-14', '2019', '3', '8', '32', '3', '15');
INSERT INTO `razor_dim_date` VALUES ('3515', '2019-08-15', '2019', '3', '8', '32', '4', '16');
INSERT INTO `razor_dim_date` VALUES ('3516', '2019-08-16', '2019', '3', '8', '32', '5', '17');
INSERT INTO `razor_dim_date` VALUES ('3517', '2019-08-17', '2019', '3', '8', '33', '6', '18');
INSERT INTO `razor_dim_date` VALUES ('3518', '2019-08-18', '2019', '3', '8', '33', '0', '19');
INSERT INTO `razor_dim_date` VALUES ('3519', '2019-08-19', '2019', '3', '8', '33', '1', '20');
INSERT INTO `razor_dim_date` VALUES ('3520', '2019-08-20', '2019', '3', '8', '33', '2', '21');
INSERT INTO `razor_dim_date` VALUES ('3521', '2019-08-21', '2019', '3', '8', '33', '3', '22');
INSERT INTO `razor_dim_date` VALUES ('3522', '2019-08-22', '2019', '3', '8', '33', '4', '23');
INSERT INTO `razor_dim_date` VALUES ('3523', '2019-08-23', '2019', '3', '8', '33', '5', '24');
INSERT INTO `razor_dim_date` VALUES ('3524', '2019-08-24', '2019', '3', '8', '34', '6', '25');
INSERT INTO `razor_dim_date` VALUES ('3525', '2019-08-25', '2019', '3', '8', '34', '0', '26');
INSERT INTO `razor_dim_date` VALUES ('3526', '2019-08-26', '2019', '3', '8', '34', '1', '27');
INSERT INTO `razor_dim_date` VALUES ('3527', '2019-08-27', '2019', '3', '8', '34', '2', '28');
INSERT INTO `razor_dim_date` VALUES ('3528', '2019-08-28', '2019', '3', '8', '34', '3', '29');
INSERT INTO `razor_dim_date` VALUES ('3529', '2019-08-29', '2019', '3', '8', '34', '4', '30');
INSERT INTO `razor_dim_date` VALUES ('3530', '2019-08-30', '2019', '3', '8', '34', '5', '31');
INSERT INTO `razor_dim_date` VALUES ('3531', '2019-08-31', '2019', '3', '9', '35', '6', '1');
INSERT INTO `razor_dim_date` VALUES ('3532', '2019-09-01', '2019', '3', '9', '35', '0', '2');
INSERT INTO `razor_dim_date` VALUES ('3533', '2019-09-02', '2019', '3', '9', '35', '1', '3');
INSERT INTO `razor_dim_date` VALUES ('3534', '2019-09-03', '2019', '3', '9', '35', '2', '4');
INSERT INTO `razor_dim_date` VALUES ('3535', '2019-09-04', '2019', '3', '9', '35', '3', '5');
INSERT INTO `razor_dim_date` VALUES ('3536', '2019-09-05', '2019', '3', '9', '35', '4', '6');
INSERT INTO `razor_dim_date` VALUES ('3537', '2019-09-06', '2019', '3', '9', '35', '5', '7');
INSERT INTO `razor_dim_date` VALUES ('3538', '2019-09-07', '2019', '3', '9', '36', '6', '8');
INSERT INTO `razor_dim_date` VALUES ('3539', '2019-09-08', '2019', '3', '9', '36', '0', '9');
INSERT INTO `razor_dim_date` VALUES ('3540', '2019-09-09', '2019', '3', '9', '36', '1', '10');
INSERT INTO `razor_dim_date` VALUES ('3541', '2019-09-10', '2019', '3', '9', '36', '2', '11');
INSERT INTO `razor_dim_date` VALUES ('3542', '2019-09-11', '2019', '3', '9', '36', '3', '12');
INSERT INTO `razor_dim_date` VALUES ('3543', '2019-09-12', '2019', '3', '9', '36', '4', '13');
INSERT INTO `razor_dim_date` VALUES ('3544', '2019-09-13', '2019', '3', '9', '36', '5', '14');
INSERT INTO `razor_dim_date` VALUES ('3545', '2019-09-14', '2019', '3', '9', '37', '6', '15');
INSERT INTO `razor_dim_date` VALUES ('3546', '2019-09-15', '2019', '3', '9', '37', '0', '16');
INSERT INTO `razor_dim_date` VALUES ('3547', '2019-09-16', '2019', '3', '9', '37', '1', '17');
INSERT INTO `razor_dim_date` VALUES ('3548', '2019-09-17', '2019', '3', '9', '37', '2', '18');
INSERT INTO `razor_dim_date` VALUES ('3549', '2019-09-18', '2019', '3', '9', '37', '3', '19');
INSERT INTO `razor_dim_date` VALUES ('3550', '2019-09-19', '2019', '3', '9', '37', '4', '20');
INSERT INTO `razor_dim_date` VALUES ('3551', '2019-09-20', '2019', '3', '9', '37', '5', '21');
INSERT INTO `razor_dim_date` VALUES ('3552', '2019-09-21', '2019', '3', '9', '38', '6', '22');
INSERT INTO `razor_dim_date` VALUES ('3553', '2019-09-22', '2019', '3', '9', '38', '0', '23');
INSERT INTO `razor_dim_date` VALUES ('3554', '2019-09-23', '2019', '3', '9', '38', '1', '24');
INSERT INTO `razor_dim_date` VALUES ('3555', '2019-09-24', '2019', '3', '9', '38', '2', '25');
INSERT INTO `razor_dim_date` VALUES ('3556', '2019-09-25', '2019', '3', '9', '38', '3', '26');
INSERT INTO `razor_dim_date` VALUES ('3557', '2019-09-26', '2019', '3', '9', '38', '4', '27');
INSERT INTO `razor_dim_date` VALUES ('3558', '2019-09-27', '2019', '3', '9', '38', '5', '28');
INSERT INTO `razor_dim_date` VALUES ('3559', '2019-09-28', '2019', '3', '9', '39', '6', '29');
INSERT INTO `razor_dim_date` VALUES ('3560', '2019-09-29', '2019', '3', '9', '39', '0', '30');
INSERT INTO `razor_dim_date` VALUES ('3561', '2019-09-30', '2019', '4', '10', '39', '1', '1');
INSERT INTO `razor_dim_date` VALUES ('3562', '2019-10-01', '2019', '4', '10', '39', '2', '2');
INSERT INTO `razor_dim_date` VALUES ('3563', '2019-10-02', '2019', '4', '10', '39', '3', '3');
INSERT INTO `razor_dim_date` VALUES ('3564', '2019-10-03', '2019', '4', '10', '39', '4', '4');
INSERT INTO `razor_dim_date` VALUES ('3565', '2019-10-04', '2019', '4', '10', '39', '5', '5');
INSERT INTO `razor_dim_date` VALUES ('3566', '2019-10-05', '2019', '4', '10', '40', '6', '6');
INSERT INTO `razor_dim_date` VALUES ('3567', '2019-10-06', '2019', '4', '10', '40', '0', '7');
INSERT INTO `razor_dim_date` VALUES ('3568', '2019-10-07', '2019', '4', '10', '40', '1', '8');
INSERT INTO `razor_dim_date` VALUES ('3569', '2019-10-08', '2019', '4', '10', '40', '2', '9');
INSERT INTO `razor_dim_date` VALUES ('3570', '2019-10-09', '2019', '4', '10', '40', '3', '10');
INSERT INTO `razor_dim_date` VALUES ('3571', '2019-10-10', '2019', '4', '10', '40', '4', '11');
INSERT INTO `razor_dim_date` VALUES ('3572', '2019-10-11', '2019', '4', '10', '40', '5', '12');
INSERT INTO `razor_dim_date` VALUES ('3573', '2019-10-12', '2019', '4', '10', '41', '6', '13');
INSERT INTO `razor_dim_date` VALUES ('3574', '2019-10-13', '2019', '4', '10', '41', '0', '14');
INSERT INTO `razor_dim_date` VALUES ('3575', '2019-10-14', '2019', '4', '10', '41', '1', '15');
INSERT INTO `razor_dim_date` VALUES ('3576', '2019-10-15', '2019', '4', '10', '41', '2', '16');
INSERT INTO `razor_dim_date` VALUES ('3577', '2019-10-16', '2019', '4', '10', '41', '3', '17');
INSERT INTO `razor_dim_date` VALUES ('3578', '2019-10-17', '2019', '4', '10', '41', '4', '18');
INSERT INTO `razor_dim_date` VALUES ('3579', '2019-10-18', '2019', '4', '10', '41', '5', '19');
INSERT INTO `razor_dim_date` VALUES ('3580', '2019-10-19', '2019', '4', '10', '42', '6', '20');
INSERT INTO `razor_dim_date` VALUES ('3581', '2019-10-20', '2019', '4', '10', '42', '0', '21');
INSERT INTO `razor_dim_date` VALUES ('3582', '2019-10-21', '2019', '4', '10', '42', '1', '22');
INSERT INTO `razor_dim_date` VALUES ('3583', '2019-10-22', '2019', '4', '10', '42', '2', '23');
INSERT INTO `razor_dim_date` VALUES ('3584', '2019-10-23', '2019', '4', '10', '42', '3', '24');
INSERT INTO `razor_dim_date` VALUES ('3585', '2019-10-24', '2019', '4', '10', '42', '4', '25');
INSERT INTO `razor_dim_date` VALUES ('3586', '2019-10-25', '2019', '4', '10', '42', '5', '26');
INSERT INTO `razor_dim_date` VALUES ('3587', '2019-10-26', '2019', '4', '10', '43', '6', '27');
INSERT INTO `razor_dim_date` VALUES ('3588', '2019-10-27', '2019', '4', '10', '43', '0', '28');
INSERT INTO `razor_dim_date` VALUES ('3589', '2019-10-28', '2019', '4', '10', '43', '1', '29');
INSERT INTO `razor_dim_date` VALUES ('3590', '2019-10-29', '2019', '4', '10', '43', '2', '30');
INSERT INTO `razor_dim_date` VALUES ('3591', '2019-10-30', '2019', '4', '10', '43', '3', '31');
INSERT INTO `razor_dim_date` VALUES ('3592', '2019-10-31', '2019', '4', '11', '43', '4', '1');
INSERT INTO `razor_dim_date` VALUES ('3593', '2019-11-01', '2019', '4', '11', '43', '5', '2');
INSERT INTO `razor_dim_date` VALUES ('3594', '2019-11-02', '2019', '4', '11', '44', '6', '3');
INSERT INTO `razor_dim_date` VALUES ('3595', '2019-11-03', '2019', '4', '11', '44', '0', '4');
INSERT INTO `razor_dim_date` VALUES ('3596', '2019-11-04', '2019', '4', '11', '44', '1', '5');
INSERT INTO `razor_dim_date` VALUES ('3597', '2019-11-05', '2019', '4', '11', '44', '2', '6');
INSERT INTO `razor_dim_date` VALUES ('3598', '2019-11-06', '2019', '4', '11', '44', '3', '7');
INSERT INTO `razor_dim_date` VALUES ('3599', '2019-11-07', '2019', '4', '11', '44', '4', '8');
INSERT INTO `razor_dim_date` VALUES ('3600', '2019-11-08', '2019', '4', '11', '44', '5', '9');
INSERT INTO `razor_dim_date` VALUES ('3601', '2019-11-09', '2019', '4', '11', '45', '6', '10');
INSERT INTO `razor_dim_date` VALUES ('3602', '2019-11-10', '2019', '4', '11', '45', '0', '11');
INSERT INTO `razor_dim_date` VALUES ('3603', '2019-11-11', '2019', '4', '11', '45', '1', '12');
INSERT INTO `razor_dim_date` VALUES ('3604', '2019-11-12', '2019', '4', '11', '45', '2', '13');
INSERT INTO `razor_dim_date` VALUES ('3605', '2019-11-13', '2019', '4', '11', '45', '3', '14');
INSERT INTO `razor_dim_date` VALUES ('3606', '2019-11-14', '2019', '4', '11', '45', '4', '15');
INSERT INTO `razor_dim_date` VALUES ('3607', '2019-11-15', '2019', '4', '11', '45', '5', '16');
INSERT INTO `razor_dim_date` VALUES ('3608', '2019-11-16', '2019', '4', '11', '46', '6', '17');
INSERT INTO `razor_dim_date` VALUES ('3609', '2019-11-17', '2019', '4', '11', '46', '0', '18');
INSERT INTO `razor_dim_date` VALUES ('3610', '2019-11-18', '2019', '4', '11', '46', '1', '19');
INSERT INTO `razor_dim_date` VALUES ('3611', '2019-11-19', '2019', '4', '11', '46', '2', '20');
INSERT INTO `razor_dim_date` VALUES ('3612', '2019-11-20', '2019', '4', '11', '46', '3', '21');
INSERT INTO `razor_dim_date` VALUES ('3613', '2019-11-21', '2019', '4', '11', '46', '4', '22');
INSERT INTO `razor_dim_date` VALUES ('3614', '2019-11-22', '2019', '4', '11', '46', '5', '23');
INSERT INTO `razor_dim_date` VALUES ('3615', '2019-11-23', '2019', '4', '11', '47', '6', '24');
INSERT INTO `razor_dim_date` VALUES ('3616', '2019-11-24', '2019', '4', '11', '47', '0', '25');
INSERT INTO `razor_dim_date` VALUES ('3617', '2019-11-25', '2019', '4', '11', '47', '1', '26');
INSERT INTO `razor_dim_date` VALUES ('3618', '2019-11-26', '2019', '4', '11', '47', '2', '27');
INSERT INTO `razor_dim_date` VALUES ('3619', '2019-11-27', '2019', '4', '11', '47', '3', '28');
INSERT INTO `razor_dim_date` VALUES ('3620', '2019-11-28', '2019', '4', '11', '47', '4', '29');
INSERT INTO `razor_dim_date` VALUES ('3621', '2019-11-29', '2019', '4', '11', '47', '5', '30');
INSERT INTO `razor_dim_date` VALUES ('3622', '2019-11-30', '2019', '4', '12', '48', '6', '1');
INSERT INTO `razor_dim_date` VALUES ('3623', '2019-12-01', '2019', '4', '12', '48', '0', '2');
INSERT INTO `razor_dim_date` VALUES ('3624', '2019-12-02', '2019', '4', '12', '48', '1', '3');
INSERT INTO `razor_dim_date` VALUES ('3625', '2019-12-03', '2019', '4', '12', '48', '2', '4');
INSERT INTO `razor_dim_date` VALUES ('3626', '2019-12-04', '2019', '4', '12', '48', '3', '5');
INSERT INTO `razor_dim_date` VALUES ('3627', '2019-12-05', '2019', '4', '12', '48', '4', '6');
INSERT INTO `razor_dim_date` VALUES ('3628', '2019-12-06', '2019', '4', '12', '48', '5', '7');
INSERT INTO `razor_dim_date` VALUES ('3629', '2019-12-07', '2019', '4', '12', '49', '6', '8');
INSERT INTO `razor_dim_date` VALUES ('3630', '2019-12-08', '2019', '4', '12', '49', '0', '9');
INSERT INTO `razor_dim_date` VALUES ('3631', '2019-12-09', '2019', '4', '12', '49', '1', '10');
INSERT INTO `razor_dim_date` VALUES ('3632', '2019-12-10', '2019', '4', '12', '49', '2', '11');
INSERT INTO `razor_dim_date` VALUES ('3633', '2019-12-11', '2019', '4', '12', '49', '3', '12');
INSERT INTO `razor_dim_date` VALUES ('3634', '2019-12-12', '2019', '4', '12', '49', '4', '13');
INSERT INTO `razor_dim_date` VALUES ('3635', '2019-12-13', '2019', '4', '12', '49', '5', '14');
INSERT INTO `razor_dim_date` VALUES ('3636', '2019-12-14', '2019', '4', '12', '50', '6', '15');
INSERT INTO `razor_dim_date` VALUES ('3637', '2019-12-15', '2019', '4', '12', '50', '0', '16');
INSERT INTO `razor_dim_date` VALUES ('3638', '2019-12-16', '2019', '4', '12', '50', '1', '17');
INSERT INTO `razor_dim_date` VALUES ('3639', '2019-12-17', '2019', '4', '12', '50', '2', '18');
INSERT INTO `razor_dim_date` VALUES ('3640', '2019-12-18', '2019', '4', '12', '50', '3', '19');
INSERT INTO `razor_dim_date` VALUES ('3641', '2019-12-19', '2019', '4', '12', '50', '4', '20');
INSERT INTO `razor_dim_date` VALUES ('3642', '2019-12-20', '2019', '4', '12', '50', '5', '21');
INSERT INTO `razor_dim_date` VALUES ('3643', '2019-12-21', '2019', '4', '12', '51', '6', '22');
INSERT INTO `razor_dim_date` VALUES ('3644', '2019-12-22', '2019', '4', '12', '51', '0', '23');
INSERT INTO `razor_dim_date` VALUES ('3645', '2019-12-23', '2019', '4', '12', '51', '1', '24');
INSERT INTO `razor_dim_date` VALUES ('3646', '2019-12-24', '2019', '4', '12', '51', '2', '25');
INSERT INTO `razor_dim_date` VALUES ('3647', '2019-12-25', '2019', '4', '12', '51', '3', '26');
INSERT INTO `razor_dim_date` VALUES ('3648', '2019-12-26', '2019', '4', '12', '51', '4', '27');
INSERT INTO `razor_dim_date` VALUES ('3649', '2019-12-27', '2019', '4', '12', '51', '5', '28');
INSERT INTO `razor_dim_date` VALUES ('3650', '2019-12-28', '2019', '4', '12', '52', '6', '29');
INSERT INTO `razor_dim_date` VALUES ('3651', '2019-12-29', '2019', '4', '12', '52', '0', '30');
INSERT INTO `razor_dim_date` VALUES ('3652', '2019-12-30', '2019', '4', '12', '52', '1', '31');
INSERT INTO `razor_dim_date` VALUES ('3653', '2019-12-31', '2020', '1', '1', '0', '2', '1');
INSERT INTO `razor_dim_date` VALUES ('3654', '2020-01-01', '2020', '1', '1', '0', '3', '2');
INSERT INTO `razor_dim_date` VALUES ('3655', '2020-01-02', '2020', '1', '1', '0', '4', '3');
INSERT INTO `razor_dim_date` VALUES ('3656', '2020-01-03', '2020', '1', '1', '0', '5', '4');
INSERT INTO `razor_dim_date` VALUES ('3657', '2020-01-04', '2020', '1', '1', '1', '6', '5');
INSERT INTO `razor_dim_date` VALUES ('3658', '2020-01-05', '2020', '1', '1', '1', '0', '6');
INSERT INTO `razor_dim_date` VALUES ('3659', '2020-01-06', '2020', '1', '1', '1', '1', '7');
INSERT INTO `razor_dim_date` VALUES ('3660', '2020-01-07', '2020', '1', '1', '1', '2', '8');
INSERT INTO `razor_dim_date` VALUES ('3661', '2020-01-08', '2020', '1', '1', '1', '3', '9');
INSERT INTO `razor_dim_date` VALUES ('3662', '2020-01-09', '2020', '1', '1', '1', '4', '10');
INSERT INTO `razor_dim_date` VALUES ('3663', '2020-01-10', '2020', '1', '1', '1', '5', '11');
INSERT INTO `razor_dim_date` VALUES ('3664', '2020-01-11', '2020', '1', '1', '2', '6', '12');
INSERT INTO `razor_dim_date` VALUES ('3665', '2020-01-12', '2020', '1', '1', '2', '0', '13');
INSERT INTO `razor_dim_date` VALUES ('3666', '2020-01-13', '2020', '1', '1', '2', '1', '14');
INSERT INTO `razor_dim_date` VALUES ('3667', '2020-01-14', '2020', '1', '1', '2', '2', '15');
INSERT INTO `razor_dim_date` VALUES ('3668', '2020-01-15', '2020', '1', '1', '2', '3', '16');
INSERT INTO `razor_dim_date` VALUES ('3669', '2020-01-16', '2020', '1', '1', '2', '4', '17');
INSERT INTO `razor_dim_date` VALUES ('3670', '2020-01-17', '2020', '1', '1', '2', '5', '18');
INSERT INTO `razor_dim_date` VALUES ('3671', '2020-01-18', '2020', '1', '1', '3', '6', '19');
INSERT INTO `razor_dim_date` VALUES ('3672', '2020-01-19', '2020', '1', '1', '3', '0', '20');
INSERT INTO `razor_dim_date` VALUES ('3673', '2020-01-20', '2020', '1', '1', '3', '1', '21');
INSERT INTO `razor_dim_date` VALUES ('3674', '2020-01-21', '2020', '1', '1', '3', '2', '22');
INSERT INTO `razor_dim_date` VALUES ('3675', '2020-01-22', '2020', '1', '1', '3', '3', '23');
INSERT INTO `razor_dim_date` VALUES ('3676', '2020-01-23', '2020', '1', '1', '3', '4', '24');
INSERT INTO `razor_dim_date` VALUES ('3677', '2020-01-24', '2020', '1', '1', '3', '5', '25');
INSERT INTO `razor_dim_date` VALUES ('3678', '2020-01-25', '2020', '1', '1', '4', '6', '26');
INSERT INTO `razor_dim_date` VALUES ('3679', '2020-01-26', '2020', '1', '1', '4', '0', '27');
INSERT INTO `razor_dim_date` VALUES ('3680', '2020-01-27', '2020', '1', '1', '4', '1', '28');
INSERT INTO `razor_dim_date` VALUES ('3681', '2020-01-28', '2020', '1', '1', '4', '2', '29');
INSERT INTO `razor_dim_date` VALUES ('3682', '2020-01-29', '2020', '1', '1', '4', '3', '30');
INSERT INTO `razor_dim_date` VALUES ('3683', '2020-01-30', '2020', '1', '1', '4', '4', '31');
INSERT INTO `razor_dim_date` VALUES ('3684', '2020-01-31', '2020', '1', '2', '4', '5', '1');
INSERT INTO `razor_dim_date` VALUES ('3685', '2020-02-01', '2020', '1', '2', '5', '6', '2');
INSERT INTO `razor_dim_date` VALUES ('3686', '2020-02-02', '2020', '1', '2', '5', '0', '3');
INSERT INTO `razor_dim_date` VALUES ('3687', '2020-02-03', '2020', '1', '2', '5', '1', '4');
INSERT INTO `razor_dim_date` VALUES ('3688', '2020-02-04', '2020', '1', '2', '5', '2', '5');
INSERT INTO `razor_dim_date` VALUES ('3689', '2020-02-05', '2020', '1', '2', '5', '3', '6');
INSERT INTO `razor_dim_date` VALUES ('3690', '2020-02-06', '2020', '1', '2', '5', '4', '7');
INSERT INTO `razor_dim_date` VALUES ('3691', '2020-02-07', '2020', '1', '2', '5', '5', '8');
INSERT INTO `razor_dim_date` VALUES ('3692', '2020-02-08', '2020', '1', '2', '6', '6', '9');
INSERT INTO `razor_dim_date` VALUES ('3693', '2020-02-09', '2020', '1', '2', '6', '0', '10');
INSERT INTO `razor_dim_date` VALUES ('3694', '2020-02-10', '2020', '1', '2', '6', '1', '11');
INSERT INTO `razor_dim_date` VALUES ('3695', '2020-02-11', '2020', '1', '2', '6', '2', '12');
INSERT INTO `razor_dim_date` VALUES ('3696', '2020-02-12', '2020', '1', '2', '6', '3', '13');
INSERT INTO `razor_dim_date` VALUES ('3697', '2020-02-13', '2020', '1', '2', '6', '4', '14');
INSERT INTO `razor_dim_date` VALUES ('3698', '2020-02-14', '2020', '1', '2', '6', '5', '15');
INSERT INTO `razor_dim_date` VALUES ('3699', '2020-02-15', '2020', '1', '2', '7', '6', '16');
INSERT INTO `razor_dim_date` VALUES ('3700', '2020-02-16', '2020', '1', '2', '7', '0', '17');
INSERT INTO `razor_dim_date` VALUES ('3701', '2020-02-17', '2020', '1', '2', '7', '1', '18');
INSERT INTO `razor_dim_date` VALUES ('3702', '2020-02-18', '2020', '1', '2', '7', '2', '19');
INSERT INTO `razor_dim_date` VALUES ('3703', '2020-02-19', '2020', '1', '2', '7', '3', '20');
INSERT INTO `razor_dim_date` VALUES ('3704', '2020-02-20', '2020', '1', '2', '7', '4', '21');
INSERT INTO `razor_dim_date` VALUES ('3705', '2020-02-21', '2020', '1', '2', '7', '5', '22');
INSERT INTO `razor_dim_date` VALUES ('3706', '2020-02-22', '2020', '1', '2', '8', '6', '23');
INSERT INTO `razor_dim_date` VALUES ('3707', '2020-02-23', '2020', '1', '2', '8', '0', '24');
INSERT INTO `razor_dim_date` VALUES ('3708', '2020-02-24', '2020', '1', '2', '8', '1', '25');
INSERT INTO `razor_dim_date` VALUES ('3709', '2020-02-25', '2020', '1', '2', '8', '2', '26');
INSERT INTO `razor_dim_date` VALUES ('3710', '2020-02-26', '2020', '1', '2', '8', '3', '27');
INSERT INTO `razor_dim_date` VALUES ('3711', '2020-02-27', '2020', '1', '2', '8', '4', '28');
INSERT INTO `razor_dim_date` VALUES ('3712', '2020-02-28', '2020', '1', '2', '8', '5', '29');
INSERT INTO `razor_dim_date` VALUES ('3713', '2020-02-29', '2020', '1', '3', '9', '6', '1');
INSERT INTO `razor_dim_date` VALUES ('3714', '2020-03-01', '2020', '1', '3', '9', '0', '2');
INSERT INTO `razor_dim_date` VALUES ('3715', '2020-03-02', '2020', '1', '3', '9', '1', '3');
INSERT INTO `razor_dim_date` VALUES ('3716', '2020-03-03', '2020', '1', '3', '9', '2', '4');
INSERT INTO `razor_dim_date` VALUES ('3717', '2020-03-04', '2020', '1', '3', '9', '3', '5');
INSERT INTO `razor_dim_date` VALUES ('3718', '2020-03-05', '2020', '1', '3', '9', '4', '6');
INSERT INTO `razor_dim_date` VALUES ('3719', '2020-03-06', '2020', '1', '3', '9', '5', '7');
INSERT INTO `razor_dim_date` VALUES ('3720', '2020-03-07', '2020', '1', '3', '10', '6', '8');
INSERT INTO `razor_dim_date` VALUES ('3721', '2020-03-08', '2020', '1', '3', '10', '0', '9');
INSERT INTO `razor_dim_date` VALUES ('3722', '2020-03-09', '2020', '1', '3', '10', '1', '10');
INSERT INTO `razor_dim_date` VALUES ('3723', '2020-03-10', '2020', '1', '3', '10', '2', '11');
INSERT INTO `razor_dim_date` VALUES ('3724', '2020-03-11', '2020', '1', '3', '10', '3', '12');
INSERT INTO `razor_dim_date` VALUES ('3725', '2020-03-12', '2020', '1', '3', '10', '4', '13');
INSERT INTO `razor_dim_date` VALUES ('3726', '2020-03-13', '2020', '1', '3', '10', '5', '14');
INSERT INTO `razor_dim_date` VALUES ('3727', '2020-03-14', '2020', '1', '3', '11', '6', '15');
INSERT INTO `razor_dim_date` VALUES ('3728', '2020-03-15', '2020', '1', '3', '11', '0', '16');
INSERT INTO `razor_dim_date` VALUES ('3729', '2020-03-16', '2020', '1', '3', '11', '1', '17');
INSERT INTO `razor_dim_date` VALUES ('3730', '2020-03-17', '2020', '1', '3', '11', '2', '18');
INSERT INTO `razor_dim_date` VALUES ('3731', '2020-03-18', '2020', '1', '3', '11', '3', '19');
INSERT INTO `razor_dim_date` VALUES ('3732', '2020-03-19', '2020', '1', '3', '11', '4', '20');
INSERT INTO `razor_dim_date` VALUES ('3733', '2020-03-20', '2020', '1', '3', '11', '5', '21');
INSERT INTO `razor_dim_date` VALUES ('3734', '2020-03-21', '2020', '1', '3', '12', '6', '22');
INSERT INTO `razor_dim_date` VALUES ('3735', '2020-03-22', '2020', '1', '3', '12', '0', '23');
INSERT INTO `razor_dim_date` VALUES ('3736', '2020-03-23', '2020', '1', '3', '12', '1', '24');
INSERT INTO `razor_dim_date` VALUES ('3737', '2020-03-24', '2020', '1', '3', '12', '2', '25');
INSERT INTO `razor_dim_date` VALUES ('3738', '2020-03-25', '2020', '1', '3', '12', '3', '26');
INSERT INTO `razor_dim_date` VALUES ('3739', '2020-03-26', '2020', '1', '3', '12', '4', '27');
INSERT INTO `razor_dim_date` VALUES ('3740', '2020-03-27', '2020', '1', '3', '12', '5', '28');
INSERT INTO `razor_dim_date` VALUES ('3741', '2020-03-28', '2020', '1', '3', '13', '6', '29');
INSERT INTO `razor_dim_date` VALUES ('3742', '2020-03-29', '2020', '1', '3', '13', '0', '30');
INSERT INTO `razor_dim_date` VALUES ('3743', '2020-03-30', '2020', '1', '3', '13', '1', '31');
INSERT INTO `razor_dim_date` VALUES ('3744', '2020-03-31', '2020', '2', '4', '13', '2', '1');
INSERT INTO `razor_dim_date` VALUES ('3745', '2020-04-01', '2020', '2', '4', '13', '3', '2');
INSERT INTO `razor_dim_date` VALUES ('3746', '2020-04-02', '2020', '2', '4', '13', '4', '3');
INSERT INTO `razor_dim_date` VALUES ('3747', '2020-04-03', '2020', '2', '4', '13', '5', '4');
INSERT INTO `razor_dim_date` VALUES ('3748', '2020-04-04', '2020', '2', '4', '14', '6', '5');
INSERT INTO `razor_dim_date` VALUES ('3749', '2020-04-05', '2020', '2', '4', '14', '0', '6');
INSERT INTO `razor_dim_date` VALUES ('3750', '2020-04-06', '2020', '2', '4', '14', '1', '7');
INSERT INTO `razor_dim_date` VALUES ('3751', '2020-04-07', '2020', '2', '4', '14', '2', '8');
INSERT INTO `razor_dim_date` VALUES ('3752', '2020-04-08', '2020', '2', '4', '14', '3', '9');
INSERT INTO `razor_dim_date` VALUES ('3753', '2020-04-09', '2020', '2', '4', '14', '4', '10');
INSERT INTO `razor_dim_date` VALUES ('3754', '2020-04-10', '2020', '2', '4', '14', '5', '11');
INSERT INTO `razor_dim_date` VALUES ('3755', '2020-04-11', '2020', '2', '4', '15', '6', '12');
INSERT INTO `razor_dim_date` VALUES ('3756', '2020-04-12', '2020', '2', '4', '15', '0', '13');
INSERT INTO `razor_dim_date` VALUES ('3757', '2020-04-13', '2020', '2', '4', '15', '1', '14');
INSERT INTO `razor_dim_date` VALUES ('3758', '2020-04-14', '2020', '2', '4', '15', '2', '15');
INSERT INTO `razor_dim_date` VALUES ('3759', '2020-04-15', '2020', '2', '4', '15', '3', '16');
INSERT INTO `razor_dim_date` VALUES ('3760', '2020-04-16', '2020', '2', '4', '15', '4', '17');
INSERT INTO `razor_dim_date` VALUES ('3761', '2020-04-17', '2020', '2', '4', '15', '5', '18');
INSERT INTO `razor_dim_date` VALUES ('3762', '2020-04-18', '2020', '2', '4', '16', '6', '19');
INSERT INTO `razor_dim_date` VALUES ('3763', '2020-04-19', '2020', '2', '4', '16', '0', '20');
INSERT INTO `razor_dim_date` VALUES ('3764', '2020-04-20', '2020', '2', '4', '16', '1', '21');
INSERT INTO `razor_dim_date` VALUES ('3765', '2020-04-21', '2020', '2', '4', '16', '2', '22');
INSERT INTO `razor_dim_date` VALUES ('3766', '2020-04-22', '2020', '2', '4', '16', '3', '23');
INSERT INTO `razor_dim_date` VALUES ('3767', '2020-04-23', '2020', '2', '4', '16', '4', '24');
INSERT INTO `razor_dim_date` VALUES ('3768', '2020-04-24', '2020', '2', '4', '16', '5', '25');
INSERT INTO `razor_dim_date` VALUES ('3769', '2020-04-25', '2020', '2', '4', '17', '6', '26');
INSERT INTO `razor_dim_date` VALUES ('3770', '2020-04-26', '2020', '2', '4', '17', '0', '27');
INSERT INTO `razor_dim_date` VALUES ('3771', '2020-04-27', '2020', '2', '4', '17', '1', '28');
INSERT INTO `razor_dim_date` VALUES ('3772', '2020-04-28', '2020', '2', '4', '17', '2', '29');
INSERT INTO `razor_dim_date` VALUES ('3773', '2020-04-29', '2020', '2', '4', '17', '3', '30');
INSERT INTO `razor_dim_date` VALUES ('3774', '2020-04-30', '2020', '2', '5', '17', '4', '1');
INSERT INTO `razor_dim_date` VALUES ('3775', '2020-05-01', '2020', '2', '5', '17', '5', '2');
INSERT INTO `razor_dim_date` VALUES ('3776', '2020-05-02', '2020', '2', '5', '18', '6', '3');
INSERT INTO `razor_dim_date` VALUES ('3777', '2020-05-03', '2020', '2', '5', '18', '0', '4');
INSERT INTO `razor_dim_date` VALUES ('3778', '2020-05-04', '2020', '2', '5', '18', '1', '5');
INSERT INTO `razor_dim_date` VALUES ('3779', '2020-05-05', '2020', '2', '5', '18', '2', '6');
INSERT INTO `razor_dim_date` VALUES ('3780', '2020-05-06', '2020', '2', '5', '18', '3', '7');
INSERT INTO `razor_dim_date` VALUES ('3781', '2020-05-07', '2020', '2', '5', '18', '4', '8');
INSERT INTO `razor_dim_date` VALUES ('3782', '2020-05-08', '2020', '2', '5', '18', '5', '9');
INSERT INTO `razor_dim_date` VALUES ('3783', '2020-05-09', '2020', '2', '5', '19', '6', '10');
INSERT INTO `razor_dim_date` VALUES ('3784', '2020-05-10', '2020', '2', '5', '19', '0', '11');
INSERT INTO `razor_dim_date` VALUES ('3785', '2020-05-11', '2020', '2', '5', '19', '1', '12');
INSERT INTO `razor_dim_date` VALUES ('3786', '2020-05-12', '2020', '2', '5', '19', '2', '13');
INSERT INTO `razor_dim_date` VALUES ('3787', '2020-05-13', '2020', '2', '5', '19', '3', '14');
INSERT INTO `razor_dim_date` VALUES ('3788', '2020-05-14', '2020', '2', '5', '19', '4', '15');
INSERT INTO `razor_dim_date` VALUES ('3789', '2020-05-15', '2020', '2', '5', '19', '5', '16');
INSERT INTO `razor_dim_date` VALUES ('3790', '2020-05-16', '2020', '2', '5', '20', '6', '17');
INSERT INTO `razor_dim_date` VALUES ('3791', '2020-05-17', '2020', '2', '5', '20', '0', '18');
INSERT INTO `razor_dim_date` VALUES ('3792', '2020-05-18', '2020', '2', '5', '20', '1', '19');
INSERT INTO `razor_dim_date` VALUES ('3793', '2020-05-19', '2020', '2', '5', '20', '2', '20');
INSERT INTO `razor_dim_date` VALUES ('3794', '2020-05-20', '2020', '2', '5', '20', '3', '21');
INSERT INTO `razor_dim_date` VALUES ('3795', '2020-05-21', '2020', '2', '5', '20', '4', '22');
INSERT INTO `razor_dim_date` VALUES ('3796', '2020-05-22', '2020', '2', '5', '20', '5', '23');
INSERT INTO `razor_dim_date` VALUES ('3797', '2020-05-23', '2020', '2', '5', '21', '6', '24');
INSERT INTO `razor_dim_date` VALUES ('3798', '2020-05-24', '2020', '2', '5', '21', '0', '25');
INSERT INTO `razor_dim_date` VALUES ('3799', '2020-05-25', '2020', '2', '5', '21', '1', '26');
INSERT INTO `razor_dim_date` VALUES ('3800', '2020-05-26', '2020', '2', '5', '21', '2', '27');
INSERT INTO `razor_dim_date` VALUES ('3801', '2020-05-27', '2020', '2', '5', '21', '3', '28');
INSERT INTO `razor_dim_date` VALUES ('3802', '2020-05-28', '2020', '2', '5', '21', '4', '29');
INSERT INTO `razor_dim_date` VALUES ('3803', '2020-05-29', '2020', '2', '5', '21', '5', '30');
INSERT INTO `razor_dim_date` VALUES ('3804', '2020-05-30', '2020', '2', '5', '22', '6', '31');
INSERT INTO `razor_dim_date` VALUES ('3805', '2020-05-31', '2020', '2', '6', '22', '0', '1');
INSERT INTO `razor_dim_date` VALUES ('3806', '2020-06-01', '2020', '2', '6', '22', '1', '2');
INSERT INTO `razor_dim_date` VALUES ('3807', '2020-06-02', '2020', '2', '6', '22', '2', '3');
INSERT INTO `razor_dim_date` VALUES ('3808', '2020-06-03', '2020', '2', '6', '22', '3', '4');
INSERT INTO `razor_dim_date` VALUES ('3809', '2020-06-04', '2020', '2', '6', '22', '4', '5');
INSERT INTO `razor_dim_date` VALUES ('3810', '2020-06-05', '2020', '2', '6', '22', '5', '6');
INSERT INTO `razor_dim_date` VALUES ('3811', '2020-06-06', '2020', '2', '6', '23', '6', '7');
INSERT INTO `razor_dim_date` VALUES ('3812', '2020-06-07', '2020', '2', '6', '23', '0', '8');
INSERT INTO `razor_dim_date` VALUES ('3813', '2020-06-08', '2020', '2', '6', '23', '1', '9');
INSERT INTO `razor_dim_date` VALUES ('3814', '2020-06-09', '2020', '2', '6', '23', '2', '10');
INSERT INTO `razor_dim_date` VALUES ('3815', '2020-06-10', '2020', '2', '6', '23', '3', '11');
INSERT INTO `razor_dim_date` VALUES ('3816', '2020-06-11', '2020', '2', '6', '23', '4', '12');
INSERT INTO `razor_dim_date` VALUES ('3817', '2020-06-12', '2020', '2', '6', '23', '5', '13');
INSERT INTO `razor_dim_date` VALUES ('3818', '2020-06-13', '2020', '2', '6', '24', '6', '14');
INSERT INTO `razor_dim_date` VALUES ('3819', '2020-06-14', '2020', '2', '6', '24', '0', '15');
INSERT INTO `razor_dim_date` VALUES ('3820', '2020-06-15', '2020', '2', '6', '24', '1', '16');
INSERT INTO `razor_dim_date` VALUES ('3821', '2020-06-16', '2020', '2', '6', '24', '2', '17');
INSERT INTO `razor_dim_date` VALUES ('3822', '2020-06-17', '2020', '2', '6', '24', '3', '18');
INSERT INTO `razor_dim_date` VALUES ('3823', '2020-06-18', '2020', '2', '6', '24', '4', '19');
INSERT INTO `razor_dim_date` VALUES ('3824', '2020-06-19', '2020', '2', '6', '24', '5', '20');
INSERT INTO `razor_dim_date` VALUES ('3825', '2020-06-20', '2020', '2', '6', '25', '6', '21');
INSERT INTO `razor_dim_date` VALUES ('3826', '2020-06-21', '2020', '2', '6', '25', '0', '22');
INSERT INTO `razor_dim_date` VALUES ('3827', '2020-06-22', '2020', '2', '6', '25', '1', '23');
INSERT INTO `razor_dim_date` VALUES ('3828', '2020-06-23', '2020', '2', '6', '25', '2', '24');
INSERT INTO `razor_dim_date` VALUES ('3829', '2020-06-24', '2020', '2', '6', '25', '3', '25');
INSERT INTO `razor_dim_date` VALUES ('3830', '2020-06-25', '2020', '2', '6', '25', '4', '26');
INSERT INTO `razor_dim_date` VALUES ('3831', '2020-06-26', '2020', '2', '6', '25', '5', '27');
INSERT INTO `razor_dim_date` VALUES ('3832', '2020-06-27', '2020', '2', '6', '26', '6', '28');
INSERT INTO `razor_dim_date` VALUES ('3833', '2020-06-28', '2020', '2', '6', '26', '0', '29');
INSERT INTO `razor_dim_date` VALUES ('3834', '2020-06-29', '2020', '2', '6', '26', '1', '30');
INSERT INTO `razor_dim_date` VALUES ('3835', '2020-06-30', '2020', '3', '7', '26', '2', '1');
INSERT INTO `razor_dim_date` VALUES ('3836', '2020-07-01', '2020', '3', '7', '26', '3', '2');
INSERT INTO `razor_dim_date` VALUES ('3837', '2020-07-02', '2020', '3', '7', '26', '4', '3');
INSERT INTO `razor_dim_date` VALUES ('3838', '2020-07-03', '2020', '3', '7', '26', '5', '4');
INSERT INTO `razor_dim_date` VALUES ('3839', '2020-07-04', '2020', '3', '7', '27', '6', '5');
INSERT INTO `razor_dim_date` VALUES ('3840', '2020-07-05', '2020', '3', '7', '27', '0', '6');
INSERT INTO `razor_dim_date` VALUES ('3841', '2020-07-06', '2020', '3', '7', '27', '1', '7');
INSERT INTO `razor_dim_date` VALUES ('3842', '2020-07-07', '2020', '3', '7', '27', '2', '8');
INSERT INTO `razor_dim_date` VALUES ('3843', '2020-07-08', '2020', '3', '7', '27', '3', '9');
INSERT INTO `razor_dim_date` VALUES ('3844', '2020-07-09', '2020', '3', '7', '27', '4', '10');
INSERT INTO `razor_dim_date` VALUES ('3845', '2020-07-10', '2020', '3', '7', '27', '5', '11');
INSERT INTO `razor_dim_date` VALUES ('3846', '2020-07-11', '2020', '3', '7', '28', '6', '12');
INSERT INTO `razor_dim_date` VALUES ('3847', '2020-07-12', '2020', '3', '7', '28', '0', '13');
INSERT INTO `razor_dim_date` VALUES ('3848', '2020-07-13', '2020', '3', '7', '28', '1', '14');
INSERT INTO `razor_dim_date` VALUES ('3849', '2020-07-14', '2020', '3', '7', '28', '2', '15');
INSERT INTO `razor_dim_date` VALUES ('3850', '2020-07-15', '2020', '3', '7', '28', '3', '16');
INSERT INTO `razor_dim_date` VALUES ('3851', '2020-07-16', '2020', '3', '7', '28', '4', '17');
INSERT INTO `razor_dim_date` VALUES ('3852', '2020-07-17', '2020', '3', '7', '28', '5', '18');
INSERT INTO `razor_dim_date` VALUES ('3853', '2020-07-18', '2020', '3', '7', '29', '6', '19');
INSERT INTO `razor_dim_date` VALUES ('3854', '2020-07-19', '2020', '3', '7', '29', '0', '20');
INSERT INTO `razor_dim_date` VALUES ('3855', '2020-07-20', '2020', '3', '7', '29', '1', '21');
INSERT INTO `razor_dim_date` VALUES ('3856', '2020-07-21', '2020', '3', '7', '29', '2', '22');
INSERT INTO `razor_dim_date` VALUES ('3857', '2020-07-22', '2020', '3', '7', '29', '3', '23');
INSERT INTO `razor_dim_date` VALUES ('3858', '2020-07-23', '2020', '3', '7', '29', '4', '24');
INSERT INTO `razor_dim_date` VALUES ('3859', '2020-07-24', '2020', '3', '7', '29', '5', '25');
INSERT INTO `razor_dim_date` VALUES ('3860', '2020-07-25', '2020', '3', '7', '30', '6', '26');
INSERT INTO `razor_dim_date` VALUES ('3861', '2020-07-26', '2020', '3', '7', '30', '0', '27');
INSERT INTO `razor_dim_date` VALUES ('3862', '2020-07-27', '2020', '3', '7', '30', '1', '28');
INSERT INTO `razor_dim_date` VALUES ('3863', '2020-07-28', '2020', '3', '7', '30', '2', '29');
INSERT INTO `razor_dim_date` VALUES ('3864', '2020-07-29', '2020', '3', '7', '30', '3', '30');
INSERT INTO `razor_dim_date` VALUES ('3865', '2020-07-30', '2020', '3', '7', '30', '4', '31');
INSERT INTO `razor_dim_date` VALUES ('3866', '2020-07-31', '2020', '3', '8', '30', '5', '1');
INSERT INTO `razor_dim_date` VALUES ('3867', '2020-08-01', '2020', '3', '8', '31', '6', '2');
INSERT INTO `razor_dim_date` VALUES ('3868', '2020-08-02', '2020', '3', '8', '31', '0', '3');
INSERT INTO `razor_dim_date` VALUES ('3869', '2020-08-03', '2020', '3', '8', '31', '1', '4');
INSERT INTO `razor_dim_date` VALUES ('3870', '2020-08-04', '2020', '3', '8', '31', '2', '5');
INSERT INTO `razor_dim_date` VALUES ('3871', '2020-08-05', '2020', '3', '8', '31', '3', '6');
INSERT INTO `razor_dim_date` VALUES ('3872', '2020-08-06', '2020', '3', '8', '31', '4', '7');
INSERT INTO `razor_dim_date` VALUES ('3873', '2020-08-07', '2020', '3', '8', '31', '5', '8');
INSERT INTO `razor_dim_date` VALUES ('3874', '2020-08-08', '2020', '3', '8', '32', '6', '9');
INSERT INTO `razor_dim_date` VALUES ('3875', '2020-08-09', '2020', '3', '8', '32', '0', '10');
INSERT INTO `razor_dim_date` VALUES ('3876', '2020-08-10', '2020', '3', '8', '32', '1', '11');
INSERT INTO `razor_dim_date` VALUES ('3877', '2020-08-11', '2020', '3', '8', '32', '2', '12');
INSERT INTO `razor_dim_date` VALUES ('3878', '2020-08-12', '2020', '3', '8', '32', '3', '13');
INSERT INTO `razor_dim_date` VALUES ('3879', '2020-08-13', '2020', '3', '8', '32', '4', '14');
INSERT INTO `razor_dim_date` VALUES ('3880', '2020-08-14', '2020', '3', '8', '32', '5', '15');
INSERT INTO `razor_dim_date` VALUES ('3881', '2020-08-15', '2020', '3', '8', '33', '6', '16');
INSERT INTO `razor_dim_date` VALUES ('3882', '2020-08-16', '2020', '3', '8', '33', '0', '17');
INSERT INTO `razor_dim_date` VALUES ('3883', '2020-08-17', '2020', '3', '8', '33', '1', '18');
INSERT INTO `razor_dim_date` VALUES ('3884', '2020-08-18', '2020', '3', '8', '33', '2', '19');
INSERT INTO `razor_dim_date` VALUES ('3885', '2020-08-19', '2020', '3', '8', '33', '3', '20');
INSERT INTO `razor_dim_date` VALUES ('3886', '2020-08-20', '2020', '3', '8', '33', '4', '21');
INSERT INTO `razor_dim_date` VALUES ('3887', '2020-08-21', '2020', '3', '8', '33', '5', '22');
INSERT INTO `razor_dim_date` VALUES ('3888', '2020-08-22', '2020', '3', '8', '34', '6', '23');
INSERT INTO `razor_dim_date` VALUES ('3889', '2020-08-23', '2020', '3', '8', '34', '0', '24');
INSERT INTO `razor_dim_date` VALUES ('3890', '2020-08-24', '2020', '3', '8', '34', '1', '25');
INSERT INTO `razor_dim_date` VALUES ('3891', '2020-08-25', '2020', '3', '8', '34', '2', '26');
INSERT INTO `razor_dim_date` VALUES ('3892', '2020-08-26', '2020', '3', '8', '34', '3', '27');
INSERT INTO `razor_dim_date` VALUES ('3893', '2020-08-27', '2020', '3', '8', '34', '4', '28');
INSERT INTO `razor_dim_date` VALUES ('3894', '2020-08-28', '2020', '3', '8', '34', '5', '29');
INSERT INTO `razor_dim_date` VALUES ('3895', '2020-08-29', '2020', '3', '8', '35', '6', '30');
INSERT INTO `razor_dim_date` VALUES ('3896', '2020-08-30', '2020', '3', '8', '35', '0', '31');
INSERT INTO `razor_dim_date` VALUES ('3897', '2020-08-31', '2020', '3', '9', '35', '1', '1');
INSERT INTO `razor_dim_date` VALUES ('3898', '2020-09-01', '2020', '3', '9', '35', '2', '2');
INSERT INTO `razor_dim_date` VALUES ('3899', '2020-09-02', '2020', '3', '9', '35', '3', '3');
INSERT INTO `razor_dim_date` VALUES ('3900', '2020-09-03', '2020', '3', '9', '35', '4', '4');
INSERT INTO `razor_dim_date` VALUES ('3901', '2020-09-04', '2020', '3', '9', '35', '5', '5');
INSERT INTO `razor_dim_date` VALUES ('3902', '2020-09-05', '2020', '3', '9', '36', '6', '6');
INSERT INTO `razor_dim_date` VALUES ('3903', '2020-09-06', '2020', '3', '9', '36', '0', '7');
INSERT INTO `razor_dim_date` VALUES ('3904', '2020-09-07', '2020', '3', '9', '36', '1', '8');
INSERT INTO `razor_dim_date` VALUES ('3905', '2020-09-08', '2020', '3', '9', '36', '2', '9');
INSERT INTO `razor_dim_date` VALUES ('3906', '2020-09-09', '2020', '3', '9', '36', '3', '10');
INSERT INTO `razor_dim_date` VALUES ('3907', '2020-09-10', '2020', '3', '9', '36', '4', '11');
INSERT INTO `razor_dim_date` VALUES ('3908', '2020-09-11', '2020', '3', '9', '36', '5', '12');
INSERT INTO `razor_dim_date` VALUES ('3909', '2020-09-12', '2020', '3', '9', '37', '6', '13');
INSERT INTO `razor_dim_date` VALUES ('3910', '2020-09-13', '2020', '3', '9', '37', '0', '14');
INSERT INTO `razor_dim_date` VALUES ('3911', '2020-09-14', '2020', '3', '9', '37', '1', '15');
INSERT INTO `razor_dim_date` VALUES ('3912', '2020-09-15', '2020', '3', '9', '37', '2', '16');
INSERT INTO `razor_dim_date` VALUES ('3913', '2020-09-16', '2020', '3', '9', '37', '3', '17');
INSERT INTO `razor_dim_date` VALUES ('3914', '2020-09-17', '2020', '3', '9', '37', '4', '18');
INSERT INTO `razor_dim_date` VALUES ('3915', '2020-09-18', '2020', '3', '9', '37', '5', '19');
INSERT INTO `razor_dim_date` VALUES ('3916', '2020-09-19', '2020', '3', '9', '38', '6', '20');
INSERT INTO `razor_dim_date` VALUES ('3917', '2020-09-20', '2020', '3', '9', '38', '0', '21');
INSERT INTO `razor_dim_date` VALUES ('3918', '2020-09-21', '2020', '3', '9', '38', '1', '22');
INSERT INTO `razor_dim_date` VALUES ('3919', '2020-09-22', '2020', '3', '9', '38', '2', '23');
INSERT INTO `razor_dim_date` VALUES ('3920', '2020-09-23', '2020', '3', '9', '38', '3', '24');
INSERT INTO `razor_dim_date` VALUES ('3921', '2020-09-24', '2020', '3', '9', '38', '4', '25');
INSERT INTO `razor_dim_date` VALUES ('3922', '2020-09-25', '2020', '3', '9', '38', '5', '26');
INSERT INTO `razor_dim_date` VALUES ('3923', '2020-09-26', '2020', '3', '9', '39', '6', '27');
INSERT INTO `razor_dim_date` VALUES ('3924', '2020-09-27', '2020', '3', '9', '39', '0', '28');
INSERT INTO `razor_dim_date` VALUES ('3925', '2020-09-28', '2020', '3', '9', '39', '1', '29');
INSERT INTO `razor_dim_date` VALUES ('3926', '2020-09-29', '2020', '3', '9', '39', '2', '30');
INSERT INTO `razor_dim_date` VALUES ('3927', '2020-09-30', '2020', '4', '10', '39', '3', '1');
INSERT INTO `razor_dim_date` VALUES ('3928', '2020-10-01', '2020', '4', '10', '39', '4', '2');
INSERT INTO `razor_dim_date` VALUES ('3929', '2020-10-02', '2020', '4', '10', '39', '5', '3');
INSERT INTO `razor_dim_date` VALUES ('3930', '2020-10-03', '2020', '4', '10', '40', '6', '4');
INSERT INTO `razor_dim_date` VALUES ('3931', '2020-10-04', '2020', '4', '10', '40', '0', '5');
INSERT INTO `razor_dim_date` VALUES ('3932', '2020-10-05', '2020', '4', '10', '40', '1', '6');
INSERT INTO `razor_dim_date` VALUES ('3933', '2020-10-06', '2020', '4', '10', '40', '2', '7');
INSERT INTO `razor_dim_date` VALUES ('3934', '2020-10-07', '2020', '4', '10', '40', '3', '8');
INSERT INTO `razor_dim_date` VALUES ('3935', '2020-10-08', '2020', '4', '10', '40', '4', '9');
INSERT INTO `razor_dim_date` VALUES ('3936', '2020-10-09', '2020', '4', '10', '40', '5', '10');
INSERT INTO `razor_dim_date` VALUES ('3937', '2020-10-10', '2020', '4', '10', '41', '6', '11');
INSERT INTO `razor_dim_date` VALUES ('3938', '2020-10-11', '2020', '4', '10', '41', '0', '12');
INSERT INTO `razor_dim_date` VALUES ('3939', '2020-10-12', '2020', '4', '10', '41', '1', '13');
INSERT INTO `razor_dim_date` VALUES ('3940', '2020-10-13', '2020', '4', '10', '41', '2', '14');
INSERT INTO `razor_dim_date` VALUES ('3941', '2020-10-14', '2020', '4', '10', '41', '3', '15');
INSERT INTO `razor_dim_date` VALUES ('3942', '2020-10-15', '2020', '4', '10', '41', '4', '16');
INSERT INTO `razor_dim_date` VALUES ('3943', '2020-10-16', '2020', '4', '10', '41', '5', '17');
INSERT INTO `razor_dim_date` VALUES ('3944', '2020-10-17', '2020', '4', '10', '42', '6', '18');
INSERT INTO `razor_dim_date` VALUES ('3945', '2020-10-18', '2020', '4', '10', '42', '0', '19');
INSERT INTO `razor_dim_date` VALUES ('3946', '2020-10-19', '2020', '4', '10', '42', '1', '20');
INSERT INTO `razor_dim_date` VALUES ('3947', '2020-10-20', '2020', '4', '10', '42', '2', '21');
INSERT INTO `razor_dim_date` VALUES ('3948', '2020-10-21', '2020', '4', '10', '42', '3', '22');
INSERT INTO `razor_dim_date` VALUES ('3949', '2020-10-22', '2020', '4', '10', '42', '4', '23');
INSERT INTO `razor_dim_date` VALUES ('3950', '2020-10-23', '2020', '4', '10', '42', '5', '24');
INSERT INTO `razor_dim_date` VALUES ('3951', '2020-10-24', '2020', '4', '10', '43', '6', '25');
INSERT INTO `razor_dim_date` VALUES ('3952', '2020-10-25', '2020', '4', '10', '43', '0', '26');
INSERT INTO `razor_dim_date` VALUES ('3953', '2020-10-26', '2020', '4', '10', '43', '1', '27');
INSERT INTO `razor_dim_date` VALUES ('3954', '2020-10-27', '2020', '4', '10', '43', '2', '28');
INSERT INTO `razor_dim_date` VALUES ('3955', '2020-10-28', '2020', '4', '10', '43', '3', '29');
INSERT INTO `razor_dim_date` VALUES ('3956', '2020-10-29', '2020', '4', '10', '43', '4', '30');
INSERT INTO `razor_dim_date` VALUES ('3957', '2020-10-30', '2020', '4', '10', '43', '5', '31');
INSERT INTO `razor_dim_date` VALUES ('3958', '2020-10-31', '2020', '4', '11', '44', '6', '1');
INSERT INTO `razor_dim_date` VALUES ('3959', '2020-11-01', '2020', '4', '11', '44', '0', '2');
INSERT INTO `razor_dim_date` VALUES ('3960', '2020-11-02', '2020', '4', '11', '44', '1', '3');
INSERT INTO `razor_dim_date` VALUES ('3961', '2020-11-03', '2020', '4', '11', '44', '2', '4');
INSERT INTO `razor_dim_date` VALUES ('3962', '2020-11-04', '2020', '4', '11', '44', '3', '5');
INSERT INTO `razor_dim_date` VALUES ('3963', '2020-11-05', '2020', '4', '11', '44', '4', '6');
INSERT INTO `razor_dim_date` VALUES ('3964', '2020-11-06', '2020', '4', '11', '44', '5', '7');
INSERT INTO `razor_dim_date` VALUES ('3965', '2020-11-07', '2020', '4', '11', '45', '6', '8');
INSERT INTO `razor_dim_date` VALUES ('3966', '2020-11-08', '2020', '4', '11', '45', '0', '9');
INSERT INTO `razor_dim_date` VALUES ('3967', '2020-11-09', '2020', '4', '11', '45', '1', '10');
INSERT INTO `razor_dim_date` VALUES ('3968', '2020-11-10', '2020', '4', '11', '45', '2', '11');
INSERT INTO `razor_dim_date` VALUES ('3969', '2020-11-11', '2020', '4', '11', '45', '3', '12');
INSERT INTO `razor_dim_date` VALUES ('3970', '2020-11-12', '2020', '4', '11', '45', '4', '13');
INSERT INTO `razor_dim_date` VALUES ('3971', '2020-11-13', '2020', '4', '11', '45', '5', '14');
INSERT INTO `razor_dim_date` VALUES ('3972', '2020-11-14', '2020', '4', '11', '46', '6', '15');
INSERT INTO `razor_dim_date` VALUES ('3973', '2020-11-15', '2020', '4', '11', '46', '0', '16');
INSERT INTO `razor_dim_date` VALUES ('3974', '2020-11-16', '2020', '4', '11', '46', '1', '17');
INSERT INTO `razor_dim_date` VALUES ('3975', '2020-11-17', '2020', '4', '11', '46', '2', '18');
INSERT INTO `razor_dim_date` VALUES ('3976', '2020-11-18', '2020', '4', '11', '46', '3', '19');
INSERT INTO `razor_dim_date` VALUES ('3977', '2020-11-19', '2020', '4', '11', '46', '4', '20');
INSERT INTO `razor_dim_date` VALUES ('3978', '2020-11-20', '2020', '4', '11', '46', '5', '21');
INSERT INTO `razor_dim_date` VALUES ('3979', '2020-11-21', '2020', '4', '11', '47', '6', '22');
INSERT INTO `razor_dim_date` VALUES ('3980', '2020-11-22', '2020', '4', '11', '47', '0', '23');
INSERT INTO `razor_dim_date` VALUES ('3981', '2020-11-23', '2020', '4', '11', '47', '1', '24');
INSERT INTO `razor_dim_date` VALUES ('3982', '2020-11-24', '2020', '4', '11', '47', '2', '25');
INSERT INTO `razor_dim_date` VALUES ('3983', '2020-11-25', '2020', '4', '11', '47', '3', '26');
INSERT INTO `razor_dim_date` VALUES ('3984', '2020-11-26', '2020', '4', '11', '47', '4', '27');
INSERT INTO `razor_dim_date` VALUES ('3985', '2020-11-27', '2020', '4', '11', '47', '5', '28');
INSERT INTO `razor_dim_date` VALUES ('3986', '2020-11-28', '2020', '4', '11', '48', '6', '29');
INSERT INTO `razor_dim_date` VALUES ('3987', '2020-11-29', '2020', '4', '11', '48', '0', '30');
INSERT INTO `razor_dim_date` VALUES ('3988', '2020-11-30', '2020', '4', '12', '48', '1', '1');
INSERT INTO `razor_dim_date` VALUES ('3989', '2020-12-01', '2020', '4', '12', '48', '2', '2');
INSERT INTO `razor_dim_date` VALUES ('3990', '2020-12-02', '2020', '4', '12', '48', '3', '3');
INSERT INTO `razor_dim_date` VALUES ('3991', '2020-12-03', '2020', '4', '12', '48', '4', '4');
INSERT INTO `razor_dim_date` VALUES ('3992', '2020-12-04', '2020', '4', '12', '48', '5', '5');
INSERT INTO `razor_dim_date` VALUES ('3993', '2020-12-05', '2020', '4', '12', '49', '6', '6');
INSERT INTO `razor_dim_date` VALUES ('3994', '2020-12-06', '2020', '4', '12', '49', '0', '7');
INSERT INTO `razor_dim_date` VALUES ('3995', '2020-12-07', '2020', '4', '12', '49', '1', '8');
INSERT INTO `razor_dim_date` VALUES ('3996', '2020-12-08', '2020', '4', '12', '49', '2', '9');
INSERT INTO `razor_dim_date` VALUES ('3997', '2020-12-09', '2020', '4', '12', '49', '3', '10');
INSERT INTO `razor_dim_date` VALUES ('3998', '2020-12-10', '2020', '4', '12', '49', '4', '11');
INSERT INTO `razor_dim_date` VALUES ('3999', '2020-12-11', '2020', '4', '12', '49', '5', '12');
INSERT INTO `razor_dim_date` VALUES ('4000', '2020-12-12', '2020', '4', '12', '50', '6', '13');
INSERT INTO `razor_dim_date` VALUES ('4001', '2020-12-13', '2020', '4', '12', '50', '0', '14');
INSERT INTO `razor_dim_date` VALUES ('4002', '2020-12-14', '2020', '4', '12', '50', '1', '15');
INSERT INTO `razor_dim_date` VALUES ('4003', '2020-12-15', '2020', '4', '12', '50', '2', '16');
INSERT INTO `razor_dim_date` VALUES ('4004', '2020-12-16', '2020', '4', '12', '50', '3', '17');
INSERT INTO `razor_dim_date` VALUES ('4005', '2020-12-17', '2020', '4', '12', '50', '4', '18');
INSERT INTO `razor_dim_date` VALUES ('4006', '2020-12-18', '2020', '4', '12', '50', '5', '19');
INSERT INTO `razor_dim_date` VALUES ('4007', '2020-12-19', '2020', '4', '12', '51', '6', '20');
INSERT INTO `razor_dim_date` VALUES ('4008', '2020-12-20', '2020', '4', '12', '51', '0', '21');
INSERT INTO `razor_dim_date` VALUES ('4009', '2020-12-21', '2020', '4', '12', '51', '1', '22');
INSERT INTO `razor_dim_date` VALUES ('4010', '2020-12-22', '2020', '4', '12', '51', '2', '23');
INSERT INTO `razor_dim_date` VALUES ('4011', '2020-12-23', '2020', '4', '12', '51', '3', '24');
INSERT INTO `razor_dim_date` VALUES ('4012', '2020-12-24', '2020', '4', '12', '51', '4', '25');
INSERT INTO `razor_dim_date` VALUES ('4013', '2020-12-25', '2020', '4', '12', '51', '5', '26');
INSERT INTO `razor_dim_date` VALUES ('4014', '2020-12-26', '2020', '4', '12', '52', '6', '27');
INSERT INTO `razor_dim_date` VALUES ('4015', '2020-12-27', '2020', '4', '12', '52', '0', '28');
INSERT INTO `razor_dim_date` VALUES ('4016', '2020-12-28', '2020', '4', '12', '52', '1', '29');
INSERT INTO `razor_dim_date` VALUES ('4017', '2020-12-29', '2020', '4', '12', '52', '2', '30');
INSERT INTO `razor_dim_date` VALUES ('4018', '2020-12-30', '2020', '4', '12', '52', '3', '31');

-- ----------------------------
-- Table structure for razor_dim_devicebrand
-- ----------------------------
DROP TABLE IF EXISTS `razor_dim_devicebrand`;
CREATE TABLE `razor_dim_devicebrand` (
  `devicebrand_sk` int(11) NOT NULL AUTO_INCREMENT,
  `devicebrand_name` varchar(60) NOT NULL,
  PRIMARY KEY (`devicebrand_sk`),
  KEY `devicebrand_name` (`devicebrand_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_dim_devicebrand
-- ----------------------------
INSERT INTO `razor_dim_devicebrand` VALUES ('1', 'Unknown sdk');

-- ----------------------------
-- Table structure for razor_dim_devicelanguage
-- ----------------------------
DROP TABLE IF EXISTS `razor_dim_devicelanguage`;
CREATE TABLE `razor_dim_devicelanguage` (
  `devicelanguage_sk` int(11) NOT NULL AUTO_INCREMENT,
  `devicelanguage_name` varchar(60) NOT NULL,
  PRIMARY KEY (`devicelanguage_sk`),
  KEY `devicelanguage_name` (`devicelanguage_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_dim_devicelanguage
-- ----------------------------
INSERT INTO `razor_dim_devicelanguage` VALUES ('1', 'zh');

-- ----------------------------
-- Table structure for razor_dim_deviceos
-- ----------------------------
DROP TABLE IF EXISTS `razor_dim_deviceos`;
CREATE TABLE `razor_dim_deviceos` (
  `deviceos_sk` int(11) NOT NULL AUTO_INCREMENT,
  `deviceos_name` varchar(256) NOT NULL,
  PRIMARY KEY (`deviceos_sk`),
  KEY `deviceos_name` (`deviceos_name`(255))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_dim_deviceos
-- ----------------------------
INSERT INTO `razor_dim_deviceos` VALUES ('1', '4.4');

-- ----------------------------
-- Table structure for razor_dim_deviceresolution
-- ----------------------------
DROP TABLE IF EXISTS `razor_dim_deviceresolution`;
CREATE TABLE `razor_dim_deviceresolution` (
  `deviceresolution_sk` int(11) NOT NULL AUTO_INCREMENT,
  `deviceresolution_name` varchar(60) NOT NULL,
  PRIMARY KEY (`deviceresolution_sk`),
  KEY `deviceresolution_name` (`deviceresolution_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_dim_deviceresolution
-- ----------------------------
INSERT INTO `razor_dim_deviceresolution` VALUES ('1', '480x800');

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
) ENGINE=InnoDB AUTO_INCREMENT=1068 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_dim_devicesupplier
-- ----------------------------
INSERT INTO `razor_dim_devicesupplier` VALUES ('1', '20201', 'Cosmote Greece', '202', 'GR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('2', '20205', 'Vodafone - Panafon Greece', '202', 'GR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('3', '20209', 'Info Quest S.A. Greece', '202', 'GR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('4', '20210', 'Telestet Greece', '202', 'GR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('5', '20402', 'Tele2 (Netherlands) B.V.', '204', 'NL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('6', '20404', 'Vodafone Libertel N.V. Netherlands', '204', 'NL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('7', '20408', 'KPN Telecom B.V. Netherlands', '204', 'NL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('8', '20412', 'BT Ignite Nederland B.V.', '204', 'NL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('9', '20416', 'BEN Nederland B.V.', '204', 'NL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('10', '20420', 'Dutchtone N.V. Netherlands', '204', 'NL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('11', '20421', 'NS Railinfrabeheer B.V. Netherlands', '204', 'NL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('12', '20601', 'Proximus Belgium', '206', 'BE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('13', '20610', 'Mobistar Belgium', '206', 'BE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('14', '20620', 'Base Belgium', '206', 'BE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('15', '20801', 'Orange', '208', 'FR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('16', '20802', 'Orange', '208', 'FR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('17', '20805', 'Globalstar Europe France', '208', 'FR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('18', '20806', 'Globalstar Europe France', '208', 'FR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('19', '20807', 'Globalstar Europe France', '208', 'FR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('20', '20810', 'SFR', '208', 'FR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('21', '20811', 'SFR', '208', 'FR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('22', '20813', 'SFR', '208', 'FR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('23', '20815', 'Free Mobile', '208', 'FR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('24', '20816', 'Free Mobile', '208', 'FR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('25', '20820', 'Bouygues Telecom', '208', 'FR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('26', '20821', 'Bouygues Telecom', '208', 'FR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('27', '20823', 'Virgin Mobile', '208', 'FR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('28', '20825', 'Lycamobile', '208', 'FR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('29', '20826', 'NRJ Mobile', '208', 'FR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('30', '20827', 'Afone Mobile', '208', 'FR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('31', '20830', 'Symacom', '208', 'FR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('32', '20888', 'Bouygues Telecom', '208', 'FR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('33', '21303', 'MobilandAndorra', '213', 'AD');
INSERT INTO `razor_dim_devicesupplier` VALUES ('34', '21401', 'Vodafone Spain', '214', 'ES');
INSERT INTO `razor_dim_devicesupplier` VALUES ('35', '21403', 'Amena Spain', '214', 'ES');
INSERT INTO `razor_dim_devicesupplier` VALUES ('36', '21404', 'Xfera Spain', '214', 'ES');
INSERT INTO `razor_dim_devicesupplier` VALUES ('37', '21407', 'Movistar Spain', '214', 'ES');
INSERT INTO `razor_dim_devicesupplier` VALUES ('38', '21601', 'Pannon GSM Hungary', '216', 'HU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('39', '21630', 'T-Mobile Hungary', '216', 'HU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('40', '21670', 'Vodafone Hungary', '216', 'HU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('41', '21803', 'Eronet Mobile Communications Ltd. Bosnia and Herzegovina', '218', 'BA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('42', '21805', 'MOBIS (Mobilina Srpske) Bosnia and Herzegovina', '218', 'BA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('43', '21890', 'GSMBIH Bosnia and Herzegovina', '218', 'BA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('44', '21901', 'Cronet Croatia', '219', 'HR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('45', '21910', 'VIPnet Croatia', '219', 'HR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('46', '22001', 'Mobtel Serbia', '220', 'YU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('47', '22002', 'Promonte GSM Serbia', '220', 'YU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('48', '22003', 'Telekom Srbija', '220', 'YU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('49', '22004', 'Monet Serbia', '220', 'YU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('50', '22201', 'Telecom Italia Mobile (TIM)', '222', 'IT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('51', '22202', 'Elsacom Italy', '222', 'IT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('52', '22210', 'Omnitel Pronto Italia (OPI)', '222', 'IT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('53', '22277', 'IPSE 2000 Italy', '222', 'IT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('54', '22288', 'Wind Italy', '222', 'IT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('55', '22298', 'Blu Italy', '222', 'IT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('56', '22299', 'H3G Italy', '222', 'IT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('57', '22601', 'Vodafone Romania SA', '226', 'RO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('58', '22603', 'Cosmorom Romania', '226', 'RO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('59', '22610', 'Orange Romania', '226', 'RO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('60', '22801', 'Swisscom GSM', '228', 'CH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('61', '22802', 'Sunrise GSM Switzerland', '228', 'CH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('62', '22803', 'Orange Switzerland', '228', 'CH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('63', '22805', 'Togewanet AG Switzerland', '228', 'CH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('64', '22806', 'SBB AG Switzerland', '228', 'CH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('65', '22807', 'IN&Phone SA Switzerland', '228', 'CH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('66', '22808', 'Tele2 Telecommunications AG Switzerland', '228', 'CH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('67', '22812', 'Sunrise UMTS Switzerland', '228', 'CH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('68', '22850', '3G Mabile AG Switzerland', '228', 'CH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('69', '22851', 'Global Networks Schweiz AG', '228', 'CH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('70', '23001', 'RadioMobil a.s., T-Mobile Czech Rep.', '230', 'CZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('71', '23002', 'Eurotel Praha, spol. Sro., Eurotel Czech Rep.', '230', 'CZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('72', '23003', 'Cesky Mobil a.s., Oskar', '230', 'CZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('73', '23099', 'Cesky Mobil a.s., R&D Centre', '230', 'CZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('74', '23101', 'Orange, GSM Slovakia', '231', 'SK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('75', '23102', 'Eurotel, GSM & NMT Slovakia', '231', 'SK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('76', '23104', 'Eurotel, UMTS Slovakia', '231', 'SK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('77', '23105', 'Orange, UMTS Slovakia', '231', 'SK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('78', '23201', 'A1 Austria', '232', 'AT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('79', '23203', 'T-Mobile Austria', '232', 'AT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('80', '23205', 'One Austria', '232', 'AT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('81', '23207', 'tele.ring Austria', '232', 'AT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('82', '23208', 'Telefonica Austria', '232', 'AT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('83', '23209', 'One Austria', '232', 'AT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('84', '23210', 'Hutchison 3G Austria', '232', 'AT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('85', '23402', 'O2 UK Ltd.', '234', 'GB');
INSERT INTO `razor_dim_devicesupplier` VALUES ('86', '23410', 'O2 UK Ltd.', '234', 'GB');
INSERT INTO `razor_dim_devicesupplier` VALUES ('87', '23411', 'O2 UK Ltd.', '234', 'GB');
INSERT INTO `razor_dim_devicesupplier` VALUES ('88', '23412', 'Railtrack Plc UK', '234', 'GB');
INSERT INTO `razor_dim_devicesupplier` VALUES ('89', '23415', 'Vodafone', '234', 'GB');
INSERT INTO `razor_dim_devicesupplier` VALUES ('90', '23420', 'Hutchison 3G UK Ltd.', '234', 'GB');
INSERT INTO `razor_dim_devicesupplier` VALUES ('91', '23430', 'T-Mobile UK', '234', 'GB');
INSERT INTO `razor_dim_devicesupplier` VALUES ('92', '23431', 'T-Mobile UK', '234', 'GB');
INSERT INTO `razor_dim_devicesupplier` VALUES ('93', '23432', 'T-Mobile UK', '234', 'GB');
INSERT INTO `razor_dim_devicesupplier` VALUES ('94', '23433', 'Orange UK', '234', 'GB');
INSERT INTO `razor_dim_devicesupplier` VALUES ('95', '23434', 'Orange UK', '234', 'GB');
INSERT INTO `razor_dim_devicesupplier` VALUES ('96', '23450', 'Jersey Telecom UK', '234', 'GB');
INSERT INTO `razor_dim_devicesupplier` VALUES ('97', '23455', 'Guensey Telecom UK', '234', 'GB');
INSERT INTO `razor_dim_devicesupplier` VALUES ('98', '23458', 'Manx Telecom UK', '234', 'GB');
INSERT INTO `razor_dim_devicesupplier` VALUES ('99', '23475', 'Inquam Telecom (Holdings) Ltd. UK', '234', 'GB');
INSERT INTO `razor_dim_devicesupplier` VALUES ('100', '23801', 'TDC Mobil Denmark', '238', 'DK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('101', '23802', 'Sonofon Denmark', '238', 'DK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('102', '23803', 'MIGway A/S Denmark', '238', 'DK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('103', '23806', 'Hi3G Denmark', '238', 'DK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('104', '23807', 'Barablu Mobile Ltd. Denmark', '238', 'DK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('105', '23810', 'TDC Mobil Denmark', '238', 'DK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('106', '23820', 'Telia Denmark', '238', 'DK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('107', '23830', 'Telia Mobile Denmark', '238', 'DK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('108', '23877', 'Tele2 Denmark', '238', 'DK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('109', '24001', 'Telia Sonera AB Sweden', '240', 'SE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('110', '24002', 'H3G Access AB Sweden', '240', 'SE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('111', '24003', 'Nordisk Mobiltelefon AS Sweden', '240', 'SE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('112', '24004', '3G Infrastructure Services AB Sweden', '240', 'SE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('113', '24005', 'Svenska UMTS-Nat AB', '240', 'SE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('114', '24006', 'Telenor Sverige AB', '240', 'SE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('115', '24007', 'Tele2 Sverige AB', '240', 'SE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('116', '24008', 'Telenor Sverige AB', '240', 'SE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('117', '24009', 'Telenor Mobile Sverige', '240', 'SE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('118', '24010', 'Swefour AB Sweden', '240', 'SE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('119', '24011', 'Linholmen Science Park AB Sweden', '240', 'SE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('120', '24020', 'Wireless Maingate Message Services AB Sweden', '240', 'SE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('121', '24021', 'Banverket Sweden', '240', 'SE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('122', '24201', 'Telenor Mobil AS Norway', '242', 'NO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('123', '24202', 'Netcom GSM AS Norway', '242', 'NO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('124', '24203', 'Teletopia Mobile Communications AS Norway', '242', 'NO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('125', '24204', 'Tele2 Norge AS', '242', 'NO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('126', '24404', 'Finnet Networks Ltd.', '244', 'FI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('127', '24405', 'Elisa Matkapuhelinpalvelut Ltd. Finland', '244', 'FI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('128', '24409', 'Finnet Group', '244', 'FI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('129', '24412', 'Finnet Networks Ltd.', '244', 'FI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('130', '24414', 'Alands Mobiltelefon AB Finland', '244', 'FI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('131', '24416', 'Oy Finland Tele2 AB', '244', 'FI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('132', '24421', 'Saunalahti Group Ltd. Finland', '244', 'FI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('133', '24491', 'Sonera Carrier Networks Oy Finland', '244', 'FI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('134', '24601', 'Omnitel Lithuania', '246', 'LT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('135', '24602', 'Bit GSM Lithuania', '246', 'LT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('136', '24603', 'Tele2 Lithuania', '246', 'LT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('137', '24701', 'Latvian Mobile Phone', '247', 'LV');
INSERT INTO `razor_dim_devicesupplier` VALUES ('138', '24702', 'Tele2 Latvia', '247', 'LV');
INSERT INTO `razor_dim_devicesupplier` VALUES ('139', '24703', 'Telekom Baltija Latvia', '247', 'LV');
INSERT INTO `razor_dim_devicesupplier` VALUES ('140', '24704', 'Beta Telecom Latvia', '247', 'LV');
INSERT INTO `razor_dim_devicesupplier` VALUES ('141', '24801', 'EMT GSM Estonia', '248', 'EE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('142', '24802', 'RLE Estonia', '248', 'EE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('143', '24803', 'Tele2 Estonia', '248', 'EE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('144', '24804', 'OY Top Connect Estonia', '248', 'EE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('145', '24805', 'AS Bravocom Mobiil Estonia', '248', 'EE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('146', '24806', 'OY ViaTel Estonia', '248', 'EE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('147', '25001', 'Mobile Telesystems Russia', '250', 'RU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('148', '25002', 'Megafon Russia', '250', 'RU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('149', '25003', 'Nizhegorodskaya Cellular Communications Russia', '250', 'RU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('150', '25004', 'Sibchallenge Russia', '250', 'RU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('151', '25005', 'Mobile Comms System Russia', '250', 'RU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('152', '25007', 'BM Telecom Russia', '250', 'RU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('153', '25010', 'Don Telecom Russia', '250', 'RU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('154', '25011', 'Orensot Russia', '250', 'RU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('155', '25012', 'Baykal Westcom Russia', '250', 'RU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('156', '25013', 'Kuban GSM Russia', '250', 'RU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('157', '25016', 'New Telephone Company Russia', '250', 'RU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('158', '25017', 'Ermak RMS Russia', '250', 'RU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('159', '25019', 'Volgograd Mobile Russia', '250', 'RU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('160', '25020', 'ECC Russia', '250', 'RU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('161', '25028', 'Extel Russia', '250', 'RU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('162', '25039', 'Uralsvyazinform Russia', '250', 'RU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('163', '25044', 'Stuvtelesot Russia', '250', 'RU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('164', '25092', 'Printelefone Russia', '250', 'RU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('165', '25093', 'Telecom XXI Russia', '250', 'RU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('166', '25099', 'Bec Line GSM Russia', '250', 'RU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('167', '25501', 'Ukrainian Mobile Communication, UMC', '255', 'UA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('168', '25502', 'Ukranian Radio Systems, URS', '255', 'UA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('169', '25503', 'Kyivstar Ukraine', '255', 'UA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('170', '25504', 'Golden Telecom, GT Ukraine', '255', 'UA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('171', '25506', 'Astelit Ukraine', '255', 'UA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('172', '25507', 'Ukrtelecom Ukraine', '255', 'UA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('173', '25701', 'MDC Velcom Belarus', '257', 'BY');
INSERT INTO `razor_dim_devicesupplier` VALUES ('174', '25702', 'MTS Belarus', '257', 'BY');
INSERT INTO `razor_dim_devicesupplier` VALUES ('175', '25901', 'Voxtel Moldova', '259', 'MD');
INSERT INTO `razor_dim_devicesupplier` VALUES ('176', '25902', 'Moldcell Moldova', '259', 'MD');
INSERT INTO `razor_dim_devicesupplier` VALUES ('177', '26001', 'Plus GSM (Polkomtel S.A.) Poland', '260', 'PL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('178', '26002', 'ERA GSM (Polska Telefonia Cyfrowa Sp. Z.o.o.)', '260', 'PL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('179', '26003', 'Idea (Polska Telefonia Komorkowa Centertel Sp. Z.o.o)', '260', 'PL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('180', '26004', 'Tele2 Polska (Tele2 Polska Sp. Z.o.o.)', '260', 'PL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('181', '26005', 'IDEA (UMTS)/PTK Centertel sp. Z.o.o. Poland', '260', 'PL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('182', '26006', 'Netia Mobile Poland', '260', 'PL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('183', '26007', 'Premium internet Poland', '260', 'PL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('184', '26008', 'E-Telko Poland', '260', 'PL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('185', '26009', 'Telekomunikacja Kolejowa (GSM-R) Poland', '260', 'PL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('186', '26010', 'Telefony Opalenickie Poland', '260', 'PL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('187', '26201', 'T-Mobile Deutschland GmbH', '262', 'DE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('188', '26202', 'Vodafone D2 GmbH Germany', '262', 'DE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('189', '26203', 'E-Plus Mobilfunk GmbH & Co. KG Germany', '262', 'DE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('190', '26204', 'Vodafone D2 GmbH Germany', '262', 'DE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('191', '26205', 'E-Plus Mobilfunk GmbH & Co. KG Germany', '262', 'DE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('192', '26206', 'T-Mobile Deutschland GmbH', '262', 'DE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('193', '26207', 'O2 (Germany) GmbH & Co. OHG', '262', 'DE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('194', '26208', 'O2 (Germany) GmbH & Co. OHG', '262', 'DE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('195', '26209', 'Vodafone D2 GmbH Germany', '262', 'DE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('196', '26210', 'Arcor AG & Co. Germany', '262', 'DE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('197', '26211', 'O2 (Germany) GmbH & Co. OHG', '262', 'DE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('198', '26212', 'Dolphin Telecom (Deutschland) GmbH', '262', 'DE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('199', '26213', 'Mobilcom Multimedia GmbH Germany', '262', 'DE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('200', '26214', 'Group 3G UMTS GmbH (Quam) Germany', '262', 'DE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('201', '26215', 'Airdata AG Germany', '262', 'DE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('202', '26276', 'Siemens AG, ICMNPGUSTA Germany', '262', 'DE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('203', '26277', 'E-Plus Mobilfunk GmbH & Co. KG Germany', '262', 'DE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('204', '26601', 'Gibtel GSM Gibraltar', '266', 'GI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('205', '26801', 'Vodafone Telecel - Comunicacoes Pessoais, S.A. Portugal', '268', 'PT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('206', '26803', 'Optimus - Telecomunicacoes, S.A. Portugal', '268', 'PT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('207', '26805', 'Oniway - Inforcomunicacoes, S.A. Portugal', '268', 'PT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('208', '26806', 'TMN - Telecomunicacoes Moveis Nacionais, S.A. Portugal', '268', 'PT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('209', '27001', 'P&T Luxembourg', '270', 'LU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('210', '27077', 'Tango Luxembourg', '270', 'LU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('211', '27099', 'Voxmobile S.A. Luxembourg', '270', 'LU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('212', '27201', 'Vodafone Ireland Plc', '272', 'IE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('213', '27202', 'Digifone mm02 Ltd. Ireland', '272', 'IE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('214', '27203', 'Meteor Mobile Communications Ltd. Ireland', '272', 'IE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('215', '27207', 'Eircom Ireland', '272', 'IE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('216', '27209', 'Clever Communications Ltd. Ireland', '272', 'IE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('217', '27401', 'Iceland Telecom Ltd.', '274', 'IS');
INSERT INTO `razor_dim_devicesupplier` VALUES ('218', '27402', 'Tal hf Iceland', '274', 'IS');
INSERT INTO `razor_dim_devicesupplier` VALUES ('219', '27403', 'Islandssimi GSM ehf Iceland', '274', 'IS');
INSERT INTO `razor_dim_devicesupplier` VALUES ('220', '27404', 'IMC Islande ehf', '274', 'IS');
INSERT INTO `razor_dim_devicesupplier` VALUES ('221', '27601', 'AMC Albania', '276', 'AL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('222', '27602', 'Vodafone Albania', '276', 'AL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('223', '27801', 'Vodafone Malta', '278', 'MT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('224', '27821', 'go mobile Malta', '278', 'MT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('225', '28001', 'CYTA Cyprus', '280', 'CY');
INSERT INTO `razor_dim_devicesupplier` VALUES ('226', '28010', 'Scancom (Cyprus) Ltd.', '280', 'CY');
INSERT INTO `razor_dim_devicesupplier` VALUES ('227', '28201', 'Geocell Ltd. Georgia', '282', 'GE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('228', '28202', 'Magti GSM Ltd. Georgia', '282', 'GE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('229', '28203', 'Iberiatel Ltd. Georgia', '282', 'GE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('230', '28204', 'Mobitel Ltd. Georgia', '282', 'GE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('231', '28301', 'ARMGSM', '283', 'AM');
INSERT INTO `razor_dim_devicesupplier` VALUES ('232', '28401', 'M-Tel GSM BG Bulgaria', '284', 'BG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('233', '28405', 'Globul Bulgaria', '284', 'BG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('234', '28601', 'Turkcell Turkey', '286', 'TR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('235', '28602', 'Telsim GSM Turkey', '286', 'TR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('236', '28603', 'Aria Turkey', '286', 'TR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('237', '28604', 'Aycell Turkey', '286', 'TR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('238', '28801', 'Faroese Telecom - GSM', '288', 'FO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('239', '28802', 'Kall GSM Faroe Islands', '288', 'FO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('240', '29001', 'Tele Greenland', '290', 'GR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('241', '29201', 'SMT - San Marino Telecom', '292', 'SM');
INSERT INTO `razor_dim_devicesupplier` VALUES ('242', '29340', 'SI Mobil Slovenia', '293', 'SI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('243', '29341', 'Mobitel Slovenia', '293', 'SI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('244', '29369', 'Akton d.o.o. Slovenia', '293', 'SI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('245', '29370', 'Tusmobil d.o.o. Slovenia', '293', 'SI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('246', '29401', 'Mobimak Macedonia', '294', 'MK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('247', '29402', 'MTS Macedonia', '294', 'MK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('248', '29501', 'Telecom FL AG Liechtenstein', '295', 'LI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('249', '29502', 'Viag Europlatform AG Liechtenstein', '295', 'LI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('250', '29505', 'Mobilkom (Liechstein) AG', '295', 'LI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('251', '29577', 'Tele2 AG Liechtenstein', '295', 'LI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('252', '30236', 'Clearnet Canada', '302', 'CA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('253', '30237', 'Microcell Canada', '302', 'CA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('254', '30262', 'Ice Wireless Canada', '302', 'CA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('255', '30263', 'Aliant Mobility Canada', '302', 'CA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('256', '30264', 'Bell Mobility Canada', '302', 'CA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('257', '302656', 'Tbay Mobility Canada', '302', 'CA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('258', '30266', 'MTS Mobility Canada', '302', 'CA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('259', '30267', 'CityTel Mobility Canada', '302', 'CA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('260', '30268', 'Sask Tel Mobility Canada', '302', 'CA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('261', '30271', 'Globalstar Canada', '302', 'CA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('262', '30272', 'Rogers Wireless Canada', '302', 'CA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('263', '30286', 'Telus Mobility Canada', '302', 'CA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('264', '30801', 'St. Pierre-et-Miquelon Telecom', '308', 'CA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('265', '310010', 'MCI USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('266', '310012', 'Verizon Wireless USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('267', '310013', 'Mobile Tel Inc. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('268', '310014', 'Testing USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('269', '310016', 'Cricket Communications USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('270', '310017', 'North Sight Communications Inc. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('271', '310020', 'Union Telephone Company USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('272', '310030', 'Centennial Communications USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('273', '310034', 'Nevada Wireless LLC USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('274', '310040', 'Concho Cellular Telephone Co., Inc. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('275', '310050', 'ACS Wireless Inc. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('276', '310060', 'Consolidated Telcom USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('277', '310070', 'Highland Cellular, Inc. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('278', '310080', 'Corr Wireless Communications LLC USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('279', '310090', 'Edge Wireless LLC USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('280', '310100', 'New Mexico RSA 4 East Ltd. Partnership USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('281', '310120', 'Sprint USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('282', '310130', 'Carolina West Wireless USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('283', '310140', 'GTA Wireless LLC USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('284', '310150', 'Cingular Wireless USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('285', '310160', 'T-Mobile USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('286', '310170', 'Cingular Wireless USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('287', '310180', 'West Central Wireless USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('288', '310190', 'Alaska Wireless Communications LLC USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('289', '310200', 'T-Mobile USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('290', '310210', 'T-Mobile USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('291', '310220', 'T-Mobile USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('292', '310230', 'T-Mobile USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('293', '310240', 'T-Mobile USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('294', '310250', 'T-Mobile USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('295', '310260', 'T-Mobile USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('296', '310270', 'T-Mobile USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('297', '310280', 'Contennial Puerto Rio License Corp. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('298', '310290', 'Nep Cellcorp Inc. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('299', '310300', 'Get Mobile Inc. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('300', '310310', 'T-Mobile USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('301', '310320', 'Bug Tussel Wireless LLC USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('302', '310330', 'AN Subsidiary LLC USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('303', '310340', 'High Plains Midwest LLC, dba Wetlink Communications USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('304', '310350', 'Mohave Cellular L.P. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('305', '310360', 'Cellular Network Partnership dba Pioneer Cellular USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('306', '310370', 'Guamcell Cellular and Paging USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('307', '310380', 'AT&T Wireless Services Inc. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('308', '310390', 'TX-11 Acquistion LLC USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('309', '310400', 'Wave Runner LLC USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('310', '310410', 'Cingular Wireless USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('311', '310420', 'Cincinnati Bell Wireless LLC USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('312', '310430', 'Alaska Digitel LLC USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('313', '310440', 'Numerex Corp. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('314', '310450', 'North East Cellular Inc. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('315', '310460', 'TMP Corporation USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('316', '310470', 'Guam Wireless Telephone Company USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('317', '310480', 'Choice Phone LLC USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('318', '310490', 'Triton PCS USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('319', '310500', 'Public Service Cellular, Inc. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('320', '310510', 'Airtel Wireless LLC USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('321', '310520', 'VeriSign USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('322', '310530', 'West Virginia Wireless USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('323', '310540', 'Oklahoma Western Telephone Company USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('324', '310560', 'American Cellular Corporation USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('325', '310570', 'MTPCS LLC USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('326', '310580', 'PCS ONE USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('327', '310590', 'Western Wireless Corporation USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('328', '310600', 'New Cell Inc. dba Cellcom USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('329', '310610', 'Elkhart Telephone Co. Inc. dba Epic Touch Co. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('330', '310620', 'Coleman County Telecommunications Inc. (Trans Texas PCS) USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('331', '310630', 'Comtel PCS Mainstreet LP USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('332', '310640', 'Airadigm Communications USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('333', '310650', 'Jasper Wireless Inc. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('334', '310660', 'T-Mobile USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('335', '310670', 'Northstar USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('336', '310680', 'Noverr Publishing, Inc. dba NPI Wireless USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('337', '310690', 'Conestoga Wireless Company USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('338', '310700', 'Cross Valiant Cellular Partnership USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('339', '310710', 'Arctic Slopo Telephone Association Cooperative USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('340', '310720', 'Wireless Solutions International Inc. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('341', '310730', 'Sea Mobile USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('342', '310740', 'Telemetrix Technologies USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('343', '310750', 'East Kentucky Network LLC dba Appalachian Wireless USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('344', '310760', 'Panhandle Telecommunications Systems Inc. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('345', '310770', 'Iowa Wireless Services LP USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('346', '310790', 'PinPoint Communications Inc. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('347', '310800', 'T-Mobile USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('348', '310810', 'Brazos Cellular Communications Ltd. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('349', '310820', 'Triton PCS License Company LLC USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('350', '310830', 'Caprock Cellular Ltd. Partnership USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('351', '310840', 'Edge Mobile LLC USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('352', '310850', 'Aeris Communications, Inc. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('353', '310870', 'Kaplan Telephone Company Inc. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('354', '310880', 'Advantage Cellular Systems, Inc. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('355', '310890', 'Rural Cellular Corporation USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('356', '310900', 'Taylor Telecommunications Ltd. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('357', '310910', 'Southern IL RSA Partnership dba First Cellular of Southern USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('358', '310940', 'Poka Lambro Telecommunications Ltd. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('359', '310950', 'Texas RSA 1 dba XIT Cellular USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('360', '310970', 'Globalstar USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('361', '310980', 'AT&T Wireless Services Inc. USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('362', '310990', 'Alaska Digitel USA', '310', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('363', '311000', 'Mid-Tex Cellular Ltd. USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('364', '311010', 'Chariton Valley Communications Corp., Inc. USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('365', '311020', 'Missouri RSA No. 5 Partnership USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('366', '311030', 'Indigo Wireless, Inc. USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('367', '311040', 'Commet Wireless, LLC USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('368', '311070', 'Easterbrooke Cellular Corporation USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('369', '311080', 'Pine Telephone Company dba Pine Cellular USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('370', '311090', 'Siouxland PCS USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('371', '311100', 'High Plains Wireless L.P. USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('372', '311110', 'High Plains Wireless L.P. USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('373', '311120', 'Choice Phone LLC USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('374', '311130', 'Amarillo License L.P. USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('375', '311140', 'MBO Wireless Inc./Cross Telephone Company USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('376', '311150', 'Wilkes Cellular Inc. USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('377', '311160', 'Endless Mountains Wireless, LLC USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('378', '311180', 'Cingular Wireless, Licensee Pacific Telesis Mobile Services, LLC USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('379', '311190', 'Cellular Properties Inc. USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('380', '311200', 'ARINC USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('381', '311210', 'Farmers Cellular Telephone USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('382', '311230', 'Cellular South Inc. USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('383', '311250', 'Wave Runner LLC USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('384', '311260', 'SLO Cellular Inc. dba CellularOne of San Luis Obispo USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('385', '311270', 'Alltel Communications Inc. USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('386', '311271', 'Alltel Communications Inc. USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('387', '311272', 'Alltel Communications Inc. USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('388', '311273', 'Alltel Communications Inc. USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('389', '311274', 'Alltel Communications Inc. USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('390', '311275', 'Alltel Communications Inc. USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('391', '311276', 'Alltel Communications Inc. USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('392', '311277', 'Alltel Communications Inc. USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('393', '311278', 'Alltel Communications Inc. USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('394', '311279', 'Alltel Communications Inc. USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('395', '311280', 'Verizon Wireless USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('396', '311281', 'Verizon Wireless USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('397', '311282', 'Verizon Wireless USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('398', '311283', 'Verizon Wireless USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('399', '311284', 'Verizon Wireless USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('400', '311285', 'Verizon Wireless USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('401', '311286', 'Verizon Wireless USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('402', '311287', 'Verizon Wireless USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('403', '311288', 'Verizon Wireless USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('404', '311289', 'Verizon Wireless USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('405', '311290', 'Pinpoint Wireless Inc. USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('406', '311320', 'Commnet Wireless LLC USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('407', '311340', 'Illinois Valley Cellular USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('408', '311380', 'New Dimension Wireless Ltd. USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('409', '311390', 'Midwest Wireless Holdings LLC USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('410', '311400', 'Salmon PCS LLC USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('411', '311410', 'Iowa RSA No.2 Ltd Partnership USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('412', '311420', 'Northwest Missouri Cellular Limited Partnership USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('413', '311430', 'RSA 1 Limited Partnership dba Cellular 29 Plus USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('414', '311440', 'Bluegrass Cellular LLC USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('415', '311450', 'Panhandle Telecommunication Systems Inc. USA', '311', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('416', '316010', 'Nextel Communications Inc. USA', '316', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('417', '316011', 'Southern Communications Services Inc. USA', '316', 'US');
INSERT INTO `razor_dim_devicesupplier` VALUES ('418', '334020', 'Telcel Mexico', '334', 'MX');
INSERT INTO `razor_dim_devicesupplier` VALUES ('419', '338020', 'Cable & Wireless Jamaica Ltd.', '338', 'JM');
INSERT INTO `razor_dim_devicesupplier` VALUES ('420', '338050', 'Mossel (Jamaica) Ltd.', '338', 'JM');
INSERT INTO `razor_dim_devicesupplier` VALUES ('421', '34001', 'Orange Carabe Mobiles Guadeloupe', '340', 'FW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('422', '34002', 'Outremer Telecom Guadeloupe', '340', 'FW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('423', '34003', 'Saint Martin et Saint Barthelemy Telcell Sarl Guadeloupe', '340', 'FW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('424', '34020', 'Bouygues Telecom Caraibe Guadeloupe', '340', 'FW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('425', '342600', 'Cable & Wireless (Barbados) Ltd.', '342', 'BB ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('426', '342820', 'Sunbeach Communications Barbados', '342', 'BB ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('427', '344030', 'APUA PCS Antigua ', '344', 'AG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('428', '344920', 'Cable & Wireless (Antigua)', '344', 'AG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('429', '344930', 'AT&T Wireless (Antigua)', '344', 'AG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('430', '346140', 'Cable & Wireless (Cayman)', '346', 'KY');
INSERT INTO `razor_dim_devicesupplier` VALUES ('431', '348570', 'Caribbean Cellular Telephone, Boatphone Ltd.', '348', 'BVI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('432', '35001', 'Telecom', '350', 'BM');
INSERT INTO `razor_dim_devicesupplier` VALUES ('433', '36251', 'TELCELL GSM Netherlands Antilles', '362', 'AN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('434', '36269', 'CT GSM Netherlands Antilles', '362', 'AN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('435', '36291', 'SETEL GSM Netherlands Antilles', '362', 'AN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('436', '36301', 'Setar GSM Aruba', '363', 'AW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('437', '365010', 'Weblinks Limited Anguilla', '365', 'AI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('438', '36801', 'ETECSA Cuba', '368', 'CU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('439', '37001', 'Orange Dominicana, S.A.', '370', 'DO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('440', '37002', 'Verizon Dominicana S.A.', '370', 'DO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('441', '37003', 'Tricom S.A. Dominican Rep.', '370', 'DO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('442', '37004', 'CentennialDominicana', '370', 'DO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('443', '37201', 'Comcel Haiti', '372', 'HT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('444', '37202', 'Digicel Haiti', '372', 'HT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('445', '37203', 'Rectel Haiti', '372', 'HT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('446', '37412', 'TSTT Mobile Trinidad and Tobago', '374', 'TT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('447', '374130', 'Digicel Trinidad and Tobago Ltd.', '374', 'TT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('448', '374140', 'LaqTel Ltd. Trinidad and Tobago', '374', 'TT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('449', '40001', 'Azercell Limited Liability Joint Venture', '400', 'AZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('450', '40002', 'Bakcell Limited Liability Company Azerbaijan', '400', 'AZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('451', '40003', 'Catel JV Azerbaijan', '400', 'AZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('452', '40004', 'Azerphone LLC', '400', 'AZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('453', '40101', 'Kar-Tel llc Kazakhstan', '401', 'KZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('454', '40102', 'TSC Kazak Telecom Kazakhstan', '401', 'KZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('455', '40211', 'Bhutan Telecom Ltd', '402', 'BT ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('456', '40217', 'B-Mobile of Bhutan Telecom', '402', 'BT ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('457', '40401', 'Aircell Digilink India Ltd.,', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('458', '40402', 'Bharti Mobile Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('459', '40403', 'Bharti Telenet Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('460', '40404', 'Idea Cellular Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('461', '40405', 'Fascel Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('462', '40406', 'Bharti Mobile Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('463', '40407', 'Idea Cellular Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('464', '40409', 'Reliance Telecom Private Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('465', '40410', 'Bharti Cellular Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('466', '40411', 'Sterling Cellular Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('467', '40412', 'Escotel Mobile Communications Pvt Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('468', '40413', 'Hutchinson Essar South Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('469', '40414', 'Spice Communications Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('470', '40415', 'Aircell Digilink India Ltd.', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('471', '40416', 'Hexcom India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('472', '40418', 'Reliance Telecom Private Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('473', '40419', 'Escotel Mobile Communications Pvt Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('474', '40420', 'Hutchinson Max Telecom India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('475', '40421', 'BPL Mobile Communications Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('476', '40422', 'Idea Cellular Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('477', '40424', 'Idea Cellular Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('478', '40427', 'BPL Cellular Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('479', '40430', 'Usha Martin Telecom Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('480', '40431', 'Bharti Mobinet Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('481', '40434', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('482', '40436', 'Reliance Telecom Private Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('483', '40438', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('484', '40440', 'Bharti Mobinet Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('485', '40441', 'RPG Cellular India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('486', '40442', 'Aircel Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('487', '40443', 'BPL Mobile Cellular Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('488', '40444', 'Spice Communications Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('489', '40446', 'BPL Cellular Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('490', '40449', 'Bharti Mobile Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('491', '40450', 'Reliance Telecom Private Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('492', '40451', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('493', '40452', 'Reliance Telecom Private Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('494', '40453', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('495', '40454', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('496', '40455', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('497', '40456', 'Escotel Mobile Communications Pvt Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('498', '40457', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('499', '40458', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('500', '40459', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('501', '40460', 'Aircell Digilink India Ltd.', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('502', '40462', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('503', '40464', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('504', '40466', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('505', '40467', 'Reliance Telecom Private Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('506', '40468', 'Mahanagar Telephone Nigam Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('507', '40469', 'Mahanagar Telephone Nigam Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('508', '40470', 'Hexicom India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('509', '40471', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('510', '40472', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('511', '40473', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('512', '40474', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('513', '40475', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('514', '40476', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('515', '40477', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('516', '40478', 'BTA Cellcom Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('517', '40480', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('518', '40481', 'Bharat Sanchar Nigam Ltd. (BSNL) India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('519', '40482', 'Escorts Telecom Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('520', '40483', 'Reliable Internet Services Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('521', '40484', 'Hutchinson Essar South Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('522', '40485', 'Reliance Telecom Private Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('523', '40486', 'Hutchinson Essar South Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('524', '40487', 'Escorts Telecom Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('525', '40488', 'Escorts Telecom Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('526', '40489', 'Escorts Telecom Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('527', '40490', 'Bharti Cellular Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('528', '40492', 'Bharti Cellular Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('529', '40493', 'Bharti Cellular Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('530', '40494', 'Bharti Cellular Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('531', '40495', 'Bharti Cellular Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('532', '40496', 'Bharti Cellular Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('533', '40497', 'Bharti Cellular Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('534', '40498', 'Bharti Cellular Ltd. India', '404', 'IN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('535', '41001', 'Mobilink Pakistan', '410', 'PK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('536', '41003', 'PAK Telecom Mobile Ltd. (UFONE) Pakistan', '410', 'PK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('537', '41201', 'AWCC Afghanistan', '412', 'AF');
INSERT INTO `razor_dim_devicesupplier` VALUES ('538', '41220', 'Roshan Afghanistan', '412', 'AF');
INSERT INTO `razor_dim_devicesupplier` VALUES ('539', '41230', 'New1 Afghanistan', '412', 'AF');
INSERT INTO `razor_dim_devicesupplier` VALUES ('540', '41240', 'Areeba Afghanistan', '412', 'AF');
INSERT INTO `razor_dim_devicesupplier` VALUES ('541', '41288', 'Afghan Telecom', '412', 'AF');
INSERT INTO `razor_dim_devicesupplier` VALUES ('542', '41302', 'MTN Network Ltd. Sri Lanka', '413', 'LK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('543', '41303', 'Celtel Lanka Ltd. Sri Lanka', '413', 'LK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('544', '41401', 'Myanmar Post and Telecommunication', '414', 'MM');
INSERT INTO `razor_dim_devicesupplier` VALUES ('545', '41532', 'Cellis Lebanon', '415', 'LB');
INSERT INTO `razor_dim_devicesupplier` VALUES ('546', '41533', 'Cellis Lebanon', '415', 'LB');
INSERT INTO `razor_dim_devicesupplier` VALUES ('547', '41534', 'Cellis Lebanon', '415', 'LB');
INSERT INTO `razor_dim_devicesupplier` VALUES ('548', '41535', 'Cellis Lebanon', '415', 'LB');
INSERT INTO `razor_dim_devicesupplier` VALUES ('549', '41536', 'Libancell', '415', 'LB');
INSERT INTO `razor_dim_devicesupplier` VALUES ('550', '41537', 'Libancell', '415', 'LB');
INSERT INTO `razor_dim_devicesupplier` VALUES ('551', '41538', 'Libancell', '415', 'LB');
INSERT INTO `razor_dim_devicesupplier` VALUES ('552', '41539', 'Libancell', '415', 'LB');
INSERT INTO `razor_dim_devicesupplier` VALUES ('553', '41601', 'Fastlink Jordan', '416', 'JO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('554', '41602', 'Xpress Jordan', '416', 'JO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('555', '41603', 'Umniah Jordan', '416', 'JO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('556', '41677', 'Mobilecom Jordan', '416', 'JO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('557', '41701', 'Syriatel', '417', 'SY');
INSERT INTO `razor_dim_devicesupplier` VALUES ('558', '41702', 'Spacetel Syria', '417', 'SY');
INSERT INTO `razor_dim_devicesupplier` VALUES ('559', '41709', 'Syrian Telecom', '417', 'SY');
INSERT INTO `razor_dim_devicesupplier` VALUES ('560', '41902', 'Mobile Telecommunications Company Kuwait', '419', 'KW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('561', '41903', 'Wataniya Telecom Kuwait', '419', 'KW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('562', '42001', 'Saudi Telecom', '420', 'SA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('563', '42101', 'Yemen Mobile Phone Company', '421', 'YE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('564', '42102', 'Spacetel Yemen', '421', 'YE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('565', '42202', 'Oman Mobile Telecommunications Company (Oman Mobile)', '422', 'OM');
INSERT INTO `razor_dim_devicesupplier` VALUES ('566', '42203', 'Oman Qatari Telecommunications Company (Nawras)', '422', 'OM');
INSERT INTO `razor_dim_devicesupplier` VALUES ('567', '42204', 'Oman Telecommunications Company (Omantel)', '422', 'OM');
INSERT INTO `razor_dim_devicesupplier` VALUES ('568', '42402', 'Etisalat United Arab Emirates', '424', 'AE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('569', '42501', 'Partner Communications Co. Ltd. Israel', '425', 'IL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('570', '42502', 'Cellcom Israel Ltd.', '425', 'IL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('571', '42503', 'Pelephone Communications Ltd. Israel', '425', 'IL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('572', '42601', 'BHR Mobile Plus Bahrain', '426', 'BH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('573', '42701', 'QATARNET', '427', 'QA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('574', '42899', 'Mobicom Mongolia', '428', 'MN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('575', '42901', 'Nepal Telecommunications', '429', 'NP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('576', '43211', 'Telecommunication Company of Iran (TCI)', '432', 'IR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('577', '43214', 'Telecommunication Kish Co. (KIFZO) Iran', '432', 'IR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('578', '43219', 'Telecommunication Company of Iran (TCI) Isfahan Celcom', '432', 'IR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('579', '43401', 'Buztel Uzbekistan', '434', 'UZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('580', '43402', 'Uzmacom Uzbekistan', '434', 'UZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('581', '43404', 'Daewoo Unitel Uzbekistan', '434', 'UZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('582', '43405', 'Coscom Uzbekistan', '434', 'UZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('583', '43407', 'Uzdunrobita Uzbekistan', '434', 'UZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('584', '43601', 'JC Somoncom Tajikistan', '436', 'TJ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('585', '43602', 'CJSC Indigo Tajikistan', '436', 'TJ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('586', '43603', 'TT mobile Tajikistan', '436', 'TJ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('587', '43604', 'Josa Babilon-T Tajikistan', '436', 'TJ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('588', '43605', 'CTJTHSC Tajik-tel', '436', 'TJ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('589', '43701', 'Bitel GSM Kyrgyzstan', '437', 'KG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('590', '43801', 'Barash Communication Technologies (BCTI) Turkmenistan', '438', 'TM');
INSERT INTO `razor_dim_devicesupplier` VALUES ('591', '43802', 'TM-Cell Turkmenistan', '438', 'TM');
INSERT INTO `razor_dim_devicesupplier` VALUES ('592', '44001', 'NTT DoCoMo, Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('593', '44002', 'NTT DoCoMo Kansai, Inc.  Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('594', '44003', 'NTT DoCoMo Hokuriku, Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('595', '44004', 'Vodafone Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('596', '44006', 'Vodafone Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('597', '44007', 'KDDI Corporation Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('598', '44008', 'KDDI Corporation Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('599', '44009', 'NTT DoCoMo Kansai Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('600', '44010', 'NTT DoCoMo Kansai Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('601', '44011', 'NTT DoCoMo Tokai Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('602', '44012', 'NTT DoCoMo Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('603', '44013', 'NTT DoCoMo Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('604', '44014', 'NTT DoCoMo Tohoku Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('605', '44015', 'NTT DoCoMo Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('606', '44016', 'NTT DoCoMo Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('607', '44017', 'NTT DoCoMo Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('608', '44018', 'NTT DoCoMo Tokai Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('609', '44019', 'NTT DoCoMo Hokkaido Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('610', '44020', 'NTT DoCoMo Hokuriku Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('611', '44021', 'NTT DoCoMo Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('612', '44022', 'NTT DoCoMo Kansai Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('613', '44023', 'NTT DoCoMo Tokai Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('614', '44024', 'NTT DoCoMo Chugoku Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('615', '44025', 'NTT DoCoMo Hokkaido Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('616', '44026', 'NTT DoCoMo Kyushu Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('617', '44027', 'NTT DoCoMo Tohoku Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('618', '44028', 'NTT DoCoMo Shikoku Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('619', '44029', 'NTT DoCoMo Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('620', '44030', 'NTT DoCoMo Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('621', '44031', 'NTT DoCoMo Kansai Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('622', '44032', 'NTT DoCoMo Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('623', '44033', 'NTT DoCoMo Tokai Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('624', '44034', 'NTT DoCoMo Kyushu Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('625', '44035', 'NTT DoCoMo Kansai Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('626', '44036', 'NTT DoCoMo Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('627', '44037', 'NTT DoCoMo Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('628', '44038', 'NTT DoCoMo Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('629', '44039', 'NTT DoCoMo Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('630', '44040', 'Vodafone Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('631', '44041', 'Vodafone Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('632', '44042', 'Vodafone Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('633', '44043', 'Vodafone Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('634', '44044', 'Vodafone Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('635', '44045', 'Vodafone Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('636', '44046', 'Vodafone Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('637', '44047', 'Vodafone Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('638', '44048', 'Vodafone Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('639', '44049', 'NTT DoCoMo Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('640', '44050', 'KDDI Corporation Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('641', '44051', 'KDDI Corporation Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('642', '44052', 'KDDI Corporation Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('643', '44053', 'KDDI Corporation Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('644', '44054', 'KDDI Corporation Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('645', '44055', 'KDDI Corporation Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('646', '44056', 'KDDI Corporation Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('647', '44058', 'NTT DoCoMo Kansai Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('648', '44060', 'NTT DoCoMo Kansai Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('649', '44061', 'NTT DoCoMo Chugoku Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('650', '44062', 'NTT DoCoMo Kyushu Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('651', '44063', 'NTT DoCoMo Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('652', '44064', 'NTT DoCoMo Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('653', '44065', 'NTT DoCoMo Shikoku Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('654', '44066', 'NTT DoCoMo Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('655', '44067', 'NTT DoCoMo Tohoku Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('656', '44068', 'NTT DoCoMo Kyushu Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('657', '44069', 'NTT DoCoMo Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('658', '44070', 'KDDI Corporation Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('659', '44071', 'KDDI Corporation Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('660', '44072', 'KDDI Corporation Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('661', '44073', 'KDDI Corporation Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('662', '44074', 'KDDI Corporation Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('663', '44075', 'KDDI Corporation Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('664', '44076', 'KDDI Corporation Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('665', '44077', 'KDDI Corporation Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('666', '44078', 'Okinawa Cellular Telephone Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('667', '44079', 'KDDI Corporation Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('668', '44080', 'TU-KA Cellular Tokyo Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('669', '44081', 'TU-KA Cellular Tokyo Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('670', '44082', 'TU-KA Phone Kansai Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('671', '44083', 'TU-KA Cellular Tokai Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('672', '44084', 'TU-KA Phone Kansai Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('673', '44085', 'TU-KA Cellular Tokai Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('674', '44086', 'TU-KA Cellular Tokyo Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('675', '44087', 'NTT DoCoMo Chugoku Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('676', '44088', 'KDDI Corporation Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('677', '44089', 'KDDI Corporation Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('678', '44090', 'Vodafone Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('679', '44092', 'Vodafone Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('680', '44093', 'Vodafone Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('681', '44094', 'Vodafone Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('682', '44095', 'Vodafone Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('683', '44096', 'Vodafone Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('684', '44097', 'Vodafone Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('685', '44098', 'Vodafone Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('686', '44099', 'NTT DoCoMo Inc. Japan', '440', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('687', '44140', 'NTT DoCoMo Inc. Japan', '441', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('688', '44141', 'NTT DoCoMo Inc. Japan', '441', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('689', '44142', 'NTT DoCoMo Inc. Japan', '441', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('690', '44143', 'NTT DoCoMo Kansai Inc. Japan', '441', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('691', '44144', 'NTT DoCoMo Chugoku Inc. Japan', '441', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('692', '44145', 'NTT DoCoMo Shikoku Inc. Japan', '441', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('693', '44150', 'TU-KA Cellular Tokyo Inc. Japan', '441', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('694', '44151', 'TU-KA Phone Kansai Inc. Japan', '441', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('695', '44161', 'Vodafone Japan', '441', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('696', '44162', 'Vodafone Japan', '441', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('697', '44163', 'Vodafone Japan', '441', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('698', '44164', 'Vodafone Japan', '441', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('699', '44165', 'Vodafone Japan', '441', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('700', '44170', 'KDDI Corporation Japan', '441', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('701', '44190', 'NTT DoCoMo Inc. Japan', '441', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('702', '44191', 'NTT DoCoMo Inc. Japan', '441', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('703', '44192', 'NTT DoCoMo Inc. Japan', '441', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('704', '44193', 'NTT DoCoMo Hokkaido Inc. Japan', '441', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('705', '44194', 'NTT DoCoMo Tohoku Inc. Japan', '441', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('706', '44198', 'NTT DoCoMo Kyushu Inc. Japan', '441', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('707', '44199', 'NTT DoCoMo Kyushu Inc. Japan', '441', 'JP');
INSERT INTO `razor_dim_devicesupplier` VALUES ('708', '45201', 'Mobifone Vietnam', '452', 'VN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('709', '45202', 'Vinaphone Vietnam', '452', 'VN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('710', '45400', 'CSL', '454', 'HK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('711', '45401', 'MVNO/CITIC Hong Kong', '454', 'HK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('712', '45402', '3G Radio System/HKCSL3G Hong Kong', '454', 'HK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('713', '45403', 'Hutchison 3G', '454', 'HK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('714', '45404', 'GSM900/GSM1800/Hutchison Hong Kong', '454', 'HK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('715', '45405', 'CDMA/Hutchison Hong Kong', '454', 'HK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('716', '45406', 'SMC', '454', 'HK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('717', '45407', 'MVNO/China Unicom International Ltd. Hong Kong', '454', 'HK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('718', '45408', 'MVNO/Trident Hong Kong', '454', 'HK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('719', '45409', 'MVNO/China Motion Telecom (HK) Ltd. Hong Kong', '454', 'HK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('720', '45410', 'GSM1800New World PCS Ltd. Hong Kong', '454', 'HK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('721', '45411', 'MVNO/CHKTL Hong Kong', '454', 'HK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('722', '45412', 'PEOPLES', '454', 'HK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('723', '45415', '3G Radio System/SMT3G Hong Kong', '454', 'HK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('724', '45416', 'GSM1800/Mandarin Communications Ltd. Hong Kong', '454', 'HK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('725', '45418', 'GSM7800/Hong Kong CSL Ltd.', '454', 'HK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('726', '45419', 'Sunday3G', '454', 'HK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('727', '45500', 'Smartone Mobile Communications (Macao) Ltd.', '455', 'MO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('728', '45501', 'CTM GSM Macao', '455', 'MO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('729', '45503', 'Hutchison Telecom Macao', '455', 'MO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('730', '45601', 'Mobitel (Cam GSM) Cambodia', '456', 'KH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('731', '45602', 'Samart (Casacom) Cambodia', '456', 'KH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('732', '45603', 'S Telecom (CDMA) (reserved) Cambodia', '456', 'KH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('733', '45618', 'Camshin (Shinawatra) Cambodia', '456', 'KH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('734', '45701', 'Lao Telecommunications', '457', 'LA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('735', '45702', 'ETL Mobile Lao', '457', 'LA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('736', '45708', 'Millicom Lao', '457', 'LA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('737', '46000', 'China Mobile', '460', 'CN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('738', '46001', 'China Unicom', '460', 'CN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('739', '46002', 'China Mobile', '460', 'CN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('740', '46003', 'China Telecom', '460', 'CN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('741', '46004', 'China Satellite Global Star Network', '460', 'CN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('742', '46601', 'Far EasTone', '466', 'TW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('743', '46606', 'TUNTEX', '466', 'TW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('744', '46668', 'ACeS', '466', 'TW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('745', '46688', 'KGT', '466', 'TW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('746', '46689', 'KGT', '466', 'TW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('747', '46692', 'Chunghwa', '466', 'TW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('748', '46693', 'MobiTai', '466', 'TW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('749', '46697', 'TWN GSM', '466', 'TW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('750', '46699', 'TransAsia', '466', 'TW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('751', '47001', 'GramenPhone Bangladesh', '470', 'BD');
INSERT INTO `razor_dim_devicesupplier` VALUES ('752', '47002', 'Aktel Bangladesh', '470', 'BD');
INSERT INTO `razor_dim_devicesupplier` VALUES ('753', '47003', 'Mobile 2000 Bangladesh', '470', 'BD');
INSERT INTO `razor_dim_devicesupplier` VALUES ('754', '47201', 'DhiMobile Maldives', '472', 'MV');
INSERT INTO `razor_dim_devicesupplier` VALUES ('755', '50200', 'Art900 Malaysia', '502', 'MY');
INSERT INTO `razor_dim_devicesupplier` VALUES ('756', '50212', 'Maxis Malaysia', '502', 'MY');
INSERT INTO `razor_dim_devicesupplier` VALUES ('757', '50213', 'TM Touch Malaysia', '502', 'MY');
INSERT INTO `razor_dim_devicesupplier` VALUES ('758', '50216', 'DiGi', '502', 'MY');
INSERT INTO `razor_dim_devicesupplier` VALUES ('759', '50217', 'TimeCel Malaysia', '502', 'MY');
INSERT INTO `razor_dim_devicesupplier` VALUES ('760', '50219', 'CelCom Malaysia', '502', 'MY');
INSERT INTO `razor_dim_devicesupplier` VALUES ('761', '50501', 'Telstra Corporation Ltd. Australia', '505', 'AU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('762', '50502', 'Optus Mobile Pty. Ltd. Australia', '505', 'AU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('763', '50503', 'Vodafone Network Pty. Ltd. Australia', '505', 'AU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('764', '50504', 'Department of Defence Australia', '505', 'AU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('765', '50505', 'The Ozitel Network Pty. Ltd. Australia', '505', 'AU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('766', '50506', 'Hutchison 3G Australia Pty. Ltd.', '505', 'AU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('767', '50507', 'Vodafone Network Pty. Ltd. Australia', '505', 'AU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('768', '50508', 'One.Tel GSM 1800 Pty. Ltd. Australia', '505', 'AU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('769', '50509', 'Airnet Commercial Australia Ltd.', '505', 'AU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('770', '50511', 'Telstra Corporation Ltd. Australia', '505', 'AU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('771', '50512', 'Hutchison Telecommunications (Australia) Pty. Ltd.', '505', 'AU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('772', '50514', 'AAPT Ltd. Australia', '505', 'AU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('773', '50515', '3GIS Pty Ltd. (Telstra & Hutchison 3G) Australia', '505', 'AU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('774', '50524', 'Advanced Communications Technologies Pty. Ltd. Australia', '505', 'AU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('775', '50571', 'Telstra Corporation Ltd. Australia', '505', 'AU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('776', '50572', 'Telstra Corporation Ltd. Australia', '505', 'AU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('777', '50588', 'Localstar Holding Pty. Ltd. Australia', '505', 'AU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('778', '50590', 'Optus Ltd. Australia', '505', 'AU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('779', '50599', 'One.Tel GSM 1800 Pty. Ltd. Australia', '505', 'AU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('780', '51000', 'PSN Indonesia', '510', 'ID');
INSERT INTO `razor_dim_devicesupplier` VALUES ('781', '51001', 'Satelindo Indonesia', '510', 'ID');
INSERT INTO `razor_dim_devicesupplier` VALUES ('782', '51008', 'Natrindo (Lippo Telecom) Indonesia', '510', 'ID');
INSERT INTO `razor_dim_devicesupplier` VALUES ('783', '51010', 'Telkomsel Indonesia', '510', 'ID');
INSERT INTO `razor_dim_devicesupplier` VALUES ('784', '51011', 'Excelcomindo Indonesia', '510', 'ID');
INSERT INTO `razor_dim_devicesupplier` VALUES ('785', '51021', 'Indosat - M3 Indonesia', '510', 'ID');
INSERT INTO `razor_dim_devicesupplier` VALUES ('786', '51028', 'Komselindo Indonesia', '510', 'ID');
INSERT INTO `razor_dim_devicesupplier` VALUES ('787', '51501', 'Islacom Philippines', '515', 'PH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('788', '51502', 'Globe Telecom Philippines', '515', 'PH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('789', '51503', 'Smart Communications Philippines', '515', 'PH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('790', '51505', 'Digitel Philippines', '515', 'PH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('791', '52000', 'CAT CDMA Thailand', '520', 'TH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('792', '52001', 'AIS GSM Thailand', '520', 'TH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('793', '52015', 'ACT Mobile Thailand', '520', 'TH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('794', '52501', 'SingTel ST GSM900 Singapore', '525', 'SG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('795', '52502', 'SingTel ST GSM1800 Singapore', '525', 'SG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('796', '52503', 'MobileOne Singapore', '525', 'SG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('797', '52505', 'STARHUB-SGP', '525', 'SG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('798', '52512', 'Digital Trunked Radio Network Singapore', '525', 'SG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('799', '52811', 'DST Com Brunei ', '528', 'BN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('800', '53000', 'Reserved for AMPS MIN based IMSIs New Zealand', '530', 'NZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('801', '53001', 'Vodafone New Zealand GSM Mobile Network', '530', 'NZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('802', '53002', 'Teleom New Zealand CDMA Mobile Network', '530', 'NZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('803', '53003', 'Walker Wireless Ltd. New Zealand', '530', 'NZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('804', '53028', 'Econet Wireless New Zealand GSM Mobile Network', '530', 'NZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('805', '53701', 'Pacific Mobile Communications Papua New Guinea', '537', 'PG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('806', '53702', 'Dawamiba PNG Ltd Papua New Guinea', '537', 'PG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('807', '53703', 'Digicel Ltd Papua New Guinea', '537', 'PG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('808', '53901', 'Tonga Communications Corporation', '539', 'TO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('809', '53943', 'Shoreline Communication Tonga', '539', 'TO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('810', '54101', 'SMILE Vanuatu', '541', 'VU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('811', '54201', 'Vodafone Fiji', '542', 'FJ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('812', '54411', 'Blue Sky', '544', 'AS');
INSERT INTO `razor_dim_devicesupplier` VALUES ('813', '54601', 'OPT Mobilis New Caledonia', '546', 'NC');
INSERT INTO `razor_dim_devicesupplier` VALUES ('814', '54720', 'Tikiphone French Polynesia', '547', 'PF');
INSERT INTO `razor_dim_devicesupplier` VALUES ('815', '54801', 'Telecom Cook', '548', 'CK');
INSERT INTO `razor_dim_devicesupplier` VALUES ('816', '54901', 'Telecom Samoa Cellular Ltd.', '549', 'WS');
INSERT INTO `razor_dim_devicesupplier` VALUES ('817', '54927', 'GoMobile SamoaTel Ltd', '549', 'WS');
INSERT INTO `razor_dim_devicesupplier` VALUES ('818', '55001', 'FSM Telecom Micronesia', '550', 'FM');
INSERT INTO `razor_dim_devicesupplier` VALUES ('819', '55201', 'Palau National Communications Corp. (a.k.a. PNCC)', '552', 'PW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('820', '60201', 'EMS - Mobinil Egypt', '602', 'EG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('821', '60202', 'Vodafone Egypt', '602', 'EG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('822', '60301', 'Algrie Telecom', '603', 'DZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('823', '60302', 'Orascom Telecom Algrie', '603', 'DZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('824', '60400', 'Meditelecom (GSM) Morocco', '604', 'MA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('825', '60401', 'Ittissalat Al Maghrid Morocco', '604', 'MA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('826', '60502', 'Tunisie Telecom', '605', 'TN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('827', '60503', 'Orascom Telecom Tunisia', '605', 'TN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('828', '60701', 'Gamcel Gambia', '607', 'GM');
INSERT INTO `razor_dim_devicesupplier` VALUES ('829', '60702', 'Africell Gambia', '607', 'GM');
INSERT INTO `razor_dim_devicesupplier` VALUES ('830', '60703', 'Comium Services Ltd Gambia', '607', 'GM');
INSERT INTO `razor_dim_devicesupplier` VALUES ('831', '60801', 'Sonatel Senegal', '608', 'SN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('832', '60802', 'Sentel GSM Senegal', '608', 'SN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('833', '60901', 'Mattel S.A.', '609', 'MR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('834', '60902', 'Chinguitel S.A. ', '609', 'MR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('835', '60910', 'Mauritel Mobiles  ', '609', 'MR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('836', '61001', 'Malitel', '610', 'ML');
INSERT INTO `razor_dim_devicesupplier` VALUES ('837', '61101', 'Spacetel Guinea', '611', 'GN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('838', '61102', 'Sotelgui Guinea', '611', 'GN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('839', '61202', 'Atlantique Cellulaire Cote d Ivoire', '612', 'CI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('840', '61203', 'Orange Cote dIvoire', '612', 'CI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('841', '61204', 'Comium Cote d Ivoire', '612', 'CI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('842', '61205', 'Loteny Telecom Cote d Ivoire', '612', 'CI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('843', '61206', 'Oricel Cote d Ivoire', '612', 'CI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('844', '61207', 'Aircomm Cote d Ivoire', '612', 'CI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('845', '61302', 'Celtel Burkina Faso', '613', 'BF');
INSERT INTO `razor_dim_devicesupplier` VALUES ('846', '61303', 'Telecel Burkina Faso', '613', 'BF');
INSERT INTO `razor_dim_devicesupplier` VALUES ('847', '61401', 'Sahel.Com Niger', '614', 'NE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('848', '61402', 'Celtel Niger', '614', 'NE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('849', '61403', 'Telecel Niger', '614', 'NE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('850', '61501', 'Togo Telecom', '615', 'TG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('851', '61601', 'Libercom Benin', '616', 'BJ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('852', '61602', 'Telecel Benin', '616', 'BJ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('853', '61603', 'Spacetel Benin', '616', 'BJ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('854', '61701', 'Cellplus Mauritius', '617', 'MU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('855', '61702', 'Mahanagar Telephone (Mauritius) Ltd.', '617', 'MU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('856', '61710', 'Emtel Mauritius', '617', 'MU');
INSERT INTO `razor_dim_devicesupplier` VALUES ('857', '61804', 'Comium Liberia', '618', 'LR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('858', '61901', 'Celtel Sierra Leone', '619', 'SL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('859', '61902', 'Millicom Sierra Leone', '619', 'SL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('860', '61903', 'Africell Sierra Leone', '619', 'SL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('861', '61904', 'Comium (Sierra Leone) Ltd.', '619', 'SL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('862', '61905', 'Lintel (Sierra Leone) Ltd.', '619', 'SL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('863', '61925', 'Mobitel Sierra Leone', '619', 'SL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('864', '61940', 'Datatel (SL) Ltd GSM Sierra Leone', '619', 'SL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('865', '61950', 'Dtatel (SL) Ltd CDMA Sierra Leone', '619', 'SL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('866', '62001', 'Spacefon Ghana', '620', 'GH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('867', '62002', 'Ghana Telecom Mobile', '620', 'GH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('868', '62003', 'Mobitel Ghana', '620', 'GH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('869', '62004', 'Kasapa Telecom Ltd. Ghana', '620', 'GH');
INSERT INTO `razor_dim_devicesupplier` VALUES ('870', '62120', 'Econet Wireless Nigeria Ltd.', '621', 'NG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('871', '62130', 'MTN Nigeria Communications', '621', 'NG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('872', '62140', 'Nigeria Telecommunications Ltd.', '621', 'NG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('873', '62201', 'Celtel Chad', '622', 'TD');
INSERT INTO `razor_dim_devicesupplier` VALUES ('874', '62202', 'Tchad Mobile', '622', 'TD');
INSERT INTO `razor_dim_devicesupplier` VALUES ('875', '62301', 'Centrafrique Telecom Plus (CTP)', '623', 'CF');
INSERT INTO `razor_dim_devicesupplier` VALUES ('876', '62302', 'Telecel Centrafrique (TC)', '623', 'CF');
INSERT INTO `razor_dim_devicesupplier` VALUES ('877', '62303', 'Celca (Socatel) Central African Rep.', '623', 'CF');
INSERT INTO `razor_dim_devicesupplier` VALUES ('878', '62401', 'Mobile Telephone Networks Cameroon', '624', 'CM');
INSERT INTO `razor_dim_devicesupplier` VALUES ('879', '62402', 'Orange Cameroun', '624', 'CM');
INSERT INTO `razor_dim_devicesupplier` VALUES ('880', '62501', 'Cabo Verde Telecom', '625', 'CV');
INSERT INTO `razor_dim_devicesupplier` VALUES ('881', '62601', 'Companhia Santomese de Telecomunicacoes', '626', 'ST');
INSERT INTO `razor_dim_devicesupplier` VALUES ('882', '62701', 'Guinea Ecuatorial de Telecomunicaciones Sociedad Anonima', '627', 'GQ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('883', '62801', 'Libertis S.A. Gabon', '628', 'GA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('884', '62802', 'Telecel Gabon S.A.', '628', 'GA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('885', '62803', 'Celtel Gabon S.A.', '628', 'GA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('886', '62901', 'Celtel Congo', '629', 'CG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('887', '62910', 'Libertis Telecom Congo', '629', 'CG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('888', '63001', 'Vodacom Congo RDC sprl', '630', 'CD');
INSERT INTO `razor_dim_devicesupplier` VALUES ('889', '63005', 'Supercell Sprl Congo', '630', 'CD');
INSERT INTO `razor_dim_devicesupplier` VALUES ('890', '63086', 'Congo-Chine Telecom s.a.r.l.', '630', 'CD');
INSERT INTO `razor_dim_devicesupplier` VALUES ('891', '63102', 'Unitel Angola', '631', 'AO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('892', '63201', 'Guinetel S.A. Guinea-Bissau', '632', 'GW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('893', '63202', 'Spacetel Guine-Bissau S.A.', '632', 'GW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('894', '63301', 'Cable & Wireless (Seychelles) Ltd.', '633', 'SC');
INSERT INTO `razor_dim_devicesupplier` VALUES ('895', '63302', 'Mediatech International Ltd. Seychelles', '633', 'SC');
INSERT INTO `razor_dim_devicesupplier` VALUES ('896', '63310', 'Telecom (Seychelles) Ltd.', '633', 'SC');
INSERT INTO `razor_dim_devicesupplier` VALUES ('897', '63401', 'SD Mobitel Sudan', '634', 'MZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('898', '63402', 'Areeba-Sudan', '634', 'MZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('899', '63510', 'MTN Rwandacell', '635', 'RW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('900', '63601', 'ETH MTN Ethiopia', '636', 'ET');
INSERT INTO `razor_dim_devicesupplier` VALUES ('901', '63730', 'Golis Telecommunications Company Somalia', '637', 'SO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('902', '63801', 'Evatis Djibouti', '638', 'DJ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('903', '63902', 'Safaricom Ltd. Kenya', '639', 'KE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('904', '63903', 'Kencell Communications Ltd. Kenya', '639', 'KE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('905', '64002', 'MIC (T) Ltd. Tanzania', '640', 'TZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('906', '64003', 'Zantel Tanzania', '640', 'TZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('907', '64004', 'Vodacom (T) Ltd. Tanzania', '640', 'TZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('908', '64005', 'Celtel (T) Ltd. Tanzania', '640', 'TZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('909', '64101', 'Celtel Uganda', '641', 'UG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('910', '64110', 'MTN Uganda Ltd.', '641', 'UG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('911', '64111', 'Uganda Telecom Ltd.', '641', 'UG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('912', '64201', 'Spacetel Burundi', '642', 'BI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('913', '64202', 'Safaris Burundi', '642', 'BI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('914', '64203', 'Telecel Burundi Company', '642', 'BI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('915', '64301', 'T.D.M. GSM Mozambique', '643', 'MZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('916', '64304', 'VM Sarl Mozambique', '643', 'MZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('917', '64501', 'Celtel Zambia Ltd.', '645', 'ZM');
INSERT INTO `razor_dim_devicesupplier` VALUES ('918', '64502', 'Telecel Zambia Ltd.', '645', 'ZM');
INSERT INTO `razor_dim_devicesupplier` VALUES ('919', '64503', 'Zamtel Zambia', '645', 'ZM');
INSERT INTO `razor_dim_devicesupplier` VALUES ('920', '64601', 'MADACOM Madagascar', '646', 'MG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('921', '64602', 'Orange Madagascar', '646', 'MG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('922', '64604', 'Telecom Malagasy Mobile Madagascar', '646', 'MG');
INSERT INTO `razor_dim_devicesupplier` VALUES ('923', '64700', 'Orange La Reunion', '647', 'RE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('924', '64702', 'Outremer Telecom', '647', 'RE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('925', '64710', 'Societe Reunionnaise du Radiotelephone', '647', 'RE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('926', '64801', 'Net One Zimbabwe', '648', 'ZW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('927', '64803', 'Telecel Zimbabwe', '648', 'ZW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('928', '64804', 'Econet Zimbabwe', '648', 'ZW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('929', '64901', 'Mobile Telecommunications Ltd. Namibia', '649', 'NA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('930', '64903', 'Powercom Pty Ltd Namibia', '649', 'NA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('931', '65001', 'Telekom Network Ltd. Malawi', '650', 'MW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('932', '65010', 'Celtel ltd. Malawi', '650', 'MW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('933', '65101', 'Vodacom Lesotho (pty) Ltd.', '651', 'LS');
INSERT INTO `razor_dim_devicesupplier` VALUES ('934', '65102', 'Econet Ezin-cel Lesotho', '651', 'LS');
INSERT INTO `razor_dim_devicesupplier` VALUES ('935', '65201', 'Mascom Wireless (Pty) Ltd. Botswana', '652', 'BW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('936', '65202', 'Orange Botswana (Pty) Ltd.', '652', 'BW');
INSERT INTO `razor_dim_devicesupplier` VALUES ('937', '65310', 'Swazi MTN', '653', 'SZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('938', '65401', 'HURI - SNPT Comoros', '654', 'KM');
INSERT INTO `razor_dim_devicesupplier` VALUES ('939', '65501', 'Vodacom (Pty) Ltd. South Africa', '655', 'ZA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('940', '65506', 'Sentech (Pty) Ltd. South Africa', '655', 'ZA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('941', '65507', 'Cell C (Pty) Ltd. South Africa', '655', 'ZA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('942', '65510', 'Mobile Telephone Networks South Africa', '655', 'ZA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('943', '65511', 'SAPS Gauteng South Africa', '655', 'ZA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('944', '65521', 'Cape Town Metropolitan Council South Africa', '655', 'ZA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('945', '65530', 'Bokamoso Consortium South Africa', '655', 'ZA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('946', '65531', 'Karabo Telecoms (Pty) Ltd. South Africa', '655', 'ZA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('947', '65532', 'Ilizwi Telecommunications South Africa', '655', 'ZA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('948', '65533', 'Thinta Thinta Telecommunications South Africa', '655', 'ZA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('949', '65534', 'Bokone Telecoms South Africa', '655', 'ZA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('950', '65535', 'Kingdom Communications South Africa', '655', 'ZA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('951', '65536', 'Amatole Telecommunication Services South Africa', '655', 'ZA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('952', '70267', 'Belize Telecommunications Ltd., GSM 1900', '702', 'BZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('953', '70268', 'International Telecommunications Ltd. (INTELCO) Belize', '702', 'BZ');
INSERT INTO `razor_dim_devicesupplier` VALUES ('954', '70401', 'Servicios de Comunicaciones Personales Inalambricas, S.A. Guatemala', '704', 'GT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('955', '70402', 'Comunicaciones Celulares S.A. Guatemala', '704', 'GT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('956', '70403', 'Telefonica Centroamerica Guatemala S.A.', '704', 'GT');
INSERT INTO `razor_dim_devicesupplier` VALUES ('957', '70601', 'CTE Telecom Personal, S.A. de C.V. El Salvador', '706', 'SV');
INSERT INTO `razor_dim_devicesupplier` VALUES ('958', '70602', 'Digicel, S.A. de C.V. El Salvador', '706', 'SV');
INSERT INTO `razor_dim_devicesupplier` VALUES ('959', '70603', 'Telemovil El Salvador, S.A.', '706', 'SV');
INSERT INTO `razor_dim_devicesupplier` VALUES ('960', '708001', 'Megatel Honduras', '708', 'HN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('961', '708002', 'Celtel Honduras', '708', 'HN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('962', '708040', 'Digicel Honduras', '708', 'HN');
INSERT INTO `razor_dim_devicesupplier` VALUES ('963', '71021', 'Empresa Nicaraguense de Telecomunicaciones, S.A. (ENITEL)', '710', 'NI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('964', '71073', 'Servicios de Comunicaciones, S.A. (SERCOM) Nicaragua', '710', 'NI');
INSERT INTO `razor_dim_devicesupplier` VALUES ('965', '71201', 'Instituto Costarricense de Electricidad - ICE', '712', 'CR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('966', '71401', 'Cable & Wireless Panama S.A.', '714', 'PA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('967', '71402', 'BSC de Panama S.A.', '714', 'PA');
INSERT INTO `razor_dim_devicesupplier` VALUES ('968', '71610', 'TIM Peru', '716', 'PE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('969', '722010', 'Compaia de Radiocomunicaciones Moviles S.A. Argentina', '722', 'AR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('970', '722020', 'Nextel Argentina srl', '722', 'AR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('971', '722070', 'Telefonica Comunicaciones Personales S.A. Argentina', '722', 'AR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('972', '722310', 'CTI PCS S.A. Argentina', '722', 'AR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('973', '722320', 'Compaia de Telefonos del Interior Norte S.A. Argentina', '722', 'AR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('974', '722330', 'Compaia de Telefonos del Interior S.A. Argentina', '722', 'AR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('975', '722341', 'Telecom Personal S.A. Argentina', '722', 'AR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('976', '72400', 'Telet Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('977', '72401', 'CRT Cellular Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('978', '72402', 'Global Telecom Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('979', '72403', 'CTMR Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('980', '72404', 'BCP Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('981', '72405', 'Telesc Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('982', '72406', 'Tess Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('983', '72407', 'Sercontel Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('984', '72408', 'Maxitel MG Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('985', '72409', 'Telepar Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('986', '72410', 'ATL Algar Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('987', '72411', 'Telems Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('988', '72412', 'Americel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('989', '72413', 'Telesp Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('990', '72414', 'Maxitel BA Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('991', '72415', 'CTBC Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('992', '72416', 'BSE Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('993', '72417', 'Ceterp Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('994', '72418', 'Norte Brasil Tel', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('995', '72419', 'Telemig Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('996', '72421', 'Telerj Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('997', '72423', 'Telest Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('998', '72425', 'Telebrasilia Cel', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('999', '72427', 'Telegoias Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1000', '72429', 'Telemat Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1001', '72431', 'Teleacre Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1002', '72433', 'Teleron Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1003', '72435', 'Telebahia Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1004', '72437', 'Telergipe Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1005', '72439', 'Telasa Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1006', '72441', 'Telpe Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1007', '72443', 'Telepisa Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1008', '72445', 'Telpa Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1009', '72447', 'Telern Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1010', '72448', 'Teleceara Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1011', '72451', 'Telma Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1012', '72453', 'Telepara Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1013', '72455', 'Teleamazon Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1014', '72457', 'Teleamapa Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1015', '72459', 'Telaima Cel Brazil', '724', 'BR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1016', '73001', 'Entel Telefonica Movil Chile', '730', 'CL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1017', '73002', 'Telefonica Movil Chile', '730', 'CL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1018', '73003', 'Smartcom Chile', '730', 'CL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1019', '73004', 'Centennial Cayman Corp. Chile S.A.', '730', 'CL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1020', '73005', 'Multikom S.A. Chile', '730', 'CL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1021', '73010', 'Entel Chile', '730', 'CL');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1022', '732001', 'Colombia Telecomunicaciones S.A. - Telecom', '732', 'CO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1023', '732002', 'Edatel S.A. Colombia', '732', 'CO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1024', '732101', 'Comcel S.A. Occel S.A./Celcaribe Colombia', '732', 'CO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1025', '732102', 'Bellsouth Colombia S.A.', '732', 'CO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1026', '732103', 'Colombia Movil S.A.', '732', 'CO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1027', '732111', 'Colombia Movil S.A.', '732', 'CO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1028', '732123', 'Telfonica Moviles Colombia S.A.', '732', 'CO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1029', '73401', 'Infonet Venezuela', '734', 'VE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1030', '73402', 'Corporacion Digitel Venezuela', '734', 'VE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1031', '73403', 'Digicel Venezuela', '734', 'VE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1032', '73404', 'Telcel, C.A. Venezuela', '734', 'VE');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1033', '73601', 'Nuevatel S.A. Bolivia', '736', 'BO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1034', '73602', 'ENTEL S.A. Bolivia', '736', 'BO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1035', '73603', 'Telecel S.A. Bolivia', '736', 'BO');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1036', '73801', 'Cel*Star (Guyana) Inc.', '738', 'GY');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1037', '74000', 'Otecel S.A. - Bellsouth Ecuador', '740', 'EC');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1038', '74001', 'Porta GSM Ecuador', '740', 'EC');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1039', '74002', 'Telecsa S.A. Ecuador', '740', 'EC');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1040', '74401', 'Hola Paraguay S.A.', '744', 'PY');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1041', '74402', 'Hutchison Telecom S.A. Paraguay', '744', 'PY');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1042', '74403', 'Compania Privada de Comunicaciones S.A. Paraguay', '744', 'PY');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1043', '74602', 'Telesur Suriname', '746', 'SR');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1044', '74800', 'Ancel TDMA Uruguay', '748', 'UY');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1045', '74801', 'Ancel GSM Uruguay', '748', 'UY');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1046', '74803', 'Ancel Uruguay', '748', 'UY');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1047', '74807', 'Movistar Uruguay', '748', 'UY');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1048', '74810', 'CTI Movil Uruguay', '748', 'UY');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1049', '90101', 'ICO Global Communications', '901', 'International Mobile, shared code');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1050', '90102', 'Sense Communications International AS', '901', 'International Mobile, shared code');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1051', '90103', 'Iridium Satellite, LLC (GMSS)', '901', 'International Mobile, shared code');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1052', '90104', 'Globalstar International Mobile', '901', 'International Mobile, shared code');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1053', '90105', 'Thuraya RMSS Network', '901', 'International Mobile, shared code');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1054', '90106', 'Thuraya Satellite Telecommunications Company', '901', 'International Mobile, shared code');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1055', '90107', 'Ellipso International Mobile', '901', 'International Mobile, shared code');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1056', '90108', 'GSM International Mobile', '901', 'International Mobile, shared code');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1057', '90109', 'Tele1 Europe', '901', 'International Mobile, shared code');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1058', '90110', 'Asia Cellular Satellite (AceS)', '901', 'International Mobile, shared code');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1059', '90111', 'Inmarsat Ltd.', '901', 'International Mobile, shared code');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1060', '90112', 'Maritime Communications Partner AS (MCP network)', '901', 'International Mobile, shared code');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1061', '90113', 'Global Networks, Inc.', '901', 'International Mobile, shared code');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1062', '90114', 'Telenor GSM - services in aircraft', '901', 'International Mobile, shared code');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1063', '90115', 'SITA GSM services in aircraft', '901', 'International Mobile, shared code');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1064', '90116', 'Jasper Systems, Inc.', '901', 'International Mobile, shared code');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1065', '90117', 'Jersey Telecom', '901', 'International Mobile, shared code');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1066', '90118', 'Cingular Wireless', '901', 'International Mobile, shared code');
INSERT INTO `razor_dim_devicesupplier` VALUES ('1067', '90119', 'Vodaphone Malta', '901', 'International Mobile, shared code');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_dim_errortitle
-- ----------------------------
INSERT INTO `razor_dim_errortitle` VALUES ('1', 'java.lang.ArithmeticException: divide by zero\n	at com.wbtech.test_sample.CobubSampleActivity$2.onClick(CobubSampleActivity.java:154)\n	at android.view.View.performClick(View.java:4424)', '0');

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_dim_event
-- ----------------------------
INSERT INTO `razor_dim_event` VALUES ('1', 'login', 'login', '1', '1', '2015-12-15 10:20:22', '1');
INSERT INTO `razor_dim_event` VALUES ('2', 'click', 'click', '1', '1', '2015-12-15 10:20:57', '2');
INSERT INTO `razor_dim_event` VALUES ('3', 'quit', 'quit', '1', '1', '2015-12-15 10:21:13', '3');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_dim_location
-- ----------------------------
INSERT INTO `razor_dim_location` VALUES ('1', '局域网', '局域网', 'unknown');

-- ----------------------------
-- Table structure for razor_dim_network
-- ----------------------------
DROP TABLE IF EXISTS `razor_dim_network`;
CREATE TABLE `razor_dim_network` (
  `network_sk` int(11) NOT NULL AUTO_INCREMENT,
  `networkname` varchar(256) NOT NULL,
  PRIMARY KEY (`network_sk`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_dim_network
-- ----------------------------
INSERT INTO `razor_dim_network` VALUES ('1', 'epc.tmobile.com');

-- ----------------------------
-- Table structure for razor_dim_product
-- ----------------------------
DROP TABLE IF EXISTS `razor_dim_product`;
CREATE TABLE `razor_dim_product` (
  `product_sk` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(256) NOT NULL,
  `product_type` varchar(128) NOT NULL,
  `product_active` tinyint(4) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `channel_name` varchar(256) NOT NULL,
  `channel_active` tinyint(4) NOT NULL,
  `product_key` varchar(256) DEFAULT NULL,
  `version_name` varchar(64) NOT NULL,
  `version_active` tinyint(4) NOT NULL,
  `userid` int(11) NOT NULL,
  `platform` varchar(128) NOT NULL,
  PRIMARY KEY (`product_sk`),
  UNIQUE KEY `product_id` (`product_id`,`channel_id`,`version_name`,`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_dim_product
-- ----------------------------
INSERT INTO `razor_dim_product` VALUES ('1', '1', 'Sample_android', '游戏', '1', '1', '安卓市场', '1', 'f0e1815b61767ffa9206d4dc1048b26e', '1.0', '1', '1', 'Android');

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_dim_segment_launch
-- ----------------------------
INSERT INTO `razor_dim_segment_launch` VALUES ('1', '1-2', '1', '2', '0000-00-00', '9999-12-31');
INSERT INTO `razor_dim_segment_launch` VALUES ('2', '3-5', '3', '5', '0000-00-00', '9999-12-31');
INSERT INTO `razor_dim_segment_launch` VALUES ('3', '6-9', '6', '9', '0000-00-00', '9999-12-31');
INSERT INTO `razor_dim_segment_launch` VALUES ('4', '10-19', '10', '19', '0000-00-00', '9999-12-31');
INSERT INTO `razor_dim_segment_launch` VALUES ('5', '20-49', '20', '49', '0000-00-00', '9999-12-31');
INSERT INTO `razor_dim_segment_launch` VALUES ('6', '50', '50', '2147483647', '0000-00-00', '9999-12-31');

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_dim_segment_usinglog
-- ----------------------------
INSERT INTO `razor_dim_segment_usinglog` VALUES ('1', '0-3', '0', '3000', '0000-00-00', '9999-12-31');
INSERT INTO `razor_dim_segment_usinglog` VALUES ('2', '3-10', '3000', '10000', '0000-00-00', '9999-12-31');
INSERT INTO `razor_dim_segment_usinglog` VALUES ('3', '10-30', '10000', '30000', '0000-00-00', '9999-12-31');
INSERT INTO `razor_dim_segment_usinglog` VALUES ('4', '30-60', '30000', '60000', '0000-00-00', '9999-12-31');
INSERT INTO `razor_dim_segment_usinglog` VALUES ('5', '1-3', '60000', '180000', '0000-00-00', '9999-12-31');
INSERT INTO `razor_dim_segment_usinglog` VALUES ('6', '3-10', '180000', '600000', '0000-00-00', '9999-12-31');
INSERT INTO `razor_dim_segment_usinglog` VALUES ('7', '10-30', '600000', '1800000', '0000-00-00', '9999-12-31');
INSERT INTO `razor_dim_segment_usinglog` VALUES ('8', '30', '1800000', '2147483647', '0000-00-00', '9999-12-31');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_fact_clientdata
-- ----------------------------
INSERT INTO `razor_fact_clientdata` VALUES ('1', '1', '1', '1', '1', '1', '295', '1', '2175', '9401a87f322c05f9c93272bc7ed69d10', '2', '1', '22', '1', '1', null);
INSERT INTO `razor_fact_clientdata` VALUES ('2', '1', '1', '1', '1', '1', '295', '1', '2176', '9401a87f322c05f9c93272bc7ed69d10', '3', '1', '0', '0', '0', null);

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_fact_errorlog
-- ----------------------------
INSERT INTO `razor_fact_errorlog` VALUES ('1', '2175', '1', '1', '1', '1', '.CobubSampleActivity', '2015-12-14 22:36:26', 'java.lang.ArithmeticException: divide by zero\n	at com.wbtech.test_sample.CobubSampleActivity$2.onClick(CobubSampleActivity.java:154)\n	at android.view.View.performClick(View.java:4424)', 'java.lang.ArithmeticException: divide by zero\n	at com.wbtech.test_sample.CobubSampleActivity$2.onClick(CobubSampleActivity.java:154)\n	at android.view.View.performClick(View.java:4424)\n	java.lang.ArithmeticException: divide by zero\n	at com.wbtech.test_sample.CobubSampleActivity$2.onClick(CobubSampleActivity.java:154)\n	at android.view.View.performClick(View.java:4424)\n	at android.view.View$PerformClick.run(View.java:18383)\n	at android.os.Handler.handleCallback(Handler.java:733)\n	at android.os.Handler.dispatchMessage(Handler.java:95)\n	at android.os.Looper.loop(Looper.java:137)\n	at android.app.ActivityThread.main(ActivityThread.java:4998)\n	at java.lang.reflect.Method.invokeNative(Native Method)\n	at java.lang.reflect.Method.invoke(Method.java:515)\n	at com.android.internal.os.ZygoteInit$MethodAndArgsCaller.run(ZygoteInit.java:777)\n	at com.android.internal.os.ZygoteInit.main(ZygoteInit.java:593)\n	at dalvik.system.NativeStart.main(Native Method)\n', '0', '1');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_fact_event
-- ----------------------------
INSERT INTO `razor_fact_event` VALUES ('1', '1', '1', '2176', null, null, '.CobubSampleActivity', '', null, '2015-12-15 00:50:59', '1');

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_fact_launch_daily
-- ----------------------------
INSERT INTO `razor_fact_launch_daily` VALUES ('1', '1', '2176', '1', '1');
INSERT INTO `razor_fact_launch_daily` VALUES ('2', '1', '2176', '2', '0');
INSERT INTO `razor_fact_launch_daily` VALUES ('3', '1', '2176', '3', '0');
INSERT INTO `razor_fact_launch_daily` VALUES ('4', '1', '2176', '4', '0');
INSERT INTO `razor_fact_launch_daily` VALUES ('5', '1', '2176', '5', '0');
INSERT INTO `razor_fact_launch_daily` VALUES ('6', '1', '2176', '6', '0');

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_fact_usinglog
-- ----------------------------
INSERT INTO `razor_fact_usinglog` VALUES ('1', '1', '2175', '1', '184a81e8be4d685aaa509d139f45a62b', '68', '.CobubSampleActivity', '2015-12-14 22:36:25', '2015-12-14 22:36:25', '1');
INSERT INTO `razor_fact_usinglog` VALUES ('2', '1', '2175', '1', '184a81e8be4d685aaa509d139f45a62b', '309053', '.CobubSampleActivity', '2015-12-14 22:31:14', '2015-12-14 22:36:24', '2');
INSERT INTO `razor_fact_usinglog` VALUES ('3', '1', '2175', '1', '184a81e8be4d685aaa509d139f45a62b', '309053', '.CobubSampleActivity', '2015-12-14 22:31:14', '2015-12-14 22:36:24', '3');
INSERT INTO `razor_fact_usinglog` VALUES ('4', '1', '2176', '1', '06a9f0c1f9567105b962089256d1f8f4', '107', '.CobubSampleActivity', '2015-12-15 00:50:57', '2015-12-15 00:50:57', '4');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_fact_usinglog_daily
-- ----------------------------
INSERT INTO `razor_fact_usinglog_daily` VALUES ('1', '1', '2176', '06a9f0c1f9567105b962089256d1f8f4', '1', '107');

-- ----------------------------
-- Table structure for razor_hour24
-- ----------------------------
DROP TABLE IF EXISTS `razor_hour24`;
CREATE TABLE `razor_hour24` (
  `hour` tinyint(11) NOT NULL,
  PRIMARY KEY (`hour`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_hour24
-- ----------------------------
INSERT INTO `razor_hour24` VALUES ('0');
INSERT INTO `razor_hour24` VALUES ('1');
INSERT INTO `razor_hour24` VALUES ('2');
INSERT INTO `razor_hour24` VALUES ('3');
INSERT INTO `razor_hour24` VALUES ('4');
INSERT INTO `razor_hour24` VALUES ('5');
INSERT INTO `razor_hour24` VALUES ('6');
INSERT INTO `razor_hour24` VALUES ('7');
INSERT INTO `razor_hour24` VALUES ('8');
INSERT INTO `razor_hour24` VALUES ('9');
INSERT INTO `razor_hour24` VALUES ('10');
INSERT INTO `razor_hour24` VALUES ('11');
INSERT INTO `razor_hour24` VALUES ('12');
INSERT INTO `razor_hour24` VALUES ('13');
INSERT INTO `razor_hour24` VALUES ('14');
INSERT INTO `razor_hour24` VALUES ('15');
INSERT INTO `razor_hour24` VALUES ('16');
INSERT INTO `razor_hour24` VALUES ('17');
INSERT INTO `razor_hour24` VALUES ('18');
INSERT INTO `razor_hour24` VALUES ('19');
INSERT INTO `razor_hour24` VALUES ('20');
INSERT INTO `razor_hour24` VALUES ('21');
INSERT INTO `razor_hour24` VALUES ('22');
INSERT INTO `razor_hour24` VALUES ('23');

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
) ENGINE=InnoDB AUTO_INCREMENT=373 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_log
-- ----------------------------
INSERT INTO `razor_log` VALUES ('1', 'rundim', '-----start rundim-----', '2015-12-15 10:30:22', null, null, null);
INSERT INTO `razor_log` VALUES ('2', 'rundim', 'razor_dim_location', '2015-12-15 10:30:22', '2015-12-15 10:30:22', '0', '0');
INSERT INTO `razor_log` VALUES ('3', 'rundim', 'razor_dim_deviceos', '2015-12-15 10:30:22', '2015-12-15 10:30:22', '0', '0');
INSERT INTO `razor_log` VALUES ('4', 'rundim', 'razor_dim_devicelanguage', '2015-12-15 10:30:22', '2015-12-15 10:30:22', '0', '0');
INSERT INTO `razor_log` VALUES ('5', 'rundim', 'razor_dim_deviceresolution', '2015-12-15 10:30:22', '2015-12-15 10:30:22', '0', '0');
INSERT INTO `razor_log` VALUES ('6', 'rundim', 'razor_dim_devicesupplier', '2015-12-15 10:30:22', '2015-12-15 10:30:22', '0', '0');
INSERT INTO `razor_log` VALUES ('7', 'rundim', 'razor_dim_product', '2015-12-15 10:30:22', '2015-12-15 10:30:22', '0', '0');
INSERT INTO `razor_log` VALUES ('8', 'rundim', 'razor_dim_network', '2015-12-15 10:30:22', '2015-12-15 10:30:22', '0', '0');
INSERT INTO `razor_log` VALUES ('9', 'rundim', 'razor_dim_activity', '2015-12-15 10:30:22', '2015-12-15 10:30:22', '0', '0');
INSERT INTO `razor_log` VALUES ('10', 'rundim', 'razor_dim_errortitle', '2015-12-15 10:30:22', '2015-12-15 10:30:22', '0', '0');
INSERT INTO `razor_log` VALUES ('11', 'rundim', 'razor_dim_event', '2015-12-15 10:30:22', '2015-12-15 10:30:22', '3', '0');
INSERT INTO `razor_log` VALUES ('12', 'rundim', '-----finish rundim-----', '2015-12-15 10:30:22', null, null, null);
INSERT INTO `razor_log` VALUES ('13', 'runfact', '-----start  runfact-----', '2015-12-15 10:30:22', null, null, null);
INSERT INTO `razor_log` VALUES ('14', 'runfact', 'razor_fact_clientdata', '2015-12-15 10:30:22', '2015-12-15 10:30:22', '0', '0');
INSERT INTO `razor_log` VALUES ('15', 'runfact', 'razor_fact_usinglog', '2015-12-15 10:30:22', '2015-12-15 10:30:22', '0', '0');
INSERT INTO `razor_log` VALUES ('16', 'runfact', 'razor_fact_errorlog', '2015-12-15 10:30:22', '2015-12-15 10:30:22', '0', '0');
INSERT INTO `razor_log` VALUES ('17', 'runfact', 'razor_fact_event', '2015-12-15 10:30:22', '2015-12-15 10:30:22', '0', '0');
INSERT INTO `razor_log` VALUES ('18', 'runfact', '-----finish runfact-----', '2015-12-15 10:30:22', null, null, null);
INSERT INTO `razor_log` VALUES ('19', 'runsum', '-----start runsum-----', '2015-12-15 10:30:22', null, null, null);
INSERT INTO `razor_log` VALUES ('20', 'runsum', 'razor_fact_clientdata update', null, '2015-12-15 10:30:23', '0', '0');
INSERT INTO `razor_log` VALUES ('21', 'runsum', 'razor_fact_clientdata update', null, '2015-12-15 10:30:23', '0', '0');
INSERT INTO `razor_log` VALUES ('22', 'runsum', 'razor_fact_usinglog_daily', null, '2015-12-15 10:30:23', '0', '0');
INSERT INTO `razor_log` VALUES ('23', 'runsum', 'razor_sum_basic_product', null, '2015-12-15 10:30:23', '0', '0');
INSERT INTO `razor_log` VALUES ('24', 'runsum', 'razor_sum_basic_channel', null, '2015-12-15 10:30:23', '0', '0');
INSERT INTO `razor_log` VALUES ('25', 'runsum', 'razor_sum_basic_product_version', null, '2015-12-15 10:30:23', '0', '0');
INSERT INTO `razor_log` VALUES ('26', 'runsum', 'razor_fact_usinglog_daily update', null, '2015-12-15 10:30:23', '0', '0');
INSERT INTO `razor_log` VALUES ('27', 'runsum', 'razor_sum_basic_byhour', null, '2015-12-15 10:30:23', '0', '0');
INSERT INTO `razor_log` VALUES ('28', 'runsum', 'razor_sum_usinglog_activity', null, '2015-12-15 10:30:23', '0', '0');
INSERT INTO `razor_log` VALUES ('29', 'runsum', 'razor_fact_launch_daily', null, '2015-12-15 10:30:23', '0', '0');
INSERT INTO `razor_log` VALUES ('30', 'runsum', 'razor_sum_location', null, '2015-12-15 10:30:23', '0', '0');
INSERT INTO `razor_log` VALUES ('31', 'runsum', 'razor_sum_devicebrand', null, '2015-12-15 10:30:23', '0', '0');
INSERT INTO `razor_log` VALUES ('32', 'runsum', 'razor_sum_deviceos', null, '2015-12-15 10:30:23', '0', '0');
INSERT INTO `razor_log` VALUES ('33', 'runsum', 'razor_sum_deviceresolution', null, '2015-12-15 10:30:23', '0', '0');
INSERT INTO `razor_log` VALUES ('34', 'runsum', 'razor_sum_devicesupplier', null, '2015-12-15 10:30:23', '0', '0');
INSERT INTO `razor_log` VALUES ('35', 'runsum', 'razor_sum_devicenetwork', null, '2015-12-15 10:30:23', '0', '0');
INSERT INTO `razor_log` VALUES ('36', 'runsum', 'razor_sum_event', null, '2015-12-15 10:30:23', '0', '0');
INSERT INTO `razor_log` VALUES ('37', 'runsum', '-----finish runsum-----', '2015-12-15 10:30:23', null, null, null);
INSERT INTO `razor_log` VALUES ('38', 'rundim', '-----start rundim-----', '2015-12-15 11:38:43', null, null, null);
INSERT INTO `razor_log` VALUES ('39', 'rundim', 'razor_dim_location', '2015-12-15 11:38:43', '2015-12-15 11:38:43', '1', '0');
INSERT INTO `razor_log` VALUES ('40', 'rundim', 'razor_dim_deviceos', '2015-12-15 11:38:43', '2015-12-15 11:38:43', '1', '0');
INSERT INTO `razor_log` VALUES ('41', 'rundim', 'razor_dim_devicelanguage', '2015-12-15 11:38:43', '2015-12-15 11:38:43', '1', '0');
INSERT INTO `razor_log` VALUES ('42', 'rundim', 'razor_dim_deviceresolution', '2015-12-15 11:38:43', '2015-12-15 11:38:43', '1', '0');
INSERT INTO `razor_log` VALUES ('43', 'rundim', 'razor_dim_devicesupplier', '2015-12-15 11:38:43', '2015-12-15 11:38:43', '0', '0');
INSERT INTO `razor_log` VALUES ('44', 'rundim', 'razor_dim_product', '2015-12-15 11:38:43', '2015-12-15 11:38:43', '1', '0');
INSERT INTO `razor_log` VALUES ('45', 'rundim', 'razor_dim_network', '2015-12-15 11:38:43', '2015-12-15 11:38:43', '1', '0');
INSERT INTO `razor_log` VALUES ('46', 'rundim', 'razor_dim_activity', '2015-12-15 11:38:43', '2015-12-15 11:38:43', '0', '0');
INSERT INTO `razor_log` VALUES ('47', 'rundim', 'razor_dim_errortitle', '2015-12-15 11:38:43', '2015-12-15 11:38:43', '0', '0');
INSERT INTO `razor_log` VALUES ('48', 'rundim', 'razor_dim_event', '2015-12-15 11:38:43', '2015-12-15 11:38:43', '0', '0');
INSERT INTO `razor_log` VALUES ('49', 'rundim', '-----finish rundim-----', '2015-12-15 11:38:43', null, null, null);
INSERT INTO `razor_log` VALUES ('50', 'runfact', '-----start  runfact-----', '2015-12-15 11:38:43', null, null, null);
INSERT INTO `razor_log` VALUES ('51', 'runfact', 'razor_fact_clientdata', '2015-12-15 11:38:43', '2015-12-15 11:38:43', '0', '0');
INSERT INTO `razor_log` VALUES ('52', 'runfact', 'razor_fact_usinglog', '2015-12-15 11:38:43', '2015-12-15 11:38:43', '0', '0');
INSERT INTO `razor_log` VALUES ('53', 'runfact', 'razor_fact_errorlog', '2015-12-15 11:38:43', '2015-12-15 11:38:43', '0', '0');
INSERT INTO `razor_log` VALUES ('54', 'runfact', 'razor_fact_event', '2015-12-15 11:38:43', '2015-12-15 11:38:43', '0', '0');
INSERT INTO `razor_log` VALUES ('55', 'runfact', '-----finish runfact-----', '2015-12-15 11:38:43', null, null, null);
INSERT INTO `razor_log` VALUES ('56', 'runsum', '-----start runsum-----', '2015-12-15 11:38:43', null, null, null);
INSERT INTO `razor_log` VALUES ('57', 'runsum', 'razor_fact_clientdata update', null, '2015-12-15 11:38:43', '0', '0');
INSERT INTO `razor_log` VALUES ('58', 'runsum', 'razor_fact_clientdata update', null, '2015-12-15 11:38:43', '0', '0');
INSERT INTO `razor_log` VALUES ('59', 'runsum', 'razor_fact_usinglog_daily', null, '2015-12-15 11:38:43', '0', '0');
INSERT INTO `razor_log` VALUES ('60', 'runsum', 'razor_sum_basic_product', null, '2015-12-15 11:38:43', '0', '0');
INSERT INTO `razor_log` VALUES ('61', 'runsum', 'razor_sum_basic_channel', null, '2015-12-15 11:38:44', '0', '0');
INSERT INTO `razor_log` VALUES ('62', 'runsum', 'razor_sum_basic_product_version', null, '2015-12-15 11:38:44', '0', '0');
INSERT INTO `razor_log` VALUES ('63', 'runsum', 'razor_fact_usinglog_daily update', null, '2015-12-15 11:38:44', '0', '0');
INSERT INTO `razor_log` VALUES ('64', 'runsum', 'razor_sum_basic_byhour', null, '2015-12-15 11:38:44', '0', '0');
INSERT INTO `razor_log` VALUES ('65', 'runsum', 'razor_sum_usinglog_activity', null, '2015-12-15 11:38:44', '0', '0');
INSERT INTO `razor_log` VALUES ('66', 'runsum', 'razor_fact_launch_daily', null, '2015-12-15 11:38:44', '0', '0');
INSERT INTO `razor_log` VALUES ('67', 'runsum', 'razor_sum_location', null, '2015-12-15 11:38:44', '0', '0');
INSERT INTO `razor_log` VALUES ('68', 'runsum', 'razor_sum_devicebrand', null, '2015-12-15 11:38:44', '0', '0');
INSERT INTO `razor_log` VALUES ('69', 'runsum', 'razor_sum_deviceos', null, '2015-12-15 11:38:44', '0', '0');
INSERT INTO `razor_log` VALUES ('70', 'runsum', 'razor_sum_deviceresolution', null, '2015-12-15 11:38:44', '0', '0');
INSERT INTO `razor_log` VALUES ('71', 'runsum', 'razor_sum_devicesupplier', null, '2015-12-15 11:38:44', '0', '0');
INSERT INTO `razor_log` VALUES ('72', 'runsum', 'razor_sum_devicenetwork', null, '2015-12-15 11:38:44', '0', '0');
INSERT INTO `razor_log` VALUES ('73', 'runsum', 'razor_sum_event', null, '2015-12-15 11:38:44', '0', '0');
INSERT INTO `razor_log` VALUES ('74', 'runsum', '-----finish runsum-----', '2015-12-15 11:38:44', null, null, null);
INSERT INTO `razor_log` VALUES ('75', 'rundaily', '-----start rundaily-----', '2015-12-15 11:43:57', null, null, null);
INSERT INTO `razor_log` VALUES ('76', 'rundaily', 'razor_sum_accesspath', '2015-12-15 11:43:57', '2015-12-15 11:43:57', '1', '0');
INSERT INTO `razor_log` VALUES ('77', 'rundaily', 'razor_sum_reserveusers_daily new users for app,version,channel dimensions', '2015-12-15 11:43:57', '2015-12-15 11:43:57', '0', '0');
INSERT INTO `razor_log` VALUES ('78', 'rundaily', 'razor_sum_reserveusers_daily DAY -1 reserve users for app,channel,version dimensions', '2015-12-15 11:43:57', '2015-12-15 11:43:57', '0', '0');
INSERT INTO `razor_log` VALUES ('79', 'rundaily', 'razor_sum_reserveusers_daily DAY -2 reserve users for app,channel,version dimensions', '2015-12-15 11:43:58', '2015-12-15 11:43:58', '0', '0');
INSERT INTO `razor_log` VALUES ('80', 'rundaily', 'razor_sum_reserveusers_daily DAY -3 reserve users for app,channel,version dimensions', '2015-12-15 11:43:58', '2015-12-15 11:43:58', '0', '0');
INSERT INTO `razor_log` VALUES ('81', 'rundaily', 'razor_sum_reserveusers_daily DAY -4 reserve users for app,channel,version dimensions', '2015-12-15 11:43:58', '2015-12-15 11:43:58', '0', '0');
INSERT INTO `razor_log` VALUES ('82', 'rundaily', 'razor_sum_reserveusers_daily DAY -5 reserve users for app,channel,version dimensions', '2015-12-15 11:43:58', '2015-12-15 11:43:58', '0', '0');
INSERT INTO `razor_log` VALUES ('83', 'rundaily', 'razor_sum_reserveusers_daily DAY -6 reserve users for app,channel,version dimensions', '2015-12-15 11:43:58', '2015-12-15 11:43:58', '0', '0');
INSERT INTO `razor_log` VALUES ('84', 'rundaily', 'razor_sum_reserveusers_daily DAY -7 reserve users for app,channel,version dimensions', '2015-12-15 11:43:58', '2015-12-15 11:43:58', '0', '0');
INSERT INTO `razor_log` VALUES ('85', 'rundaily', 'razor_sum_reserveusers_daily DAY -8 reserve users for app,channel,version dimensions', '2015-12-15 11:43:58', '2015-12-15 11:43:58', '0', '0');
INSERT INTO `razor_log` VALUES ('86', 'rundaily', 'razor_sum_accesslevel', '2015-12-15 11:43:58', '2015-12-15 11:43:58', '1', '0');
INSERT INTO `razor_log` VALUES ('87', 'rundaily', 'razor_fact_clientdata recalculate new users', '2015-12-15 11:43:58', '2015-12-15 11:43:58', '0', '0');
INSERT INTO `razor_log` VALUES ('88', 'rundaily', 'razor_fact_clientdata recalculate new users for channel', '2015-12-15 11:43:58', '2015-12-15 11:43:58', '0', '0');
INSERT INTO `razor_log` VALUES ('89', 'rundaily', '-----finish rundaily-----', '2015-12-15 11:43:58', null, null, null);
INSERT INTO `razor_log` VALUES ('90', 'runsum', '-----start runsum-----', '2015-12-15 13:39:33', null, null, null);
INSERT INTO `razor_log` VALUES ('91', 'runsum', 'razor_fact_clientdata update', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('92', 'runsum', 'razor_fact_clientdata update', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('93', 'runsum', 'razor_fact_usinglog_daily', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('94', 'runsum', 'razor_sum_basic_product', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('95', 'runsum', 'razor_sum_basic_channel', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('96', 'runsum', 'razor_sum_basic_product_version', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('97', 'runsum', 'razor_fact_usinglog_daily update', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('98', 'runsum', 'razor_sum_basic_byhour', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('99', 'runsum', 'razor_sum_usinglog_activity', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('100', 'runsum', 'razor_fact_launch_daily', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('101', 'runsum', 'razor_sum_location', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('102', 'runsum', 'razor_sum_devicebrand', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('103', 'runsum', 'razor_sum_deviceos', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('104', 'runsum', 'razor_sum_deviceresolution', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('105', 'runsum', 'razor_sum_devicesupplier', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('106', 'runsum', 'razor_sum_devicenetwork', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('107', 'runsum', 'razor_sum_event', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('108', 'runsum', '-----finish runsum-----', '2015-12-15 13:39:33', null, null, null);
INSERT INTO `razor_log` VALUES ('109', 'runsum', '-----start runsum-----', '2015-12-15 13:39:33', null, null, null);
INSERT INTO `razor_log` VALUES ('110', 'runsum', 'razor_fact_clientdata update', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('111', 'runsum', 'razor_fact_clientdata update', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('112', 'runsum', 'razor_fact_usinglog_daily', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('113', 'runsum', 'razor_sum_basic_product', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('114', 'runsum', 'razor_sum_basic_channel', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('115', 'runsum', 'razor_sum_basic_product_version', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('116', 'runsum', 'razor_fact_usinglog_daily update', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('117', 'runsum', 'razor_sum_basic_byhour', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('118', 'runsum', 'razor_sum_usinglog_activity', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('119', 'runsum', 'razor_fact_launch_daily', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('120', 'runsum', 'razor_sum_location', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('121', 'runsum', 'razor_sum_devicebrand', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('122', 'runsum', 'razor_sum_deviceos', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('123', 'runsum', 'razor_sum_deviceresolution', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('124', 'runsum', 'razor_sum_devicesupplier', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('125', 'runsum', 'razor_sum_devicenetwork', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('126', 'runsum', 'razor_sum_event', null, '2015-12-15 13:39:33', '0', '0');
INSERT INTO `razor_log` VALUES ('127', 'runsum', '-----finish runsum-----', '2015-12-15 13:39:33', null, null, null);
INSERT INTO `razor_log` VALUES ('128', 'runsum', '-----start runsum-----', '2015-12-15 13:39:34', null, null, null);
INSERT INTO `razor_log` VALUES ('129', 'runsum', 'razor_fact_clientdata update', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('130', 'runsum', 'razor_fact_clientdata update', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('131', 'runsum', 'razor_fact_usinglog_daily', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('132', 'runsum', 'razor_sum_basic_product', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('133', 'runsum', 'razor_sum_basic_channel', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('134', 'runsum', 'razor_sum_basic_product_version', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('135', 'runsum', 'razor_fact_usinglog_daily update', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('136', 'runsum', 'razor_sum_basic_byhour', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('137', 'runsum', 'razor_sum_usinglog_activity', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('138', 'runsum', 'razor_fact_launch_daily', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('139', 'runsum', 'razor_sum_location', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('140', 'runsum', 'razor_sum_devicebrand', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('141', 'runsum', 'razor_sum_deviceos', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('142', 'runsum', 'razor_sum_deviceresolution', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('143', 'runsum', 'razor_sum_devicesupplier', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('144', 'runsum', 'razor_sum_devicenetwork', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('145', 'runsum', 'razor_sum_event', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('146', 'runsum', '-----finish runsum-----', '2015-12-15 13:39:34', null, null, null);
INSERT INTO `razor_log` VALUES ('147', 'runsum', '-----start runsum-----', '2015-12-15 13:39:34', null, null, null);
INSERT INTO `razor_log` VALUES ('148', 'runsum', 'razor_fact_clientdata update', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('149', 'runsum', 'razor_fact_clientdata update', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('150', 'runsum', 'razor_fact_usinglog_daily', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('151', 'runsum', 'razor_sum_basic_product', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('152', 'runsum', 'razor_sum_basic_channel', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('153', 'runsum', 'razor_sum_basic_product_version', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('154', 'runsum', 'razor_fact_usinglog_daily update', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('155', 'runsum', 'razor_sum_basic_byhour', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('156', 'runsum', 'razor_sum_usinglog_activity', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('157', 'runsum', 'razor_fact_launch_daily', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('158', 'runsum', 'razor_sum_location', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('159', 'runsum', 'razor_sum_devicebrand', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('160', 'runsum', 'razor_sum_deviceos', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('161', 'runsum', 'razor_sum_deviceresolution', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('162', 'runsum', 'razor_sum_devicesupplier', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('163', 'runsum', 'razor_sum_devicenetwork', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('164', 'runsum', 'razor_sum_event', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('165', 'runsum', '-----finish runsum-----', '2015-12-15 13:39:34', null, null, null);
INSERT INTO `razor_log` VALUES ('166', 'runsum', '-----start runsum-----', '2015-12-15 13:39:34', null, null, null);
INSERT INTO `razor_log` VALUES ('167', 'runsum', 'razor_fact_clientdata update', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('168', 'runsum', 'razor_fact_clientdata update', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('169', 'runsum', 'razor_fact_usinglog_daily', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('170', 'runsum', 'razor_sum_basic_product', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('171', 'runsum', 'razor_sum_basic_channel', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('172', 'runsum', 'razor_sum_basic_product_version', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('173', 'runsum', 'razor_fact_usinglog_daily update', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('174', 'runsum', 'razor_sum_basic_byhour', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('175', 'runsum', 'razor_sum_usinglog_activity', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('176', 'runsum', 'razor_fact_launch_daily', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('177', 'runsum', 'razor_sum_location', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('178', 'runsum', 'razor_sum_devicebrand', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('179', 'runsum', 'razor_sum_deviceos', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('180', 'runsum', 'razor_sum_deviceresolution', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('181', 'runsum', 'razor_sum_devicesupplier', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('182', 'runsum', 'razor_sum_devicenetwork', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('183', 'runsum', 'razor_sum_event', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('184', 'runsum', '-----finish runsum-----', '2015-12-15 13:39:34', null, null, null);
INSERT INTO `razor_log` VALUES ('185', 'runsum', '-----start runsum-----', '2015-12-15 13:39:34', null, null, null);
INSERT INTO `razor_log` VALUES ('186', 'runsum', 'razor_fact_clientdata update', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('187', 'runsum', 'razor_fact_clientdata update', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('188', 'runsum', 'razor_fact_usinglog_daily', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('189', 'runsum', 'razor_sum_basic_product', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('190', 'runsum', 'razor_sum_basic_channel', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('191', 'runsum', 'razor_sum_basic_product_version', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('192', 'runsum', 'razor_fact_usinglog_daily update', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('193', 'runsum', 'razor_sum_basic_byhour', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('194', 'runsum', 'razor_sum_usinglog_activity', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('195', 'runsum', 'razor_fact_launch_daily', null, '2015-12-15 13:39:34', '0', '0');
INSERT INTO `razor_log` VALUES ('196', 'runsum', 'razor_sum_location', null, '2015-12-15 13:39:35', '0', '0');
INSERT INTO `razor_log` VALUES ('197', 'runsum', 'razor_sum_devicebrand', null, '2015-12-15 13:39:35', '0', '0');
INSERT INTO `razor_log` VALUES ('198', 'runsum', 'razor_sum_deviceos', null, '2015-12-15 13:39:35', '0', '0');
INSERT INTO `razor_log` VALUES ('199', 'runsum', 'razor_sum_deviceresolution', null, '2015-12-15 13:39:35', '0', '0');
INSERT INTO `razor_log` VALUES ('200', 'runsum', 'razor_sum_devicesupplier', null, '2015-12-15 13:39:35', '0', '0');
INSERT INTO `razor_log` VALUES ('201', 'runsum', 'razor_sum_devicenetwork', null, '2015-12-15 13:39:35', '0', '0');
INSERT INTO `razor_log` VALUES ('202', 'runsum', 'razor_sum_event', null, '2015-12-15 13:39:35', '0', '0');
INSERT INTO `razor_log` VALUES ('203', 'runsum', '-----finish runsum-----', '2015-12-15 13:39:35', null, null, null);
INSERT INTO `razor_log` VALUES ('204', 'runsum', '-----start runsum-----', '2015-12-15 13:39:35', null, null, null);
INSERT INTO `razor_log` VALUES ('205', 'runsum', 'razor_fact_clientdata update', null, '2015-12-15 13:39:35', '0', '0');
INSERT INTO `razor_log` VALUES ('206', 'runsum', 'razor_fact_clientdata update', null, '2015-12-15 13:39:35', '0', '0');
INSERT INTO `razor_log` VALUES ('207', 'runsum', 'razor_fact_usinglog_daily', null, '2015-12-15 13:39:35', '0', '0');
INSERT INTO `razor_log` VALUES ('208', 'runsum', 'razor_sum_basic_product', null, '2015-12-15 13:39:35', '0', '0');
INSERT INTO `razor_log` VALUES ('209', 'runsum', 'razor_sum_basic_channel', null, '2015-12-15 13:39:35', '0', '0');
INSERT INTO `razor_log` VALUES ('210', 'runsum', 'razor_sum_basic_product_version', null, '2015-12-15 13:39:35', '0', '0');
INSERT INTO `razor_log` VALUES ('211', 'runsum', 'razor_fact_usinglog_daily update', null, '2015-12-15 13:39:35', '0', '0');
INSERT INTO `razor_log` VALUES ('212', 'runsum', 'razor_sum_basic_byhour', null, '2015-12-15 13:39:35', '0', '0');
INSERT INTO `razor_log` VALUES ('213', 'runsum', 'razor_sum_usinglog_activity', null, '2015-12-15 13:39:35', '0', '0');
INSERT INTO `razor_log` VALUES ('214', 'runsum', 'razor_fact_launch_daily', null, '2015-12-15 13:39:35', '0', '0');
INSERT INTO `razor_log` VALUES ('215', 'runsum', 'razor_sum_location', null, '2015-12-15 13:39:35', '0', '0');
INSERT INTO `razor_log` VALUES ('216', 'runsum', 'razor_sum_devicebrand', null, '2015-12-15 13:39:35', '0', '0');
INSERT INTO `razor_log` VALUES ('217', 'runsum', 'razor_sum_deviceos', null, '2015-12-15 13:39:35', '0', '0');
INSERT INTO `razor_log` VALUES ('218', 'runsum', 'razor_sum_deviceresolution', null, '2015-12-15 13:39:35', '0', '0');
INSERT INTO `razor_log` VALUES ('219', 'runsum', 'razor_sum_devicesupplier', null, '2015-12-15 13:39:35', '0', '0');
INSERT INTO `razor_log` VALUES ('220', 'runsum', 'razor_sum_devicenetwork', null, '2015-12-15 13:39:35', '0', '0');
INSERT INTO `razor_log` VALUES ('221', 'runsum', 'razor_sum_event', null, '2015-12-15 13:39:35', '0', '0');
INSERT INTO `razor_log` VALUES ('222', 'runsum', '-----finish runsum-----', '2015-12-15 13:39:35', null, null, null);
INSERT INTO `razor_log` VALUES ('223', 'rundim', '-----start rundim-----', '2015-12-15 13:50:31', null, null, null);
INSERT INTO `razor_log` VALUES ('224', 'rundim', 'razor_dim_location', '2015-12-15 13:50:31', '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('225', 'rundim', 'razor_dim_deviceos', '2015-12-15 13:50:31', '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('226', 'rundim', 'razor_dim_devicelanguage', '2015-12-15 13:50:31', '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('227', 'rundim', 'razor_dim_deviceresolution', '2015-12-15 13:50:31', '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('228', 'rundim', 'razor_dim_devicesupplier', '2015-12-15 13:50:31', '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('229', 'rundim', 'razor_dim_product', '2015-12-15 13:50:31', '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('230', 'rundim', 'razor_dim_network', '2015-12-15 13:50:31', '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('231', 'rundim', 'razor_dim_activity', '2015-12-15 13:50:31', '2015-12-15 13:50:31', '1', '0');
INSERT INTO `razor_log` VALUES ('232', 'rundim', 'razor_dim_errortitle', '2015-12-15 13:50:31', '2015-12-15 13:50:31', '1', '0');
INSERT INTO `razor_log` VALUES ('233', 'rundim', 'razor_dim_event', '2015-12-15 13:50:31', '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('234', 'rundim', '-----finish rundim-----', '2015-12-15 13:50:31', null, null, null);
INSERT INTO `razor_log` VALUES ('235', 'runfact', '-----start  runfact-----', '2015-12-15 13:50:31', null, null, null);
INSERT INTO `razor_log` VALUES ('236', 'runfact', 'razor_fact_clientdata', '2015-12-15 13:50:31', '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('237', 'runfact', 'razor_fact_usinglog', '2015-12-15 13:50:31', '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('238', 'runfact', 'razor_fact_errorlog', '2015-12-15 13:50:31', '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('239', 'runfact', 'razor_fact_event', '2015-12-15 13:50:31', '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('240', 'runfact', '-----finish runfact-----', '2015-12-15 13:50:31', null, null, null);
INSERT INTO `razor_log` VALUES ('241', 'runsum', '-----start runsum-----', '2015-12-15 13:50:31', null, null, null);
INSERT INTO `razor_log` VALUES ('242', 'runsum', 'razor_fact_clientdata update', null, '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('243', 'runsum', 'razor_fact_clientdata update', null, '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('244', 'runsum', 'razor_fact_usinglog_daily', null, '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('245', 'runsum', 'razor_sum_basic_product', null, '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('246', 'runsum', 'razor_sum_basic_channel', null, '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('247', 'runsum', 'razor_sum_basic_product_version', null, '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('248', 'runsum', 'razor_fact_usinglog_daily update', null, '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('249', 'runsum', 'razor_sum_basic_byhour', null, '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('250', 'runsum', 'razor_sum_usinglog_activity', null, '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('251', 'runsum', 'razor_fact_launch_daily', null, '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('252', 'runsum', 'razor_sum_location', null, '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('253', 'runsum', 'razor_sum_devicebrand', null, '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('254', 'runsum', 'razor_sum_deviceos', null, '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('255', 'runsum', 'razor_sum_deviceresolution', null, '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('256', 'runsum', 'razor_sum_devicesupplier', null, '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('257', 'runsum', 'razor_sum_devicenetwork', null, '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('258', 'runsum', 'razor_sum_event', null, '2015-12-15 13:50:31', '0', '0');
INSERT INTO `razor_log` VALUES ('259', 'runsum', '-----finish runsum-----', '2015-12-15 13:50:31', null, null, null);
INSERT INTO `razor_log` VALUES ('260', 'rundim', '-----start rundim-----', '2015-12-15 13:53:01', null, null, null);
INSERT INTO `razor_log` VALUES ('261', 'rundim', 'razor_dim_location', '2015-12-15 13:53:01', '2015-12-15 13:53:01', '0', '0');
INSERT INTO `razor_log` VALUES ('262', 'rundim', 'razor_dim_deviceos', '2015-12-15 13:53:01', '2015-12-15 13:53:01', '0', '0');
INSERT INTO `razor_log` VALUES ('263', 'rundim', 'razor_dim_devicelanguage', '2015-12-15 13:53:01', '2015-12-15 13:53:01', '0', '0');
INSERT INTO `razor_log` VALUES ('264', 'rundim', 'razor_dim_deviceresolution', '2015-12-15 13:53:01', '2015-12-15 13:53:01', '0', '0');
INSERT INTO `razor_log` VALUES ('265', 'rundim', 'razor_dim_devicesupplier', '2015-12-15 13:53:01', '2015-12-15 13:53:01', '0', '0');
INSERT INTO `razor_log` VALUES ('266', 'rundim', 'razor_dim_product', '2015-12-15 13:53:01', '2015-12-15 13:53:01', '0', '0');
INSERT INTO `razor_log` VALUES ('267', 'rundim', 'razor_dim_network', '2015-12-15 13:53:01', '2015-12-15 13:53:01', '0', '0');
INSERT INTO `razor_log` VALUES ('268', 'rundim', 'razor_dim_activity', '2015-12-15 13:53:01', '2015-12-15 13:53:01', '0', '0');
INSERT INTO `razor_log` VALUES ('269', 'rundim', 'razor_dim_errortitle', '2015-12-15 13:53:01', '2015-12-15 13:53:01', '0', '0');
INSERT INTO `razor_log` VALUES ('270', 'rundim', 'razor_dim_event', '2015-12-15 13:53:01', '2015-12-15 13:53:01', '0', '0');
INSERT INTO `razor_log` VALUES ('271', 'rundim', '-----finish rundim-----', '2015-12-15 13:53:01', null, null, null);
INSERT INTO `razor_log` VALUES ('272', 'runfact', '-----start  runfact-----', '2015-12-15 13:53:01', null, null, null);
INSERT INTO `razor_log` VALUES ('273', 'runfact', 'razor_fact_clientdata', '2015-12-15 13:53:02', '2015-12-15 13:53:02', '0', '0');
INSERT INTO `razor_log` VALUES ('274', 'runfact', 'razor_fact_usinglog', '2015-12-15 13:53:02', '2015-12-15 13:53:02', '0', '0');
INSERT INTO `razor_log` VALUES ('275', 'runfact', 'razor_fact_errorlog', '2015-12-15 13:53:02', '2015-12-15 13:53:02', '0', '0');
INSERT INTO `razor_log` VALUES ('276', 'runfact', 'razor_fact_event', '2015-12-15 13:53:02', '2015-12-15 13:53:02', '0', '0');
INSERT INTO `razor_log` VALUES ('277', 'runfact', '-----finish runfact-----', '2015-12-15 13:53:02', null, null, null);
INSERT INTO `razor_log` VALUES ('278', 'runsum', '-----start runsum-----', '2015-12-15 13:53:02', null, null, null);
INSERT INTO `razor_log` VALUES ('279', 'runsum', 'razor_fact_clientdata update', null, '2015-12-15 13:53:02', '0', '0');
INSERT INTO `razor_log` VALUES ('280', 'runsum', 'razor_fact_clientdata update', null, '2015-12-15 13:53:02', '0', '0');
INSERT INTO `razor_log` VALUES ('281', 'runsum', 'razor_fact_usinglog_daily', null, '2015-12-15 13:53:02', '0', '0');
INSERT INTO `razor_log` VALUES ('282', 'runsum', 'razor_sum_basic_product', null, '2015-12-15 13:53:02', '0', '0');
INSERT INTO `razor_log` VALUES ('283', 'runsum', 'razor_sum_basic_channel', null, '2015-12-15 13:53:02', '0', '0');
INSERT INTO `razor_log` VALUES ('284', 'runsum', 'razor_sum_basic_product_version', null, '2015-12-15 13:53:02', '0', '0');
INSERT INTO `razor_log` VALUES ('285', 'runsum', 'razor_fact_usinglog_daily update', null, '2015-12-15 13:53:02', '0', '0');
INSERT INTO `razor_log` VALUES ('286', 'runsum', 'razor_sum_basic_byhour', null, '2015-12-15 13:53:02', '0', '0');
INSERT INTO `razor_log` VALUES ('287', 'runsum', 'razor_sum_usinglog_activity', null, '2015-12-15 13:53:02', '0', '0');
INSERT INTO `razor_log` VALUES ('288', 'runsum', 'razor_fact_launch_daily', null, '2015-12-15 13:53:02', '0', '0');
INSERT INTO `razor_log` VALUES ('289', 'runsum', 'razor_sum_location', null, '2015-12-15 13:53:02', '0', '0');
INSERT INTO `razor_log` VALUES ('290', 'runsum', 'razor_sum_devicebrand', null, '2015-12-15 13:53:02', '0', '0');
INSERT INTO `razor_log` VALUES ('291', 'runsum', 'razor_sum_deviceos', null, '2015-12-15 13:53:02', '0', '0');
INSERT INTO `razor_log` VALUES ('292', 'runsum', 'razor_sum_deviceresolution', null, '2015-12-15 13:53:02', '0', '0');
INSERT INTO `razor_log` VALUES ('293', 'runsum', 'razor_sum_devicesupplier', null, '2015-12-15 13:53:02', '0', '0');
INSERT INTO `razor_log` VALUES ('294', 'runsum', 'razor_sum_devicenetwork', null, '2015-12-15 13:53:02', '0', '0');
INSERT INTO `razor_log` VALUES ('295', 'runsum', 'razor_sum_event', null, '2015-12-15 13:53:02', '0', '0');
INSERT INTO `razor_log` VALUES ('296', 'runsum', '-----finish runsum-----', '2015-12-15 13:53:02', null, null, null);
INSERT INTO `razor_log` VALUES ('297', 'runweekly', '-----start runweekly-----', '2015-12-15 14:34:25', null, null, null);
INSERT INTO `razor_log` VALUES ('298', 'runweekly', 'razor_sum_reserveusers_weekly new users for app,version,channel dimensions', '2015-12-15 14:34:25', '2015-12-15 14:34:25', '0', '0');
INSERT INTO `razor_log` VALUES ('299', 'runweekly', 'razor_sum_reserveusers_weekly WEEK -1 reserve users for app,channel,version dimensions', '2015-12-15 14:34:25', '2015-12-15 14:34:25', '0', '0');
INSERT INTO `razor_log` VALUES ('300', 'runweekly', 'razor_sum_reserveusers_weekly WEEK -2 reserve users for app,channel,version dimensions', '2015-12-15 14:34:25', '2015-12-15 14:34:25', '0', '0');
INSERT INTO `razor_log` VALUES ('301', 'runweekly', 'razor_sum_reserveusers_weekly WEEK -3 reserve users for app,channel,version dimensions', '2015-12-15 14:34:25', '2015-12-15 14:34:25', '0', '0');
INSERT INTO `razor_log` VALUES ('302', 'runweekly', 'razor_sum_reserveusers_weekly WEEK -4 reserve users for app,channel,version dimensions', '2015-12-15 14:34:25', '2015-12-15 14:34:25', '0', '0');
INSERT INTO `razor_log` VALUES ('303', 'runweekly', 'razor_sum_reserveusers_weekly WEEK -5 reserve users for app,channel,version dimensions', '2015-12-15 14:34:25', '2015-12-15 14:34:25', '0', '0');
INSERT INTO `razor_log` VALUES ('304', 'runweekly', 'razor_sum_reserveusers_weekly WEEK -6 reserve users for app,channel,version dimensions', '2015-12-15 14:34:25', '2015-12-15 14:34:25', '0', '0');
INSERT INTO `razor_log` VALUES ('305', 'runweekly', 'razor_sum_reserveusers_weekly WEEK -7 reserve users for app,channel,version dimensions', '2015-12-15 14:34:25', '2015-12-15 14:34:25', '0', '0');
INSERT INTO `razor_log` VALUES ('306', 'runweekly', 'razor_sum_reserveusers_weekly WEEK -8 reserve users for app,channel,version dimensions', '2015-12-15 14:34:25', '2015-12-15 14:34:25', '0', '0');
INSERT INTO `razor_log` VALUES ('307', 'runweekly', 'razor_sum_basic_activeusers week activeuser and percent', '2015-12-15 14:34:25', '2015-12-15 14:34:25', '0', '0');
INSERT INTO `razor_log` VALUES ('308', 'runweekly', 'razor_sum_basic_channel_activeusers each channel active user and percent', '2015-12-15 14:34:25', '2015-12-15 14:34:25', '0', '0');
INSERT INTO `razor_log` VALUES ('309', 'runweekly', '-----finish runweekly-----', '2015-12-15 14:34:25', null, null, null);
INSERT INTO `razor_log` VALUES ('310', 'rundim', '-----start rundim-----', '2015-12-15 14:39:12', null, null, null);
INSERT INTO `razor_log` VALUES ('311', 'rundim', 'razor_dim_location', '2015-12-15 14:39:12', '2015-12-15 14:39:12', '0', '0');
INSERT INTO `razor_log` VALUES ('312', 'rundim', 'razor_dim_deviceos', '2015-12-15 14:39:13', '2015-12-15 14:39:13', '0', '0');
INSERT INTO `razor_log` VALUES ('313', 'rundim', 'razor_dim_devicelanguage', '2015-12-15 14:39:13', '2015-12-15 14:39:13', '0', '0');
INSERT INTO `razor_log` VALUES ('314', 'rundim', 'razor_dim_deviceresolution', '2015-12-15 14:39:13', '2015-12-15 14:39:13', '0', '0');
INSERT INTO `razor_log` VALUES ('315', 'rundim', 'razor_dim_devicesupplier', '2015-12-15 14:39:13', '2015-12-15 14:39:13', '0', '0');
INSERT INTO `razor_log` VALUES ('316', 'rundim', 'razor_dim_product', '2015-12-15 14:39:13', '2015-12-15 14:39:13', '0', '0');
INSERT INTO `razor_log` VALUES ('317', 'rundim', 'razor_dim_network', '2015-12-15 14:39:13', '2015-12-15 14:39:13', '0', '0');
INSERT INTO `razor_log` VALUES ('318', 'rundim', 'razor_dim_activity', '2015-12-15 14:39:13', '2015-12-15 14:39:13', '0', '0');
INSERT INTO `razor_log` VALUES ('319', 'rundim', 'razor_dim_errortitle', '2015-12-15 14:39:13', '2015-12-15 14:39:13', '0', '0');
INSERT INTO `razor_log` VALUES ('320', 'rundim', 'razor_dim_event', '2015-12-15 14:39:13', '2015-12-15 14:39:13', '0', '0');
INSERT INTO `razor_log` VALUES ('321', 'rundim', '-----finish rundim-----', '2015-12-15 14:39:13', null, null, null);
INSERT INTO `razor_log` VALUES ('322', 'runfact', '-----start  runfact-----', '2015-12-15 14:39:13', null, null, null);
INSERT INTO `razor_log` VALUES ('323', 'runfact', 'razor_fact_clientdata', '2015-12-15 14:39:13', '2015-12-15 14:39:13', '2', '0');
INSERT INTO `razor_log` VALUES ('324', 'runfact', 'razor_fact_usinglog', '2015-12-15 14:39:13', '2015-12-15 14:39:13', '4', '0');
INSERT INTO `razor_log` VALUES ('325', 'runfact', 'razor_fact_errorlog', '2015-12-15 14:39:13', '2015-12-15 14:39:13', '1', '0');
INSERT INTO `razor_log` VALUES ('326', 'runfact', 'razor_fact_event', '2015-12-15 14:39:13', '2015-12-15 14:39:13', '1', '0');
INSERT INTO `razor_log` VALUES ('327', 'runfact', '-----finish runfact-----', '2015-12-15 14:39:13', null, null, null);
INSERT INTO `razor_log` VALUES ('328', 'runsum', '-----start runsum-----', '2015-12-15 14:39:13', null, null, null);
INSERT INTO `razor_log` VALUES ('329', 'runsum', 'razor_fact_clientdata update', null, '2015-12-15 14:39:13', '1', '0');
INSERT INTO `razor_log` VALUES ('330', 'runsum', 'razor_fact_clientdata update', null, '2015-12-15 14:39:13', '1', '0');
INSERT INTO `razor_log` VALUES ('331', 'runsum', 'razor_fact_usinglog_daily', null, '2015-12-15 14:39:13', '1', '0');
INSERT INTO `razor_log` VALUES ('332', 'runsum', 'razor_sum_basic_product', null, '2015-12-15 14:39:13', '2', '0');
INSERT INTO `razor_log` VALUES ('333', 'runsum', 'razor_sum_basic_channel', null, '2015-12-15 14:39:13', '2', '0');
INSERT INTO `razor_log` VALUES ('334', 'runsum', 'razor_sum_basic_product_version', null, '2015-12-15 14:39:14', '2', '1');
INSERT INTO `razor_log` VALUES ('335', 'runsum', 'razor_fact_usinglog_daily update', null, '2015-12-15 14:39:14', '1', '0');
INSERT INTO `razor_log` VALUES ('336', 'runsum', 'razor_sum_basic_byhour', null, '2015-12-15 14:39:14', '0', '0');
INSERT INTO `razor_log` VALUES ('337', 'runsum', 'razor_sum_usinglog_activity', null, '2015-12-15 14:39:14', '2', '0');
INSERT INTO `razor_log` VALUES ('338', 'runsum', 'razor_fact_launch_daily', null, '2015-12-15 14:39:14', '6', '0');
INSERT INTO `razor_log` VALUES ('339', 'runsum', 'razor_sum_location', null, '2015-12-15 14:39:14', '0', '0');
INSERT INTO `razor_log` VALUES ('340', 'runsum', 'razor_sum_devicebrand', null, '2015-12-15 14:39:14', '0', '0');
INSERT INTO `razor_log` VALUES ('341', 'runsum', 'razor_sum_deviceos', null, '2015-12-15 14:39:14', '0', '0');
INSERT INTO `razor_log` VALUES ('342', 'runsum', 'razor_sum_deviceresolution', null, '2015-12-15 14:39:14', '0', '0');
INSERT INTO `razor_log` VALUES ('343', 'runsum', 'razor_sum_devicesupplier', null, '2015-12-15 14:39:14', '0', '0');
INSERT INTO `razor_log` VALUES ('344', 'runsum', 'razor_sum_devicenetwork', null, '2015-12-15 14:39:14', '0', '0');
INSERT INTO `razor_log` VALUES ('345', 'runsum', 'razor_sum_event', null, '2015-12-15 14:39:14', '1', '0');
INSERT INTO `razor_log` VALUES ('346', 'runsum', '-----finish runsum-----', '2015-12-15 14:39:14', null, null, null);
INSERT INTO `razor_log` VALUES ('347', 'runweekly', '-----start runweekly-----', '2015-12-15 19:46:57', null, null, null);
INSERT INTO `razor_log` VALUES ('348', 'runweekly', 'razor_sum_reserveusers_weekly new users for app,version,channel dimensions', '2015-12-15 19:46:57', '2015-12-15 19:46:58', '5', '1');
INSERT INTO `razor_log` VALUES ('349', 'runweekly', 'razor_sum_reserveusers_weekly WEEK -1 reserve users for app,channel,version dimensions', '2015-12-15 19:46:58', '2015-12-15 19:46:58', '0', '0');
INSERT INTO `razor_log` VALUES ('350', 'runweekly', 'razor_sum_reserveusers_weekly WEEK -2 reserve users for app,channel,version dimensions', '2015-12-15 19:46:58', '2015-12-15 19:46:58', '0', '0');
INSERT INTO `razor_log` VALUES ('351', 'runweekly', 'razor_sum_reserveusers_weekly WEEK -3 reserve users for app,channel,version dimensions', '2015-12-15 19:46:58', '2015-12-15 19:46:58', '0', '0');
INSERT INTO `razor_log` VALUES ('352', 'runweekly', 'razor_sum_reserveusers_weekly WEEK -4 reserve users for app,channel,version dimensions', '2015-12-15 19:46:58', '2015-12-15 19:46:58', '0', '0');
INSERT INTO `razor_log` VALUES ('353', 'runweekly', 'razor_sum_reserveusers_weekly WEEK -5 reserve users for app,channel,version dimensions', '2015-12-15 19:46:58', '2015-12-15 19:46:58', '0', '0');
INSERT INTO `razor_log` VALUES ('354', 'runweekly', 'razor_sum_reserveusers_weekly WEEK -6 reserve users for app,channel,version dimensions', '2015-12-15 19:46:58', '2015-12-15 19:46:58', '0', '0');
INSERT INTO `razor_log` VALUES ('355', 'runweekly', 'razor_sum_reserveusers_weekly WEEK -7 reserve users for app,channel,version dimensions', '2015-12-15 19:46:58', '2015-12-15 19:46:58', '0', '0');
INSERT INTO `razor_log` VALUES ('356', 'runweekly', 'razor_sum_reserveusers_weekly WEEK -8 reserve users for app,channel,version dimensions', '2015-12-15 19:46:58', '2015-12-15 19:46:58', '0', '0');
INSERT INTO `razor_log` VALUES ('357', 'runweekly', 'razor_sum_basic_activeusers week activeuser and percent', '2015-12-15 19:46:58', '2015-12-15 19:46:58', '1', '0');
INSERT INTO `razor_log` VALUES ('358', 'runweekly', 'razor_sum_basic_channel_activeusers each channel active user and percent', '2015-12-15 19:46:58', '2015-12-15 19:46:58', '1', '0');
INSERT INTO `razor_log` VALUES ('359', 'runweekly', '-----finish runweekly-----', '2015-12-15 19:46:58', null, null, null);
INSERT INTO `razor_log` VALUES ('360', 'runmonthly', '-----start runmonthly-----', '2015-12-15 19:47:06', null, null, null);
INSERT INTO `razor_log` VALUES ('361', 'runmonthly', 'razor_sum_reserveusers_monthly new users for app,version,channel dimensions', '2015-12-15 19:47:06', '2015-12-15 19:47:06', '0', '0');
INSERT INTO `razor_log` VALUES ('362', 'runmonthly', 'razor_sum_reserveusers_monthly MONTH -1 reserve users for app,channel,version dimensions', '2015-12-15 19:47:06', '2015-12-15 19:47:06', '0', '0');
INSERT INTO `razor_log` VALUES ('363', 'runmonthly', 'razor_sum_reserveusers_monthly MONTH -2 reserve users for app,channel,version dimensions', '2015-12-15 19:47:06', '2015-12-15 19:47:06', '0', '0');
INSERT INTO `razor_log` VALUES ('364', 'runmonthly', 'razor_sum_reserveusers_monthly MONTH -3 reserve users for app,channel,version dimensions', '2015-12-15 19:47:07', '2015-12-15 19:47:07', '0', '0');
INSERT INTO `razor_log` VALUES ('365', 'runmonthly', 'razor_sum_reserveusers_monthly MONTH -4 reserve users for app,channel,version dimensions', '2015-12-15 19:47:07', '2015-12-15 19:47:07', '0', '0');
INSERT INTO `razor_log` VALUES ('366', 'runmonthly', 'razor_sum_reserveusers_monthly MONTH -5 reserve users for app,channel,version dimensions', '2015-12-15 19:47:07', '2015-12-15 19:47:07', '0', '0');
INSERT INTO `razor_log` VALUES ('367', 'runmonthly', 'razor_sum_reserveusers_monthly MONTH -6 reserve users for app,channel,version dimensions', '2015-12-15 19:47:07', '2015-12-15 19:47:07', '0', '0');
INSERT INTO `razor_log` VALUES ('368', 'runmonthly', 'razor_sum_reserveusers_monthly MONTH -7 reserve users for app,channel,version dimensions', '2015-12-15 19:47:07', '2015-12-15 19:47:07', '0', '0');
INSERT INTO `razor_log` VALUES ('369', 'runmonthly', 'razor_sum_reserveusers_monthly MONTH -8 reserve users for app,channel,version dimensions', '2015-12-15 19:47:07', '2015-12-15 19:47:07', '0', '0');
INSERT INTO `razor_log` VALUES ('370', 'runmonthly', 'razor_sum_basic_activeusers active users and percent', '2015-12-15 19:47:07', '2015-12-15 19:47:07', '0', '0');
INSERT INTO `razor_log` VALUES ('371', 'runmonthly', 'razor_sum_basic_channel_activeusers channel activeusers and percent', '2015-12-15 19:47:07', '2015-12-15 19:47:07', '0', '0');
INSERT INTO `razor_log` VALUES ('372', 'runmonthly', '-----finish runmonthly-----', '2015-12-15 19:47:07', null, null, null);

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_sum_accesslevel
-- ----------------------------
INSERT INTO `razor_sum_accesslevel` VALUES ('1', null, '-1', '-999', null, '1');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_sum_accesspath
-- ----------------------------
INSERT INTO `razor_sum_accesspath` VALUES ('1', null, '-1', '-999', null, '1');

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
-- Records of razor_sum_basic_activeusers
-- ----------------------------
INSERT INTO `razor_sum_basic_activeusers` VALUES ('1', '1', '0', '1', '0');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_sum_basic_byhour
-- ----------------------------
INSERT INTO `razor_sum_basic_byhour` VALUES ('1', '1', '2176', '0', '1', '1', '0');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_sum_basic_channel
-- ----------------------------
INSERT INTO `razor_sum_basic_channel` VALUES ('1', '1', '1', '2176', '1', '1', '0', '0', '0', '1', '107');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_sum_basic_channel_activeusers
-- ----------------------------
INSERT INTO `razor_sum_basic_channel_activeusers` VALUES ('1', '2169', '1', '1', '1', '1', '0');

-- ----------------------------
-- Table structure for razor_sum_basic_dauusers
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_dauusers`;
CREATE TABLE `razor_sum_basic_dauusers` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `channel_name` varchar(64) NOT NULL,
  `server_name` varchar(64) NOT NULL,
  `version_name` varchar(64) NOT NULL,
  `date_sk` int(11) NOT NULL,
  `newuser` int(11) NOT NULL DEFAULT '0',
  `payuser` int(11) NOT NULL DEFAULT '0',
  `notpayuser` int(11) NOT NULL DEFAULT '0',
  `daydau` int(11) NOT NULL DEFAULT '0',
  `weekdau` int(11) NOT NULL DEFAULT '0',
  `monthdau` int(11) NOT NULL DEFAULT '0',
  `useractiverate` decimal(11,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_sum_basic_dauusers
-- ----------------------------
INSERT INTO `razor_sum_basic_dauusers` VALUES ('1', '1', '安卓市场', '区服一', '1.0', '2176', '2', '2', '0', '2', '2', '2', '1.00');
INSERT INTO `razor_sum_basic_dauusers` VALUES ('2', '1', '安卓市场', 'all', '1.0', '2176', '2', '2', '0', '2', '2', '2', '1.00');
INSERT INTO `razor_sum_basic_dauusers` VALUES ('3', '1', '安卓市场', 'all', 'all', '2176', '2', '2', '0', '2', '2', '2', '1.00');
INSERT INTO `razor_sum_basic_dauusers` VALUES ('4', '1', 'all', '区服一', '1.0', '2176', '2', '2', '0', '2', '2', '2', '1.00');
INSERT INTO `razor_sum_basic_dauusers` VALUES ('5', '1', 'all', 'all', '1.0', '2176', '2', '2', '0', '2', '2', '2', '1.00');
INSERT INTO `razor_sum_basic_dauusers` VALUES ('6', '1', 'all', 'all', 'all', '2176', '2', '2', '0', '2', '2', '2', '1.00');

-- ----------------------------
-- Table structure for razor_sum_basic_newusers
-- ----------------------------
DROP TABLE IF EXISTS `razor_sum_basic_newusers`;
CREATE TABLE `razor_sum_basic_newusers` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `channel_name` varchar(64) NOT NULL,
  `version_name` varchar(64) NOT NULL,
  `date_sk` int(11) NOT NULL,
  `deviceactivations` int(11) NOT NULL DEFAULT '0',
  `newusers` int(11) NOT NULL DEFAULT '0',
  `newdevices` decimal(11,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_sum_basic_newusers
-- ----------------------------
INSERT INTO `razor_sum_basic_newusers` VALUES ('1', '1', '安卓市场', '1.0', '2176', '2', '2', '2.00');
INSERT INTO `razor_sum_basic_newusers` VALUES ('2', '1', 'all', 'all', '2176', '2', '2', '2.00');
INSERT INTO `razor_sum_basic_newusers` VALUES ('3', '1', 'all', '1.0', '2176', '2', '2', '2.00');
INSERT INTO `razor_sum_basic_newusers` VALUES ('4', '1', '安卓市场', 'all', '2176', '2', '2', '2.00');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_sum_basic_product
-- ----------------------------
INSERT INTO `razor_sum_basic_product` VALUES ('1', '1', '2176', '1', '1', '0', '0', '0', '1', '107');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_sum_basic_product_version
-- ----------------------------
INSERT INTO `razor_sum_basic_product_version` VALUES ('1', '1', '2176', '1.0', '1', '1', '0', '0', '0', '1', '107');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_sum_devicebrand
-- ----------------------------
INSERT INTO `razor_sum_devicebrand` VALUES ('1', '1', '2176', '1', '1', '0');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_sum_devicenetwork
-- ----------------------------
INSERT INTO `razor_sum_devicenetwork` VALUES ('1', '1', '2176', '1', '1', '0');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_sum_deviceos
-- ----------------------------
INSERT INTO `razor_sum_deviceos` VALUES ('1', '1', '2176', '1', '1', '0');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_sum_deviceresolution
-- ----------------------------
INSERT INTO `razor_sum_deviceresolution` VALUES ('1', '1', '2176', '1', '1', '0');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_sum_devicesupplier
-- ----------------------------
INSERT INTO `razor_sum_devicesupplier` VALUES ('1', '1', '2176', '295', '1', '0');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_sum_event
-- ----------------------------
INSERT INTO `razor_sum_event` VALUES ('1', '1', '2176', '1', '1');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_sum_location
-- ----------------------------
INSERT INTO `razor_sum_location` VALUES ('1', '1', '2176', '1', '1', '0');

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
-- Records of razor_sum_reserveusers_daily
-- ----------------------------

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
-- Records of razor_sum_reserveusers_monthly
-- ----------------------------

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_sum_reserveusers_weekly
-- ----------------------------
INSERT INTO `razor_sum_reserveusers_weekly` VALUES ('1', '2169', '2175', '1', '1.0', '安卓市场', '1', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `razor_sum_reserveusers_weekly` VALUES ('2', '2169', '2175', '1', '1.0', 'all', '1', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `razor_sum_reserveusers_weekly` VALUES ('3', '2169', '2175', '1', 'all', 'all', '1', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `razor_sum_reserveusers_weekly` VALUES ('4', '2169', '2175', '-1', 'all', 'all', '1', '0', '0', '0', '0', '0', '0', '0', '0');
INSERT INTO `razor_sum_reserveusers_weekly` VALUES ('5', '2169', '2175', '1', 'all', '安卓市场', '1', '0', '0', '0', '0', '0', '0', '0', '0');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of razor_sum_usinglog_activity
-- ----------------------------
INSERT INTO `razor_sum_usinglog_activity` VALUES ('1', '2176', '1', '1', '1', '107', '1');

-- ----------------------------
-- Procedure structure for rundaily
-- ----------------------------
DROP PROCEDURE IF EXISTS `rundaily`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `rundaily`(IN `yesterday` DATE)
    NO SQL
begin

declare csession varchar(128);
declare clastsession varchar(128);

declare cactivityid int;
declare clastactivityid int;

declare cproductsk int;
declare clastproductsk int;
declare s datetime;
declare e datetime;
declare single int;
declare endflag int;
declare seq int;
DECLARE col VARCHAR(16); 
DECLARE days INT; 
DECLARE d INT; 

declare usinglogcursor cursor

for

select product_sk,session_id,activity_sk from razor_fact_usinglog f, razor_dim_date d where f.date_sk = d.date_sk

and d.datevalue = yesterday;

declare continue handler for not found set endflag = 1;

set endflag = 0;

set clastactivityid = -1;
set single = 0;

insert into razor_log(op_type,op_name,op_starttime) 
    values('rundaily','-----start rundaily-----',now());

set s = now();

open usinglogcursor;

repeat

  fetch usinglogcursor into cproductsk,csession,cactivityid;

  if csession=clastsession then
      update razor_sum_accesspath set count=count+1 
      where product_sk=cproductsk and fromid=clastactivityid 
      and toid=cactivityid and jump=seq;
      
      if row_count()=0 then 
      insert into razor_sum_accesspath(product_sk,fromid,toid,jump,count)
      select cproductsk,clastactivityid,cactivityid,seq,1;
      end if;
    set seq = seq +1;

  else
     update razor_sum_accesspath set count=count+1 
     where product_sk=clastproductsk and fromid=clastactivityid 
     and toid=-999 and jump=seq;
     
     if row_count()=0 then 
     insert into razor_sum_accesspath(product_sk,fromid,toid,jump,count) 
     select clastproductsk,clastactivityid,-999,seq,1;
     end if;
     set seq = 1;

     end if;

   set clastsession = csession;
   set clastactivityid = cactivityid;
   set clastproductsk = cproductsk;

until endflag=1 end repeat;

close usinglogcursor;

set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('rundaily','razor_sum_accesspath',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));
set s = now();

-- generate the count of new users for yesterday

-- for channels, versions
INSERT INTO razor_sum_reserveusers_daily 
            (startdate_sk, 
             enddate_sk, 
             product_id, 
             version_name, 
             channel_name, 
             usercount) 
SELECT (SELECT date_sk 
        FROM   razor_dim_date 
        WHERE  datevalue = yesterday)     startdate_sk, 
       (SELECT date_sk 
        FROM   razor_dim_date 
        WHERE  datevalue = yesterday)       enddate_sk, 
       ifnull(p.product_id,-1), 
       ifnull(p.version_name,'all'),
       ifnull(p.channel_name,'all'), 
       Count(DISTINCT f.deviceidentifier) count 
FROM   razor_fact_clientdata f, 
       razor_dim_date d, 
       razor_dim_product p 
WHERE  f.date_sk = d.date_sk 
       AND d.datevalue = yesterday 
       AND f.product_sk = p.product_sk 
       AND p.product_active = 1 
       AND p.channel_active = 1 
       AND p.version_active = 1 
       AND f.isnew = 1 
GROUP  BY p.product_id, 
          p.version_name,
          p.channel_name with rollup
union
SELECT (SELECT date_sk 
        FROM   razor_dim_date 
        WHERE  datevalue = yesterday)     startdate_sk, 
       (SELECT date_sk 
        FROM   razor_dim_date 
        WHERE  datevalue = yesterday)       enddate_sk, 
       ifnull(p.product_id,-1), 
       ifnull(p.version_name,'all'),
       ifnull(p.channel_name,'all'), 
       Count(DISTINCT f.deviceidentifier) count 
FROM   razor_fact_clientdata f, 
       razor_dim_date d, 
       razor_dim_product p 
WHERE  f.date_sk = d.date_sk 
       AND d.datevalue = yesterday 
       AND f.product_sk = p.product_sk 
       AND p.product_active = 1 
       AND p.channel_active = 1 
       AND p.version_active = 1 
       AND f.isnew = 1 
GROUP  BY p.product_id, 
          p.channel_name,
          p.version_name with rollup
ON DUPLICATE KEY UPDATE usercount=VALUES(usercount);

set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('rundaily','razor_sum_reserveusers_daily new users for app,version,channel dimensions',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));


set d = 1;
while d<=8 do
  begin
    set col = concat('day',d);

    set days = -d;
    
    set s = now();
    
    -- 8 days for app,channel, version
    SET @sql=concat(
        'insert into razor_sum_reserveusers_daily(startdate_sk, enddate_sk, product_id, version_name,channel_name,',
        col,
        ')
        Select 
        (select date_sk from razor_dim_date where datevalue= date_add(\'',yesterday,'\',interval ',days,' DAY)) startdate,
        (select date_sk from razor_dim_date where datevalue= date_add(\'',yesterday,'\',interval ',days,' DAY)) enddate,
        ifnull(p.product_id,-1),ifnull(p.version_name,\'all\'),ifnull(p.channel_name,\'all\'),
        count(distinct f.deviceidentifier)
        from
        razor_fact_clientdata f, razor_dim_date d, razor_dim_product p where f.date_sk = d.date_sk 
        and f.product_sk = p.product_sk and d.datevalue = \'',yesterday,'\' and p.product_active=1 
        and p.channel_active=1 and p.version_active=1 and exists 
         (select 1 from razor_fact_clientdata ff, razor_dim_product pp, razor_dim_date dd where ff.product_sk = pp.product_sk 
         and ff.date_sk = dd.date_sk and pp.product_id = p.product_id and dd.datevalue between 
         date_add(\'',yesterday,'\',interval ',days,' DAY) and 
         date_add(\'',yesterday,'\',interval ',days,' DAY) and ff.deviceidentifier = f.deviceidentifier and pp.product_active=1 
         and pp.channel_active=1 and pp.version_active=1 and ff.isnew=1) group by p.product_id,p.version_name,p.channel_name with rollup
         union
         Select 
        (select date_sk from razor_dim_date where datevalue= date_add(\'',yesterday,'\',interval ',days,' DAY)) startdate,
        (select date_sk from razor_dim_date where datevalue= date_add(\'',yesterday,'\',interval ',days,' DAY)) enddate,
        ifnull(p.product_id,-1),ifnull(p.version_name,\'all\'),ifnull(p.channel_name,\'all\'),
        count(distinct f.deviceidentifier)
        from
        razor_fact_clientdata f, razor_dim_date d, razor_dim_product p where f.date_sk = d.date_sk 
        and f.product_sk = p.product_sk and d.datevalue = \'',yesterday,'\' and p.product_active=1 
        and p.channel_active=1 and p.version_active=1 and exists 
         (select 1 from razor_fact_clientdata ff, razor_dim_product pp, razor_dim_date dd where ff.product_sk = pp.product_sk 
         and ff.date_sk = dd.date_sk and pp.product_id = p.product_id and dd.datevalue between 
         date_add(\'',yesterday,'\',interval ',days,' DAY) and 
         date_add(\'',yesterday,'\',interval ',days,' DAY) and ff.deviceidentifier = f.deviceidentifier and pp.product_active=1 
         and pp.channel_active=1 and pp.version_active=1 and ff.isnew=1) group by p.product_id,p.channel_name,p.version_name with rollup
        on duplicate key update ',col,'=values(',col,');');
        
    
    PREPARE sl FROM @sql;
    EXECUTE sl;
    DEALLOCATE PREPARE sl;
    
    set e = now();
    insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('rundaily',concat('razor_sum_reserveusers_daily DAY ',-d,' reserve users for app,channel,version dimensions'),s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));
    
    set d = d + 1; 
  end;
end while;

set s = now();

insert into razor_sum_accesslevel(product_sk,fromid,toid,level,count)
select product_sk,fromid,toid,min(jump),sum(count) from razor_sum_accesspath group by product_sk,fromid,toid
on duplicate key update count = values(count);

set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('rundaily','razor_sum_accesslevel',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));
    
set s = now();


update razor_fact_clientdata a,razor_fact_clientdata b,razor_dim_date c,
razor_dim_product d,razor_dim_product f set a.isnew=0 where 
((a.date_sk>b.date_sk) or (a.date_sk=b.date_sk and a.dataid>b.dataid)) 
and a.isnew=1 
and a.date_sk=c.date_sk and c.datevalue between DATE_SUB(yesterday,INTERVAL 7 DAY) and yesterday
and a.product_sk=d.product_sk 
and b.product_sk=f.product_sk 
and a.deviceidentifier=b.deviceidentifier and d.product_id=f.product_id;

set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('rundaily','razor_fact_clientdata recalculate new users',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));
    
set s = now();


update razor_fact_clientdata a,razor_fact_clientdata b,razor_dim_date c,
razor_dim_product d,razor_dim_product f set a.isnew_channel=0 where 
((a.date_sk>b.date_sk) or (a.date_sk=b.date_sk and a.dataid>b.dataid)) 
and a.isnew_channel=1 
and a.date_sk=c.date_sk and c.datevalue between DATE_SUB(yesterday,INTERVAL 7 DAY) and yesterday
and a.product_sk=d.product_sk 
and b.product_sk=f.product_sk 
and a.deviceidentifier=b.deviceidentifier and d.product_id=f.product_id and d.channel_id=f.channel_id;

set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('rundaily','razor_fact_clientdata recalculate new users for channel',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

insert into razor_log(op_type,op_name,op_starttime) 
    values('rundaily','-----finish rundaily-----',now());
    
end
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for rundim
-- ----------------------------
DROP PROCEDURE IF EXISTS `rundim`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `rundim`()
    NO SQL
begin
declare s datetime;
declare e datetime;

insert into razor_log(op_type,op_name,op_starttime)
    values('rundim','-----start rundim-----',now());


/* dim location */
set s = now();

update razor.razor_clientdata
set region = 'unknown'
where (region = '') or (region is null);

update razor.razor_clientdata
set city = 'unknown'
where (city = '') or (city is null);

insert into razor_dim_location
           (country,
            region,
            city)
select distinct country,
                region,
                city
from   razor.razor_clientdata a
where  not exists (select 1
                   from   razor_dim_location b
                   where  a.country = b.country
                          and a.region = b.region
                          and a.city = b.city);
set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('rundim','razor_dim_location',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

/* dim devicebrand */
set s = now();
insert into razor_dim_devicebrand(devicebrand_name)
select distinct devicename
from   razor.razor_clientdata a
where  not exists (select 1
                   from   razor_dim_devicebrand b
                   where  a.devicename = b.devicebrand_name);
 insert into razor_dim_deviceos
           (deviceos_name)
select distinct osversion
from   razor.razor_clientdata a
where  not exists (select *
                   from   razor_dim_deviceos b
                   where  b.deviceos_name = a.osversion);
                   
set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('rundim','razor_dim_deviceos',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

/* dim devicelanguage */
set s = now();
insert into razor_dim_devicelanguage
           (devicelanguage_name)
select distinct language
from   razor.razor_clientdata a
where  not exists (select *
                   from   razor_dim_devicelanguage b
                   where  a.language = b.devicelanguage_name);
set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('rundim','razor_dim_devicelanguage',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

/* dim resolution */
set s = now();
insert into razor_dim_deviceresolution
           (deviceresolution_name)
select distinct resolution
from   razor.razor_clientdata a
where  not exists (select *
                   from   razor_dim_deviceresolution b
                   where  a.resolution = b.deviceresolution_name);
                   
set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('rundim','razor_dim_deviceresolution',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

/* dim devicesupplier */
set s = now();
insert into razor_dim_devicesupplier (mccmnc)
select distinct a.service_supplier
from   razor.razor_clientdata a
where  not exists (select *
                   from   razor_dim_devicesupplier b
                   where  a.service_supplier = b.mccmnc);

set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('rundim','razor_dim_devicesupplier',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

/* dim product */
set s = now();
update 
razor_dim_product dp, 
razor.razor_product p,
       razor.razor_channel_product cp,
       razor.razor_channel c,
       razor.razor_clientdata cd,
       razor.razor_product_category pc,
       razor.razor_platform pf
set 
    dp.product_name = p.name,
    dp.product_type = pc.name,
    dp.product_active = p.active,
    dp.channel_name = c.channel_name,
    dp.channel_active = c.active,
    dp.product_key = cd.productkey,
    dp.version_name = cd.version,
    dp.platform = pf.name
where
    p.id = cp.product_id and
    cp.channel_id = c.channel_id and 
    cp.productkey = cd.productkey and 
    p.category = pc.id and 
    c.platform = pf.id and
    dp.product_id = p.id and 
    dp.channel_id = c.channel_id and 
    dp.version_name = cd.version and
    dp.userid = cp.user_id and 
    (dp.product_name <> p.name or 
    dp.product_type <> pc.name or 
    dp.product_active = p.active or 
    dp.channel_name = c.channel_name or 
    dp.channel_active = c.active or 
    dp.product_key = cd.productkey or 
    dp.version_name = cd.version or 
        dp.platform <> pf.name );
insert into razor_dim_product
           (product_id,
            product_name,
            product_type,
            product_active,
            channel_id,
            channel_name,
            channel_active,
            product_key,
            version_name,
            version_active,
            userid,
            platform)
select distinct 
p.id,
p.name,
pc.name,
p.active,
c.channel_id,
c.channel_name,
c.active,
cd.productkey,
                cd.version,
                1,
                cp.user_id,
                pf.name
from  razor.razor_product p inner join
       razor.razor_channel_product cp on p.id = cp.product_id inner join
       razor.razor_channel c on cp.channel_id = c.channel_id inner join
       razor.razor_product_category pc on p.category = pc.id inner join
       razor.razor_platform pf on c.platform = pf.id inner join (select distinct
       productkey,version from razor.razor_clientdata) cd on cp.productkey = cd.productkey  
       and not exists (select 1
                       from   razor_dim_product dp
                       where  dp.product_id = p.id and
                               dp.channel_id = c.channel_id and
                               dp.version_name = cd.version and
                               dp.userid = cp.user_id);
set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('rundim','razor_dim_product',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

/* dim network */
set s = now();                              
insert into razor_dim_network
           (networkname)
select distinct cd.network
from  razor.razor_clientdata cd
where  not exists (select 1
                       from   razor_dim_network nw
                       where  nw.networkname = cd.network);

set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('rundim','razor_dim_network',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

/* dim activity */
set s = now();   

insert into razor_dim_activity  (activity_name,product_id)
select distinct f.activities,p.id
from   razor.razor_clientusinglog f,razor.razor_product p,razor.razor_channel_product cp
where  
f.appkey = cp.productkey and 
cp.product_id = p.id
and not exists (select 1
                   from   razor_dim_activity a
                   where  a.activity_name = f.activities
and a.product_id = p.id);

set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('rundim','razor_dim_activity',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

/* dim errortitle */
set s = now();
insert into razor_dim_errortitle
           (title_name,isfix)
select distinct f.title,0
from   razor.razor_errorlog f
where  not exists (select *
                   from   razor_dim_errortitle ee
                   where  ee.title_name = f.title);
set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration)
     values('rundim','razor_dim_errortitle',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

/* dim event */
set s = now();
update razor_dim_event e,razor.razor_event_defination d
set e.eventidentifier = d.event_identifier,
e.eventname = d.event_name,
e.product_id = d.product_id,
e.active = d.active
where e.event_id = d.event_id and (e.eventidentifier <> d.event_identifier or e.eventname<>d.event_name or e.product_id <> d.product_id or e.active <> d.active);


insert into razor_dim_event       (eventidentifier,eventname,active,product_id,createtime,event_id)
select distinct event_identifier,event_name,active,product_id,create_date,f.event_id
from   razor.razor_event_defination f
where  not exists (select *
                   from   razor_dim_event ee
                   where  ee.eventidentifier = f.event_identifier
and ee.eventname = f.event_name
and ee.active = f.active
and ee.product_id = f.product_id
and ee.createtime = f.create_date);

set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('rundim','razor_dim_event',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

insert into razor_log(op_type,op_name,op_starttime)
    values('rundim','-----finish rundim-----',now());



end
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for runfact
-- ----------------------------
DROP PROCEDURE IF EXISTS `runfact`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `runfact`(IN `starttime` DATETIME, IN `endtime` DATETIME)
    NO SQL
begin
declare s datetime;
declare e datetime;

insert into razor_log(op_type,op_name,op_starttime)
    values('runfact','-----start  runfact-----',now());

set s = now();

insert into razor_fact_clientdata
           (product_sk,
            deviceos_sk,
            deviceresolution_sk,
            devicelanguage_sk,
            devicebrand_sk,
            devicesupplier_sk,
            location_sk,
            date_sk,
            hour_sk,
            deviceidentifier,
            clientdataid,
            network_sk,
            useridentifier
            )
select i.product_sk,
       b.deviceos_sk,
       d.deviceresolution_sk,
       e.devicelanguage_sk,
       c.devicebrand_sk,
       f.devicesupplier_sk,
       h.location_sk,
       g.date_sk,
       hour(a.date),
       a.deviceid,
       a.id,
       n.network_sk,
       a.useridentifier
from   razor.razor_clientdata a,
       razor_dim_deviceos b,
       razor_dim_devicebrand c,
       razor_dim_deviceresolution d,
       razor_dim_devicelanguage e,
       razor_dim_devicesupplier f,
       razor_dim_date g,
       razor_dim_location h,
       razor_dim_product i,
       razor_dim_network n
where 
       a.osversion = b.deviceos_name
       and a.devicename = c.devicebrand_name
       and a.resolution = d.deviceresolution_name
       and a.language = e.devicelanguage_name
       and a.service_supplier = f.mccmnc
       and date(a.date) = g.datevalue
       and a.country = h.country
       and a.region = h.region
       and a.city = h.city
       and a.productkey = i.product_key
       and i.product_active = 1 and i.channel_active = 1 and i.version_active = 1 
       and a.version = i.version_name
       and a.network = n.networkname
       and a.insertdate between starttime and endtime;

set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('runfact','razor_fact_clientdata',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));


set s = now();
insert into razor_fact_usinglog
           (product_sk,
            date_sk,
            activity_sk,
            session_id,
            duration,
            activities,
            starttime,
            endtime,
            uid)
select p.product_sk,
       d.date_sk,
       a.activity_sk,
       u.session_id,
       u.duration,
       u.activities,
       u.start_millis,
       end_millis,
       u.id
from   razor.razor_clientusinglog u,
       razor_dim_date d,
       razor_dim_product p,
       razor_dim_activity a
where  date(u.start_millis) = d.datevalue and 
       u.appkey = p.product_key 
       and p.product_id=a.product_id 
       and u.version = p.version_name 
       and u.activities = a.activity_name
       and u.insertdate between starttime and endtime;
set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('runfact','razor_fact_usinglog',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

set s = now();
insert into razor_fact_errorlog
           (date_sk,
            product_sk,
            osversion_sk,
            title_sk,
            deviceidentifier,
            activity,
            time,
            title,
            stacktrace,
            isfix,
            id
            )
select d.date_sk,
       p.product_sk,
       o.deviceos_sk,
       t.title_sk,
       b.devicebrand_sk,
       e.activity,
       e.time,
       e.title,
       e.stacktrace,
       e.isfix,
       e.id
from   razor.razor_errorlog e,
       razor_dim_product p,
       razor_dim_date d,
       razor_dim_deviceos o,
       razor_dim_errortitle t,
       razor_dim_devicebrand b
where  e.appkey = p.product_key
       and e.version = p.version_name
       and date(e.time) = d.datevalue
       and e.os_version = o.deviceos_name
       and e.title = t.title_name
       and e.device = b.devicebrand_name
       and p.product_active = 1 and p.channel_active = 1 and p.version_active = 1
       and e.insertdate between starttime and endtime; 
set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('runfact','razor_fact_errorlog',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));


set s = now();
insert into razor_fact_event
           (event_sk,
            product_sk,
            date_sk,
            deviceid,
            category,
            event,
            label,
            attachment,
            clientdate,
            number)
select e.event_sk,
       p.product_sk,
       d.date_sk,
       f.deviceid,
       f.category,
       f.event,
       f.label,
       f.attachment,
       f.clientdate,
       f.num
from   razor.razor_eventdata f,
       razor_dim_event e,
       razor_dim_product p,
       razor_dim_date d
where  f.event_id = e.event_id
       and e.product_id = p.product_id
       and f.version = p.version_name
       and f.productkey = p.product_key
       and p.product_active = 1 and p.channel_active = 1 and p.version_active = 1
       and date(f.clientdate) = d.datevalue
       and f.insertdate between starttime and endtime;
set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('runfact','razor_fact_event',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));
set s = now();

insert into razor_log(op_type,op_name,op_starttime)
    values('runfact','-----finish runfact-----',now());
    
end
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for runmonthly
-- ----------------------------
DROP PROCEDURE IF EXISTS `runmonthly`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `runmonthly`(IN `begindate` DATE, IN `enddate` DATE)
    NO SQL
begin


declare s datetime;
declare e datetime;
DECLARE col VARCHAR(16); 
DECLARE months INT; 
DECLARE m INT; 

insert into razor_log(op_type,op_name,op_starttime) 
    values('runmonthly','-----start runmonthly-----',now());
    
set s = now();
-- new users for monthly reserve. for each channel, each version
INSERT INTO razor_sum_reserveusers_monthly 
            (startdate_sk, 
             enddate_sk, 
             product_id, 
             version_name, 
             channel_name, 
             usercount) 
SELECT (SELECT date_sk 
        FROM   razor_dim_date 
        WHERE  datevalue = begindate)     startdate_sk, 
       (SELECT date_sk 
        FROM   razor_dim_date 
        WHERE  datevalue = enddate)       enddate_sk, 
       ifnull(p.product_id,-1), 
       ifnull(p.version_name,'all'), 
       ifnull(p.channel_name,'all'),
       Count(DISTINCT f.deviceidentifier) count 
FROM   razor_fact_clientdata f, 
       razor_dim_date d, 
       razor_dim_product p 
WHERE  f.date_sk = d.date_sk 
       AND d.datevalue BETWEEN begindate AND enddate 
       AND f.product_sk = p.product_sk 
       AND p.product_active = 1 
       AND p.channel_active = 1 
       AND p.version_active = 1 
       AND f.isnew = 1 
GROUP  BY p.product_id, 
          p.version_name,
          p.channel_name with rollup
union
SELECT (SELECT date_sk 
        FROM   razor_dim_date 
        WHERE  datevalue = begindate)     startdate_sk, 
       (SELECT date_sk 
        FROM   razor_dim_date 
        WHERE  datevalue = enddate)       enddate_sk, 
       ifnull(p.product_id,-1), 
       ifnull(p.version_name,'all'), 
       ifnull(p.channel_name,'all'),
       Count(DISTINCT f.deviceidentifier) count 
FROM   razor_fact_clientdata f, 
       razor_dim_date d, 
       razor_dim_product p 
WHERE  f.date_sk = d.date_sk 
       AND d.datevalue BETWEEN begindate AND enddate 
       AND f.product_sk = p.product_sk 
       AND p.product_active = 1 
       AND p.channel_active = 1 
       AND p.version_active = 1 
       AND f.isnew = 1 
GROUP  BY p.product_id, 
          p.channel_name,
          p.version_name with rollup
ON DUPLICATE KEY UPDATE usercount=VALUES(usercount);

set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('runmonthly','razor_sum_reserveusers_monthly new users for app,version,channel dimensions',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));
    
set m = 1;
while m<=8 do
  begin
    set col = concat('month',m);

    set months = -m;
    set s = now();
    
    -- 8 months for each channel, each version
    SET @sql=Concat(
        'insert into razor_sum_reserveusers_monthly(startdate_sk, enddate_sk, product_id,version_name,channel_name,',
        col,
        ') Select
        (select date_sk from razor_dim_date where datevalue = date_add(\'',begindate,'\',interval ',months,' MONTH)) startdate,
        (select date_sk from razor_dim_date where datevalue = last_day(\'',enddate,'\' + interval ',months,' MONTH)) enddate,
        ifnull(p.product_id,-1),ifnull(p.version_name,\'all\'),ifnull(p.channel_name,\'all\'),
        count(distinct f.deviceidentifier)
        from
        razor_fact_clientdata f, razor_dim_date d, razor_dim_product p where f.date_sk = d.date_sk and 
        f.product_sk = p.product_sk and d.datevalue between \'',begindate,'\' and \'',enddate,'\' and p.product_active = 1 
        and p.channel_active = 1 and p.version_active = 1 and exists
        (select 1 from razor_fact_clientdata ff, razor_dim_product pp, razor_dim_date dd 
        where ff.product_sk = pp.product_sk and ff.date_sk = dd.date_sk and pp.product_id = p.product_id and 
        dd.datevalue between date_add(\'',begindate,'\',interval ',months,' MONTH) and last_day(\'',enddate,'\' + interval ',months,' MONTH) and 
        ff.deviceidentifier = f.deviceidentifier and pp.product_active = 1 and pp.channel_active = 1 and 
        pp.version_active = 1 and ff.isnew = 1 ) group by p.product_id,p.version_name,p.channel_name with rollup
        union
        Select
        (select date_sk from razor_dim_date where datevalue = date_add(\'',begindate,'\',interval ',months,' MONTH)) startdate,
        (select date_sk from razor_dim_date where datevalue = last_day(\'',enddate,'\' + interval ',months,' MONTH)) enddate,
        ifnull(p.product_id,-1),ifnull(p.version_name,\'all\'),ifnull(p.channel_name,\'all\'),
        count(distinct f.deviceidentifier)
        from
        razor_fact_clientdata f, razor_dim_date d, razor_dim_product p where f.date_sk = d.date_sk and 
        f.product_sk = p.product_sk and d.datevalue between \'',begindate,'\' and \'',enddate,'\' and p.product_active = 1 
        and p.channel_active = 1 and p.version_active = 1 and exists
        (select 1 from razor_fact_clientdata ff, razor_dim_product pp, razor_dim_date dd 
        where ff.product_sk = pp.product_sk and ff.date_sk = dd.date_sk and pp.product_id = p.product_id and 
        dd.datevalue between date_add(\'',begindate,'\',interval ',months,' MONTH) and last_day(\'',enddate,'\' + interval ',months,' MONTH) and 
        ff.deviceidentifier = f.deviceidentifier and pp.product_active = 1 and pp.channel_active = 1 and 
        pp.version_active = 1 and ff.isnew = 1 ) group by p.product_id,p.channel_name,p.version_name with rollup
        on duplicate key update ',
        col,
        '= values(',
        col,
        ');');
    
    PREPARE sl FROM @sql;
    EXECUTE sl;
    DEALLOCATE PREPARE sl;
    
    set e = now();
    insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('runmonthly',concat('razor_sum_reserveusers_monthly MONTH ',-m,' reserve users for app,channel,version dimensions'),s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));
    set s = now();
    

    
    set m = m + 1; 
  end;
end while;

set s = now();
INSERT INTO razor_sum_basic_activeusers 
            (product_id, 
             month_activeuser, 
             month_percent) 
SELECT p.product_id, 
       Count(DISTINCT f.deviceidentifier) activeusers, 
       Count(DISTINCT f.deviceidentifier) / (SELECT 
       Count(DISTINCT ff.deviceidentifier) 
                                             FROM   razor_fact_clientdata ff, 
                                                    razor_dim_date dd, 
                                                    razor_dim_product pp 
                                             WHERE  dd.datevalue <= enddate 
                                                    AND 
                                            pp.product_id = p.product_id 
                                                    AND pp.product_active = 1 
                                                    AND pp.channel_active = 1 
                                                    AND pp.version_active = 1 
                                                    AND 
                                            ff.product_sk = pp.product_sk 
                                                    AND ff.date_sk = dd.date_sk) 
                                          percent 
FROM   razor_fact_clientdata f, 
       razor_dim_date d, 
       razor_dim_product p 
WHERE  d.datevalue BETWEEN begindate AND enddate 
       AND p.product_active = 1 
       AND p.channel_active = 1 
       AND p.version_active = 1 
       AND f.product_sk = p.product_sk 
       AND f.date_sk = d.date_sk 
GROUP  BY p.product_id 
ON DUPLICATE KEY UPDATE month_activeuser=VALUES(month_activeuser),month_percent=VALUES(month_percent);

set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('runmonthly','razor_sum_basic_activeusers active users and percent',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));
    
    
set s = now();
INSERT INTO razor_sum_basic_channel_activeusers 
            (date_sk, 
             product_id, 
             channel_id, 
             activeuser, 
             percent, 
             flag) 
SELECT (SELECT date_sk 
        FROM   razor_dim_date 
        WHERE  datevalue = begindate)     startdate, 
       p.product_id, 
       p.channel_id, 
       Count(DISTINCT f.deviceidentifier) activeusers, 
       Count(DISTINCT f.deviceidentifier) / (SELECT 
       Count(DISTINCT ff.deviceidentifier) 
                                             FROM   razor_fact_clientdata ff, 
                                                    razor_dim_date dd, 
                                                    razor_dim_product pp 
                                             WHERE  dd.datevalue <= enddate 
                                                    AND 
                                            pp.product_id = p.product_id 
                                                    AND 
                                            pp.channel_id = p.channel_id 
                                                    AND pp.product_active = 1 
                                                    AND pp.channel_active = 1 
                                                    AND pp.version_active = 1 
                                                    AND 
                                            ff.product_sk = pp.product_sk 
                                                    AND 
       ff.date_sk = dd.date_sk), 
       1 
FROM   razor_fact_clientdata f, 
       razor_dim_date d, 
       razor_dim_product p 
WHERE  d.datevalue BETWEEN begindate AND enddate 
       AND p.product_active = 1 
       AND p.channel_active = 1 
       AND p.version_active = 1 
       AND f.product_sk = p.product_sk 
       AND f.date_sk = d.date_sk 
GROUP  BY p.product_id, 
          p.channel_id 
ON DUPLICATE KEY UPDATE activeuser = VALUES(activeuser),percent=VALUES(percent);

set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('runmonthly','razor_sum_basic_channel_activeusers channel activeusers and percent',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

insert into razor_log(op_type,op_name,op_starttime) 
    values('runmonthly','-----finish runmonthly-----',now());

end
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for runsum
-- ----------------------------
DROP PROCEDURE IF EXISTS `runsum`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `runsum`(IN `today` DATE)
    NO SQL
begin
declare s datetime;
declare e datetime;

insert into razor_log(op_type,op_name,op_starttime) 
    values('runsum','-----start runsum-----',now());
    
-- update fact_clientdata  
set s = now();
update  razor_fact_clientdata a,
        razor_fact_clientdata b,
        razor_dim_date c,
        razor_dim_product d,
        razor_dim_product f 
set     a.isnew=0 
where   ((a.date_sk>b.date_sk) or (a.date_sk=b.date_sk and a.dataid>b.dataid)) 
and     a.isnew=1 
and     a.date_sk=c.date_sk 
and     c.datevalue=today
and     a.product_sk=d.product_sk 
and     b.product_sk=f.product_sk 
and     a.deviceidentifier=b.deviceidentifier 
and     d.product_id=f.product_id;

set e = now();
insert into razor_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','razor_fact_clientdata update',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

set s = now();

update razor_fact_clientdata a,
       razor_fact_clientdata b,
       razor_dim_date c,
       razor_dim_product d,
       razor_dim_product f 
set    a.isnew_channel=0 
where  ((a.date_sk>b.date_sk) or (a.date_sk=b.date_sk and a.dataid>b.dataid))
       and a.isnew_channel=1 
       and a.date_sk=c.date_sk 
       and c.datevalue=today 
       and a.product_sk=d.product_sk 
       and b.product_sk=f.product_sk 
       and a.deviceidentifier=b.deviceidentifier 
       and d.product_id=f.product_id 
       and d.channel_id=f.channel_id;

set e = now();
insert into razor_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','razor_fact_clientdata update',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

-- sum usinglog for each sessions
set s = now();
insert into razor_fact_usinglog_daily
           (product_sk,
            date_sk,
            session_id,
            duration)
select  f.product_sk,
         d.date_sk,
         f.session_id,
         sum(f.duration)
from    razor_fact_usinglog f,
         razor_dim_date d
where   
         d.datevalue = today and f.date_sk = d.date_sk
group by f.product_sk,d.date_sk,f.session_id on duplicate key update duration = values(duration);

set e = now();
insert into razor_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','razor_fact_usinglog_daily',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

-- sum_basic_product 

set s = now();
insert into razor_sum_basic_product(product_id,date_sk,sessions) 
select p.product_id, d.date_sk,count(f.deviceidentifier) 
from razor_fact_clientdata f,
     razor_dim_date d,
     razor_dim_product p
where d.datevalue = today 
      and f.date_sk = d.date_sk 
      and f.product_sk=p.product_sk
group by p.product_id on duplicate key update sessions = values(sessions);

insert into razor_sum_basic_product(product_id,date_sk,startusers) 
select p.product_id, d.date_sk,count(distinct f.deviceidentifier) 
from razor_fact_clientdata f,
     razor_dim_date d,
     razor_dim_product p 
where d.datevalue = today 
      and f.date_sk = d.date_sk 
      and p.product_sk=f.product_sk 
group by p.product_id on duplicate key update startusers = values(startusers);

insert into razor_sum_basic_product(product_id,date_sk,newusers) 
select p.product_id, f.date_sk,sum(f.isnew) 
from razor_fact_clientdata f, 
     razor_dim_date d, 
     razor_dim_product p 
where d.datevalue = today 
      and f.date_sk = d.date_sk 
      and p.product_sk = f.product_sk 
      and p.product_active = 1 
      and p.channel_active = 1 
      and p.version_active = 1 
group by p.product_id,f.date_sk on duplicate key update newusers = values(newusers);

insert into razor_sum_basic_product(product_id,date_sk,upgradeusers) 
select p.product_id, d.date_sk,
count(distinct f.deviceidentifier) 
from razor_fact_clientdata f, 
     razor_dim_date d, 
     razor_dim_product p 
where d.datevalue = today 
      and f.date_sk = d.date_sk 
      and p.product_sk = f.product_sk 
      and p.product_active = 1
      and p.channel_active = 1 
      and p.version_active = 1 
      and exists 
(select 1 
from razor_fact_clientdata ff, 
     razor_dim_date dd, razor_dim_product pp 
where dd.datevalue < today 
      and ff.date_sk = dd.date_sk 
      and pp.product_sk = ff.product_sk
      and pp.product_id = p.product_id 
      and pp.product_active = 1 
      and pp.channel_active = 1 
      and pp.version_active = 1 
      and f.deviceidentifier = ff.deviceidentifier 
      and STRCMP( pp.version_name, p.version_name ) < 0) 
group by p.product_id,d.date_sk on duplicate key update upgradeusers = values(upgradeusers);

insert into razor_sum_basic_product(product_id,date_sk,allusers) 
select f.product_id, 
(
 select date_sk 
 from razor_dim_date 
where datevalue=today) date_sk,
sum(f.newusers) 
from razor_sum_basic_product f,
     razor_dim_date d 
where d.date_sk=f.date_sk 
      and d.datevalue<=today 
group by f.product_id on duplicate key update allusers = values(allusers);

insert into razor_sum_basic_product(product_id,date_sk,allsessions) 
select f.product_id,(select date_sk from razor_dim_date where datevalue=today) date_sk,sum(f.sessions) 
from razor_sum_basic_product f,
     razor_dim_date d 
where d.datevalue<=today 
      and d.date_sk=f.date_sk 
group by f.product_id on duplicate key update allsessions = values(allsessions);

insert into razor_sum_basic_product(product_id,date_sk,usingtime)
select p.product_id,f.date_sk,sum(duration) 
from razor_fact_usinglog_daily f,
     razor_dim_product p,
     razor_dim_date d 
where f.date_sk = d.date_sk 
      and d.datevalue = today 
      and f.product_sk=p.product_sk 
group by p.product_id on duplicate key update usingtime = values(usingtime);

set e = now();
insert into razor_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','razor_sum_basic_product',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

-- sum_basic_channel 
set s = now();
insert into razor_sum_basic_channel(product_id,channel_id,date_sk,sessions) 
select p.product_id,p.channel_id,d.date_sk,count(f.deviceidentifier) 
from razor_fact_clientdata f, 
     razor_dim_date d,
     razor_dim_product p
where d.datevalue = today 
      and f.date_sk = d.date_sk 
      and f.product_sk=p.product_sk
group by p.product_id,p.channel_id on duplicate key update sessions = values(sessions);

insert into razor_sum_basic_channel(product_id,channel_id,date_sk,startusers) 
select p.product_id,p.channel_id, d.date_sk,count(distinct f.deviceidentifier) 
from razor_fact_clientdata f,
     razor_dim_date d,
     razor_dim_product p 
where d.datevalue = today 
      and f.date_sk = d.date_sk 
      and p.product_sk=f.product_sk 
group by p.product_id,p.channel_id on duplicate key update startusers = values(startusers);

insert into razor_sum_basic_channel(product_id,channel_id,date_sk,newusers) 
select p.product_id,p.channel_id,f.date_sk,sum(f.isnew_channel) 
from razor_fact_clientdata f,
     razor_dim_date d, 
     razor_dim_product p 
where d.datevalue = today 
      and f.date_sk = d.date_sk 
      and p.product_sk = f.product_sk 
      and p.product_active = 1 
      and p.channel_active = 1 
      and p.version_active = 1 
group by p.product_id,p.channel_id,f.date_sk on duplicate key update newusers = values(newusers);

insert into razor_sum_basic_channel(product_id,channel_id,date_sk,upgradeusers) 
select p.product_id,p.channel_id,d.date_sk,
count(distinct f.deviceidentifier) 
from razor_fact_clientdata f,
     razor_dim_date d, 
     razor_dim_product p 
where d.datevalue = today 
      and f.date_sk = d.date_sk 
      and p.product_sk = f.product_sk  
      and p.product_active = 1 
      and p.channel_active = 1 
     and p.version_active = 1 
and exists 
(select 1 
from razor_fact_clientdata ff,
     razor_dim_date dd,
     razor_dim_product pp 
where dd.datevalue < today 
      and ff.date_sk = dd.date_sk 
      and pp.product_sk = ff.product_sk 
      and pp.product_id = p.product_id 
      and pp.channel_id=p.channel_id 
      and pp.product_active = 1 
      and pp.channel_active = 1 
      and pp.version_active = 1 
      and f.deviceidentifier = ff.deviceidentifier 
      and STRCMP( pp.version_name, p.version_name ) < 0) 
 group by p.product_id,p.channel_id,d.date_sk on duplicate key update upgradeusers = values(upgradeusers);

insert into razor_sum_basic_channel(product_id,channel_id,date_sk,allusers) 
select f.product_id,f.channel_id,
(select date_sk 
  from razor_dim_date 
  where datevalue=today) date_sk,
sum(f.newusers)
from razor_sum_basic_channel f,
     razor_dim_date d
where d.date_sk=f.date_sk 
      and d.datevalue<=today 
group by f.product_id,f.channel_id on duplicate key update allusers = values(allusers); 

insert into razor_sum_basic_channel(product_id,channel_id,date_sk,allsessions) 
select f.product_id,f.channel_id,(select date_sk from razor_dim_date where datevalue=today) date_sk,
sum(f.sessions) 
from razor_sum_basic_channel f,
     razor_dim_date d 
where d.datevalue<=today 
      and d.date_sk=f.date_sk 
group by f.product_id,f.channel_id on duplicate key update allsessions = values(allsessions);

insert into razor_sum_basic_channel(product_id,channel_id,date_sk,usingtime)
select p.product_id,p.channel_id,f.date_sk,sum(duration) 
from razor_fact_usinglog_daily f,
     razor_dim_product p,
     razor_dim_date d where f.date_sk = d.date_sk 
and d.datevalue = today and f.product_sk=p.product_sk 
group by p.product_id,p.channel_id on duplicate key update usingtime = values(usingtime);

set e = now();
insert into razor_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','razor_sum_basic_channel',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));
  
    
-- sum_basic_product_version 

set s = now();
insert into razor_sum_basic_product_version(product_id,date_sk,version_name,sessions) 
select p.product_id, d.date_sk,p.version_name,count(f.deviceidentifier) 
from razor_fact_clientdata f,
     razor_dim_date d,
     razor_dim_product p
where d.datevalue = today 
      and f.date_sk = d.date_sk 
      and f.product_sk=p.product_sk
group by p.product_id,p.version_name on duplicate key update sessions = values(sessions);

insert into razor_sum_basic_product_version(product_id,date_sk,version_name,startusers) 
select p.product_id, d.date_sk,p.version_name,count(distinct f.deviceidentifier) 
from razor_fact_clientdata f,
     razor_dim_date d,
     razor_dim_product p 
where d.datevalue = today 
      and f.date_sk = d.date_sk
      and p.product_sk=f.product_sk 
group by p.product_id,p.version_name on duplicate key update startusers = values(startusers);

insert into razor_sum_basic_product_version(product_id,date_sk,version_name,newusers) 
select p.product_id, f.date_sk,p.version_name,sum(f.isnew) 
from razor_fact_clientdata f,
     razor_dim_date d, 
     razor_dim_product p 
where d.datevalue = today 
      and f.date_sk = d.date_sk  
      and p.product_sk = f.product_sk 
      and p.product_active = 1 
      and p.channel_active = 1 
      and p.version_active = 1 
      group by p.product_id,p.version_name,f.date_sk  
on duplicate key update newusers = values(newusers);

insert into razor_sum_basic_product_version(product_id,date_sk,version_name,upgradeusers) 
select p.product_id, d.date_sk,p.version_name,
count(distinct f.deviceidentifier)
from razor_fact_clientdata f, 
     razor_dim_date d,  
     razor_dim_product p 
where d.datevalue = today 
      and f.date_sk = d.date_sk 
      and p.product_sk = f.product_sk 
      and p.product_active = 1 
      and p.channel_active = 1 
      and p.version_active = 1 
      and exists 
(select 1 
from razor_fact_clientdata ff, 
     razor_dim_date dd,
     razor_dim_product pp 
where dd.datevalue < today 
      and ff.date_sk = dd.date_sk 
      and pp.product_sk = ff.product_sk
      and pp.product_id = p.product_id 
      and pp.product_active = 1 
      and pp.channel_active = 1 
      and pp.version_active = 1 
      and f.deviceidentifier = ff.deviceidentifier 
      and STRCMP( pp.version_name, p.version_name ) < 0) 
 group by   p.product_id,p.version_name,d.date_sk on duplicate key update upgradeusers = values(upgradeusers);

insert into razor_sum_basic_product_version(product_id,date_sk,version_name,allusers) 
select f.product_id, 
(select date_sk 
 from razor_dim_date 
where datevalue=today) date_sk,
f.version_name,
sum(f.newusers) 
from razor_sum_basic_product_version f,
     razor_dim_date d
where d.date_sk=f.date_sk 
      and d.datevalue<=today
group by f.product_id,f.version_name on duplicate key update allusers = values(allusers);

insert into razor_sum_basic_product_version(product_id,date_sk,version_name,allsessions) 
select f.product_id,(select date_sk from razor_dim_date where datevalue=today) date_sk,f.version_name,sum(f.sessions) 
from razor_sum_basic_product_version f,
     razor_dim_date d 
where d.datevalue<=today 
      and d.date_sk=f.date_sk 
group by f.product_id,f.version_name on duplicate key update allsessions = values(allsessions);

insert into razor_sum_basic_product_version(product_id,date_sk,version_name,usingtime)
select p.product_id,f.date_sk,p.version_name,sum(duration) 
from razor_fact_usinglog_daily f,
     razor_dim_product p,
     razor_dim_date d 
where f.date_sk = d.date_sk 
      and d.datevalue = today 
      and f.product_sk=p.product_sk 
group by p.product_id,p.version_name on duplicate key update usingtime = values(usingtime);

set e = now();
insert into razor_log(op_type,op_name,op_date,affected_rows,duration) 
values('runsum','razor_sum_basic_product_version',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));  
  

set s = now();
-- update segment_sk column

update razor_fact_usinglog_daily f,razor_dim_segment_usinglog s,razor_dim_date d
set    f.segment_sk = s.segment_sk
where  f.duration >= s.startvalue
       and f.duration < s.endvalue
       and f.date_sk = d.date_sk
       and d.datevalue = today;
set e = now();
insert into razor_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','razor_fact_usinglog_daily update',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

set s = now();
-- sum_basic_byhour --
Insert into razor_sum_basic_byhour(product_sk,date_sk,hour_sk,
sessions) 
Select f.product_sk, f.date_sk,f.hour_sk,
count(f.deviceidentifier) from razor_fact_clientdata f, razor_dim_date d
where d.datevalue = today and f.date_sk = d.date_sk
group by f.product_sk,f.date_sk,f.hour_sk on duplicate 
key update sessions = values(sessions);

Insert into razor_sum_basic_byhour(product_sk,date_sk,hour_sk,
startusers) 
Select f.product_sk, f.date_sk,f.hour_sk,
count(distinct f.deviceidentifier) from 
razor_fact_clientdata f, razor_dim_date d where d.datevalue = today  
and f.date_sk = d.date_sk group by f.product_sk,d.date_sk,
f.hour_sk on duplicate key update startusers = values(startusers);

Insert into razor_sum_basic_byhour(product_sk,date_sk,hour_sk,newusers) 
Select f.product_sk, f.date_sk,f.hour_sk,count(distinct f.deviceidentifier) from razor_fact_clientdata f, razor_dim_date d, razor_dim_product p where d.datevalue = today and f.date_sk = d.date_sk and p.product_sk = f.product_sk and p.product_active = 1 and p.channel_active = 1 and p.version_active = 1 and not exists (select 1 from razor_fact_clientdata ff, razor_dim_date dd, razor_dim_product pp where dd.datevalue < today and ff.date_sk = dd.date_sk and pp.product_sk = ff.product_sk and p.product_id = pp.product_id and pp.product_active = 1 and pp.channel_active = 1 and pp.version_active = 1 and f.deviceidentifier = ff.deviceidentifier) group by f.product_sk,f.date_sk,f.hour_sk on duplicate key update newusers = values(newusers);
set e = now();
insert into razor_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','razor_sum_basic_byhour',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));


set s = now();
-- sum_usinglog_activity --
insert into razor_sum_usinglog_activity(date_sk,product_sk,activity_sk,accesscount,totaltime)
select d.date_sk,p.product_sk,a.activity_sk, count(*), sum(duration)
from        razor_fact_usinglog f,         razor_dim_product p,   razor_dim_date d, razor_dim_activity a
where    f.date_sk = d.date_sk and f.activity_sk = a.activity_sk
         and d.datevalue =today
         and f.product_sk = p.product_sk
         and p.product_active = 1 and p.channel_active = 1 and p.version_active = 1 
group by d.date_sk,p.product_sk,a.activity_sk
on duplicate key update accesscount = values(accesscount),totaltime = values(totaltime);

insert into razor_sum_usinglog_activity(date_sk,product_sk,activity_sk,exitcount)
select tt.date_sk,tt.product_sk, tt.activity_sk,count(*)
from
(select * from(
select   d.date_sk,session_id,p.product_sk,f.activity_sk,endtime
                    from     razor_fact_usinglog f,
                             razor_dim_product p,
                             razor_dim_date d
                    where    f.date_sk = d.date_sk
                             and d.datevalue = today
                             and f.product_sk = p.product_sk
                    order by session_id,
                             endtime desc) t group by t.session_id) tt
group by tt.date_sk,tt.product_sk,tt.activity_sk
order by tt. date_sk,tt.product_sk,tt.activity_sk on duplicate key update
exitcount = values(exitcount);
set e = now();
insert into razor_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','razor_sum_usinglog_activity',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));


set s = now();
insert into razor_fact_launch_daily
           (product_sk,
            date_sk,
            segment_sk,
            accesscount) 
select rightf.product_sk,
       rightf.date_sk,
       rightf.segment_sk,
       ifnull(ffff.num,0)
from (select  fff.product_sk,
         fff.date_sk,
         fff.segment_sk,
         count(fff.segment_sk) num
         from (select fs.datevalue,
                 dd.date_sk,
                 fs.product_sk,
                 fs.deviceidentifier,
                 fs.times,
                 ss.segment_sk
                 from (select   d.datevalue,
                           p.product_sk,
                           deviceidentifier,
                           count(* ) times
                           from  razor_fact_clientdata f,
                           razor_dim_date d,
                           razor_dim_product p
                           where d.datevalue = today
                           and f.date_sk = d.date_sk
                           and p.product_sk = f.product_sk
                  group by d.datevalue,p.product_sk,deviceidentifier) fs,
                 razor_dim_segment_launch ss,
                 razor_dim_date dd
          where  fs.times between ss.startvalue and ss.endvalue
                 and dd.datevalue = fs.datevalue) fff
group by fff.date_sk,fff.segment_sk,fff.product_sk
order by fff.date_sk,
         fff.segment_sk,
         fff.product_sk) ffff right join (select fff.date_sk,fff.product_sk,sss.segment_sk
         from (select distinct d.date_sk,p.product_sk 
         from razor_fact_clientdata f,razor_dim_date d,razor_dim_product p 
         where d.datevalue=today and f.date_sk=d.date_sk and p.product_sk = f.product_sk) fff cross join
         razor_dim_segment_launch sss) rightf on ffff.date_sk=rightf.date_sk and
         ffff.product_sk=rightf.product_sk and ffff.segment_sk=rightf.segment_sk
          on duplicate key update accesscount = values(accesscount);
set e = now();
insert into razor_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','razor_fact_launch_daily',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));


set s = now();

insert into razor_sum_location(product_id,date_sk,location_sk,sessions)
select p.product_id,d.date_sk,l.location_sk, count(*)
from     razor_fact_clientdata f,
         razor_dim_product p,
         razor_dim_date d,
         razor_dim_location l
where    f.date_sk = d.date_sk
         and d.datevalue = today 
         and f.location_sk = l.location_sk 
         and f.product_sk = p.product_sk
group by p.product_id,d.date_sk,l.location_sk
on duplicate key update sessions = values(sessions);


insert into razor_sum_location(product_id,date_sk,location_sk,newusers)
select p.product_id,d.date_sk,l.location_sk, count(distinct f.deviceidentifier)
from     razor_fact_clientdata f,
         razor_dim_product p,
         razor_dim_date d,
         razor_dim_location l
where    f.date_sk = d.date_sk
         and d.datevalue = today 
         and f.location_sk = l.location_sk 
         and f.product_sk = p.product_sk
         and f.isnew = 1
group by p.product_id,d.date_sk,l.location_sk
on duplicate key update newusers = values(newusers);

set e = now();
insert into razor_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','razor_sum_location',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));



set s = now();

insert into razor_sum_devicebrand(product_id,date_sk,devicebrand_sk,sessions)
select p.product_id,d.date_sk,b.devicebrand_sk, count(*)
from     razor_fact_clientdata f,
         razor_dim_product p,
         razor_dim_date d,
         razor_dim_devicebrand b
where    f.date_sk = d.date_sk
         and d.datevalue = today 
         and f.devicebrand_sk = b.devicebrand_sk 
         and f.product_sk = p.product_sk
group by p.product_id,d.date_sk,b.devicebrand_sk
on duplicate key update sessions = values(sessions);


insert into razor_sum_devicebrand(product_id,date_sk,devicebrand_sk,newusers)
select p.product_id,d.date_sk,b.devicebrand_sk, count(distinct f.deviceidentifier)
from     razor_fact_clientdata f,
         razor_dim_product p,
         razor_dim_date d,
         razor_dim_devicebrand b
where    f.date_sk = d.date_sk
         and d.datevalue = today 
         and f.devicebrand_sk = b.devicebrand_sk 
         and f.product_sk = p.product_sk
         and f.isnew = 1
group by p.product_id,d.date_sk,b.devicebrand_sk
on duplicate key update newusers = values(newusers);

set e = now();
insert into razor_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','razor_sum_devicebrand',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));
    

set s = now();

insert into razor_sum_deviceos(product_id,date_sk,deviceos_sk,sessions)
select p.product_id,d.date_sk,o.deviceos_sk, count(*)
from     razor_fact_clientdata f,
         razor_dim_product p,
         razor_dim_date d,
         razor_dim_deviceos o
where    f.date_sk = d.date_sk
         and d.datevalue = today 
         and f.deviceos_sk = o.deviceos_sk 
         and f.product_sk = p.product_sk
group by p.product_id,d.date_sk,o.deviceos_sk
on duplicate key update sessions = values(sessions);


insert into razor_sum_deviceos(product_id,date_sk,deviceos_sk,newusers)
select p.product_id,d.date_sk,o.deviceos_sk, count(distinct f.deviceidentifier)
from     razor_fact_clientdata f,
         razor_dim_product p,
         razor_dim_date d,
         razor_dim_deviceos o
where    f.date_sk = d.date_sk
         and d.datevalue = today 
         and f.deviceos_sk = o.deviceos_sk 
         and f.product_sk = p.product_sk
         and f.isnew = 1
group by p.product_id,d.date_sk,o.deviceos_sk
on duplicate key update newusers = values(newusers);

set e = now();
insert into razor_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','razor_sum_deviceos',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));
    

set s = now();

insert into razor_sum_deviceresolution(product_id,date_sk,deviceresolution_sk,sessions)
select p.product_id,d.date_sk,r.deviceresolution_sk, count(*)
from     razor_fact_clientdata f,
         razor_dim_product p,
         razor_dim_date d,
         razor_dim_deviceresolution r
where    f.date_sk = d.date_sk
         and d.datevalue = today 
         and f.deviceresolution_sk = r.deviceresolution_sk 
         and f.product_sk = p.product_sk
group by p.product_id,d.date_sk,r.deviceresolution_sk
on duplicate key update sessions = values(sessions);


insert into razor_sum_deviceresolution(product_id,date_sk,deviceresolution_sk,newusers)
select p.product_id,d.date_sk,r.deviceresolution_sk, count(distinct f.deviceidentifier)
from     razor_fact_clientdata f,
         razor_dim_product p,
         razor_dim_date d,
         razor_dim_deviceresolution r
where    f.date_sk = d.date_sk
         and d.datevalue = today 
         and f.deviceresolution_sk = r.deviceresolution_sk 
         and f.product_sk = p.product_sk
         and f.isnew = 1
group by p.product_id,d.date_sk,r.deviceresolution_sk
on duplicate key update newusers = values(newusers);

set e = now();
insert into razor_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','razor_sum_deviceresolution',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));


set s = now();

insert into razor_sum_devicesupplier(product_id,date_sk,devicesupplier_sk,sessions)
select p.product_id,d.date_sk,s.devicesupplier_sk, count(*)
from     razor_fact_clientdata f,
         razor_dim_product p,
         razor_dim_date d,
         razor_dim_devicesupplier s
where    f.date_sk = d.date_sk
         and d.datevalue = today 
         and f.devicesupplier_sk = s.devicesupplier_sk 
         and f.product_sk = p.product_sk
group by p.product_id,d.date_sk,s.devicesupplier_sk
on duplicate key update sessions = values(sessions);


insert into razor_sum_devicesupplier(product_id,date_sk,devicesupplier_sk,newusers)
select p.product_id,d.date_sk,s.devicesupplier_sk, count(distinct f.deviceidentifier)
from     razor_fact_clientdata f,
         razor_dim_product p,
         razor_dim_date d,
         razor_dim_devicesupplier s
where    f.date_sk = d.date_sk
         and d.datevalue = today 
         and f.devicesupplier_sk = s.devicesupplier_sk 
         and f.product_sk = p.product_sk
         and f.isnew = 1
group by p.product_id,d.date_sk,s.devicesupplier_sk
on duplicate key update newusers = values(newusers);

set e = now();
insert into razor_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','razor_sum_devicesupplier',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));


set s = now();

insert into razor_sum_devicenetwork(product_id,date_sk,devicenetwork_sk,sessions)
select p.product_id,d.date_sk,n.network_sk, count(*)
from     razor_fact_clientdata f,
         razor_dim_product p,
         razor_dim_date d,
         razor_dim_network n
where    f.date_sk = d.date_sk
         and d.datevalue = today 
         and f.network_sk = n.network_sk 
         and f.product_sk = p.product_sk
group by p.product_id,d.date_sk,n.network_sk
on duplicate key update sessions = values(sessions);


insert into razor_sum_devicenetwork(product_id,date_sk,devicenetwork_sk,newusers)
select p.product_id,d.date_sk,n.network_sk, count(distinct f.deviceidentifier)
from     razor_fact_clientdata f,
         razor_dim_product p,
         razor_dim_date d,
         razor_dim_network n
where    f.date_sk = d.date_sk
         and d.datevalue = today 
         and f.network_sk = n.network_sk 
         and f.product_sk = p.product_sk
         and f.isnew = 1
group by p.product_id,d.date_sk,n.network_sk
on duplicate key update newusers = values(newusers);

set e = now();
insert into razor_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','razor_sum_devicenetwork',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));


set s = now();
insert into razor_sum_event(product_sk,date_sk,event_sk, total)
SELECT product_sk,f.date_sk,event_sk,sum(number) FROM `razor_fact_event` f,
         razor_dim_date d
where f.date_sk = d.date_sk  and d.datevalue = today 
group by product_sk,f.date_sk,event_sk
on duplicate key update total=values(total);

insert into razor_log(op_type,op_name,op_date,affected_rows,duration) 
    values('runsum','razor_sum_event',e,row_count(),TIMESTAMPDIFF(SECOND,s,e));


insert into razor_log(op_type,op_name,op_starttime) 
    values('runsum','-----finish runsum-----',now());
    
end
;;
DELIMITER ;

-- ----------------------------
-- Procedure structure for runweekly
-- ----------------------------
DROP PROCEDURE IF EXISTS `runweekly`;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `runweekly`(IN `begindate` DATE, IN `enddate` DATE)
    NO SQL
begin

DECLARE s datetime; 
DECLARE e datetime; 
DECLARE col VARCHAR(16); 
DECLARE days INT; 
DECLARE w INT; 

insert into razor_log(op_type,op_name,op_starttime) 
    values('runweekly','-----start runweekly-----',now());
    
set s = now();

-- generate the count of new users for last week

-- for channels, versions
INSERT INTO razor_sum_reserveusers_weekly 
            (startdate_sk, 
             enddate_sk, 
             product_id, 
             version_name, 
             channel_name, 
             usercount) 
SELECT (SELECT date_sk 
        FROM   razor_dim_date 
        WHERE  datevalue = begindate)     startdate_sk, 
       (SELECT date_sk 
        FROM   razor_dim_date 
        WHERE  datevalue = enddate)       enddate_sk, 
       ifnull(p.product_id,-1), 
       ifnull(p.version_name,'all'),
       ifnull(p.channel_name,'all'), 
       Count(DISTINCT f.deviceidentifier) count 
FROM   razor_fact_clientdata f, 
       razor_dim_date d, 
       razor_dim_product p 
WHERE  f.date_sk = d.date_sk 
       AND d.datevalue BETWEEN begindate AND enddate 
       AND f.product_sk = p.product_sk 
       AND p.product_active = 1 
       AND p.channel_active = 1 
       AND p.version_active = 1 
       AND f.isnew = 1 
GROUP  BY p.product_id, 
          p.version_name,
          p.channel_name with rollup
union
SELECT (SELECT date_sk 
        FROM   razor_dim_date 
        WHERE  datevalue = begindate)     startdate_sk, 
       (SELECT date_sk 
        FROM   razor_dim_date 
        WHERE  datevalue = enddate)       enddate_sk, 
       ifnull(p.product_id,-1), 
       ifnull(p.version_name,'all'),
       ifnull(p.channel_name,'all'), 
       Count(DISTINCT f.deviceidentifier) count 
FROM   razor_fact_clientdata f, 
       razor_dim_date d, 
       razor_dim_product p 
WHERE  f.date_sk = d.date_sk 
       AND d.datevalue BETWEEN begindate AND enddate 
       AND f.product_sk = p.product_sk 
       AND p.product_active = 1 
       AND p.channel_active = 1 
       AND p.version_active = 1 
       AND f.isnew = 1 
GROUP  BY p.product_id, 
          p.channel_name,
          p.version_name with rollup
ON DUPLICATE KEY UPDATE usercount=VALUES(usercount);


set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('runweekly','razor_sum_reserveusers_weekly new users for app,version,channel dimensions',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));


set w = 1;
while w<=8 do
  begin
    set col = concat('week',w);

    set days = -w*7;
    
    set s = now();
    
    -- 8 weeks for app,channel, version
    SET @sql=concat(
        'insert into razor_sum_reserveusers_weekly(startdate_sk, enddate_sk, product_id, version_name,channel_name,',
        col,
        ')
        Select 
        (select date_sk from razor_dim_date where datevalue= date_add(\'',begindate,'\',interval ',days,' DAY)) startdate,
        (select date_sk from razor_dim_date where datevalue= date_add(\'',enddate,'\',interval ',days,' DAY)) enddate,
        ifnull(p.product_id,-1),ifnull(p.version_name,\'all\'),ifnull(p.channel_name,\'all\'),
        count(distinct f.deviceidentifier)
        from
        razor_fact_clientdata f, razor_dim_date d, razor_dim_product p where f.date_sk = d.date_sk 
        and f.product_sk = p.product_sk and d.datevalue between \'',begindate,'\' and \'',enddate,'\' and p.product_active=1 
        and p.channel_active=1 and p.version_active=1 and exists 
         (select 1 from razor_fact_clientdata ff, razor_dim_product pp, razor_dim_date dd where ff.product_sk = pp.product_sk 
         and ff.date_sk = dd.date_sk and pp.product_id = p.product_id and dd.datevalue between 
         date_add(\'',begindate,'\',interval ',days,' DAY) and 
         date_add(\'',enddate,'\',interval ',days,' DAY) and ff.deviceidentifier = f.deviceidentifier and pp.product_active=1 
         and pp.channel_active=1 and pp.version_active=1 and ff.isnew=1) group by p.product_id,p.version_name,p.channel_name with rollup
         union
         Select 
        (select date_sk from razor_dim_date where datevalue= date_add(\'',begindate,'\',interval ',days,' DAY)) startdate,
        (select date_sk from razor_dim_date where datevalue= date_add(\'',enddate,'\',interval ',days,' DAY)) enddate,
        ifnull(p.product_id,-1),ifnull(p.version_name,\'all\'),ifnull(p.channel_name,\'all\'),
        count(distinct f.deviceidentifier)
        from
        razor_fact_clientdata f, razor_dim_date d, razor_dim_product p where f.date_sk = d.date_sk 
        and f.product_sk = p.product_sk and d.datevalue between \'',begindate,'\' and \'',enddate,'\' and p.product_active=1 
        and p.channel_active=1 and p.version_active=1 and exists 
         (select 1 from razor_fact_clientdata ff, razor_dim_product pp, razor_dim_date dd where ff.product_sk = pp.product_sk 
         and ff.date_sk = dd.date_sk and pp.product_id = p.product_id and dd.datevalue between 
         date_add(\'',begindate,'\',interval ',days,' DAY) and 
         date_add(\'',enddate,'\',interval ',days,' DAY) and ff.deviceidentifier = f.deviceidentifier and pp.product_active=1 
         and pp.channel_active=1 and pp.version_active=1 and ff.isnew=1) group by p.product_id,p.channel_name,p.version_name with rollup
        on duplicate key update ',col,'=values(',col,');');
        
    
    PREPARE sl FROM @sql;
    EXECUTE sl;
    DEALLOCATE PREPARE sl;
    
    set e = now();
    insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('runweekly',concat('razor_sum_reserveusers_weekly WEEK ',-w,' reserve users for app,channel,version dimensions'),s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));
    
    set w = w + 1; 
  end;
end while;

set s = now();
INSERT INTO razor_sum_basic_activeusers 
            (product_id, 
             week_activeuser, 
             week_percent) 
SELECT p.product_id, 
       Count(DISTINCT f.deviceidentifier) activeusers, 
       Count(DISTINCT f.deviceidentifier) / (SELECT 
       Count(DISTINCT ff.deviceidentifier) 
                                             FROM   razor_fact_clientdata ff, 
                                                    razor_dim_date dd, 
                                                    razor_dim_product pp 
                                             WHERE  dd.datevalue <= enddate 
                                                    AND 
                                            p.product_id = pp.product_id 
                                                    AND pp.product_active = 1 
                                                    AND pp.channel_active = 1 
                                                    AND pp.version_active = 1 
                                                    AND 
                                            ff.product_sk = pp.product_sk 
                                                    AND ff.date_sk = dd.date_sk) 
                                          percent 
FROM   razor_fact_clientdata f, 
       razor_dim_date d, 
       razor_dim_product p 
WHERE  d.datevalue BETWEEN begindate AND enddate 
       AND p.product_active = 1 
       AND p.channel_active = 1 
       AND p.version_active = 1 
       AND f.product_sk = p.product_sk 
       AND f.date_sk = d.date_sk 
GROUP  BY p.product_id 
ON DUPLICATE KEY UPDATE week_activeuser = VALUES(week_activeuser),week_percent = VALUES(week_percent);

set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('runweekly','razor_sum_basic_activeusers week activeuser and percent',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));
    
set s = now();
INSERT INTO razor_sum_basic_channel_activeusers 
            (date_sk, 
             product_id, 
             channel_id, 
             activeuser, 
             percent, 
             flag) 
SELECT (SELECT date_sk 
        FROM   razor_dim_date 
        WHERE  datevalue = begindate)     startdate, 
       p.product_id, 
       p.channel_id, 
       Count(DISTINCT f.deviceidentifier) activeusers, 
       Count(DISTINCT f.deviceidentifier) / (SELECT 
       Count(DISTINCT ff.deviceidentifier) 
                                             FROM   razor_fact_clientdata ff, 
                                                    razor_dim_date dd, 
                                                    razor_dim_product pp 
                                             WHERE  dd.datevalue <= enddate 
                                                    AND 
                                            pp.product_id = p.product_id 
                                                    AND 
                                            pp.channel_id = p.channel_id 
                                                    AND pp.product_active = 1 
                                                    AND pp.channel_active = 1 
                                                    AND pp.version_active = 1 
                                                    AND 
                                            ff.product_sk = pp.product_sk 
                                                    AND ff.date_sk = dd.date_sk) 
       , 
       0 
FROM   razor_fact_clientdata f, 
       razor_dim_date d, 
       razor_dim_product p 
WHERE  d.datevalue BETWEEN begindate AND enddate 
       AND p.product_active = 1 
       AND p.channel_active = 1 
       AND p.version_active = 1 
       AND f.product_sk = p.product_sk 
       AND f.date_sk = d.date_sk 
GROUP  BY p.product_id, 
          p.channel_id 
ON DUPLICATE KEY UPDATE activeuser = VALUES(activeuser),percent=VALUES(percent);

set e = now();
insert into razor_log(op_type,op_name,op_starttime,op_date,affected_rows,duration) 
    values('runweekly','razor_sum_basic_channel_activeusers each channel active user and percent',s,e,row_count(),TIMESTAMPDIFF(SECOND,s,e));

insert into razor_log(op_type,op_name,op_starttime) 
    values('runweekly','-----finish runweekly-----',now());
    

end
;;
DELIMITER ;
