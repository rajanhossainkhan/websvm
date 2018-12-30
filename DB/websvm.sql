/*
 Navicat Premium Data Transfer

 Source Server         : local_db
 Source Server Type    : MySQL
 Source Server Version : 50635
 Source Host           : localhost
 Source Database       : websvm

 Target Server Type    : MySQL
 Target Server Version : 50635
 File Encoding         : utf-8

 Date: 12/30/2018 13:36:12 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `UserFiles`
-- ----------------------------
DROP TABLE IF EXISTS `UserFiles`;
CREATE TABLE `UserFiles` (
  `FileId` int(11) NOT NULL AUTO_INCREMENT,
  `FileNameGiven` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FileName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `FileIdentifier` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `FileType` enum('train','test','model','result','auto') COLLATE utf8_unicode_ci DEFAULT NULL,
  `FilePath` text COLLATE utf8_unicode_ci NOT NULL,
  `UserId` int(11) DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `UpdateBy` int(11) DEFAULT NULL,
  `reference_number` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`FileId`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
--  Records of `UserFiles`
-- ----------------------------
BEGIN;
INSERT INTO `UserFiles` VALUES ('4', '', '1544956574.38_heart_scale.txt.model', '', 'auto', '../AutomaticFiles/1//1544956574.38_heart_scale.txt.model', '1', '2018-12-16 12:07:12', '1', '5c1631e067a46'), ('5', '', '1544956574.38_heart_scale.txt.scale', '', 'auto', '../AutomaticFiles/1//1544956574.38_heart_scale.txt.scale', '1', '2018-12-16 12:07:12', '1', '5c1631e067a46'), ('6', '', '1544956574.38_heart_scale.txt.range', '', 'auto', '../AutomaticFiles/1//1544956574.38_heart_scale.txt.range', '1', '2018-12-16 12:07:12', '1', '5c1631e067a46'), ('7', 'Heart disease', 'heart_scale.txt', '', 'train', '../Uploads/TrainingFiles/1/1545560859.85_heart_scale.txt', '1', '2018-12-23 11:27:39', '1', null), ('8', '', '1545560859.85_heart_scale.txt.model', '', 'auto', '../AutomaticFiles/1//1545560859.85_heart_scale.txt.model', '1', '2018-12-23 11:27:43', '1', '5c1f631fbf100'), ('9', '', '1545560859.85_heart_scale.txt.scale', '', 'auto', '../AutomaticFiles/1//1545560859.85_heart_scale.txt.scale', '1', '2018-12-23 11:27:43', '1', '5c1f631fbf100'), ('10', '', '1545560859.85_heart_scale.txt.range', '', 'auto', '../AutomaticFiles/1//1545560859.85_heart_scale.txt.range', '1', '2018-12-23 11:27:43', '1', '5c1f631fbf100'), ('11', '', '1545560859.85_heart_scale.txt.model', '', 'auto', '../AutomaticFiles/1//1545560859.85_heart_scale.txt.model', '1', '2018-12-23 11:50:19', '1', '5c1f686bb2aa4'), ('12', '', '1545560859.85_heart_scale.txt.scale', '', 'auto', '../AutomaticFiles/1//1545560859.85_heart_scale.txt.scale', '1', '2018-12-23 11:50:19', '1', '5c1f686bb2aa4'), ('13', '', '1545560859.85_heart_scale.txt.range', '', 'auto', '../AutomaticFiles/1//1545560859.85_heart_scale.txt.range', '1', '2018-12-23 11:50:19', '1', '5c1f686bb2aa4'), ('14', '', '1545560859.85_heart_scale.txt.model', '', 'auto', '../AutomaticFiles/1//1545560859.85_heart_scale.txt.model', '1', '2018-12-23 11:50:23', '1', '5c1f686fdce45'), ('15', '', '1545560859.85_heart_scale.txt.scale', '', 'auto', '../AutomaticFiles/1//1545560859.85_heart_scale.txt.scale', '1', '2018-12-23 11:50:23', '1', '5c1f686fdce45'), ('16', '', '1545560859.85_heart_scale.txt.range', '', 'auto', '../AutomaticFiles/1//1545560859.85_heart_scale.txt.range', '1', '2018-12-23 11:50:23', '1', '5c1f686fdce45'), ('17', '', '1545560859.85_heart_scale.txt.model', '', 'auto', '../AutomaticFiles/1//1545560859.85_heart_scale.txt.model', '1', '2018-12-23 11:50:52', '1', '5c1f688c27e83'), ('18', '', '1545560859.85_heart_scale.txt.scale', '', 'auto', '../AutomaticFiles/1//1545560859.85_heart_scale.txt.scale', '1', '2018-12-23 11:50:52', '1', '5c1f688c27e83'), ('19', '', '1545560859.85_heart_scale.txt.range', '', 'auto', '../AutomaticFiles/1//1545560859.85_heart_scale.txt.range', '1', '2018-12-23 11:50:52', '1', '5c1f688c27e83'), ('20', '', 'heart_scale.txt', '', 'train', '../Uploads/TrainingFiles/1/1545562542.26_heart_scale.txt', '1', '2018-12-23 11:55:42', '1', null), ('21', '', '1545562542.26_heart_scale.txt.model', '', 'auto', '../AutomaticFiles/1//1545562542.26_heart_scale.txt.model', '1', '2018-12-23 11:55:46', '1', '5c1f69b297a21'), ('22', '', '1545562542.26_heart_scale.txt.scale', '', 'auto', '../AutomaticFiles/1//1545562542.26_heart_scale.txt.scale', '1', '2018-12-23 11:55:46', '1', '5c1f69b297a21'), ('23', '', '1545562542.26_heart_scale.txt.range', '', 'auto', '../AutomaticFiles/1//1545562542.26_heart_scale.txt.range', '1', '2018-12-23 11:55:46', '1', '5c1f69b297a21'), ('24', '', '1545562542.26_heart_scale.txt.model', '', 'auto', '../AutomaticFiles/1//1545562542.26_heart_scale.txt.model', '1', '2018-12-23 12:30:24', '1', '5c1f71d0ad38c'), ('25', '', '1545562542.26_heart_scale.txt.scale', '', 'auto', '../AutomaticFiles/1//1545562542.26_heart_scale.txt.scale', '1', '2018-12-23 12:30:24', '1', '5c1f71d0ad38c'), ('26', '', '1545562542.26_heart_scale.txt.range', '', 'auto', '../AutomaticFiles/1//1545562542.26_heart_scale.txt.range', '1', '2018-12-23 12:30:24', '1', '5c1f71d0ad38c'), ('27', 'Test File', 'heart_scale.txt', '', 'train', '../Uploads/TrainingFiles/1/5c1f78959b0fd_1545566357.64_heart_scale.txt', '1', '2018-12-23 12:59:17', '1', null);
COMMIT;

-- ----------------------------
--  Table structure for `user_accounts`
-- ----------------------------
DROP TABLE IF EXISTS `user_accounts`;
CREATE TABLE `user_accounts` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_fullname` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_date` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `mod_date` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
--  Records of `user_accounts`
-- ----------------------------
BEGIN;
INSERT INTO `user_accounts` VALUES ('1', 'Rajan', 'rajan.hossain@yahoo.com', '123456', '2018-09-22 16:51:37', '2018-09-22 16:51:37'), ('2', 'Test', 'rajan.hossain@yahoo.com', '123456', '2018-09-22 17:05:06', '2018-09-22 17:05:06');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
