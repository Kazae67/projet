-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour projet
CREATE DATABASE IF NOT EXISTS `projet` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `projet`;

-- Listage de la structure de table projet. adress
CREATE TABLE IF NOT EXISTS `adress` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default_billing` tinyint(1) NOT NULL,
  `is_default_delivery` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5CECC7BEA76ED395` (`user_id`),
  CONSTRAINT `FK_5CECC7BEA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projet.adress : ~12 rows (environ)
INSERT INTO `adress` (`id`, `user_id`, `type`, `street`, `city`, `state`, `postal_code`, `country`, `is_default_billing`, `is_default_delivery`) VALUES
	(107, 79, 'delivery', 'Delivery 2', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(108, 79, 'billing', 'Billing', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(109, 79, 'billing', 'Billing', 'Billing', 'azeaze', '67100', 'FR', 0, 0),
	(110, 79, 'delivery', 'Delivery', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(111, 79, 'delivery', 'Delivery', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(112, 79, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(113, 79, 'billing', 'aaaa', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(114, 79, 'billing', 'tata', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(115, 79, 'delivery', 'tatata', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(116, 79, 'billing', 'tatatata', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(117, 80, 'billing', 'Billing 4', 'City of billing', 'State of billing', '67100', 'FR', 0, 0),
	(118, 80, 'delivery', 'Delivery 20', 'test', 'azeaze', '67100', 'FR', 0, 0);

-- Listage de la structure de table projet. archived_order
CREATE TABLE IF NOT EXISTS `archived_order` (
  `id` int NOT NULL AUTO_INCREMENT,
  `archived_at` datetime NOT NULL,
  `user_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projet.archived_order : ~53 rows (environ)
INSERT INTO `archived_order` (`id`, `archived_at`, `user_name`, `address_details`, `total`, `status`, `created_at`, `first_name`, `last_name`) VALUES
	(1, '2023-11-16 18:40:55', 'Kaz15', 'Information d\'adresse ici', 50.00, 'confirmed', '2023-11-16 18:36:33', NULL, NULL),
	(2, '2023-11-16 20:31:13', 'Kaz15', 'test, test, azeaze, 67100, FR', 20.00, 'confirmed', '2023-11-16 20:17:58', NULL, NULL),
	(3, '2023-11-16 20:36:49', 'Kaz15', 'test, test, azeaze, 67100, FR', 329.99, 'confirmed', '2023-11-16 20:36:01', NULL, NULL),
	(4, '2023-11-16 20:40:55', 'Kaz15', 'test, test, azeaze, 67100, FR', 10.00, 'confirmed', '2023-11-16 20:40:35', NULL, NULL),
	(5, '2023-11-16 21:03:18', 'Kaz15', 'test, test, azeaze, 67100, FR', 10.00, 'confirmed', '2023-11-16 21:03:01', NULL, NULL),
	(6, '2023-11-16 22:05:38', 'Kaz15', 'Address not provided', 10.00, 'confirmed', '2023-11-16 22:05:20', NULL, NULL),
	(7, '2023-11-16 22:08:00', 'Kaz15', 'Address not provided', 10.00, 'confirmed', '2023-11-16 22:07:45', NULL, NULL),
	(8, '2023-11-16 22:19:12', 'Kaz15', 'Address not provided', 10.00, 'confirmed', '2023-11-16 22:18:56', NULL, NULL),
	(9, '2023-11-16 22:26:27', 'Kaz15', 'Address not provided', 10.00, 'confirmed', '2023-11-16 22:26:10', NULL, NULL),
	(10, '2023-11-16 22:49:16', 'Kaz15', 'test, test, azeaze, 67100, FR', 10.00, 'confirmed', '2023-11-16 22:48:48', NULL, NULL),
	(11, '2023-11-16 22:51:48', 'Login1', 'Delivery, city, state, 67100, FR', 10.00, 'confirmed', '2023-11-16 22:51:35', NULL, NULL),
	(12, '2023-11-16 22:52:44', 'Login1', 'Delivery, city, state, 67100, FR', 10.00, 'confirmed', '2023-11-16 22:52:29', NULL, NULL),
	(13, '2023-11-16 22:54:05', 'Login1', 'Street, City, State, 67100, FR', 10.00, 'confirmed', '2023-11-16 22:53:50', NULL, NULL),
	(14, '2023-11-16 22:55:54', 'Login1', 'Street Billing, City Billing, State Billing, 67100, FR', 10.00, 'confirmed', '2023-11-16 22:55:41', NULL, NULL),
	(15, '2023-11-16 22:57:15', 'Login1', 'Street Billing, City Billing, State Billing, 67100, FR', 10.00, 'confirmed', '2023-11-16 22:56:58', NULL, NULL),
	(16, '2023-11-16 22:57:58', 'Login1', 'Street Billing, City Billing, State Billing, 67100, FR', 10.00, 'confirmed', '2023-11-16 22:57:44', NULL, NULL),
	(17, '2023-11-16 22:58:52', 'Login1', 'Street Billing, City Billing, State Billing, 67100, FR', 10.00, 'confirmed', '2023-11-16 22:58:38', NULL, NULL),
	(18, '2023-11-16 23:38:50', 'Login1', 'Street Billing, City Billing, State Billing, 67100, FR', 10.00, 'confirmed', '2023-11-16 23:38:31', NULL, NULL),
	(19, '2023-11-17 00:12:35', 'Login1', 'test, test, azeaze, 67100, FR', 10.00, 'confirmed', '2023-11-17 00:12:19', NULL, NULL),
	(20, '2023-11-17 00:36:38', 'Login1', 'Address not provided', 10.00, 'confirmed', '2023-11-17 00:36:23', NULL, NULL),
	(21, '2023-11-17 00:46:04', 'Login1', 'test, test, azeaze, 67100, FR', 10.00, 'confirmed', '2023-11-17 00:45:41', NULL, NULL),
	(22, '2023-11-18 19:09:18', 'Login1', 'Delivery street, test, azeaze, 67100, FR', 20.00, 'confirmed', '2023-11-18 19:08:59', NULL, NULL),
	(23, '2023-11-18 19:11:32', 'Login1', 'Billing, test, azeaze, 67100, FR', 10.00, 'confirmed', '2023-11-18 19:11:12', NULL, NULL),
	(24, '2023-11-18 19:12:40', 'Login1', 'Billing, test, azeaze, 67100, FR', 10.00, 'confirmed', '2023-11-18 19:12:24', NULL, NULL),
	(25, '2023-11-18 19:14:32', 'Login1', 'Billing, test, azeaze, 67100, FR', 20.00, 'confirmed', '2023-11-18 19:14:13', NULL, NULL),
	(26, '2023-11-18 19:35:03', 'Login1', 'Billing, test, azeaze, 67100, FR', 10.00, 'confirmed', '2023-11-18 19:34:47', NULL, NULL),
	(27, '2023-11-18 19:52:28', 'Login1', 'Address not provided', 10.00, 'confirmed', '2023-11-18 19:51:03', NULL, NULL),
	(28, '2023-11-18 19:58:36', 'Login1', 'Address not provided', 20.00, 'confirmed', '2023-11-18 19:58:20', NULL, NULL),
	(29, '2023-11-18 22:22:09', 'Login1', 'testzzz, test, azeaze, 67100, FR', 20.00, 'confirmed', '2023-11-18 22:21:52', NULL, NULL),
	(30, '2023-11-18 22:23:47', 'Login1', 'testzzz, test, azeaze, 67100, FR', 10.00, 'confirmed', '2023-11-18 22:23:26', NULL, NULL),
	(31, '2023-11-18 22:24:37', 'Login1', 'testzzz, test, azeaze, 67100, FR', 10.00, 'confirmed', '2023-11-18 22:24:21', NULL, NULL),
	(32, '2023-11-19 18:54:33', 'Login1', 'testzzz, test, azeaze, 67100, FR', 30.00, 'confirmed', '2023-11-19 18:53:38', NULL, NULL),
	(33, '2023-11-19 20:16:16', 'Kaz67', 'Delivery, test, azeaze, 67100, FR', 20.00, 'confirmed', '2023-11-19 20:15:58', NULL, NULL),
	(34, '2023-11-19 22:26:19', 'Kaz67', 'Delivery 2, test, azeaze, 67100, FR', 70.00, 'confirmed', '2023-11-19 22:24:55', NULL, NULL),
	(35, '2023-11-20 06:44:57', 'Kaz67', 'test, test, azeaze, 67100, FR', 20.00, 'confirmed', '2023-11-20 06:44:40', NULL, NULL),
	(36, '2023-11-20 07:01:26', 'Kaz67', 'test, test, azeaze, 67100, FR', 10.00, 'confirmed', '2023-11-20 07:01:10', NULL, NULL),
	(37, '2023-11-20 07:14:00', 'Kaz67', 'test, test, azeaze, 67100, FR', 10.00, 'confirmed', '2023-11-20 07:13:42', NULL, NULL),
	(38, '2023-11-20 23:11:06', 'Kaz67', 'test, test, azeaze, 67100, FR', 10.00, 'confirmed', '2023-11-20 23:10:27', NULL, NULL),
	(39, '2023-11-20 23:14:07', 'Kaz67', 'test, test, azeaze, 67100, FR', 10.00, 'confirmed', '2023-11-20 23:13:44', NULL, NULL),
	(40, '2023-11-20 23:47:22', 'Kaz67', 'Address not provided', 10.00, 'confirmed', '2023-11-20 23:46:56', NULL, NULL),
	(41, '2023-11-21 11:07:51', 'Kaz2', 'Billing 4, City of billing, State of billing, 67100, FR', 20.00, 'confirmed', '2023-11-21 10:56:46', NULL, NULL),
	(42, '2023-11-21 11:51:55', 'Kaz2', 'Billing 4, City of billing, State of billing, 67100, FR', 20.00, 'confirmed', '2023-11-21 11:51:31', NULL, NULL),
	(43, '2023-11-21 11:54:12', 'Kaz2', 'Billing 4, City of billing, State of billing, 67100, FR', 10.00, 'confirmed', '2023-11-21 11:53:54', NULL, NULL),
	(44, '2023-11-21 12:20:58', 'Kaz2', 'Delivery 4, Delivery city, Delivery province, 67100, FR', 10.00, 'confirmed', '2023-11-21 12:20:28', NULL, NULL),
	(45, '2023-11-21 12:22:35', 'Kaz2', 'Billing 4, City of billing, State of billing, 67100, FR', 10.00, 'confirmed', '2023-11-21 12:22:17', NULL, NULL),
	(46, '2023-11-21 12:35:12', 'Kaz2', 'Billing 4, City of billing, State of billing, 67100, FR', 10.00, 'confirmed', '2023-11-21 12:34:54', NULL, NULL),
	(47, '2023-11-21 12:36:19', 'Kaz2', 'Delivery 4, Delivery city, Delivery province, 67100, FR', 10.00, 'confirmed', '2023-11-21 12:36:01', NULL, NULL),
	(48, '2023-11-21 14:06:33', 'Kaz2', 'Billing 4, City of billing, State of billing, 67100, FR', 10.00, 'confirmed', '2023-11-21 14:06:10', NULL, NULL),
	(49, '2023-11-21 14:13:58', 'Kaz2', 'Delivery 4, Delivery city, Delivery province, 67100, FR', 10.00, 'confirmed', '2023-11-21 14:13:36', 'Yass', 'Akgedik'),
	(50, '2023-11-21 14:33:15', 'Kaz2', 'Delivery 4, Delivery city, Delivery province, 67100, FR', 10.00, 'confirmed', '2023-11-21 14:32:57', 'yass', 'tesst'),
	(51, '2023-11-21 14:34:29', 'Kaz2', 'Billing 4, City of billing, State of billing, 67100, FR', 10.00, 'confirmed', '2023-11-21 14:34:06', 'Billing', 'test'),
	(52, '2023-11-21 15:05:32', 'Kaz2', 'Delivery 10, test, azeaze, 67100, FR', 10.00, 'confirmed', '2023-11-21 15:05:13', 'delivery name test', 'laozoea'),
	(53, '2023-11-21 15:27:00', 'Kaz2', 'Delivery 20, test, azeaze, 67100, FR', 10.00, 'confirmed', '2023-11-21 15:26:03', 'aztaztaztazata', 'azezaeaeazeazeatr'),
	(54, '2023-11-21 15:44:09', 'Kaz2', 'Billing 4, City of billing, State of billing, 67100, FR', 349.97, 'confirmed', '2023-11-21 15:43:51', 'Yaaas', 'AAAKg'),
	(55, '2023-11-21 15:48:11', 'Kaz2', 'Billing 4, City of billing, State of billing, 67100, FR', 60.00, 'confirmed', '2023-11-21 15:47:54', 'Yaaas', 'AAAKg');

-- Listage de la structure de table projet. archived_order_detail
CREATE TABLE IF NOT EXISTS `archived_order_detail` (
  `id` int NOT NULL AUTO_INCREMENT,
  `archived_order_id` int NOT NULL,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4271C0587765132B` (`archived_order_id`),
  CONSTRAINT `FK_4271C0587765132B` FOREIGN KEY (`archived_order_id`) REFERENCES `archived_order` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projet.archived_order_detail : ~55 rows (environ)
INSERT INTO `archived_order_detail` (`id`, `archived_order_id`, `product_name`, `price`, `quantity`) VALUES
	(1, 1, 'name product', 10.00, 2),
	(2, 1, 'lol', 30.00, 1),
	(3, 2, 'name product', 10.00, 2),
	(4, 3, 'name product', 10.00, 3),
	(5, 3, 'Produit de test', 299.99, 1),
	(6, 4, 'name product', 10.00, 1),
	(7, 5, 'name product', 10.00, 1),
	(8, 6, 'name product', 10.00, 1),
	(9, 7, 'name product', 10.00, 1),
	(10, 8, 'name product', 10.00, 1),
	(11, 9, 'name product', 10.00, 1),
	(12, 10, 'name product', 10.00, 1),
	(13, 11, 'name product', 10.00, 1),
	(14, 12, 'name product', 10.00, 1),
	(15, 13, 'name product', 10.00, 1),
	(16, 14, 'name product', 10.00, 1),
	(17, 15, 'name product', 10.00, 1),
	(18, 16, 'name product', 10.00, 1),
	(19, 17, 'name product', 10.00, 1),
	(20, 18, 'name product', 10.00, 1),
	(21, 19, 'name product', 10.00, 1),
	(22, 20, 'name product', 10.00, 1),
	(23, 21, 'name product', 10.00, 1),
	(24, 22, 'name product', 10.00, 2),
	(25, 23, 'name product', 10.00, 1),
	(26, 24, 'name product', 10.00, 1),
	(27, 25, 'name product', 10.00, 2),
	(28, 26, 'name product', 10.00, 1),
	(29, 27, 'name product', 10.00, 1),
	(30, 28, 'name product', 10.00, 2),
	(31, 29, 'name product', 10.00, 2),
	(32, 30, 'name product', 10.00, 1),
	(33, 31, 'name product', 10.00, 1),
	(34, 32, 'name product', 10.00, 3),
	(35, 33, 'name product', 10.00, 2),
	(36, 34, 'name product', 10.00, 2),
	(37, 34, 'lol', 30.00, 1),
	(38, 34, 'am', 20.00, 1),
	(39, 35, 'name product', 10.00, 2),
	(40, 36, 'name product', 10.00, 1),
	(41, 37, 'name product', 10.00, 1),
	(42, 38, 'name product', 10.00, 1),
	(43, 39, 'name product', 10.00, 1),
	(44, 40, 'name product', 10.00, 1),
	(45, 41, 'name product', 10.00, 2),
	(46, 42, 'name product', 10.00, 2),
	(47, 43, 'name product', 10.00, 1),
	(48, 44, 'name product', 10.00, 1),
	(49, 45, 'name product', 10.00, 1),
	(50, 46, 'name product', 10.00, 1),
	(51, 47, 'name product', 10.00, 1),
	(52, 48, 'name product', 10.00, 1),
	(53, 49, 'name product', 10.00, 1),
	(54, 50, 'name product', 10.00, 1),
	(55, 51, 'name product', 10.00, 1),
	(56, 52, 'name product', 10.00, 1),
	(57, 53, 'name product', 10.00, 1),
	(58, 54, 'name product', 10.00, 2),
	(59, 54, 'lol', 30.00, 1),
	(60, 54, 'ce produit est à moi', 99.99, 3),
	(61, 55, 'am', 20.00, 3);

-- Listage de la structure de table projet. category
CREATE TABLE IF NOT EXISTS `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projet.category : ~0 rows (environ)
INSERT INTO `category` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'testDescription', 'description details', '2023-10-26 19:52:56', '2023-10-26 19:52:56');

-- Listage de la structure de table projet. doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Listage des données de la table projet.doctrine_migration_versions : ~15 rows (environ)
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20231019031655', '2023-10-19 05:17:08', 62),
	('DoctrineMigrations\\Version20231019044001', '2023-10-19 06:40:11', 87),
	('DoctrineMigrations\\Version20231105160036', '2023-11-05 17:00:47', 69),
	('DoctrineMigrations\\Version20231105225723', '2023-11-06 00:11:20', 50),
	('DoctrineMigrations\\Version20231107105329', '2023-11-07 11:53:43', 227),
	('DoctrineMigrations\\Version20231107120531', '2023-11-07 13:05:40', 33),
	('DoctrineMigrations\\Version20231107132912', '2023-11-07 14:29:19', 54),
	('DoctrineMigrations\\Version20231114222413', '2023-11-14 23:24:44', 202),
	('DoctrineMigrations\\Version20231114222758', '2023-11-14 23:28:10', 42),
	('DoctrineMigrations\\Version20231116173926', '2023-11-16 18:39:38', 121),
	('DoctrineMigrations\\Version20231116183725', '2023-11-16 19:37:36', 23),
	('DoctrineMigrations\\Version20231116193002', '2023-11-16 20:30:19', 16),
	('DoctrineMigrations\\Version20231118185151', '2023-11-18 19:52:02', 59),
	('DoctrineMigrations\\Version20231118190801', '2023-11-18 20:09:30', 16),
	('DoctrineMigrations\\Version20231119173029', '2023-11-19 18:30:34', 202),
	('DoctrineMigrations\\Version20231120220015', '2023-11-20 23:09:12', 173);

-- Listage de la structure de table projet. order
CREATE TABLE IF NOT EXISTS `order` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `tracking_token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_id` int DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F5299398B46BB896` (`tracking_token`),
  KEY `IDX_F5299398A76ED395` (`user_id`),
  KEY `IDX_F5299398F5B7AF75` (`address_id`),
  CONSTRAINT `FK_F5299398A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `FK_F5299398F5B7AF75` FOREIGN KEY (`address_id`) REFERENCES `adress` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=241 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projet.order : ~35 rows (environ)
INSERT INTO `order` (`id`, `user_id`, `total_price`, `status`, `created_at`, `updated_at`, `tracking_token`, `address_id`, `first_name`, `last_name`) VALUES
	(205, 79, 10.00, 'confirmed', '2023-11-20 23:10:27', '2023-11-20 23:11:06', 'aa63b38bfee26570', 107, 'Yaaas', 'AAAKg'),
	(206, 79, 10.00, 'pending', '2023-11-20 23:13:04', '2023-11-20 23:13:04', '11b1ba1e56ed5e37', 108, 'Yaaas', 'AAAKg'),
	(207, 79, 10.00, 'confirmed', '2023-11-20 23:13:44', '2023-11-20 23:14:07', 'aceef83658429f4e', 109, 'Yaaas', 'AAAKg'),
	(208, 79, 10.00, 'pending', '2023-11-20 23:38:55', '2023-11-20 23:38:55', 'fdebab2b80d5709d', 110, 'Yaaas', 'AAAKg'),
	(209, 79, 10.00, 'confirmed', '2023-11-20 23:46:56', '2023-11-20 23:47:22', 'b9373bcbde3be3eb', 111, 'Yaaas', 'AAAKg'),
	(210, 79, 10.00, 'pending', '2023-11-21 00:02:36', '2023-11-21 00:02:36', '6eed21d2df0b5834', 112, 'Yaaas', 'AAAKg'),
	(211, 79, 10.00, 'pending', '2023-11-21 00:03:01', '2023-11-21 00:03:01', '6adc9e926c610039', 113, 'Yaaas', 'AAAKg'),
	(212, 79, 10.00, 'pending', '2023-11-21 00:05:14', '2023-11-21 00:05:14', '721a687b6bdd6115', 114, 'Yaaas', 'AAAKg'),
	(213, 79, 10.00, 'pending', '2023-11-21 00:05:32', '2023-11-21 00:05:32', 'c63a3730d6a1d408', 115, 'Yaaas', 'AAAKg'),
	(214, 79, 10.00, 'pending', '2023-11-21 00:06:01', '2023-11-21 00:06:01', '1659854ca90c60cf', 116, 'Yaaas', 'AAAKg'),
	(215, 79, 10.00, 'pending', '2023-11-21 09:47:21', '2023-11-21 09:47:21', '0729491d5f683982', NULL, NULL, NULL),
	(216, 80, 10.00, 'pending', '2023-11-21 09:51:45', '2023-11-21 09:51:45', 'cb2b3658819da2c1', NULL, NULL, NULL),
	(217, 80, 20.00, 'pending', '2023-11-21 10:48:01', '2023-11-21 10:48:01', '2a1db5d79f848972', 117, NULL, NULL),
	(218, 80, 20.00, 'pending', '2023-11-21 10:56:20', '2023-11-21 10:56:20', 'e4da4de831a2bcea', 117, NULL, NULL),
	(219, 80, 20.00, 'confirmed', '2023-11-21 10:56:46', '2023-11-21 11:07:51', '61756dae39a5f8b5', 118, NULL, NULL),
	(220, 80, 20.00, 'confirmed', '2023-11-21 11:51:31', '2023-11-21 11:51:55', 'dd99e3abde755859', 118, NULL, NULL),
	(221, 80, 10.00, 'confirmed', '2023-11-21 11:53:54', '2023-11-21 11:54:12', 'e8dd84ce7d3f5f9c', 117, NULL, NULL),
	(222, 80, 10.00, 'confirmed', '2023-11-21 12:20:28', '2023-11-21 12:20:59', '1ece9d7a1083e89b', 118, NULL, NULL),
	(223, 80, 10.00, 'confirmed', '2023-11-21 12:22:17', '2023-11-21 12:22:35', 'fcfa4c3717bcce72', 117, NULL, NULL),
	(224, 80, 10.00, 'confirmed', '2023-11-21 12:34:54', '2023-11-21 12:35:12', '3124f53c9cef8b02', 117, NULL, NULL),
	(225, 80, 10.00, 'confirmed', '2023-11-21 12:36:01', '2023-11-21 12:36:19', 'a9aaeb699de83c1e', 118, NULL, NULL),
	(226, 80, 10.00, 'confirmed', '2023-11-21 14:06:10', '2023-11-21 14:06:33', '89aa511babd77268', 117, 'Yasin', 'Akgedik'),
	(227, 80, 10.00, 'confirmed', '2023-11-21 14:13:36', '2023-11-21 14:13:58', '56355061437f80cc', 118, 'Yass', 'Akgedik'),
	(228, 80, 10.00, 'pending', '2023-11-21 14:32:40', '2023-11-21 14:32:40', 'aafe44e19f35f674', 117, 'yass', 'tesst'),
	(229, 80, 10.00, 'confirmed', '2023-11-21 14:32:57', '2023-11-21 14:33:15', '182788bcdab3bc14', 118, 'yass', 'tesst'),
	(230, 80, 10.00, 'confirmed', '2023-11-21 14:34:06', '2023-11-21 14:34:29', 'd69444b7c0e3c614', 117, 'Billing', 'test'),
	(231, 80, 10.00, 'confirmed', '2023-11-21 15:05:13', '2023-11-21 15:05:32', 'd5dde1e50c531eb6', 118, 'delivery name test', 'laozoea'),
	(232, 80, 10.00, 'pending', '2023-11-21 15:21:57', '2023-11-21 15:21:57', '340216ff40663492', 117, 'ezaezae', 'eazezaeza'),
	(233, 80, 10.00, 'pending', '2023-11-21 15:23:31', '2023-11-21 15:23:31', '54bc46bc3063167f', 118, 'ezaezaeza', 'ezaezaez'),
	(234, 80, 10.00, 'pending', '2023-11-21 15:23:50', '2023-11-21 15:23:50', '7ab45e22bd69320e', 117, 'ezaezaeza', 'ezaezaez'),
	(235, 80, 10.00, 'pending', '2023-11-21 15:24:13', '2023-11-21 15:24:13', 'f59b99a861fcf681', 118, 'tatatata', 'ezaezaez'),
	(236, 80, 10.00, 'confirmed', '2023-11-21 15:26:03', '2023-11-21 15:27:00', '7001c723fa87b40b', 118, 'aztaztaztazata', 'azezaeaeazeazeatr'),
	(237, 80, 10.00, 'pending', '2023-11-21 15:39:00', '2023-11-21 15:39:00', '3a1113c423ac166a', 117, 'Yaaas', 'AAAKg'),
	(238, 80, 349.97, 'confirmed', '2023-11-21 15:43:51', '2023-11-21 15:44:09', '8f65d772ea7baf56', 117, 'Yaaas', 'AAAKg'),
	(239, 80, 60.00, 'confirmed', '2023-11-21 15:47:54', '2023-11-21 15:48:11', '103d98bcdc1d7adc', 117, 'Yaaas', 'AAAKg'),
	(240, 80, 60.00, 'pending', '2023-11-21 15:53:56', '2023-11-21 15:53:56', '80c34ea6d6ff3c16', 117, 'Yaaas', 'AAAKg');

-- Listage de la structure de table projet. order_detail
CREATE TABLE IF NOT EXISTS `order_detail` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `product_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_ED896F468D9F6D38` (`order_id`),
  KEY `IDX_ED896F464584665A` (`product_id`),
  CONSTRAINT `FK_ED896F464584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  CONSTRAINT `FK_ED896F468D9F6D38` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=298 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projet.order_detail : ~24 rows (environ)
INSERT INTO `order_detail` (`id`, `order_id`, `quantity`, `price`, `subtotal`, `product_id`) VALUES
	(263, 208, 1, 10.00, 10.00, 1),
	(264, 209, 1, 10.00, 10.00, 1),
	(265, 210, 1, 10.00, 10.00, 1),
	(266, 211, 1, 10.00, 10.00, 1),
	(267, 212, 1, 10.00, 10.00, 1),
	(268, 213, 1, 10.00, 10.00, 1),
	(269, 214, 1, 10.00, 10.00, 1),
	(270, 215, 1, 10.00, 10.00, 1),
	(271, 216, 1, 10.00, 10.00, 1),
	(272, 217, 2, 10.00, 20.00, 1),
	(273, 218, 2, 10.00, 20.00, 1),
	(274, 219, 2, 10.00, 20.00, 1),
	(275, 220, 2, 10.00, 20.00, 1),
	(276, 221, 1, 10.00, 10.00, 1),
	(277, 222, 1, 10.00, 10.00, 1),
	(278, 223, 1, 10.00, 10.00, 1),
	(279, 224, 1, 10.00, 10.00, 1),
	(280, 225, 1, 10.00, 10.00, 1),
	(281, 226, 1, 10.00, 10.00, 1),
	(282, 227, 1, 10.00, 10.00, 1),
	(283, 228, 1, 10.00, 10.00, 1),
	(284, 229, 1, 10.00, 10.00, 1),
	(285, 230, 1, 10.00, 10.00, 1),
	(286, 231, 1, 10.00, 10.00, 1),
	(287, 232, 1, 10.00, 10.00, 1),
	(288, 233, 1, 10.00, 10.00, 1),
	(289, 234, 1, 10.00, 10.00, 1),
	(290, 235, 1, 10.00, 10.00, 1),
	(291, 236, 1, 10.00, 10.00, 1),
	(292, 237, 1, 10.00, 10.00, 1),
	(293, 238, 3, 99.99, 299.97, 24),
	(294, 238, 1, 30.00, 30.00, 2),
	(295, 238, 2, 10.00, 20.00, 1),
	(296, 239, 3, 20.00, 60.00, 3),
	(297, 240, 3, 20.00, 60.00, 3);

-- Listage de la structure de table projet. order_tracking
CREATE TABLE IF NOT EXISTS `order_tracking` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_498480AD8D9F6D38` (`order_id`),
  CONSTRAINT `FK_498480AD8D9F6D38` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projet.order_tracking : ~0 rows (environ)
INSERT INTO `order_tracking` (`id`, `order_id`, `status`, `updated_at`) VALUES
	(9, 209, 'Payment Confirmed', '2023-11-20 23:47:22'),
	(10, 219, 'Payment Confirmed', '2023-11-21 11:07:51'),
	(11, 220, 'Payment Confirmed', '2023-11-21 11:51:55'),
	(12, 221, 'Payment Confirmed', '2023-11-21 11:54:12'),
	(13, 222, 'Payment Confirmed', '2023-11-21 12:20:59'),
	(14, 223, 'Payment Confirmed', '2023-11-21 12:22:35'),
	(15, 224, 'Payment Confirmed', '2023-11-21 12:35:12'),
	(16, 225, 'Payment Confirmed', '2023-11-21 12:36:19'),
	(17, 226, 'Payment Confirmed', '2023-11-21 14:06:33'),
	(18, 227, 'Payment Confirmed', '2023-11-21 14:13:58'),
	(19, 229, 'Payment Confirmed', '2023-11-21 14:33:15'),
	(20, 230, 'Payment Confirmed', '2023-11-21 14:34:29'),
	(21, 231, 'Payment Confirmed', '2023-11-21 15:05:32'),
	(22, 236, 'Payment Confirmed', '2023-11-21 15:27:00'),
	(23, 238, 'Payment Confirmed', '2023-11-21 15:44:09'),
	(24, 239, 'Payment Confirmed', '2023-11-21 15:48:11');

-- Listage de la structure de table projet. payment
CREATE TABLE IF NOT EXISTS `payment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_6D28840D8D9F6D38` (`order_id`),
  CONSTRAINT `FK_6D28840D8D9F6D38` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projet.payment : ~13 rows (environ)
INSERT INTO `payment` (`id`, `order_id`, `transaction_id`, `amount`, `payment_method`, `status`, `created_at`) VALUES
	(101, 209, 'ch_3OEgFREONNRmxI8s0iu7rBSx', 10.00, 'carte de crédit', 'succeeded', '2023-11-20 23:47:22'),
	(102, 219, 'ch_3OEqrwEONNRmxI8s1syRku9m', 20.00, 'carte de crédit', 'succeeded', '2023-11-21 11:07:51'),
	(103, 220, 'ch_3OErYcEONNRmxI8s15fmlR92', 20.00, 'carte de crédit', 'succeeded', '2023-11-21 11:51:55'),
	(104, 221, 'ch_3OErapEONNRmxI8s1gmQdu7U', 10.00, 'carte de crédit', 'succeeded', '2023-11-21 11:54:12'),
	(105, 222, 'ch_3OEs0jEONNRmxI8s08ao8Vps', 10.00, 'carte de crédit', 'succeeded', '2023-11-21 12:20:58'),
	(106, 223, 'ch_3OEs2IEONNRmxI8s0EBv1ij8', 10.00, 'carte de crédit', 'succeeded', '2023-11-21 12:22:35'),
	(107, 224, 'ch_3OEsEUEONNRmxI8s0p1WtBHm', 10.00, 'carte de crédit', 'succeeded', '2023-11-21 12:35:12'),
	(108, 225, 'ch_3OEsFaEONNRmxI8s04Q2VaLu', 10.00, 'carte de crédit', 'succeeded', '2023-11-21 12:36:19'),
	(109, 226, 'ch_3OEtetEONNRmxI8s0hkNF5xX', 10.00, 'carte de crédit', 'succeeded', '2023-11-21 14:06:33'),
	(110, 227, 'ch_3OEtm5EONNRmxI8s1U3VPiYu', 10.00, 'carte de crédit', 'succeeded', '2023-11-21 14:13:58'),
	(111, 229, 'ch_3OEu4kEONNRmxI8s14LOoMgj', 10.00, 'carte de crédit', 'succeeded', '2023-11-21 14:33:15'),
	(112, 230, 'ch_3OEu5wEONNRmxI8s1tPYIjhq', 10.00, 'carte de crédit', 'succeeded', '2023-11-21 14:34:29'),
	(113, 231, 'ch_3OEuZzEONNRmxI8s0ZfTDWmg', 10.00, 'carte de crédit', 'succeeded', '2023-11-21 15:05:32'),
	(114, 236, 'ch_3OEuukEONNRmxI8s10V5RT0K', 10.00, 'carte de crédit', 'succeeded', '2023-11-21 15:27:00'),
	(115, 238, 'ch_3OEvBMEONNRmxI8s1Q4ePPVo', 349.97, 'carte de crédit', 'succeeded', '2023-11-21 15:44:09'),
	(116, 239, 'ch_3OEvFGEONNRmxI8s1N5eFyVR', 60.00, 'carte de crédit', 'succeeded', '2023-11-21 15:48:11');

-- Listage de la structure de table projet. product
CREATE TABLE IF NOT EXISTS `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `category_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D34A04AD5E237E06` (`name`),
  KEY `IDX_D34A04ADA76ED395` (`user_id`),
  KEY `IDX_D34A04AD12469DE2` (`category_id`),
  CONSTRAINT `FK_D34A04AD12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `FK_D34A04ADA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projet.product : ~19 rows (environ)
INSERT INTO `product` (`id`, `user_id`, `category_id`, `name`, `description`, `price`, `stock_quantity`, `is_active`, `image_url`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 'name product', 'description product', 10.00, 410, 1, 'default.image.jpg', '2023-10-26 19:53:48', '2023-11-21 15:44:09'),
	(2, 1, 1, 'lol', 'maaaaa', 30.00, 8, 1, 'default.image.jpg', '2023-10-26 20:19:38', '2023-11-21 15:44:09'),
	(3, 1, 1, 'am', 'nana', 20.00, 3, 1, 'default.image.jpg', '2023-10-26 22:47:04', '2023-11-21 15:53:56'),
	(4, 1, 1, 'ta', 'ze', 20.00, 1, 1, 'default.image.jpg', '2023-10-26 22:47:04', '2023-11-14 12:32:59'),
	(5, 1, 1, 'tata', 'ze', 20.00, 0, 1, 'default.image.jpg', '2023-10-26 22:47:04', '2023-11-14 13:41:06'),
	(6, 1, 1, 'tatata', 'ze', 20.00, 2, 1, 'default.image.jpg', '2023-10-26 22:47:04', '2023-11-12 23:39:06'),
	(7, 1, 1, 'tatatata', 'ze', 20.00, 2, 1, 'default.image.jpg', '2023-10-26 22:47:04', '2023-11-07 21:26:07'),
	(8, 1, 1, 'ta1', 'ze', 20.00, 1, 1, 'default.image.jpg', '2023-10-26 22:47:04', '2023-11-14 13:41:06'),
	(9, 1, 1, 'ta12', 'ze', 20.00, 2, 1, 'default.image.jpg', '2023-10-26 22:47:04', '2023-11-12 23:55:19'),
	(10, 1, 1, 'ta123', 'ze', 20.00, 2, 1, 'default.image.jpg', '2023-10-26 22:47:04', '2023-10-26 22:47:06'),
	(15, 62, 1, 'my product edit', 'my producct', 1.00, 1, 1, NULL, '2023-11-08 04:40:22', '2023-11-08 05:14:35'),
	(16, 62, 1, 'mon produit 2', 'produit de luxe 2', 20.00, 2, 1, NULL, '2023-11-08 04:58:50', '2023-11-13 00:52:22'),
	(17, 62, 1, 'azezaezza', 'ezaeza', 10.00, 1, 1, NULL, '2023-11-08 04:59:22', '2023-11-08 04:59:22'),
	(20, 61, 1, 'mon produit sécurisée', 'produit sécurisé', 10.00, 1, 1, NULL, '2023-11-09 09:22:28', '2023-11-12 21:38:03'),
	(21, 61, 1, 'add TEST', 'azoejkoze', 10.00, 1, 1, NULL, '2023-11-09 21:14:10', '2023-11-09 21:14:10'),
	(22, 71, 1, 'Produit de test', 'Ceci est une description du produit', 299.99, 24, 1, NULL, '2023-11-14 13:18:15', '2023-11-16 20:36:49'),
	(23, 72, 1, 'test du produit', 'description du produit', 99.99, 50, 1, NULL, '2023-11-14 13:35:05', '2023-11-14 13:35:05'),
	(24, 77, 1, 'ce produit est à moi', 'description', 99.99, 7, 1, NULL, '2023-11-16 11:35:07', '2023-11-21 15:44:09'),
	(25, 80, 1, 'test final', 'description test final', 25.55, 28, 1, NULL, '2023-11-21 14:18:19', '2023-11-21 14:18:19');

-- Listage de la structure de table projet. review
CREATE TABLE IF NOT EXISTS `review` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` int NOT NULL,
  `comment` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_794381C64584665A` (`product_id`),
  KEY `IDX_794381C6A76ED395` (`user_id`),
  CONSTRAINT `FK_794381C64584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  CONSTRAINT `FK_794381C6A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projet.review : ~0 rows (environ)

-- Listage de la structure de table projet. user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `profile_picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `default_billing_address_id` int DEFAULT NULL,
  `default_delivery_address_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8D93D6491995CE08` (`default_billing_address_id`),
  KEY `IDX_8D93D649422ED30C` (`default_delivery_address_id`),
  CONSTRAINT `FK_8D93D6491995CE08` FOREIGN KEY (`default_billing_address_id`) REFERENCES `adress` (`id`),
  CONSTRAINT `FK_8D93D649422ED30C` FOREIGN KEY (`default_delivery_address_id`) REFERENCES `adress` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projet.user : ~73 rows (environ)
INSERT INTO `user` (`id`, `username`, `password`, `email`, `role`, `last_login`, `profile_picture`, `created_at`, `updated_at`, `default_billing_address_id`, `default_delivery_address_id`) VALUES
	(1, 'test', 'test', 'test@gmail.com', 'artisan', '2023-10-26 19:52:20', NULL, '2023-10-26 19:52:27', '2023-10-26 19:52:28', NULL, NULL),
	(2, 'test2', '$2y$04$KqV8VeUKXpaE0ed1tjstrOAHPy2rE3dbDm.WRBnJV4LxI12oaFigi', 'test2@gmail.com', 'artisan', NULL, NULL, '2023-10-28 00:31:50', '2023-10-28 00:31:50', NULL, NULL),
	(3, 'test2', '$2y$04$5D092agu2OwviecglGIUsuGfeNZX9S3gO7p9u1Xa0cjafoXoY9mN2', 'test2@gmail.com', 'artisan', NULL, NULL, '2023-10-28 00:54:42', '2023-10-28 00:54:42', NULL, NULL),
	(4, 'test3', '$2y$04$rBBsBvdzfce8keK6wB1KV.69dianve5pGEzVuzOYzvobrzpl4LiCe', 'test3@gmail.com', 'artisan', NULL, NULL, '2023-10-28 00:55:06', '2023-10-28 00:55:06', NULL, NULL),
	(5, 'test4', '$2y$04$gZ.m8hLowM0a5X4K8I3av.ZY.RH9VzrGzEsmoLnU/O9UaACpjoBFS', 'test4@gmail.com', 'artisan', NULL, NULL, '2023-10-28 01:00:40', '2023-10-28 01:00:40', NULL, NULL),
	(6, 'test5', '$2y$04$ckiE7zP9xB5S4vCYUqKh.eIGD6ZqRihkHWT41LQd0I8s2CIuNP8o2', 'test5@gmail.com', 'artisan', NULL, NULL, '2023-10-28 01:04:27', '2023-10-28 01:04:27', NULL, NULL),
	(7, 'test12', '$2y$04$qaehUARpeRs35bZrJlOH9.cJjKKtkY1trk6Zi6fdz8Unv7PyyLK7q', 'test12@gmail.com', 'artisan', NULL, NULL, '2023-10-28 01:11:04', '2023-10-28 01:11:04', NULL, NULL),
	(8, 'test15', '$2y$04$VKzu3Tbb/DCWfddEWmS4I.ioyM4J41zXAeCrrSFKIE7F.WKUuMmtO', 'test15@gmail.com', 'artisan', NULL, NULL, '2023-10-28 01:17:50', '2023-10-28 01:17:50', NULL, NULL),
	(9, 'azeazeaze', '$2y$04$nwUQxL4lHmbf.otWZv1jD.DUo930QW9Vu9Q20q2K8MJezYO3nNTt6', 'test16@gmail.com', 'artisan', NULL, NULL, '2023-10-28 01:18:40', '2023-10-28 01:18:40', NULL, NULL),
	(10, 'test17', '$2y$04$HYbohko41TWXnlagIk7zo.VPtn.qG7jvWxsutkaCvrbwVE/VnmDMG', 'test17@gmail.com', 'artisan', NULL, NULL, '2023-10-28 01:33:32', '2023-10-28 01:33:32', NULL, NULL),
	(11, 'test18', '$2y$04$HN3WFnVX9W/uil1Mt4edvOlec91T7KYQIXwPQ858fvQb8RojkYX5W', 'test18@gmail.com', 'artisan', NULL, NULL, '2023-10-28 01:35:00', '2023-10-28 01:35:00', NULL, NULL),
	(12, 'test18', '$2y$04$343KHR8DkZhrTpZAjHyOCeyoQYFYYbYKV5sUHDWnFne.tZpNwxrmm', 'test18@gmail.com', 'artisan', NULL, NULL, '2023-10-28 01:49:14', '2023-10-28 01:49:14', NULL, NULL),
	(13, 'client', '$2y$04$NGTML8HmPibhZiDhH7l0uO6Nt5.nJtHHoa2M6zdE7oG8yQVZF0Ng2', 'client@gmail.com', 'client', NULL, NULL, '2023-11-05 12:37:28', '2023-11-05 12:37:28', NULL, NULL),
	(15, 'test101', '$2y$04$DnRvLKTDN4w0VJHJ/9FhjOzUi.GXwtppfZLPfODBM.Pii5squhGzu', 'teazkeo@gmail.com', 'artisan', NULL, NULL, '2023-11-05 14:16:37', '2023-11-05 14:16:37', NULL, NULL),
	(17, 'oakesdfoak', '$2y$04$Kj6slfTGaMCWSeLRpefEcOC73yI.qYRbJ0zsuZcolbf9zBd5bm9He', 'aozkeajijzefra@gmail.com', 'artisan', NULL, NULL, '2023-11-05 14:33:33', '2023-11-05 14:33:33', NULL, NULL),
	(18, 'test2aizokej', '$2y$04$O/qnsY/Ydxmf3R5s17lQC.5T8M2tl/yf9wv8nHrvnPQlqItnZAHr2', 'azekjaziojfa@gmail.com', 'artisan', NULL, NULL, '2023-11-05 14:39:19', '2023-11-05 14:39:19', NULL, NULL),
	(20, 'jean2', '$2y$04$TErmNKhNzKj.8h1mKwvuHeRmjs/8rLs2wp92GhFR/u/2pfyfdmPni', 'jean2@gmail.com', 'artisan', NULL, NULL, '2023-11-05 17:16:48', '2023-11-05 17:16:48', NULL, NULL),
	(21, 'Jean3', '$2y$04$wV7Nm17IpUl3Opb3Mqry7OX7ZaHZH5IaSKFHwpjZigV7YE/bGKFvi', 'jean3@gmail.com', 'craftsman', NULL, NULL, '2023-11-05 17:42:18', '2023-11-05 17:42:18', NULL, NULL),
	(26, 'jean20', '$2y$04$5fxrWos7H8c5PCnRsKmyEO0a8F7BOXcWck.NzEGTjR9d4Oz0jD.vK', 'jean20@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 00:11:49', '2023-11-06 00:11:49', NULL, NULL),
	(27, 'jean21', '$2y$04$4XFDEix4b8YD5eEnmCav/.l8wntinuNSeh6jY3IOTxoTEh7m9m1u.', 'jean21@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 00:12:12', '2023-11-06 00:12:12', NULL, NULL),
	(28, 'jean25', '$2y$04$4x8HqMKD4GXrGwfP5LGuv.HMQ6DpLJgfPsVp0dzANWoCYFcviffWC', 'jean25@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 00:20:06', '2023-11-06 00:20:06', NULL, NULL),
	(29, 'a5ze1', '$2y$04$N4DyJmRmBFrDYY.aTGMFw.Qw/AVBBfBIedZNwJKLvJWy1GV1FIvIK', 'zekdjaiok@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 00:21:19', '2023-11-06 00:21:19', NULL, NULL),
	(30, 'tutu@gmail.com', '$2y$04$1PIT2H9sDIOXON81gdI0beP3FecpmamV0IugCQx7n3ShLD3sWHz1e', 'tutu@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 00:22:01', '2023-11-06 00:22:01', NULL, NULL),
	(31, 'tata', '$2y$04$pucIbigHg2/kos7LOikqqOO1wZF5lgEeostOGc0TaTM/5NBA5phLG', 'tata@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 00:22:47', '2023-11-06 00:22:47', NULL, NULL),
	(32, 'titi', '$2y$04$TaAndWwCxtDlxJwsPWO8a.iNT7321kJ1VbhRsVFS5Vs3ILWYFbHC.', 'titi@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 00:23:58', '2023-11-06 00:23:58', NULL, NULL),
	(33, 'yas', '$2y$04$MY6G6pmjakGm.VRv80LVTuvHKZFS7Fu9jTVL/hrpq0vnTIpKR6o3y', 'yas@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 00:25:31', '2023-11-06 00:25:31', NULL, NULL),
	(34, 'last', '$2y$04$3cW9/ytvHAK7v7h7fd7nr.gz5.gGAPKUUG6S67MbKgKld53LIUHMW', 'last@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 00:39:10', '2023-11-06 00:39:10', NULL, NULL),
	(35, 'lol1', '$2y$04$QNugZ4gljAWUp83lOCtWwOHBXZWp1OBtGnD9Rm2PFWVZMqh2Xmclq', 'lol1@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 00:42:06', '2023-11-06 00:42:06', NULL, NULL),
	(36, 'lol2', '$2y$04$VPQ96Qecr10ANLoNRnT0LedaD5yUmp3Drg0nzkaxWQz0vR1P6ZnY2', 'lol2@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 00:44:04', '2023-11-06 00:44:04', NULL, NULL),
	(37, 'lolol', '$2y$04$W9d50/hYlRqt/7bHPG60G.5nqgW2t1QzJsV92dZjFB2XcGi6BycKS', 'lolol@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:01:13', '2023-11-06 01:01:13', NULL, NULL),
	(38, 'lol15', '$2y$04$DrxM0.pRXXvfkur38g141u6jy0LKXxnPa8NfYr8xkqHszGHcJtCBi', 'lol15@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:02:25', '2023-11-06 01:02:25', NULL, NULL),
	(39, 'mama1', '$2y$04$3Yw2sjAdbqeFOpIuY15uve4EygzobmUX9/umjq638hCaOyNXQ5POO', 'mama1@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:28:02', '2023-11-06 01:28:02', NULL, NULL),
	(40, 'xxx1', '$2y$04$qqx4n14kKtA0fw.QSD5r..mbo2.6ErcaLOzvgVz4bDspw6i3grYC.', 'xxx1@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:28:29', '2023-11-06 01:28:29', NULL, NULL),
	(41, 'tata2', '$2y$04$kD6ZAwu5IGTGfdNv9xdKNecw9TlAKVtB.59eMRYFCAtELu95mo1.O', 'tata2@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:29:29', '2023-11-06 01:29:29', NULL, NULL),
	(42, 'tata3', '$2y$04$DvoygYWQ3WByN.dUzzadwe6mFU5uOz8.im84Zx7Qcfj4RtsbrTDp2', 'tata3@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:30:12', '2023-11-06 01:30:12', NULL, NULL),
	(43, 'tata4', '$2y$04$2kxPzh0m50yj6vy8wTb77u5Ce1BqCKApt.Oq5piiSlnWBtJWrzNRq', 'tata4@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:30:54', '2023-11-06 01:30:54', NULL, NULL),
	(44, 'izaej', '$2y$04$DL7uaRND/laXtBy7BxxLj.5Pk6qs464oIxo5xBLDW0DkcSQ86H9uC', 'azieja@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:32:32', '2023-11-06 01:32:32', NULL, NULL),
	(45, 'loleur', '$2y$04$fnQtZDumW9WnUQkBWAmdzOR7cniL6S9v7O6hVC3px5UC74NeIPrla', 'loleur@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:33:42', '2023-11-06 01:33:42', NULL, NULL),
	(46, 'lolad', '$2y$04$jEm0GoUeiXUHQBSkORgkpewCh.PrRxfWa9/RCjgsNXoh9IZUz/B7S', 'lolad@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:34:12', '2023-11-06 01:34:12', NULL, NULL),
	(47, 'lolad1', '$2y$04$YEcTg8FWK/K5MUjZOXY5NujSmMzDcWhJbWUqVP5oTKYv7dnJ7VIz.', 'lolad1@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:34:51', '2023-11-06 01:34:51', NULL, NULL),
	(48, 'test181', '$2y$04$nzlglRMt3qekIyi3zaMlSug6n34xEdykSSWXOaK5sxJKQVWMn/OQu', 'test181@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:35:18', '2023-11-06 01:35:18', NULL, NULL),
	(49, 'test2', '$2y$04$NPQ/7QxAzEkPDI5C8sM/weQ7itkwxYR19QGVIEDpvwRCVyrbcO3Ea', 'azejaiej@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:35:45', '2023-11-06 01:35:45', NULL, NULL),
	(50, 'te54r', '$2y$04$9TVwWe.0ElJwCKjQFdeX6OjZod2MVo2nbsJSbhz9jsh75BTw0LeCO', 'azpkagjiao@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:36:55', '2023-11-06 01:36:55', NULL, NULL),
	(51, 'iajzifajfi', '$2y$04$WyVwBkibcPiNcMTYUs5Kj.ifKNgpYGV3u6wNdW0iaSd2Zvnuy1KoW', 'gaokzejagmail@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:39:48', '2023-11-06 01:39:48', NULL, NULL),
	(52, 'teaijezai', '$2y$04$WfZyIJP4B4C/V.PNo575ou4hCREQxh.zMlJvU5axOC15U2vjIAZ76', 'aijzifajijaiejga@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:47:15', '2023-11-06 01:47:15', NULL, NULL),
	(53, 'aozeazoe', '$2y$04$7hLyTUaU93BnTrGxcsAzdOXcBjpXa9kl15vlS/QiqXl6Dg5A/PgSC', 'aozkeoazkeoa@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:48:08', '2023-11-06 01:48:08', NULL, NULL),
	(54, 'test2azea', '$2y$04$EkRNcrL5FRy3z1JdOLU1GuLguEJyeViQHtYCHeq.J4YILK1meMm1u', 'azezatavdza@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:49:17', '2023-11-06 01:49:17', NULL, NULL),
	(55, 'oazkeoae', '$2y$04$8aiXpMilPmHOx72o/4hJ/e.su71gxWy84mHt1w9PhhW1r1r2HoVki', 'ajzkaoikzpeaepa@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:49:49', '2023-11-06 01:49:49', NULL, NULL),
	(56, 'test2zzz', '$2y$04$BqpfDUJksGRHVSM5y7BPy./SA6CZHjdsZ7Hw1a7k/FLJJSW1t0J9e', 'zezaeazeza@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:50:20', '2023-11-06 01:50:20', NULL, NULL),
	(57, 'azazzazaa', '$2y$04$tiYUJdzZNVZEwS4LTfuOoe7ifFx8gRKJv7BTTls/YOGAvFqXhU132', 'azaazaa@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:51:23', '2023-11-06 01:51:23', NULL, NULL),
	(58, 'leroi', '$2y$04$1T2bVI2hh/aggIEYZAe.2uChYEH74TVi8zraWKsn.Dr3rK7tgJNBC', 'leroi@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:52:57', '2023-11-06 01:52:57', NULL, NULL),
	(59, 'lasttest', '$2y$04$ezllgX2WsBfIxUOWo.9UyebyISfpvmk64Xxy/A1a4tBUpzZJu5uvC', 'lasttest@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:57:07', '2023-11-06 01:57:07', NULL, NULL),
	(60, 'pet', '$2y$04$fYJCepC0KnLq2muW0Rob1u0dunpEziwijdgUx7V.J9VYHZagU8VMO', 'pet@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 02:00:43', '2023-11-07 18:23:03', NULL, NULL),
	(61, 'Kazae', '$2y$04$iZDrlaCCfsjbMiN2MwvEAOs5Wwb/rUp4imXIFb97xhE7lkPUDkBnW', 'Kazae@gmail.com', 'craftsman', NULL, NULL, '2023-11-07 22:10:28', '2023-11-09 07:23:55', NULL, NULL),
	(62, 'Kazae2', '$2y$04$FZOoxE9F4VcJ4w1ejt.wZeo6xaR7MexEpFbiDJgiiUz4pj9bEU4p.', 'Kazae2@gmail.com', 'craftsman', NULL, NULL, '2023-11-08 04:39:37', '2023-11-08 04:39:37', NULL, NULL),
	(63, 'Kazae3', '$2y$04$6Hy1zwaVAm6NPulGYWPygOy8I15uxxm1nQ2Pgmbv53QZO/heVhQwu', 'Kazae3@gmail.com', 'customer', NULL, NULL, '2023-11-09 08:39:12', '2023-11-09 08:39:12', NULL, NULL),
	(64, 'Kaztest', '$2y$04$MbJqJ4rTUUzV2RvlAiPb8e4gzQsJHbTaavoA4dT8GBpOG760oZTUG', 'Kaztest@gmail.com', 'craftsman', NULL, NULL, '2023-11-12 18:39:05', '2023-11-12 18:39:05', NULL, NULL),
	(65, 'testachat', '$2y$04$v6BvZzMZLUqL2AWmKnWSl.k0gL1PcZ1I/p.5TC/7b4lIWTeNeCYwm', 'testachat@gmail.com', 'craftsman', NULL, NULL, '2023-11-12 21:35:10', '2023-11-12 21:35:10', NULL, NULL),
	(66, 'Lareine', '$2y$04$lk5LN0UdRa3zSaMC5txxaOx.mwtZBNpCDRj2RVRYQfw7.wGUFWzxi', 'Lareine@gmail.com', 'craftsman', NULL, NULL, '2023-11-13 13:29:10', '2023-11-13 13:29:10', NULL, NULL),
	(67, 'adress', '$2y$04$5fYKGF8b9YuiCR9Svkf7JOEoCqlib2HNN4t0HEVFf5ShAkPJ6rn/G', 'adress@gmail.com', 'craftsman', NULL, NULL, '2023-11-13 19:03:27', '2023-11-13 19:03:27', NULL, NULL),
	(68, 'Nouveau', '$2y$04$0CT.Taiue2IcKTPoJ1Lbie.XgNsHxo1fMF/.Php5X.eD.CaZxj1MO', 'Nouveau@gmail.com', 'craftsman', NULL, NULL, '2023-11-13 19:06:54', '2023-11-13 19:25:03', NULL, NULL),
	(69, 'az123', '$2y$04$IV6LZHLMzlzldx0gIslzIumhp1TrFcTdDBoMVIrZ4UpELEm9qXm..', 'az123@gmail.com', 'craftsman', NULL, NULL, '2023-11-14 08:38:47', '2023-11-14 08:38:47', NULL, NULL),
	(70, 'titi1', '$2y$04$kukIKvrbzuB6UMEDXhwUuu1BbY6xMVsZeJBmKfn/m7rP03xtRWywW', 'titi1@gmail.com', 'craftsman', NULL, NULL, '2023-11-14 08:43:34', '2023-11-14 08:43:34', NULL, NULL),
	(71, 'Testfinal', '$2y$04$/TOESQSGvu4dJLlRnlBUweNoj9Zmz2NHgItbbCFYPekWsQDUZzo6S', 'Testfinal@gmail.com', 'craftsman', NULL, NULL, '2023-11-14 13:14:01', '2023-11-14 13:16:59', NULL, NULL),
	(72, 'test500', '$2y$04$hfVxgTGQ3ajTbMmb6MMsPuaBuUsu.g2J9uN0OsiUlpHsOpgqOmXV2', 'test500@gmail.com', 'craftsman', NULL, NULL, '2023-11-14 13:33:44', '2023-11-14 13:33:44', NULL, NULL),
	(73, 'test400', '$2y$04$TI2BPHJqF/6lsOtw2pQZzOHIES8fLK5GllPXTpdQTgfav78o9fxOK', 'test400@gmail.com', 'craftsman', NULL, NULL, '2023-11-14 16:41:59', '2023-11-14 16:41:59', NULL, NULL),
	(74, 'test100', '$2y$04$JkWRjmz7eZSB0ln16K3CUumzZhqtf3651tr/vU681R8SBYnlACcYW', 'test100@gmail.com', 'craftsman', NULL, NULL, '2023-11-14 18:12:31', '2023-11-14 18:12:31', NULL, NULL),
	(75, 'test144', '$2y$04$Fh2ud.evk8kgg.l.9rfzze07tKzPK0jsi6YiRYa6W/uX1tzsO3nMm', 'test144@gmail.com', 'craftsman', NULL, NULL, '2023-11-14 19:58:36', '2023-11-14 19:58:36', NULL, NULL),
	(76, 'Arnu', '$2y$04$NHAxZUQuTm2HWPBfK4xg1OKVwi9uLMsRcdSl3M5y9MeWqls4UGzAS', 'Arnu@gmail.com', 'craftsman', NULL, NULL, '2023-11-14 21:51:43', '2023-11-14 21:51:43', NULL, NULL),
	(77, 'Kaz15', '$2y$04$LFTxkXAoTEugSdnXHlhVIeomP29KUGqtSfAo3S6tf6AFG8LFrYICm', 'Kaz15@gmail.com', 'craftsman', NULL, NULL, '2023-11-16 11:25:30', '2023-11-16 11:25:30', NULL, NULL),
	(78, 'Login1', '$2y$04$zPM/yYv.MQSuCsfXHhaEYOby1rJLpp7.qDZhJB.kKTt7TU3J6/G/y', 'Login1@gmail.com', 'craftsman', NULL, NULL, '2023-11-16 22:50:31', '2023-11-16 22:50:31', NULL, NULL),
	(79, 'Kaz67', '$2y$04$xGKPai/p4gWS8KOQtLzuTuvexTf0/SY3DGtebd6JEvsYVghsGcy4S', 'Kaz67@gmail.com', 'craftsman', '2023-11-19 22:51:56', NULL, '2023-11-19 19:06:59', '2023-11-19 19:06:59', NULL, NULL),
	(80, 'Kaz2', '$2y$04$/nyqxzxYPvODLC1dBJohbepJSVKNPe0.3tNJjfcGH3WyKHSosuYb2', 'Kaz2@gmail.com', 'craftsman', NULL, NULL, '2023-11-21 09:48:14', '2023-11-21 10:46:09', 117, 118);

-- Listage de la structure de table projet. wishlist
CREATE TABLE IF NOT EXISTS `wishlist` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `added_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_9CE12A31A76ED395` (`user_id`),
  KEY `IDX_9CE12A314584665A` (`product_id`),
  CONSTRAINT `FK_9CE12A314584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  CONSTRAINT `FK_9CE12A31A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projet.wishlist : ~3 rows (environ)
INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `added_at`) VALUES
	(10, 70, 1, '2023-11-14 13:13:01'),
	(17, 77, 1, '2023-11-16 12:34:16');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
