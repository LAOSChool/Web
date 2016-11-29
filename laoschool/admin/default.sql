/*
SQLyog Ultimate v9.51 
MySQL - 5.0.77-log : Database - hmong_callcenter
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`hmong_callcenter` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hmong_callcenter`;

/*Table structure for table `cps` */

DROP TABLE IF EXISTS `cps`;

CREATE TABLE `cps` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(20) default NULL,
  `short_code` varchar(20) default NULL,
  `pbx` varchar(20) NOT NULL,
  `address` varchar(250) default NULL,
  `create_date` timestamp NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

/*Table structure for table `rp_revenue_sumary` */

DROP TABLE IF EXISTS `rp_revenue_sumary`;

CREATE TABLE `rp_revenue_sumary` (
  `id` int(11) NOT NULL auto_increment,
  `hour` int(2) default NULL,
  `date` date NOT NULL,
  `method` varchar(220) collate utf8_unicode_ci default NULL,
  `total_call` int(11) NOT NULL default '0',
  `total_duration` int(11) NOT NULL default '0',
  `total_block` int(11) default NULL,
  `total_revenue` int(11) default '0',
  `total_charge` int(11) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=153716 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `phone` varchar(250) NOT NULL,
  `description` varchar(250) default NULL,
  `permission` varchar(250) NOT NULL,
  `role` varchar(250) NOT NULL,
  `cp_id` int(11) default NULL,
  `creat_time` timestamp NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

/*Table structure for table `weblog` */

DROP TABLE IF EXISTS `weblog`;

CREATE TABLE `weblog` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(20) character set utf8 collate utf8_unicode_ci default NULL,
  `command` varchar(250) default NULL,
  `description` varchar(250) character set utf8 collate utf8_unicode_ci default NULL,
  `datetime` timestamp NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1140 DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
