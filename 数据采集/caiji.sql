/*
SQLyog Ultimate v11.24 (32 bit)
MySQL - 5.5.40 : Database - caiji
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`caiji` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `caiji`;

/*Table structure for table `info_person` */

DROP TABLE IF EXISTS `info_person`;

CREATE TABLE `info_person` (
  `id` varchar(32) NOT NULL,
  `iname` varchar(32) DEFAULT NULL,
  `caseCode` varchar(64) DEFAULT NULL,
  `age` varchar(8) DEFAULT NULL,
  `sexy` varchar(8) DEFAULT NULL,
  `cardNum` varchar(32) DEFAULT NULL,
  `courtName` varchar(32) DEFAULT NULL,
  `areaName` varchar(32) DEFAULT NULL,
  `partyTypeName` varchar(32) DEFAULT NULL,
  `gistId` varchar(128) DEFAULT NULL,
  `regDate` varchar(64) DEFAULT NULL,
  `gistUnit` varchar(128) DEFAULT NULL,
  `duty` varchar(255) DEFAULT NULL,
  `performance` varchar(64) DEFAULT NULL,
  `disruptTypeName` varchar(255) DEFAULT NULL,
  `publishDate` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `info_person` */

/*Table structure for table `info_unit` */

DROP TABLE IF EXISTS `info_unit`;

CREATE TABLE `info_unit` (
  `id` varchar(32) NOT NULL,
  `iname` varchar(32) DEFAULT NULL,
  `caseCode` varchar(64) DEFAULT NULL,
  `age` varchar(8) DEFAULT NULL,
  `sexy` varchar(8) DEFAULT NULL,
  `cardNum` varchar(32) DEFAULT NULL,
  `courtName` varchar(32) DEFAULT NULL,
  `areaName` varchar(32) DEFAULT NULL,
  `partyTypeName` varchar(32) DEFAULT NULL,
  `gistId` varchar(128) DEFAULT NULL,
  `regDate` varchar(64) DEFAULT NULL,
  `gistUnit` varchar(128) DEFAULT NULL,
  `duty` varchar(255) DEFAULT NULL,
  `performance` varchar(64) DEFAULT NULL,
  `disruptTypeName` varchar(255) DEFAULT NULL,
  `publishDate` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `info_unit` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
