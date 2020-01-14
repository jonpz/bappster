-- MySQL dump 10.13  Distrib 5.7.20, for Linux (x86_64)
--
-- Host: localhost    Database: l_appstarter_com
-- ------------------------------------------------------
-- Server version	5.7.20-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `section_action_id` int(10) unsigned DEFAULT NULL,
  `action` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `row_id` int(10) unsigned DEFAULT NULL,
  `old_data` longblob,
  `new_data` longblob,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `exec_time` decimal(10,4) DEFAULT NULL,
  `is_permfail` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `row_id` (`row_id`),
  KEY `audit_logs_ibfk2` (`section_action_id`),
  CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `audit_logs_ibfk_2` FOREIGN KEY (`section_action_id`) REFERENCES `section_actions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_images`
--

DROP TABLE IF EXISTS `email_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `file` varchar(255) NOT NULL,
  `email_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `email_id` (`email_id`),
  CONSTRAINT `email_images_ibfk_1` FOREIGN KEY (`email_id`) REFERENCES `emails` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_images`
--

LOCK TABLES `email_images` WRITE;
/*!40000 ALTER TABLE `email_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emails`
--

DROP TABLE IF EXISTS `emails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `emails` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `html` longtext COLLATE utf8_unicode_ci,
  `text` longtext COLLATE utf8_unicode_ci,
  `vars` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emails`
--

LOCK TABLES `emails` WRITE;
/*!40000 ALTER TABLE `emails` DISABLE KEYS */;
INSERT INTO `emails` VALUES (1,'template',':subject','<h1>App Header</h1>\r\n<hr/>\r\n:content\r\n<hr/>\r\n<small><em>sent by the web app!</em></small>','APP HEADER\r\n===========\r\n:content\r\n\r\n------------------\r\nsent by the web app!','[\"content\",\"subject\"]'),(2,'reset_password','Reset your password','<p>Somebody using your email submitted a request for a password reset. If this was not you, you may simply ignore the link below, as it will expire in 24 hours. Otherwise, follow the link to set up your new password.</p>\r\n<a href=\":reset_link\">:reset_link</a>','Somebody using your email submitted a request for a password reset. If this was not you, you may simply ignore the link below, as it will expire in 24 hours. Otherwise, follow the link to set up your new password.\r\n\r\n:reset_link','[\"reset_link\"]');
/*!40000 ALTER TABLE `emails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report_cols`
--

DROP TABLE IF EXISTS `report_cols`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `report_cols` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `select` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `array_index` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `datatype` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '0',
  `nullable` tinyint(1) NOT NULL DEFAULT '1',
  `section_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `select` (`select`,`section_id`),
  KEY `section_id` (`section_id`),
  CONSTRAINT `report_cols_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_cols`
--

LOCK TABLES `report_cols` WRITE;
/*!40000 ALTER TABLE `report_cols` DISABLE KEYS */;
INSERT INTO `report_cols` VALUES (1,'ID','u.id','id','int',1,0,2),(2,'First Name','u.first_name','first_name','string',1,0,2),(3,'Last Name','u.last_name','last_name','string',1,0,2),(4,'Email','u.email','email','string',1,0,2),(5,'Role','r.name','name','string',1,0,2),(6,'Last Login','u.last_login','last_login','datetime',0,0,2),(7,'Created At','u.created_at','created_at','datetime',0,0,2),(8,'Deleted','u.deleted','deleted','bool',0,0,2);
/*!40000 ALTER TABLE `report_cols` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reports` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `config` longblob,
  `section_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`,`section_id`),
  KEY `section_id` (`section_id`),
  CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reports`
--

LOCK TABLES `reports` WRITE;
/*!40000 ALTER TABLE `reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_permissions` (
  `role_id` int(10) unsigned NOT NULL,
  `section_action_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`section_action_id`),
  KEY `section_action_id` (`section_action_id`),
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`section_action_id`) REFERENCES `section_actions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
INSERT INTO `role_permissions` VALUES (1,1),(2,1),(1,2),(2,2),(1,3),(2,3),(1,4),(2,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11),(1,12),(1,13),(1,14),(2,14),(1,15),(2,15),(1,16),(2,16),(1,17),(2,17),(1,18),(1,19),(1,20),(1,21),(1,22),(2,22),(1,23),(1,24),(2,24),(1,25),(1,26);
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `priority` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Super Admin',2),(2,'Admin',1);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `section_actions`
--

DROP TABLE IF EXISTS `section_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `section_actions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tag` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `section_id` int(10) unsigned NOT NULL,
  `hide_nav` tinyint(1) NOT NULL DEFAULT '0',
  `priority` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`,`section_id`),
  KEY `section_id` (`section_id`),
  CONSTRAINT `section_actions_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `section_actions`
--

LOCK TABLES `section_actions` WRITE;
/*!40000 ALTER TABLE `section_actions` DISABLE KEYS */;
INSERT INTO `section_actions` VALUES (1,'View','read',1,1,0),(2,'View','read',2,1,0),(3,'Add','create',2,0,0),(4,'Edit','update',2,1,0),(5,'Export','export',2,0,0),(6,'View','read',3,1,0),(7,'Add','create',3,0,0),(8,'Edit','update',3,1,0),(9,'Delete','delete',3,1,0),(10,'View','read',4,1,0),(11,'Add','create',4,0,0),(12,'Edit','update',4,1,0),(13,'Delete','delete',4,1,0),(14,'View','read',5,1,0),(15,'Add','create',5,0,0),(16,'Edit','update',5,1,0),(17,'Delete','delete',5,1,0),(18,'View','read',6,1,0),(19,'Add','create',6,0,0),(20,'Edit','update',6,1,0),(21,'Delete','delete',6,1,0),(22,'View','read',7,1,0),(23,'Add','create',7,0,0),(24,'Edit','update',7,1,0),(25,'Delete','delete',7,1,0),(26,'View','read',8,1,0);
/*!40000 ALTER TABLE `section_actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sections` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `table` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tag` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `model_name` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reports_from` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `hide_nav` tinyint(1) NOT NULL DEFAULT '0',
  `priority` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `table` (`table`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sections`
--

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
INSERT INTO `sections` VALUES (1,'Admin',NULL,'admin',NULL,NULL,1,0),(2,'Users','users','users','Users','users u left join roles r on r.id = u.role_id',0,0),(3,'Roles','roles','roles','Roles',NULL,0,0),(4,'Sections','sections','sections','Sections',NULL,0,0),(5,'Reports','reports','reports','Reports',NULL,1,0),(6,'Report Columns','report_cols','report-columns','ReportColumns',NULL,0,0),(7,'Emails','emails','emails','Emails',NULL,0,0),(8,'Audit Logs','audit_logs','audit-logs','AuditLogs',NULL,0,0);
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hash` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role_id` int(10) unsigned DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token_exp` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`,`deleted`),
  KEY `role_id` (`role_id`),
  KEY `deleted` (`deleted`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Jonathan','Phillips','jon.pza@gmail.com','$2a$10$WTxFcYXHGGP4M4thbthCPeDBfokVP/3zFE9z1xY/OLOJaW1D23Mby',1,NULL,NULL,NULL,'2017-11-06 21:28:34',0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-11-13 17:06:21
