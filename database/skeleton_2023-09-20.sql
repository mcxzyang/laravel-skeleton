# ************************************************************
# Sequel Pro SQL dump
# Version 5446
#
# https://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 8.0.32)
# Database: skeleton
# Generation Time: 2023-09-19 16:52:35 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table admin_menus
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_menus`;

CREATE TABLE `admin_menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pid` int NOT NULL DEFAULT '0',
  `type` int NOT NULL COMMENT '1-菜单 2-按钮',
  `path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ignoreCache` int DEFAULT NULL,
  `hideInMenu` int DEFAULT NULL,
  `permission` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort` int NOT NULL,
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_menus` WRITE;
/*!40000 ALTER TABLE `admin_menus` DISABLE KEYS */;

INSERT INTO `admin_menus` (`id`, `title`, `pid`, `type`, `path`, `name`, `icon`, `ignoreCache`, `hideInMenu`, `permission`, `sort`, `status`, `created_at`, `updated_at`)
VALUES
	(1,'系统管理',0,1,'/system','System','settings',0,0,NULL,1,1,NULL,'2023-09-09 22:31:10'),
	(2,'用户管理',1,1,'/system/user','AdminUser','user',0,0,NULL,1,1,NULL,'2023-09-10 23:21:07'),
	(3,'角色管理',1,1,'/system/role','Role','safe',0,0,NULL,2,1,'2023-09-09 21:51:11','2023-09-09 21:51:11'),
	(4,'菜单管理',1,1,'/system/menu','Menu','menu',0,0,NULL,3,1,'2023-09-09 21:58:29','2023-09-09 21:58:29'),
	(5,'新增',3,2,NULL,NULL,NULL,0,0,'system:role:add',1,1,'2023-09-10 21:51:20','2023-09-10 21:51:20'),
	(6,'编辑',3,2,NULL,NULL,NULL,0,0,'system:role:update',2,1,'2023-09-10 21:51:40','2023-09-10 21:53:45'),
	(7,'删除',3,2,NULL,NULL,NULL,0,0,'system:role:delete',3,1,'2023-09-10 21:51:54','2023-09-10 21:51:54'),
	(8,'新增',2,2,NULL,NULL,NULL,0,0,'system:adminUser:add',1,1,'2023-09-10 21:58:28','2023-09-10 21:58:28'),
	(9,'编辑',2,2,NULL,NULL,NULL,0,0,'system:adminUser:update',2,1,'2023-09-10 21:58:44','2023-09-10 21:58:44'),
	(10,'删除',2,2,NULL,NULL,NULL,0,0,'system:adminUser:delete',3,1,'2023-09-10 21:59:00','2023-09-10 21:59:00'),
	(11,'导出',2,2,NULL,NULL,NULL,0,0,'system:adminUser:export',4,1,'2023-09-10 21:59:11','2023-09-10 21:59:11'),
	(12,'新增',4,2,NULL,NULL,NULL,0,0,'system:menu:add',1,1,'2023-09-10 22:24:07','2023-09-10 22:24:07'),
	(13,'编辑',4,2,NULL,NULL,NULL,0,0,'system:menu:update',2,1,'2023-09-10 22:24:25','2023-09-10 22:24:25'),
	(14,'删除',4,2,NULL,NULL,NULL,0,0,'system:menu:delete',3,1,'2023-09-10 22:24:36','2023-09-10 22:24:36');

/*!40000 ALTER TABLE `admin_menus` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_operation_logs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_operation_logs`;

CREATE TABLE `admin_operation_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admin_user_id` int NOT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `params` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `response_params` text COLLATE utf8mb4_unicode_ci,
  `browser` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_code` int NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_operation_logs` WRITE;
/*!40000 ALTER TABLE `admin_operation_logs` DISABLE KEYS */;

INSERT INTO `admin_operation_logs` (`id`, `admin_user_id`, `method`, `ip`, `params`, `response_params`, `browser`, `status_code`, `url`, `created_at`, `updated_at`)
VALUES
	(1,0,'POST','127.0.0.1','{\"username\":\"admin\",\"password\":\"123456\"}','{\"status\":\"error\",\"code\":400,\"message\":\"\\u8d26\\u53f7\\u4e0d\\u5b58\\u5728\"}','0',400,'http://skeleton.work/api/admin/auth/login','2023-09-07 15:29:27','2023-09-07 15:29:27'),
	(2,0,'POST','127.0.0.1','{\"username\":\"admin\",\"password\":\"123456\"}','{\"status\":\"error\",\"code\":400,\"message\":\"\\u8d26\\u53f7\\u4e0d\\u5b58\\u5728\"}','0',400,'http://skeleton.work/api/admin/auth/login','2023-09-07 16:16:15','2023-09-07 16:16:15');

/*!40000 ALTER TABLE `admin_operation_logs` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_role_menus
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_role_menus`;

CREATE TABLE `admin_role_menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int NOT NULL,
  `menu_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_role_menus` WRITE;
/*!40000 ALTER TABLE `admin_role_menus` DISABLE KEYS */;

INSERT INTO `admin_role_menus` (`id`, `role_id`, `menu_id`, `created_at`, `updated_at`)
VALUES
	(1,1,1,NULL,NULL),
	(3,1,3,NULL,NULL),
	(6,1,4,NULL,NULL),
	(8,1,2,NULL,NULL),
	(9,2,1,NULL,NULL),
	(10,2,2,NULL,NULL),
	(11,2,3,NULL,NULL),
	(12,2,4,NULL,NULL),
	(13,2,8,NULL,NULL),
	(14,2,9,NULL,NULL),
	(15,2,10,NULL,NULL),
	(16,2,11,NULL,NULL),
	(17,2,5,NULL,NULL),
	(18,2,6,NULL,NULL),
	(19,2,7,NULL,NULL),
	(20,2,12,NULL,NULL),
	(21,2,13,NULL,NULL),
	(22,2,14,NULL,NULL);

/*!40000 ALTER TABLE `admin_role_menus` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_roles`;

CREATE TABLE `admin_roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_roles` WRITE;
/*!40000 ALTER TABLE `admin_roles` DISABLE KEYS */;

INSERT INTO `admin_roles` (`id`, `name`, `code`, `description`, `status`, `created_at`, `updated_at`)
VALUES
	(1,'Test','Test','123',1,'2023-09-10 12:14:47','2023-09-10 19:25:36'),
	(2,'管理员','admin',NULL,1,'2023-09-10 20:06:32','2023-09-10 23:15:44');

/*!40000 ALTER TABLE `admin_roles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_user_roles
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_user_roles`;

CREATE TABLE `admin_user_roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admin_user_id` int NOT NULL,
  `role_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_user_roles` WRITE;
/*!40000 ALTER TABLE `admin_user_roles` DISABLE KEYS */;

INSERT INTO `admin_user_roles` (`id`, `admin_user_id`, `role_id`, `created_at`, `updated_at`)
VALUES
	(1,1,1,NULL,NULL),
	(2,1,2,NULL,NULL),
	(3,4,1,NULL,NULL);

/*!40000 ALTER TABLE `admin_user_roles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table admin_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_users`;

CREATE TABLE `admin_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_super_admin` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;

INSERT INTO `admin_users` (`id`, `username`, `name`, `avatar`, `password`, `is_super_admin`, `status`, `created_at`, `updated_at`)
VALUES
	(1,'admin','Admin',NULL,'$2y$10$PcnYKxUBalzjIAY6lmM3NeylDGK9.dUyta.Ebn3drl.L1sCu9SS/a',1,1,'2023-09-08 00:36:43','2023-09-08 00:36:43'),
	(4,'test','test',NULL,'$2y$10$Z4yTIj8qAtEIYCuPgILqvu55jVPPCK3IB9hVQOe7i5e5UMlHVKwIW',1,1,'2023-09-09 11:25:06','2023-09-10 23:18:59');

/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table failed_jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`id`, `migration`, `batch`)
VALUES
	(1,'2014_10_12_000000_create_users_table',1),
	(2,'2014_10_12_100000_create_password_resets_table',1),
	(3,'2019_08_19_000000_create_failed_jobs_table',1),
	(4,'2019_12_14_000001_create_personal_access_tokens_table',1),
	(5,'2021_11_12_212601_create_request_logs_table',1),
	(6,'2023_02_13_203305_create_admin_users_table',1),
	(7,'2023_09_07_114513_create_admin_operation_logs_table',2),
	(8,'2023_09_09_204231_create_admin_menus_table',3),
	(9,'2023_09_10_112904_create_admin_roles_table',4),
	(10,'2023_09_10_114051_create_admin_role_menus_table',5),
	(11,'2023_09_10_193837_create_admin_user_roles_table',6);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table password_resets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table personal_access_tokens
# ------------------------------------------------------------

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table request_logs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `request_logs`;

CREATE TABLE `request_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int DEFAULT NULL,
  `params` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `response_params` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nickname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '昵称',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '头像',
  `oa_openid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '公众号openid',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '手机号',
  `role_id` int DEFAULT NULL COMMENT '角色 1-普通用户 2-运营者',
  `pid` int DEFAULT NULL COMMENT '上级ID',
  `balance` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '余额',
  `status` int NOT NULL DEFAULT '1' COMMENT '状态 1-正常 0-已禁用',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
