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

 Date: 23/11/2018 00:59:21
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
  `FileType` enum('train','test','model','result') COLLATE utf8_unicode_ci DEFAULT NULL,
  `FilePath` text COLLATE utf8_unicode_ci NOT NULL,
  `UserId` int(11) DEFAULT NULL,
  `UpdateDate` datetime DEFAULT NULL,
  `UpdateBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`FileId`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;;

-- ----------------------------
-- Records of UserFiles
-- ----------------------------
BEGIN;
INSERT INTO `UserFiles` VALUES (1, '', 'train_master.txt', '', '', '../Uploads/TrainingFiles/1/1540053665.14_ train_master.txt', 1, '2018-10-20 18:41:05', 1);
INSERT INTO `UserFiles` VALUES (2, '', 'train_master.txt', '', 'train', '../Uploads/TrainingFiles/1/1540053723.3_ train_master.txt', 1, '2018-10-20 18:42:03', 1);
INSERT INTO `UserFiles` VALUES (3, '', 'train_master.txt', '', 'train', '../Uploads/TrainingFiles/1/1540054599.39_ train_master.txt', 1, '2018-10-20 18:56:39', 1);
INSERT INTO `UserFiles` VALUES (4, 'Training File', 'train_master.txt', '', 'train', '../Uploads/TrainingFiles/1/1540118505.69_train_master.txt', 1, '2018-10-21 12:41:45', 1);
INSERT INTO `UserFiles` VALUES (5, '', 'train_master.txt', '', 'train', '../Uploads/TrainingFiles/1/1540123680.46_train_master.txt', 1, '2018-10-21 14:08:00', 1);
INSERT INTO `UserFiles` VALUES (6, '', '', '', 'model', '../LearningModels/1/1540123680.46_train_master.txt.model', 1, '2018-10-21 14:29:24', 1);
INSERT INTO `UserFiles` VALUES (7, '', 'train_master.txt', '', 'train', '../Uploads/TrainingFiles/1/1541509507.65_train_master.txt', 1, '2018-11-06 14:05:07', 1);
INSERT INTO `UserFiles` VALUES (8, '', 'train_master.txt', '', 'model', '../LearningModels/1/1541509507.65_train_master.txt.model', 1, '2018-11-06 14:05:12', 1);
INSERT INTO `UserFiles` VALUES (9, '', 'train_master.txt', '', 'model', '../LearningModels/1/1541509507.65_train_master.txt.model', 1, '2018-11-06 14:08:52', 1);
INSERT INTO `UserFiles` VALUES (10, '', 'train_master.txt', '', 'model', '../LearningModels/1/1541509507.65_train_master.txt.model', 1, '2018-11-06 14:10:30', 1);
INSERT INTO `UserFiles` VALUES (11, 'Training file', 'train_master.txt', '', 'train', '../Uploads/TrainingFiles/1/1541866379.05_train_master.txt', 1, '2018-11-10 17:12:59', 1);
INSERT INTO `UserFiles` VALUES (12, '', 'train_master.txt', '', 'model', '../LearningModels/1/1541866379.05_train_master.txt.model', 1, '2018-11-10 17:13:05', 1);
INSERT INTO `UserFiles` VALUES (13, 'Training File Master', 'train_master.txt', '', 'train', '../Uploads/TrainingFiles/1/1542457470.63_train_master.txt', 1, '2018-11-17 13:24:30', 1);
INSERT INTO `UserFiles` VALUES (14, 'Master training file', 'train_master.txt', '', 'train', '../Uploads/TrainingFiles/1/1542457581.2_train_master.txt', 1, '2018-11-17 13:26:21', 1);
INSERT INTO `UserFiles` VALUES (15, '', 'train_master.txt', '', 'model', '../LearningModels/1/1542457581.2_train_master.txt.model', 1, '2018-11-17 13:26:29', 1);
INSERT INTO `UserFiles` VALUES (16, '', 'test_master.txt', '', 'test', '../TestDatasets/1//1542458658.61_test_master.txt', 1, '2018-11-17 13:44:18', 1);
INSERT INTO `UserFiles` VALUES (17, '', 'test_master.txt', '', 'test', '../TestDatasets/1//1542461246.63_test_master.txt', 1, '2018-11-17 14:27:26', 1);
INSERT INTO `UserFiles` VALUES (18, '', 'test_master.txt', '', 'test',