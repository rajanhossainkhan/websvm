/*
 Navicat Premium Data Transfer

 Source Server         : databases -local
 Source Server Type    : MySQL
 Source Server Version : 50635
 Source Host           : localhost:3306
 Source Schema         : websvm

 Target Server Type    : MySQL
 Target Server Version : 50635
 File Encoding         : 65001

 Date: 21/10/2018 01:23:15
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for UserFiles
-- ----------------------------
DROP TABLE IF EXISTS `UserFiles`;
CREATE TABLE `UserFiles` (
  `FileId` int(11) NOT NULL AUTO_INCREMENT,
  `FileNameGiven` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `FileName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `FileIdentifier` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `FileType` enum('train','test','model') COLLATE utf8_unicode_ci DEFAULT NULL,
  `FilePath` text COLLATE utf8_unicode_ci NOT NULL,
  `UserId` int(11) DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `UpdateBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`FileId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;;

-- ----------------------------
-- Records of UserFiles
-- ----------------------------
BEGIN;
INSERT INTO `UserFiles` VALUES (1, '', 'train_master.txt', '', '', '../Uploads/TrainingFiles/1/1540053665.14_ train_master.txt', 1, '2018-10-20 18:41:05', 1);
INSERT INTO `UserFiles` VALUES (2, '', 'train_master.txt', '', 'train', '../Uploads/TrainingFiles/1/1540053723.3_ train_master.txt', 1, '2018-10-20 18:42:03', 1);
INSERT INTO `UserFiles` VALUES (3, '', 'train_master.txt', '', 'train', '../Uploads/TrainingFiles/1/1540054599.39_ train_master.txt', 1, '2018-10-20 18:56:39', 1);
COMMIT;

-- ----------------------------
-- Table structure for user_accounts
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;;

-- ----------------------------
-- Records of user_accounts
-- ----------------------------
BEGIN;
INSERT INTO `user_accounts` VALUES (1, 'Rajan', 'rajan.hossain@yahoo.com', '123456', '2018-09-22 16:51:37', '2018-09-22 16:51:37');
INSERT INTO `user_accounts` VALUES (2, 'Test', 'rajan.hossain@yahoo.com', '123456', '2018-09-22 17:05:06', '2018-09-22 17:05:06');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
