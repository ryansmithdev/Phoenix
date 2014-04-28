# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.14)
# Database: phoenix
# Generation Time: 2014-02-13 23:50:40 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table pages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_page` longtext NOT NULL,
  `slug` longtext NOT NULL,
  `title` longtext NOT NULL,
  `body` longtext NOT NULL,
  `description` longtext NOT NULL,
  `page_type` varchar(5) NOT NULL,
  `security` varchar(5) NOT NULL,
  `published_by` varchar(50) NOT NULL,
  `published_date` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;

INSERT INTO `pages` (`id`, `parent_page`, `slug`, `title`, `body`, `description`, `page_type`, `security`, `published_by`, `published_date`)
VALUES
	(1,'','welcome','Welcome To Your Phoenix Website','You can now start building an awesome site! Yay!','A welcome page','1','0','admin',''),
	(3,'','about','About Us','We are awesome people. That\'s all you need to know.','About our company','2','0','admin',''),
	(4,'','home','Home','Welcome Home.','This is the home page','2','0','admin',''),
	(5,'','login','User Login','','','0','0','admin','');

/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table prefs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `prefs`;

CREATE TABLE `prefs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` longtext NOT NULL,
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `prefs` WRITE;
/*!40000 ALTER TABLE `prefs` DISABLE KEYS */;

INSERT INTO `prefs` (`id`, `name`, `value`, `type`)
VALUES
	(1,'site_title','My First Phoenix Site','str'),
	(2,'company_name','Ryan Smith Developer, INC','str'),
	(3,'company_logo_path','/img/logo.png','str'),
	(4,'domain','http://phoenix.local/','str'),
	(5,'search_engine_index','false','bool'),
	(6,'front_page','home','str'),
	(7,'init_secure_page','main','str'),
	(8,'show_user_bar','true','bool'),
	(9,'phoenix_valid','true','bool');

/*!40000 ALTER TABLE `prefs` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `display_name` varchar(50) NOT NULL,
  `user_role` varchar(5) NOT NULL,
  `privileges` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
