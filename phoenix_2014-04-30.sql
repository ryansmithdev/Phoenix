# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.5.33)
# Database: phoenix
# Generation Time: 2014-04-30 21:30:07 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table groups
# ------------------------------------------------------------

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `inherit` varchar(50) DEFAULT NULL,
  `perms` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;

INSERT INTO `groups` (`id`, `name`, `inherit`, `perms`)
VALUES
	(1,'admin',NULL,'*'),
	(2,'default',NULL,'-.*'),
	(3,'writer',NULL,'phoenix.calendar.*%phoenix.page.access.blog'),
	(6,'test1','','');

/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;


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
  `protected` int(1) NOT NULL DEFAULT '0',
  `security` varchar(5) NOT NULL,
  `published_by` varchar(50) NOT NULL,
  `published_date` varchar(50) NOT NULL,
  `requires_template` int(1) NOT NULL DEFAULT '0',
  `no_privilege` varchar(50) DEFAULT NULL,
  `no_privilege_msg` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;

INSERT INTO `pages` (`id`, `parent_page`, `slug`, `title`, `body`, `description`, `page_type`, `protected`, `security`, `published_by`, `published_date`, `requires_template`, `no_privilege`, `no_privilege_msg`)
VALUES
	(1,'','index','Home - Welcome','','','',0,'','','',0,NULL,NULL),
	(3,'','about','About Us','We are awesome people. That\'s all you need to know.','About our company','2',0,'0','admin','',1,NULL,NULL),
	(4,'','home','Home','Welcome Home.','This is the home page','2',1,'0','admin','',1,NULL,NULL),
	(5,'','login','User Login','','','0',0,'0','admin','',0,NULL,NULL),
	(6,'','','','','','',0,'','','',0,NULL,NULL),
	(7,'','welcome','Welcome To Your Phoenix Website','You can now start building an awesome site! Yay!','A welcome page','1',0,'0','admin','',1,NULL,NULL);

/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table permissions
# ------------------------------------------------------------

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `node` text,
  `description` longtext,
  `plugin` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;

INSERT INTO `permissions` (`id`, `node`, `description`, `plugin`)
VALUES
	(1,'phoenix.blog.create.*','Create Phoenix blog posts','Phoenix'),
	(2,'phoenix.blog.*','Full read,write,edit permissions for entire blog, all posts.','Phoenix'),
	(3,'phoenix.blog.edit.*','Edit all blogs','Phoenix'),
	(4,'phoenix.blog.edit.own','Edit own blog','Phoenix'),
	(5,'phoenix.blog.edit.others','Edit others blogs','Phoenix'),
	(6,'phoenix.blog.edit.delete.own','Delete own blog','Phoenix'),
	(7,'phoenix.blog.edit.delete.others','Delete others blogs','Phoenix'),
	(8,'phoenix.calendar.date.*','Change all calendar dates','Phoenix'),
	(9,'phoenix.calendar.edit.own','Change own calendar dates','Phoenix'),
	(10,'phoenix.calendar.edit.others','Change other users calendar dates','Phoenix'),
	(11,'phoenix.calendar.*','All calender permissions\n','Phoenix'),
	(12,'phoenix.calendar.date.add.own','Add own calendar date','Phoenix'),
	(13,'phoenix.calendar.date.add.others','add calender dates for other people','Phoenix'),
	(14,'phoenix.calendar.event.*','add calender events for anyone','Phoenix'),
	(15,'phoenix.calendar.event.edit.own','add calender events for self','Phoenix'),
	(16,'phoenix.calendar.event.edit.others','add calendar events for others','Phoenix'),
	(17,'phoenix.calendar.event.edit.*','edit calendar event','Phoenix'),
	(18,'phoenix.page.*','all page perms','Phoenix'),
	(19,'phoenix.page.edit.*','edit any page','Phoenix'),
	(20,'phoenix.page.edit.own','edit only own page','Phoenix'),
	(21,'phoenix.page.edit.others','edit only others pages','Phoenix'),
	(22,'phoenix.page.edit.add.*','add pages','Phoenix'),
	(23,'phoenix.page.edit.add.own','add only pages owned by self','Phoenix'),
	(24,'phoenix.page.edit.add.others','add pages for other users','Phoenix'),
	(25,'Phoenix.users.*','control all users','Phoenix'),
	(26,'Phoenix.users.add','add users','Phoenix'),
	(27,'Phoenix.users.delete','delete users','Phoenix'),
	(28,'Phoenix.users.edit.*','edit anything about users','Phoenix'),
	(29,'Phoenix.users.edit.own','edit self','Phoenix'),
	(30,'Phoenix.users.edit.others','edit other users','Phoenix');

/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table plugins
# ------------------------------------------------------------

DROP TABLE IF EXISTS `plugins`;

CREATE TABLE `plugins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `path` varchar(50) DEFAULT NULL,
  `enabled` varchar(5) DEFAULT NULL,
  `developer` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `plugins` WRITE;
/*!40000 ALTER TABLE `plugins` DISABLE KEYS */;

INSERT INTO `plugins` (`id`, `path`, `enabled`, `developer`)
VALUES
	(1,'/rsdev_calendar/','true','Thirty & Seven, LLC'),
	(2,'/admin_panel/','true','Thirty & Seven, LLC');

/*!40000 ALTER TABLE `plugins` ENABLE KEYS */;
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
	(1,'site_title','My Site Name','str'),
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


# Dump of table url_structure
# ------------------------------------------------------------

DROP TABLE IF EXISTS `url_structure`;

CREATE TABLE `url_structure` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file` varchar(50) DEFAULT NULL,
  `slug` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL,
  `first_name` varchar(50) NOT NULL DEFAULT 'Jon',
  `last_name` varchar(50) NOT NULL DEFAULT 'Appleseed',
  `display_name` varchar(50) NOT NULL DEFAULT 'Phoenix Member',
  `group` varchar(50) NOT NULL DEFAULT '0',
  `permissions` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `login`, `password`, `first_name`, `last_name`, `display_name`, `group`, `permissions`)
VALUES
	(1,'admin','admin','Ryan','Smith','ryansmithdev','writer','phoenix.page.access.home%phoenix.page.access.about');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
