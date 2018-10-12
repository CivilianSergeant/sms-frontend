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

/*Table structure for table `billing_addresses` */

DROP TABLE IF EXISTS `billing_addresses`;

CREATE TABLE `billing_addresses` (
  `id` int(11) NOT NULL auto_increment,
  `users_id` int(11) NOT NULL,
  `billing_name` varchar(40) default NULL,
  `billing_address` text,
  `status` tinyint(1) default NULL,
  `token` varchar(40) default NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  `created_by` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_billing_addresses_users1_idx` (`users_id`),
  CONSTRAINT `fk_billing_addresses_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

/*Data for the table `billing_addresses` */

insert  into `billing_addresses`(`id`,`users_id`,`billing_name`,`billing_address`,`status`,`token`,`created_at`,`updated_at`,`created_by`) values (9,11,'Mso Staff','Admin',NULL,'622135f78082662413b50aaecc376f11','2015-11-15 09:07:33','2015-11-15 09:18:45',NULL),(10,12,'LCO Admin','Dhanmondi, Dhaka',NULL,'8db6195fda56efeb7458e3b9efd2761d','2015-11-15 09:16:58','2015-11-15 09:16:58',NULL),(11,13,'LCO Staff','Dhanmondi',NULL,'bf2bea2ff43c1413fa6a143438104f76','2015-11-15 09:27:46','2015-11-15 09:27:46',NULL),(12,14,'Subscriber','Dhanmondi, Dhaka',NULL,'357819d66be982ac9ffda638dc126635','2015-11-15 09:29:14','2015-11-15 09:29:14',NULL),(13,15,'LCO Admin','Dhanmondi, Dhaka',NULL,'817cf94bc8d77208c614f8c6d48023d2','2015-11-15 10:12:57','2015-11-15 10:12:57',NULL),(14,16,'Mehearaz Uddin Himel','1398/5 Riazbagh, khilgoan',NULL,'f27eb29a2fc026bbd53864bd1d23c0c1','2015-11-16 06:56:31','2015-11-16 06:56:31',NULL);

/*Table structure for table `business_regions` */

DROP TABLE IF EXISTS `business_regions`;

CREATE TABLE `business_regions` (
  `id` int(11) default NULL,
  `user_id` int(11) default NULL,
  `country_id` int(11) default NULL,
  `division_id` int(11) default NULL,
  `district_id` int(11) default NULL,
  `region_id` int(11) default NULL,
  `code` varchar(200) default NULL,
  `created_by` int(11) default NULL,
  `is_active` tinyint(1) default NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `business_regions` */

/*Table structure for table `contacts` */

DROP TABLE IF EXISTS `contacts`;

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL auto_increment,
  `users_id` int(11) NOT NULL,
  `phone_no` varchar(20) default NULL,
  `status` tinyint(1) default NULL,
  `token` varchar(40) default NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_contacts_users1_idx` (`users_id`),
  CONSTRAINT `fk_contacts_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

/*Data for the table `contacts` */

insert  into `contacts`(`id`,`users_id`,`phone_no`,`status`,`token`,`created_at`,`updated_at`) values (10,11,'01672927910',NULL,'622135f78082662413b50aaecc376f11','2015-11-15 09:07:33','2015-11-15 09:18:45'),(11,12,'01672927910',NULL,'8db6195fda56efeb7458e3b9efd2761d','2015-11-15 09:16:58','2015-11-15 09:16:58'),(12,13,'01672927910',NULL,'bf2bea2ff43c1413fa6a143438104f76','2015-11-15 09:27:46','2015-11-15 09:27:46'),(13,14,'01672927910',NULL,'357819d66be982ac9ffda638dc126635','2015-11-15 09:29:14','2015-11-15 09:29:14'),(14,15,'01672927910',NULL,'817cf94bc8d77208c614f8c6d48023d2','2015-11-15 10:12:57','2015-11-15 10:12:57'),(15,16,'01738725242',NULL,'f27eb29a2fc026bbd53864bd1d23c0c1','2015-11-16 06:56:31','2015-11-16 06:56:31');

/*Table structure for table `countries` */

DROP TABLE IF EXISTS `countries`;

CREATE TABLE `countries` (
  `id` int(11) NOT NULL auto_increment,
  `country_name` varchar(40) NOT NULL,
  `country_code` varchar(10) default NULL,
  `created_by` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `countries` */

insert  into `countries`(`id`,`country_name`,`country_code`,`created_by`) values (0,'Bangladesh','BD',NULL),(3,'India','IND',NULL);

/*Table structure for table `currencies` */

DROP TABLE IF EXISTS `currencies`;

CREATE TABLE `currencies` (
  `id` int(11) NOT NULL auto_increment,
  `currency_name` varchar(20) default NULL,
  `currency_code` varchar(5) default NULL,
  `is_active` tinyint(1) default NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `currencies` */

insert  into `currencies`(`id`,`currency_name`,`currency_code`,`is_active`,`created_at`,`updated_at`) values (1,'Bangladeshi Taka','BDT',1,'2015-11-17 11:36:00','2015-11-17 11:36:00');

/*Table structure for table `districts` */

DROP TABLE IF EXISTS `districts`;

CREATE TABLE `districts` (
  `id` int(11) NOT NULL auto_increment,
  `district_name` varchar(40) NOT NULL,
  `district_code` varchar(10) default NULL,
  `country_id` int(11) default NULL,
  `division_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_districts_divisions1_idx` (`division_id`),
  KEY `FK_districts_countries` (`country_id`),
  CONSTRAINT `FK_districts_division` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_districts_countries` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `districts` */

insert  into `districts`(`id`,`district_name`,`district_code`,`country_id`,`division_id`,`created_by`,`created_at`,`updated_at`) values (1,'Dhaka',NULL,NULL,1,NULL,NULL,NULL),(2,'Gazipur',NULL,NULL,1,1,'2015-11-17 10:01:57','2015-11-17 10:01:57');

/*Table structure for table `divisions` */

DROP TABLE IF EXISTS `divisions`;

CREATE TABLE `divisions` (
  `id` int(11) NOT NULL auto_increment,
  `division_name` varchar(100) NOT NULL,
  `division_code` varchar(10) default NULL,
  `country_id` int(11) NOT NULL,
  `created_by` int(11) default NULL,
  PRIMARY KEY  (`id`,`division_name`),
  KEY `fk_divisions_countries1_idx` (`country_id`),
  CONSTRAINT `FK_divisions` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `divisions` */

insert  into `divisions`(`id`,`division_name`,`division_code`,`country_id`,`created_by`) values (1,'Dhaka',NULL,0,NULL),(2,'Rajshahi',NULL,0,NULL),(3,'Sylhet',NULL,0,NULL),(4,'Chittagong',NULL,0,NULL),(5,'Khulna',NULL,0,NULL),(6,'Barishal',NULL,0,NULL),(7,'Rangpur',NULL,0,NULL);

/*Table structure for table `lco_privileges` */

DROP TABLE IF EXISTS `lco_privileges`;

CREATE TABLE `lco_privileges` (
  `id` int(11) NOT NULL auto_increment,
  `module_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `can_create` tinyint(1) NOT NULL,
  `can_update` tinyint(1) NOT NULL,
  `can_view` tinyint(1) NOT NULL,
  `can_delete` tinyint(1) NOT NULL,
  `created_by` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_privileges_system_sections1_idx` (`module_id`),
  KEY `fk_LCO_privileges_roles1_idx` (`role_id`),
  CONSTRAINT `fk_LCO_privileges_roles1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_privileges_system_sections10` FOREIGN KEY (`module_id`) REFERENCES `system_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `lco_privileges` */

/*Table structure for table `lco_profiles` */

DROP TABLE IF EXISTS `lco_profiles`;

CREATE TABLE `lco_profiles` (
  `id` int(11) NOT NULL auto_increment,
  `firstname` varchar(255) default NULL,
  `lastname` varchar(255) default NULL,
  `present_address` text,
  `permanent_address` text,
  `country_id` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `token` varchar(40) default NULL,
  `created_by` int(11) default NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_profiles_areas1_idx` (`area_id`),
  KEY `fk_profiles_districts1_idx` (`district_id`),
  KEY `fk_profiles_divisions1_idx` (`division_id`),
  KEY `fk_profiles_countries1_idx` (`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

/*Data for the table `lco_profiles` */

insert  into `lco_profiles`(`id`,`firstname`,`lastname`,`present_address`,`permanent_address`,`country_id`,`division_id`,`district_id`,`area_id`,`token`,`created_by`,`created_at`,`updated_at`) values (1,'super','admin',NULL,NULL,0,0,0,0,NULL,NULL,NULL,NULL),(2,'lco','admin',NULL,NULL,0,1,1,1,NULL,1,NULL,NULL),(19,'MSO','Staff','Dhaka','Dhaka',0,1,1,1,'622135f78082662413b50aaecc376f11',1,'2015-11-15 09:07:33','2015-11-15 09:18:45'),(20,'LCO','Admin','Dhaka','Dhaka',0,4,1,1,'8db6195fda56efeb7458e3b9efd2761d',1,'2015-11-15 09:16:58','2015-11-15 09:16:58'),(21,'LCO','Staff','Dhaka','Dhaka',0,1,1,1,'bf2bea2ff43c1413fa6a143438104f76',2,'2015-11-15 09:27:46','2015-11-15 09:27:46'),(22,'Faysal','Ahmed','Dhanmondi, Dhaka','Dhanmondi, Dhaka',0,4,1,1,'357819d66be982ac9ffda638dc126635',2,'2015-11-15 09:29:14','2015-11-15 09:29:14'),(23,'Sample','User','Dhaka','Dhaka',0,2,1,1,'817cf94bc8d77208c614f8c6d48023d2',1,'2015-11-15 10:12:57','2015-11-15 10:12:57'),(24,'Mehearaz','Himel','1398/5 Riazbagh, khilgoan\r\nTaltola(Talukder Bh-1st floor)','1398/5 Riazbagh, khilgoan\r\nTaltola(Talukder Bh-1st floor)',0,2,1,1,'f27eb29a2fc026bbd53864bd1d23c0c1',1,'2015-11-16 06:56:31','2015-11-16 06:56:31');

/*Table structure for table `messages` */

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `id` int(11) NOT NULL auto_increment,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `sender_users_id` int(11) NOT NULL,
  `receiver_users_id` int(11) NOT NULL,
  `read_status` tinyint(1) default NULL,
  `sender_delete_status` tinyint(1) default NULL,
  `receiver_delete_status` tinyint(1) default NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  `created_by` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_messages_users1_idx` (`sender_users_id`),
  KEY `fk_messages_users2_idx` (`receiver_users_id`),
  CONSTRAINT `fk_messages_users1` FOREIGN KEY (`sender_users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_messages_users2` FOREIGN KEY (`receiver_users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `messages` */

/*Table structure for table `mso_privileges` */

DROP TABLE IF EXISTS `mso_privileges`;

CREATE TABLE `mso_privileges` (
  `id` int(11) NOT NULL auto_increment,
  `module_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `can_create` tinyint(1) NOT NULL,
  `can_update` tinyint(1) NOT NULL,
  `can_view` tinyint(1) NOT NULL,
  `can_delete` tinyint(1) NOT NULL,
  `created_by` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_privileges_system_sections1_idx` (`module_id`),
  KEY `fk_MCO_privileges_roles1_idx` (`role_id`),
  CONSTRAINT `fk_MCO_privileges_roles1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_privileges_system_sections100` FOREIGN KEY (`module_id`) REFERENCES `system_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `mso_privileges` */

/*Table structure for table `mso_profiles` */

DROP TABLE IF EXISTS `mso_profiles`;

CREATE TABLE `mso_profiles` (
  `id` int(11) NOT NULL auto_increment,
  `firstname` varchar(255) default NULL,
  `lastname` varchar(255) default NULL,
  `present_address` text,
  `permanent_address` text,
  `country_id` int(11) NOT NULL,
  `division_id` int(11) NOT NULL,
  `district_id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `token` varchar(40) default NULL,
  `created_by` int(11) default NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_profiles_areas1_idx` (`area_id`),
  KEY `fk_profiles_districts1_idx` (`district_id`),
  KEY `fk_profiles_divisions1_idx` (`division_id`),
  KEY `fk_profiles_countries1_idx` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `mso_profiles` */

/*Table structure for table `organization_info` */

DROP TABLE IF EXISTS `organization_info`;

CREATE TABLE `organization_info` (
  `id` int(11) NOT NULL auto_increment,
  `organization_name` varchar(100) default NULL,
  `organization_phone` varchar(20) default NULL,
  `organization_email` varchar(100) default NULL,
  `copyright_year` varchar(4) default NULL,
  `logo` blob,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

/*Data for the table `organization_info` */

insert  into `organization_info`(`id`,`organization_name`,`organization_phone`,`organization_email`,`copyright_year`,`logo`,`created_at`,`updated_at`) values (19,'nexdecade 2','nexdecade 2','nexdecade 2','0','����\0JFIF\0\0`\0`\0\0��\0�Exif\0\0MM\0*\0\0\0\0\n\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\01\0\0\0\0\0\0\0\02\0\0\0\0\0\0\0�;\0\0\0\0\0\0\0\0 \0\0\0\0\0\0\0\0Q\0\0\0\0\0\0\0Q\0\0\0\0\0\0�Q\0\0\0\0\0\0Ă�\0\0\0\0\0\0\0\0\0\0\0\02015:11:09 05:18:45\0��\0C\0		\n\r\Z\Z $.\' \",#(7),01444\'9=82<.342��\0C			\r\r2!!22222222222222222222222222222222222222222222222222��\0��\"\0��\0\0\0\0\0\0\0\0\0\0\0	\n��\0�\0\0\0}\0!1AQa\"q2���#B��R��$3br�	\n\Z%&\'()*456789:CDEFGHIJSTUVWXYZcdefghijstuvwxyz���������������������������������������������������������������������������\0\0\0\0\0\0\0\0	\n��\0�\0\0w\0!1AQaq\"2�B����	#3R�br�\n$4�%�\Z&\'()*56789:CDEFGHIJSTUVWXYZcdefghijstuvwxyz��������������������������������������������������������������������������\0\0\0?\0�Kmf[�6�P���p�\0�F�wݏ�{�ۃ�ۮG�������P�\r�Hd�o��eS��^z8�$��uT���Ŷ�\n(�����(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(\0��(�������*��\0E5��\0��E4?�C�\0����\'�&�\0�U���j�<�)J��,�e���\r2]>�V�Mi��̻	��8���Vlo~jMl�z�\'ڼ�n�Vb��Tc�>��ۑL��5\nZ�o{�xn��$�������\0�}���U�O�:֛a��&��f�/�..JH�:�I2��\\����\0��V����ks�Zޅ����֗-֦�r��<��#H��2w�w�\r3�\r�\'՗T�n���c�$��b��8�BD��c�N��e�+}G���,��z��+s��7��$򰭂?������x*�M�<{��\r�j�>��Ff��w.cَg!�9�䒿����ו���\0�tv����f�I���%���\\�4��q��lے;\\���/xO��v�w�-�n�If�[H���e��n\'p���|�n`�Bj������!��W3.6����\n���>!�ĺ��}\"}��b�|��M���08�9��U$�$��\0�o�BbԢ�������e���=R�R���a���TJ���1�b,{�9���|�\0«]0j-�hko��d۴p\'\'[��r���B�I�h�5�7�7:���J�H#�2��]���B� 6=��<7��_R��G����5���V�����`���\0��i�~��},��\0_��6�\0���\n����\0�h�\0���\0�*h�.��\0����@s�\0��x?��M�\0��\0�4�	��\0�4?�C�\0��AE\0s�\0��x?��M�\0��\0�4�	��\0�4?�C�\0��AE\0s�\0��x?��M�\0��\0�4�	��\0�4?�C�\0��AE\0pz�<+6��t��:3�:�$J���Kv���\r���\'�T��{���-\'�^�r�[�������B[hw݂y������=�\0!��U?�ֹO�g]�����4�\0�îi)`%\"�i����\\��1��I��Z?�j��IuH��]٥߁l����-���o�y�>PA����=k��\0���\0�*h�.��\0��Z�L���Kao��\Z�΁�yF�-f��s2�<�w̬�\0\n��}+�j����������s�\0��x?��M�\0��\0�4�	��\0�4?�C�\0��AE!��\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0� ��\0�SC�\0�t?�M��x?��M�\0��\0�5�Q@�\0�?�xg��V��)k�����O<3�\0`�_���P?��\'�&�\0�U���j?����\0��\\�\0�6_��G��\0�x���W_�)�}���R���}�j�� h�\0��o⻘�r�:��r:�Cft�k��\0�{S�G=s����=y�{�+���~�7�-.����4�[c��	������?�s��������\\�ro��Rw�`�Kd1+����sM�y~#����;صC{�X�zO��Y�Z]݋P�Z}���H.᭔�9j�Ґ�s_çx�\\�K���e�_.A�~ka��ȯ\'�<-ug�X/��#�-<I��.� &﵌�0�pH\0��_���=��-$��U[�o����`�4ê��v�zm\r��]%~�����k[.�\0��H���G�O�����e�\0���}k����@����C�ިkxZ��	 |�e�	*F	�b��#�N�g���Q�e�2�.r���wʳ�s໯�L���k����&���Y>~����M�V����m$������16�/��r�ܽ��\0�֋�U�����q�����?�s�?�͗�\0#אh6�!���˭�D��]���7�l�1�\Z0\Zo!9�yp1�{�����?�lt�t�A���8E��*Z����H�X�x��4%woO�����\0?���d�\0�{T�\0��\\�\0�6_��G�#ڧ�z�����\0�z��O�7��v��~�v��x�9t�&ѳ9�ʙ%x�g���ֆ�s>���mf-p]Eym��u4�pV�\\\"ð���})G޷����\0\0z_���~\'��=���ߛ/�G���S��=s����=tP?�\0���\0C���\0~l����G�O�����e�\0���Q@�\0�#ڧ�z�����\0�z?��?�s�?�͗�\0#�AE\0pz�����u_k1��4Ve��2�[�͘�`aG�;���j��9�����\0������<Y�\0aT�\0�+Z�o��m�{=K_��4��<k\r�ۡ�8S�H���g����ݿ�+�+�\0^v;_�G�O�����e�\0���=���ߛ/�G�÷\Z���]\rV�QY��Q_=����p%`�@�Apq���V�P����]뷓Κ��^H|��m���[hR� ����ݯ�^��K�\0[_��tɬX�2ƿ<Bwދ��>�˒|�د�M�ʜ�H�\"�4�iu����Z�d�{Y�\0Ѭ�d����a�g�����m\'�~����u�&p.�!��6�0p2�<��IuKOҵci7�e/��#���5�H�0\Zd�R�Aa��J:������RV�������?�s�?�͗�\0#��\0���\0C���\0~l������T��qK\'��Ƕ�Wy#h���Knwٚ�T0pI�<��zO�/�U�|�\\�g�G��t���M���G={Ӷ�\0����R��#ڧ�z�����\0�z?��?�s�?�͗�\0#�AE!��\0�#ڧ�z�����\0�z?��?�s�?�͗�\0#�AE\0s�\0��j��9�����\0���\0�{T�\0��\\�\0�6_��]��\0�=���ߛ/�G���S��=s����=tP?�\0���\0C���\0~l����G�O�����e�\0���Q@�\0�#ڧ�z�����\0�z?��?�s�?�͗�\0#�AE\0s�\0��j��9�����\0���\0�{T�\0��\\�\0�6_��]��\0�=���ߛ/�G���S��=s����=tP?�\0���\0C���\0~l����G�O�����e�\0���Q@�\0�#ڧ�z�����\0�z?��?�s�?�͗�\0#�AE\0s�\0��j��9�����\0���\0�{T�\0��\\�\0�6_��]��\0�=���ߛ/�G���S��=s����=tP?�\0���\0C���\0~l����G�O�����e�\0���Q@�\0�#ڧ�z�����\0�z?��?�s�?�͗�\0#�AE\0s�\0��j��9�����\0���\0�{T�\0��\\�\0�6_��]��\0�=���ߛ/�G���S��=s����=tP?�\0���\0C���\0~l����G�O�����e�\0���Q@�\0�#ڧ�z�����\0�z?��?�s�?�͗�\0#�AE\0s�\0��j��9�����\0���\0�{T�\0��\\�\0�6_��]��\0�=���ߛ/�G���S��=s����=tP?�\0���\0C���\0~l����G�O�����e�\0���Q@�\0�#ڧ�z�����\0�z?��?�s�?�͗�\0#�AE\0s�\0��j��9�����\0���\0�{T�\0��\\�\0�6_��]��\0�=���ߛ/�G���S��=s����=tP?�\0���\0C���\0~l����G�O�����e�\0���Q@�\0�#ڧ�z�����\0�z?��?�s�?�͗�\0#�AE\0s�\0��j��9�����\0���\0�{T�\0��\\�\0�6_��]��\0�=���ߛ/�G���S��=s����=tP?�\0���\0C���\0~l����G�O�����e�\0���Q@�\0�#ڧ�z�����\0�z?��?�s�?�͗�\0#�AE\0s�\0��j��9�����\0���\0�{T�\0��\\�\0�6_��]��\0�=���ߛ/�G���S��=s����=tP?�\0���\0C���\0~l����G�O�����e�\0���Q@�\0�#ڧ�z�����\0�z?��?�s�?�͗�\0#�AE\0s��\0�y��Z�\0襮���\0�<������R�A@�\0��\0�x���W_�)��\0��T�\0�3\\�\0��_��G��\0�x���W_�)�}���Rn��`�C�Й���/�H��S���s��\0Y�Ep	�ou=�R� ����?�L%ՙ��R�\r�\'��Y��A���]J�]&��k��\"��4\rB�L�p�2�g�F��?�կ����\0�!�?�L�?����\0$U{�R�R�����V����R*�١*F�܂8�5�ZN��\\�·�Զ����H��y���%�����5��x��ZM��5x���(t�fE���X��\r�Q�q\Z��\\�hi[_�o�4����\0#�4���L�Ӵ�\0kpZ[ �(��gj�ss��5o�S���s��\0Y�EP�w��<]�kS�-��md�9��>��s��0+\\��/��:4�����#N�Z�Mn�A#\0߻e������%k;t�\0;W�$:��	������\0�?�!�?�L�?����\0$W���5��m�mF�=}�MB���SgH�i�0$�I띩|{���V�xr�Κ��Z��&�\'ٹ?h�1�������ח�o�Af��������S���s��\0Y�E���&k�����\0�+�����^�\ZV�}��ϩ��VԾ�2±�Op����u�\"���G�c�Ϳ�-#���\'O�d�ʓF����3����\'� `�j�����t�\0���&k�����\0�(�\0��T�\0�3\\�\0��_��^ac��-P��n�o5�O��f�[wY2}�L/���Y�_����oiw�x����lc�4�$V+�y���ܛ�x�c������V���\0�\0�=O�S���s��\0Y�E���&k�����\0�+�.�_s�?x��i�\Z�f�s�\Z�$��έ#U�\\О���O�� �^$���L��t�m�`��	WT*��q���GD������\0�C�Й���/�H��S���s��\0Y�Eex_�:��\0��j��F����9�ax��!����A��ւS���Zǉ�|\'��d�љV[<�~�n6�g8\0�� O�����H����\r�ضy�^���?;��j�����<Y�\0aT�\0�+Zz�Gǒi�_�1�,�=��Ys�g�g-���������C�Й���/�H��S���s��\0Y�EeI���3H���X&?��٬�S}��Vm�I6��PH9,<景|C�D�;V\Z9�[%��.5u�X6p�3\'�I��B��3�M5�k��������\0	��\0Bf��\0���\"��HuO�5���e�\0��-��me�i�\\$�Q����\n�nH��:u��s�^�F�F��$����V^�W�o� �ls��;����9v��\0	��\0Bf��\0���\"��HuO�5���e�\0�.��^��Z~�a�Gwuz���n|��ٴ��kݔ��Fd^1��]6-���b�)�����\n�}���8!rx�d�{�S���s��\0Y�E���&k�����\0�*����z�Lm��\0�5غ���\03��\"ٍ�g˝�㝵�k�k�K�_�#x��b�6��*.�j�$^Z�6Km�w0�B��\0^_�O������S���s��\0Y�E���&k�����\0�+��\0��h�m��͓]\\jW�[j���aH���U�~e@鞘��|P�����<�D��2!*L��ea����X�z~ �v,�\0�C�Й���/�H��S���s��\0Y�EQ�\0����ͣ*�S:c���2�Q\\G�m,$�@��Υ��x�X�m̲.��6�E���]��H�l�\Z�%�[=9�[����_�gE�\0	��\0Bf��\0���\"��HuO�5���e�\0�F��7�l:�z����Y,,�~bO��J�\"a��x[�떓�f����Y}�e\Z���hs��h#��H�ܛHve��HuO�5���e�\0��C�Й���/�H��w�.���+���Zsj6!.��XԮU�\n��Ο�����+ψW�����4��4�b�β���!�Ἧ�\n�q�j�ɺ�Σ�S���s��\0Y�E���&k�����\0�*��\0�.��BD��cp,�)g´M<��C�jܝ�bq����n<c{�ũG�hȷ�B�A�ߚ�G+�R�Ș`����<%������!�?�L�?����\0$Q�\0	��\0Bf��\0���\"��u���GP�u>+;�?-Ȇ�����Qr��n�����\0��T�\0�3\\�\0��_��G�$:��	������\0��(��S���s��\0Y�E���&k�����\0�+���9�\0�HuO�5���e�\0��C�Й���/�H���\0��\0�!�?�L�?����\0$Q�\0	��\0Bf��\0���\"�\n(��\0��T�\0�3\\�\0��_��G�$:��	������\0��(��S���s��\0Y�E���&k�����\0�+���9�\0�HuO�5���e�\0��C�Й���/�H���\0��\0�!�?�L�?����\0$Q�\0	��\0Bf��\0���\"�\n(��\0��T�\0�3\\�\0��_��G�$:��	������\0��(��S���s��\0Y�E���&k�����\0�+���9�\0�HuO�5���e�\0��C�Й���/�H���\0��\0�!�?�L�?����\0$Q�\0	��\0Bf��\0���\"�\n(��\0��T�\0�3\\�\0��_��G�$:��	������\0��(��S���s��\0Y�E���&k�����\0�+���9�\0�HuO�5���e�\0��C�Й���/�H���\0��\0�!�?�L�?����\0$Q�\0	��\0Bf��\0���\"�\n(��\0��T�\0�3\\�\0��_��G�$:��	������\0��(��S���s��\0Y�E���&k�����\0�+���9�\0�HuO�5���e�\0��C�Й���/�H���\0��\0�!�?�L�?����\0$Q�\0	��\0Bf��\0���\"�\n(��\0��T�\0�3\\�\0��_��G�$:��	������\0��(��S���s��\0Y�E���&k�����\0�+���9�\0�HuO�5���e�\0��C�Й���/�H���\0��\0�!�?�L�?����\0$Q�\0	��\0Bf��\0���\"�\n(��\0��T�\0�3\\�\0��_��G�$:��	������\0��(��S���s��\0Y�E���&k�����\0�+���9�\0�HuO�5���e�\0��C�Й���/�H���\0��	�\0$��?�\n��\0�K]s��\0�y��Z�\0襮��9�\0�\0�<�7�����SQ�\0	׃��\0��C�\0��_�U;�\0�y�o�]覮��<�Ho�Z��<Mcm30�|F����ʹ�=����aҢ��t4M*g��l�;���O�>��C��z�n��	��U��Wb�8�O����S�ڼ��\0{S﮼\0��w:_�t�5HdYR]om��ɷs���H\\���E��I�\r7{�u�O��e���-�������b*�1��9�㠫\Z���]cW�V��&�5��:�[��݄y\'o��^2Mgk_��߉iY@��xl��YN��1@lc����=8��~#�~$��g�F�����I�v�wL2��\r��3M˛�~���#������/��\0[A����԰�u��R�\Z7S��������������\r@8�U������&��\'n���v�c�g�_N����z�ƃ���W��;t�f(���Y�9��3��b���ڽ���g�5K�.,#�xmnt�W�n��vX� ��Q���{1G[	?\n��cf�·\nX;=�����hٳ��R@�l��j}>�ᦙ�E����F�[���&�$o-��t�$�\'\'\'��ۏ��V�k�j����ޟ3��yRF\n6�T����FzU�N�\0�~�]�Ҭd�Tҡ�gk�S\0YY@\'����������ax�N�F�mg��Y���T�/��i;�0bU�x��=�{o¯왴ӭh��k�y#dZpr$2���g���\0���b�D�$�#�7�V�KA��I�n8PŰ9��/R��4�]J�O2��1,O�2�dpy�m?�ߧ�����k��>߅E5%�Z�\'�Ӎ\"�k�dL҄BY�$�პs�k��m>��}oF�-A���}o̖]���)|��@q���ûj�X�����1$3K���r\'\n0_´�\0�;��\rz�a�\0⫠��8=ƞ�X�;��]mM&k��u�%��~nF�a��ڤ�u�j7�jx�Oӯ������Z2A(� u# �py9�����<Y�\0aT�\0�+Z��x�R�Ʃ�/H�����\0��no��l���9��G_���}��<o�G���?��č,��h�N�Ir�b��$��=�8����k%����힔���hZ�R�o64$�a(q�K��}���|@�n���uku��sd��q,2D@����A���2��O��t�/��-������^E}R�`�\0�Lm��r@S��������O������\n�V:��%�Z���`�Ub��#�X�{U}w^���l֒��O���6��{�M��pCVa�G�T����?�z6��\r>��F�h�щ-�f���d^G*H�=+3R�妕�d��ŕ�Cd�ou�ڨr��8��\Zh�V�\0Q����1u�s�7��%����,��MŮ�l\Z2���X0 0�R;�pj杩xLm=��~��d�a���&V��#�<�#<`rx�\0ۓ������m�亷e�F�i��r0=q�׊,<o��Ni\"�ԗ)nn��č�Ȯ��w*Hm��#��G�\r���#a����\0f�N���oݜy����߷=��Z�U��W���\r(G{v���������ǯ~k�ѼU�k�\Z}̍2ĳ�s[�4m�]D��������c�)<-�O�����Ad.�uzm��b�8F������_֟�o���k��Iw�o9nm<wecx��2���Z�f��I\nyRF��\\ߺ��o�U�o|o�N#5�j6�#�p���^�:(��cV������0�	.�����W\0��@5����]K��.|���=/����Y|�*;Q2q��8<�h������7����(�W�fѭ��0Ҷ6�5��Ϙ$c������\'��qa��?�����_kiT�<R��Yx�QH�y�q[z��|?�N ��f��E⥽������������z��S�熴�\"��Q&)-��<��& +��[j�pc=�Zh���߀n��Z�\'>.�$7�l�Ս���C��ͩZ�6���\0@��\\��\'�4�����no_��@���Y�\Z�	�9����U��q��ll�땛J�����LF����Q|���\0\rݫk¾2��Ekg��%�wr��I\Z��nh�:�8ʖ�sE�����\0�����\0\0¹���ݼM��1��K�K\0j�\r�>̒A�߻��T*�x�1y��K�5D��Y�+UdD,W`EU=�l�\"�wo��������h�?���-��^���{����N��2$�f\0p���du�ׁd���:����h|۹�+Q&؛z �������ǡ�HJx&\rb�S_��k�����P�2�c���9�Ү�\0�w��\0�\Z�?���\0�WAE\0s�\0��?����\00�\0�T�w��\0�\Z�?���\0�Q�O�\'��\0�U���Z��7�׵�;�f�}-��%��C,x/\"Fc\np�8.:\Zfo�\0�w��\0�\Z�?���\0�Q�\0	߃�\0�k��\0�c�\0L�Ǟ\Z�H���ą̋�B_�~c�+l�#����]&MrM.�b!d�4b����v#����A7*�\0�w��\0�\Z�?���\0�Q�\0	߃�\0�k��\0�c�\0P�\0��𸵸�:��X3�᠔D�H#%n�;(%	#8��x��Mzl��ǣǤ��V[K��B��d� ^\0|ǨȠ�2�\0�\'~�\0��C�\0��?�U��?����\00�\0�UN_��67��\\Kw-��^$Km2��7!)�x,���U4�ɮ\\x^H��\rZ�[k�gTV�O�#(�����k�z�\0���N��\0C^��\0���?�;��\rz�a�\0⨰񷇵;���/��3H�9�E�r�#��$�s�I�i�_�|9��Z�Xj\r+݆6�m�D�h�*�(Rú�#Ҁ�w�\'~�\0��C�\0��?�U��?����\00�\0�U�Q@�\0�\'~�\0��C�\0��?�U��?����\00�\0�U�Q@�\0�\'~�\0��C�\0��?�U��?����\00�\0�U�Q@�\0�\'~�\0��C�\0��?�U��?����\00�\0�U�Q@�\0�\'~�\0��C�\0��?�U��?����\00�\0�U�Q@�\0�\'~�\0��C�\0��?�U��?����\00�\0�Tx�I��k�\0����~&��څ��0�6�Y�ȒD�&vm�C�^^���օ���������ht���?����\00�\0�T�w��\0�\Z�?���\0�Sǌt�e��nM��\r�I��ɋ��������x�j��|/\r�[I��t�k7co.ęs������O=�@�w��\0�\Z�?���\0�Q�\0	߃�\0�k��\0�c�\0N��zŃ��u3�w١�y�P2S���n�m�	�*�������a|�%�q���H��]|���G�F}�4\'�\'~�\0��C�\0��?�U��?����\00�\0�U���\0�����j�����ͅ�QE!i,�9����G�t\Z/�����i��4j��F����������:ة�\0	߃�\0�k��\0�c�\0G�\'~�\0��C�\0��?�UV����>\"��&�8K[kI/5 [po�$�;��\n�]�����t�\"��H��Ԟ[��n�ʕ���rHJWV����c�����ѡ�\0	߃�\0�k��\0�c�\0G�\'~�\0��C�\0��?�UP����ˡiڕܓڵ��\r���g� p]��;S=��R?�|����ٮ��(���<�\0�+��۳o=?\Z�4��i�\0$����N��\0C^��\0���?�;��\rz�a�\0�+^����:��Z�uus`����k5�����������\"�Y��n|G��sim�e}��s��G�*���(�\0z����/��������?����\00�\0�T�w��\0�\Z�?���\0�S-<w���g�+�a�7�ε�\"�\0I��2/W=�EX�|[�k��d��\ZY�\"�A$bH���,�:猩\"� \"�\0����\0�5��1��\0�����\0�ס�\0���*�\n(��\0����\0�5��1��\0�����\0�ס�\0���*�\n(��\0����\0�5��1��\0�����\0�ס�\0���*�\n(��\0����\0�5��1��\0�����\0�ס�\0���*�\n(��\0����\0�5��1��\0�����\0�ס�\0���*�\n(��\0����\0�5��1��\0�����\0�ס�\0���*�\n(��\0����\0�5��1��\0�����\0�ס�\0���*�\n(��\0����\0�5��1��\0�����\0�ס�\0���*�\n(��\0����\0�5��1��\0�����\0�ס�\0���*�\n(��\'����\0�*��\0E-t���I��k�\0���\n\0��w�\0$����\n��\0�M]s�;�\0�y�o�]覤o�j���z�\0d����\0�����+P��n-��߉ܡ���i�@����KE���O����G�O�����e�\0���Q\\5�^��٩�_]K���lA$)a�bJ��:U�>��R��-�����&Xe2[؀X����>q�q�h�61����u�V����kP�{���������bB�c���������Ʃ}&���i��jH�gt�c(^6�>L�pCV��#ڧ�z�����\0�z�h��]=��w�$�Z�$7ٶ��@r8�QJ�[��ߐ���{�f������³]k�N�Y#�\"�1�Q�/�L���r�Q��\0?�\'�T��>G��ٸ�.�#�#���8�ǽkXZ^j���>0�\nɧ� �� 3W���a�\\s���\0���\0C���\0~l�����\0Z�%�����/�-�ǭK��k.��Z�i���Tv�+��%���b���eư�!�Uף�}f�+bm�LK	�������#>���O�#ڧ�z�����\0�z?��?�s�?�͗�\0#�������j��{]���7Z5��������ȫ�����G\0`��A]���\'F��f�����X#?�Ƽ(���\0���\0C���\0~l����G�O�����e�\0���+��\0�=���ߛ/�G���S��=s����=!���\0�=���ߛ/�G���S��=s����=\0�\0��?�*��EkY����\'�X�O�C���4��n��\r��ݕ��:�[Bе�<N���f3��̱Y�C�Ks����(�9\'_���F���c�<���s���>q�3G_�t9_�W�!��|�R+�����R�/\'���&ۆV�e@1���\\��.��-s@����7Kc���rL��7HI�w㟕��k�}Q��ƺڢ��4V ��\0�V[	��F�޲R��;vH��JXqo��\'=03G�����������<�+/�:Ưy�gk4�u����:��!��^>����\r�N�%��e�;\'���iZ6Ug\r�0������,Zl���e��Y.��E�/a�ܿg��鞵�v���/���d]n~���(�)U�\'p��3�Gg�����]�\0��u��+\'[�>���^i�j�C�K*�pH_µ���,��ˍR{+[e�鰵kgwv2���\0�\0|��y�Wq��O\nM�u��E�!����ޟ�\0���\0C���\0~l���Yr���\Z}�^���\0+~FW��%q���y����{�����]�\\�TG2��H�8�X�\'�{�j�#Ե[9�$����}����>��*�Q��䒹\\�9�G]�\0���\0C���\0~l����G�O�����e�\0��ۻ�����F��R��֯�˛�Y�n��R�Y��f�D��~C��Wp�����j	wcr���\0cC���V��KG�<JK����s]w�#ڧ�z�����\0�z?��?�s�?�͗�\0#����P��\'�����ǭ5͢�[�dEn&w1��G%rP����5�-���ԺV�u�=�������j;�H�B�6H���dd��^��\0���\0C���\0~l����G�O�����e�\0��]��\0�_�a����\0$s�>կ��6q�Gm�-!-�]���k ����.~`G<\Z��xF�\0O���K5�E�h�҄f��eW���)���V��#ڧ�z�����\0�z?��?�s�?�͗�\0#�Z=?��\0͋ug�m�H�(���S��=s����=��j��9�����\0���(���S��=s����=��j��9�����\0������\0�G�O�����e�\0���=���ߛ/�G����O<3�\0`�_�������c�\"�#�׵���O#IR�H�|����`d�s{���7�|=*x�Y�L�e�8�ʠ1/�7@NNI>�ո�2ꭥG�*��$E}<�#g����wi��*�e}o\r�Z��x��`�Y��)�B\"R��H\'�[~�f��oo���&of���=�B\"�9 ǖH\'�7g���[�\"��=׎�x!$�b�	 �oܐ>���\0�{T�\0��\\�\0�6_��C�q-699<�I>��|�$C$v�^t��2�.�?w��/�z�9��O݋���̇����c�q�7�8�6��>���\0�=���ߛ/�G���S��=s����=)^I��\0a���uO�8�xKV׬4}GR��x�<lm~����j��\0.�9�U�/�\Z����az�Ŭ����ʯ���5��9�����?�s�?�͗�\0#��\0���\0C���\0~l���M6��?�Ɋ�^_��gF�6�o���Fm<i�����GinH��FP#�l3d�1����t��5ś6�qq-�GbH\r�^O�3�V톙w���{g�}rKy�to�{1��6٫��j��9�����\0���Gn�����Q\\�\0�#ڧ�z�����\0�z?��?�s�?�͗�\0#�����\0�G�O�����e�\0���=���ߛ/�G�����\0��?�s�?�͗�\0#��\0���\0C���\0~l���:\n+��\0�{T�\0��\\�\0�6_��G�#ڧ�z�����\0�z\0�(���S��=s����=��j��9�����\0��\0�\'����\0�*��\0E-q��o��Sլ�M��^}N���b3�v\n�ʌ��Z�е���SŚ��e�,Q�fU�~Q�p:rI�&��!�Um*?�W��� �+��P@�<��=�Z;��\0��k=���1�χS�k}��\rB��U�%}f�#	-���1�)�9����M�����-⡭���no����=�q�dH�V����k7��~�\0�;v���:�կ�G�O�����e�\0��GK[��� z��\0���g5w�����X�@����x����jf�DcK\Z�C��������_G�4+���6[����M#�\0m\0����c�s]_�#ڧ�z�����\0�z?��?�s�?�͗�\0#�wo�D�@z�\0^��r:gíkF�}׈쮬Z{�B����ѽ��0��Ċ�8�j��_�u/j�#Ai��$\Zm�ܷF�y��\r��.G�j�=���ߛ/�G���S��=s����=EdWvg�\Z���5����os�^�sF��o�*�H��\0j�x���>(񬺖���c��`��2�1���r00	�9���G�O�����e�\0���=���ߛ/�G�����Xi�?�ŧ���ŭ������鋧�ĺ�Ւ��;_|hK�S�+.3��kZ��\Z�z��h�6�b��F�jG���>�W�������\0�{T�\0��\\�\0�6_��G�#ڧ�z�����\0�z����bZ]��H�f�c�_������)5.+H��7�H��-$��)=��<�gP�6��j7w\Z��v�w��ҝm�yJHd,��q�C�{�C�\0���\0C���\0~l����G�O�����e�\0��=t���������\0$r�x\'�\Z�ڣ����s閫k4�,�J�L�Y���\nO<`�����C�j�l�i��t��4�C��~��y8>է�\0���\0C���\0~l����G�O�����e�\0�������[K]?���\0�=���ߛ/�G���S��=s����=!���\0�=���ߛ/�G���S��=s����=\0tW?�\0���\0C���\0~l����G�O�����e�\0���Q\\�\0�#ڧ�z�����\0�z?��?�s�?�͗�\0#�AEs�\0��j��9�����\0���\0�{T�\0��\\�\0�6_��@��\0�=���ߛ/�G���S��=s����=\0tW?�\0���\0C���\0~l����G�O�����e�\0���Q\\�\0�#ڧ�z�����\0�z?��?�s�?�͗�\0#�AEs�\0��j��9�����\0���\0�{T�\0��\\�\0�6_��@�?�xg��V��)k�����O<3�\0`�_���P?��\'�&�\0�U���jټ�!��IQ2Y����������*��\0E5���t-��f_�E�%t�������K>��^����̱i�Z}�Y!*\0EEm��yc��&��Ae�i�N��M�}�	M�6�v˩<�%�A%�\0�:�\'�ߎ�_����+��7yX�e�7u��/z#����|�\n�m|��V5Y~M�q��ޔ�����\0��װV�OO/��|��.�lXk��pͬA�{x^��d�neR��APprF:՝Z)�k&Q\n�G��I}ͣ\\�BmT�*��/���z�pv�(�5�|�/¾_#w��V_�w\\���;M~\'������cU������nM�O��?q��vK��_�Y|%-��ᄷ��\"ų32��(�Lk�31�p@Uc�\0�s~.��߈�M�l�:z�?~!q!�?rc$m�Cc�jO��۝�h�/�fػWͿw�$�N�$�4�>\rLC�|/�O.��T6���8qů ����n�\0���u�	[_����_�V�;o�M�a�C��������Y\0�$7P;�o\\��^#�����E�T٫�0����@�?l��\0�?���\0ȵWv��|��N�������h�9��\0�h�g�?���\0��o�E������\0�x���Z�f�\0�Z>���h�9��\0�h����\0�x���Z�f�\0�Z>���h�9��\0�h����\0�x���Z�f�\0�Z>���h�9��\0�h\0����<Y�\0aT�\0�+Z���A�rhdh��.��pT�r=�&�u⡬x�ţh��SC(mZU\n�d��Oَ�i��F8ɷqk��E�ӢRw�q��d��������J�����S_��>V��m�����2ݛ��1jz�Β^JY�FL2a���0���O���I��7���Z\\;1�i���r�H 4�/����_�x��}쿿�\0!���=z��=���^+k�Y����d��\\#>�)\"M���ׅeb\n�:cJN�o�o��\0���v��e�_�[I��5ĭ^���IT�O�/��,`4�I�U�,2K��Ԟ�4�w��:]��,MuAok4dV�%ʪ�����߶x���Z�f�\0�Z���_ycqjt}!4m�-jP鑌�mH���>V�nF��\0�bo�|�rJ�p��z�$/�:t5�؏i�}�����(��X�A����\0���}�����\0�s7�\0\"�=�[�������\0�s7�\0\"����C�\0�����HgAEs�\0l��\0�?���\0ȴ}�����\0�s7�\0\"�AEs�\0l��\0�?���\0ȴ}�����\0�s7�\0\"�AEs�\0l��\0�?���\0ȴ}�����\0�s7�\0\"�AEs�\0l��\0�?���\0ȴ}�����\0�s7�\0\"�AEs�\0l��\0�?���\0ȴ}�����\0�s7�\0\"�AEs�\0l��\0�?���\0ȴ}�����\0�s7�\0\"��O�\'��\0�U���Z崭\'R�\'����m�I%�33O�9mX�`d�n�О*�n|S�\0��\rG���2�F�jң2�K�T[��8�5?�<g�\0>�_��\0����\0��\0������Թ�����cE�J���~�|��4MoV���Z�usmn�	_jA�P�3����qP��������\"k!��:��Ҕ(��\'A`q�01���\0Ͻ��?�;/���ǯ_���\0�`����s�C��\0����z��\0o�����5u��O���-Ӧ����G��_�1.uS���m���Z��}�	n�Y-������WsrN��	����>��ԯ��ɨ��f��R9@�F�`0�1�=H;�`����s�C��\0����z��\0o�����\0Ͻ��?�;/���ǯ_���\0�5)v���K��\0���[J��$�b�ZK���ʹ��i��3[�0#1��20��=k�=\ry���R���(m\"��E��]u�,JT�=2N㓒y��B��S�#ެʱ�LRkxWO}� ��\0hi^V���ҽ��\0b�oR�-|5r̺�i2>�m�tU�`�����nެM��j�T��|Gi�m8� �P�������G\0`s[_`����s�C��\0����z��\0o������?�������e�\0W�������������k�z�7\n_��?\"k\'��\\v���$vilĄYPff�뽊g���W_\\����A�(����6r|�rB1,ۀ�\0��[v3�n+w�0�\0���\0�������c(�.�w:\n+��g�?���\0��o�E��0�\0���\0�����N�������h�9��\0�h�g�?���\0��o�E��������h�9��\0�h�g�?���\0��o�E��������h�9��\0�h�g�?���\0��o�E����O<3�\0`�_���iZN��O}m�YE��x�K�rff�trڱ���ݿ�<U�]x�|��Ѵi \Ze���եFe��� v�ǩ�϶x���Z�f�\0�Z����O�����_��h�t�\0]�Z���W���n�itύ�;�b����<�U��0�\0���\0������x���Z�f�\0�Z�Q�Y7��(��0�\0���\0������x���Z�f�\0�Z\0�(��0�\0���\0������x���Z�f�\0�Z\0�(��0�\0���\0������x���Z�f�\0�Z\0�(��0�\0���\0������x���Z�f�\0�Z\0�(��0�\0���\0������x���Z�f�\0�Z\0�(��0�\0���\0������x���Z�f�\0�Z\0�(��0�\0���\0������x���Z�f�\0�Z\0�(��0�\0���\0������x���Z�f�\0�Z\0�(��0�\0���\0������x���Z�f�\0�Z\0�(��0�\0���\0������x���Z�f�\0�Z\0�(��0�\0���\0������x���Z�f�\0�Z\0�(��0�\0���\0������x���Z�f�\0�Z\0�(��0�\0���\0������x���Z�f�\0�Z\0�(��0�\0���\0������x���Z�f�\0�Z\0�(��0�\0���\0������x���Z�f�\0�Z\0<	�\0$��?�\n��\0�K]s��\0�y��Z�\0襮��9�\0�\0�<�7�����SV��Z	��ʰ�w�\0$����\n��\0�M[�D���1p�0J1R>�r\r)+���7�o�G�e �g��������q�[#�Ӟ�<�$ִ��bk�/%�m/nlm�@�s$L6�h�\0z���I8�+�O��ɩ�$�Нv��\"�K0�7�@���g9���V�ew=ݻ<77d�ɠD��r2P9���{擔����\0�F�%okt����ܴ��-t���7q����7������W�#I��=\Z�	-�#�[���A�<��������xI��kXW˷�A,�C\ZD���Q����W�g4�\n^\\=�>,�#w�M���g���|u����R���\0_��ڭ���S���]�Z5�0G�F�dY:�!������Ʒá��3��k�5+�fm���!�c���\0�3x.���{{�����ެ���e�B�(\0�/��p3����}��[ck9v�*�EP~\0d�I�-�����\n�aovW�z�\0����B�(�bQE\0QE\0QE\0QE\0s��\0��?�*��EkY���w�x�U�)Y���\Z� v&?1]�+������sş�O�\"��Դk��B�aki(���<�I\"���0�I\'������^�F����o��x��ŝĺlR��/k,H~�T��ِ8uÍ������:���|0\Z���wwp�f�H�2n�[���ś��AV?���E9��̑[�(K�&�L�s�x}>�I�h_c�&�g�E��DJcF1�BN\0�F�䔮������^Ǖ�JI����L�{+�3�\Z����-o-�2C.ŕ�hó�76C6A;N�H\'jx�nm�S᫈�Ŏ{�[,�b��J�*�n�U?1lm�9�|!m��L��מy,�H�m{<���#.O��pz\Z����F�%��GRw�\0@˪�)B��e�H,~b&�m_N��F��}�6�6�>�`L�,b�`��Vh��W`�\0��CX�ZT�x�Q�g�\Z9`����V4��X����*z/���}��EPEPEPEPEPEPEP?�O�\'��\0�U���Z�8�\\x�U�����jw*�cS?eD\\�<�<g1呒>����I��k�\0������]=g[X��<�<�1;��X�{�t����t�������mjf�/��t�u��oN�[n��B�\\�>U ��sO�v/�z��ؖm^e��-���>��32����*��O%��t��t�Ի��u�=�J=ď���1H����:T��kI�G�Қم��+@gG��FI��2pH�i�!��> �.��&k�i\"���٘�qf&@�� ��`���� ��5����V�`�����n��e�%�����d���摻�~PN���]��v��:;Diݠ��o%�̷\r�J�&F%��~Rq\\��\rR��m�:���k[��V��[����U`�=p0-l����=.����_���4�mgY�]��U-��`6��a!�4lY��\0��`��}1�-���!մ{t�8�ntmҩ��XG�G�܁�0K|��i�q�Av��{x\r�M������g�^O<Ua��(O�k�hnd��E��,���9���~��7�]v��r%^�~_��+��Z�ͣ-�m���<�7P�������\0$��	]���?���\\���A-�M��$�0,F��\0}%��4��Eϟb�p��l�cd䅄����튯���\\�� Y�K��o$�\0��>�i�����\r�\0_���2�:ϓ���`4q���ڴO���w��y�����۰��Ś_i����Q�{	���3�fc�\n��.]��c؂s���\"z/�����_������O#��\0��N�/}�s�s�kF���ɮ\Z�=��c<�1;��	�\0p)GM|���K]��\0�����5k�M��1�`����F��&�AR*�	.�\n\08^�����h�g�n 5��gx�bC�BTt�|~�s�=\Z�i{iQ�y.X.e�@�r�t`�n�8n�D�4� �6�Q&�C��#r�\\�w:s�����a��~�9{�WĶ�&�{u����M���{ki@��ެ�\\�!��t�/\0�˦x\'J�Gf�Kx�o�[y*Ŕ���n�l�y��W,<+��S;K���]K;,_�S#1E�\\��K[hl�!��M�A\Z�\Z䝪�\0d��U++��_�����	-QHaEP?�O�\'��\0�U���Z�+��\'����\0�*��\0E-t\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE���I��k�\0���\n��	�\0$��?�\n��\0�K]\0s�;�\0�y�o�]覮���\0�\0�<�7�����SWA@Q@Q@Q@Q@Q@Q@Q@Q@�\0���9���\n���Z�A\\�\0���9���\n���Z�A@Q@Q@Q@Q@Q@Q@Q@Q@Q@Q@�\0�?�xg��V��)i�Ŗ��>M���3�cj���<���������\0�y��Z�\0襬��M�(��&�5Q�����\'��d2o�Bw�dr@�į���\\�����Ӷ�e���&��-6�����b8p9� ��8�d/���k�/Q�䵳7�M�ǺXFrWc���$���Q��0�5=J�S��	}i-��3o$��\0���H�$\02x�M�<,4�FЮ�^Y����h��O�c.D��\0/4������Q�����B��&J��8�-VK��ym��\0p�I\n�̸y@�_����1Rk~ ���A�X[ip�3�d��6�	��\"�1����E�O�h��P��[x���q`f�X\r�H��_-��������]�i76w�Ɲ)`��f+!FS���^Ʃ��_��؝l1�Mz�\Zt�n�\\��[�ʈ\"�ER�@ݼp��T�	��G��e%펏�i|�_#,W�E\Z�<�	*\0m��62����y�x��Y{�9�nĿ���R�<�\'��*�@Q��U�\'�׶/��y�-݆��%�+i��d*T}��\0(<���g�Owo��Я���D����W���ؾ�x�q����@f˜�l*N�ǯeo<WV�\\@������dʸI>A�9����[I���`�̬�z�?3�F8⻫hE��0��Dh,I�>U�=jkmD�����%��(\0��(\0��(\0��(\0��(��\'����\0�*��\0E-t���I��k�\0���\n\0(��\0(��\0(��\0(��\0(��\0(��\0(��\0(��\0(��\0(��\0(��\0(��\0(��\0(��\0(��\0(��\0��	�\0$��?�\n��\0�K]s��\0�y��Z�\0襮��9�\0�\0�<�7�����SS&�m��}�Gj]ݵ���I��;�\0�y�o�]覭��VS�B�I\n��OAɩ�j-��]�yǆ��?�Y�䷗���G$�$~/���\Zm\'�S*�`Ip^�23�-����E��\'����a��n�y��dύø�\\�՜�x[K��1%�\Z��d�շN�UB$�����H��]��������������&ct�~�<��1��������\0������o�s��\02�x&]OQ���\Z��ń�)\\��B����FX/8��*��j�k�7�n������Eה[�?���x�sZ�I,�ŚZ����++Ը����? H�or<�$*���j�R�,ou���\'�ouX��`\"y�[���I�؇@9���.x��O�p}Z��?�����:����]�m2y�M\'�.�$L��Xς2�2=G�Om�xf��k;]oQ��\r,1x��0y�M�GZ�5��i����&�w9�Gf�ed�����wn���8����\Z>�Gm$^�������A�ϙʱb����9�m�\0��}వ��+������\'�u�^m3Z�obF��m�K���+1��������������\0�V7�?y�^�O.�ne��,_IjC,{��}�B2���I�.{��:�2������\0���\0�ֹ�\0����=G�!�_��k��>��\0���Q@���/�~���^�\0��?�\r��\0��\\�\0����\0���\0��\0�\r��\0��\\�\0����\0���/�~���^�\0���(���/�~���^�\0��?�\r��\0��\\�\0����\0���\0��/	���\'F��@�SD]���>�n�1�X�rq��\04����>�s����dG�3�_����re�kt�5k����g��S�\0H�k3�)�麍���Y�uu,2i�������O`��li\\�a��T�76w�䰉=�\0�w�F*�̼��3���_����������\0�W5�%,��EM.+�;{g�ϸ�e�H�@�aے$n?��|1��{q��kVIsm�[{؉ܫ7$7b���>�/O���\0��.���_��Z����\Z{^�\\x��WD\"=v����f�U��Ct����?�}{�\0ǫ��J����h����������&�_>3!2�y���Km9\\�i#Do�iZ:m�Y������2��\\x2aUƼ�=hz��\r��\0��\\�\0����\0��t�ui-d�W��z#�	`2|�#��W+��V�b��_�0��9��l��M��D,IH�d0\'���]N�`ѼC�햌#�[g����%`¢\"��������O������\0�<��[��R�4B��%�[�V9G��S3`�� ��h�+oe��>-�\'���F�Z���il�o#\0��j�Si��_�Z�����~J��:m�F�ܴx`����sl��\n�b�[[���X��,�B�n�/�c]@<��������o:���~B����cV=/A�}>1/��_���W���A���(pHG��O�t�\0�\r��\0��\\�\0����\0�v�o��<3��&��6kl��p&+��wy]s�<��*}?�\'��:��ݯ���\0�\r��\0��\\�\0����\0���/�~���^�\0���(�?�\0n��\0?Z���������������\0�WAE\0s�\0����������\0�Q�\0n��\0?Z�����tP?�\0n��\0?Z���������������\0�WAE\0s�\0����������\0�Q�\0n��\0?Z�����tP��	��>���s���L�v�W�����(\n=�\0v����m쯯R�\r��ȗ�����Ǜ��l�U�\0�<������R�93�i�o�t\'�5ou���hm$xf/˙TLA�F1��T���o�A�i~�\0��A����Ic��C)�ݾ���֟�\0n��\0?Z�����s�֛����`5;�b�����rM`?2Es�d2�rNv��8��7F��\0����?��$�d�\\��p��	f؆�\\���]�\0����r��\'^[���4-E�d����2\"2 �r���0UUa�IU��Ct����?�}{�\0Ǫ��<=gΧ���q���+��{�s ����-�_�\0q�z���X~%�ݶ���g��w�H�DDn��͜匜�������D�i�2kW:j��e�,{�����H�*�D�َӀ�ݫV?iRF�-μ\0@mr�O>�ˑ�5�{��u�<i{o�D.nt�O��+�ro\n�e�;s����|;kp��ާiu��(��h��*��`��A�2p�\0�}����گ뱵y��\n�ak��r14��<�9v8QĽ�X�\0�7K�\0��s�\0׿�z�c����^/��Z�KK-^V�������_쬥���w�1W��E}���ҥk��	t�0x�!B#Vܠ���_���Z���ί��/�~���^�\0��?�\r��\0��\\�\0����\0�+�_]x�Mo���P��nWK�y� ��t�\r�$�`c\0�H����R�7siR=����1F�>�Ґ�7QR��;�=M%�������_�ٿ��:��Ct����?�}{�\0Ǩ�\0�7K�\0��s�\0׿�z�}CD��������z$:���6��0�sn�+n��H��%S ��Wg�k5��\Z$��-q#��t�+\n������9#�s����������z�\0�/�!�_��k��>��\0�����\0�ֹ�\0����=]��\0���\0�ֹ�\0����=G�!�_��k��>��\0���Q@�\0�!�_��k��>��\0�����\0�ֹ�\0����=]��3�zuρ|=;��!��-��z��(&%<*��`\0�X-|%puU�X֙��E��\0m��,IǛ�#<�:���\'����\0�*��\0E-q\Z����h������2_}��	-U=լ�\r��EX�_\\0jdڿ��\nI;z�\0��/	�V�L��օ��h���w��Lg ���8\'<85r������[�rA���<�]Ns/8��XS�n�T�m�b��xtskt��Ct�YA>���;����&�}q�i���7\Z���o2�W-�=G��*�^����?O���\0n��\0?Z���������������\0�WAE!��\0�!�_��k��>��\0�����\0�ֹ�\0����=]��\0���\0�ֹ�\0����=G�!�_��k��>��\0���Q@�\0�!�_��k��>��\0�����\0�ֹ�\0����=]��\0���\0�ֹ�\0����=G�!�_��k��>��\0���Q@�\0�!�_��k��>��\0�����\0�ֹ�\0����=]��\0���\0�ֹ�\0����=G�!�_��k��>��\0���Q@�\0�!�_��k��>��\0�����\0�ֹ�\0����=]��\0���\0�ֹ�\0����=G�!�_��k��>��\0���Q@�\0�!�_��k��>��\0�����\0�ֹ�\0����=]��\0���\0�ֹ�\0����=G�!�_��k��>��\0���Q@�\0�!�_��k��>��\0�����\0�ֹ�\0����=]��\0���\0�ֹ�\0����=G�!�_��k��>��\0���Q@�\0�!�_��k��>��\0�����\0�ֹ�\0����=]��\0���\0�ֹ�\0����=G�!�_��k��>��\0���Q@�\0�?�xg��V��)k�����O<3�\0`�_���P?��\'�&�\0�U���jF��r�f�t0\0��\0�4���K��\'�&�\0�U���j۸8��?�?ʦN�li]��t��H�=:���^�d��1$.49�*�9��f�}�ň���&p�e��\0����}t�\0i1[�֚����3K�Mf�v��PV�S�`*	�㧓P3���_���7������Kh��\'̵R���s�]q�UMY�t�k����t��x����_xvXwE$Z<���E�0}�Q���mƵ���1����MW��V���\r�V�Πt�������+��@V�\\��m�<rjoų7�4���xu��#�3k2���AǗ\n�\n���T\r��|�\0�k��\0^��z@��X6�Z�Fޘ�e��\0&�\Z��kZ��\Z,���q����ͭ�+��P�o�����]0�$�`v�o�#$�����G�i�^Lt�mn8e��T�#\rl�]|�\"�J���܀i[����J�_�ٍ?Ńn5�m�^?�j��?�wC�\0�4���N�l�Rxb���Et���4S����m���ZL.�>lg\'9��mY��9�\0�������	��\0�>�����&��\0������\0��a�\0A��\0��Uc��t?�M�\0�U�Q@�\0��a�\0A��\0��Uc��t?�M�\0�U�Q@�\0��a�\0A��\0��Uc��t?�M�\0�U�Q@�k⣬x�E����SA)m&V�d��G�Fѷh�\'�Ny��q��\\<��\Z.����g\0?@����kG����g��S�\0H�k+�Z�~�3kr6���A*�N�C<@��]}����Or�9���?���/�z�\0�.��\0=?���g�}}��\0�C]�\0�����\0 ���\0���\0��������w?m����h��Z�jr[�yt�tV�G|��F{�� yF7˂\09~���^!�J�����k}Bh-��3J����\r� r=0Ü�]?�ɚ}f�����\0O�$��\Z��F[�wB�0F^M6ua8d��\0�$���s֥�\0�C]�\0�����\0 ���\0���\0��������k˭GS��>�&�S���,�-�1�#�l\n�Sv��O��>��h�k�}޳ss�F���4&�t�S�PFX����l�@��+F��5�����\rw���7������\0���>�\0���ޤ�е�-C0k~[���\0�T��0����o���{��Ų��.�}8�]Z�G�����+g�4a\"�[�\'8�<���ēx��i���\"�\r��O#���!�X�ۓ#�W��\n1Z��D�\0TL�T��97�\0o����?�;��\0��o�J��~0�\0���\0�i��*��-7[��Ζ�:6�~ֱY���ɻxd29P��r*�)��~\rq%����vr����G�\r���9��̢Q��ਪ��/�2:K�3_�W��}w�����+iS\nv�*�k��Fq�q�y��?�wC�\0�4���\\ޝv$�|746�voi��c��?.	y��\"�S���u=k�(�u�u�Cgg�j��s�\0c��t?�M�\0�T}�����\0�M7�\0%WAE\0s�\0c��t?�M�\0�T}�����\0�M7�\0%WAE\0s�\0c��t?�M�\0�T}�����\0�M7�\0%WAE\0s�\0c��t?�M�\0�T}�����\0�M7�\0%WAE\0s�\0c��t?�M�\0�T}�����\0�M7�\0%WAE\0p~��Sx��o����t�c\ZI����.ar8A[�c��t?�M�\0�Tx�I��k�\0���خ!���;�Y^��1I�w����0Ǯj[����\0/�\ZWk����\0#���?�;��\0��o�J��~0�\0���\0�i��*�o��m*�H��{�}Cm$��30R���_i�(��6q��ZV��W]76�j\rg�-�🱋X�Fh���?�\r��5v���������	�;w������>���2��nI��K�?�wC�\0�4���Y�m�׉��[W�\'��-JXR�#�\")�P�if�p~`��rL�_j�^6�=F��=6�Q��$\rn��$��S0|��T��^s�R��cz_Ȧ|7���\\��ύ�a�8kvnN�.�\0vX�c9��\0�!������c�_��ξO�����=���wڄ�z�����wWz��Q%�Q<���1f>Z��H\"�>!�\r߅-uinoⱴ��=Bk$�7@E!UvW\rU��|����*\"����++%\'��\0]���D5��5}\Z,���������w�g�1���!������c�_��ξO�����=���$���2�-���h�ا�\'��1�S#�\r��{��.��h�I��~u��,��I-�m��\n�b�Vd���Y��_��>H�����\0&Y������d���k��j�4X�\0W��s��������|c=��\0�C]sWѢ���/K�|�_/&{��X���.�fg��{x����H�ZT2�Q�[s��\0`7^z>�}�=\"/Ȟ$��4[x�!���m�KG!ϐ�hV��p(�q�Y���S�\rt}�_F����.u�}|�]���g�Oi��X�e���$8ڋ��6���}����Zɽ�<Q{�=R-�Pq�OI?c�<��H�S#	F���\0�r+�(P��b^\"����^�?�?�wC�\0�4���G��a�\0A��\0��UtU�\0��a�\0A��\0��Uc��t?�M�\0�U�Q@�\0��a�\0A��\0��Uc��t?�M�\0�U�Q@�-|T���[�:4p2�ƒi2�*�K�X\\�N;�g�V���a�\0A��\0��U�\0�y��Z�\0襯;��M�Ś�v:~�w}ezH�L���埪zT�Z�����i^޶=�~0�\0���\0�i��*��������	��\0�⮭o_�\Z^���}CL��¤��w$X�\0iz����������jw��y��\Z�̱�1�,���v�����}��U��������	��\0�>�����&��\0�������~0�\0���\0�i��*��������	��\0��(��~0�\0���\0�i��*��������	��\0��(��~0�\0���\0�i��*��������	��\0��(��~0�\0���\0�i��*��������	��\0��(��~0�\0���\0�i��*��������	��\0��(��~0�\0���\0�i��*��������	��\0��(��~0�\0���\0�i��*��������	��\0��(��~0�\0���\0�i��*��������	��\0��(��~0�\0���\0�i��*��������	��\0��(��~0�\0���\0�i��*��������	��\0��(��~0�\0���\0�i��*��������	��\0��(��~0�\0���\0�i��*��������	��\0��(��~0�\0���\0�i��*��������	��\0��(��~0�\0���\0�i��*��������	��\0��(����O<3�\0`�_���W?�O�\'��\0�U���Z�(�������*��\0E52oh��3YkRF�+h7�������\'�&�\0�U���jޑ�Fό��i-F�}3��?O���\0Y�h���zy����l���w�O���A.�y�5�yޞfb���9�j��[���J�BHc�I�T�X�|� �)dݍ��\\���<U�[�h��\"��J���Y&�;�WɈD�z�c�\0XK��:���\"�_�􍾳_����w�������\\�\0��4k��<����s��}��\'��� �?�<�\Z���O31|���9<}֡�[Y�fQ���\r��wYIa]�%u\0�2�G�6��%�ω���%^�kX��r$�1b�m��`q\'��g��+u�����w���xd��?���g��_����f/���v���?O���\0Y�h���zy����l��.���7�\'��L����F�Q��n����`7��9�]ޑ5�ƙ���ܲ�	����qD��c�z=�{_����������~$�l\"d��_;��y4K�w>��Y<`}Y�\0��K�\0�]s�\07��f��4]]�����h�������0@�ܧ*�C���f�x�Q������mj�Z���	.qJ4A1�z�e���V��_�e\')6���F��&Z_����!��\0�4�e��\0Ϯ��\0���3\\�׍u1�Z�d#ҿ�oo�����\\���`Tl0_�㳫x�m��_�iVw��$�Gy��1�/Y�	p6\0G_��������mm�Z��i������\0���\0	���\0>���o��f\'����4�k8,��6�]!����)#l+����FW������Zׯ���8�^Td�v�(�r�lU� �<c֝����\n�_��&�\0��K�\0�]s�\07��f��L�����?�C{�\0�k����\0�&Z_����!��\0�4�e��\0Ϯ��\0���3]��^,Ӣ�<N�m��.���t[� }��~`\"ʜ�����ɩkVz��u�^�$Y�]ɲU��a���6z0���sş�O�\"���&�<E�jzZi׺Z[j�h�qa$�1�,�(o���^)5{\"�7����f>�ya�����腼��τ��\'��	�}>o��K}V�С�:�%#\n�_�n�h��!�\0R2~_���l����e{{�[��hb��\0P���	U�/�2��ha�֧���km�<7#Q��(��.�Q�����\0�\06I�\n���\n�]\r>�>��_�r�>��2�gu�L&�t���2�p|��7s�͞wT�:����-����<K�K���Fه�d����V�\0��A}�]����3٥��C�c�\0�푎�s��;�*\\��>�;�ܿ��K�2�$�����4�\\*C�+�0F\0������U�mCI]B���-f�ud��	<7y��T�<��\\�r\0�{�)��L��J�/�K�8Hǀ��X�����U_	܀�@#��d�p*ķ��M�M��wrXBۢ����ʡ�\0�z�:*��&�Uѧ�4yR\rjM,��k��B�)�`V~1�t�:��i������\0��AE\0s�\0�i������\0���\0	���\0>���o��tP?�\0	���\0>���o���i������\0��AE\0s�\0�i������\0���\0	���\0>���o��tP?�\0	���\0>���o���i������\0��AE\0s�\0�i������\0���\0	���\0>���o��tP��i����m���L�F1跎���p�=� ���\Z���5(�+��O}<��|3vҦF���Ӛ��\'����\0�*��\0E-b�W��v�nt�4��\0e-���9�o ���v�P��_�E���_���&ռ+s�ũϠ�˨D\0���5vҠ�Ñ���M}G�2�U�÷����o]�\0��3��]o�*������{)�٦��,��\"���Čm��ei\r���v֗r��s��Ґ#�W1�c;�9#���$>*�mÈ4�f �d`��]�NI8��=�j��U}V=�5{���ؙ�1��9<\0:֕ǉ^�;8Ή�>�v�OC�Q>�y�X����u�M�F��$�����R��!�C��ޯ��P��䁕#9�&�sڅޙ���.-o.a�A\'�u�;�S�s�Tp�`�s��$z-��6iV͋Z	�p�K�8�`瑳o g5��6�����L��.m56�JO��@�L4��VS���8$⵵����,�f�sm^u�ź&����e-В1\0r9�D��\"oK/���O{c=��%����\"0$�>�wx�;�F�W���G<�mZAΧ)�#Fo]�Ѷ7#���0h�g5c��3��H��I���[8�=�Q���#\0ʬ�����H��<e>�����_=ŔJ$��8�yX\n��q8*N��d�pr/�����Y�e�/�3-N��B��X��˄�Q.�ޅ�gi�v��뚵6�m*�,��S��$��·M��牱�N�1���&�e���}k%�畧\'�{y�D0/��Knl�0��z�&(�ej�^ɨ�چ�m,���v�Y��/��8�8###�9P,D�/���C��}\'P���������o5�.d|g8f�s�$�ݸ��a�v�(��+(����X��C�t�+�=���躌S�N ��/nd��/��S\rՁ�#;�H�[�+�$����d\'��H��${�Q�����I~F�&Z_����!��\0�4�e��\0Ϯ��\0���3]���-/�}u����\0�?�2��\0��\\�\0�\r��\0���\0��\0�2��\0��\\�\0�\r��\0��-/�}u����\0��(���,ӭ����Y/�l�c�oI(�\"{�A�ZO��rK[�Y4}M��Y�x[×�%-��\'O|��^�\0�y��Z�\0襬�u�-ψa����[��H��e0���>V����M&ҽ�1��\0^?�0̳E�j�*�!��r>O��(��&�aEe���ѳ�!�������5�u�k�sI�{ks��a\r���|&V*�9���=g<WE��Z}Z=E�H��jZ��ʡ�\'$�U�����Mվ��]~�L�����?�C{�\0�h�\0��K�\0�]s�\07��f�\n)��\0�2��\0��\\�\0�\r��\0��-/�}u����\0��(��-/�}u����\0�?�2��\0��\\�\0�\r��\0���\0��\0�2��\0��\\�\0�\r��\0��-/�}u����\0��(��-/�}u����\0�?�2��\0��\\�\0�\r��\0���\0��\0�2��\0��\\�\0�\r��\0��-/�}u����\0��(��-/�}u����\0�?�2��\0��\\�\0�\r��\0���\0��\0�2��\0��\\�\0�\r��\0��-/�}u����\0��(��-/�}u����\0�?�2��\0��\\�\0�\r��\0���\0��\0�2��\0��\\�\0�\r��\0��-/�}u����\0��(��-/�}u����\0�?�2��\0��\\�\0�\r��\0���\0��\0�2��\0��\\�\0�\r��\0��-/�}u����\0��(��-/�}u����\0�?�2��\0��\\�\0�\r��\0���\0��\0�2��\0��\\�\0�\r��\0��-/�}u����\0��(��-/�}u����\0�?�2��\0��\\�\0�\r��\0���\0��	�\0$��?�\n��\0�K]s��\0�y��Z�\0襮��9�\0�\0�<�7�����SV��K%��*��B����\'�&�\0�U���j���	7z��:���\0�%uf4��r:7�u��F���.��.�t��	���ި#P��I]���=��kz��s�u�wn�W6��B�`�V@�T�]���a�xSU�H��~��H�4�o�k�\nА��ڙ���7����ֱ�\"y��=�\"� ,\'�$�Ǹ��z��M���������/��ݷ�ֵI-�{�K0b-f�\rVvd��	��ӝ��K_kV��K����I&>f�m�l�n<��c��U-�C�om���(��q��?��~�q!v��\0��`1�Ҥ\Zw�M�������]J<Ou��0���4�;�����o�+������ި�E��YY8�A�.�����[w��0F͹<5\\���6rZĚ]�E����H�u#�T�`|�b�o�rj?����آ\\�\0�Qu�\0I��\0?���i���aԮ|C{\r�����5��&FF�3����������\0�_w��}�n��<�jW�\\���㽺��{H�Y\"I�ٴ�h��>Z�\0�4��n�uhoV�C^=�V^`xm�ees�H{nP��u�xJ��;{����.�.)�Mt����\0ϓ�z��m:	�档[�ڿ��e�����{aU�*��Tn�8 �:_����`u�\0������ޟ�+��!h���짱��]l��U�\'�$������4-oP��x.��.�գ��݃�(@܌J�j����׻�\r�zE��u=gS���͹��k��e�5^�N𝖕�q��Ec(��Ğ&�X�$��8=)5e���7��������7t��Ƶ�In�߲Y�k7�Mh�2��&�$�N�N���3X�{X�^�U���K�\"B���ؑ�U*�dݴ�����y���\rYx��w�����VF���o]�L�wlBe�� p;�+z\r\'��Gf5�D_:���ݙ1|���=�\Z{�\'][�Z�\0_���w~�`Ӽ#ua=����f��$Mq��8���a>^��-|��/��R�v+\r�x��G!�V��H���L��h�|x;K#\"�\\����������������\0�P���x��©�\0�V�sX��\0�n4�|�\0+�>�]�f�3鷨���y�\\���=:]c���:�jh��Z�RG�-��\"\\��NN0:\0���m�-/n�X���#��iVF ����X��s�������{�]j�C����Y�R��EeP���z�(����?�d�ă_\Z�-�r��|�Qm�����Ė��ݴ�����.���;��H��K����UiAS�@5/�!�_��k��>��\0��m�`ܳ�i\Zf��M��峽��+	��#r�����/����SZ����sB�k�����L`��\'��k�	��t�����������\0�Q�S����\0�Ct����?�}{�\0Ǩ�\0�7K�\0��s�\0׿�z�:\n+��\0�7K�\0��s�\0׿�z��Ct����?�}{�\0Ǩ����\0�Ct����?�}{�\0Ǩ�\0�7K�\0��s�\0׿�z�:\n+��\0�7K�\0��s�\0׿�z��Ct����?�}{�\0Ǩ����\0�Ct����?�}{�\0Ǩ�\0�7K�\0��s�\0׿�z�:\n+��\0�7K�\0��s�\0׿�z��Ct����?�}{�\0Ǩ����\0�Ct����?�}{�\0Ǩ�\0�7K�\0��s�\0׿�z�:\n+��\0�7K�\0��s�\0׿�z��Ct����?�}{�\0Ǩ\0�\'����\0�*��\0E-\Z7�4�#R�ԍ��ڍ�Է��ʲ�����$�_°��=:�����u��i���=j��eG�\0Ԋ�mO�?�O�K7�\r����5��?嘔��[�6Iⅾ���]�k�A�xwQ�..����Q�\0ʎd(d$�P@q����XxRm?E�����PIv@o��f8:$Bo� ���3�կ�Ct����?�}{�\0Ǩ�\0�7K�\0��s�\0׿�z������\r-��MO3M���ϵ/��qk�VQ!.\n!B�����8-n4y��C���y6v�LM�m�Sq�ޙ=i�����������\0�Q�\0n��\0?Z�����\0WO	�[C�=J4�����-�tu��d*@~=�s����\0\0�\0�Ww<�yfkO���-nr�|����d��j��j�\0���\0�ֹ�\0����=G�!�_��k��>��\0���c3R�=��\Z������:�V��ه�H���}�!*T��a���*�P]f��YKK\\���1$�@��W�\0Rr\n��3Ə�!�_��k��>��\0��^�Ú�\\-�����c��<�\\\0H�^x#�;��\0_�alZ���x�����PG\"��с����x�OΨ�>�A��ۯ�\"��*9��?���ɶ_�w�6���Ct����?�}{�\0Ǫ�hz\"k�h���Kf�\r��y�j��󳜰�Ion���G����4��/.�[ˤ�Y�\n\0X��ÕN㓓�N�Gӆ��Y��yn>�\n���rρ���[C�^�G3���[5�o��͛U�Hϝ��j��\0n��\0?Z�����?��_뮧AEs�\0����������\0�Q�\0n��\0?Z�����\0tW?�\0n��\0?Z���������������\0�PAEs�\0����������\0�Q�\0n��\0?Z�����\0�\0�y��Z�\0襬�S�Q�~��è43�^�{�E�[��#����yzUOxON��/��{�d<�e���Z�Eħ�Y@Q�\0�n���\0�ֹ�\0����=I��\0���hl~��ﺕn-�I]2H\Z<PI-���i��ܞ�䰓Q�Pg��o>T��q�NH�����\0�ֹ�\0����=G�!�_��k��>��\0��W��\0���ɶ�����tW?�\0n��\0?Z���������������\0�R�Q\\�\0�!�_��k��>��\0�����\0�ֹ�\0����=@��\0���\0�ֹ�\0����=G�!�_��k��>��\0���Q\\�\0�!�_��k��>��\0�����\0�ֹ�\0����=@��\0���\0�ֹ�\0����=G�!�_��k��>��\0���Q\\�\0�!�_��k��>��\0�����\0�ֹ�\0����=@��\0���\0�ֹ�\0����=G�!�_��k��>��\0���Q\\�\0�!�_��k��>��\0�����\0�ֹ�\0����=@��\0���\0�ֹ�\0����=G�!�_��k��>��\0���Q\\�\0�!�_��k��>��\0�����\0�ֹ�\0����=@��\0���\0�ֹ�\0����=G�!�_��k��>��\0���Q\\�\0�!�_��k��>��\0�����\0�ֹ�\0����=@��\0���\0�ֹ�\0����=G�!�_��k��>��\0���Q\\�\0�!�_��k��>��\0�����\0�ֹ�\0����=@��\0���\0�ֹ�\0����=G�!�_��k��>��\0��\0x�I��k�\0���\n��	�\0$��?�\n��\0�K]\0s�;�\0�y�o�]覭��V�1U��ڥ�OAɬo�\0�<�7�����SPl�^A\\Ј=Aѥ�\0�RWM-&�<�DS�x^��M1��=6���-u$�9�FX��B[��܎�����\0J�y��g��]=\Z�68f�R۠WUR�9-ب��S����Z��<\r�CdV録 �p#Ys��Z�O�`ۍkAzcE������7���\0Sg*���ח���x�aԯ�U5��q��	,��\0c<������ܙ#�ߥ%��Ն����{~}6��n��H�h�O���g*^3]`��X6�Z�Fޘ�e��\0&�)!�-��$� ��O+��[G�K�	!ҹ8�v����\0ֿ�7*쿽yyy6��W���]L����5с�1hc�#��F	�4�ͯٮa��\0]j+��f��9�ޯ�\Z7\\�/̻��\"����mƵ���1����MP4�\0\r�ִ��4Yx�\0ɪ-/��/�9��\0+�ן������Ǧ�V���g���l��+M\"��E`�nL�\0���<G��kW֦�\'��K�w�D�I�A�nv�;T�܎��a�,w1[xqnY���*1�����dd�QQF|Dڤ�lz����x�f�hs\r��9�N9*�jw��}�\0��n��<ג����|C�;{����z����틦42܈��H�(�p\\�8]��Ѥ��aӯ$����56�$M)��޲�R#|\0C��q㨮�i�,q�h#oLh���\0�T\r?Ńn5�m�^?�j�ҳ�|�?�����6h�l�/%�V-}sw���X%C�̡�\0K~�N�����\"�)4�\0ۉ�}X,v��/揳$yR�,�g�5ԍ?Ńn5�m�^?�j����mƵ���1����MS|����w:)����^K�����ͦjk�\\]J��~u���\\�kq�!9��[{{�ɦZ�!��ZH��m:H�N�䗅B�\0H��ߺ\Z��kZ��\Z,���O�`ۍkAzcE�����߽��}�\0נ��/����F�[Xd�@wE���ڥ�yl|\\��u��h��\0�U/��a�\0A��\0��UQ������g��S�\0H�i�)������A��:��)D,|�F��A�O��m\n��GX�8�YѕƦ�R�L���oʏ���nтO ���?�wC�\0�4���GTãG�hח^2�d��˼�>�|�\r��ƪ���>U��I9�n�˯�O��v�&�/�B�ؖ�!q�nLk�RA�`���G�?�wC�\0�4���G��a�\0A��\0��UKy���u���\0���C?��M���x�te��{�\"L�&4��T,�v�ݻ� �\"�\n�������&��\0����?�;��\0��o�J��]��}΂��������&��\0����?�;��\0��o�J����������&��\0����?�;��\0��o�J����������&��\0����?�;��\0��o�J����������&��\0����?�;��\0��o�J����������&��\0����?�;��\0��o�J����������&��\0����?�;��\0��o�J����������&��\0����?�;��\0��o�J����������&��\0����?�;��\0��o�J����O<3�\0`�_����xkS�.o���m���d�Ko���)I��K�a`	�:Ӽk��/���Yѣ���4�I��W�\\��q�>��>�����&��\0��ZK���?����_�8�7F��\0����?��$�d�\\��p��	f؆�\\���]�\0����>\Z�k�L��J�U`�,l\'0}��a�*`n�p:�^k���?�;��\0��o�J��~0�\0���\0�i��*�����@���\0���q���- ��Ϧ�;H���܀�[/\n��&V<�8�x��P��R���w���r4���\r�K��!L<�15�������\0�M7�\0%Q�?�wC�\0�4���M;4�K��#�O��i�\rZ?���c*۴�l�\"�ā�ܚ���ן�y���>������nY�,��+�����##5�}�����\0�M7�\0%Q�?�wC�\0�4���J>���K[��\0��\03�>���L�j���|wSVݧ�`�f$���֯���Y�V� ��k��$���Ya(�B�)�|��s�+K�~0�\0���\0�i��*��������	��\0�IY[�ߗ�~,������8���%���JқN���n�E�L�ٖ��(��+b�@wb^2w�kA4�oo%���{�����1�Y����S���>||�1�q�������\0�M7�\0%Q�?�wC�\0�4���O���\0�w��t�\0#�M&���K��\0_XZ?�n`e6[�����`	|>lp[o��p�	�m��!��#�G�QV�*�d��;�ǘ۰z�A���~0�\0���\0�i��*��������	��\0�?����\0%����qھ���cA9���8$[{K��h\'���A08ª���ݐA5���W�h�!����>��Z\\��@��vT!N\0��I.�#�[�c��t?�M�\0�T}�����\0�M7�\0%S������X:[����s����\0�������	��\0�>�����&��\0���Q\\�\0��a�\0A��\0��Uc��t?�M�\0�T\0x�I��k�\0���\n��k��/���Yѣ���4�I��W�\\��q�>��>�����&��\0������\0�������	��\0�>�����&��\0������\0�������	��\0�>�����&��\0������\0�������	��\0�>�����&��\0������\0�������	��\0�>�����&��\0������\0�������	��\0�>�����&��\0������\0�������	��\0�>�����&��\0������\0�������	��\0�>�����&��\0������\0�������	��\0�>�����&��\0������\0�������	��\0�>�����&��\0������\0�������	��\0�>�����&��\0������\0�������	��\0�>�����&��\0������\0�������	��\0�>�����&��\0������\0�������	��\0�>�����&��\0������\0�������	��\0�>�����&��\0������\0�������	��\0�>�����&��\0������\0�������	��\0�>�����&��\0��\0�\'����\0�*��\0E-t���I��k�\0���\n\0��w�\0$����\n��\0�M[�g�m���5���\'�&�\0�U���jd�,��!xf�֤��VV�oH#��M&��5k�cxGV�,�;a��\\ZG�����ly�u�sI���-���1�g�9o��S�ן�ڦ�s�I��K��.���cb��>Kd�(\\�p+��w�O���A.�y�5�yޞfb���9�j>�����\0K���f�~�w����|v�qڕ������#t�\'�?��	�j�$mV���+\\�K_#IA-��m�L�m`q�8$z`��3T��5d�Ե���/�1q>�2#{=�4��c{`������>�����\0K���f�~�w����|v�qڏ�xd��?���g��_����f/���v�yv������F�����+w1�c��z햧�\\���B�}#���(!m�2 �G<u���RӮu�g��ԧ7�����,[T��ʡ��sXW�xf��{G��2y�RYWH�.��X�\'q��L�?�Y����D*�b�u8��>�b�m��zt�.��_�~=�ƅ�\'����~0�uK=v%ҥ��\Z-��§*򯕰���d�>��G��isx�S�IԦ�`m.̽ķ�rK�#s�c�A�9�U�%�H�a�,�S�o�i���\\\r��9�j�������Ai�4���_�I鸘�lv�A��.K��������e\'OO?/�먮�-/�}u����\0�?�2��\0��\\�\0�\r��\0�1:\n+��\0��K�\0�]s�\07��f��L�����?�C{�\0�h����\0�L�����?�C{�\0�h�\0��K�\0�]s�\07��f�:\n+��\0��K�\0�]s�\07��f��L�����?�C{�\0�h\0����<Y�\0aT�\0�+Z�+�мY�E�x���Y\"]Mv跌@�%���E�9S���B	��\0��K�\0�]s�\07��f�:\n+��\0��K�\0�]s�\07��f��L�����?�C{�\0�h����\0�L�����?�C{�\0�h�\0��K�\0�]s�\07��f�:\n+��\0��K�\0�]s�\07��f��L�����?�C{�\0�h����\0�L�����?�C{�\0�h�\0��K�\0�]s�\07��f�:\n+��\0��K�\0�]s�\07��f��L�����?�C{�\0�h����\0�L�����?�C{�\0�h�\0��K�\0�]s�\07��f�:\n+��\0��K�\0�]s�\07��f��L�����?�C{�\0�h����\0�L�����?�C{�\0�h�\0��K�\0�]s�\07��f�:\n+��\0��K�\0�]s�\07��f��L�����?�C{�\0�h����\0�L�����?�C{�\0�h�\0��K�\0�]s�\07��f��<������R�A\\�<Y�[x��=��^=2�Ǣ�:�\"Q�,D0��ڷ?�2��\0��\\�\0�\r��\0�����\0�2��\0��\\�\0�\r��\0��-/�}u����\0�\0�(��-/�}u����\0�?�2��\0��\\�\0�\r��\0�����\0�2��\0��\\�\0�\r��\0��-/�}u����\0�\0�(��-/�}u����\0�?�2��\0��\\�\0�\r��\0�����\0�2��\0��\\�\0�\r��\0��-/�}u����\0�\0�(��-/�}u����\0�?�2��\0��\\�\0�\r��\0�����\0�2��\0��\\�\0�\r��\0��-/�}u����\0�\0�(��-/�}u����\0�?�2��\0��\\�\0�\r��\0�����\0�2��\0��\\�\0�\r��\0��-/�}u����\0�\0<	�\0$��?�\n��\0�K]p~�f�m�_@���x��dc�x�H�G���j��\0��K�\0�]s�\07��f�:\n+��\0��K�\0�]s�\07��f��L�����?�C{�\0�h����\0�L�����?�C{�\0�h�\0��K�\0�]s�\07��f�:\n+��\0��K�\0�]s�\07��f��L�����?�C{�\0�h����\0�L�����?�C{�\0�h�\0��K�\0�]s�\07��f�:\n+��\0��K�\0�]s�\07��f��L�����?�C{�\0�h����\0�L�����?�C{�\0�h�\0��K�\0�]s�\07��f�:\n+��\0��K�\0�]s�\07��f��L�����?�C{�\0�h����\0�L�����?�C{�\0�h�\0��K�\0�]s�\07��f�:\n+��\0��K�\0�]s�\07��f��L�����?�C{�\0�h����\0�L�����?�C{�\0�h�\0��K�\0�]s�\07��f�:\n+��\0��K�\0�]s�\07��f��L�����?�C{�\0�h����\0�L�����?�C{�\0�h�\0��K�\0�]s�\07��f�:\n+��\0��K�\0�]s�\07��f��L�����?�C{�\0�h����\0�L�����?�C{�\0�h�\0��K�\0�]s�\07��f�:\n+��\0��K�\0�]s�\07��f��L�����?�C{�\0�h����\0�L�����?�C{�\0�h�\0��K�\0�]s�\07��f��<������R�A\\�\0�?�xg��V��)k����O<M�\0`���ջ3�FS���V��\0�x���W_�)�n�$��X�uIHVe��T���Í��y�����^�<Ku�ϨE{�t����Q�2�h�h�f1`F{�֎��dI5�Kr��RJ��\0�ɅE���S�rI5��j�0����/uie��[]�4v��u��\Z���6�y \Zuςno��Ӧ�&���2�h�����1;).���M�6������ü����~].�x���nno,�N��kinTEM#���=�\0�|���s+x��k+��ٛN����>�.`u����$0TA������ޥs��x�u��1C����̱30�n�A�\0ƩC�{��%X$Cu�s�Ϟ��g\n���^6�as����������g�5�K�_K��կb�����+}����d���:\\I��\n0`�q��9�sA֧�N�oyi�����)���Q\\r�HÎ�9�ֹ��V�%��uw��;۱���<�-���>TUu$�~c��U�ӼK�O�^iv�c�js,�%�˄��8*��*��>��ֺZ[��̾�\0����W��x�=Y�\\y�\0e]:��QR��خI��0�u�<b���b��ڜz�)h�M��;Xn�tM�.I�(p�=&�y��:��\\ꖺtJm��H�yUb�&��b�$�\n�s�k������}y5��ۓ4���[hʢ�����g\'&�˺���\0�)Ѵ]���\0O�?�W�袊f!EPEPEP?���x��©�\0�V��W?���x��©�\0�V��PEPEPEPEPEPEPEPEPEPEP?�O�\'��\0�U���Z�+��\'����\0�*��\0E-t\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE���I��k�\0���\n��	�\0$��?�\n��\0�K]\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0s��\0�y��Z�\0襮���\0�<������R�A@�\0��\0�x���W_�)��x��?�\"z�	��|?�M;��O<M�\0`���յv]l�)H�q���2�+���<�L�@��I��?�^���?-b��H�h^LybU0��I�T�3��h����V�mφ4R�i�X��sr؉ʳXw\02q��ǂh�V�ܺ��j:r%����%�I$��\0#\0�	,y��]i\Z��y6:|�v���_Es=��yҗ��_�(�d�j������[��\r��W�����}��,���5{{�\0閑i�)o���KF��`�mS��<�����a��Kt��gB��������J�Ufu�x�)�2X��g:��U���\"��k�]B���.$\r�Иw�M�DNY���Z��X�jڮ�}eo9k�{?�����[y�Rm^@%�z�2��e��o����U��_���gّ�� C^����5�[Ȥq#�vDd(�\0(`��[�1�Z�ռ)��+G��¾	mJ�e�ap�<���E\'��Mާ����k�n��)�6��+�bsgC�޾Y�Wvx&�x�^��:���iڷ�n��w�O�(�gR2�\0�q�Nq���\0V��ͪ������3+�Px#JXm��)��~�ڣ�ѼȣYdU��Tڍ��zps�W�\0\'��\0�T��\0�]�\0\\n����Ou�e���^�މ%WIC�	Wn1���9$\0zק�2\\B�G��22��A��R�Zw�\0#)R��տ����3�O�\0Щ��\0��&��A<�\0B���\0�����(����\0���\0�*h�.��\0���O�\0Щ��\0��&�\n(��\0���\0�*h�.��\0���O�\0Щ��\0��&�\n(��\0���\0�*h�.��\0���O�\0Щ��\0��&�\n(�м�Y����ѝ!��\"V���_�[���nf8�=�s�O�\0Щ��\0��&��sş�O�\"����9�\0�A<�\0B���\0����?���\n����\0�k���9�\0�A<�\0B���\0����?���\n����\0�k���9�\0�A<�\0B���\0����?���\n����\0�k���9�\0�A<�\0B���\0����?���\n����\0�k���9�\0�A<�\0B���\0����?���\n����\0�k���9�\0�A<�\0B���\0����?���\n����\0�k���9�\0�A<�\0B���\0����?���\n����\0�k���9�\0�A<�\0B���\0����?���\n����\0�k���9�\0�A<�\0B���\0����?���\n����\0�k���9�\0�A<�\0B���\0����?���\n����\0�k���8?x3·^���ǆti��L�y$��&gc�I+�I�[���x?��M�\0��\0�4x�I��k�\0���\n\0��\0���\n����\0�h�\0���\0�*h�.��\0����\0��\0���\n����\0�h�\0���\0�*h�.��\0����\0��\0���\n����\0�h�\0���\0�*h�.��\0����\0��\0���\n����\0�h�\0���\0�*h�.��\0����\0��\0���\n����\0�h�\0���\0�*h�.��\0����\0��\0���\n����\0�h�\0���\0�*h�.��\0����\0��\0���\n����\0�h�\0���\0�*h�.��\0����\0��\0���\n����\0�h�\0���\0�*h�.��\0����\0��\0���\n����\0�h�\0���\0�*h�.��\0����\0����\n�x��Ѧ�]2��K���JI$�I\'�n�	��\0�4?�C�\0���O�\'��\0�U���Z�(��\0���\0�*h�.��\0���O�\0Щ��\0��&�\n(��\0���\0�*h�.��\0���O�\0Щ��\0��&�\n(��\0���\0�*h�.��\0���O�\0Щ��\0��&�\n(��\0���\0�*h�.��\0���O�\0Щ��\0��&�\n(��\0���\0�*h�.��\0���O�\0Щ��\0��&�\n(��\0���\0�*h�.��\0���O�\0Щ��\0��&�\n(��\0���\0�*h�.��\0���O�\0Щ��\0��&�\n(��\0���\0�*h�.��\0���O�\0Щ��\0��&�\n(��\0���\0�*h�.��\0���O�\0Щ��\0��&�\n(��\0���\0�*h�.��\0���O�\0Щ��\0��&�\n(��\0���\0�*h�.��\0���O�\0Щ��\0��&�\n(��\0���\0�*h�.��\0���O�\0Щ��\0��&�\n(��\0���\0�*h�.��\0���O�\0Щ��\0��&�\n(��\0���\0�*h�.��\0���O�\0Щ��\0��&�\n(��\0���\0�*h�.��\0���O�\0Щ��\0��&�\n(��\0���\0�*h�.��\0���O�\0Щ��\0��&�\n(��\'����\0�*��\0E-t���I��k�\0���\n\0��w�\0$����\n��\0�MG��a�\0A��\0��U;�\0�y�o�]覭�����f���C\n�`I��J���9؏�g���xrK�}�tI�H^-�+�}�##���?��a�\0A��\0��Up�%����iڽޏc���G}s;�Q�2[�d��h�xw�,��<;~u���@ڝ��4\n�cb��wT��J��I��_/���zH������&��\0����?�;��\0��o�J�.�X0xoH��ob�y���{�\0	\r͝��_�hU\r;�eە;��g׷�e�ƥ����_2�k(�W=Y����i�/��^%�D�M>#���@�%T�e&6�8a��8=���%�.��g�-X�W���$��U�7=�\"��OT�M[V��S����7S���Ȅګ\0$�˴������o#�YE��$?�7�[�����	f$�����Z��\0]?�rV��ȿ�?�wC�\0�4���G��a�\0A��\0��UtP#���?�;��\0��o�J��~0�\0���\0�i��*�\n(���?�;��\0��o�J��~0�\0���\0�i��*�\n(���?�;��\0��o�J��~0�\0���\0�i��*�\n(���?�;��\0��o�J��~0�\0���\0�i��*�\n(�Э|Tu����\\jh%-��������H�6�$�	�8�c��t?�M�\0�Tx{�C�,�\0����t�������\0�M7�\0%Q�?�wC�\0�4���]�������\0�M7�\0%Q�?�wC�\0�4���]�������\0�M7�\0%Q�?�wC�\0�4���]�������\0�M7�\0%Q�?�wC�\0�4���]�������\0�M7�\0%Q�?�wC�\0�4���]�������\0�M7�\0%Q�?�wC�\0�4���]�������\0�M7�\0%Q�?�wC�\0�4���]�������\0�M7�\0%Q�?�wC�\0�4���]�������\0�M7�\0%Q�?�wC�\0�4���]�������\0�M7�\0%Q�?�wC�\0�4���]��2��M�_5���G�-�i&�+�������}n}�����\0�M7�\0%Q�O�\'��\0�U���Z�(���?�;��\0��o�J��~0�\0���\0�i��*�\n(���?�;��\0��o�J��~0�\0���\0�i��*�\n(���?�;��\0��o�J��~0�\0���\0�i��*�\n(���?�;��\0��o�J��~0�\0���\0�i��*�\n(���?�;��\0��o�J��~0�\0���\0�i��*�\n(���?�;��\0��o�J��~0�\0���\0�i��*�\n(���?�;��\0��o�J��~0�\0���\0�i��*�\n(���?�;��\0��o�J��~0�\0���\0�i��*�\n(���?�;��\0��o�J��~0�\0���\0�i��*�\n(��e�����k}gF��[�M&We_)p�	�|�\n���?�;��\0��o�J����O<3�\0`�_���P?�?�wC�\0�4���G��a�\0A��\0��UtP?�?�wC�\0�4���G��a�\0A��\0��UtP?�?�wC�\0�4���G��a�\0A��\0��UtP?�?�wC�\0�4���G��a�\0A��\0��UtP?�?�wC�\0�4���G��a�\0A��\0��UtP?�?�wC�\0�4���G��a�\0A��\0��UtP?�?�wC�\0�4���G��a�\0A��\0��UtP?�?�wC�\0�4���G��a�\0A��\0��UtP?�?�wC�\0�4���G��a�\0A��\0��UtP?�?�wC�\0�4���G��a�\0A��\0��UtP?�?�wC�\0�4���G��a�\0A��\0��UtP?�?�wC�\0�4���G��a�\0A��\0��UtP?�?�wC�\0�4���G��a�\0A��\0��UtP?�?�wC�\0�4���G��a�\0A��\0��UtP?�?�wC�\0�4���G��a�\0A��\0��UtP?�?�wC�\0�4���G��a�\0A��\0��UtP?�O�\'��\0�U���Z�+��\'����\0�*��\0E-t����I牿�u�\0���6�q�7�u�#pU��� �O������I牿�u�\0�����ϱO交�[lf\\�q� ��R��lq��9\r���\0��\\�\0��.����3s��s��m\'�����g�uh�w�������v��\r�^\'�v�u\r>{��=��մ��8����]�H>|�\0?)���-���0\\�E�޷�؈^�3C��b��{*�K�J7���ɛ}j�����\0O�7�����	s��̺�o;����ώ��;U	�k{����g�\\�m��\0�q����t��~0ԓ�2����4�p�MzeĦM�H\'_��H�����I���Z�R�{�`l/5[�5\"�YS��v�r��x+�u�{RT��Z�<Mu�����C�=������Zyז�f��	�;��vqگY�w�2C��|�;�乳ws�X������B�VșW�5iI�s�\0���&k�����\0�(�\0��T�\0�3\\�\0��_��]FG?�\0	��\0Bf��\0���\"��HuO�5���e�\0��Q@�\0�$:��	������\0�?�!�?�L�?����\0$WAE\0s�\0���&k�����\0�(�\0��T�\0�3\\�\0��_��]��\0�C�Й���/�H��S���s��\0Y�EtP�k��k\'e�!�SFeYl��%��ٜ��Fs����\0	��\0Bf��\0���\"��sş�O�\"��\\�3i>,��P�f�X�bT)�\'��9VF�F��[���\0�C�Й���/�H��S���s��\0Y�Ee���ºv��6�����3jW�eA�t��Y\0���$�3b��3j�\Zz��b5�Ο�,�>X�\'$��Y�8�H�/gg�V�.�C�Й���/�H��S���s��\0Y�Eaj�%�/���:i���Ȟ�k{��\"�º�P�պa�;���أ�\"�4�kk�5#��~>��ɜ;J�@*۾S��n<Q���HuO�5���e�\0��C�Й���/�H��?�-a�������Hiش�����f�2�`�#\r�p�m�K���Q�6�U�=�24�%d�o\0�;�A�\'H�����\0�/�{��!�?�L�?����\0$Q�\0	��\0Bf��\0���\"��<G�84O�\0h\\ƺ�z���\"R��q��g�yl�x�ɫjv�v�\'���mX�<��5�#$H*�VwU8�4������:_�C{�S���s��\0Y�E���&k�����\0�+����\0�$:��	������\0�?�!�?�L�?����\0$WAE\0s�\0���&k�����\0�(�\0��T�\0�3\\�\0��_��]��\0�C�Й���/�H��S���s��\0Y�EtP?�\0	��\0Bf��\0���\"��HuO�5���e�\0��Q@�\0�$:��	������\0�?�!�?�L�?����\0$WAE\0p~�u|���z��e����f��~a�ppz���?�!�?�L�?����\0$Q�O�\'��\0�U���Z���]V?\\�ꚅ��<�ga=����@�:�;�����1���5��!�?�L�?����\0$Q�\0	��\0Bf��\0���\"��_�����ckq����kf�w��kHI����*���2qV��7���*�Yu�Il������B��۴���� _��/�Z��!�?�L�?����\0$Q�\0	��\0Bf��\0���\"��/�PiZ��w)�#ټ1^FڨY��R�LE�T09;	�\0dV����~&�m4�-$���E�Mpc-�\'F��2<�4Л�S���s��\0Y�E���&k�����\0�*�x���SJF���\0�\r�ڿ}��򷈶cg���~q�*���N�:Ʃ�Ǻ�KK뗼�I�-\"��5�I;N`\0	$p����5o����o�HuO�5���e�\0��C�Й���/�H���F���kgӌw��%�ٵ�u�Dyd�O�F5kG��s��ml�eqm0��:)l:l\0�!��dp\0.�\0�C�Й���/�H��S���s��\0Y�Eb���V�5���`o���Y�˲�(��-�&��@����	$�޳㨴}n��X�<��5W�Un�JB�H6��	�S�\"��`�/�\0�C�Й���/�H��S���s��\0Y�EQ���5�nഷ��RW�?��x�(�\0J��Wׅ𷍢��6��نt�[�:v�����rv�8��N\r]��w�S���s��\0Y�E���&k�����\0�*�x���SJF���\0�\r�ڿ}��򷈶cg���~q�)�o>ج�2��53�=�ڳ }��{0P��r��O\0H������=.�O�?������&k�����\0�(�\0��T�\0�3\\�\0��_��]��\0�C�Й���/�H��S���s��\0Y�EtP��wQ����<\'�Ή�[*��a\\���� P+s�S���s��\0Y�E�\0�y��Z�\0襮z��~#[6���Ӹ�X��_+y�U8��<��G^h[���/���\0����C�\0	��\0Bf��\0���\"��HuO�5���e�\0�sP��M�I�{pV������\\�0��%v玢����\0i>8�6�wu$M���T2	~�u1|�`�x�����?���&k�����\0�(�\0��T�\0�3\\�\0��_��\\t���ud.���|�:�_�a��vU�Ȫ�+�!FG������h�`�$�jI��yk )\'���	����I=�ݿ\r�Z��W$�\0��T�\0�3\\�\0��_��G�$:��	������\0�ʻ���le]F���S��X�/�[)�3�_/�����n��b�\0����ki\rԶU�[�,r�>\\�~`Wp:��i����j.�w-�C�Й���/�H��S���s��\0Y�EQ�|[�;ŰIL��Ho!�A-4����!��`���w�Q!���͂s�o�T�v���*��ܬK\n�r@M����i_o���$:��	������\0�?�!�?�L�?����\0$W<�\'���z�lZ���-��y������Z!��1 <`�q%�SA�5������h��g�=��س�v.� c�oGf%��7�!�?�L�?����\0$Q�\0	��\0Bf��\0���\"�𿈢�6�%�B\0�N�I�k�<E��I\0��8p@\"�,�c�˧[���u����$��X���v�\"���(�����o�u������\0��T�\0�3\\�\0��_��G�$:��	������\0�u�\0��𥶿�M�يFͰ�gE8<��Ƿ�U�_�2�X�t�m9-X�)���p20�sϵ\r[pN���S���s��\0Y�E���&k�����\0�*6�R��Rè*@�ّ���\\4�+d��N���+��m�{[[\\��\0�!�?�L�?����\0$Q�\0	��\0Bf��\0���\"�hZ�^Z�M$�A}qe*p��q�W�8�O�=kÿ�<A�ZY�4�.�9$��6��΁yh��,����慮ޠ����HuO�5���e�\0��C�Й���/�H���\0��\0�!�?�L�?����\0$Q�\0	��\0Bf��\0���\"�\n(��\0��T�\0�3\\�\0��_��G�$:��	������\0��(��S���s��\0Y�E���&k�����\0�+���9�\0�HuO�5���e�\0��C�Й���/�H���\0��\0�!�?�L�?����\0$Q�\0	��\0Bf��\0���\"�\n(��\0��T�\0�3\\�\0��_��G�$:��	������\0��(����O<3�\0`�_���W?�O�\'��\0�U���Z�(�������*��\0E5t����I牿�u�\0���\n\0珂4f��E����%��b`�0|���!xqVg�r���|{\'����Y��$�P�T�A\\q��(���)��3�P��vɳ�4��l�;3�x�)��wJ���]�>�w%�_�o�gݹ����㞝+R�\0(��\0(��\0(��\0(��\0(��\0(��\0��=�\0!��U?�֪x�F�\0��b���g�+i��,����FrKAN�*߇��9���\n���Z�AI��	(�U��\0���}:�\0U���K[��x�E��xr�ﴲ��Hv�\0�~�F�&�>��m*��1�(��`BH�\0��\'#��\0�]�<����7��{X\"������:�s&�-�6��w�xU��.q�F��MZ�4i�Y�&�M��[�n�⸏�k���*G���\nk���+��ȿ�8k?���g��Q��k���Y�b˕��\nB���\0\0ڭ=\r���bMSK�]7U�^	�J�D����3���z�(J���\"gR2ZE/��g-���ê�ef��.�3Z�f�v�x^\0d���\'�*;�\0��4�\'��K�J+�B��; egb(���`*�u5��TdQE\0QE\0QE\0QE\0QE\0QE\0s��\0�y��Z�\0襧�����Z��y��=����c��#p!�0���a�x�I��k�\0����E�?�_��o�7ym��~͝?��z{з�p�e�Yh�=F?&�,�V�}��m�2�e!�b@��Mm�;+q=���+}��nee�<�\n\r�P� aB��^�/��S]=o��\rk�����?�2mد�\nH\'#��ź}�L׎C�������]������G1ȡy[�����\0���p�Z}B�X�t�r�%�6�KG3���#b��Pv��w��*j�����K�)��KnS<�VRXA�ϡ�h��Y+՛�+[FĂ&\n�c\0��횎_h��[�����I\"�-�e�Gd$>#E/�A��:sȣ`ܫ�\0e���o�͇�����]<�;v����cϷ~���4^��P��n#�[��i�2,�����<���=sb�^�_ǬZK���F\'weFʠ�U�?7M�x8�W��jk�����s�y<��g�fM���I�c9-�����? z��\0_����m���������o%��{(o/�!z���:��6V�h��-�]%��,�,J2�sÞ��Y\ZO����u{*�i:l�R����~��G±$�.��#;�V���&u��a-���y �22	I[t8���4o�=�35/��W��>��C�P^��\"yS��P�(J��Rrrza������q�jA=�7r�D��O,[6��/�ݨ�t������������:���$�kd���E�)q�TƊ܅,I뜚���7:fD�棨ǰ*%���c�)\Z�?RkR���9��C,���~l>�������y۷���3�}���8�G�6Fѭ�ۍ��\rD��>`�I��w#�\\w��(Zm�m�K���\0��vQE\0QE\0s��\0�y��Z�\0襥��Vri����@�_�\0h,�o�m��R�����i<	�\0$��?�\n��\0�KU��\Z�\\_�a}(��j3YŲ�O,�c�ZL�X��ȡ|^�W�`{k���\0��tH5��˻��V���\"�*�0`��\0�zcڡ��ͅ����Q+[����1����1���?�=�?�{8t�j�i��:l����	8��O8�\'��{��~Z�-�����\0�����0��9$dg<��\0���o��\02�xLKD���Kxm�޹��j���w��=kD�\\��9nnm��qq���� A|��=FAt:�q���Cv�\\����E$<9xlca�<�U��*�x�\\��v�k,�m@0���sԖp�����_��Ƶ~���C��-���\rC��\0k�o�����\0;n�����gɷf�v�5�ai-���qq~�9��cA��W�s��a���=v��M���e����-q\Zv�b�\rKo�M>K��$u���K�\"���r�Tb��L�e����_ւ���[���F���~m\Zn���;;�.\n�*��@DP1�~5$������P���[y��T[�.7��\Z��,[��2_��B�v�����ҋh$��Ny�վN9n�#8�ˬ�]�_�:�~��r�I�B:Ȏ�+���=�j��\0]�Z��ׁl��i�q[�,iym���\"(P�(J�	B����ū��]\\Ms�ݭ��Gs�2���?,l�H)�C�l5�_�Icu5�7�l�,���=�(�«a�݃�R	��W���k�����s�y<��g�fM���I�c97bZ#J��k;Q�����:�c}�v���f��҂��6�!d7���36x��n�TWڼ�����O��i�y�~�v�Ĉ�BFH�s��m�_�݋���M�[��g���d���\"������������֟�G�E]gDH����ݜQG0ʫp�1�� )`ʧ��=+4=A�_�j׳�\r���g�~H�z��\0d䞟��ܬ�2G;��Gc;���F�H� �PW޲�\Z��A�D�:���Ӯ.QU�6��>�	0K)II�ܨN��S��\0̳�k�>�6�0�[-��;�deY|��X���q�5{M�P]V�{���U,�]y�FT`F23ճ�zf���e��׍k��j^�\".�\03H�J�6v�\r��q���k�~�\rĖ��>��&I�x$��7̒*���	�J--���L��֛i��\0#�N���I��E��<�r�/]����������ڮ���m���|*`��i��(�l7����b�T��W��\r�Q��&v��k+x-Y��$JX��Ib8-��V5h�ztw1]�L�����ITG�B�|��r���N+}��|��X7�-}��p3��R�7�����5+�E�f��h��Vm�q��*p~b�Ў��kz��i�riRKou��G/-�G�yy�\"�D�:�j��/��M5��t4W3�x�M��W��;�(4ɚ9%���5epr��[[�����h�wZYf������X�����C`����Ξ�Û��$ �s4�N��m�e���e��RX/Bː��:h�Z�ܛƑ/�2Z �W�@�DRĀA#�\'\0mQUt�F�V�࿱�Mk:��##�A�A�\rZ��(��(��(����O<3�\0`�_���W?�O�\'��\0�U���Z�(�������*��\0E5t����I牿�u�\0����G�O�����e�\0���Q\\,wQM�}�/x�G�94�v�����^���x��m�=���ߛ/�G�����(���S��=s����=UM>�MRm5|q��bI����|#\ns�lrU�������\0��?�s�?�͗�\0#�K�B�R���!Y4�����b��\0/�?L8�s@}��\0�=���ߛ/�G����ɪM���5�wI4���τb�N~͎J�~��Q\\�\0�#ڧ�z�����\0�z?��?�s�?�͗�\0#�AEs�\0��j��9�����\0���\0�{T�\0��\\�\0�6_��@��\0�=���ߛ/�G���S��=s����=\0tW?�\0���\0C���\0~l����G�O�����e�\0��\0x{�C�,�\0����t��Z��ǉ�|Y��c��Y�+<�~�nw6`#8 q��g$�ԍ��2C�|L����r�ԚtLæ@hE\0vtW;���\Z��u�G��+=?g��\0c^��m�\0	Ƴ�2���A�l}�8�4�Q\\����x�Z�(Գ��b@�M������ŗ�ڗ:i8\0�x��\0��(�����FX�H�k��8��X�A�A�=;��S��=s����=\0tW?�\0���\0C���\0~l����G�O�����e�\0���Q\\�\0�#ڧ�z�����\0�z?��?�s�?�͗�\0#�AEs�\0��j��9�����\0���\0�{T�\0��\\�\0�6_��@��\0�=���ߛ/�G���S��=s����=\0tW?�\0���\0C���\0~l����G�O�����e�\0���Q\\�\0�#ڧ�z�����\0�z?��?�s�?�͗�\0#�AEs�\0��j��9�����\0���\0�{T�\0��\\�\0�6_��@�?�xg��V��)k\r�)�	��M��\0f�lj�v���S(��ٳh ��n9��<�j3x�ҧ�5���fX��̪��t�t��M]��;=F:��=��cʶ����L�\r�`����O����-]�RF���i�o�[�:��A,p#�1��mzg�U��:֛�&�a�������w�LSȮ`��R�#nO#��\0���\0C���\0~l����G�O�����e�\0��--o�D�$_����S�:V�&����p�����P�Xn$x#q$͖VUrdx\'��xV��I�Ά����i4qj6	\"�/�d�,��H ���\'��F��g�Ƴw�x�Ws���O�I�\0���\0C���\0~l��������oJ�\n�F��IH��n�������]͜H�j&���i�o�[�:��A,p#�1��mzg�;QӮ��>{���[@���ٶ���Ց��L�������\0���\\������y������/{>��E��q>������	C*�FLx8��=*jC]�ֵT��������V�Q�n.Ȅ�\\�`֪i�uΫf.�q�Kw@�g�^U���\"��ƽ\"���g�(dyv;�����>q�hZ+[[�A�����(���S��=s����=��j��9�����\0������\0�G�O�����e�\0���=���ߛ/�G�����\0��?�s�?�͗�\0#��\0���\0C���\0~l���:\n+��\0�{T�\0��\\�\0�6_��G�#ڧ�z�����\0�z\0�(���S��=s����=��j��9�����\0��\0�\'����\0�*��\0E-To^6���e��D�18�>��G�{�w�4-Fo�zT�f�>�l�qY�@b_�n����}I���G�O�����e�\0��-�\0�����l��g��kV�O�F���C�H��[pr�6:m�_��zU[��x�Cei���ѣY���O-	�L�G�Р�z�s��\0���\0C���\0~l����G�O�����e�\0������A����Pk=w�\Z��M[{�k=/������\\*TGԞ��u����ͼ2=�J�u&�.��>� N\\�]�\0�{T�\0��\\�\0�6_��G�#ڧ�z�����\0�z���t�[V�u��/�t�b��6)g7ڤu0/��*��Hr��A�s�/k��ɦ��u3�6�	sq$k��#o{eH^��x��\0�{T�\0��\\�\0�6_��G�#ڧ�z�����\0�z]-�u�\00�����)i��K�A�rĚ,zj;�Υ�b0@0�j<-� �d�������eӖn[l��mp1ʃ��N+O��S��=s����=��j��9�����\0����?�a��\0�O�G3m�mg~�,�Mw�h�jw�f.��*��<\01�ζ�������o��cQq��N$�O����o��S��=s����=��j��9�����\0����t�\0A[K[[�2M���^)�.���J;��$R����H=>E�j�?�kVi=�ӡ1����;y�2�]��b���S��;���j��9�����\0���\0�{T�\0��\\�\0�6_��J��y%�5�2�����\0\"�U���5=1l�.l��[{��$u}�r��A�pW�O#��k�)4ie��䷻���ِ��J��_8������j��9�����\0���\0�{T�\0��\\�\0�6_��T�����m9�~j3iڵպ���Hc�Ḛ�e���*����@�������Z�^hm%�-V�Pa	$;3̠�T/�q�+w��S��=s����=��j��9�����\0��-���ۻ�_�(��·��]��2[o��AG�AÖ(>R9�T�𦯦�m��c�����)�f���\ZFގSt��[!������#ڧ�z�����\0�z?��?�s�?�͗�\0#��VcN���I�G��S���)��|��9<}Ñ�_jֱ���<[��J�`�ϳD��2�s!F*��U�g�0���?�s�?�͗�\0#��\0���\0C���\0~l����������I%o�K��w�Zh��x��G6��g+J�2!���/\nLx��s��TZ��5mv=Z�Q����v���Ď�R�	2�V�w60����j��9�����\0���\0�{T�\0��\\�\0�6_��K���ߐ�\0�������6��z��\rͶ��Z�F5+�{w�@y!܅�!�\'s��xr[;�X���nX�I�iJ��GP��$����\0�{T�\0��\\�\0�6_��G�#ڧ�z�����\0�z\0��.mGs�e���,��^Wp9�\n֮��S��=s����=��j��9�����\0������\0�G�O�����e�\0���=���ߛ/�G�����\0��?�s�?�͗�\0#��\0���\0C���\0~l���:\n+��\0�{T�\0��\\�\0�6_��G�#ڧ�z�����\0�z\0<	�\0$��?�\n��\0�K]s��\0�y��Z�\0襮��9�\0�\0�<�7�����SV�ĉ��$�*��1�Q��������*��\0E5���t-��f_�E��Ն�f�|Ai��~_�w����\\l������a�r۹�7q\\����߇\Z\Z%���D���qdn���� S\nO\\We�����W�k�n���n��^�Gi����~�����j�������/}��߇��t���������~��{_\r��e�wo�F���d���1D-���[�.�\n��������	�?����ؙ-���?y.����x�⻵]^����|9�x�]���ؐ0^7�e�>a��*H�5�|�/¾_#w��V_�w\\����w��\0���/`����?-x�{e��޺��V����K�� �2�-ݙ��;I\r�c���.��\\�����Ҵ����\0e\n�`��,P9���k��>_�|6�F�+\Z��&��\0E�T�t۩��P����K��؂�,UCZ�x\'�=��wo�����+P�_���\0���/�w�R���Ŏ�sH�5�ޖڈ�s�\"yhCF_9H.9Ȭo[�Z��M��,jGF��\\Mf�I��H�O��J��\0�WKi�j�{+Y�3��줰1jN�$`�-z㊞+]~���Co��+�J6g�?�{ҏ2�����\Z�����~>Z��W?���C�\0�����G�<a�\0@-�\03�-Q��Q\\�\0�<a�\0@-�\03�-l��\0�?���\0ȴ�Q\\�\0�<a�\0@-�\03�-l��\0�?���\0ȴ�Q\\�\0�<a�\0@-�\03�-l��\0�?���\0ȴ\0x{�C�,�\0����ej:Γ�|Kwյ++���U��Xß9��3PhW^*\Zǉ�Z6��u42�եP��K~���v��9$c��w�1���[-:%\'z��.N��\0�?�?)��w�k�Ѣ�+�;ærs_G�h��dMaeus{=�w\Z��\\K�nD߼�%\"�bH�bց4WZ߅�mR�u�o� љ/��.\'?�&�H9+�z�[�`�����C��\0���\0�z��\0c�{Q��\0Ͻ���\0�;/����\0ǯO�>�+˷�f��GN����s����}�XIs��ǆ_�+�%�v��G�\0��aH\09�ֱ�H&��42<r��Ѱ�E�A\rM��\0Ͻ���\0�;/����\0ǯO�>�`�����C��\0���\0�z��\0c�{Q\'&��K0��~����_O���P\ZƩ�tv�ZyWl�cf%z>OPᗯ\'.֮5=	�M�鷷�A������GI�HC�.��@;9`+g�3�\0�{/��\0�v_����\0�^��}�j>��?�����\0��e�\0Y��\0�����������Q�M+s���WN��]V����Cv���F�\ZWP�8�d��\Z��|c�H�Zt���Mv\\4���=z�>Q�Z?l��\0�?���\0ȵWos)F1v���Q\\�\0�<a�\0@-�\03�-l��\0�?���\0ȴtW?���C�\0�����G�<a�\0@-�\03�-\0tW?���C�\0�����G�<a�\0@-�\03�-\0tW?���C�\0�����G�<a�\0@-�\03�-\0tW?���C�\0�����G�<a�\0@-�\03�-\0tW?���C�\0�����G�<a�\0@-�\03�-\0�\0�y��Z�\0襬-V�4��4�z��-^}Jv���K��%��\"����8ێ1K�˯/�|<��6�$L�����̾R�����8�5����C�\0�����B��9;�5(<5�\r^���[�ԥ�;�%X��~��\nM����K(�\0�z�ZxW[�:��ݏ�D4�}�\r�b��K�M�Fx%�*#�u�l��\0�?���\0ȴ}�����\0�s7�\0\"�Z+z~���m�3��OC��Q�d��ӼAl�<\Z���E������w<�W���7����<R��\0e��-���m��.6�,�I�����0�\0���\0������x���Z�f�\0�Z���y%�~,���y���\'����ŐjZ�ݾ�E�죹uYm��%�#�2r�nޫ�5�����-oG�յ)��\ZXgf�;ǵp6yh�!S�L�.y+�ⴾ���h�9��\0�h�g�?���\0��o�E��_�Q�����v~ѭ%��34�β\\�i��g`7<i���q\0���1��Mַ�m[T��u�4fK�#K����	�+J㞤Wg���C�\0�����G�<a�\0@-�\03�-�����K[��o�5-B�Z���W����V/�K<�!䏲:F\0$s��R��0�\0���\0������x���Z�f�\0�Zm�S����\0�x���Z�f�\0�Z>���h�9��\0�i�Q\\�\0�<a�\0@-�\03�-l��\0�?���\0ȴ�Q\\�\0�<a�\0@-�\03�-l��\0�?���\0ȴ�Q\\�\0�<a�\0@-�\03�-l��\0�?���\0ȴ\0x�I��k�\0���\n��u��/���FѤ�i��7�V���\\�؀q�\'��>���h�9��\0�h����\0�x���Z�f�\0�Z>���h�9��\0�h����\0�x���Z�f�\0�Z>���h�9��\0�h����\0�x���Z�f�\0�Z>���h�9��\0�h����\0�x���Z�f�\0�Z>���h�9��\0�h����\0�x���Z�f�\0�Z>���h�9��\0�h����\0�x���Z�f�\0�Z>���h�9��\0�h����\0�x���Z�f�\0�Z>���h�9��\0�h����\0�x���Z�f�\0�Z>���h�9��\0�h����\0�x���Z�f�\0�Z>���h�9��\0�h����\0�x���Z�f�\0�Z>���h�9��\0�h����\0�x���Z�f�\0�Z>���h�9��\0�h����\0�x���Z�f�\0�Z>���h�9��\0�h����\0�x���Z�f�\0�Z>���h�9��\0�h����\0�x���Z�f�\0�Z>���h�9��\0�h����\0�x���Z�f�\0�Z>���h�9��\0�h����\0�x���Z�f�\0�Z>���h�9��\0�h\0�\'����\0�*��\0E-t���I��k�\0���\n\0��w�\0$����\n��\0�M[�)h$P2J�?*�������*��\0E52ox:x^&�f��	MN%#�Cd\ZRWM;��c�?[)s>��ͽ\'M�U��������s\\�Ѱ��U{���{��0Fb��*�UwnZ�I ��<Ӓ��	�ɩ�����ۛ���IfF� �t��g5KF��\ZE�����Ek۩e{���`�IUܸR��gi\0㑌\n��v����\0/��+[��~����=^O�m�Z��9�W�r*G,�����ޟ>�W�Uu}CZ� ��/.uxV�����,�оe�������9�ⶡ�\0�s���:������6�w\\��\\�gn7c��Gm�K[i��\'�㷺]��Cum�(����\0g��=�II������ӥ��\0���\0���;O&��8�K=�����\Zu嵬�!�He��ys�M�y�値����%���]��R�h�ʹzxH�q,q́��yb\0c���ӥK�p���h^}�yi*^[#\"�X���O	�99�h��?��K�񎐚���}�����o�*8�{�)+i�i�y~c�J�s�����������i�5�����A/$�\"�h�x$}Ҽ�W�5�B�\"��W�a�K���R���\\�?�AM�*������+tj�I�������\0Oo&�h��`mPT�<��-��ikm=�^$��v�K��(n��[�t�\0�\\g�hNW���ҵ��\0�w����ѭ�x-c�K�n]\r4�C��;@\\�\0-r����i�YA��P DX�`E\0z*��\0�?��?����\00�\0�UG=���Q\\�\0�\'~�\0��C�\0��?�U��?����\00�\0�T�Q\\�\0�\'~�\0��C�\0��?�U��?����\00�\0�T�Q\\�\0�\'~�\0��C�\0��?�U��?����\00�\0�T\0x{�C�,�\0����g�6CW���77��v��K*�k5��ea��n�8��Sмi�Xu���%ё&���f��_�[�W��nV������ST����F��$Ah|@��у�(��Qԓ���������e�<�W��:%����������-�wV�q5�׳\\$-�|�6��i2\0 pKq.��kZψt}H]��u�}�K/!܏�7\0|�7d��x`�5�=�\0������V�;H�B-5��퍈-1Ȥ�@%�A#%)���m5���\0�tضZ�Z�]����ğ8�G��}�9��\0[5�L�������O����#��I�e:~�o#��M��6��ɛ��$��nM�����!�K+�\Z̷���J_�X��.�TUr�y�������zv��&�ϱ�/��s��`@NLK	��_g\n\0좧oxy�����P�\0i���5�y_���c��Ҕ��*�Th��%oRk�x���S��M7�J[�G:��\\��~�1��8ɒ��\Z��5�]B;;��Qhm䴶�g�C��4a��k)?)�\0�/��e�-�]��Q��X|�T*��� (�?ݢ�[�����]k���b�n	�W�D,R!������pv�)�wz\"#A����t��uT�E�B�\0��U��t�K2���9���	��tu�h�&���!����#y����r��c͑���\0�\0w�o�\0�w��\0�\Z�?���\0�U^�r�#���:\n+��\0����\0�5��1��\0�����\0�ס�\0���*�N����\0�;��\rz�a�\0��\0����\0�5��1��\0������\0�;��\rz�a�\0��\0����\0�5��1��\0������\0�;��\rz�a�\0��\0����\0�5��1��\0������\0�;��\rz�a�\0��\0����\0�5��1��\0������\0�;��\rz�a�\0��\0����\0�5��1��\0�����O<3�\0`�_���o�]�!���o6���G#JwDIP~P8#Ң�g�<+k�_[�x�F�x��d�9/�VF( ��Էw�\roo彛�:?�9V�4ւE9^�H�@�p\0���\0����[�\Zv����\\mW^��7L�O����\\^D�bYv�]z�;��:��\'��I�?�RY[ ѣ��K��C!�>#FVq��Cu/Z�S��>��Y/�U��cb�� քg{��JFFpq��U���M��J!���!Ֆ$h{F���,��ɧ/z/��\0��\0��ו�O�&T�.���tv��ٍ��Ԗ�����:3G��N�}���6��sV���j�7q\\�61]%���Q�o0�x���x�b��o�װ$k�@�mV���嬐�!Q��7�����M]}g�ܗ�^?��F�[qk*�Y<�b^=��#,	�o���ܘ�r���?����i����Z����g�Ś=&��w�wE����C/l�\n�M\Z���i��5�Ҷ�++����cʕfl��\0y�A�]c�|<����?������]���=��ВJ�q�qM���\Z=�����H�J�`Y�ք�#$�y	�\n���c\'\"�5�\0����wq������g�:���Ca|Z�t�����q:�w7��T���F1�\r�<a�\0	U�������Kf�4������cpH�S��F� �7��=��)/��k�)�܋���#�Y��X�͊ĎH\0���\'$W\r ���#��\'�w��N�����ne�ɱI����(��\0��1�v��������5�\'�\Zs��Gyy�j��2���aUiY�F��\'@\0��]��[�[Jy�g�x�x�����e�u�_�Aps�<����Si-��$ZH�K��\0��2��&b9\'�m�}Mii�+�N�j-��v��	9�ք���RH��f���\0���[���:�+��\0����\0�5��1��\0�����\0�ס�\0���*�:\n+��\0����\0�5��1��\0�����\0�ס�\0���*�:\n+��\0����\0�5��1��\0�����\0��','2015-11-17 10:45:35','2015-11-17 10:45:35');

/*Table structure for table `package_programs` */

DROP TABLE IF EXISTS `package_programs`;

CREATE TABLE `package_programs` (
  `id` int(11) NOT NULL auto_increment,
  `package_id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `is_active` tinyint(1) default NULL,
  `created_by` int(11) default NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_package_program_programs1_idx` (`package_id`),
  KEY `fk_package_program_packages1_idx` (`program_id`),
  CONSTRAINT `fk_package_program_packages1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_package_program_programs1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=174 DEFAULT CHARSET=utf8;

/*Data for the table `package_programs` */

insert  into `package_programs`(`id`,`package_id`,`program_id`,`is_active`,`created_by`,`created_at`,`updated_at`) values (56,5,1,1,1,'2015-11-12 12:00:18','2015-11-12 12:00:18'),(57,4,1,1,1,'2015-11-12 12:00:18','2015-11-12 12:00:18'),(58,8,1,1,1,'2015-11-12 12:00:18','2015-11-12 12:00:18'),(59,13,1,1,1,'2015-11-12 12:00:18','2015-11-12 12:00:18'),(60,16,1,1,1,'2015-11-12 12:00:18','2015-11-12 12:00:18'),(64,24,3,1,1,'2015-11-15 08:55:01','2015-11-15 08:55:01'),(67,9,2,1,1,'2015-11-15 09:46:16','2015-11-15 09:46:16'),(123,2,9,1,1,'2015-11-17 06:41:45','2015-11-17 06:41:45'),(124,2,10,1,1,'2015-11-17 06:41:45','2015-11-17 06:41:45'),(125,2,12,1,1,'2015-11-17 06:41:45','2015-11-17 06:41:45'),(126,3,3,1,1,'2015-11-17 07:03:56','2015-11-17 07:03:56'),(127,3,4,1,1,'2015-11-17 07:03:56','2015-11-17 07:03:56'),(128,3,5,1,1,'2015-11-17 07:03:56','2015-11-17 07:03:56'),(159,1,1,1,1,'2015-11-17 07:43:02','2015-11-17 07:43:02'),(160,1,2,1,1,'2015-11-17 07:43:02','2015-11-17 07:43:02'),(161,1,3,1,1,'2015-11-17 07:43:02','2015-11-17 07:43:02'),(162,1,16,1,1,'2015-11-17 07:43:02','2015-11-17 07:43:02'),(163,1,18,1,1,'2015-11-17 07:43:02','2015-11-17 07:43:02'),(164,1,21,1,1,'2015-11-17 07:43:02','2015-11-17 07:43:02'),(165,1,24,1,1,'2015-11-17 07:43:02','2015-11-17 07:43:02'),(166,1,11,1,1,'2015-11-17 07:43:02','2015-11-17 07:43:02'),(167,1,12,1,1,'2015-11-17 07:43:02','2015-11-17 07:43:02'),(168,1,13,1,1,'2015-11-17 07:43:02','2015-11-17 07:43:02'),(169,1,14,1,1,'2015-11-17 07:43:02','2015-11-17 07:43:02'),(170,1,5,1,1,'2015-11-17 07:43:02','2015-11-17 07:43:02'),(171,1,6,1,1,'2015-11-17 07:43:02','2015-11-17 07:43:02'),(172,1,7,1,1,'2015-11-17 07:43:02','2015-11-17 07:43:02'),(173,1,8,1,1,'2015-11-17 07:43:02','2015-11-17 07:43:02');

/*Table structure for table `packages` */

DROP TABLE IF EXISTS `packages`;

CREATE TABLE `packages` (
  `id` int(11) NOT NULL auto_increment,
  `package_name` varchar(40) default NULL,
  `limit_flag` tinyint(1) default NULL,
  `duration` int(10) default NULL,
  `price` decimal(20,0) default NULL,
  `is_active` tinyint(1) default NULL COMMENT 'is_active',
  `creator` varchar(50) default NULL,
  `last_editor` varchar(50) default NULL,
  `last_edit_time` datetime default NULL,
  `match_flag` tinyint(1) default NULL,
  `token` varchar(40) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=65537 DEFAULT CHARSET=utf8;

/*Data for the table `packages` */

insert  into `packages`(`id`,`package_name`,`limit_flag`,`duration`,`price`,`is_active`,`creator`,`last_editor`,`last_edit_time`,`match_flag`,`token`,`created_by`,`created_at`,`updated_at`) values (1,'Basic Channel',NULL,30,'450',NULL,NULL,'superadmin','2015-11-17 07:43:02',NULL,'b1fdc0af418c5c55bb5c5685c5de17a1',1,'2015-11-16 11:28:21','2015-11-17 07:43:02'),(2,'Sports',NULL,30,'200',NULL,NULL,NULL,NULL,NULL,'918e180e06b96c76b7193c9fcb1cb312',1,'2015-11-17 06:40:58','2015-11-17 06:41:45'),(3,'Test Channels',NULL,10,'100',1,NULL,NULL,NULL,NULL,'87ce091bf52e15f385767a99bf0eb3c5',1,'2015-11-17 07:03:56','2015-11-17 07:03:56'),(65534,'No Scrambled',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'65534',1,NULL,NULL),(65535,'Data Broadcast',0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'65535',1,NULL,NULL);

/*Table structure for table `programs` */

DROP TABLE IF EXISTS `programs`;

CREATE TABLE `programs` (
  `id` int(11) NOT NULL auto_increment,
  `LCN` int(10) default NULL,
  `program_name` varchar(100) default NULL,
  `Fingerprint` tinyint(1) default NULL,
  `display_position` varchar(64) default NULL,
  `font_type` int(11) default NULL,
  `font_color` int(11) default NULL,
  `font_size` int(11) default NULL,
  `background_color` int(11) default NULL,
  `visible_level` char(1) default NULL,
  `program_type` varchar(64) default NULL,
  `status` tinyint(1) default NULL,
  `creator` varchar(50) default NULL,
  `created_time` datetime default NULL,
  `last_editor` varchar(50) default NULL,
  `last_edit_time` datetime default NULL,
  `network_id` varchar(64) default NULL,
  `transport_stream_id` varchar(64) default NULL,
  `service_id` varchar(64) default NULL,
  `position_x` smallint(6) default NULL,
  `position_y` smallint(6) default NULL,
  `color_type` int(11) default NULL,
  `show_time` int(11) default NULL,
  `stop_time` int(11) default NULL,
  `over_flag` tinyint(4) default NULL,
  `show_background_flag` tinyint(4) default NULL,
  `show_stb_number_flag` tinyint(4) default NULL,
  `program_status` tinyint(1) default NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

/*Data for the table `programs` */

insert  into `programs`(`id`,`LCN`,`program_name`,`Fingerprint`,`display_position`,`font_type`,`font_color`,`font_size`,`background_color`,`visible_level`,`program_type`,`status`,`creator`,`created_time`,`last_editor`,`last_edit_time`,`network_id`,`transport_stream_id`,`service_id`,`position_x`,`position_y`,`color_type`,`show_time`,`stop_time`,`over_flag`,`show_background_flag`,`show_stb_number_flag`,`program_status`,`created_at`,`updated_at`) values (1,NULL,'Channel 24',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2015-11-12 09:55:36'),(2,NULL,'Channel 16',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-11-11 09:17:31','2015-11-12 09:55:24'),(3,NULL,'Bijoy TV',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-11-11 10:54:10','2015-11-12 09:54:48'),(4,NULL,'ATN Bangla',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-11-11 11:36:21','2015-11-12 09:53:18'),(5,NULL,'ATN News',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-11-11 11:36:58','2015-11-12 09:54:13'),(6,NULL,'Banglavision',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-11-11 11:37:36','2015-11-12 09:54:28'),(7,NULL,'Boishakhi TV',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-11-12 04:53:04','2015-11-12 09:55:16'),(8,NULL,'BTV',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-11-12 09:23:42','2015-11-12 09:23:42'),(9,NULL,'Gazi Television',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-11-12 09:30:35','2015-11-12 09:59:03'),(10,NULL,'Channel 9',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-11-12 09:55:53','2015-11-12 09:55:53'),(11,NULL,'Channel i',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-11-12 09:56:07','2015-11-12 09:56:07'),(12,NULL,'Desh TV',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-11-12 09:56:12','2015-11-12 09:56:12'),(13,NULL,'Ekattor TV',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-11-12 09:56:23','2015-11-12 09:56:23'),(14,NULL,'Ekushey Television (ETV)',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-11-12 09:56:55','2015-11-12 09:56:55'),(15,NULL,'Gaan Bangla',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-11-12 09:57:00','2015-11-12 09:57:00'),(16,NULL,'Independent TV',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-11-12 09:57:08','2015-11-12 09:57:08'),(17,NULL,'Jamuna Television',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-11-12 09:57:23','2015-11-12 09:57:23'),(18,NULL,'Maasranga Television',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-11-12 09:57:27','2015-11-12 09:57:27'),(19,NULL,'Mohona TV',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-11-12 09:57:32','2015-11-12 09:57:32'),(20,NULL,'My TV',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-11-12 09:57:44','2015-11-12 09:57:44'),(21,3452,'NTV',127,'Left',12,23456,12,234,'2','',NULL,NULL,NULL,NULL,NULL,'56764','235567','120',23,43,5654,23,43,1,1,1,0,'2015-11-12 09:57:52','2015-11-17 12:16:24'),(22,NULL,'RTV',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-11-12 09:58:00','2015-11-12 09:58:00'),(23,NULL,'SA TV',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-11-12 09:58:08','2015-11-12 09:58:08'),(24,NULL,'Somoy Television',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-11-12 09:58:11','2015-11-12 09:58:11'),(25,NULL,'BTV World',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-11-15 08:19:06','2015-11-15 08:19:06'),(26,123,'Test',123,'Left',12,23456,12,234,'2','eee',1,NULL,NULL,NULL,NULL,'12345','12345','12',12,12,123,234,22,1,NULL,NULL,0,'2015-11-17 08:50:45','2015-11-17 12:13:25');

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

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` int(11) NOT NULL auto_increment,
  `user_type` enum('MSO','LCO','Staff','Subscriber') default NULL,
  `role_name` varchar(45) default NULL,
  `created_by` int(11) default NULL,
  `status` tinyint(1) default '1',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `roles` */

insert  into `roles`(`id`,`user_type`,`role_name`,`created_by`,`status`) values (1,'MSO','Admin',1,1),(2,'MSO','Staff',1,1),(3,'LCO','Admin',1,1),(4,'LCO','Staff',1,1),(5,'Subscriber','Subscriber',1,1);

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

/*Table structure for table `subscriber_privileges` */

DROP TABLE IF EXISTS `subscriber_privileges`;

CREATE TABLE `subscriber_privileges` (
  `id` int(11) NOT NULL auto_increment,
  `module_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `can_create` tinyint(1) NOT NULL,
  `can_update` tinyint(1) NOT NULL,
  `can_view` tinyint(1) NOT NULL,
  `can_delete` tinyint(1) NOT NULL,
  `created_by` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_privileges_system_sections1_idx` (`module_id`),
  KEY `fk_Subscriber_privileges_roles1_idx` (`role_id`),
  CONSTRAINT `fk_privileges_system_sections1` FOREIGN KEY (`module_id`) REFERENCES `system_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_Subscriber_privileges_roles1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `subscriber_privileges` */

/*Table structure for table `subscriber_profiles` */

DROP TABLE IF EXISTS `subscriber_profiles`;

CREATE TABLE `subscriber_profiles` (
  `id` int(11) default NULL,
  `subscriber_name` varchar(64) default NULL,
  `email` varchar(50) default NULL,
  `national_id` varchar(15) default NULL,
  `country_id` int(11) default NULL,
  `division_id` int(11) default NULL,
  `district_id` int(11) default NULL,
  `region_id` int(11) default NULL,
  `creator` int(11) default NULL,
  `set_topbox_id` int(11) default NULL,
  `smart_card_id` int(11) default NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `subscriber_profiles` */

/*Table structure for table `system_sections` */

DROP TABLE IF EXISTS `system_sections`;

CREATE TABLE `system_sections` (
  `id` int(11) NOT NULL auto_increment,
  `menu_name` varchar(40) default NULL,
  `url_route` varchar(255) default NULL COMMENT 'optional',
  `created_by` int(11) default NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  `status` tinyint(4) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Data for the table `system_sections` */

insert  into `system_sections`(`id`,`menu_name`,`url_route`,`created_by`,`created_at`,`updated_at`,`status`) values (1,'Package Management','package-management',0,NULL,NULL,NULL),(2,'User Management','user-management',0,NULL,NULL,NULL),(3,'Program Management','program-management',0,NULL,NULL,NULL),(5,'Billing','billing',0,NULL,NULL,NULL),(6,'User registration','user-registration',NULL,NULL,NULL,NULL);

/*Table structure for table `user_packages` */

DROP TABLE IF EXISTS `user_packages`;

CREATE TABLE `user_packages` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL,
  `status` tinyint(1) default NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  `created_by` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_user_packages_users1_idx` (`user_id`),
  KEY `fk_user_packages_packages1_idx` (`package_id`),
  CONSTRAINT `fk_user_packages_packages1` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_packages_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `user_packages` */

/*Table structure for table `user_types` */

DROP TABLE IF EXISTS `user_types`;

CREATE TABLE `user_types` (
  `id` int(11) NOT NULL auto_increment,
  `user_type` varchar(10) default NULL,
  `is_active` tinyint(1) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `user_types` */

insert  into `user_types`(`id`,`user_type`,`is_active`) values (1,'MSO',1),(2,'LCO',1),(3,'Subscriber',1);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `profile_id` int(11) NOT NULL,
  `username` varchar(40) default NULL,
  `email` varchar(300) default NULL,
  `password` varchar(40) default NULL,
  `user_type` enum('MSO','LCO','Subscriber') default NULL,
  `role_id` int(11) NOT NULL,
  `user_status` tinyint(1) default NULL,
  `created_by` int(11) default NULL,
  `token` varchar(40) default NULL,
  `created_at` datetime default NULL,
  `updated_at` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `fk_users_profiles1_idx` (`profile_id`),
  KEY `fk_users_roles1_idx` (`role_id`),
  CONSTRAINT `fk_users_profiles1` FOREIGN KEY (`profile_id`) REFERENCES `lco_profiles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_roles1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Data for the table `users` */

insert  into `users`(`id`,`profile_id`,`username`,`email`,`password`,`user_type`,`role_id`,`user_status`,`created_by`,`token`,`created_at`,`updated_at`) values (1,1,'superadmin','superadmin@mail.com','81dc9bdb52d04dc20036dbd8313ed055','MSO',0,1,0,NULL,NULL,NULL),(2,2,'lcoadmin','lco@mail.com','81dc9bdb52d04dc20036dbd8313ed055','LCO',3,1,1,NULL,NULL,NULL),(11,19,'msostaff','msostaff@gmail.com','81dc9bdb52d04dc20036dbd8313ed055','MSO',2,NULL,1,'622135f78082662413b50aaecc376f11','2015-11-15 09:07:33','2015-11-15 09:18:45'),(12,20,'lco','lco@gmail.com','81dc9bdb52d04dc20036dbd8313ed055','LCO',3,NULL,1,'8db6195fda56efeb7458e3b9efd2761d','2015-11-15 09:16:58','2015-11-15 09:16:58'),(13,21,'lcostaff','lcostaff@gmail.com','81dc9bdb52d04dc20036dbd8313ed055','LCO',4,NULL,2,'bf2bea2ff43c1413fa6a143438104f76','2015-11-15 09:27:46','2015-11-15 09:27:46'),(14,22,'subscriber','subscriber@gmail.com','81dc9bdb52d04dc20036dbd8313ed055','Subscriber',5,NULL,2,'357819d66be982ac9ffda638dc126635','2015-11-15 09:29:14','2015-11-15 09:29:14'),(15,23,'sample','sample@gmail.com','81dc9bdb52d04dc20036dbd8313ed055','MSO',2,NULL,1,'817cf94bc8d77208c614f8c6d48023d2','2015-11-15 10:12:57','2015-11-15 10:12:57'),(16,24,'jahir','mu.himel@gmail.com','81dc9bdb52d04dc20036dbd8313ed055','MSO',2,NULL,1,'f27eb29a2fc026bbd53864bd1d23c0c1','2015-11-16 06:56:31','2015-11-16 06:56:31');

/* Procedure structure for procedure `Get_User_LCO_Access_Role` */

/*!50003 DROP PROCEDURE IF EXISTS  `Get_User_LCO_Access_Role` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`himel`@`%` PROCEDURE `Get_User_LCO_Access_Role`()
BEGIN
	SELECT * FROM roles WHERE (user_type = 'LCO' AND role_name = "Staff") OR (user_type = 'Subscriber' AND role_name = "Subscriber");
    END */$$
DELIMITER ;

/* Procedure structure for procedure `Get_User_MSO_Access_Role` */

/*!50003 DROP PROCEDURE IF EXISTS  `Get_User_MSO_Access_Role` */;

DELIMITER $$

/*!50003 CREATE DEFINER=`himel`@`%` PROCEDURE `Get_User_MSO_Access_Role`()
BEGIN
	SELECT * FROM roles WHERE (user_type = 'MSO' AND role_name = "Staff") OR (user_type = 'LCO' AND role_name != "Staff");
    END */$$
DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
