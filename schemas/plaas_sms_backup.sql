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

insert  into `organization_info`(`id`,`organization_name`,`organization_phone`,`organization_email`,`copyright_year`,`logo`,`created_at`,`updated_at`) values (19,'nexdecade 2','nexdecade 2','nexdecade 2','0','ÿØÿà\0JFIF\0\0`\0`\0\0ÿá\0¢Exif\0\0MM\0*\0\0\0\0\n\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\01\0\0\0\0\0\0\0\02\0\0\0\0\0\0\0†;\0\0\0\0\0\0\0\0 \0\0\0\0\0\0\0\0Q\0\0\0\0\0\0\0Q\0\0\0\0\0\0ÄQ\0\0\0\0\0\0Ä‚˜\0\0\0\0\0\0\0\0\0\0\0\02015:11:09 05:18:45\0ÿÛ\0C\0		\n\r\Z\Z $.\' \",#(7),01444\'9=82<.342ÿÛ\0C			\r\r2!!22222222222222222222222222222222222222222222222222ÿÀ\0…ş\"\0ÿÄ\0\0\0\0\0\0\0\0\0\0\0	\nÿÄ\0µ\0\0\0}\0!1AQa\"q2‘¡#B±ÁRÑğ$3br‚	\n\Z%&\'()*456789:CDEFGHIJSTUVWXYZcdefghijstuvwxyzƒ„…†‡ˆ‰Š’“”•–—˜™š¢£¤¥¦§¨©ª²³´µ¶·¸¹ºÂÃÄÅÆÇÈÉÊÒÓÔÕÖ×ØÙÚáâãäåæçèéêñòóôõö÷øùúÿÄ\0\0\0\0\0\0\0\0	\nÿÄ\0µ\0\0w\0!1AQaq\"2B‘¡±Á	#3RğbrÑ\n$4á%ñ\Z&\'()*56789:CDEFGHIJSTUVWXYZcdefghijstuvwxyz‚ƒ„…†‡ˆ‰Š’“”•–—˜™š¢£¤¥¦§¨©ª²³´µ¶·¸¹ºÂÃÄÅÆÇÈÉÊÒÓÔÕÖ×ØÙÚâãäåæçèéêòóôõö÷øùúÿÚ\0\0\0?\0öKmf[­6ÓP‰ÃpÑ\0FØwİ®{ƒÛƒšÛ®G²²´²°‘¯Pê\röHdo¼åeSÀÏ^z8ã$÷õuTšÄÅ¶µ\n(¢  ¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(Ÿñßü“ÏØ*ëÿ\0E5À¾\0ŸøE4?üCÿ\0ÄÑã¿ù\'&ÿ\0°U×şŠjß<‚)JöĞ,ğe¾ã\r2]>øVÓMi˜£Ì»	„û8‘ıáVlo~jMl¶z“\'Ú¼Án°Vb€–Tc†>èçÛ‘Lğ€5\nZÃo{¡xnâå$•­íö•œ\0à}ñÇåU´O†:Ö›aàÈ&º°fÑ/®..JHä:ÈI2œŸ\\â›òòÿ\0ƒ÷VÖŞ­ŒksÂZŞ…¤ê¾ğÖ—-Ö¦–rÇ¤<‘º#H…2wàw­\r3Ä\r®\'Õ—Tğn™¦Ãc¨$ôbèç8ØBDŸác‘Nµøeâ+}GĞüÍ,ÛézÚê+sö™7Ëö$ò°­‚?ˆ¼Šµ¨x*ãMÑ<{¤ö\r£jÏ> ·FfÀûw.cÙg!û9¤ä’¿õöà×•—óÿ\0€tvºÃëİfûI¶ğî…%õŠ£\\Æ4¸ñq•ËlÛ’;\\ŸŠµ/xOÆÚvƒwà-ín¡If¿[H€·åeòún\'pëùí|Ón`ğBjú‹´º–±!ºšW3.6Æ¶Ğü\n­ëŞ>!ñÄº•é·}\"}ôÙbÜ|İæMÁ€Æ08ç9ÈéU$ã$¾ÿ\0»o¿BbÔ¢ßİ÷ïú˜ºe×Ãë›=RëRğ…a–¬úTJ¶Î÷1·b,{‰9û •|ÿ\0Â«]0j-£hkoö±dÛ´p\'\'[¡r÷€®BøI®hº5˜7ö7:–®JòH#2¨¥]¶’ŒBç 6=ë¨Ò<7âû_Rµ“GşÕÕõ5¹¸†V• †ÆÔ`³ã•\0Ôiù~Ÿğ},üÿ\0_ø·6ÿ\0áğı\nšş¡ÿ\0âhÿ\0„Áÿ\0ô*hø.‡ÿ\0‰®‚Š@sÿ\0ğ‚x?ş…Mÿ\0Ğÿ\0ñ4Â	àÿ\0ú4?üCÿ\0Ä×AE\0sÿ\0ğ‚x?ş…Mÿ\0Ğÿ\0ñ4Â	àÿ\0ú4?üCÿ\0Ä×AE\0sÿ\0ğ‚x?ş…Mÿ\0Ğÿ\0ñ4Â	àÿ\0ú4?üCÿ\0Ä×AE\0pzƒ<+6±ât—Ã:3¤:š$JÖ‹öKvÂü¼\rÌÇ¹\'½Tñø{áËé-\'ğ^ŸrĞ[»¦¶ÒíÊÛB[hwİ‚yÏ¸àŠéü=ÿ\0!ÏØU?ôŠÖ¹Oøg]ºÖõçÓ4ÿ\0µÃ®i)`%\"‹iˆÜá˜»\\Ÿ”1ÈéIŞúZ?Ôjİ­IuH¼¤]Ù¥ßlÖÊîá-£Ô•oäy>PAùğ¼¯=k¦ÿ\0„Áÿ\0ô*hø.‡ÿ\0‰®ZïLÖîüKao¨è\Z…Î£yFÉ-f¶Ûs2€<éwÌ¬ÿ\0\nî}+Òj´¶ŸÒş¿«’›êsÿ\0ğ‚x?ş…Mÿ\0Ğÿ\0ñ4Â	àÿ\0ú4?üCÿ\0Ä×AE!œÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0ü ÿ\0¡SCÿ\0Át?üMğ‚x?ş…Mÿ\0Ğÿ\0ñ5ĞQ@ÿ\0?äxgşÁV¿ú)k ®ÀŸòO<3ÿ\0`«_ıµĞP?ã¿ù\'&ÿ\0°U×şŠj?áÔÀÿ\0‘Ï\\ÿ\0¿6_üGÿ\0äx›şÁW_ú)«}¾éúR“²¸}£jÖŞ hÿ\0²¼oâ»˜är‹:èñˆr:æCftîk¢ÿ\0„{SşG=sşüÙò=y÷Â‹{İ+Ãöú~¡7‰-.³Ÿìé4–[c¸¶	”Á‘¿ë?•sşğıŸ€¥\\Ãro®ÓRw²`şKd1+„€ÜsMùy~#–—ùş;ØµC{¥XêzOŠüY«Z]İ‹PöZ}¡òÎH.á­”ª9jÒÒës_Ã§xó\\šK†¶¹e´_.AÕ~kaŸ¨È¯\'Ó<-ugáX/†ï#Ö-<I—².™ &ïµŒ»0èpH\0öÍ_“À±=§-$Ñ×U[éoô›µ°`¢4Ãª¤Ûv€zm\rëÇ]%~ŸşÏù°k[.ÿ\0çşHõ¿øGµOúõÏûóeÿ\0Èõ‰}k¥øÏ@½ø‡­C©Ş¨kxZÚÓ	 |ßeÚ	*F	şb¨ü#ûN¹g©ø×Qeæµ2ª.r†ÅêwÊ³¼sà»¯üLÂÛÏk Ÿ²ê&ÙÒÌY>~½³œM®V“ùı×m$Úşµ±Ôé16¸/ã½r±Ü½¬ÿ\0èÖ‹²UÆåù­†q‘Èâ´áÕ?ès×?ïÍ—ÿ\0#×h6!¶ğ„Ë­øDİİ]ø•æ¸7šl·1À\Z0\Zo!9•yp1Æ{ô¨¡ğî·?…ltùtA­áñˆ’8EŒ±*Z‘’ËÉHòXòxÉç4%woOÆßçøÿ\0?×üdÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_üGü#Ú§ızçıù²ÿ\0äzñıOÂ7öºv¼ú~v“Ûx9tß&Ñ³9šÊ™%xàg «şÖ†·s>±öËmf-p]Eym¡Ïu4ÑpVé\\\"Ã°à¡ÆĞ})GŞ·õÛüÿ\0\0z_Ëşù~\'¬Â=ªĞç®ß›/şG£şíSş‡=sşüÙò=tP?ÿ\0ö©ÿ\0C¹ÿ\0~l¿ùøGµOúõÏûóeÿ\0ÈõĞQ@ÿ\0ü#Ú§ızçıù²ÿ\0äz?áÕ?ès×?ïÍ—ÿ\0#×AE\0pz…¨¾±âu_k1˜õ4VeŠÏ2²[Í˜Î`aGÉ;ŸğjŸô9ëŸ÷æËÿ\0‘èğ÷ü‡<Yÿ\0aTÿ\0Ò+Zâ¼o«Ãmñ{=K_½Ó4ïì<k\rãÛ¡¸8S¹Hù±ĞgæÀô¤İ¿¯++ÿ\0^v;_øGµOúõÏûóeÿ\0ÈôÂ=ªĞç®ß›/şG®Ã·\Zö¯â]\rVûQY“ÃQ_=ªÜÉËp%`¦@¤ApqŠ©á­VïP—Ã·©]ë·“Îšı“^H|ˆ¾må¢Èû[hR¡ æªÚÛúİ¯Ó^ÄßKÿ\0[_úîtÉ¬XÉ2Æ¿<BwŞ‹“û>ßË’|°Ø¯öM­ÊœHÏ\"µ4ÛiuµıƒÇZä¿d¹{Yÿ\0Ñ¬×d©÷—›aœg¨â¼÷Ãæm\'Á~’Æòöuâ¿&p.ä!ãÌ6à¶0p2Ş<œšIuKOÒµci7‘e/Œî#¿˜İ5°H0\Zd¢RÛAaôïJ:¯ëû¿ü‘RV×÷¿ùÔáÕ?ès×?ïÍ—ÿ\0#Ñÿ\0ö©ÿ\0C¹ÿ\0~l¿ù¼úâçTÁqK\'‹´Ç¶Wy#hµ¹ŠKnwÙšõT0pIÁ<œÍzO…/ÛUğ¦—|Ğ\\Àg¶Gòî›t‹‘üM»êG={Ó¶ÿ\0ÖêäßR¿ü#Ú§ızçıù²ÿ\0äz?áÕ?ès×?ïÍ—ÿ\0#×AE!œÿ\0ü#Ú§ızçıù²ÿ\0äz?áÕ?ès×?ïÍ—ÿ\0#×AE\0sÿ\0ğjŸô9ëŸ÷æËÿ\0‘èÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_ü]Ïÿ\0Â=ªĞç®ß›/şG£şíSş‡=sşüÙò=tP?ÿ\0ö©ÿ\0C¹ÿ\0~l¿ùøGµOúõÏûóeÿ\0ÈõĞQ@ÿ\0ü#Ú§ızçıù²ÿ\0äz?áÕ?ès×?ïÍ—ÿ\0#×AE\0sÿ\0ğjŸô9ëŸ÷æËÿ\0‘èÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_ü]Ïÿ\0Â=ªĞç®ß›/şG£şíSş‡=sşüÙò=tP?ÿ\0ö©ÿ\0C¹ÿ\0~l¿ùøGµOúõÏûóeÿ\0ÈõĞQ@ÿ\0ü#Ú§ızçıù²ÿ\0äz?áÕ?ès×?ïÍ—ÿ\0#×AE\0sÿ\0ğjŸô9ëŸ÷æËÿ\0‘èÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_ü]Ïÿ\0Â=ªĞç®ß›/şG£şíSş‡=sşüÙò=tP?ÿ\0ö©ÿ\0C¹ÿ\0~l¿ùøGµOúõÏûóeÿ\0ÈõĞQ@ÿ\0ü#Ú§ızçıù²ÿ\0äz?áÕ?ès×?ïÍ—ÿ\0#×AE\0sÿ\0ğjŸô9ëŸ÷æËÿ\0‘èÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_ü]Ïÿ\0Â=ªĞç®ß›/şG£şíSş‡=sşüÙò=tP?ÿ\0ö©ÿ\0C¹ÿ\0~l¿ùøGµOúõÏûóeÿ\0ÈõĞQ@ÿ\0ü#Ú§ızçıù²ÿ\0äz?áÕ?ès×?ïÍ—ÿ\0#×AE\0sÿ\0ğjŸô9ëŸ÷æËÿ\0‘èÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_ü]Ïÿ\0Â=ªĞç®ß›/şG£şíSş‡=sşüÙò=tP?ÿ\0ö©ÿ\0C¹ÿ\0~l¿ùøGµOúõÏûóeÿ\0ÈõĞQ@ÿ\0ü#Ú§ızçıù²ÿ\0äz?áÕ?ès×?ïÍ—ÿ\0#×AE\0sÿ\0ğjŸô9ëŸ÷æËÿ\0‘èÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_ü]Ïÿ\0Â=ªĞç®ß›/şG£şíSş‡=sşüÙò=tP?ÿ\0ö©ÿ\0C¹ÿ\0~l¿ùøGµOúõÏûóeÿ\0ÈõĞQ@ÿ\0ü#Ú§ızçıù²ÿ\0äz?áÕ?ès×?ïÍ—ÿ\0#×AE\0sÿ\0ğjŸô9ëŸ÷æËÿ\0‘èÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_ü]Ïÿ\0Â=ªĞç®ß›/şG£şíSş‡=sşüÙò=tP?ÿ\0ö©ÿ\0C¹ÿ\0~l¿ùøGµOúõÏûóeÿ\0ÈõĞQ@ÿ\0ü#Ú§ızçıù²ÿ\0äz?áÕ?ès×?ïÍ—ÿ\0#×AE\0sÿ\0ğjŸô9ëŸ÷æËÿ\0‘èÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_ü]Ïÿ\0Â=ªĞç®ß›/şG£şíSş‡=sşüÙò=tP?ÿ\0ö©ÿ\0C¹ÿ\0~l¿ùøGµOúõÏûóeÿ\0ÈõĞQ@ÿ\0ü#Ú§ızçıù²ÿ\0äz?áÕ?ès×?ïÍ—ÿ\0#×AE\0sşÿ\0’yáŸûZÿ\0è¥®‚¹ÿ\0É<ğÏı‚­ôR×A@ÿ\0ÿ\0äx›şÁW_ú)¨ÿ\0„‡Tÿ\0¡3\\ÿ\0¿Ö_ü‘Gÿ\0äx›şÁW_ú)«}¾éúRnÊà`ÂCªĞ™®ßë/şH£şSş„Ísşÿ\0YòEp	®ou=ÛRÔ ñåÂÍ?üL%Õ™­¾RØ\rŸ\'ÓıYçó£Aø•â]JÜ]&’±k×¡\"µ4\rB¾L§pÎ2¸gŸFôü?†Õ¯åÀïÿ\0á!Õ?èL×?ïõ—ÿ\0$U{íRóR°¸²ºğV¼Ö÷´R*ÜÙ¡*FÊÜ‚8ô5äZN¯ª\\øÂ·šÔ¶úÂÍâHâ‡íyšã˜%ù±Øß5­§x£ÆZM5x¯­µ(t­fE–ÚêXˆƒ\rŞQóq\ZÈ\\hi[_ëoó4ôş·ÿ\0#Ñ4ËûL·Ó´ÿ\0kpZ[ (Äögjss“õ5oşSş„Ísşÿ\0YòEPğw‹®<]ªkSÛ-¹Ğmd9Ğó>ĞÒsŒ€0+\\óõ/–:4º†¥œú#NĞZßMn¦A#\0ß»eçİî¯×ü®%k;tÿ\0;Wü$:§ı	šçış²ÿ\0äŠ?á!Õ?èL×?ïõ—ÿ\0$Wšéß5ÂmämFâ=}ô‹MBâœ˜SgH¾iœ0$Ië©|{âèôVxràÎš§ÙZóû&ä\'Ù¹?hä‰1İÁïÈ´µ×—ãoóAf¿®¿äÎÃşSş„Ísşÿ\0YòEğêŸô&kŸ÷úËÿ\0’+“²ø…­^è\ZV‹}¤İÏ©ı¢VÔ¾É2Â±ÄOpûÏËàuç\"¥»ñGcñÍ¿…-#ğü·\'Oûd³Ê“FŸëŠü 3¹”÷\'æ `»j—ëôïätÿ\0ğêŸô&kŸ÷úËÿ\0’(ÿ\0„‡Tÿ\0¡3\\ÿ\0¿Ö_ü‘^acãÍ-PèÚn™o5çŒO‘f‘[wY2}îL/°¨üYã_ÜïÓ¡oiw¦x’ÚÍîlc–4$V+¸y™ÀÁÜ›xäc”µ·¿šV½üÿ\0ÿ\0“=OşSş„Ísşÿ\0YòEğêŸô&kŸ÷úËÿ\0’+Í.ü_sá?xÚäi–\Z¬fÂsË\ZÍ$‹€Î­#Uô\\Ğõ©âOˆş ğ¼^$²¸‡LºÔtÈm®`¸	WT*éæqóóéGDû…µ±Ûÿ\0ÂCªĞ™®ßë/şH£şSş„Ísşÿ\0YòEex_Å:µÿ\0Œµj¢ÊF³µ‚æ9íax•!³ŒAø®Ö‚S¹ÁèZî¢šÇ‰Ù|\'¬ÈdÔÑ™V[<Æ~Én6¶g8\0ñ‘†ç Oºçş¿øH¿áñÛ\r—Ø¶yö^Íûó?;³ïjÕğ÷ü‡<Yÿ\0aTÿ\0Ò+ZzêGÇ’i¦_ô1¦,â=£ï™Ysœg g-×Ïò ŞÏúê†ÂCªĞ™®ßë/şH£şSş„Ísşÿ\0YòEeIâû­3H¹»¹X&?ÚóÙ¬×S}ŞVm¦I6ÑÀPH9,<æ™¯|C‹D;V\Z9º[%¼.5uX6p°3\'ïIÚİB»’3ÂM5ëkÎöş·±±ÿ\0	©ÿ\0Bf¹ÿ\0¬¿ù\"øHuOú5Ïûıeÿ\0Éñ-åî«me¤i±\\$öQß‹›“\n¤nH–à:uÉ¥s¬^øFÃFÒî$·»»¸V^êWÍo“ ”ls¸–;º§9v£ÿ\0	©ÿ\0Bf¹ÿ\0¬¿ù\"øHuOú5Ïûıeÿ\0É.³­^ØêZ~a§Gwuz’²™n|˜ãÙ´Çkİ”œãŒFd^1½¼]6-şíîb’)®ü¸à’\nù}„²ç8!rxàd{şSş„Ísşÿ\0YòEğêŸô&kŸ÷úËÿ\0’*‘ñÖázºLm¢ÿ\0°5Øºıöÿ\03ÊŞ\"ÙgËàãµ‹k¨kÂK«_™#xõíbû6¤ò*.è—jÅ$^ZŒ6KmÜw0ãƒBÕÿ\0^_æOë×üŸşSş„Ísşÿ\0YòEğêŸô&kŸ÷úËÿ\0’+œÿ\0„Ühm­ÅÍ“]\\jW¾[jšÙaH£“ŞU~e@é˜­©|P·ş·×í<ØDíÄ2!*LªŒeaœŒã‘ÓX©z~ ôv,ÿ\0ÂCªĞ™®ßë/şH£şSş„Ísşÿ\0YòEQÿ\0„ÆóíŠÍ£*é£S:cÜı«2ŞQ\\G³m,$ğ@åéšÎ¥§ÜxXÕmÌ². ¶6°E©Èè]¼µHÄl«\Z°%ñ[=9»[š¦Ÿ×_ògEÿ\0	©ÿ\0Bf¹ÿ\0¬¿ù\"øHuOú5Ïûıeÿ\0ÉFãÆ7ºl:”z–Œ‹¨Y,,·~bO¯±J»\"aƒx[ßë–“Ëfº¤·°Y}¶e\Z‘ÜãhsâÇh#å®HÀÜ›HveßøHuOú5Ïûıeÿ\0ÉÂCªĞ™®ßë/şHªšwŒ.®îÒ+À·Zsj6!.•ŞXÔ®UÁ\n±¿ÎŸÄËÏŞ¬+ÏˆW×Ú¹ı4‡Ô4øb›Î²ÔÅÌ!°á¼¯¾\nò¥q‚j¬ÉºµÎ£şSş„Ísşÿ\0YòEğêŸô&kŸ÷úËÿ\0’*…ÿ\0Š.´BD½¶cp,à)gÂ´M<³ÕC´jÜ¹bqáÈån<c{¦Å©G©hÈ·öBŞA­ßš“G+ìR®È˜`Á†Ï<%ª¸ú—¿á!Õ?èL×?ïõ—ÿ\0$Qÿ\0	©ÿ\0Bf¹ÿ\0¬¿ù\"¥Ñu«ËíGPÓu>+;Ë?-È†àÍ‘ÈÖQr¬Çn¦¶èŸÿ\0„‡Tÿ\0¡3\\ÿ\0¿Ö_ü‘Gü$:§ı	šçış²ÿ\0äŠè( şSş„Ísşÿ\0YòEğêŸô&kŸ÷úËÿ\0’+ ¢€9ÿ\0øHuOú5Ïûıeÿ\0ÉÂCªĞ™®ßë/şH®‚Š\0çÿ\0á!Õ?èL×?ïõ—ÿ\0$Qÿ\0	©ÿ\0Bf¹ÿ\0¬¿ù\"º\n(Ÿÿ\0„‡Tÿ\0¡3\\ÿ\0¿Ö_ü‘Gü$:§ı	šçış²ÿ\0äŠè( şSş„Ísşÿ\0YòEğêŸô&kŸ÷úËÿ\0’+ ¢€9ÿ\0øHuOú5Ïûıeÿ\0ÉÂCªĞ™®ßë/şH®‚Š\0çÿ\0á!Õ?èL×?ïõ—ÿ\0$Qÿ\0	©ÿ\0Bf¹ÿ\0¬¿ù\"º\n(Ÿÿ\0„‡Tÿ\0¡3\\ÿ\0¿Ö_ü‘Gü$:§ı	šçış²ÿ\0äŠè( şSş„Ísşÿ\0YòEğêŸô&kŸ÷úËÿ\0’+ ¢€9ÿ\0øHuOú5Ïûıeÿ\0ÉÂCªĞ™®ßë/şH®‚Š\0çÿ\0á!Õ?èL×?ïõ—ÿ\0$Qÿ\0	©ÿ\0Bf¹ÿ\0¬¿ù\"º\n(Ÿÿ\0„‡Tÿ\0¡3\\ÿ\0¿Ö_ü‘Gü$:§ı	šçış²ÿ\0äŠè( şSş„Ísşÿ\0YòEğêŸô&kŸ÷úËÿ\0’+ ¢€9ÿ\0øHuOú5Ïûıeÿ\0ÉÂCªĞ™®ßë/şH®‚Š\0çÿ\0á!Õ?èL×?ïõ—ÿ\0$Qÿ\0	©ÿ\0Bf¹ÿ\0¬¿ù\"º\n(Ÿÿ\0„‡Tÿ\0¡3\\ÿ\0¿Ö_ü‘Gü$:§ı	šçış²ÿ\0äŠè( şSş„Ísşÿ\0YòEğêŸô&kŸ÷úËÿ\0’+ ¢€9ÿ\0øHuOú5Ïûıeÿ\0ÉÂCªĞ™®ßë/şH®‚Š\0çÿ\0á!Õ?èL×?ïõ—ÿ\0$Qÿ\0	©ÿ\0Bf¹ÿ\0¬¿ù\"º\n(Ÿÿ\0„‡Tÿ\0¡3\\ÿ\0¿Ö_ü‘Gü$:§ı	šçış²ÿ\0äŠè( şSş„Ísşÿ\0YòEğêŸô&kŸ÷úËÿ\0’+ ¢€9ÿ\0øHuOú5Ïûıeÿ\0ÉÂCªĞ™®ßë/şH®‚Š\0çÿ\0á!Õ?èL×?ïõ—ÿ\0$Qÿ\0	©ÿ\0Bf¹ÿ\0¬¿ù\"º\n(Ÿÿ\0„‡Tÿ\0¡3\\ÿ\0¿Ö_ü‘Gü$:§ı	šçış²ÿ\0äŠè( şSş„Ísşÿ\0YòEğêŸô&kŸ÷úËÿ\0’+ ¢€9ÿ\0øHuOú5Ïûıeÿ\0ÉÂCªĞ™®ßë/şH®‚Š\0çü	ÿ\0$óÃ?ö\nµÿ\0ÑK]sşÿ\0’yáŸûZÿ\0è¥®‚€9ÿ\0ÿ\0É<ñ7ı‚®¿ôSQÿ\0	×ƒÈÿ\0‘¯Cÿ\0ÁŒ_üU;ÿ\0’yâoû]è¦®‚€<óHo†ZÅÓ<Mcm30|Fæ¹ŒÍ´ç=ÅŸğªìaÒ¢¶Öt4M*gšÈlå;œ±æO›>ù¯C¢€zn¶ß	ÓËUÖôWbö8·O•À“½SÍÚ¼“À\0{Sï®¼\0°ëw:_ˆtÔ5HdYR]om¼®É·sÆ¯¦H\\õõ¯E¢“I«\r7{uàOÂğe†Šş-ğûÏ–ãÔb*Ò1Ë’9Àã «\Z¥×Ã]cWV»ñ&˜5àû:Ü[ëÆİ„y\'oîå^2Mgk_¯´ß‰iY@Ş‚xlïïYNøç”1@lcŸ”ô=8¥Ô~#ë–~$ñ†„g‡F³ÀéååIvùwL2„ò\rØê3MË›Ş~‡üÀ#½Õåø¿ó/¼ÿ\0[A·Ñ­áÔ°¶u’‹R\Z7SêêáÃçøÉÉÉäÓ¿\r@8ñU¨Éæ¿á&—Í\'nßõvìcŒgÕ_Nø°±èzúÆƒªı¿Wä†;t•f(¡ÅYÀ9ÀÜ3ëÍbø«ÇÚ½ŒgÓ5KØ.,#±xmntøWìnİÃvX¹ œ†Q´ôÍ{1G[	?\nşÁcfšÎ‡\nX;=´–ú¸ŠhÙ³¸‰R@ùló–ç¾j}>óá¦™ªE©ÚøƒFÑ[µ²Ï&²$o-œ¹t‡$±\'\'\'´ÛŠšV—k¨jéú¥µŞŸ3ËÇyRF\n6ÈTŒ‘œFzUüNÿ\0„~Ï]·Ò¬d›TÒ¡‚gk„S\0YY@\'õÇÈíšåûß×õäax«NğF¥mg‡âY†ÖÓT¾/¬íi;Ê0bUxÆĞ=«{oÂ¯ì™´Ó­h†Şk¡y#dZpr$2ù›÷g¾ê¾ÿ\0 ·bŸDÕ$Ô#±7÷V°KAÇÎI—n8PÅ°9º­/RµÖ4»]JÆO2Öê1,O‚2¤dpyím?®ß§à½Ÿõıkøœ>ß…E5%—ZÑ\'şÓ\"¼kdLÒ„BYä$ıáƒsškÅğ¦m>şÆ}oF-A‘®}oÌ–]˜Ø)|â½Š@q–š×Ã»jçX·ñ†—÷1$3Kıª‡r\'\n0_Â´ÿ\0á;ğı\rzşaÿ\0â« ¢€8=Æ‡Xñ;Ëâ]mM&kø€uû%ºå~nFåa‘ÜÚ¤Õu¯j7ğjxïOÓ¯¡¢š•¶Z2A(Ë u# ™py9Øğ÷ü‡<Yÿ\0aTÿ\0Ò+Z©ªx«RƒÆ©á/Hµº´ÿ\0·™nošælÚÄù9ÁíG_ëúØ}õÖ<o¤G§éş?±±Ä,³ÃhòNìIrşb°É$“€=°8ª±Ïàk%µşÈñí”öö©hZ×Rµo64$¨a(qK€Ì}±³§|@ÒnôµİÇuku²Ásd–ïq,2D@“ˆ•‰A‘óã2çâ¢Oˆštş/°Ñ- âÚöÄ^E}R²`\0ÀLmÁÉr@SÁÁ£¯õÙş—Oë¿ù‹ÄŞ\n‹V:™ñ†‘%ËZ¥³Ô`ÁUbÙÀ#’Xû{U}w^ğ†½lÖ’øûO¶´’6Šâ{ûM³£pCVaÆGÊTóô­İ?Åz6©©\r>ÒêF¹hŒÑ‰-äfŒÆì¡d^G*Hç=+3Rñå¦•ãdğíÅ•ÙCd×ouÈÚ¨r¸ä¸8ƒÍ\Zh½Vÿ\0Që«şºÀ1u½sÃ7úî‡%ŸŒôË,¢MÅ®©l\Z2Á®X0 0ÁR;ğpjæ©xLm=áñ~–Ïd³aäÔà&V”ƒ#¿<±#<`rxÆ\0Û“ÆşŠÆÆñµmï­äº·e†Fßi½Ûr0=qé×Š,<oáÍNi\"¶Ô—)nn÷ÍÄàÈ®ê”w*Hm¸º#œûG\rŞãã«#aö¿¶ÿ\0fNÛÈó·oİœy˜ßóíß·=±ÅZ—UğÖWö­ã\r(G{v·’©Áà¡Àç§îÇ¯~k Ñ¼U£kó¼\Z}Ì2Ä³ùs[É4m÷]DŠ¥”ãïŠæücñ)<-¬O¦¥´³Ad.ñuzmÌùb¢8FÆóƒÇÏÚ_ÖŸ€o¯õıkø‘Iwào9nm<wecx³Ï2ÜÁ©Z–fé‡¥I\nyRFÑÏ\\ßºñƒoôUÓo|o¦N#5Ãj6Ë#”pà¸^ª:(­ÍcVšÇÁ÷ÚÌ0	.’…û¬°W\0úğ@5—ô«]KºÖ.|«»­=/¥ŠÚÚY|¤*;Q2q¹°8<ğhµ½ŞÖıÈ7³ïëñ(WÀfÑ­¿á0Ò¶6 5´àÏ˜$c¯İÈé×é\'Ôüqa¨Ú?‹ô°·×_kiT„<R¥YxÚQHÎyäq[z|?¥N ¹¾f•­Eâ¥½¼““Ïï–­•àäöêzŠSñç†´ˆ\"ïQ&)-Öè<òÎ& +±[j’pc=¨Zh¿¯êß€nïıZş\'>.¼$7ŸlñÕíÕÛCæİÍ©Z‰6ÄÛÑ\0@¨ô\\Ç\'¦4§ñçÔno_ÅÚ@’âĞY¸\Z”	–9õùøUŸ‰qµç‰llôë•›JÓş×ÓÛLFòÙğêQ|±òÿ\0\rİ«kÂ¾2ÓüEkg§%”wrÃäI\ZÀnhË:†8Ê–ÇsE¹¿®÷ÿ\0‚·õéÿ\0\0Â¹Ÿáåİ¼Mâı1£‡K“K\0j\rÑ>Ì’Aûß»Œ¼T*şxõ1yãûKÉ5D´’Yµ+UdD,W`EU=læ½\"Šwoúş»…´·õıh?º½ğ-ú»^øâÂ{‡·´NÙä2$ƒf\0pÄ˜àduË×d†óí:±½º»h|Û¹µ+Q&Ø›z ƒ‹“¸äôÇ¡ÑHJx&\rbïS_èæk¨¢‰ÔêPí2Äcœçç9çÒ®ÿ\0Âwàÿ\0ú\Zô?üÃÿ\0ÅWAE\0sÿ\0ğø?ş†½ÿ\00ÿ\0ñTÂwàÿ\0ú\Zô?üÃÿ\0ÅQàOù\'ÿ\0°U¯şŠZÀµ7·×µ¼;ºf‡}-­Ó%ùûC,x/\"Fc\np§8.:\Zfoÿ\0Âwàÿ\0ú\Zô?üÃÿ\0ÅQÿ\0	ßƒÿ\0èkĞÿ\0ğcÿ\0L“Ç\ZŠH‘õûÄ…Ì‹B_õ~c…+lŒ#¨«©â]&MrM.ïb!d†4b»‚´›v#¥³íA7*ÿ\0Âwàÿ\0ú\Zô?üÃÿ\0ÅQÿ\0	ßƒÿ\0èkĞÿ\0ğcÿ\0Pÿ\0ÂÁğ¸µ¸»:¦ÛX3ºá ”DàH#%nÙ;(%	#8ªÑxÖŞMzlİÛÇ£Ç¤¶ V[KˆîB«dÃ ^\0|Ç¨È «2ÿ\0ü\'~ÿ\0¡¯Cÿ\0ÁŒ?üUğø?ş†½ÿ\00ÿ\0ñUN_ˆ67³Ù\\Kw-½›^$Km2ùñ7!)ó®x,¹©éU4É®\\x^HŞÎ\rZ[k¨gTVÄO°#(İËû¹¡kızÿ\0øNüÿ\0C^‡ÿ\0ƒøª?á;ğı\rzşaÿ\0â¨°ñ·‡µ;ø¬í/ËÉ3H°9‚EŠr‡#‘”$„s÷Ièiº_|9¬İZÛXj\r+İ†6ìmåD—hË*»(RÃºç#Ò€Øwü\'~ÿ\0¡¯Cÿ\0ÁŒ?üUğø?ş†½ÿ\00ÿ\0ñUĞQ@ÿ\0ü\'~ÿ\0¡¯Cÿ\0ÁŒ?üUğø?ş†½ÿ\00ÿ\0ñUĞQ@ÿ\0ü\'~ÿ\0¡¯Cÿ\0ÁŒ?üUğø?ş†½ÿ\00ÿ\0ñUĞQ@ÿ\0ü\'~ÿ\0¡¯Cÿ\0ÁŒ?üUğø?ş†½ÿ\00ÿ\0ñUĞQ@ÿ\0ü\'~ÿ\0¡¯Cÿ\0ÁŒ?üUğø?ş†½ÿ\00ÿ\0ñUĞQ@ÿ\0ü\'~ÿ\0¡¯Cÿ\0ÁŒ?üUğø?ş†½ÿ\00ÿ\0ñTxşIç†ìkÿ\0¢–¹©~&İÂÚ…Ëè06ÇYşÈ’D¾&vmÁC¬^^ù‡óÖ…«·õÛõ¢¿õıht¿ğø?ş†½ÿ\00ÿ\0ñTÂwàÿ\0ú\Zô?üÃÿ\0ÅSÇŒt¬e‹ânMÁµ\räIå€É‹ÍÛåïÇğîÎxÆj´¿|/\rÓ[I©•t¹k7co.Ä™s˜ËíÚƒ€O=³@Âwàÿ\0ú\Zô?üÃÿ\0ÅQÿ\0	ßƒÿ\0èkĞÿ\0ğcÿ\0N‡ÆzÅƒİÃu3¬wÙ¡“yâP2SÉÛænœmè	è*ñ÷†’ŞÆía|Ò%´qÚÊòHèÁ]|°¥ƒGÊF}¸4\'ü\'~ÿ\0¡¯Cÿ\0ÁŒ?üUğø?ş†½ÿ\00ÿ\0ñU•áÿ\0ÛŞİŞÚj²ÃÏö½Í…”QE!i,Ì9Æù˜áGµt\Z/ˆô¿ÆÒi“É4jöòF®¤‘¹¨¹•È÷ :Ø©ÿ\0	ßƒÿ\0èkĞÿ\0ğcÿ\0Gü\'~ÿ\0¡¯Cÿ\0ÁŒ?üUV½Õïî>\"éÚ&Ÿ8K[kI/5 [po’$É;›Œ\nƒ]ñ•şâ·Ğtı\"ÖêHô¶Ô[›ãn¡Ê•ùèrHJWV¿¯áòc³½¿­áÑ¡ÿ\0	ßƒÿ\0èkĞÿ\0ğcÿ\0Gü\'~ÿ\0¡¯Cÿ\0ÁŒ?üUP´ø‘ Ë¡iÚ•Ü“Úµå§Û\r¸·’g† p]üµ;S=°çR?‹|¿¶–òÙ®º(ÔÍÑ<ÿ\0¬+ÙÛ³o=?\Z«4íıiÿ\0$î®¿¯êå¿øNüÿ\0C^‡ÿ\0ƒøª?á;ğı\rzşaÿ\0â«+^ø—¤é:§¨ZÃuus`±³ÙÍk5³áÎøòàüØÇÏ\"­Yøµn|GäÉsim§e}¼ÇsğÜG‡*ÎÛÔ(Œ\0zà÷éÍ/ëõëúûËğø?ş†½ÿ\00ÿ\0ñTÂwàÿ\0ú\Zô?üÃÿ\0ÅS-<wáËØg–+éa´7§Îµš\"ğ\0I‘ 2/W=½EXÒ|[¢k—¿dÓï\ZYŒ\"áA$bH‰Æô, :çŒ©\" \"ÿ\0„ïÁÿ\0ô5èø1‡ÿ\0Š£ş¿ÿ\0Ğ×¡ÿ\0àÆş*º\n(Ÿÿ\0„ïÁÿ\0ô5èø1‡ÿ\0Š£ş¿ÿ\0Ğ×¡ÿ\0àÆş*º\n(Ÿÿ\0„ïÁÿ\0ô5èø1‡ÿ\0Š£ş¿ÿ\0Ğ×¡ÿ\0àÆş*º\n(Ÿÿ\0„ïÁÿ\0ô5èø1‡ÿ\0Š£ş¿ÿ\0Ğ×¡ÿ\0àÆş*º\n(Ÿÿ\0„ïÁÿ\0ô5èø1‡ÿ\0Š£ş¿ÿ\0Ğ×¡ÿ\0àÆş*º\n(Ÿÿ\0„ïÁÿ\0ô5èø1‡ÿ\0Š£ş¿ÿ\0Ğ×¡ÿ\0àÆş*º\n(Ÿÿ\0„ïÁÿ\0ô5èø1‡ÿ\0Š£ş¿ÿ\0Ğ×¡ÿ\0àÆş*º\n(Ÿÿ\0„ïÁÿ\0ô5èø1‡ÿ\0Š£ş¿ÿ\0Ğ×¡ÿ\0àÆş*º\n(Ÿÿ\0„ïÁÿ\0ô5èø1‡ÿ\0Š£ş¿ÿ\0Ğ×¡ÿ\0àÆş*º\n(Ÿğ\'ü“Ïÿ\0Ø*×ÿ\0E-tÏøşIç†ìkÿ\0¢–º\n\0çüwÿ\0$óÄßö\nºÿ\0ÑM]sş;ÿ\0’yâoû]è¦¤oêj¥›Æzà\0dşæËÿ\0‘è¡¢¸+P·Ön-à³ñß‰Ü¡’ÜÜi°@³¨ä˜ÚKEÁÏÊOô­ïøGµOúõÏûóeÿ\0ÈôĞQ\\5ë^ÙëÙ©â_]Kæáí­lA$)aäbJ¶:U>ÖóR¼Ô-¡ñˆ•ì&Xe2[Ø€X¢¸Çú>q‡qÎhÜ61Ÿàö›uá­VÊşâkP¹{“¬µ˜ÄìÀ€¹bBŒc¹Éõ«ÃáíÏöÆ©}&³‹¬iñÙjHÖgt…c(^6ó>LğpCVßü#Ú§ızçıù²ÿ\0äzˆhº‹]=¸ñˆw¢$ÚZÁ$7Ù¶“Ç@r8ÏQJÚ[úÚßîïë{şf•ğŞòÊóÂ³]kNY#…\"±1™QĞ/ÌL­ÈÆráQëÿ\0?·\'ñT¿Û>Gö÷Ù¸û.ï#É#ı±»8öÇ½kXZ^jº•¤>0ñ\nÉ§Î ”¼ 3WùÑúaÇ\\sš¿ÿ\0ö©ÿ\0C¹ÿ\0~l¿ùúÿ\0Zê%§õØåõ/…-®Ç­K«ëk.¡¨ZÃiÖöTvé+»%”–úb’ûáeÆ°ş!›U×£–}fÎ+bmìLK	Œ‚¬‘‰û£#>¼İOü#Ú§ızçıù²ÿ\0äz?áÕ?ès×?ïÍ—ÿ\0#Ğõ¦ßÖßäjçá{]êğë7Z5İøµ³¥îçÛÈ«Œ±´¹G\0`äA]¶‹§\'F´°fÚÂ··X#?îÆ¼(öÿ\0ö©ÿ\0C¹ÿ\0~l¿ùøGµOúõÏûóeÿ\0Èôî+Ïÿ\0Â=ªĞç®ß›/şG£şíSş‡=sşüÙò=!Ïÿ\0Â=ªĞç®ß›/şG£şíSş‡=sşüÙò=\0ÿ\0ç‹?ì*ŸúEkYš¦ƒ¯\'ÄXüO¥C¦ÜÄ4¯°n®Ş\ræïİ•‰ò:Õ[BĞµÖ<N«âÍf3¦ŠÌ±YæCöKs¹³ÁŒ(ã9\'_ûóíF×şcí<ÃËûsØû>q3G_ë°t9_øW¾!µ…|R+†¼šæëR…/\'±®&Û†Vˆe@1±ˆÏ\\Œñ.ƒà-s@»ğíÌ7Kc¤¾›rL¬»7HIÈwãŸ•¶ık©}QßÆºÚ¢Œ³4V ëÿ\0õV[	âÒFñŞ²RñÖ;vH¬˜JXqoÈÀ\'=03G§õºı«›şºş†‡<â+/é:Æ¯yígk4Êu‹‡ß:¬Š!Çİ^>½·õê\rñNñ%ŸÙe‚;\'²¸ŠiZ6Ug\r½0ŒõàííÍ,ZlóŞÏeµY.­Â™ E°/a•Ü¿gÈÈéµµv¶½/êóÇd]n~ÏŒ­(Ë)U·\'pşî3íGgÛşù…·]ÿ\0¥ùu¥è+\'[“>Ÿ£Ú^iÚjãˆC«K*pH_ÂµôÏê,ĞôËR{+[eğé°µkgwv2¢üî¤\0¸\0| ¶yäWq‡¨O\nMu·E¬!²ÁãŞŸÿ\0ö©ÿ\0C¹ÿ\0~l¿ù‹Yr¾Öü\Z}Ø^îë½ÿ\0+~FWƒü%q£ŞÅy¨éöë{´‰¬İ]–\\‚TG2€ŠHÎ8éXŞ\'ø{­j#Ôµ[9­$¸œÂú}ô·’Ã>˜È*QÕĞä’¹\\î9ìG]ÿ\0ö©ÿ\0C¹ÿ\0~l¿ùøGµOúõÏûóeÿ\0ÈôÛ»¿õÜŠÆF‘áRÇáÖ¯áË›‹Y¯n–íRíY‚ÎfÜD»~C–åWpàö¬‰¾j	wcr«êÿ\0cC¦İÛVæÉKGÜ<JK©‚¬¯s]wü#Ú§ızçıù²ÿ\0äz?áÕ?ès×?ïÍ—ÿ\0#Òş¿¿Pş¿\'úßğ„ÜÇ­5Í¢Ú[ÙdEn&w1¾âG%rPãÉô¯5Ö-¤ğíÔºV¥uË=ÎŞêÓíâĞj;±H·Bí6HÛòùddŒó^Çÿ\0ö©ÿ\0C¹ÿ\0~l¿ùøGµOúõÏûóeÿ\0Èô]Şÿ\0×_óaÓúòÿ\0$s·>Õ¯õê6qÛGmâ-!-Ê]ÈñÍk ‰Ôó.~`G<\Z¿¤xFÿ\0Oñ¨K5³E§hÙÒ„fÜÒeWåû¿)äàûVŸü#Ú§ızçıù²ÿ\0äz?áÕ?ès×?ïÍ—ÿ\0#ÓZ=?­ÿ\0Í‹ugımşHè(®şíSş‡=sşüÙò=ğjŸô9ëŸ÷æËÿ\0‘éè(®şíSş‡=sşüÙò=ğjŸô9ëŸ÷æËÿ\0‘è ¢¹ÿ\0øGµOúõÏûóeÿ\0ÈôÂ=ªĞç®ß›/şG ÀŸòO<3ÿ\0`«_ıµÊÅàßÁcâ\"Ò#´×µ‰ÍëO#IRáHò|°¶ƒü`dûs{Áš£7|=*x³YL¶eŠ8¬Ê 1/Ê7@NNI>¤Õ¸Ä2ê­¥Gñ*õõ$E}<Ê#g‘µÔwi÷ü*Ãe}o\r¬Zx–ê`ŸY¹³)å B\"R’•H\'é[~Ôfñªê–oo§ÚÉ&of¶»˜=äB\"‚9 Ç–H\'‰7g™­[\"îÊ=×µx!$©bª	 “oÜ>¦¥ÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_üC×q-699<âI>¿ƒ|Í$C$v÷^t›¦2¸.»?wòƒÀ/’z€9·ãOİ‹¯ø‡Ì‡ìŸğ‹Ïc³qó7åŸ8Æ6ãß>ÕĞÿ\0Â=ªĞç®ß›/şG£şíSş‡=sşüÙò=)^Iüÿ\0aÅòµıuOô8ÏxKV×¬4}GR’Æx¼<lm~Îîîşj½Á\0.Ù9äUí/Á\ZÊÂ—íazµÅ¬ÆŞáÜÊ¯¢²æ5ÁÈ9§šéáÕ?ès×?ïÍ—ÿ\0#Ñÿ\0ö©ÿ\0C¹ÿ\0~l¿ùªM6ßø?æÉŠ²^_ä—ègFğ6·o†´ÍFm<iŞæ†âŞGinH±†FP#á‰l3d1œÒèÕtÛÁ5Å›6‡qq-ÉGbH\r™^OÌ3œVí†™w©ØÃ{gã}rKy—toö{1‘ô6Ù«ğjŸô9ëŸ÷æËÿ\0‘è»ıGnŸ×õ©ĞQ\\ÿ\0ü#Ú§ızçıù²ÿ\0äz?áÕ?ès×?ïÍ—ÿ\0#Ò ¢¹ÿ\0øGµOúõÏûóeÿ\0ÈôÂ=ªĞç®ß›/şG ‚Šçÿ\0áÕ?ès×?ïÍ—ÿ\0#Ñÿ\0ö©ÿ\0C¹ÿ\0~l¿ù€:\n+Ÿÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_üGü#Ú§ızçıù²ÿ\0äz\0è(®şíSş‡=sşüÙò=ğjŸô9ëŸ÷æËÿ\0‘è\0ğ\'ü“Ïÿ\0Ø*×ÿ\0E-q¿ğ¬oà“SÕ¬ÛMÄ^}NÂè–¹b3¬v\n—ÊŒŒ‘ZŞĞµ¼áéSÅšÌúe³,QÅfU‰~Qºp:rIõ&­Æ!—Um*?‰W¯¨© Ú+éæP@É<Œğ=¨Z;­ÿ\0áŸèk=¿¯ó1ôÏ‡SØk}ªÒ\rBÃûUµ%}fê#	-¼£…1³)Ï9»âŞÕM¼‘ı¢Ï-â¡­¿Ônoİûşİ=ëq¬dHšVøƒ©¬k7Î~À\0—;vö½’:äÕ¯øGµOúõÏûóeÿ\0ÈôGK[§üú zŞÿ\0Öëõg5wà¯®£ªİXß@°ŞêËxöÑŞÍjf„DcK\Z–C¸òäæ¡ğ×ÃÍ_GÕ4+»››6[Ëû‰‚M#±\0m\0²åˆÁÉcŸs]_ü#Ú§ızçıù²ÿ\0äz?áÕ?ès×?ïÍ—ÿ\0#ÑwoëD¿@zÿ\0^¿ær:gÃ­kFñ}×ˆì®¬Z{½BëíÌîÑ½œ¤0ÀÛÄŠÃ8¡jÚğ_…u/j­#Ai¥É$\ZmµÜ·Fà±yÌË\r»î.G½jÂ=ªĞç®ß›/şG£şíSş‡=sşüÙò=EdWvgø\ZÆéî5ßßÛËos«^“sFÈéoÉ*ÀHØÿ\0jªxáä>(ñ¬º–¥¤ÚcèÍ`ªã2Ç1°‘r00	Á9­¿øGµOúõÏûóeÿ\0ÈôÂ=ªĞç®ß›/şG¡¥§—ùXiµ?ó¹Å§Ãß½Å­ö¤öš•Óé‹§ŞÄº½Õ’¾Æ;_|hK†Só+.3ÈÎkZãÁ\Z¤z£İh·6Úb¯‡F•jG‘ ”>á‚W•ÇæïŠŞÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_üGü#Ú§ızçıù²ÿ\0äz¿ãóbZ]­şHáføc®_ÛëâææÚ)5.+H·ê7„H¿-$«¸)=€ã<ßgPğ6©¯j7w\Z„–v‰wáöÒmæyJHd,‹¹qCÔ{×Cÿ\0ö©ÿ\0C¹ÿ\0~l¿ùøGµOúõÏûóeÿ\0Èô=tşµ¿ù‚Óúôÿ\0$róx\'Ä\ZÄÚ£é–óÙèsé–«k4’,²JLY¢£\nO<`êé¿ÓüC jÍlÑiÚöt¡·4™C•ù~ïÊy8>Õ§ÿ\0ö©ÿ\0C¹ÿ\0~l¿ùøGµOúõÏûóeÿ\0Èôîïë¯ù±[K]?ÉÏÿ\0Â=ªĞç®ß›/şG£şíSş‡=sşüÙò=!Ïÿ\0Â=ªĞç®ß›/şG£şíSş‡=sşüÙò=\0tW?ÿ\0ö©ÿ\0C¹ÿ\0~l¿ùøGµOúõÏûóeÿ\0ÈôĞQ\\ÿ\0ü#Ú§ızçıù²ÿ\0äz?áÕ?ès×?ïÍ—ÿ\0#ĞAEsÿ\0ğjŸô9ëŸ÷æËÿ\0‘èÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_ü@Ïÿ\0Â=ªĞç®ß›/şG£şíSş‡=sşüÙò=\0tW?ÿ\0ö©ÿ\0C¹ÿ\0~l¿ùøGµOúõÏûóeÿ\0ÈôĞQ\\ÿ\0ü#Ú§ızçıù²ÿ\0äz?áÕ?ès×?ïÍ—ÿ\0#ĞAEsÿ\0ğjŸô9ëŸ÷æËÿ\0‘èÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_ü@?äxgşÁV¿ú)k ®ÀŸòO<3ÿ\0`«_ıµĞP?ã¿ù\'&ÿ\0°U×şŠjÙ¼‘!²IQ2Y˜àŠÆñßü“ÏØ*ëÿ\0E5ïÅät-ƒÔf_şE¥%tĞâìîÎûáK>£©^ÜßØèÌ±i÷Z}Y!*\0EEmÄ£ycÉÇ&´¼Ae i£NóMŠ}Ú	Mí¬6şvË©<¹%A%ˆ\0ì:·\'šßÓ_‡Éòü+áµò7yXÕeù7uÇú/z#´×áò|¿\nøm|ŞV5Y~Mİqş‹Ş”œ¤Ûïÿ\0üÍ×°VßOO/òü|µá.ôlXkóê¶pÍ¬Aá{x^åÑd‘neRõŞAPprF:ÕZ)Ğk&Q\nÙG«ÛI}Í£\\£BmT´*ÊÒ/™·€z®pvâ»(í5ø|Ÿ/Â¾_#w•V_“w\\¢÷¢;M~\'Ëğ¯†×ÈİåcU—äİ×è½ènMßOéÜ?qçøvKôù_ËY|%-¶•á„·¹Ô\"Å³32²ù(îLkå31p@Uc\0Ås~.ŞÚßˆåMÍl–:zİ?~!q!–?rc$mêCc½jOáëÛŸhğ/„fØ»WÍ¿wã$ã›N™$ş4ı>\rLCÓ|/áO.İäT6ú³á8qÅ¯ ƒô¢ónÿ\0×õıuö	[_Ã×ôù_ËVø;oüMaöCÛı”ÛáüŞY\0¨$7P;×o\\µ¼^#´òşÏáŸEå†TÙ«Ê0äøõî@«?lñ‡ı\0´?üÍÿ\0ÈµWvÔÊ|·÷N‚ŠçşÙãúhø9›ÿ\0‘hûgŒ?è¡ÿ\0àæoşE “ ¢¹ÿ\0¶xÃş€Zşfÿ\0äZ>Ùãúhø9›ÿ\0‘h ¢¹ÿ\0¶xÃş€Zşfÿ\0äZ>Ùãúhø9›ÿ\0‘h ¢¹ÿ\0¶xÃş€Zşfÿ\0äZ>Ùãúhø9›ÿ\0‘h\0ğ÷ü‡<Yÿ\0aTÿ\0Ò+ZÄñ²AârhdhåÃ.ÈèpT‡r=&…uâ¡¬xœÅ£hÌçSC(mZU\nßd·áOÙá·iÉ’F8É·qkãîE²Ó¢Rw¬që²ádìßñëÓı“òŸJ‰¦öóüS_©¥>Vıçm¿™™ıŸ2İ›¨ê—1jz“Î’^JY¦FL2a‡—Ÿ0‚©µO†ŞÆI¼–7†æÔZ\\;1ºiöÜ£rÌH 4Ÿ/ğôÀ­_°xÏş}ì¿¿ÿ\0!ÙÖş=z±÷=©¿Ù^+k‹YåÓôÙd¶˜\\#>¹)\"M¬¬ã×…eb\n:cJNûoşoüÿ\0§®§v¼Ÿeş_‰[IÔü5Ä­^ÂÒûIT“O†/³Å,`4‚IÚUÚ,2K¼äÔÕ4wÄö:]ş›,MuAok4dVŞ%ÊªŸ¸ÛÇ­ß¶xÃş€Zşfÿ\0äZ‚ñü_ycqjt}!4m’-jPé‘Œ®mHÈíÅ>VünFîÿ\0×bo’|¤rJ‹p±’z $/ş:t5ËØiº}½¯‡ô(íí¢X¢A¬ÍòªŒÿ\0¾‚¬}³ÆôĞÿ\0ğs7ÿ\0\"Õ=É[Ïı³ÆôĞÿ\0ğs7ÿ\0\"ÑöÏĞCÿ\0ÁÌßü‹HgAEsÿ\0lñ‡ı\0´?üÍÿ\0È´}³ÆôĞÿ\0ğs7ÿ\0\"ĞAEsÿ\0lñ‡ı\0´?üÍÿ\0È´}³ÆôĞÿ\0ğs7ÿ\0\"ĞAEsÿ\0lñ‡ı\0´?üÍÿ\0È´}³ÆôĞÿ\0ğs7ÿ\0\"ĞAEsÿ\0lñ‡ı\0´?üÍÿ\0È´}³ÆôĞÿ\0ğs7ÿ\0\"ĞAEsÿ\0lñ‡ı\0´?üÍÿ\0È´}³ÆôĞÿ\0ğs7ÿ\0\"ĞAEsÿ\0lñ‡ı\0´?üÍÿ\0È´}³ÆôĞÿ\0ğs7ÿ\0\"ĞàOù\'ÿ\0°U¯şŠZå´­\'R×\'¾¶ò¬¢Óm¼I%á¹33Oº9mXö`dŒnßĞ*çƒn|Sÿ\0‡Ò\rG’ßû2ØFòjÒ£2ùK‚T[§8õ5?Ø<gÿ\0>ö_Üÿ\0ì¿êÿ\0¹ÿ\0½Ûûşô®Ô¹—õª¡¤cE©JßğÍ~¦|¦é4MoVşÒÔZéusmnä	_jAµP½3Éà‘œqPÏİÂã¬êÑÌŞ\"k!åŞ:¢ÀÒ”(îô\'A`q‚01­öÿ\0Ï½—÷?ä;/ú¿îÇ¯_öşÿ\0½`ñŸüûÙsşC²ÿ\0«şçüzõÿ\0oïûÔÅÉ5uµ¿OÎÏï-Ó¦Ó÷÷¿Gçù_ğ1.uS¥ØêºmÍõÛZÁ®}–	nµY-•À²íšïæ‘WsrNÕÎ	¹¼ñ>µ¼Ô¯…»É¨¤Ÿf¾R9@ŒF×`0Ü1Ç=H;Ÿ`ñŸüûÙsşC²ÿ\0«şçüzõÿ\0oïûÑöÿ\0Ï½—÷?ä;/ú¿îÇ¯_öşÿ\0½5)víù§K¤ÿ\0çş[JºÔ$ñbøZKËÆşÊ¹šúi™Û3[¿0#1ûÃ20Æå‡=k¾=\ryèğï‹Ræöê(m\"¹»E…æ]uË,JT´=2Nã“’yääƒBñÜSÇ#Ş¬Ê±ˆLRkxWO}¶ îÿ\0hi^VµƒÙÒ½ùÿ\0bøoRÖ-|5rÌºši2>‡m†tUÉ`¸ùæŒ©nŞ¬Mß½j×TµÓ|Gi¶m8¾ —PŒü²ê­çG\0`s[_`ñŸüûÙsşC²ÿ\0«şçüzõÿ\0oïûÑıŸã?ù÷²şçü‡eÿ\0WıÏøõëşßß÷¢òíıkızê7\n_Ïø?\"k\'šó\\v¸ŠÜ$vilÄ„YPffÛë½Šgş™úW_\\‰¢ø¯A‚(­¬´ù6r|írB1,Û€´\0’Ì[v3Ÿn+wí0ÿ\0 ‡ÿ\0ƒ™¿ù®÷èc(Æ.Ñw:\n+ŸûgŒ?è¡ÿ\0àæoşE£í0ÿ\0 ‡ÿ\0ƒ™¿ù‚N‚ŠçşÙãúhø9›ÿ\0‘hûgŒ?è¡ÿ\0àæoşE ‚ŠçşÙãúhø9›ÿ\0‘hûgŒ?è¡ÿ\0àæoşE ‚ŠçşÙãúhø9›ÿ\0‘hûgŒ?è¡ÿ\0àæoşE ÀŸòO<3ÿ\0`«_ıµËiZN¥®O}måYE¦Ûx’KÃrffŸtrÚ±ìÀÉİ¿¡<Uß]x©|áå·Ñ´i \Ze°äÕ¥Feò—¨¶ vÉÇ©­Ï¶xÃş€Zşfÿ\0äZ’æş·Oôª·õ³_©ÅhŒtÿ\0]êZ»‡ÒWº·´n‰itÏÎ;—b¿ğôş<ŸU®í0ÿ\0 ‡ÿ\0ƒ™¿ù¶xÃş€Zşfÿ\0äZ‘QìY7Üè(®í0ÿ\0 ‡ÿ\0ƒ™¿ù¶xÃş€Zşfÿ\0äZ\0è(®í0ÿ\0 ‡ÿ\0ƒ™¿ù¶xÃş€Zşfÿ\0äZ\0è(®í0ÿ\0 ‡ÿ\0ƒ™¿ù¶xÃş€Zşfÿ\0äZ\0è(®í0ÿ\0 ‡ÿ\0ƒ™¿ù¶xÃş€Zşfÿ\0äZ\0è(®í0ÿ\0 ‡ÿ\0ƒ™¿ù¶xÃş€Zşfÿ\0äZ\0è(®í0ÿ\0 ‡ÿ\0ƒ™¿ù¶xÃş€Zşfÿ\0äZ\0è(®í0ÿ\0 ‡ÿ\0ƒ™¿ù¶xÃş€Zşfÿ\0äZ\0è(®í0ÿ\0 ‡ÿ\0ƒ™¿ù¶xÃş€Zşfÿ\0äZ\0è(®í0ÿ\0 ‡ÿ\0ƒ™¿ù¶xÃş€Zşfÿ\0äZ\0è(®í0ÿ\0 ‡ÿ\0ƒ™¿ù¶xÃş€Zşfÿ\0äZ\0è(®í0ÿ\0 ‡ÿ\0ƒ™¿ù¶xÃş€Zşfÿ\0äZ\0è(®í0ÿ\0 ‡ÿ\0ƒ™¿ù¶xÃş€Zşfÿ\0äZ\0è(®í0ÿ\0 ‡ÿ\0ƒ™¿ù¶xÃş€Zşfÿ\0äZ\0è(®í0ÿ\0 ‡ÿ\0ƒ™¿ù¶xÃş€Zşfÿ\0äZ\0è(®í0ÿ\0 ‡ÿ\0ƒ™¿ù¶xÃş€Zşfÿ\0äZ\0<	ÿ\0$óÃ?ö\nµÿ\0ÑK]sşÿ\0’yáŸûZÿ\0è¥®‚€9ÿ\0ÿ\0É<ñ7ı‚®¿ôSVìÊZ	’¤Ê°¼wÿ\0$óÄßö\nºÿ\0ÑM[³D³Âñ1p®0J1R>„r\r)+¦‡Ï7ŒoøGá‹e ÎgÓáòÁù·¤é½qê»[#¶Ó•<Ş$Ö´«bkÉ/%™m/nlmÂ@ös$L6”hÿ\0z»ƒI8è+¡Oéé©É©™$şĞvÜİ\"¢K0Ú7¨@Àû¤g9¤´ğV›ew=İ»<77d›É D‰îr2P9çåÆ{æ“”ôŞÿ\0ŠFê%okt×ô¶ÖÜ´šïŠ-t‹­×7q–Ãì×7âÍåÄÓ’WË#Iç–ç=\ZÎ	-­#†[¹®äAƒ<Á¿¹ª¿ÏÛxI´µkXWË·–A,ñC\ZD’ºQ°Š²Wàg4ë\n^\\=Á>,×#w¬M…ÛÓgîÎß|u¦äûÙRşÁÿ\0_Òó¶Ú­úøƒSÓí®È]ë±Z5Ü0GæFŸdY:•!˜•Ú†àã°Æ·Ã¡·Ã3©¸k‚5+Ğfm¹“ı!şc´“ì\0«3x.ÂòÚ{{ù¥¼íƒŞ¬ª˜ºeÀBÀ(\0Œ/İÆp3šÔÑô‹}ÌÛ[ck9vÂ*ŸEP~\0däI¢-ì×õ§ù\n¤aovWùzÿ\0ŸåİÛBŠ(¦bQE\0QE\0QE\0QE\0sşÿ\0ç‹?ì*ŸúEkYú¾­w§x§U’)Y£µĞ\Zé v&?1]ù+¼¸­ÈsÅŸöOı\"µ¤Ô´k‹½Bîaki(ØÛ<’I\"—„ç0àI\'ÌŒô¨÷^“F”â¤ìßoÍ­x…ÅÄºlRİé/k,H~ÎT€ÈÙ8uÃœƒòö«¶:–¥§|0\ZµõÌwwpéfèH‘2nÄ[†íÎÅ›ÔçŸAV?²õ´E9´ÓÌ‘[´(K¹&ÚLèsµx}>íI¤h_cÑ&Ñg…E„‘DJcF1æBN\0èF ä”®¤’ßşèÑ^Ç•©JIÛÏÓõLçµ{+3Á\Z–úì-o-Š2C.Å•hÃ³76C6A;NâH\'jxnmüSá«ˆõÅ{ó[,»bÛäJÇ* nÉU?1lmã9™|!m¨øLÓõ×y,¢Hóm{<ì˜Úä#.OÊÎpz\Z÷ÁÚF£%¤—GRw´\0@Ëªİ)B¸e°H,~b&µm_N÷ùFü¶}¬6×6¾>Ô`Lï,b¹`ü´VhÉüW`ÿ\0€×CXöZTñx›QÕg‘\Z9`†ÚÙ’V4ÜÌXåœúğ¢¶*z/ëúĞ}®ÁEPEPEPEPEPEPEP?àOù\'ÿ\0°U¯şŠZç¼8ó\\xƒUºº´×çjw*—cS?eD\\á<Ÿ<g1å‘’>µĞøşIç†ìkÿ\0¢–µ¬´ë]=g[X¼±<Ï<Ÿ1;Xò{út¥­î»t·Ÿùœ¦™âmjfĞ/ïtíuŠÃoN³[n¥BÎ\\‰>U áñœsOşv/éz…ÇØ–m^eŠİ-ôùî>Î»32£”œ*…ÚO%€ÍtöÑtËÔ»µµu’=ŞJ=Ä¾÷•1HóÓä:T’økI›G·ÒšÙ…¥³+@gG‰‡FI§’2pHèi¿!œô> Ô.ôı&këi\"˜êæÙ˜Ãqf&@Ë È`ÇÊû— õÀ5—¤ø÷VÕ`¸¾¶¾Ñní¢ÒeÔ%‚ÚİÙídÇÉæ‘»®~PNÃÀë]£øvÏû:;Diİ Ío%ÕÌ·\r…J†&F%€Ü~Rq\\æá\rRØÇm©:¥€´k[˜“V¹º[ ÊˆİU`á=p0-l×õµ¿=.¡¦Ÿ×_òÓó4´mgY“]‚ÇU-å‡Û`6¨êa!”4lYÿ\0¾¸`¡ù}1¼-®ÎŞ!Õ´{t8í¯nî®tmÒ©‘€XG°GÌÜ0K|½²i–qŞAv°â{x\r¼M¸ü±’¤Œg•^O<Uaáİ(Oëk¶hndºE‘ƒ,ç9èÙå~éã7ñ]vŸr%^Ö~_–§+á¿ëZíÍ£-®m¯íä–<é7P­¡Æè÷Ìÿ\0$À	]¼ã?ø¢ó\\»ÓàA-“M©¯$Ã0,F¼ÿ\0}%ëµ4ïéšEÏŸb·p»löcdä…„¿–£ĞíŠ¯á¿÷\\½‘ YõKö¹o$’\0Èà>ìiéëúëø\rÿ\0_×Ëñ2Å:Ï“ı²«`4q©ı€Ú´Oö‚¾w‘æy›öƒ¿æÛ°ü¿Åš_i×®ø—Q½{	îı 3Åfc—\n‰….]ÌcØ‚sÎßü\"z/öŸö‡Ù_Íó¾Ñåı¢O#Íÿ\0Nï/}ÛsskFÖÆÚÉ®\ZŞ=†æc<¿1;œ€	ç§\0p)GM|¿ËşK]»ÿ\0Ÿü³×5kM´1í`¹’îúFñ&¸AR*æ	.¼\n\08^‚—Âú«h¿gÕn 5§Ûgx£bCšBTtÏ|~¿sá=\Zëi{iQÒy.X.eŠ@òrøt`Ønëœ8nÏDÓ4ı é6ÖQ&C©¶#rä–\\ğw:s”•ùëaéÍ~—9{ÍWÄ¶Ï&™{u¥ı¢óMšêŞ{ki@ÑíŞ¬¦\\¸!Æät­/\0éË¦x\'JˆGf†Kxæo²[y*Å”°ÜÙn™lòyÀéW,<+£é¦S;KÙËÜ]K;,_ÜS#1Eö\\ ­K[hl­!µ·MA\ZÇ\Zäª£\0dóĞU++ü¿_øë§õÛş	-QHaEP?àOù\'ÿ\0°U¯şŠZè+Ÿğ\'ü“Ïÿ\0Ø*×ÿ\0E-t\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QEÏøşIç†ìkÿ\0¢–º\nçü	ÿ\0$óÃ?ö\nµÿ\0ÑK]\0sş;ÿ\0’yâoû]è¦®‚¹ÿ\0ÿ\0É<ñ7ı‚®¿ôSWA@Q@Q@Q@Q@Q@Q@Q@Q@ÿ\0‡¿ä9âÏû\n§ş‘Z×A\\ÿ\0‡¿ä9âÏû\n§ş‘Z×A@Q@Q@Q@Q@Q@Q@Q@Q@Q@Q@ÿ\0?äxgşÁV¿ú)iÃÅ–Ïí>MÎÏí3¦cjçÍó<¼õû¹ï×©¾ÿ\0’yáŸûZÿ\0è¥¬×ğMáº(šÒ&˜5Qª­¿Øó\'™æd2oÁBw…dr@ÁÄ¯·üú\\Âí¿üúØÓ¶ñe­Âê&Òê-6ÃÎêü¢b8p9“ †êƒ8÷d/¢¼kÍ/QÓäµ³7ŞMÊÇºXFrWc°ÈÆ$‘‘ÍQ¹ğ0Õ5=JëS¹´	}i-£‹3o$ˆø\0ÊÅÜHÊ$\02xíMÓ<,4İFĞ®ƒ^Yµ¯Ÿ§hÂÖO˜c.DŒ×\0/4µåó·ã¯üëQéŸá§üëBçü&JæÎ8´-VK‹Õymíÿ\0p’I\n…Ì¸y@ç_”şª1Rk~ ¾ÒüA£X[ipß3‰dŒÄ6á	Àİ\"ò1“Áé“ÅEâO®höÚP¹°[x£ÚÏq`f•X\r¢H›Ì_-ÀÎÆö©¢Ü]Üi76wËÆ)`ÓÂf+!FSœ†Ï^Æ©Úú_×õØl1¼MzÌ\Ztún¡\\Êğ[İÊˆ\"–ERÅ@İ¼p­‚T	ÈÎG…¼e%í§i|“_#,WÒE\ZÃ<ª	*\0mÀà62€§ñ˜ øyåx¢ßY{Û9ÙnÄ¿ÙãíR‡<¹\'ßó*‡@Q€ U­\'Á×¶/¤Ãy­-İ†”Í%´+iåÈd*T}äß\0(<Œ“g¡OwoëúĞ¯¥øÖDğıŒ—Wú¥ïØ¾×xÖqÇû˜ò@fËœíl*NÓÇ¯eo<WVÑ\\@áá•£Œ¤dÊ¸I>A›9´‹©á´[I«¤¤`¬Ì¬ƒz”?3óF8â»«hE½¬0…Dh,I±>Uì=jkmD÷Óúşµ%¢Š(\0¢Š(\0¢Š(\0¢Š(\0¢Š(Ÿğ\'ü“Ïÿ\0Ø*×ÿ\0E-tÏøşIç†ìkÿ\0¢–º\n\0(¢Š\0(¢Š\0(¢Š\0(¢Š\0(¢Š\0(¢Š\0(¢Š\0(¢Š\0(¢Š\0(¢Š\0(¢Š\0(¢Š\0(¢Š\0(¢Š\0(¢Š\0(¢Š\0çü	ÿ\0$óÃ?ö\nµÿ\0ÑK]sşÿ\0’yáŸûZÿ\0è¥®‚€9ÿ\0ÿ\0É<ñ7ı‚®¿ôSS&ğm“Í}­Gj]İµûĞ’Ió©ş;ÿ\0’yâoû]è¦­›ÉVS¹BÆI\n¥OAÉ©“j-¡Å]¤yÇ†îü?¯Y­ä·—¶ö²G$É$~/¹Å\Zm\'ÎS*”`Ip^ì23±-¯‚ííEÔŞ\'¹ÜÊa¿Šn‚yƒªdÏÃ¸ë\\…Õœòx[K²1%Ä\Zº°dÕ·NëUB$û­’¹H­¯]¯“§¶†·†úÚÉí­å±ò¸&ct~ò<ªî1Êô¦ç÷ş®ÿ\0Ëñûöúµoäsòÿ\02Ôx&]OQÓßÄ\Z½Å„¢)\\¦âBœ¨óòFX/8ù²*é²ğjékª7‰n†Ïå­ÙñE×”[Ğ?ŸŒğxÍsZ«I,úÅšZ›Èïõ++Ô¸€¡…Ö? Hªor<¶$*°©ÏjïRÔ,ou¹´¹\'òouXæß`\"yå„[¢–€I˜Ø‡@9ÀÏÅ.xÛëOóp}Z·ò?¹›“Øø:ÖÒ»]Ãm2y‘M\'Š.•$L¹XÏ‚2Ê2=G­Om¢xfòòk;]oQê\r,1x’í0y”MGZã¼5Úßi·èÏä&¦w9GfedÛåü®ÅwnòÁÈ8¨áÓè\Z>Gm$^º°‘âù€šAìÏ™Ê±b›±ƒš9ãmÿ\0­É}à°µ›·+ûŸ‘ÚéÚ\'†uˆ^m3ZÔobFØïmâK¹·¡+1Áö«Ÿğ†éóõ®àú÷ÿ\0V7?y¨^İO.¤ne·†,_IjC,{¿Õ}B2‚øÎIç.{š­:Ê2‹´•™Ïÿ\0Â¥ÿ\0ÏÖ¹ÿ\0ƒëßş=Gü!º_üıkŸø>½ÿ\0ãÕĞQ@şİ/ş~µÏü^ÿ\0ñê?á\rÒÿ\0çë\\ÿ\0Áõïÿ\0®‚Š\0çÿ\0á\rÒÿ\0çë\\ÿ\0Áõïÿ\0£şİ/ş~µÏü^ÿ\0ñêè( şİ/ş~µÏü^ÿ\0ñê?á\rÒÿ\0çë\\ÿ\0Áõïÿ\0®‚Š\0àô/	éÒë\'F¹Ö@‹SD]ºÕâ’>Énß1åXòrqĞ\04¢ğöƒ>£s§Ç®«dG–3®_ªùÚreÁktô5kÃßòñgı…Sÿ\0H­k3Æ)©éº®·¢YÉuu,2iÒÇ“÷ù‰Ûî£O`äÒli\\³aáíT¶76wúä°‰=ÿ\0ÛwàF*ØÌ¼Œ‚3Ğö«_ğ†éóõ®àú÷ÿ\0W5â%,ôÍEM.+Û;{gÏ¸ÓeÔH¡@ØaÛ’$n?ˆÓ|1¡Ï{q£ÜkVIsm¡[{Ø‰Ü«7$7bñÏŞ>§/OëÑÿ\0—â.—ş·_çøZ®¢é\Z{^Ü\\x£WD\"=vôœ»…fØUßøCt¿ùú×?ğ}{ÿ\0Ç«‡‡J¶ŸÁíhš£¾ÑÀš¥À´’&š_>3!2Œy¤ÌKm9\\ó¥¨i#DoÛiZ:mÂY°¶ŠİÌË2Êş\\x2aUÆ¼¸=hzé¿á\rÒÿ\0çë\\ÿ\0Áõïÿ\0¬étÁui-îºŸdµW¶»z#‰	`2|î¿# úW+£è—Vßb¸Ó_û0ø†9ôÙláòM¿ÌD,IHËd0\'“‘ó]NŒ`Ñ¼Câí–Œ#·[g†ŞÖŸ%`Â¢\"õù•À—ş¶Oõü­¿­ÿ\0à<ß‹[™ßRñ4BØÆ%Š[ıV9G˜ÛS3`ÍÀ š–hü+oeÔ×>-\'ŸìñF÷Z°•ßil÷o#\0œãj”SiÚâ_êZş“®´×~Jµ´:mäFÒÜ´x`ŠÌáÛslÉğ\n©b°[[µ´ÇX´ñ,úBên“/úc]@< ¶Ãö€¥Œ€o:àÉı~Bş¿¯ëcV=/A’}>1/‰_ù‚›W¿„†A’¬(pHGËÑO¶tÿ\0á\rÒÿ\0çë\\ÿ\0Áõïÿ\0®vİoãÓ<3 ×&áµÆ6klÏöp&+¼wy]sÏ<óšô*}?¯\'úØ:Ûúİ¯Ğçÿ\0á\rÒÿ\0çë\\ÿ\0Áõïÿ\0£şİ/ş~µÏü^ÿ\0ñêè(¤?ÿ\0n—ÿ\0?Zçş¯øõğ†éóõ®àú÷ÿ\0WAE\0sÿ\0ğ†éóõ®àú÷ÿ\0Qÿ\0n—ÿ\0?Zçş¯øõtP?ÿ\0n—ÿ\0?Zçş¯øõğ†éóõ®àú÷ÿ\0WAE\0sÿ\0ğ†éóõ®àú÷ÿ\0Qÿ\0n—ÿ\0?Zçş¯øõtPàÏ	é×>ğôïs¬‡“L¶vëWˆ ˜”ğ«(\n=€\0v«©£ømì¯¯R×\r½‹È—ıµòşøÇ›“lûUÿ\0É<ğÏı‚­ôR×93İiÚoŠt\'Ó5ou‹—²hm$xf/Ë™TLAŞF1„T¶õ·oÇA«i~ÿ\0†§A„´‰áIc»×C)şİ¾äŸòÖŸÿ\0n—ÿ\0?Zçş¯øõsºÖ›öèóÇ`5;øb·€ÛÜérM`?2Es·d2ÌrNv¨À8¬İ7F¼ÿ\0„ËÏÔ?Ñõ$Õd˜\\¦ƒpòÍ	fØ†ñ\\Çå˜È]¤\0¸Á«²r²ó\'^[³¦Ö4-EÓd¾¸ŸÄ2\"2 rô»³0UUa’IUïøCt¿ùú×?ğ}{ÿ\0ÇªŸ†<=gÎ§¨İéqı½õ+†{ˆs ÌÊì-È_â\0qzœÕ³X~%­İ¶Ÿöég—Íw¥HÍDDn†ì®ÍœåŒœ»õ–¶óÒşD’iº2kW:j§Šeû,{‹ˆµ«ÆHÃ*»DûÙÓ€Šİ«V?iRF²-Î¼\0@mrùO> Ë‘ô5‡{áÛuÕ<i{o£D.ntà°O¨ß+´ro\nÀe‰;s§¨í|;kpúıŞ§iu†Ú(­î£h”Û*¹€`İAÚ2pÎ\0¥}ü¿àÚ¯ë±µyáÍ\nÁak›ír14Éñ<¾9v8QÄ½ÍXÿ\0„7Kÿ\0Ÿ­sÿ\0×¿üz¸c¢ÚÜø^/´øZÊKK-^V¶ĞÚ¸‡äó_ì¬¥Áş€wÎ1Wµ¯E}Š¯¢Ò¥k¨Ö	t—0xÙ!B#VÜ  ”Ğ_×à¿ÌZßúîÎ¯şİ/ş~µÏü^ÿ\0ñê?á\rÒÿ\0çë\\ÿ\0Áõïÿ\0®+Å_]x°Mo¤º‡P´ònWKšy Ñïtº\r²$Á`c\0çHù‰«øR´7siR=ëø‰³1Fó>ÌÒÀ7QRÙå;‰=M%­¿®ßçøÑ_úÙ¿Óñ:ßøCt¿ùú×?ğ}{ÿ\0Ç¨ÿ\0„7Kÿ\0Ÿ­sÿ\0×¿üz¸}CD¾¶²¿±³ÓÄz$:øÚ6œ÷0›sn§+nŒ¦HÄÇ%S œ¤Wgàk5±Ğ\Z$œ¼-q#Åöt¶+\n“‰¤²¨9#œsÇ…ª¿õÓüÁéızÿ\0‘/ü!º_üıkŸø>½ÿ\0ãÔÂ¥ÿ\0ÏÖ¹ÿ\0ƒëßş=]Ïÿ\0Â¥ÿ\0ÏÖ¹ÿ\0ƒëßş=Gü!º_üıkŸø>½ÿ\0ãÕĞQ@ÿ\0ü!º_üıkŸø>½ÿ\0ãÔÂ¥ÿ\0ÏÖ¹ÿ\0ƒëßş=]Áø3ÂzuÏ|=;Üë!äÓ-„zÕâ(&%<*Ê`\0ªX-|%puU‡XÖ™´’Eêÿ\0mßæ,IÇ›ó#<‚:Š×ğ\'ü“Ïÿ\0Ø*×ÿ\0E-q\Z®‡ªÛhºş¯§é÷2_}²ú	-U=Õ¬¼\r«üEX‡_\\0jdÚ¿£ı\nI;zÿ\0™Ñ/	VÇLş×Ö…åôh¶ˆëwãÌLg ù¸Î8\'<85rËÃÚ¢³›[ırAÏŸñ<¿]Ns/8õéXSønóTÔmÕbšÚxtsktÈÀCtYA>£ºú;ÖÏÃÃ&‰}q©iÓé÷7\Z•ÄÍo2W-Û=G¡î*í«^¿‘Ñ?OÊì»ÿ\0n—ÿ\0?Zçş¯øõğ†éóõ®àú÷ÿ\0WAE!œÿ\0ü!º_üıkŸø>½ÿ\0ãÔÂ¥ÿ\0ÏÖ¹ÿ\0ƒëßş=]Ïÿ\0Â¥ÿ\0ÏÖ¹ÿ\0ƒëßş=Gü!º_üıkŸø>½ÿ\0ãÕĞQ@ÿ\0ü!º_üıkŸø>½ÿ\0ãÔÂ¥ÿ\0ÏÖ¹ÿ\0ƒëßş=]Ïÿ\0Â¥ÿ\0ÏÖ¹ÿ\0ƒëßş=Gü!º_üıkŸø>½ÿ\0ãÕĞQ@ÿ\0ü!º_üıkŸø>½ÿ\0ãÔÂ¥ÿ\0ÏÖ¹ÿ\0ƒëßş=]Ïÿ\0Â¥ÿ\0ÏÖ¹ÿ\0ƒëßş=Gü!º_üıkŸø>½ÿ\0ãÕĞQ@ÿ\0ü!º_üıkŸø>½ÿ\0ãÔÂ¥ÿ\0ÏÖ¹ÿ\0ƒëßş=]Ïÿ\0Â¥ÿ\0ÏÖ¹ÿ\0ƒëßş=Gü!º_üıkŸø>½ÿ\0ãÕĞQ@ÿ\0ü!º_üıkŸø>½ÿ\0ãÔÂ¥ÿ\0ÏÖ¹ÿ\0ƒëßş=]Ïÿ\0Â¥ÿ\0ÏÖ¹ÿ\0ƒëßş=Gü!º_üıkŸø>½ÿ\0ãÕĞQ@ÿ\0ü!º_üıkŸø>½ÿ\0ãÔÂ¥ÿ\0ÏÖ¹ÿ\0ƒëßş=]Ïÿ\0Â¥ÿ\0ÏÖ¹ÿ\0ƒëßş=Gü!º_üıkŸø>½ÿ\0ãÕĞQ@ÿ\0ü!º_üıkŸø>½ÿ\0ãÔÂ¥ÿ\0ÏÖ¹ÿ\0ƒëßş=]Ïÿ\0Â¥ÿ\0ÏÖ¹ÿ\0ƒëßş=Gü!º_üıkŸø>½ÿ\0ãÕĞQ@ÿ\0?äxgşÁV¿ú)k ®ÀŸòO<3ÿ\0`«_ıµĞP?ã¿ù\'&ÿ\0°U×şŠjFµñr©f×t0\0Éÿ\0‰4ßü•Kã¿ù\'&ÿ\0°U×şŠjÛ¸8¶—?Ü?Ê¦NÑli]ØætïøHõ=:ÖşÇ^Ğdµš1$.49—*Ã9Á¹f¬}ƒÅˆşÚĞ&p±eõÿ\0—ªóİ}tÿ\0i1[ÎÖš…¶ƒÄ3K­Mf°vˆ¡PVáS•`*	Áã§“P3øãì³_µãİ7–¶öš´±Kh¦ß\'ÌµR™Éós]qĞUMY»t¸k×úş®t–ĞxšêŞ‹_xvXwE$Z<Œ¤êEÖ0}ªQ§ø°mÆµ ½1¢ËÇşMWøVŞÎæ\réVúÎ t›†¿··Õç+„ò@Vó\\ŸmÇ<rjoÅ³7„4û‡»xu”Ñ#»3k2Ùù®AÇ—\n—\n’ÊÀT\r¥ı|ÿ\0Èk™ÿ\0^Ÿæz@ÓüX6ãZĞFŞ˜Ñeãÿ\0&¨\Z‹ÜkZÛÓ\Z,¼äÕqšŒ÷òÍ­ê+«êPÏo«ØÛÀ±]0$•`vïo¼#$š•ö¡¦Gªiñ^LtËmn8e–óTš#\rl®]|ò\"™JóşÖÜ€i[úû¿ÌJï_ë©Ù?Åƒn5­mé^?òjö?ĞwCÿ\0Á4ßü•Nğl—RxbØİİEtû¤Ù4S¼ÊÑïm˜‘ÕZL.ò>lg\'9­êmYŠ÷9ÿ\0±øÃşƒºş	¦ÿ\0äª>Çãúèø&›ÿ\0’« ¢ÿ\0Øüaÿ\0Aİÿ\0ÓòUcñ‡ıt?üMÿ\0ÉUĞQ@ÿ\0Øüaÿ\0Aİÿ\0ÓòUcñ‡ıt?üMÿ\0ÉUĞQ@ÿ\0Øüaÿ\0Aİÿ\0ÓòUcñ‡ıt?üMÿ\0ÉUĞQ@…kâ£¬xœE¬èÊãSA)m&Vßd·åGÚFÑ·hÁ\'NyÀ·qáİ\\<Òë\Z.öçåÒg\0?@à¬İkGÃßòñgı…Sÿ\0H­k+ÄZŒ~ñ3kr6š›A*€NëˆC<@´Ê]}ö¨©’Orá9ÁŞÌ?á×è/£zÿ\0È.¿ÿ\0=?ãïıgû}}èÿ\0„C]ÿ\0 ¾ëÿ\0 ¹şÿ\0üôÿ\0¿õŸíõ÷ªw?mğş‰¥hö·Z‚jr[ÍytºtVæG|†’F{ƒ° yF7Ë‚\09~­ë^!ŸJ™õ¶…´k}Bh-¡3JÌÀ®æ\r„ r=0Ãœ]?­Éš}fµ¯Îşÿ\0Oó$ŸÃ\Z½¬F[wBŠ0F^M6ua8dİÿ\0¬$¿ïsÖ¥ÿ\0„C]ÿ\0 ¾ëÿ\0 ¹şÿ\0üôÿ\0¿õŸíõ÷¬kË­GSø«>¼&›SŞá,ä†-1#òl\nåSvÖÌO©ë©>¯©h²kº}Ş³ssä‹F´¸û4&ãtìSËPFX²ü¥†l¶@ÅÎ+Fƒë5¿ıì—ş\rwş‚ú7¯ü‚çûÿ\0óÓş>ÿ\0Ö·×Ş¤µĞµË-C0k~[ÓÖÿ\0‰TÅŞ0Äå‡Úşo™‰Ü{±çšÅ²ñˆ.ã}8ß]ZÜG®¥ê+g¸4a\"Ì[\'8È<ƒµ¤Ä“x‡Åi¨ÜÉ\"Á\rµ›O#ˆØÄ!ŞX²Û“#’WÇÅ\n1Z¯ëDÿ\0TLëTŸ»97ÿ\0oĞÑûŒ?è;¡ÿ\0àšoşJ£ì~0ÿ\0 î‡ÿ\0‚i¿ù*¹á-7[·ÔÎ––:6~Ö±YÂöƒÉ»xd29PÁÆr*ç¦)°ø~\rq%ğù‡Ãvrèú©G–\r¥éò9·óÌ¢QŸ˜à¨ªş¿/ó2:K3_—W²š}wÃÆş”Û+iS\nv‡*¿kçøFqÆqÆy»ö?ĞwCÿ\0Á4ßü•\\Şv$Ò|746–voi­Ëc²Â?.	y±»\"öSØçu=kÑ(éuıuıCggıj×èsÿ\0cñ‡ıt?üMÿ\0ÉT}ÆôĞÿ\0ğM7ÿ\0%WAE\0sÿ\0cñ‡ıt?üMÿ\0ÉT}ÆôĞÿ\0ğM7ÿ\0%WAE\0sÿ\0cñ‡ıt?üMÿ\0ÉT}ÆôĞÿ\0ğM7ÿ\0%WAE\0sÿ\0cñ‡ıt?üMÿ\0ÉT}ÆôĞÿ\0ğM7ÿ\0%WAE\0sÿ\0cñ‡ıt?üMÿ\0ÉT}ÆôĞÿ\0ğM7ÿ\0%WAE\0p~µñSxÃÍo¬èÑÀtËc\ZI¤Êì«å.ar8ïŸA[Ÿcñ‡ıt?üMÿ\0ÉTxşIç†ìkÿ\0¢–¹Ø®!ƒÁ¾;Y^«ï1Iåw®åãÜ0Ç®j[µü•ÿ\0/ó\ZWkÍÛóÿ\0#¢ûŒ?è;¡ÿ\0àšoşJ£ì~0ÿ\0 î‡ÿ\0‚i¿ù*²oîõm*ëHæ{û}Cm$…±30R³¬Š_iÊ(òù6qÁªZV·âW]76ğj\rg©-¬ğŸ±‹XáFhÉÎï?Ì\rÏ5vÖÄßİæş»›×	â‹;w¸¹ñ‡ …>ô’é2ª¯nIºÅKö?ĞwCÿ\0Á4ßü•YÚm×‰¿´[WÕ\'–Ê-JXRÅ#\")¹Pçifåp~`à‚rL³_j–^6Š=Fâö=6êQ‚Û$\rnçË$¤¹S0|«¶TìÀ^sRÖŞcz_È¦|7¬Üİ\\õıÏ¾a›8kvnNĞ.ÿ\0vXœc9§ÿ\0Â!®¹«èÑcı_—¥Î¾O¯—‹¿“=ñŒ÷ªwÚ„Özş¹­ÅäwWz…¼Q%”Q<òâØ1f>Zğ¤’ÙH\"œ>!ñ\rß…-uinoâ±´–î=Bk$µ7@E!UvW\rU·ù|“ *\"õ±¿Ö++%\'÷ÿ\0]øD5Ñ÷5}\Z,«òô¹×Éõòñwòg¾1ôÂ!®¹«èÑcı_—¥Î¾O¯—‹¿“=ñŒ÷ª$İÙë¾2Õ-µ›¶h´Ø§Š\'¹1ÊS#Ë\ròã{œæ¬.³ªh·I©ë~u´ú,šƒI-ªmµ‘\nçb VdıàùY™¾_½Í>HŞÖşµÿ\0&Y¤ùßßéşdßğˆk£îjú4Xÿ\0Wåés¯“ëåâïäÏ|c=èÿ\0„C]sWÑ¢Çú¿/K|Ÿ_/&{ãïX‹âÛ.·fg¾{x¬¦õH­ZT2ÌQ[s´¡\0`7^z>±}â=\"/È$¸™4[x¯!ÚÁºmÊKG!ÏãhVÌp(öqìYè¦şóSş\rt}Í_F‹êü½.uò}|¼]ü™ïŒg½OiáÏXÌe¶Õô$8Ú‹ı6ØÁê}«“ÉÇZÉ½×<Q{â=R-ßPq§OI?cû<€¢HÆS#	Fàä˜\0òr+Ñ(P¯b^\"¬•œ›^§?ö?ĞwCÿ\0Á4ßü•GØüaÿ\0Aİÿ\0ÓòUtUÿ\0Øüaÿ\0Aİÿ\0ÓòUcñ‡ıt?üMÿ\0ÉUĞQ@ÿ\0Øüaÿ\0Aİÿ\0ÓòUcñ‡ıt?üMÿ\0ÉUĞQ@ƒ-|TŞğó[ë:4p2ØÆ’i2»*ùK€X\\€N;àgĞVçØüaÿ\0Aİÿ\0ÓòUÿ\0’yáŸûZÿ\0è¥¯;»’MÇÅšÁv:~¡w}ezHŠL¾À“åŸªzT¹Zş—ü¿Ìi^Ş¶=ì~0ÿ\0 î‡ÿ\0‚i¿ù*±øÃşƒºş	¦ÿ\0äªâ®­o_Ä\Z^¥¦‡}CLğüÂ¤şıw$Xÿ\0iz´ö®—áÕı¶©¤jwö’y–÷\Z­Ì±¶1•,«¶­v¿àíø“}ôüUÍ±øÃşƒºş	¦ÿ\0äª>Çãúèø&›ÿ\0’« ¢Îì~0ÿ\0 î‡ÿ\0‚i¿ù*±øÃşƒºş	¦ÿ\0äªè( ì~0ÿ\0 î‡ÿ\0‚i¿ù*±øÃşƒºş	¦ÿ\0äªè( ì~0ÿ\0 î‡ÿ\0‚i¿ù*±øÃşƒºş	¦ÿ\0äªè( ì~0ÿ\0 î‡ÿ\0‚i¿ù*±øÃşƒºş	¦ÿ\0äªè( ì~0ÿ\0 î‡ÿ\0‚i¿ù*±øÃşƒºş	¦ÿ\0äªè( ì~0ÿ\0 î‡ÿ\0‚i¿ù*±øÃşƒºş	¦ÿ\0äªè( ì~0ÿ\0 î‡ÿ\0‚i¿ù*±øÃşƒºş	¦ÿ\0äªè( ì~0ÿ\0 î‡ÿ\0‚i¿ù*±øÃşƒºş	¦ÿ\0äªè( ì~0ÿ\0 î‡ÿ\0‚i¿ù*±øÃşƒºş	¦ÿ\0äªè( ì~0ÿ\0 î‡ÿ\0‚i¿ù*±øÃşƒºş	¦ÿ\0äªè( ì~0ÿ\0 î‡ÿ\0‚i¿ù*±øÃşƒºş	¦ÿ\0äªè( ì~0ÿ\0 î‡ÿ\0‚i¿ù*±øÃşƒºş	¦ÿ\0äªè( ì~0ÿ\0 î‡ÿ\0‚i¿ù*±øÃşƒºş	¦ÿ\0äªè( ì~0ÿ\0 î‡ÿ\0‚i¿ù*±øÃşƒºş	¦ÿ\0äªè( ÀŸòO<3ÿ\0`«_ıµĞW?àOù\'ÿ\0°U¯şŠZè(Ÿñßü“ÏØ*ëÿ\0E52oh÷¼3YkRFà«+h7¤éş¦Ÿã¿ù\'&ÿ\0°U×şŠjŞ‘öFÏŒíâ“i-F¯}3íŞ?Oñ¹ÿ\0Yæh×íçzy™‹çÇlç¨ûw†OßÓüA.Öyš5ûyŞfbùñÛ9Çj­Ä[±£JóBHcŸI—T´Xï|Ã Œ)dİ‡ç\\»¸<U½[Çh¶Ö\"şÛJ³¼½Y&Š;ÍWÉˆD¡zÈcÿ\0XK°:üØœ\"·_×ô¾³_ùßßıwöïŸ¿§ø‚\\ÿ\0¬ó4köó½<ÌÅóã¶sÔ}»Ã\'ïéş —?ë<Í\Zı¼ïO31|øíœãµ9<}Ö¡¤[YÁfQ¶‚é\ríğ·wYIa]¬%u\0’2½G­6Ûâ%•Ï‰²“ì%^âkXÂßr$ˆ1bğmù”`q\'åàgÂ+ußğ¬Öşw÷÷·xdıı?Äçıg™£_·éæf/Ÿ³œv£íŞ?Oñ¹ÿ\0Yæh×íçzy™‹çÇlç«.÷â¯7…\'¼‹L‚ÂâïF›Q°‘nüí¡îŞ`7‚Ì9Û]Ş‘5ıÆ™º”ğÜ²å–	Œ«ÇqDäúcz=œ{_Ò‰­üïïş»˜–~$Ñl\"d†Ó_;çy4Kçw>¥ŒY<`}Yÿ\0„ËKÿ\0Ÿ]sÿ\07¿üf©ë¾4]]¶±¸şÊh§–•¥‹ÏŞ0@ÂÜ§*òCôöÅfßx“Q¿ñ†Öğ‹mjóZ™…Ó	.qJ4A1³zœeİ³V¶›_æe\')6å¿õşF÷ü&Z_üúëŸø!½ÿ\0ã4Âe¥ÿ\0Ï®¹ÿ\0‚ßş3\\×u1©Zë­d#Ò¿±oo­­–õ‹\\ª˜Š`Tl0_ã³«xâmÚÄ_ÛiVw—«$ÑGyªù1”/Yë	p6\0G_›Êïúßü…mmıZšğ™ióë®à†÷ÿ\0ŒÑÿ\0	–—ÿ\0>ºçşoøÍf\'£ºÔ4‹k8,Âê6Ğ]!½¾îë)#l+µ„® FW¨õ«ºŠÛZ×¯´ô†Æ8í^Tdûvë¥(ûrğlU¹ î<cÖíëø\nú_Óñ&ÿ\0„ËKÿ\0Ÿ]sÿ\07¿üføL´¿ùõ×?ğC{ÿ\0Æk ¢ÿ\0ü&Z_üúëŸø!½ÿ\0ã4Âe¥ÿ\0Ï®¹ÿ\0‚ßş3]Áè^,Ó¢Ö<Ním¬‘.¦»t[Æ }’İ~`\"Êœ©ààã¡É©kVzŒ˜uÕ^İ$Yâ]É²Uû½aû¹çŒ6z0­ÈsÅŸöOı\"µª&Ô<E§jzZi×ºZ[j‹h©qa$1»,³(o¹Ó¯^)5{\"á7µø«şf>¡ya«ˆ†§õè…¼èÅÏ„î¤Ù\' İ	Â}>oöªK}VŞĞ¡¶:”%#\n¾_…n×hˆ†!ÿ\0R2~_½şÕlÏâÔÓe{{»[«±hbŠÿ\0Pµ…Ş	U¤/Œ2±Úha“Ö§“ÅÖkm£<7#QóÒ(íö.éQ”·š¼ÿ\0«\06IÆ\n‘Œ\nä]\r>±>Ëî_ärÑ>“·2Ãgu—L&¸tğÈ2Êp|Ÿ™7sÏÍwT÷:¥âÜ-Òê­Ê<KáK¦‘œFÙ‡æˆdà›“óVÿ\0†¼A}­]êĞİé3Ù¥ÛCcÃ\0àí‘îsœ‚;æº*\\ŠÈ>³;½Ü¿Èó›K2Â$ŠÎÚòŞ4\\*Cá+”0F\0‡„ÇßÇñU»mCI]Böîò-fíud†î	<7yåÈT¶<®›\\®r\0ç®{º)¨¤LêÊJÍ/’Kò8HÇ€¡Xâğ„‰ÊU_	Ü€à@#ÈädÏp*Ä·ŞŸM‡M›ÃwrXBÛ¢µİóÊ¡ƒ\0òzæ»:*Œ&çUÑ§Ô4yR\rjM,»Åk‡¯B—)±`V~1ÜtÇ:ßğ™ióë®à†÷ÿ\0Œ×AE\0sÿ\0ğ™ióë®à†÷ÿ\0ŒÑÿ\0	–—ÿ\0>ºçşoøÍtP?ÿ\0	–—ÿ\0>ºçşoøÍğ™ióë®à†÷ÿ\0Œ×AE\0sÿ\0ğ™ióë®à†÷ÿ\0ŒÑÿ\0	–—ÿ\0>ºçşoøÍtP?ÿ\0	–—ÿ\0>ºçşoøÍğ™ióë®à†÷ÿ\0Œ×AE\0sÿ\0ğ™ióë®à†÷ÿ\0ŒÑÿ\0	–—ÿ\0>ºçşoøÍtPàÏiÖŞğôm¬—L¶F1è·¤ˆ”pË=Á ö«—\Z§„ï5(µ+¯ßO}<»©|3vÒ¦FÁ‘ƒÓšĞğ\'ü“Ïÿ\0Ø*×ÿ\0E-bÂW­Çv×nté4ñ­ÿ\0e-¢Àë9öo ‘ÅväPµ’_×EúƒÑ_úïú&Õ¼+s©Å©Ï ßË¨D\0éü5vÒ ÆÃ‘Ô÷ïM}GÂ2ê£U“Ã·¯¨‚»o]\0ïò3Àã­]oÚ*ßËı¨›{)šÙ¦òĞ,³‡\"ŒÜÌÄŒmç–¼ei\r…ì÷vÖ—rÅ–s¼ÒÒ#ÁW1á‹c;ğ9#…İì$>*ÑmÃˆ4ıf îd`½]ÌNI8‡©=ê¢jŞ‹U}V=ı5{µğÕØ™†1Ëù9<\0:Ö•Ç‰^Ú;8Î‰©>¡v“OC™Q>ó³y¾X¯ñçæuÄMãF¶Ó$´±¿¼—RŞ!C‚˜Ş¯½•PŒœä•#9À&ÀsÚ…Ş™¨ı¡.-o.a’A\'•uá;©SĞsÌTp¤`Œsš­$z-º6iVÍ‹Z	üpÁK±8€`ç‘³o g5¸ş6šâëÃÇLÑï.m56JO”¯@ÙL4‹†VS»¨À8$âµµß¦‚,ºf¡sm^uÍÅº&ÈŸ˜–e-Ğ’1\0r9DÖ\"oK/¹‘ËO{c=çÛ%Šşâé\"0$×>ºwxÛ;’F‚W“€»G<æ¥mZAÎ§)Š#Fo]Ñ¶7#âòğ0hàg5cÅş3–ÃHÕ×Iµ½’[8”=üQÆğÁ#\0Ê¬·‚¤á†HçŒ<e>¤êé¥Ú_=Å”J$¿Š8ŞyX\nÁ›q8*N¸dpr/ëúş¯æYŸe÷/ò3-N§BğØXÜÃË„¹Q.ïŞ…„giåvíÁëšµ6©m*Ü,ŸÚS‰$‚ãÂ·Mö…ç‰±ÜNĞ1Œóšè&ñe¥¾¡}k%¥ç•§\'™{y±D0/—¼Knl0 z€&(¼ej°^É¨éÚ†˜m,şÜÉvˆYáË/–ì8Æ8###š9P,Dû/¹—õC›½}\'P»ŠîòÎêöæØo5ç„.d|g8fòsÁ$®İ¸ïšéañv›(¿+(Á‘´ÀXúœCŠt¾+û=…¬óèºŒSŞN µ´/nd˜ì/ÂS\rÕã§#;°Hó[Ç+Á$ê¢©d\'±ÚHÈö${ÓQ±ªæ•íòI~Fü&Z_üúëŸø!½ÿ\0ã4Âe¥ÿ\0Ï®¹ÿ\0‚ßş3]ÌÎş-/ş}uÏüŞÿ\0ñš?á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0®‚Š\0çÿ\0á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0£ş-/ş}uÏüŞÿ\0ñšè( Á,Ó­¼áèÛY/™lŒcÑoI(á–\"{‚AíZO¯ørK[‹Y4}MíîYšx[Ã—…%-÷‹\'O|õ«^ÿ\0’yáŸûZÿ\0è¥¬¼u¨-Ïˆa»¶¶Œ[£¦HªÛe0½Ÿ¼>VàŒ‚ºM&Ò½û1¤ÿ\0^?è0Ì³E¦jÉ*Ä!¾¼r>Oİ(´ñ&aEe¥êÖÑ³™!ğõâÄä±¤õ5™uãkësI{ks¥Ía\rÍôÊ|&V*¬9ÆÀØ=g<WEáíZ}Z=E§HÔÛjZ§–Ê¡À\'$óU×ïüŸâMÕ¾ïÅ]~øL´¿ùõ×?ğC{ÿ\0Æhÿ\0„ËKÿ\0Ÿ]sÿ\07¿üfº\n)çÿ\0á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0£ş-/ş}uÏüŞÿ\0ñšè( ş-/ş}uÏüŞÿ\0ñš?á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0®‚Š\0çÿ\0á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0£ş-/ş}uÏüŞÿ\0ñšè( ş-/ş}uÏüŞÿ\0ñš?á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0®‚Š\0çÿ\0á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0£ş-/ş}uÏüŞÿ\0ñšè( ş-/ş}uÏüŞÿ\0ñš?á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0®‚Š\0çÿ\0á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0£ş-/ş}uÏüŞÿ\0ñšè( ş-/ş}uÏüŞÿ\0ñš?á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0®‚Š\0çÿ\0á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0£ş-/ş}uÏüŞÿ\0ñšè( ş-/ş}uÏüŞÿ\0ñš?á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0®‚Š\0çÿ\0á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0£ş-/ş}uÏüŞÿ\0ñšè( ş-/ş}uÏüŞÿ\0ñš?á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0®‚Š\0çÿ\0á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0£ş-/ş}uÏüŞÿ\0ñšè( ş-/ş}uÏüŞÿ\0ñš?á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0®‚Š\0çü	ÿ\0$óÃ?ö\nµÿ\0ÑK]sşÿ\0’yáŸûZÿ\0è¥®‚€9ÿ\0ÿ\0É<ñ7ı‚®¿ôSVÕÚK%œÉ*ÊÈBÇ‹ã¿ù\'&ÿ\0°U×şŠjƒ´ 	7zà©:õïÿ\0¥%uf4ìîr:7€uøF¢±Õ.‹Ë.˜tõó™	°€Ş¨#P¶’I]£–ç=¡¡kz„–sÁu™wn­W6ìáBä`êV@ÛTô]¤½Ûa¥xSU–H´í~şòH”4‰oâk¹\nĞ³œÚ™Ÿá«7¼·ñäÖ±È\"y£ñ=Ó\"¹ ,\'À$‘Ç¸¤Óz·ıMıæŞßû«îş»/»Íİ·ŞÖµI-Ö{öK0b-fò‰£\rVvdŞä	ÚÉÓÜæK_kV×ÓKà‚ÖâI&>fèmälîn<›‰c´°U-œCöom¿á(¹Ûq·É?ğ”İ~óq!vşÿ\0œ•`1×Ò¤\Zw„M½İÀñé‚Íö]J<Ou¶Î0çÏÂœö4œ;¿ëúş¶oÚ+îş¿àõİŞ¨ğEËØYY8„A.šŠòÙ…[w±Ø0FÍ¹<5\\µ‹Ç6rZÄš]àE¼÷·„H u#Tû`|Ôbğoörj?ğ’İı…Ø¢\\ÿ\0ÂQuå³\0I¼ü\0?‘§ÜiµÓaÔ®|C{\rŒä®¤ñ5ÒÅ&FFÖ3àäĞö¦âú°öÿ\0İ_wõÙ}Şnô¯<«jW³\\½ïØã½ºŠò{H¤Y\"I¢Ù´–h÷¸>Zğ\0ô4õğn¢uhoVäC^=ìV^`xmåees÷H{nP¤ñšµu¦xJÆâ;{ÏŞÛÏ.ß.)¼MtŒû³·\0Ï“œzàÕm:	êšæ¡£[êÚ¿Ûìeòä€øŠï{aU‹*‰òTnÁ8 Š:_úÓşõ`uÿ\0º¾ïëşÍŞŸü+ÛÙ!hõÖİì§±‚•]l¡“UÂ\'İ$‚£®êÙÔ4-oP’Îx.£Ó.íÕ£Šæİƒ¼(@ÜŒJÈj‹´×»µ\rÃzE·Úu=gS²·ÜÍ¹ñÜk“Ğe¦5^ïNğ–•©q¯ßEc(ıÍÄ&ºXä$¹›8=)5e¿õı7÷Ûëğ¯»úì¾ï7t¾ğÆµªIn³ß²Yƒk7”Mh˜2³³&÷$€NÖNœîç3Xø{X‹^U»¼ûK\"B“º¿Ø‘ØU*Šdİ´¸ü¸ïÎyû›ß\rYxÛÄw—º•´×VFêŞÊo]£LÁwlBeù p;+z\r\'ÃİGf5­D_:çì¿ğ’İ™1|ìğ=ê¹\Z{ì\'][áZÿ\0_åëçw~ÂŠã`Ó¼#ua=ı¿ˆ¯f³·$Mq‰îš8ˆäîa>^ôÉ-|Ÿ¡/‰îRÊv+\rËx¦èG!ÈVóğHÁééLÀíh®|x;K#\"ï\\Çı‡¯øõğ†éóõ®àú÷ÿ\0Páïùx³şÂ©ÿ\0¤VµsXÑÿ\0µn4©|ÿ\0+û>ô]ãfï3é·¨ÇßÎyé\\¾…á=:]cÄè×:Èjh‹·Z¼RGÙ-Ûæ\"\\±ËNN0:\0ªÅámõ-/n¼X²ÈÁ#ò®õiVF ªÈÅXà€sÁô£ªŒ³à{]jñC¡Ëö¹Y—RÒêEeP¿»“z•(à†ÁÉïŠ¿?…d›Äƒ_\Z“-ürªÄ|²Qm±†„®ìÄ–İÁİ´ã‚–şÒ.­£;ŸªH¡”K¬ßÆÀUiASì@5/ü!º_üıkŸø>½ÿ\0ãÔm§`Ü³¥i\Zf§ªMöÈå³½Ÿí+	€‰#rª­óîÃ/ËÀÚÏSZõËÚøsB½k…·¾×ÛL`—ş\'—Ãk€	ËÏt«ğ†éóõ®àú÷ÿ\0QÑS ¢¹ÿ\0øCt¿ùú×?ğ}{ÿ\0Ç¨ÿ\0„7Kÿ\0Ÿ­sÿ\0×¿üz€:\n+Ÿÿ\0„7Kÿ\0Ÿ­sÿ\0×¿üzøCt¿ùú×?ğ}{ÿ\0Ç¨ ¢¹ÿ\0øCt¿ùú×?ğ}{ÿ\0Ç¨ÿ\0„7Kÿ\0Ÿ­sÿ\0×¿üz€:\n+Ÿÿ\0„7Kÿ\0Ÿ­sÿ\0×¿üzøCt¿ùú×?ğ}{ÿ\0Ç¨ ¢¹ÿ\0øCt¿ùú×?ğ}{ÿ\0Ç¨ÿ\0„7Kÿ\0Ÿ­sÿ\0×¿üz€:\n+Ÿÿ\0„7Kÿ\0Ÿ­sÿ\0×¿üzøCt¿ùú×?ğ}{ÿ\0Ç¨ ¢¹ÿ\0øCt¿ùú×?ğ}{ÿ\0Ç¨ÿ\0„7Kÿ\0Ÿ­sÿ\0×¿üz€:\n+Ÿÿ\0„7Kÿ\0Ÿ­sÿ\0×¿üzøCt¿ùú×?ğ}{ÿ\0Ç¨\0ğ\'ü“Ïÿ\0Ø*×ÿ\0E-\Z7„4Í#R½Ô­¤ÚÍÔ·óìÊ²¢¹û›ù$Ç_Â°üá=:çÀ¾îuòi–ÎÂ=jñeG°\0ÔŠ¾mOû?ûOÄK7Ú\r®÷Ôõ5„Ì?å˜”¿–[Ø6Iâ…¾›ƒÛ]kïA¨xwQÒ..ÖîõïQ\0Êd(d$‡P@q‘™¦XxRm?E¾±·‹ÃPIv@o³èf8:$BoŸ ‘÷†3ĞÕ¯øCt¿ùú×?ğ}{ÿ\0Ç¨ÿ\0„7Kÿ\0Ÿ­sÿ\0×¿üz„¬¬Öæ¿\r-ŞÇMO3MâÈÏµ/´Ñqk²VQ!.\n!Báøæ·ìü8-n4yƒÚCıÑy6vLMæmåSqØŞ™=i¿ğ†éóõ®àú÷ÿ\0Qÿ\0n—ÿ\0?Zçş¯øõ\0WO	Ü[C¦=J4¸°½¸¹-±tu˜¹d*@~=ºsŠ¥âÿ\0\0ÿ\0ÂWw<¯yfkO³»°-nrÇ|ºˆØädàçjôÅjÿ\0Â¥ÿ\0ÏÖ¹ÿ\0ƒëßş=Gü!º_üıkŸø>½ÿ\0ãÔÓc3Rğ=İÜ\Zµ¦´–¶:¨V¸ŒÙ‡H¨©•}à!*T¸a«ø*ïP]fÚÓYKK\\¬—›1$‚@Š™WÜ\0Rr\n“×3Æü!º_üıkŸø>½ÿ\0ãÕ^×Ãšë\\-½ö¸æÚc¿ñ<¾\\\0Hæ^x#¥;»ÿ\0_×alZ¸ğ´‘x‚©ÙàÖPG\"¢€ÑŒàœ‚xÏOÎ¨è>›AäÛ¯‡\"¸’*9ìô?³œúÉ¶_œwÚ6ÕÏøCt¿ùú×?ğ}{ÿ\0Çªƒhz\"kÑhæı¢Kf¹\rı¹y³j²©ó³œ°íIonãéèGæ¶Ğï4è¥Ñ/.Ó[Ë¤³Y¨\n\0Xàó†Ã•Nã““N›GÓ†‘£YéÂyn>Í\nÅæÊrÏŒšç[CÑ^‹G3ø‡í[5ÈoíËÍ›U•HÏœå‡j¿ÿ\0n—ÿ\0?Zçş¯øõ?®š_ë®§AEsÿ\0ğ†éóõ®àú÷ÿ\0Qÿ\0n—ÿ\0?Zçş¯øõ\0tW?ÿ\0n—ÿ\0?Zçş¯øõğ†éóõ®àú÷ÿ\0PAEsÿ\0ğ†éóõ®àú÷ÿ\0Qÿ\0n—ÿ\0?Zçş¯øõ\0ÿ\0’yáŸûZÿ\0è¥¬íSÀQê~ÔôÃ¨43İ^Ë{ÚEó[»ç#ù†ÒÊyzUOxON¹ğ/‡§{d<še³°Z¼EÄ§…Y@Qì\0µnÂ¥ÿ\0ÏÖ¹ÿ\0ƒëßş=I¤ÿ\0¯ë°Óhl~€İïº•n-ÛI]2H\Z<PI-œ÷ıişğÜÑä°“Q“Pg¸’o>TÚÇqàNHûÒÂ¥ÿ\0ÏÖ¹ÿ\0ƒëßş=Gü!º_üıkŸø>½ÿ\0ãÕWÖÿ\0Ö÷üÉ¶–ş¶·ätW?ÿ\0n—ÿ\0?Zçş¯øõğ†éóõ®àú÷ÿ\0RĞQ\\ÿ\0ü!º_üıkŸø>½ÿ\0ãÔÂ¥ÿ\0ÏÖ¹ÿ\0ƒëßş=@Ïÿ\0Â¥ÿ\0ÏÖ¹ÿ\0ƒëßş=Gü!º_üıkŸø>½ÿ\0ãÔĞQ\\ÿ\0ü!º_üıkŸø>½ÿ\0ãÔÂ¥ÿ\0ÏÖ¹ÿ\0ƒëßş=@Ïÿ\0Â¥ÿ\0ÏÖ¹ÿ\0ƒëßş=Gü!º_üıkŸø>½ÿ\0ãÔĞQ\\ÿ\0ü!º_üıkŸø>½ÿ\0ãÔÂ¥ÿ\0ÏÖ¹ÿ\0ƒëßş=@Ïÿ\0Â¥ÿ\0ÏÖ¹ÿ\0ƒëßş=Gü!º_üıkŸø>½ÿ\0ãÔĞQ\\ÿ\0ü!º_üıkŸø>½ÿ\0ãÔÂ¥ÿ\0ÏÖ¹ÿ\0ƒëßş=@Ïÿ\0Â¥ÿ\0ÏÖ¹ÿ\0ƒëßş=Gü!º_üıkŸø>½ÿ\0ãÔĞQ\\ÿ\0ü!º_üıkŸø>½ÿ\0ãÔÂ¥ÿ\0ÏÖ¹ÿ\0ƒëßş=@Ïÿ\0Â¥ÿ\0ÏÖ¹ÿ\0ƒëßş=Gü!º_üıkŸø>½ÿ\0ãÔĞQ\\ÿ\0ü!º_üıkŸø>½ÿ\0ãÔÂ¥ÿ\0ÏÖ¹ÿ\0ƒëßş=@Ïÿ\0Â¥ÿ\0ÏÖ¹ÿ\0ƒëßş=Gü!º_üıkŸø>½ÿ\0ãÔĞQ\\ÿ\0ü!º_üıkŸø>½ÿ\0ãÔÂ¥ÿ\0ÏÖ¹ÿ\0ƒëßş=@Ïÿ\0Â¥ÿ\0ÏÖ¹ÿ\0ƒëßş=Gü!º_üıkŸø>½ÿ\0ãÔ\0xşIç†ìkÿ\0¢–º\nçü	ÿ\0$óÃ?ö\nµÿ\0ÑK]\0sş;ÿ\0’yâoû]è¦­›ÙV‰1U‰Ú¥OAÉ¬oÿ\0É<ñ7ı‚®¿ôSPl¼^A\\Ğˆ=AÑ¥ÿ\0äªRWM-&›<ÓDS¬x^Æ‚M1­ü=6Ÿ²¨-u$Á9ŒFX²©B[ŒÜ¸¶öæÿ\0Jºy¿´g¹š]=\Zæµ68fşRÛ WURÙ9-Ø¨ÀÏSÍõ¥üZ¿‹<\räCdVéŒ² Æp#YsÀéZ£Oñ`ÛkAzcE—üš¢ó¿7õ½ÿ\0Sg*³‹û×——‘Êx™aÔ¯¼U5²Çqöİ	,¬Ù\0c<™”²§÷€Ü™#ß¥%åİÕ†£¨İè{~}6ÆÚn™öHşhOÊîˆÜg*^3]`ÓüX6ãZĞFŞ˜Ñeãÿ\0&ª)!ñ-´$¾ ğäO+”…[G‘K¶	!Ò¹8àv’çÚÿ\0Ö¿æ7*ì¿½yyy6”—WŠòì]LŸÛÂø5Ñå’1hcŞ#øF	÷4Í¯Ù®aÿ\0]j+äéfİå’9æŞ¯œ\Z7\\˜/Ì»¹Ç\"»ñ§ø°mÆµ ½1¢ËÇşMP4ÿ\0\r¸Ö´·¦4Yxÿ\0Éª-/ëÑ/Ğ9èÿ\0+û×Ÿ—™ÂÜéñÇ¦ëV–Ãíg† ÓlÎñ+M\"ù¹E`ünL\0ñŠÙÓ<G§økWÖ¦Õ\'ØŞKÄwæDòIÇAónv§;TÜ•¸añ,w1[xqnYâˆèò*1¸ö¬àddQQF|DÚ¤šlzşƒö»x–fŒhs\r¨å€9ûN9*İjwï}ÿ\0Îân‡ò½<×’íåø”|Cã;{¨ÖÛÃz¨ºšÓí‹¦42ÜˆŠ±H“(Çp\\ç8]İëšÑ¤ºÒaÓ¯$¶¸¿–56ë$M)óåŞ²©R#|\0Cˆó·qã¨®ôiş,q­h#oLh²ñÿ\0“T\r?Åƒn5­mé^?òj¦Ò³Ô|ô?•ıëü6h¯lü/%”V-}swá¸ôÅX%CäÌ¡÷\0K~ñNñ“ìçšè\"†)4ÿ\0Û‰ã†}X,v³ /æ³$yR™,ƒgÇ5Ô?Åƒn5­mé^?òj§ø°mÆµ ½1¢ËÇşMS|îúïşw:)§Êôó^K·‘ÃÅåÍ¦jk¯\\]J¶‰~u™˜˜\\¸kqœ!9ä“Á[{{©É¦ZÜ!¾“ZHî£‚âÙm:HàªN‡ä—…Bæ\0HÁéßº\Z‹ÜkZÛÓ\Z,¼äÕOñ`ÛkAzcE—üš£ß½ïı}ÿ\0× ”¨/²şõşFí´‚[Xd†@wE÷£Ú¥®yl|\\«µu½Øh²ÿ\0òU/Øüaÿ\0Aİÿ\0ÓòUQ†Ãßòñgı…Sÿ\0H­i)¶¸¸¿ğËA’¬:²É)D,|™FæÇA’O¨¬m\n×ÅGXñ8‹YÑ•Æ¦‚RÚL¬¾ÉoÊ´£nÑ‚O œó¹ö?ĞwCÿ\0Á4ßü•GTÃ£G¯h×—^2Ôd½ıË¼ğ>|º\rÅì°Æª¤ç‰ñ>U€ÎI9´nôË¯øOÍüvØ&ò/µBŠØ–ì!q³nLk”RAÁ`ğ„×Gö?ĞwCÿ\0Á4ßü•GØüaÿ\0Aİÿ\0ÓòUKyÀş½u­üÿ\0¯ëËC?ÁúM†‘®x†te²¸{Ã\"L–&4’¨T,v°İ»å ç\"»\nçşÇãúèø&›ÿ\0’¨ûŒ?è;¡ÿ\0àšoşJ£¢]ƒ«}Î‚ŠçşÇãúèø&›ÿ\0’¨ûŒ?è;¡ÿ\0àšoşJ ‚ŠçşÇãúèø&›ÿ\0’¨ûŒ?è;¡ÿ\0àšoşJ ‚ŠçşÇãúèø&›ÿ\0’¨ûŒ?è;¡ÿ\0àšoşJ ‚ŠçşÇãúèø&›ÿ\0’¨ûŒ?è;¡ÿ\0àšoşJ ‚ŠçşÇãúèø&›ÿ\0’¨ûŒ?è;¡ÿ\0àšoşJ ‚ŠçşÇãúèø&›ÿ\0’¨ûŒ?è;¡ÿ\0àšoşJ ‚ŠçşÇãúèø&›ÿ\0’¨ûŒ?è;¡ÿ\0àšoşJ ‚ŠçşÇãúèø&›ÿ\0’¨ûŒ?è;¡ÿ\0àšoşJ ÀŸòO<3ÿ\0`«_ıµƒ¤xkSÔ.oÖşámô´×d½Ko±²Í)I¡óKãa`	Î:Ó¼kâ¦ğ/‡šßYÑ£€é–Æ4“I•ÙWÊ\\Âäqß>‚·>Çãúèø&›ÿ\0’¨ZK›úİ?Ğªß×_ó8½7F¼ÿ\0„ËÏÔ?Ñõ$Õd˜\\¦ƒpòÍ	fØ†ñ\\Çå˜È]¤\0¸Á«—>\Z k·L›ûJçU`³,l\'0}©äaó*`nùp:·^k¨ûŒ?è;¡ÿ\0àšoşJ£ì~0ÿ\0 î‡ÿ\0‚i¿ù*…§õéş@İİÿ\0®¿æqº÷ƒ- ƒÅÏ¦è;H§ÓŞÜ€—[/\n&V<•8¦xßP¹ñRÍÉwıŸ•r4¹ç™â\r÷K Û!L<á‰15ÚıÆôĞÿ\0ğM7ÿ\0%Qö?ĞwCÿ\0Á4ßü•M;4ûKÇü#›Oö²i³\rZ?ïãÊc*Û´øl¢\"ŒÄòœ“Üš¥¦è×Ÿğ™yú‡ú>¤š¬“”ĞnY¡,ÛŞ+˜ü³´€##5Ú}ÆôĞÿ\0ğM7ÿ\0%Qö?ĞwCÿ\0Á4ßü•J>í¼¿àK[ùÿ\0Áÿ\03˜>Úµ“L˜jÑø|wSVİ§Ã`õf$”ääÖ¯„´«YñVú ´ºk¶‘$ËÊYa(…BÊ)·| ğs+Kì~0ÿ\0 î‡ÿ\0‚i¿ù*±øÃşƒºş	¦ÿ\0äªIY[Êß—ù~,®ïúßüÏ8°±×%¼º—JÒ›Nº¹ÑnÒE‡LšÙ–àì(²Ï+bâ@wb^2wòkA4€oo%ğƒ{¥ïğõÄ1³Y½ª›’S·çÆ>||Ø1ÚqÛıÆôĞÿ\0ğM7ÿ\0%Qö?ĞwCÿ\0Á4ßü•Oúüÿ\0Ìwëıtÿ\0#ˆM&ĞŞßKáÿ\0_XZ?‡n`e6[‡œ”Âì`	|>lp[oëŞpè	—mı”!‘ï#—G“QVº*d–Ø;¹Ç˜Û°zòA®Ãì~0ÿ\0 î‡ÿ\0‚i¿ù*±øÃşƒºş	¦ÿ\0äª?¯Ïüÿ\0%§õä—èqÚ¾øcA9µ››8$[{Kİâh\'ªÈA08Âª¼ÀİA5¨¶WÄh¯!ÓİÄî>Ğ÷Z\\›¬@‡ÃvT!N\0òÆI.Ç#‘[¿cñ‡ıt?üMÿ\0ÉT}ÆôĞÿ\0ğM7ÿ\0%S¾·ş¿¯éX:[úş¿§s ¢¹ÿ\0±øÃşƒºş	¦ÿ\0äª>Çãúèø&›ÿ\0’©ĞQ\\ÿ\0Øüaÿ\0Aİÿ\0ÓòUcñ‡ıt?üMÿ\0ÉT\0xşIç†ìkÿ\0¢–º\nàükâ¦ğ/‡šßYÑ£€é–Æ4“I•ÙWÊ\\Âäqß>‚·>Çãúèø&›ÿ\0’¨ ¢¹ÿ\0±øÃşƒºş	¦ÿ\0äª>Çãúèø&›ÿ\0’¨ ¢¹ÿ\0±øÃşƒºş	¦ÿ\0äª>Çãúèø&›ÿ\0’¨ ¢¹ÿ\0±øÃşƒºş	¦ÿ\0äª>Çãúèø&›ÿ\0’¨ ¢¹ÿ\0±øÃşƒºş	¦ÿ\0äª>Çãúèø&›ÿ\0’¨ ¢¹ÿ\0±øÃşƒºş	¦ÿ\0äª>Çãúèø&›ÿ\0’¨ ¢¹ÿ\0±øÃşƒºş	¦ÿ\0äª>Çãúèø&›ÿ\0’¨ ¢¹ÿ\0±øÃşƒºş	¦ÿ\0äª>Çãúèø&›ÿ\0’¨ ¢¹ÿ\0±øÃşƒºş	¦ÿ\0äª>Çãúèø&›ÿ\0’¨ ¢¹ÿ\0±øÃşƒºş	¦ÿ\0äª>Çãúèø&›ÿ\0’¨ ¢¹ÿ\0±øÃşƒºş	¦ÿ\0äª>Çãúèø&›ÿ\0’¨ ¢¹ÿ\0±øÃşƒºş	¦ÿ\0äª>Çãúèø&›ÿ\0’¨ ¢¹ÿ\0±øÃşƒºş	¦ÿ\0äª>Çãúèø&›ÿ\0’¨ ¢¹ÿ\0±øÃşƒºş	¦ÿ\0äª>Çãúèø&›ÿ\0’¨ ¢¹ÿ\0±øÃşƒºş	¦ÿ\0äª>Çãúèø&›ÿ\0’¨ ¢¹ÿ\0±øÃşƒºş	¦ÿ\0äª>Çãúèø&›ÿ\0’¨ ¢¹ÿ\0±øÃşƒºş	¦ÿ\0äª>Çãúèø&›ÿ\0’¨\0ğ\'ü“Ïÿ\0Ø*×ÿ\0E-tÏøşIç†ìkÿ\0¢–º\n\0çüwÿ\0$óÄßö\nºÿ\0ÑM[ÒgËm¤Çö5ƒã¿ù\'&ÿ\0°U×şŠjdŞ,Ñî!xf²Ö¤ÁVVĞoH#ÓıM&®¬5kêcxGVÑ,¼;a¡Ş\\ZG®£ââÂlyíu»sI°üÇ-óïÆ1óg×9o©êSè×ŸÙÚ¦¡sªI¡İKªÄ.ÚÒôcbªî>KdÊ(\\…p+ªûw†OßÓüA.Öyš5ûyŞfbùñÛ9Çj>İá“÷ôÿ\0KŸõf~Şw§™˜¾|vÎqÚ•çÛú·õ÷#t¨\'ñ?»ş	Íj¾$mVî÷û+\\K_#IA-¥Ëm÷L²m`q’8$z`ôÅ3T†İ5d·ÔµØô/Ä1q>§2#{=ß4ÅÃc{`Ù¶ƒƒŠê>İá“÷ôÿ\0KŸõf~Şw§™˜¾|vÎqÚ·xdıı?Äçıg™£_·éæf/Ÿ³œv§yvş®Ÿéø‰F…­Ìşï+w1ìc¾êzí–§©\\ßÙêB­}#£ÃÃ(!m¤2 G<u¹—RÓ®u©g™¢Ô§7ÑÈÇÛà,[TıİÊ¡ÈõsXWÒxfşÂ{G·ñ2yèRYWH¾.ùèX´\'q‰±L?ÜY¥¤š‰D*¡bÓu8üÀ>èbˆmÀÆzt¯.ß×_É~=ÁÆ…ş\'÷Áõü~0ÔuK=v%Ò¥—Ï\Z-ü±Â§*ò¯•°•èÄdã>¾õGÀÒisxËS“IÔ¦Ô`m.Ì½Ä·rKï—#s³cİAÀ9àUˆ%ğ­»Hßañ,ÆSºo´iš”Â\\\rÁã9İjõ¦· ØÍçAiâ4‚®í£_³Ié¸˜¾lvÏAÀ¢.K§õ¯ùşš¤ãe\'OO?/Äë¨®ş-/ş}uÏüŞÿ\0ñš?á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0ª1:\n+Ÿÿ\0„ËKÿ\0Ÿ]sÿ\07¿üføL´¿ùõ×?ğC{ÿ\0Æh ¢¹ÿ\0øL´¿ùõ×?ğC{ÿ\0Æhÿ\0„ËKÿ\0Ÿ]sÿ\07¿üf€:\n+Ÿÿ\0„ËKÿ\0Ÿ]sÿ\07¿üføL´¿ùõ×?ğC{ÿ\0Æh\0ğ÷ü‡<Yÿ\0aTÿ\0Ò+Zè+ƒĞ¼Y§E¬xÚÛY\"]Mvè·Œ@û%ºüÀE•9SÁÁÆB	Üÿ\0„ËKÿ\0Ÿ]sÿ\07¿üf€:\n+Ÿÿ\0„ËKÿ\0Ÿ]sÿ\07¿üføL´¿ùõ×?ğC{ÿ\0Æh ¢¹ÿ\0øL´¿ùõ×?ğC{ÿ\0Æhÿ\0„ËKÿ\0Ÿ]sÿ\07¿üf€:\n+Ÿÿ\0„ËKÿ\0Ÿ]sÿ\07¿üføL´¿ùõ×?ğC{ÿ\0Æh ¢¹ÿ\0øL´¿ùõ×?ğC{ÿ\0Æhÿ\0„ËKÿ\0Ÿ]sÿ\07¿üf€:\n+Ÿÿ\0„ËKÿ\0Ÿ]sÿ\07¿üføL´¿ùõ×?ğC{ÿ\0Æh ¢¹ÿ\0øL´¿ùõ×?ğC{ÿ\0Æhÿ\0„ËKÿ\0Ÿ]sÿ\07¿üf€:\n+Ÿÿ\0„ËKÿ\0Ÿ]sÿ\07¿üføL´¿ùõ×?ğC{ÿ\0Æh ¢¹ÿ\0øL´¿ùõ×?ğC{ÿ\0Æhÿ\0„ËKÿ\0Ÿ]sÿ\07¿üf€:\n+Ÿÿ\0„ËKÿ\0Ÿ]sÿ\07¿üføL´¿ùõ×?ğC{ÿ\0Æh ¢¹ÿ\0øL´¿ùõ×?ğC{ÿ\0Æhÿ\0„ËKÿ\0Ÿ]sÿ\07¿üf€É<ğÏı‚­ôR×A\\ƒ<Y§[xÃĞ=¶²^=2ÙÇ¢Ş:’\"QÃ,D0÷ƒÚ·?á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0 ‚Šçÿ\0á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0£ş-/ş}uÏüŞÿ\0ñš\0è(®ş-/ş}uÏüŞÿ\0ñš?á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0 ‚Šçÿ\0á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0£ş-/ş}uÏüŞÿ\0ñš\0è(®ş-/ş}uÏüŞÿ\0ñš?á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0 ‚Šçÿ\0á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0£ş-/ş}uÏüŞÿ\0ñš\0è(®ş-/ş}uÏüŞÿ\0ñš?á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0 ‚Šçÿ\0á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0£ş-/ş}uÏüŞÿ\0ñš\0è(®ş-/ş}uÏüŞÿ\0ñš?á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0 ‚Šçÿ\0á2Òÿ\0ç×\\ÿ\0Á\rïÿ\0£ş-/ş}uÏüŞÿ\0ñš\0<	ÿ\0$óÃ?ö\nµÿ\0ÑK]p~ñfmà_@öÚÉxôËdc‹xêH‰G±ÃÜjÜÿ\0„ËKÿ\0Ÿ]sÿ\07¿üf€:\n+Ÿÿ\0„ËKÿ\0Ÿ]sÿ\07¿üføL´¿ùõ×?ğC{ÿ\0Æh ¢¹ÿ\0øL´¿ùõ×?ğC{ÿ\0Æhÿ\0„ËKÿ\0Ÿ]sÿ\07¿üf€:\n+Ÿÿ\0„ËKÿ\0Ÿ]sÿ\07¿üføL´¿ùõ×?ğC{ÿ\0Æh ¢¹ÿ\0øL´¿ùõ×?ğC{ÿ\0Æhÿ\0„ËKÿ\0Ÿ]sÿ\07¿üf€:\n+Ÿÿ\0„ËKÿ\0Ÿ]sÿ\07¿üføL´¿ùõ×?ğC{ÿ\0Æh ¢¹ÿ\0øL´¿ùõ×?ğC{ÿ\0Æhÿ\0„ËKÿ\0Ÿ]sÿ\07¿üf€:\n+Ÿÿ\0„ËKÿ\0Ÿ]sÿ\07¿üføL´¿ùõ×?ğC{ÿ\0Æh ¢¹ÿ\0øL´¿ùõ×?ğC{ÿ\0Æhÿ\0„ËKÿ\0Ÿ]sÿ\07¿üf€:\n+Ÿÿ\0„ËKÿ\0Ÿ]sÿ\07¿üføL´¿ùõ×?ğC{ÿ\0Æh ¢¹ÿ\0øL´¿ùõ×?ğC{ÿ\0Æhÿ\0„ËKÿ\0Ÿ]sÿ\07¿üf€:\n+Ÿÿ\0„ËKÿ\0Ÿ]sÿ\07¿üføL´¿ùõ×?ğC{ÿ\0Æh ¢¹ÿ\0øL´¿ùõ×?ğC{ÿ\0Æhÿ\0„ËKÿ\0Ÿ]sÿ\07¿üf€:\n+Ÿÿ\0„ËKÿ\0Ÿ]sÿ\07¿üføL´¿ùõ×?ğC{ÿ\0Æh ¢¹ÿ\0øL´¿ùõ×?ğC{ÿ\0Æhÿ\0„ËKÿ\0Ÿ]sÿ\07¿üf€:\n+Ÿÿ\0„ËKÿ\0Ÿ]sÿ\07¿üføL´¿ùõ×?ğC{ÿ\0Æh ¢¹ÿ\0øL´¿ùõ×?ğC{ÿ\0Æhÿ\0„ËKÿ\0Ÿ]sÿ\07¿üf€É<ğÏı‚­ôR×A\\ÿ\0?äxgşÁV¿ú)k  ÇòO<Mÿ\0`«¯ıÕ»3‚FS‚‘ùVÿ\0äx›şÁW_ú)«nå$–ÖXáuIHVeÈÜTÊü®Ã®®yş—ªëÖ^Ğ<Ku­Ï¨E{öt¼´¸‚Qç2¨hŒh¤f1`F{àÖ›ãdI5äKr×ì—RJ­ÿ\0òÉ…Eäù‡S€rI5´¿jÖ0éöòê/uie´Ú[]º4v¬Ÿu—\Z¦â6y \ZuÏ‚no´´Ó¦¤&ıïÁ2–h™Ğà1;).à–ëMÍ6ì»şŸ–æŞÃ¼—ßëı~].³xâæÉnno,N³¹kinTEM#§Îâ=Ê\0³|ÀÂğs+x×Èk+ËÁÙ›N¼¼“û>í.`u…­°$0TA¨šïÃúÕŞ¥s¨ÁxºuİÜ1CæÀáşÌ±30á—nŞAÈ\0Æ©Cà{€¨%X$Cu¾sîÏÁ¥g\n«¼±^6ùasĞÒçòïúÛôş¯gì5ÖK§_Kş¿Õ¯bãÆ÷Ö·+}¡Ç£´d¶Šğº:\\Iå©ó\n0`ÙqÀÁ9ãsAÖ§ÕN¡oyi­í…ÇÙç)Œ±’Q\\rªHÃª9ÏÖ¹¤ğV£%¼‰uwçÏ;Û±¹¸<-»‡>TUu$Ê~c’ÜUõÓ¼K§O©^ivÚcÜjs,ò%ÕË„•8*„°*€ô>´ùÖºZ[õ°Ì¾ÿ\0¿úòóWµ¯x’=Y„\\yÿ\0e]:êòQRˆÇØ®Iù0ÀuÈ<b—‡µbóÆÚœz¤)h£Mµ–;XnÚtMÏ.IÊ(pÀ=&›y ë:íû\\ê–ºtJm¦³Hã¸yUb&ıãb–$¦\nàsÏkğİş‘ªÏ}y5ä—ÑÛ“4ªÍÆ[hÊ¢†ÎóÎàg\'&ˆËºşµÿ\0€)Ñ´]¤¾ÿ\0Oø?ÓWêè¢Šf!EPEPEP?áïùx³şÂ©ÿ\0¤VµĞW?áïùx³şÂ©ÿ\0¤VµĞPEPEPEPEPEPEPEPEPEPEP?àOù\'ÿ\0°U¯şŠZè+Ÿğ\'ü“Ïÿ\0Ø*×ÿ\0E-t\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QEÏøşIç†ìkÿ\0¢–º\nçü	ÿ\0$óÃ?ö\nµÿ\0ÑK]\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0QE\0sşÿ\0’yáŸûZÿ\0è¥®‚¹ÿ\0É<ğÏı‚­ôR×A@ÿ\0ÿ\0äx›şÁW_ú)©²xÁé?ü\"zÚ	Çö|?üM;ÇòO<Mÿ\0`«¯ıÕµv]læ)HÛq–ã Ï2¿+°â®Ò<ËL‡@ÇIÔõ?‡^µÒõ?-b¸·Hçh^LybU0¦ĞIÆT¶3ÁÍhıŸáÜVŞmÏ†4RÆiãXìôsrØ‰Ê³Xw\02q´Ç‚héVÚÜº j:r%…’Ë%£I$—¦\0#\0Ê	,yœæ«]i\Z«Øy6:|v÷²Ú_Es=¬±yÒ—Êí_˜(Ád“j’£©¹Âú[Áü\rş­W·õ¯õò}®,ß¢Ô5{{ÿ\0é–‘i×)oö†ÑKFû‚`–mS¹À<›¡«ëağøKtÓøgB·¶¶µéÚãJò¥Ufu£x)€2XŸág:şÇU¸ºÔ\"´³k‹]BşÒù.$\rĞ˜w¨M˜DNY—“€Z³¯XİjÚ®¥}eo9k‹{?°‡€–[yšRm^@%°zà2”ãe¯õoóşµ«Uíı_ü¯égÙ‘¿ü C^ÓôÕğ5±[È¤q#øvDd(È\0(`Îş[€1ÏZŞÕ¼)àİ+G¼ÔÂ¾	mJŞe„ap£<•ˆE\'ØÖMŞ§¬¾«§kí¡nšÖ)à6ĞË+‰bsgCä†Ş¾YùWvx&®xƒ^Ä:±¢ØiÚ·Ún­šw›Oš(ägR2”\0¾qíšNq¶›ÿ\0V†©ÍªÓú¿õäû3+ÄPx#JXmìü)¡Í~óÚ£¨Ñ¼È£YdUù¤TÚ´’zpsÏWÿ\0\'ƒÿ\0èTĞÿ\0ğ]ÿ\0\\n¡§êÓOuŸeæÙß^ÙŞ‰%WICå	Wn1ˆÙ9$\0z×§Ã2\\B“G22¤ÄAö«R‹Zwÿ\0#)R©œÕ¿¯ëòİ3şOÿ\0Ğ©¡ÿ\0àºş&øA<ÿ\0B¦‡ÿ\0‚èøšè( ƒŸÿ\0„Áÿ\0ô*hø.‡ÿ\0‰£şOÿ\0Ğ©¡ÿ\0àºş&º\n(Ÿÿ\0„Áÿ\0ô*hø.‡ÿ\0‰£şOÿ\0Ğ©¡ÿ\0àºş&º\n(Ÿÿ\0„Áÿ\0ô*hø.‡ÿ\0‰£şOÿ\0Ğ©¡ÿ\0àºş&º\n(ƒĞ¼áYµ¤¾Ñ!ÔÑ\"V°ˆ„_²[¶åànf8É=ësşOÿ\0Ğ©¡ÿ\0àºş&ÈsÅŸöOı\"µ®‚€9ÿ\0øA<ÿ\0B¦‡ÿ\0‚èøš?áğı\nšş¡ÿ\0âk ¢€9ÿ\0øA<ÿ\0B¦‡ÿ\0‚èøš?áğı\nšş¡ÿ\0âk ¢€9ÿ\0øA<ÿ\0B¦‡ÿ\0‚èøš?áğı\nšş¡ÿ\0âk ¢€9ÿ\0øA<ÿ\0B¦‡ÿ\0‚èøš?áğı\nšş¡ÿ\0âk ¢€9ÿ\0øA<ÿ\0B¦‡ÿ\0‚èøš?áğı\nšş¡ÿ\0âk ¢€9ÿ\0øA<ÿ\0B¦‡ÿ\0‚èøš?áğı\nšş¡ÿ\0âk ¢€9ÿ\0øA<ÿ\0B¦‡ÿ\0‚èøš?áğı\nšş¡ÿ\0âk ¢€9ÿ\0øA<ÿ\0B¦‡ÿ\0‚èøš?áğı\nšş¡ÿ\0âk ¢€9ÿ\0øA<ÿ\0B¦‡ÿ\0‚èøš?áğı\nšş¡ÿ\0âk ¢€9ÿ\0øA<ÿ\0B¦‡ÿ\0‚èøš?áğı\nšş¡ÿ\0âk ¢€8?x3Â·^ğõÅÇ†ti§—L¶y$’Â&gc’I+’Iï[Ÿğ‚x?ş…Mÿ\0Ğÿ\0ñ4xşIç†ìkÿ\0¢–º\n\0çÿ\0áğı\nšş¡ÿ\0âhÿ\0„Áÿ\0ô*hø.‡ÿ\0‰®‚Š\0çÿ\0áğı\nšş¡ÿ\0âhÿ\0„Áÿ\0ô*hø.‡ÿ\0‰®‚Š\0çÿ\0áğı\nšş¡ÿ\0âhÿ\0„Áÿ\0ô*hø.‡ÿ\0‰®‚Š\0çÿ\0áğı\nšş¡ÿ\0âhÿ\0„Áÿ\0ô*hø.‡ÿ\0‰®‚Š\0çÿ\0áğı\nšş¡ÿ\0âhÿ\0„Áÿ\0ô*hø.‡ÿ\0‰®‚Š\0çÿ\0áğı\nšş¡ÿ\0âhÿ\0„Áÿ\0ô*hø.‡ÿ\0‰®‚Š\0çÿ\0áğı\nšş¡ÿ\0âhÿ\0„Áÿ\0ô*hø.‡ÿ\0‰®‚Š\0çÿ\0áğı\nšş¡ÿ\0âhÿ\0„Áÿ\0ô*hø.‡ÿ\0‰®‚Š\0çÿ\0áğı\nšş¡ÿ\0âhÿ\0„Áÿ\0ô*hø.‡ÿ\0‰®‚Š\0àüàÏ\nİxÃ×Ñ¦]2Ùä’K™ŒJI$®I\'½nÂ	àÿ\0ú4?üCÿ\0ÄÑàOù\'ÿ\0°U¯şŠZè(Ÿÿ\0„Áÿ\0ô*hø.‡ÿ\0‰£şOÿ\0Ğ©¡ÿ\0àºş&º\n(Ÿÿ\0„Áÿ\0ô*hø.‡ÿ\0‰£şOÿ\0Ğ©¡ÿ\0àºş&º\n(Ÿÿ\0„Áÿ\0ô*hø.‡ÿ\0‰£şOÿ\0Ğ©¡ÿ\0àºş&º\n(Ÿÿ\0„Áÿ\0ô*hø.‡ÿ\0‰£şOÿ\0Ğ©¡ÿ\0àºş&º\n(Ÿÿ\0„Áÿ\0ô*hø.‡ÿ\0‰£şOÿ\0Ğ©¡ÿ\0àºş&º\n(Ÿÿ\0„Áÿ\0ô*hø.‡ÿ\0‰£şOÿ\0Ğ©¡ÿ\0àºş&º\n(Ÿÿ\0„Áÿ\0ô*hø.‡ÿ\0‰£şOÿ\0Ğ©¡ÿ\0àºş&º\n(Ÿÿ\0„Áÿ\0ô*hø.‡ÿ\0‰£şOÿ\0Ğ©¡ÿ\0àºş&º\n(Ÿÿ\0„Áÿ\0ô*hø.‡ÿ\0‰£şOÿ\0Ğ©¡ÿ\0àºş&º\n(Ÿÿ\0„Áÿ\0ô*hø.‡ÿ\0‰£şOÿ\0Ğ©¡ÿ\0àºş&º\n(Ÿÿ\0„Áÿ\0ô*hø.‡ÿ\0‰£şOÿ\0Ğ©¡ÿ\0àºş&º\n(Ÿÿ\0„Áÿ\0ô*hø.‡ÿ\0‰£şOÿ\0Ğ©¡ÿ\0àºş&º\n(Ÿÿ\0„Áÿ\0ô*hø.‡ÿ\0‰£şOÿ\0Ğ©¡ÿ\0àºş&º\n(Ÿÿ\0„Áÿ\0ô*hø.‡ÿ\0‰£şOÿ\0Ğ©¡ÿ\0àºş&º\n(Ÿÿ\0„Áÿ\0ô*hø.‡ÿ\0‰£şOÿ\0Ğ©¡ÿ\0àºş&º\n(Ÿÿ\0„Áÿ\0ô*hø.‡ÿ\0‰£şOÿ\0Ğ©¡ÿ\0àºş&º\n(Ÿğ\'ü“Ïÿ\0Ø*×ÿ\0E-tÏøşIç†ìkÿ\0¢–º\n\0çüwÿ\0$óÄßö\nºÿ\0ÑMGØüaÿ\0Aİÿ\0ÓòU;ÿ\0’yâoû]è¦­»›ˆ¬íf¹ÂC\n`I¤ÚJì»²9Ø‰gºÖxrK‹}¾tI¥H^-Ã+¸}«##‘µ?Øüaÿ\0Aİÿ\0ÓòUpú%Õş›­iÚ½Şcı³çG}s;ÂQä“2[ãdŒãhåxw§,÷Ö<;~u©…û@ÚÍî­4\n¨cb¹™wT¾ÀJI§Ó_/½×zHí¾Çãúèø&›ÿ\0’¨ûŒ?è;¡ÿ\0àšoşJ®.÷X0xoHµÔob’y…ËÁ{ÿ\0	\rÍ¹_¿hU\r;íeÛ•;‚³g×·ğeıÆ¥àöò_2æk(W=YŠŒ“îiÛ/ëô^%ñDòM>#ğô’@Û%TÒe&6À8aö®8=ˆ¨¬%ñ.§Ígâ-XÒW…ö$ãŒU‡7=ˆ\"¹íOT¸M[V†÷S¹¶Ò»7S‹–‹È„Ú«\0$ÔË´ûÇÔÖçÃo#şYE´$?Ú7[¼ŒìËç¾	f$œääÒZ«ÿ\0]?ÌrVş½È¿ö?ĞwCÿ\0Á4ßü•GØüaÿ\0Aİÿ\0ÓòUtP#ŸûŒ?è;¡ÿ\0àšoşJ£ì~0ÿ\0 î‡ÿ\0‚i¿ù*º\n(ŸûŒ?è;¡ÿ\0àšoşJ£ì~0ÿ\0 î‡ÿ\0‚i¿ù*º\n(ŸûŒ?è;¡ÿ\0àšoşJ£ì~0ÿ\0 î‡ÿ\0‚i¿ù*º\n(ŸûŒ?è;¡ÿ\0àšoşJ£ì~0ÿ\0 î‡ÿ\0‚i¿ù*º\n(ƒĞ­|Tuˆµ\\jh%-¤ÊÁ›ì–ü¨ûHÚ6í$ò	Ï8Ÿcñ‡ıt?üMÿ\0ÉTx{şC,ÿ\0°ªé­tÏıÆôĞÿ\0ğM7ÿ\0%Qö?ĞwCÿ\0Á4ßü•]ÏıÆôĞÿ\0ğM7ÿ\0%Qö?ĞwCÿ\0Á4ßü•]ÏıÆôĞÿ\0ğM7ÿ\0%Qö?ĞwCÿ\0Á4ßü•]ÏıÆôĞÿ\0ğM7ÿ\0%Qö?ĞwCÿ\0Á4ßü•]ÏıÆôĞÿ\0ğM7ÿ\0%Qö?ĞwCÿ\0Á4ßü•]ÏıÆôĞÿ\0ğM7ÿ\0%Qö?ĞwCÿ\0Á4ßü•]ÏıÆôĞÿ\0ğM7ÿ\0%Qö?ĞwCÿ\0Á4ßü•]ÏıÆôĞÿ\0ğM7ÿ\0%Qö?ĞwCÿ\0Á4ßü•]ÏıÆôĞÿ\0ğM7ÿ\0%Qö?ĞwCÿ\0Á4ßü•]ÏıÆôĞÿ\0ğM7ÿ\0%Qö?ĞwCÿ\0Á4ßü•]Áø2×ÅMà_5¾³£GÓ-Œi&“+²¯”¸…Èã¾}n}ÆôĞÿ\0ğM7ÿ\0%QàOù\'ÿ\0°U¯şŠZè(ŸûŒ?è;¡ÿ\0àšoşJ£ì~0ÿ\0 î‡ÿ\0‚i¿ù*º\n(ŸûŒ?è;¡ÿ\0àšoşJ£ì~0ÿ\0 î‡ÿ\0‚i¿ù*º\n(ŸûŒ?è;¡ÿ\0àšoşJ£ì~0ÿ\0 î‡ÿ\0‚i¿ù*º\n(ŸûŒ?è;¡ÿ\0àšoşJ£ì~0ÿ\0 î‡ÿ\0‚i¿ù*º\n(ŸûŒ?è;¡ÿ\0àšoşJ£ì~0ÿ\0 î‡ÿ\0‚i¿ù*º\n(ŸûŒ?è;¡ÿ\0àšoşJ£ì~0ÿ\0 î‡ÿ\0‚i¿ù*º\n(ŸûŒ?è;¡ÿ\0àšoşJ£ì~0ÿ\0 î‡ÿ\0‚i¿ù*º\n(ŸûŒ?è;¡ÿ\0àšoşJ£ì~0ÿ\0 î‡ÿ\0‚i¿ù*º\n(ŸûŒ?è;¡ÿ\0àšoşJ£ì~0ÿ\0 î‡ÿ\0‚i¿ù*º\n(ƒğe¯Š›À¾k}gF¦[ÒM&We_)p	Ç|ú\nÜûŒ?è;¡ÿ\0àšoşJ£ÀŸòO<3ÿ\0`«_ıµĞP?ö?ĞwCÿ\0Á4ßü•GØüaÿ\0Aİÿ\0ÓòUtP?ö?ĞwCÿ\0Á4ßü•GØüaÿ\0Aİÿ\0ÓòUtP?ö?ĞwCÿ\0Á4ßü•GØüaÿ\0Aİÿ\0ÓòUtP?ö?ĞwCÿ\0Á4ßü•GØüaÿ\0Aİÿ\0ÓòUtP?ö?ĞwCÿ\0Á4ßü•GØüaÿ\0Aİÿ\0ÓòUtP?ö?ĞwCÿ\0Á4ßü•GØüaÿ\0Aİÿ\0ÓòUtP?ö?ĞwCÿ\0Á4ßü•GØüaÿ\0Aİÿ\0ÓòUtP?ö?ĞwCÿ\0Á4ßü•GØüaÿ\0Aİÿ\0ÓòUtP?ö?ĞwCÿ\0Á4ßü•GØüaÿ\0Aİÿ\0ÓòUtP?ö?ĞwCÿ\0Á4ßü•GØüaÿ\0Aİÿ\0ÓòUtP?ö?ĞwCÿ\0Á4ßü•GØüaÿ\0Aİÿ\0ÓòUtP?ö?ĞwCÿ\0Á4ßü•GØüaÿ\0Aİÿ\0ÓòUtP?ö?ĞwCÿ\0Á4ßü•GØüaÿ\0Aİÿ\0ÓòUtP?ö?ĞwCÿ\0Á4ßü•GØüaÿ\0Aİÿ\0ÓòUtP?ö?ĞwCÿ\0Á4ßü•GØüaÿ\0Aİÿ\0ÓòUtP?ö?ĞwCÿ\0Á4ßü•GØüaÿ\0Aİÿ\0ÓòUtP?àOù\'ÿ\0°U¯şŠZè+Ÿğ\'ü“Ïÿ\0Ø*×ÿ\0E-tÏøïşIç‰¿ìuÿ\0¢š™6¹qÃ7‚u©#pU•¥± Oøø§øïşIç‰¿ìuÿ\0¢š¶®üÏ±Oäº¤¾[lf\\€qÁ ŸÌR“²lq½ô9\r¤ıÿ\0ø‚\\ÿ\0¬ó.­Îôó3sóã¶sÔm\'ïøÄçıg™uhŞw§™›ŸŸ³œv¬¿\rë^\'øvÅu\r>{ëí=¯ÕÕ´¶8Õ×ıö]ËH>|€\0?)ª¶-’ëÆ0\\ÚEµŞ·¤Øˆ^ä3Cˆb¸Ü{*åKã’J7µ¿­É›}jµ¯Îşÿ\0Oó7¶“÷üâ	sş³Ìº´o;ÓÌÍÏÏÙÎ;U	ôk{§§ğg‹\\Ümóÿ\0âqíû¹ğt«—~0Ô“Å2éö¶¯4·pÚMzeÄ¦MáH\'_İÆHÖ§‘‘I¦ø¯Z›R²{±`l/5[­5\"ŠYSÊóv¹räùx+´uÎ{RTâúZ˜<MuößßëşC’=ªªŞñ£şZy×–fôó	¹;ñÛvqÚ¯Yêw¶2CàÍ|î;ä¹³wsêXÜäñôÔÑBŒVÈ™W©5iI´sÿ\0ğêŸô&kŸ÷úËÿ\0’(ÿ\0„‡Tÿ\0¡3\\ÿ\0¿Ö_ü‘]FG?ÿ\0	©ÿ\0Bf¹ÿ\0¬¿ù\"øHuOú5Ïûıeÿ\0ÉĞQ@ÿ\0ü$:§ı	šçış²ÿ\0äŠ?á!Õ?èL×?ïõ—ÿ\0$WAE\0sÿ\0ğêŸô&kŸ÷úËÿ\0’(ÿ\0„‡Tÿ\0¡3\\ÿ\0¿Ö_ü‘]Ïÿ\0ÂCªĞ™®ßë/şH£şSş„Ísşÿ\0YòEtP¡kºŠk\'eğ³!“SFeYlóû%¸ÚÙœàÆFsœ¹ÿ\0	©ÿ\0Bf¹ÿ\0¬¿ù\"ÈsÅŸöOı\"µ¦\\ë3i>,ßPœfÜX›bT)¢\'ÎÇ9VFÏF¤Ú[…‡ÿ\0ÂCªĞ™®ßë/şH£şSş„Ísşÿ\0YòEe·‰õÂºv¡©6ö›ĞÓ3jWÉeA¾t„¬YÂ\0ÛÎÆ$3bÇÆ3j×\ZzéÚb5½ÎŸ£,×>X†\'$€¬YÆ8ÎHã/ggıV—.ÂCªĞ™®ßë/şH£şSş„Ísşÿ\0YòEaj¾%Ö/¼ú¬:i¶³ŸÈÊk{ÜÍ\"£Âº•P…ÕºaÔ;êÇâ›Ø£Ö\"Ô4Ëkkİ5#~>ÎÑÉœ;Jè»@*Û¾S€¼n<QêøHuOú5Ïûıeÿ\0ÉÂCªĞ™®ßë/şH¬‹?ˆ-a¾ÊÂÒòïûHiØ´Ô–ìÍ˜fØ2 `—#\r€p³m¯K±âİQÌ6úU´=¼24ˆ%dóo\0¹;ÑAÀ\'H£ÏúÚÿ\0ª/ë{¿á!Õ?èL×?ïõ—ÿ\0$Qÿ\0	©ÿ\0Bf¹ÿ\0¬¿ù\"¹ˆ<Gâ84Oÿ\0h\\ÆºŒz•½¤\"R¶‚qŸgšyläxâ¬É«jvÚv¦\'¿šæmX…<öÂ5Ä#$H*’VwU8Ï4Òş¾ïÊè:_×C{şSş„Ísşÿ\0YòEğêŸô&kŸ÷úËÿ\0’+ ¢ÿ\0ü$:§ı	šçış²ÿ\0äŠ?á!Õ?èL×?ïõ—ÿ\0$WAE\0sÿ\0ğêŸô&kŸ÷úËÿ\0’(ÿ\0„‡Tÿ\0¡3\\ÿ\0¿Ö_ü‘]Ïÿ\0ÂCªĞ™®ßë/şH£şSş„Ísşÿ\0YòEtP?ÿ\0	©ÿ\0Bf¹ÿ\0¬¿ù\"øHuOú5Ïûıeÿ\0ÉĞQ@ÿ\0ü$:§ı	šçış²ÿ\0äŠ?á!Õ?èL×?ïõ—ÿ\0$WAE\0p~×u|áè“ÂzÌèše²¬±ËfÀ‰~aºppzòõ·?á!Õ?èL×?ïõ—ÿ\0$QàOù\'ÿ\0°U¯şŠZÉÓõ]V?\\Úêš…Ä<Ëga=²¥Ä@•:¡;ñ‚À³‡ù1‚¶—5¿á!Õ?èL×?ïõ—ÿ\0$Qÿ\0	©ÿ\0Bf¹ÿ\0¬¿ù\"±´_ëğØÉckq©ßŞÉkf‰w…“kHI‘¼¡°*¡èœ2qVÛÆ7©Ú*Yu§Il·ºÑùŠâB€”Û´Ÿ”“Á _×Ş/ıZ¿á!Õ?èL×?ïõ—ÿ\0$Qÿ\0	©ÿ\0Bf¹ÿ\0¬¿ù\"°µ/‰PiZœÖw)¥#Ù¼1^FÚ¨Y÷¸RŞLE•T09;	Á\0dV¾¿©ë–~&Ğm4Ø-$µº’E˜Mpc-„\'FØÆ2<4Ğ›şSş„Ísşÿ\0YòEğêŸô&kŸ÷úËÿ\0’*“xÆçÛSJFĞÅÿ\0Ø\rßÚ¿}¿Ìò·ˆ¶cg™òç~qÎ*·†¼N÷:Æ©¤ÇºîKKë—¼–Iğ-\"ŞÂ5îI;N`\0	$pŸõ÷˜5oë×üoøHuOú5Ïûıeÿ\0ÉÂCªĞ™®ßë/şH¬üF´ñ¯kgÓŒw±É%·ÙµuÈDyd®O°F5kGñæ£s¤mlµeqm0ºó:)l:l\0ˆ!‰àdp\0.ÿ\0ÂCªĞ™®ßë/şH£şSş„Ísşÿ\0YòEbİø‹VÒ5ŸÜÇ`o´ë†Y¼Ë²†(ü•-å&ÖÜ@ÜÄƒ¦	$âŞ³ã¨´}nÖÎXì<™å†5WÔUnœJB‡H6’È	ä–Sò·\"ÖŞ`ô/ÿ\0ÂCªĞ™®ßë/şH£şSş„Ísşÿ\0YòEQ‹Ç‰5Énà´·µÒRWš?µ“x («\0J¶óW×…ğ·¢ñ¥6ßÙ†t·[•:v¤·‰´œrv°8ãğN\r]é¹wşSş„Ísşÿ\0YòEğêŸô&kŸ÷úËÿ\0’*“xÆçÛSJFĞÅÿ\0Ø\rßÚ¿}¿Ìò·ˆ¶cg™òç~qÎ)Œo>Ø¬Ú2®š53¦=ÏÚ³ }åÄ{0P¶ĞrÀ‚O\0HµÛúÛüĞ=.ßOø?äËßğêŸô&kŸ÷úËÿ\0’(ÿ\0„‡Tÿ\0¡3\\ÿ\0¿Ö_ü‘]Ïÿ\0ÂCªĞ™®ßë/şH£şSş„Ísşÿ\0YòEtPàÍwQ‡À¾‰<\'¬Î‰¦[*Ë¶a\\—æ§¯ P+sşSş„Ísşÿ\0YòEÿ\0’yáŸûZÿ\0è¥®zçÄ~#[6’ËìÓ¸ñXşş_+yU8¸<‚İG^h[Ûúİ/ÔŠÿ\0ÖÍş‡Cÿ\0	©ÿ\0Bf¹ÿ\0¬¿ù\"øHuOú5Ïûıeÿ\0ÉsPÕÛMŸI{pVúãìîèäˆ\\£0íÈ%vç¢²´ïÿ\0i>8ì6Ïwu$M›˜¢T2	~ïu1|¼`Èxäëúû‹?ğêŸô&kŸ÷úËÿ\0’(ÿ\0„‡Tÿ\0¡3\\ÿ\0¿Ö_ü‘\\tÚö¡ud.’úñ|Í:Ê_šaÍvUÈª¹+Á!FGÖø¾òöÌh­`¯$ÒjI”³yk )\'ßİÎ	àôàI=¼İ¿\r­ZíşW$ÿ\0„‡Tÿ\0¡3\\ÿ\0¿Ö_ü‘Gü$:§ı	šçış²ÿ\0äŠÊ»ñøÓle]FÒÎËSüX®/Â[)æ‡3”_/Ÿ¹œñõnËÆbÿ\0Áº»ki\rÔ¶U’[¡,rù>\\¡~`Wp:à€iô¿õıj.©w-ÂCªĞ™®ßë/şH£şSş„Ísşÿ\0YòEQÕ|[º;Å°ILñÚHo!‘A-4€‘‡Ü!³`œ†ÏwˆQ!¶¶şÍ‚s±oÓT¦v±…¢*ŠØÜ¬K\nğr@MÚ÷éÀi_oêæü$:§ı	šçış²ÿ\0äŠ?á!Õ?èL×?ïõ—ÿ\0$W<\'ÕôëzùlZşÂÍ-çœÍy±£ŒÀŒşZ!›ï1 <`òq%ïŒSAÕ5‡âŞÛÁh··g‚=ÖáØ³v.É cšoGf%ªº7á!Õ?èL×?ïõ—ÿ\0$Qÿ\0	©ÿ\0Bf¹ÿ\0¬¿ù\"¥ğ¿ˆ¢ñ6“%äB\0ÑNğIök<E”õI\0”‚8p@\"°,¼cªË§[¥”u¦‹ù$º¼X²»vå\"Á—Œ(œ‘‘»oıu¯·õĞÚÿ\0„‡Tÿ\0¡3\\ÿ\0¿Ö_ü‘Gü$:§ı	šçış²ÿ\0äŠuÿ\0‰âµğ¥¶¿»MÇÙŠFÍ°âgE8<ùÇ·ãUµ_2ãX‰tæ¸m9-X˜)˜Ìåp20ÇsÏµ\r[pNêèŸşSş„Ísşÿ\0YòEğêŸô&kŸ÷úËÿ\0’*6ïR—ÆRÃ¨*@ßÙ‘ÈÖĞ\\4±+dªäNÑéÎ+¦¡më{[[\\çÿ\0á!Õ?èL×?ïõ—ÿ\0$Qÿ\0	©ÿ\0Bf¹ÿ\0¬¿ù\"¬hZ‹^ZêM$¤A}qe*p¨äqµW8ÎO©=kÃ¿­<A¬ZYÆ4ï.ú9$¶û6¢³Îyh‚,•Éà¶Áæ…®Ş ôüøHuOú5Ïûıeÿ\0ÉÂCªĞ™®ßë/şH®‚Š\0çÿ\0á!Õ?èL×?ïõ—ÿ\0$Qÿ\0	©ÿ\0Bf¹ÿ\0¬¿ù\"º\n(Ÿÿ\0„‡Tÿ\0¡3\\ÿ\0¿Ö_ü‘Gü$:§ı	šçış²ÿ\0äŠè( şSş„Ísşÿ\0YòEğêŸô&kŸ÷úËÿ\0’+ ¢€9ÿ\0øHuOú5Ïûıeÿ\0ÉÂCªĞ™®ßë/şH®‚Š\0çÿ\0á!Õ?èL×?ïõ—ÿ\0$Qÿ\0	©ÿ\0Bf¹ÿ\0¬¿ù\"º\n(Ÿÿ\0„‡Tÿ\0¡3\\ÿ\0¿Ö_ü‘Gü$:§ı	šçış²ÿ\0äŠè( ÀŸòO<3ÿ\0`«_ıµĞW?àOù\'ÿ\0°U¯şŠZè(Ÿñßü“ÏØ*ëÿ\0E5tÏøïşIç‰¿ìuÿ\0¢šº\n\0ç‚4f–¾EÒÃ–Œ%üêb`ª0|¢ÆÅ!xqVgğ¶‰r“¤š|{\'¶ÕÕY”ã$ PÊT’A\\qÏ¶( ƒá)µïŒ3ÑPô©vÉ³î4‰»lŒ;3‚xğ)ñøwJ‡ìş]®>Ïw%ä_¼o–gİ¹ºóíÁã+RŠ\0(¢Š\0(¢Š\0(¢Š\0(¢Š\0(¢Š\0(¢Š\0çü=ÿ\0!ÏØU?ôŠÖªx¯Fÿ\0„‰b³ºÓg’+i„ë,ªù‘à«FrKANÄ*ß‡¿ä9âÏû\n§ş‘Z×AI«—	(½Uşÿ\0Ğãõ}:ÿ\0U½¶½K[û›x¤E’ÖxrÖï´²èÀHv¯\0Ÿ~“FÒ&Ñ>Ïöm*éÄ1Ú(–å`BHÎ\0ıï\'#îÿ\0µ]<¯¿õı7÷š{X\"üÌà¡ğİÜ:ös&¯-‚6öÏwËxUÕÂ.qµF¶ÆMZÕ4iõY¯&—M¼Š[¡nûâ¸÷k—Œ¨*G›¸ò¬\nk³¢Ÿ+îÖÈ¿ó8k?ÜÚÌg’×Qº¸kåÔŞYîbË•Ë\nB ¶ÿ\0\0Ú­=\r®µbMSK‚]7UŠ^	öJ¢DÊáÔñ’¢3Ü§zš(Jİ¯é\"gR2ZE/¿õg-ÃßÚÃªÇef–Ú.’3ZÇf›vùx^\0dƒ‘»\'¾*;ÿ\0ÍŒ4Û\'šîKıJ+Bòå; egb(û±¬`*÷u5ÖÑTdQE\0QE\0QE\0QE\0QE\0QE\0sşÿ\0’yáŸûZÿ\0è¥§§†ê–÷—Z®£y¬­=µ­ÃÆc…È#p!¶0™±Ÿa†xşIç†ìkÿ\0¢–®ÂE¥?í_»ûoØ7ymş¿~Í?½Æz{Ğ·†pğe²Yhµ=F?&í¯,äV}›±mÁ2˜e!Üb@ü¥Mmá;+q=ÍÜ÷+}öùneeß<¡\n\rØP  aB¾^¾/ĞßS]=oÎ×\rkŸ³Éå¬Ã?»2mØ¯Ç\nH\'#ÈËÅº}´L×C›©­£Î®]ü³ó«áøG1È¡y[ÀüöÖÿ\0ğòøpZ}BÇXÔtórë%Ì6şKG3¨¸‰#b¤¨Pv‘Ğwæ¬êº*j“ØÜ»›K‹)Œ±KnS<©VRXAôÏ¡Ëhú›Y+Õ›í«+[FÄ‚&\näc\0‘õíš_hÑÙ[İ‰¥âI\"…-íešGd$>#E/€AÉÆ:sÈ£`Ü«ÿ\0e™»ÜoïÍ‡ÚşÛıš]<;vıÙÙæcÏ·~ÜöÇ4^°ƒPşn#º[‰¦i—2,¤—‰¸æ<àÔ=sbë^µ_Ç¬ZKöÒùF\'weFÊ åUˆ?7M½x8äˆWÅújk§­ã™Úá­söy<µ˜g÷fM»øáIäc9-­¿®Ÿğ? z«ÿ\0_ÖâèşmÇ¶±¨¾Ÿ”†Âo%£‰{(o/Ì!z¹÷Í:×Ã6VhğÇ-Á]%™ ,Ã,J2ÜsÃ˜íY\ZOŒ…åæ½u{*Úi:l¾R‰´ùâ~üÆGÂ±$.ï»×#;ÚV»§ë&u³’a-»–‹y ‘22	I[t8ÁÁô4o¨=Ú35/ÚêW—Ó>¥©C¢P^ÚÅ\"ySª¨P‡(J‚©Rrrza—²¼¾q¨jA=Ô7rÚDÑùO,[6œ”/İ¨Út®¢Š›Ôç¦ğ­õì÷:­íæ¤$‚kdŠãÊE†)q½TÆŠÜ…,Iëœš»¤é7:fDÚæ£¨Ç°*%ÚÁòc¸)\Z±?RkRŠ€õ9¿øC,ÍŞã~l>×ößìÒéäyÛ·îÎÏ3ş}»öç¶8«GÃ6FÑ­¼Û¨\rDÃ>`IŸw#§\\w­ª(ZmımşKî®ÿ\0×õvQE\0QE\0sşÿ\0’yáŸûZÿ\0è¥¥›ÂVri×‘İŞ@Ó_ÿ\0h,ñ²oŠmáò¹R¸ÈèÀği<	ÿ\0$óÃ?ö\nµÿ\0ÑKU´¿\ZÙ\\_Ïa}(Šèj3YÅ²ŞO,•c±ZLÚX‘È¡|^ğWë`{k·üÿ\0ÖÖtH5Í´Ë»‹…VØÂâ\"«*º0`àã\0äzcÚ¡³ğÍ…†¸ú´Q+[­¸Œ°1¢€ 1œˆ?À=ó‡?Ä{8t½jôiº‰:læ†Êà	8–ò°½O8À\'¨­{Ÿè¶~ZÏ-ÚÊğ‹\0°¦äæ0›Ô9$dg<ûÿ\0—ù—oóÿ\02ºxLKD·İìKxmÁŞ¹Û¾jŸ»×wÛó­=kD‡\\‚Ú9nnmÚqq¶Î•À A|ÙÁ=FAt:î™q¨ÛØCv²\\ÜÚı²E$<9xlcaß<ÖU½*Ùx‡\\»›v™k,‹m@0®sÔ–pøö‹Ó_ÇüÆµ~¿åşC‡ƒ-¦ßí\rCíÿ\0kûoö–øüÿ\0;nÍØÙåãgÉ·fÜvÏ5³ai-¥§‘qq~Ù9šåcAíˆÑW…sÍâaáıÚ=ví¥ÖM™¸›e«º¡õ-q\Zvîb\rKoãM>K»Õ$u»»±KÉ\"´µ–rŠTb±«Lœe¸÷§İ_Ö‚½íı[±ğF—§ø~m\Zn¼×;;¸.\n•* ã@DP1Ğ~5$ş†öîæçPÔïï[y­¢T[Ç.7„Ù\Z“À,[§×2_øÃBÓvù÷¬å­ÅÒ‹h$œ˜NyˆÕ¾N9nƒ#8ÈË¬ü]¡_É:Á~¸‚rÒIÇB:ÈÀ+ şò’=èjûÿ\0]›Z¢×lîèiêq[Ş,iym‰åÜ\"(P§(J‚	B¤ääôÅ«ÏÙ]\\MsÍİ­ÓÜGsğ2†Ò?,l¥H)CŸl5ü_§Icu5£7›lñ,ŞÁ=«(‘Â«a£İƒÎR	Èä‰WÅúêk§­ã™Úá­söy<µ˜g÷fM»øáIäc97bZ#JÂÒk;Qú…ÍóäŸ:åc}¿vŠ¿¥fé¾±Ò‚ùÜ6Û!d7²Ÿ36xæËnœTWÚ¼°øŸÉûO“§iÖy¨~ïvíÄˆÇBFHÜsÀ©m¼_¡İ‹†ŠîM°[›¦g¶•ádŒ²\"û¦áÈõ¯úİ˜ÕÖŸ×GşE]gDH¼‡­ÕİœQG0Ê«p1±Ó )`Ê§œø=+4=Aû_Øj×³İ\rìæêgòœ´~Hªz€Ã\0däŸşìÜ¬÷2G;”€Gc;¼ø‹FH˜ ïPWŞ²õ\Z©›A‡D”:ê’ö–Ó®.QU²6¦Ü>á‚	0K)II»Ü¨N¹Sûÿ\0Ì³êk¯>§6‡0˜[-¼â;„deY|¬€XåíÅqÛ5{MP]Vö{ˆ®ÏU,’]yFT`F23Õ³zf³ìüe“´×kºj^™\".ÿ\03Hé€J«6vŒ\r€’qÏµ¦kš~¯\rÄ–’È>Îû&Iàx$Œà7Ì’*°à‚	ŠJ--ûşæLõÖ›iòÿ\0#ÛN½´»Iâ²ÔEÄò<írË/]êÍşûÃû¦›£éÚ®ˆÑÅmı¨ú|*`†Âi­Ú(l7—æ— bå½TÔÚW‰Ò\rßQÕî&vÔæ’k+x-Y’€$JX€›Ib8-ÉéV5h¶ztw1]™Lö†êŠŞITGBª|µÏr£ƒÏN+}¿«|ÕX7ğ-}Ìè†p3ŒãœR×7¾ÁáÈ5+»EšfÒà½h­–VmÒqŒØ*p~bÇĞ„ëkz¼òišriRKou©İG/-¹GyyÇ\"‚D¼:â´jÎß/ĞæM5Ÿêt4W3§xßM½W–â;›(4Éš9%¸¶–5eprÈâ[[§¨¤¾ñ…³h×wZYf¹µšå‚òÚX«’°C`‘ß—˜ÎŠÃ›Åú$ ‘s4ÄNÖám­eÔeö„RX/BËƒÅ:h°ZØÜ›Æ‘/ã2Z ’W@íDRÄ€A#À\'\0mQUtíFÓVÓà¿±˜Mk:ïÀ##ÜAìAä\rZ Š( Š( Š( ÀŸòO<3ÿ\0`«_ıµĞW?àOù\'ÿ\0°U¯şŠZè(Ÿñßü“ÏØ*ëÿ\0E5tÏøïşIç‰¿ìuÿ\0¢šøGµOúõÏûóeÿ\0ÈôĞQ\\,wQM¨}Š/xGó¼94ëvƒÌé³Î^ìñİxëÅmÂ=ªĞç®ß›/şG£¥ÃÈè(®şíSş‡=sşüÙò=UM>æMRm5|q®¸bI¤ìö|#\nsölrU»ö ªŠçÿ\0áÕ?ès×?ïÍ—ÿ\0#ÕKÍB÷R´‡Æ!Y4ùÄ—‚ÄbŠÿ\0/ú?L8ës@}Ïÿ\0Â=ªĞç®ß›/şGª©§ÜÉªM¦¯5ÃwI4‘ıÏ„bÁN~ÍJ·~ÔÕQ\\ÿ\0ü#Ú§ızçıù²ÿ\0äz?áÕ?ès×?ïÍ—ÿ\0#ĞAEsÿ\0ğjŸô9ëŸ÷æËÿ\0‘èÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_ü@Ïÿ\0Â=ªĞç®ß›/şG£şíSş‡=sşüÙò=\0tW?ÿ\0ö©ÿ\0C¹ÿ\0~l¿ùøGµOúõÏûóeÿ\0Èô\0x{şC,ÿ\0°ªé­tÁèZ¢úÇ‰Õ|Y¬ÆcÔÑY–+<È~Énw6`#8 q…g$ÙÔ¾2Cª|L¼±•×r¥ÔštLÃ¦@hE\0vtW;ƒ¨Ë\ZÉu·G•–+=?g¦ÿ\0c^‹‘mÿ\0	Æ³ç”2¼»ÅAÁl}Ÿ8É4ÒQ\\äºü¼ÓxÛZ(Ô³»Çb@ä’M¿±¡Ôô‹‡ÙÅ—‘Â–Ú—:i8\0’xƒ \0“ì(¼¢¹ØôFXÖHük­º8¬±XAèAû=;şíSş‡=sşüÙò=\0tW?ÿ\0ö©ÿ\0C¹ÿ\0~l¿ùøGµOúõÏûóeÿ\0ÈôĞQ\\ÿ\0ü#Ú§ızçıù²ÿ\0äz?áÕ?ès×?ïÍ—ÿ\0#ĞAEsÿ\0ğjŸô9ëŸ÷æËÿ\0‘èÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_ü@Ïÿ\0Â=ªĞç®ß›/şG£şíSş‡=sşüÙò=\0tW?ÿ\0ö©ÿ\0C¹ÿ\0~l¿ùøGµOúõÏûóeÿ\0ÈôĞQ\\ÿ\0ü#Ú§ızçıù²ÿ\0äz?áÕ?ès×?ïÍ—ÿ\0#ĞAEsÿ\0ğjŸô9ëŸ÷æËÿ\0‘èÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_ü@?äxgşÁV¿ú)k\rü)¯	”M¦ÿ\0flj¢v•üæS(‘£Ù³h –Ãn9ÀÈ<¡j3xÃÒ§‹5˜ôËfX£ŠÌªü£tàtä“êM]¹Š;=F:ëâ=ôÓcÊ¶•´õ’Lœ\rª`ÉÉãŠ’OúïúÖ-]¿RFğÅãi­oæ[ï:ØÔA,p#‰1Óïmzg½U‹Ã:Ö›¬&­aö‰Ä÷¡ wLSÈ®`†Rƒ#nO#«ÿ\0ö©ÿ\0C¹ÿ\0~l¿ùøGµOúõÏûóeÿ\0Èô--oëD¿$_ë×üÎSÃ:V¯& êúp±¸»±›P†Xn$x#q$Í–VUrdx\'š˜xV†ËI“Î†æòÏíi4qj6	\"Í/˜d‹,ÀùH äóÀ\'¡›F½·gñÆ³w¡xìWsŒÛòO¥Iÿ\0ö©ÿ\0C¹ÿ\0~l¿ùëúûÀ†oJş\n‹F¶‚IHÜÆn¤‰„ûÆ]ÍœHêj&ğÅãi­oæ[ï:ØÔA,p#‰1Óïmzg½;QÓ®´­>{ûßë‘[@»ä³Ù¶ÑôÄÕ‘áíLŒëŸ÷æËÿ\0‘èû\\ßÖéşöy­­ú”îü/{>™«Eöëq>ªš•®âÛ	C*¾FLx8ÎÏ=*î¦jC]¾ÖµT´‚ââ­’ŞÒV•Q»n.È„’\\ñ´`ÖªiúuÎ«f.ì¼q®Kw@ßg³^UŠ·Ø\"¦şÆ½\"ÛşgÏ(dyv;Šƒ‚Øû>q’hZ+[[òAßúëÌé(®şíSş‡=sşüÙò=ğjŸô9ëŸ÷æËÿ\0‘è ¢¹ÿ\0øGµOúõÏûóeÿ\0ÈôÂ=ªĞç®ß›/şG ‚Šçÿ\0áÕ?ès×?ïÍ—ÿ\0#Ñÿ\0ö©ÿ\0C¹ÿ\0~l¿ù€:\n+Ÿÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_üGü#Ú§ızçıù²ÿ\0äz\0è(®şíSş‡=sşüÙò=ğjŸô9ëŸ÷æËÿ\0‘è\0ğ\'ü“Ïÿ\0Ø*×ÿ\0E-To^6šÖşe¾ó­DÇ18“>öĞG¦{Öwƒ4-FoøzTñf³>™lËqY•@b_”n€œœ’}I­ÏøGµOúõÏûóeÿ\0Èô-ÿ\0­Óıê­ıl×êgİøkVŸOñFœ†ÈC©H×³[pr¨6:mÀ_¼ğzU[—ñxÎCei¥›×Ñ£Y¡šêO-	–LG–Ğ ÎzŒsµÿ\0ö©ÿ\0C¹ÿ\0~l¿ùøGµOúõÏûóeÿ\0Èô­µú“AßÏüîPk=wÃ\Z†ÃM[{Ëk=/ìñíşĞ÷\\*TGÔãéšuî…äéğÍ¼2=ŠJu&ß.¿æ>¯ N\\µ]ÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_üGü#Ú§ızçıù²ÿ\0äz«ëë¸t±[VÑu†×/¯t£bñê6)g7Ú¤u0/µÔ*üHr¤§AósÅ/kúÛÉ¦®™u3é6ö	sq$kÂ„#o{eH^ƒx×ÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_üGü#Ú§ızçıù²ÿ\0äz]-ıuÿ\00¾·şº’)iŸKŒAÄrÄš,zj;¬Î¥¾b0@0îj<-¬ Ód²½¶·¹³ĞeÓ–n[líåmp1ÊƒëÏN+OşíSş‡=sşüÙò=ğjŸô9ëŸ÷æËÿ\0‘èş¿?óaÖÿ\0×OòG3màmg~©,ÏMwœh’jw¤f.ÄÉ*ƒÈ<\01ŸÎ¶ßÃ¦µ¿™o¼ëcQqÀŒN$ÇO½´éõoşíSş‡=sşüÙò=ğjŸô9ëŸ÷æËÿ\0‘èëëtÿ\0A[K[[ò2M¬£Á^)Õ.¡’­J;©™$R¬±ª‰H=>E¬j¼?®kVi=ùÓ¡1è²ØÚù;y­2®]òƒËbü ¿SÉÇ;ŸğjŸô9ëŸ÷æËÿ\0‘èÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_üJÊÍy%ø5ù2®îšş¶ÿ\0\"ÍU·¾Ò5=1lî.l­Î[{™Ş$u}‡rº£AŒpWO#™ákÛ)4ieİä·»»»ºÙ»§J§€_8Èö«¿ğjŸô9ëŸ÷æËÿ\0‘èÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_üTİ÷şº“m9©~j3iÚµÕºË´Hc–EÌ°ÎeÆåÃ*áÈÎ@ã›èÖÚõ˜ÓZ½^hm%–-VçPa	$;3Ì  T/Œq’+wşíSş‡=sşüÙò=ğjŸô9ëŸ÷æËÿ\0‘é-ºÁ¸Û»¿_ø(ø—Â·÷Ú]îšû2[oíìAGØAÃ–(>R9íTàğ¦¯¦²môèc½ÓÖÒâ)ïf›ìÌ\ZFŞStÙó[!¶¼Ö×ü#Ú§ızçıù²ÿ\0äz?áÕ?ès×?ïÍ—ÿ\0#ĞõVcNÛà­IôG³¢S Á§)ÜÅ|èÉ9<}Ã‘Ï_jÖ±·¾Ô<[öíJÍ`şÏ³DÙã2Ês!F*»€UŒgï0©áÕ?ès×?ïÍ—ÿ\0#Ñÿ\0ö©ÿ\0C¹ÿ\0~l¿ùîïëøßüÉI%oëK‘™wáZhõ›xŞÈG6£§g+Jù2!¼¹/\nLxÜğs·ŒTZ‡…5mv=ZãQûµÍôv¶ëµÄ‰R™	2ìVÜw60£÷ğjŸô9ëŸ÷æËÿ\0‘èÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_üK¥¿®ßÿ\0¯×óÔææğ6­šzÛË\rÍ¶–’ZÚF5+‹{wØ@y!Ü…ô!Ç\'s½§xr[;Xà‚ÖnXêIÈiJŸ–GPÏÈ$–Áæ¦ÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_üGü#Ú§ızçıù²ÿ\0äz\0µá½.mGs´eÅÄò,»^Wp9³\nÖ®şíSş‡=sşüÙò=ğjŸô9ëŸ÷æËÿ\0‘è ¢¹ÿ\0øGµOúõÏûóeÿ\0ÈôÂ=ªĞç®ß›/şG ‚Šçÿ\0áÕ?ès×?ïÍ—ÿ\0#Ñÿ\0ö©ÿ\0C¹ÿ\0~l¿ù€:\n+Ÿÿ\0„{Tÿ\0¡Ï\\ÿ\0¿6_üGü#Ú§ızçıù²ÿ\0äz\0<	ÿ\0$óÃ?ö\nµÿ\0ÑK]sşÿ\0’yáŸûZÿ\0è¥®‚€9ÿ\0ÿ\0É<ñ7ı‚®¿ôSVİÄ‰´$«*’Ò1ÀQëšÄñßü“ÏØ*ëÿ\0E5ïÅät-ƒÔf_şE¤ÕÕ†´f‡|Ai¡ø~_Áw£§¢Û\\læ’™±å…aórÛ¹Æ7q\\ñÒíôß‡\Z\Z%µ»DÖï®Åqdnƒóá¯ S\nO\\We¦¿“åøWÃkänò±ªËònëô^ôGi¯Ãäù~ğÚù¼¬j²ü›ºãı½/}ïÖß‡õıtß÷¿áıÃõ¶¼~£ö{_\réÚeí®ŸwoFº¾Ğd¸ß1D-ÕËÀ[å.Ã\n€…­ †¹¼µ	¯?†í¡·¼šØ™-®ÂÈ?y.’äœ®xäâ»µ]^ŞæÖÙ|9áxî]íâşØ0^7•eÎ>a“î*Hí5ø|Ÿ/Â¾_#w•V_“w\\¢÷£Şwóÿ\0ƒş€/`»şÓı?-x«{e¶ğŞºÖÏV¸²–úKéÀ “2-İ™İã;I\r€cšéü.•§\\ø¡­­£Ò´ñÅ–ÿ\0e\n`Œ¨,P9«ÑÚkğù>_…|6¾Fï+\Z¬¿&î¸ÿ\0EïT¯tÛ©ÒÛPğ‡ƒİKí¢¸ÔØ‚Ç,UCZòx\'Ó=©Şwo¿üòş®+P·_Ãúÿ\0‡òÖ/ˆw–R½½¥Å™sHí5ÕŞ–Úˆ¶s´\"yhCF_9H.9È¬o[ÙZø‡M¼×,jGF²ˆ\\MfÒIÀŞHûO”åJ¹¸\0œWKi¤jº{+Yø3ÂÖì¤°1jN§$`-zãŠ+]~‹ÂŞCo»Ê+«J6g®?Ñ{Ò2ş½Ïğì\Z¶¿‡—ù~>Zõ”W?öÏĞCÿ\0ÁÌßü‹GÛ<aÿ\0@-ÿ\03ò-QĞQ\\ÿ\0Û<aÿ\0@-ÿ\03ò-lñ‡ı\0´?üÍÿ\0È´ĞQ\\ÿ\0Û<aÿ\0@-ÿ\03ò-lñ‡ı\0´?üÍÿ\0È´ĞQ\\ÿ\0Û<aÿ\0@-ÿ\03ò-lñ‡ı\0´?üÍÿ\0È´\0x{şC,ÿ\0°ªé­ej:Î“£|KwÕµ++äÑÕU®çXÃŸ9²â3PhW^*\ZÇ‰ÌZ6ŒÎu42†Õ¥P­öK~ı˜îvœ9$cŒ›w¾1áä[-:%\'zÇ».NÍÿ\0½?Ù?)ô©wºkúÑ¢à£+©;Ã¦rs_G¦h–¶dMaeus{=„w\Zœš\\Kñ°nDß¼î%\"àbHàbÖ4WZß…µmRúuÔo´ Ñ™/¤.\'?”&à¬H9+z‘[¿`ñŸüûÙşC²ÿ\0¬şÿ\0üzôÿ\0cî{Qöÿ\0Ï½—÷ÿ\0ä;/úÏïÿ\0Ç¯Oö>çµ+Ë·õf¾ıGN“ûƒïsÀš–¡}­XIs«ÙÇ†_í+×%vòGÙ\0€£aH\09àÖ±–H&ø42<rÆ‘Ñ°ÊEšA\rMöÿ\0Ï½—÷ÿ\0ä;/úÏïÿ\0Ç¯Oö>çµ`ñŸüûÙşC²ÿ\0¬şÿ\0üzôÿ\0cî{Q\'&š·K0¤¥~Á˜š“_O¦ø‡P\ZÆ©útv²ZyWl©cf%z>OPá—¯\'.Ö®5=	üM§é··³A–¹¼‘Ş’GIØHC².ÄÎ@;9`+gì3ÿ\0Ÿ{/ïÿ\0Èv_õŸßÿ\0^Ÿì}Ïj>Áã?ù÷²şÿ\0ü‡eÿ\0Yıÿ\0øõéşÇÜö¦äÛØQ§M+sş—ÀWNš‘]VÂöÏÍCvºÃêF·\ZWPØ8œdöâ»\Zã­í|cÂHÖZtªöMv\\4Ûş=z²>QéZ?lñ‡ı\0´?üÍÿ\0ÈµWos)F1v‹¹ĞQ\\ÿ\0Û<aÿ\0@-ÿ\03ò-lñ‡ı\0´?üÍÿ\0È´tW?öÏĞCÿ\0ÁÌßü‹GÛ<aÿ\0@-ÿ\03ò-\0tW?öÏĞCÿ\0ÁÌßü‹GÛ<aÿ\0@-ÿ\03ò-\0tW?öÏĞCÿ\0ÁÌßü‹GÛ<aÿ\0@-ÿ\03ò-\0tW?öÏĞCÿ\0ÁÌßü‹GÛ<aÿ\0@-ÿ\03ò-\0tW?öÏĞCÿ\0ÁÌßü‹GÛ<aÿ\0@-ÿ\03ò-\0ÿ\0’yáŸûZÿ\0è¥¬-VÑ4ÔÕ4­zâÒ-^}JvšÚãK Ò%‘Í\"ìòÀÀ8Û1KàË¯/|<¶ú6$L¶¼š´¨Ì¾Rà•ÄÙ8õ5¹öÏĞCÿ\0ÁÌßü‹BÜÖ9;ù5(<5â\r^Şöş[¥Ô¥¶;ï%Xíí~Ğí\nM«¸ù›K(Î\0¶z»ZxW[º:µµİŸD4ı}ï\r¹bªÂK§MÑFx%¹*#Œußlñ‡ı\0´?üÍÿ\0È´}³ÆôĞÿ\0ğs7ÿ\0\"ÒZ+z~Ÿ×Ìmë3ÎæOC¸—QÔdşÎÓ¼Al©<\ZÕÄÑE‹´Œê‰ßw<•WÔï›Å7–ë¬ØØ<RÛÿ\0e½î¿-²ÉÔmÂ….6õ,ÌIéÆ®ãí0ÿ\0 ‡ÿ\0ƒ™¿ù¶xÃş€Zşfÿ\0äZ¤íıy%ú~,–¯ıy·úœ\'®íæÅjZ¥İ¾¡EÓì£¹uYmü´%„#å2r§nŞ«·5Ôø²îŞ-oG¶Õµ)´İ\ZXgf;Çµp6yhÒ!S÷L„.y+Ğâ´¾Ùãúhø9›ÿ\0‘hûgŒ?è¡ÿ\0àæoşE¥Ñ_×Qõ¹ç–ú¤v~Ñ­%¹34—Î²\\ê’iˆÅg`7<i¼Ëóq\0˜‘1¡ MÖ·ám[T¾uí4fKé#K‰Áå	¸+Jã¤WgöÏĞCÿ\0ÁÌßü‹GÛ<aÿ\0@-ÿ\03ò-×à×ëøK[§úo5-BûZ°’çW²¿ÚV/®K<í!ä²:FÂ\0$sÁ¯R®í0ÿ\0 ‡ÿ\0ƒ™¿ù¶xÃş€Zşfÿ\0äZm‡S ¢¹ÿ\0¶xÃş€Zşfÿ\0äZ>Ùãúhø9›ÿ\0‘iĞQ\\ÿ\0Û<aÿ\0@-ÿ\03ò-lñ‡ı\0´?üÍÿ\0È´ĞQ\\ÿ\0Û<aÿ\0@-ÿ\03ò-lñ‡ı\0´?üÍÿ\0È´ĞQ\\ÿ\0Û<aÿ\0@-ÿ\03ò-lñ‡ı\0´?üÍÿ\0È´\0xşIç†ìkÿ\0¢–º\nàüuâ¥ğ/‡–ßFÑ¤€i–Â7“V•—Ê\\¢Ø€qÛ\'¦·>Ùãúhø9›ÿ\0‘h ¢¹ÿ\0¶xÃş€Zşfÿ\0äZ>Ùãúhø9›ÿ\0‘h ¢¹ÿ\0¶xÃş€Zşfÿ\0äZ>Ùãúhø9›ÿ\0‘h ¢¹ÿ\0¶xÃş€Zşfÿ\0äZ>Ùãúhø9›ÿ\0‘h ¢¹ÿ\0¶xÃş€Zşfÿ\0äZ>Ùãúhø9›ÿ\0‘h ¢¹ÿ\0¶xÃş€Zşfÿ\0äZ>Ùãúhø9›ÿ\0‘h ¢¹ÿ\0¶xÃş€Zşfÿ\0äZ>Ùãúhø9›ÿ\0‘h ¢¹ÿ\0¶xÃş€Zşfÿ\0äZ>Ùãúhø9›ÿ\0‘h ¢¹ÿ\0¶xÃş€Zşfÿ\0äZ>Ùãúhø9›ÿ\0‘h ¢¹ÿ\0¶xÃş€Zşfÿ\0äZ>Ùãúhø9›ÿ\0‘h ¢¹ÿ\0¶xÃş€Zşfÿ\0äZ>Ùãúhø9›ÿ\0‘h ¢¹ÿ\0¶xÃş€Zşfÿ\0äZ>Ùãúhø9›ÿ\0‘h ¢¹ÿ\0¶xÃş€Zşfÿ\0äZ>Ùãúhø9›ÿ\0‘h ¢¹ÿ\0¶xÃş€Zşfÿ\0äZ>Ùãúhø9›ÿ\0‘h ¢¹ÿ\0¶xÃş€Zşfÿ\0äZ>Ùãúhø9›ÿ\0‘h ¢¹ÿ\0¶xÃş€Zşfÿ\0äZ>Ùãúhø9›ÿ\0‘h ¢¹ÿ\0¶xÃş€Zşfÿ\0äZ>Ùãúhø9›ÿ\0‘h\0ğ\'ü“Ïÿ\0Ø*×ÿ\0E-tÏøşIç†ìkÿ\0¢–º\n\0çüwÿ\0$óÄßö\nºÿ\0ÑM[³)h$P2J?*Âñßü“ÏØ*ëÿ\0E52ox:x^&ñfŠÆ	MN%#èCd\ZRWM;œ¼cÂ?[)s>Ÿ–Í½\'MëUÚÙ¶œô©´Ís\\şÑ°º›U{˜®õ{Û±0FbÎ*ÊUwnZ‚I ƒÓ<Ó’çÀ	©É©ŸèßÚ®Û›¤¿·IfFõ ¨tŒàg5KFƒÀ\ZEÅÅÚøËEkÛ©e{›¨¯`ŠIUÜ¸RÁ·gi\0ã‘Œ\n¤ÛvŞïòÿ\0/ë®î+[Ÿğ~×İİÚ=^OímÄZ¾·9èW·r*G,†è‰€›ŞŸ>şWëUu}CZÔ ¿Ò/.uxVô»˜õ,şĞ¾eÎÓş¤Ûò©È9Èâ¶¡ÿ\0…sÄ×Å:šçÚäòÚ6Ÿw\\•Á\\àgn7cæÍGmÃK[ií¢ñ\'‡ã·º]—‘CumÜ(û …À\0g¢ã=óIIé¦ßæßôÁÓ¥üÿ\0ƒòÿ\0ƒ÷¯;O&³â8üK=¥¼š…Ä\Zuåµ¬!²HeÂys¶MçyÛå€¤ªŒš­%åæ©â]şïR“hñÍ´zxHÄq,qÌ³·yb\0c–ÇÏÓ¥Køp—·ğ”h^}²yi*^[#\"íXÊàÄO	·99Îh˜ü?òKæñšŒÜË}íªÎÌª°oá*8Æ{æ…)+iıiıy~c§JÍsş³ş¼ôîíİê÷°iº5ííÕÃÛA/$“\"†hÀx$}Ò¼¯WÔ5­Bı\"òçW…aŸK¹‰ïRÏíæ\\í?êAM¿*‘‘œƒœ+tj’IßÄø®¡¸\0Oo&¥h¨à`mPTø<÷¨-¡øikm=´^$ğüv÷K²ò(n­¢[…t¸\0ô\\g¾hNW½ƒÙÒµ¹ÿ\0ıwûÖÚÛÑ­¡x-cŠK™n]\r4¡C¹õ;@\\ı\0-rÚ‹¼§iğYAâ¤P DXï`E\0z*à\0«?ğø?ş†½ÿ\00ÿ\0ñUG=­¡ĞQ\\ÿ\0ü\'~ÿ\0¡¯Cÿ\0ÁŒ?üUğø?ş†½ÿ\00ÿ\0ñTĞQ\\ÿ\0ü\'~ÿ\0¡¯Cÿ\0ÁŒ?üUğø?ş†½ÿ\00ÿ\0ñTĞQ\\ÿ\0ü\'~ÿ\0¡¯Cÿ\0ÁŒ?üUğø?ş†½ÿ\00ÿ\0ñT\0x{şC,ÿ\0°ªé­gê6CWø‚Ö77šŒvÑéK*Çk5¸Şea¸ùn¹8õÍSĞ¼iáXu¼¾%Ñ‘&ÔÑâf¿ˆ_²[®WæänVÁª–µªøSTÕÍèñF†²$Ah|@öÌÑƒŸ(ˆ¥QÔ“¼çû¦¥èÓş¶eÂ<×W·ü:%‹Äú–¶«©Ã-ÌwVéq5„×³\\$-€|¨6øi2\0 pKq.®kZÏˆt}H]ÁŸu¢}²K/!Ü™7\0|À7dğÛx`ç5›=ÿ\0„§‚Ò­øVŞ;HØB-5Ÿ³íˆ-1È¤‚@%A#%)ğê¾¶m5­õÿ\0ÂtØ¶ZùZÑ]±ñû†ÄŸ8ùGÌÙ}Ê9ºÿ\0[5şLÑĞÒÜËïóOş¡á¿ê#º³Iíe:~¥o#şëM»¶6€É›†ù$ÊänM¼àŒ•!±K+Ÿ\ZÌ·º¼£J_ôXæÖ.ÙTUróyù˜œõˆÀÄzv¯á½&çÏ±ñ/‡ás¬Ä`@NLK	—Ë_g\n\0ì¢§oxy¿´³¯øPÿ\0i÷ñ5¼y_ƒ´¼cıÜÒ”®Ÿ*èThÙë%oRkÏx‚ŞËSº²M7ìºJ[³G:ÊÒ\\—~ïŒœ1œò8É’ûÆ\Z¾ˆ5›]B;;›ËQhmä´¶—gúC˜Ô4a™Ûk)?)Ë\0ª/­øeí¯-›]ğ¡ŠõQ®ûX|áT*Å÷ø (Æ?İ¢ï[ğÍü—²]k•ïbn	ÕWæD,R!óü¬¥‰Œpvğ)Êwz\"#A¥¬—ŞtşÖuTßE¨Bÿ\0èî¾U×ötöK2²äâ9²À©	¥tuÀh&ğş—!‹şİÁ#yÓëÍrëÆcÍ‘¾»\0ÿ\0w½oÿ\0Âwàÿ\0ú\Zô?üÃÿ\0ÅU^ær‡#µÓô:\n+Ÿÿ\0„ïÁÿ\0ô5èø1‡ÿ\0Š£ş¿ÿ\0Ğ×¡ÿ\0àÆş*‚N‚Šçÿ\0á;ğı\rzşaÿ\0â¨ÿ\0„ïÁÿ\0ô5èø1‡ÿ\0Š ‚Šçÿ\0á;ğı\rzşaÿ\0â¨ÿ\0„ïÁÿ\0ô5èø1‡ÿ\0Š ‚Šçÿ\0á;ğı\rzşaÿ\0â¨ÿ\0„ïÁÿ\0ô5èø1‡ÿ\0Š ‚Šçÿ\0á;ğı\rzşaÿ\0â¨ÿ\0„ïÁÿ\0ô5èø1‡ÿ\0Š ‚Šçÿ\0á;ğı\rzşaÿ\0â¨ÿ\0„ïÁÿ\0ô5èø1‡ÿ\0Š ÀŸòO<3ÿ\0`«_ıµ‘o¨]Â!ãŞîo6ÚæüG#JwDIP~P8#Ò¢ğg<+kà_[Üx—F†x´Ëd’9/âVF( ‚ÙÔ·w¿\rooå½›Ä:?™9V4Ö‚E9^†H–@’p\0ù”ä\0¥«ßÍ[ò\Zv·“¿æ\\mW^ú7L—Oˆ¦•ã\\^Dò–bYvá]zã;³Æ:ñŞ\'ÕüI¦?öRY[ Ñ£¾¹K¨ŞC!”>#FVqå·ÌCu/ZƒS¼ğ>³âY/ï¼U¢›cb¶ª Ö„g{ÆêJFFpqÈàUËÛï†—ïM®èJ!ƒìÁ!Õ–$h{Fê§û,äñÉ§/z/Îÿ\0¯ü\0º×•¿Oø&T.¼ğï‚tv³–Ù¶‹Ô–æÆâåä:3G…NÜ}Àœğ6œésVÒõj·7q\\é¶61]%’Àá°QØo0€xùŞxàb£ºo…×°$kÚ@…mVÌÇ¹å¬¨!QÂÊ7“ÙÆM]}gáÜ—ò^?ˆ´F’[qk*Y<¹b^=û†#,	æªo™¶ºÜ˜«r®ßğ?à•¬üiªÛØê·Z•£ÜÃg§Åš=&êÅw®wEûìîìC/lä\n“M\ZÀø‘iı±5ŒÒ¶++ÙÂñ™cÊ•flàÿ\0yÏA]cª|<°·šŞ?éÒÛÍ’ğ]ëÆâ=˜ÆĞ’JÀqÀqMÒõ‡\Z=âİÙøHûJÂ`Y¦Ö„Î#$™y	Ú\nŒƒœc\'\"·5ÿ\0­Ÿù wq·õÓüŸŞgë:¯¬Ca|ZÉt¡â­ãƒÊq:ùw7—ÜTåü»F1Æ\rù<aÿ\0	U¾›ö’ÛİİKf›4û‚°º£°cpHS”ÃF¸ ’7¤–=ÏÃ)/ÍëkÚ)”Ü‹°¿Û#ËYÌXüÍŠÄH\0œœç\'$W\r ÔãÔ#ñ\'Šw¸‰N·˜£‘·neŒÉ±IÜÙÀÍ(è¬ÿ\0­ù1Ëv×õ¿ù¢•‡‰5Í\'Â\ZsİŞGyy¨jÛÃ2éóÎaUiY‹FÏ\'@\0®à]…õ[í[Jyµg†xçx·µ¬¶Âeåu_ApsÈ<šççÃSi-¯ü$ZH†Kµ\0ºŞ2äÑ&b9\'îmê}Miiş+ğN›j-àñv˜è	9¹Ö„ïÏûRHÍøf…Öÿ\0ÖÀú[úş´:ª+Ÿÿ\0„ïÁÿ\0ô5èø1‡ÿ\0Š£ş¿ÿ\0Ğ×¡ÿ\0àÆş*€:\n+Ÿÿ\0„ïÁÿ\0ô5èø1‡ÿ\0Š£ş¿ÿ\0Ğ×¡ÿ\0àÆş*€:\n+Ÿÿ\0„ïÁÿ\0ô5èø1‡ÿ\0Š£ş¿ÿ\0Ğ×','2015-11-17 10:45:35','2015-11-17 10:45:35');

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
