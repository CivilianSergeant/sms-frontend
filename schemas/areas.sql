/*
SQLyog Enterprise - MySQL GUI v7.11 
MySQL - 5.0.95 : Database - plaas_sms
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`plaas_sms` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `plaas_sms`;

/*Table structure for table `areas` */

DROP TABLE IF EXISTS `areas`;

CREATE TABLE `areas` (
  `id` int(11) NOT NULL auto_increment,
  `area_name` varchar(100) NOT NULL,
  `area_code` varchar(10) default NULL,
  `country_id` int(11) default NULL,
  `division_id` int(11) default NULL,
  `district_id` int(11) NOT NULL,
  `lat` varchar(255) default NULL,
  `lon` varchar(255) default NULL,
  `created_by` int(11) default NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `FK_areas` (`country_id`),
  KEY `FK_area_division1` (`division_id`),
  KEY `FK_areas_district` (`district_id`),
  CONSTRAINT `FK_areas` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_areas_district` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_areas_division` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `areas` */

/*Table structure for table `roads` */

DROP TABLE IF EXISTS `roads`;

CREATE TABLE `roads` (
  `id` int(11) NOT NULL auto_increment,
  `road_name` varchar(100) NOT NULL,
  `road_code` varchar(10) default NULL,
  `country_id` int(11) default NULL,
  `division_id` int(11) default NULL,
  `district_id` int(11) NOT NULL,
  `area_id` int(11) default NULL,
  `sub_area_id` int(11) default NULL,
  `sub_sub_area_id` int(11) default NULL,
  `lat` varchar(255) default NULL,
  `lon` varchar(255) default NULL,
  `created_by` int(11) default NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `FK_areas` (`country_id`),
  KEY `FK_area_division1` (`division_id`),
  KEY `FK_areas_district` (`district_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `roads` */

/*Table structure for table `sub_areas` */

DROP TABLE IF EXISTS `sub_areas`;

CREATE TABLE `sub_areas` (
  `id` int(11) NOT NULL auto_increment,
  `sub_area_name` varchar(100) NOT NULL,
  `sub_area_code` varchar(10) default NULL,
  `country_id` int(11) default NULL,
  `division_id` int(11) default NULL,
  `district_id` int(11) NOT NULL,
  `area_id` int(11) default NULL,
  `lat` varchar(255) default NULL,
  `lon` varchar(255) default NULL,
  `created_by` int(11) default NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `FK_areas` (`country_id`),
  KEY `FK_area_division1` (`division_id`),
  KEY `FK_areas_district` (`district_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `sub_areas` */

/*Table structure for table `sub_sub_areas` */

DROP TABLE IF EXISTS `sub_sub_areas`;

CREATE TABLE `sub_sub_areas` (
  `id` int(11) NOT NULL auto_increment,
  `sub_sub_area_name` varchar(100) NOT NULL,
  `sub_sub_area_code` varchar(10) default NULL,
  `country_id` int(11) default NULL,
  `division_id` int(11) default NULL,
  `district_id` int(11) NOT NULL,
  `area_id` int(11) default NULL,
  `sub_area_id` int(11) default NULL,
  `lat` varchar(255) default NULL,
  `lon` varchar(255) default NULL,
  `created_by` int(11) default NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `FK_areas` (`country_id`),
  KEY `FK_area_division1` (`division_id`),
  KEY `FK_areas_district` (`district_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `sub_sub_areas` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
