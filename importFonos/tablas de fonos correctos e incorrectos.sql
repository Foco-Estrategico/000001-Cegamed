/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : foco

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-04-18 13:11:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for fonos_correctos
-- ----------------------------
DROP TABLE IF EXISTS `fonos_correctos`;
CREATE TABLE `fonos_correctos` (
  `IdFonosCorrectos` int(11) NOT NULL AUTO_INCREMENT,
  `Rut` int(15) DEFAULT NULL,
  `Fono` varchar(15) DEFAULT NULL,
  `FechaRegistro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `FechaActalizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`IdFonosCorrectos`)
) ENGINE=InnoDB AUTO_INCREMENT=5285 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for fonos_incorrectos
-- ----------------------------
DROP TABLE IF EXISTS `fonos_incorrectos`;
CREATE TABLE `fonos_incorrectos` (
  `IdFonosIncorrectos` int(11) NOT NULL AUTO_INCREMENT,
  `Rut` int(15) DEFAULT NULL,
  `Fono` varchar(20) DEFAULT NULL,
  `FechaRegistro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `FechaActualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`IdFonosIncorrectos`)
) ENGINE=InnoDB AUTO_INCREMENT=760 DEFAULT CHARSET=latin1;
