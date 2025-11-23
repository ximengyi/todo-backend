-- --------------------------------------------------------
-- 主机:                           127.0.0.1
-- 服务器版本:                        8.0.39 - MySQL Community Server - GPL
-- 服务器操作系统:                      Win64
-- HeidiSQL 版本:                  12.10.0.7000
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- 导出 todo 的数据库结构
CREATE DATABASE IF NOT EXISTS `todo` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `todo`;

-- 导出  表 todo.todolist 结构
CREATE TABLE IF NOT EXISTS `todolist` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `content` text COLLATE utf8mb4_general_ci COMMENT '内容',
  `user_id` bigint unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `sort` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '状态(0进行中1已完成)',
  `group_id` bigint unsigned NOT NULL DEFAULT '0' COMMENT '组id',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 导出  表 todo.users 结构
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT '用户名',
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL COMMENT '密码(加密)',
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '邮箱',
  `nickname` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '昵称',
  `avatar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '头像',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态(0禁用1启用)',
  `last_login_at` datetime DEFAULT NULL COMMENT '最后登录时间',
  `last_login_ip` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '最后登录IP',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  `updated_at` datetime DEFAULT NULL COMMENT '更新时间',
  `deleted_at` datetime DEFAULT NULL COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_username` (`username`),
  UNIQUE KEY `uk_email` (`email`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='用户表';

-- 导出  表 todo.login_logs 结构
CREATE TABLE IF NOT EXISTS `login_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL COMMENT '用户id',
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL COMMENT '用户名',
  `login_ip` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '登录IP',
  `user_agent` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '用户代理',
  `status` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '登录状态(0失败1成功)',
  `message` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT '登录信息',
  `created_at` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='登录日志表';

-- 数据导出被取消选择。

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
