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
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default_billing` tinyint(1) NOT NULL,
  `is_default_delivery` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5CECC7BEA76ED395` (`user_id`),
  CONSTRAINT `FK_5CECC7BEA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projet.adress : ~67 rows (environ)
INSERT INTO `adress` (`id`, `user_id`, `type`, `street`, `city`, `state`, `postal_code`, `country`, `is_default_billing`, `is_default_delivery`) VALUES
	(1, 17, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(2, 18, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(3, 18, 'billing', 'testaze', 'testaze', 'azeazeaze', '68200', 'FR', 0, 0),
	(4, 20, 'delivery', 'street', 'city', 'state', '67100', 'US', 1, 0),
	(5, 21, 'delivery', 'street', 'city', 'state', '67100', 'FR', 1, 0),
	(6, 21, 'billing', 'street2', 'city2', 'state', '67100', 'FR', 1, 0),
	(7, 26, 'delivery', 'street', 'City', 'state', '67100', 'FR', 0, 0),
	(8, 27, 'delivery', 'street', 'city', 'state', '67100', 'FR', 0, 0),
	(9, 28, 'delivery', 'street1', 'city1', 'state', '67100', 'FR', 0, 0),
	(10, 28, 'delivery', 'street2', 'city2', 'state2', '67100', 'FR', 0, 0),
	(11, 28, 'billing', 'street3', 'city3', 'state3', '67100', 'FR', 0, 0),
	(12, 28, 'billing', 'street4', 'city4', 'state4', '67100', 'FR', 0, 0),
	(13, 30, 'billing', 'street', 'city', 'state', '67100', 'FR', 0, 0),
	(14, 31, 'billing', 'street', 'city', 'state', '67100', 'FR', 0, 0),
	(15, 32, 'delivery', 'street', 'city', 'state', '67100', 'FR', 0, 0),
	(16, 32, 'billing', 'street', 'street', 'street', '67100', 'FR', 0, 0),
	(17, 33, 'delivery', 'street', 'street', 'street', '67100', 'FR', 1, 0),
	(18, 33, 'delivery', 'street', 'street', 'street', '67100', 'FR', 1, 0),
	(19, 34, 'delivery', 'street', 'city', 'state', '67100', 'FR', 1, 1),
	(20, 34, 'delivery', 'street', 'street', 'state', '67100', 'FR', 0, 0),
	(21, 35, 'delivery', 'street', 'street', 'street', '67100', 'FR', 1, 1),
	(22, 36, 'delivery', 'street', 'street', 'street', '67100', 'FR', 1, 1),
	(23, 37, 'billing', 'azeazeaz', 'ezaezae', 'azeaze', '67100', 'FR', 0, 0),
	(24, 38, 'billing', 'street', 'city', 'state', '67100', 'FR', 0, 0),
	(25, 39, 'delivery', 'street', 'city', 'state', '67100', 'FR', 0, 0),
	(26, 39, 'delivery', 'street', 'city', 'state', '67100', 'FR', 0, 0),
	(27, 40, 'delivery', 'tatat', 'tata', 'tata', '67100', 'FR', 0, 0),
	(28, 41, 'billing', 'tata', 'tata', 'tata', '67100', 'FR', 0, 0),
	(29, 42, 'delivery', 'tata', 'tata', 'tatat', '67100', 'FR', 0, 0),
	(30, 43, 'delivery', 'tata', 'tata', 'tata', 'tata', 'FR', 1, 1),
	(31, 44, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 1, 1),
	(32, 44, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(33, 45, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 1, 1),
	(34, 45, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(35, 46, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 1, 1),
	(36, 46, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(37, 47, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 1, 1),
	(38, 47, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(39, 48, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 1, 1),
	(40, 49, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 1, 1),
	(41, 50, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 1, 1),
	(42, 50, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(43, 50, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(44, 50, 'billing', 'test', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(45, 50, 'billing', 'test', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(46, 51, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(47, 51, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(48, 51, 'billing', 'test', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(49, 52, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 1, 1),
	(50, 52, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(51, 53, 'billing', 'test', 'test', 'azeaze', '67100', 'FR', 1, 1),
	(52, 53, 'delivery', 'azezaeza', 'azeaze', 'ezaeaz', '67100', 'FR', 0, 0),
	(53, 54, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(54, 55, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(55, 55, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(56, 56, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 1, 1),
	(57, 56, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(58, 57, 'delivery', 'test', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(59, 57, 'billing', 'test', 'test', 'azeaze', '67100', 'FR', 1, 0),
	(60, 58, 'delivery', 'coché', 'city', 'state', '67100', 'FR', 0, 1),
	(61, 58, 'delivery', 'pas coché', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(62, 58, 'billing', 'coché', 'tzaraea', 'aze', '67100', 'FR', 1, 0),
	(63, 58, 'billing', 'pas coché', 'azeozak', 'oazkeaok', '67100', 'FR', 0, 0),
	(64, 59, 'delivery', 'coché', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(65, 59, 'delivery', 'pas coché', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(66, 59, 'billing', 'coché', 'test', 'azeaze', '67100', 'FR', 1, 0),
	(67, 59, 'billing', 'pas coché', 'test', 'azeaze', '67100', 'FR', 0, 0),
	(68, 60, 'delivery', 'delivery coché', 'teaze', 'tzaezae', '67100', 'FR', 0, 1),
	(69, 60, 'delivery', 'delivery pas coché', 'aztgazea', 'azezaea', '67100', 'FR', 0, 0),
	(70, 60, 'billing', 'billing coché', 'azraerdza', 'ezaezat', '67100', 'FR', 1, 0),
	(71, 60, 'billing', 'billing pas coché', 'azeazezaeatga', 'ggqszdg', '67100', 'FR', 0, 0);

-- Listage de la structure de table projet. category
CREATE TABLE IF NOT EXISTS `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projet.category : ~0 rows (environ)
INSERT INTO `category` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'testDescription', 'description details', '2023-10-26 19:52:56', '2023-10-26 19:52:56');

-- Listage de la structure de table projet. doctrine_migration_versions
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Listage des données de la table projet.doctrine_migration_versions : ~4 rows (environ)
INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
	('DoctrineMigrations\\Version20231019031655', '2023-10-19 05:17:08', 62),
	('DoctrineMigrations\\Version20231019044001', '2023-10-19 06:40:11', 87),
	('DoctrineMigrations\\Version20231105160036', '2023-11-05 17:00:47', 69),
	('DoctrineMigrations\\Version20231105225723', '2023-11-06 00:11:20', 50),
	('DoctrineMigrations\\Version20231107105329', '2023-11-07 11:53:43', 227),
	('DoctrineMigrations\\Version20231107120531', '2023-11-07 13:05:40', 33),
	('DoctrineMigrations\\Version20231107132912', '2023-11-07 14:29:19', 54);

-- Listage de la structure de table projet. order
CREATE TABLE IF NOT EXISTS `order` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_F5299398A76ED395` (`user_id`),
  CONSTRAINT `FK_F5299398A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projet.order : ~0 rows (environ)
INSERT INTO `order` (`id`, `user_id`, `total_price`, `status`, `created_at`, `updated_at`) VALUES
	(1, 60, 20.00, 'pending', '2023-11-07 20:08:25', '2023-11-07 20:08:25'),
	(2, 60, 30.00, 'pending', '2023-11-07 20:13:19', '2023-11-07 20:13:19'),
	(3, 60, 40.00, 'pending', '2023-11-07 20:37:43', '2023-11-07 20:37:43'),
	(4, 60, 50.00, 'pending', '2023-11-07 20:47:34', '2023-11-07 20:47:34'),
	(5, 60, 30.00, 'pending', '2023-11-07 20:48:10', '2023-11-07 20:48:10'),
	(6, 60, 20.00, 'pending', '2023-11-07 21:10:25', '2023-11-07 21:10:25'),
	(7, 60, 90.00, 'pending', '2023-11-07 21:26:07', '2023-11-07 21:26:07'),
	(8, 60, 30.00, 'pending', '2023-11-07 21:38:47', '2023-11-07 21:38:47'),
	(9, 60, 20.00, 'pending', '2023-11-07 21:39:25', '2023-11-07 21:39:25'),
	(10, 60, 20.00, 'pending', '2023-11-07 21:40:01', '2023-11-07 21:40:01'),
	(11, 60, 50.00, 'pending', '2023-11-07 21:40:44', '2023-11-07 21:40:44'),
	(12, 61, 10.00, 'pending', '2023-11-07 22:11:31', '2023-11-07 22:11:31'),
	(13, 61, 30.00, 'pending', '2023-11-07 22:14:39', '2023-11-07 22:14:39'),
	(14, 61, 10.00, 'pending', '2023-11-07 23:26:17', '2023-11-07 23:26:17'),
	(15, 61, 30.00, 'pending', '2023-11-07 23:36:41', '2023-11-07 23:36:41'),
	(16, 61, 30.00, 'pending', '2023-11-07 23:38:49', '2023-11-07 23:38:49'),
	(17, 61, 20.00, 'pending', '2023-11-07 23:51:22', '2023-11-07 23:51:22');

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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projet.order_detail : ~0 rows (environ)
INSERT INTO `order_detail` (`id`, `order_id`, `quantity`, `price`, `subtotal`, `product_id`) VALUES
	(1, 1, 1, 20.00, 20.00, 5),
	(2, 2, 1, 30.00, 30.00, 2),
	(3, 3, 2, 20.00, 40.00, 3),
	(4, 4, 1, 20.00, 20.00, 3),
	(5, 4, 1, 30.00, 30.00, 2),
	(6, 5, 1, 30.00, 30.00, 2),
	(7, 6, 1, 20.00, 20.00, 4),
	(8, 7, 1, 20.00, 20.00, 7),
	(9, 7, 2, 20.00, 40.00, 5),
	(10, 7, 1, 30.00, 30.00, 2),
	(11, 8, 1, 30.00, 30.00, 2),
	(12, 9, 1, 20.00, 20.00, 4),
	(13, 10, 1, 20.00, 20.00, 5),
	(14, 11, 1, 30.00, 30.00, 2),
	(15, 11, 1, 20.00, 20.00, 5),
	(16, 12, 1, 10.00, 10.00, 1),
	(17, 13, 1, 30.00, 30.00, 2),
	(18, 14, 1, 10.00, 10.00, 1),
	(19, 15, 1, 30.00, 30.00, 2),
	(20, 16, 1, 30.00, 30.00, 2),
	(21, 17, 2, 10.00, 20.00, 1);

-- Listage de la structure de table projet. payment
CREATE TABLE IF NOT EXISTS `payment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_6D28840D8D9F6D38` (`order_id`),
  CONSTRAINT `FK_6D28840D8D9F6D38` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projet.payment : ~0 rows (environ)

-- Listage de la structure de table projet. product
CREATE TABLE IF NOT EXISTS `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `category_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D34A04AD5E237E06` (`name`),
  KEY `IDX_D34A04ADA76ED395` (`user_id`),
  KEY `IDX_D34A04AD12469DE2` (`category_id`),
  CONSTRAINT `FK_D34A04AD12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `FK_D34A04ADA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projet.product : ~8 rows (environ)
INSERT INTO `product` (`id`, `user_id`, `category_id`, `name`, `description`, `price`, `stock_quantity`, `is_active`, `image_url`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 'name product', 'description product', 10.00, 10, 1, 'default.image.jpg', '2023-10-26 19:53:48', '2023-11-07 23:51:22'),
	(2, 1, 1, 'lol', 'maaaaa', 30.00, 5, 1, 'default.image.jpg', '2023-10-26 20:19:38', '2023-11-07 23:38:49'),
	(3, 1, 1, 'am', 'nana', 20.00, 2, 1, 'default.image.jpg', '2023-10-26 22:47:04', '2023-11-07 20:47:34'),
	(4, 1, 1, 'ta', 'ze', 20.00, 2, 1, 'default.image.jpg', '2023-10-26 22:47:04', '2023-11-07 21:39:25'),
	(5, 1, 1, 'tata', 'ze', 20.00, 2, 1, 'default.image.jpg', '2023-10-26 22:47:04', '2023-11-07 21:40:44'),
	(6, 1, 1, 'tatata', 'ze', 20.00, 2, 1, 'default.image.jpg', '2023-10-26 22:47:04', '2023-10-26 22:47:06'),
	(7, 1, 1, 'tatatata', 'ze', 20.00, 2, 1, 'default.image.jpg', '2023-10-26 22:47:04', '2023-11-07 21:26:07'),
	(8, 1, 1, 'ta1', 'ze', 20.00, 2, 1, 'default.image.jpg', '2023-10-26 22:47:04', '2023-10-26 22:47:06'),
	(9, 1, 1, 'ta12', 'ze', 20.00, 2, 1, 'default.image.jpg', '2023-10-26 22:47:04', '2023-10-26 22:47:06'),
	(10, 1, 1, 'ta123', 'ze', 20.00, 2, 1, 'default.image.jpg', '2023-10-26 22:47:04', '2023-10-26 22:47:06');

-- Listage de la structure de table projet. review
CREATE TABLE IF NOT EXISTS `review` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` int NOT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci,
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
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `profile_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projet.user : ~54 rows (environ)
INSERT INTO `user` (`id`, `username`, `password`, `email`, `role`, `last_login`, `profile_picture`, `created_at`, `updated_at`) VALUES
	(1, 'test', 'test', 'test@gmail.com', 'artisan', '2023-10-26 19:52:20', NULL, '2023-10-26 19:52:27', '2023-10-26 19:52:28'),
	(2, 'test2', '$2y$04$KqV8VeUKXpaE0ed1tjstrOAHPy2rE3dbDm.WRBnJV4LxI12oaFigi', 'test2@gmail.com', 'artisan', NULL, NULL, '2023-10-28 00:31:50', '2023-10-28 00:31:50'),
	(3, 'test2', '$2y$04$5D092agu2OwviecglGIUsuGfeNZX9S3gO7p9u1Xa0cjafoXoY9mN2', 'test2@gmail.com', 'artisan', NULL, NULL, '2023-10-28 00:54:42', '2023-10-28 00:54:42'),
	(4, 'test3', '$2y$04$rBBsBvdzfce8keK6wB1KV.69dianve5pGEzVuzOYzvobrzpl4LiCe', 'test3@gmail.com', 'artisan', NULL, NULL, '2023-10-28 00:55:06', '2023-10-28 00:55:06'),
	(5, 'test4', '$2y$04$gZ.m8hLowM0a5X4K8I3av.ZY.RH9VzrGzEsmoLnU/O9UaACpjoBFS', 'test4@gmail.com', 'artisan', NULL, NULL, '2023-10-28 01:00:40', '2023-10-28 01:00:40'),
	(6, 'test5', '$2y$04$ckiE7zP9xB5S4vCYUqKh.eIGD6ZqRihkHWT41LQd0I8s2CIuNP8o2', 'test5@gmail.com', 'artisan', NULL, NULL, '2023-10-28 01:04:27', '2023-10-28 01:04:27'),
	(7, 'test12', '$2y$04$qaehUARpeRs35bZrJlOH9.cJjKKtkY1trk6Zi6fdz8Unv7PyyLK7q', 'test12@gmail.com', 'artisan', NULL, NULL, '2023-10-28 01:11:04', '2023-10-28 01:11:04'),
	(8, 'test15', '$2y$04$VKzu3Tbb/DCWfddEWmS4I.ioyM4J41zXAeCrrSFKIE7F.WKUuMmtO', 'test15@gmail.com', 'artisan', NULL, NULL, '2023-10-28 01:17:50', '2023-10-28 01:17:50'),
	(9, 'azeazeaze', '$2y$04$nwUQxL4lHmbf.otWZv1jD.DUo930QW9Vu9Q20q2K8MJezYO3nNTt6', 'test16@gmail.com', 'artisan', NULL, NULL, '2023-10-28 01:18:40', '2023-10-28 01:18:40'),
	(10, 'test17', '$2y$04$HYbohko41TWXnlagIk7zo.VPtn.qG7jvWxsutkaCvrbwVE/VnmDMG', 'test17@gmail.com', 'artisan', NULL, NULL, '2023-10-28 01:33:32', '2023-10-28 01:33:32'),
	(11, 'test18', '$2y$04$HN3WFnVX9W/uil1Mt4edvOlec91T7KYQIXwPQ858fvQb8RojkYX5W', 'test18@gmail.com', 'artisan', NULL, NULL, '2023-10-28 01:35:00', '2023-10-28 01:35:00'),
	(12, 'test18', '$2y$04$343KHR8DkZhrTpZAjHyOCeyoQYFYYbYKV5sUHDWnFne.tZpNwxrmm', 'test18@gmail.com', 'artisan', NULL, NULL, '2023-10-28 01:49:14', '2023-10-28 01:49:14'),
	(13, 'client', '$2y$04$NGTML8HmPibhZiDhH7l0uO6Nt5.nJtHHoa2M6zdE7oG8yQVZF0Ng2', 'client@gmail.com', 'client', NULL, NULL, '2023-11-05 12:37:28', '2023-11-05 12:37:28'),
	(15, 'test101', '$2y$04$DnRvLKTDN4w0VJHJ/9FhjOzUi.GXwtppfZLPfODBM.Pii5squhGzu', 'teazkeo@gmail.com', 'artisan', NULL, NULL, '2023-11-05 14:16:37', '2023-11-05 14:16:37'),
	(17, 'oakesdfoak', '$2y$04$Kj6slfTGaMCWSeLRpefEcOC73yI.qYRbJ0zsuZcolbf9zBd5bm9He', 'aozkeajijzefra@gmail.com', 'artisan', NULL, NULL, '2023-11-05 14:33:33', '2023-11-05 14:33:33'),
	(18, 'test2aizokej', '$2y$04$O/qnsY/Ydxmf3R5s17lQC.5T8M2tl/yf9wv8nHrvnPQlqItnZAHr2', 'azekjaziojfa@gmail.com', 'artisan', NULL, NULL, '2023-11-05 14:39:19', '2023-11-05 14:39:19'),
	(20, 'jean2', '$2y$04$TErmNKhNzKj.8h1mKwvuHeRmjs/8rLs2wp92GhFR/u/2pfyfdmPni', 'jean2@gmail.com', 'artisan', NULL, NULL, '2023-11-05 17:16:48', '2023-11-05 17:16:48'),
	(21, 'Jean3', '$2y$04$wV7Nm17IpUl3Opb3Mqry7OX7ZaHZH5IaSKFHwpjZigV7YE/bGKFvi', 'jean3@gmail.com', 'craftsman', NULL, NULL, '2023-11-05 17:42:18', '2023-11-05 17:42:18'),
	(26, 'jean20', '$2y$04$5fxrWos7H8c5PCnRsKmyEO0a8F7BOXcWck.NzEGTjR9d4Oz0jD.vK', 'jean20@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 00:11:49', '2023-11-06 00:11:49'),
	(27, 'jean21', '$2y$04$4XFDEix4b8YD5eEnmCav/.l8wntinuNSeh6jY3IOTxoTEh7m9m1u.', 'jean21@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 00:12:12', '2023-11-06 00:12:12'),
	(28, 'jean25', '$2y$04$4x8HqMKD4GXrGwfP5LGuv.HMQ6DpLJgfPsVp0dzANWoCYFcviffWC', 'jean25@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 00:20:06', '2023-11-06 00:20:06'),
	(29, 'a5ze1', '$2y$04$N4DyJmRmBFrDYY.aTGMFw.Qw/AVBBfBIedZNwJKLvJWy1GV1FIvIK', 'zekdjaiok@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 00:21:19', '2023-11-06 00:21:19'),
	(30, 'tutu@gmail.com', '$2y$04$1PIT2H9sDIOXON81gdI0beP3FecpmamV0IugCQx7n3ShLD3sWHz1e', 'tutu@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 00:22:01', '2023-11-06 00:22:01'),
	(31, 'tata', '$2y$04$pucIbigHg2/kos7LOikqqOO1wZF5lgEeostOGc0TaTM/5NBA5phLG', 'tata@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 00:22:47', '2023-11-06 00:22:47'),
	(32, 'titi', '$2y$04$TaAndWwCxtDlxJwsPWO8a.iNT7321kJ1VbhRsVFS5Vs3ILWYFbHC.', 'titi@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 00:23:58', '2023-11-06 00:23:58'),
	(33, 'yas', '$2y$04$MY6G6pmjakGm.VRv80LVTuvHKZFS7Fu9jTVL/hrpq0vnTIpKR6o3y', 'yas@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 00:25:31', '2023-11-06 00:25:31'),
	(34, 'last', '$2y$04$3cW9/ytvHAK7v7h7fd7nr.gz5.gGAPKUUG6S67MbKgKld53LIUHMW', 'last@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 00:39:10', '2023-11-06 00:39:10'),
	(35, 'lol1', '$2y$04$QNugZ4gljAWUp83lOCtWwOHBXZWp1OBtGnD9Rm2PFWVZMqh2Xmclq', 'lol1@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 00:42:06', '2023-11-06 00:42:06'),
	(36, 'lol2', '$2y$04$VPQ96Qecr10ANLoNRnT0LedaD5yUmp3Drg0nzkaxWQz0vR1P6ZnY2', 'lol2@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 00:44:04', '2023-11-06 00:44:04'),
	(37, 'lolol', '$2y$04$W9d50/hYlRqt/7bHPG60G.5nqgW2t1QzJsV92dZjFB2XcGi6BycKS', 'lolol@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:01:13', '2023-11-06 01:01:13'),
	(38, 'lol15', '$2y$04$DrxM0.pRXXvfkur38g141u6jy0LKXxnPa8NfYr8xkqHszGHcJtCBi', 'lol15@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:02:25', '2023-11-06 01:02:25'),
	(39, 'mama1', '$2y$04$3Yw2sjAdbqeFOpIuY15uve4EygzobmUX9/umjq638hCaOyNXQ5POO', 'mama1@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:28:02', '2023-11-06 01:28:02'),
	(40, 'xxx1', '$2y$04$qqx4n14kKtA0fw.QSD5r..mbo2.6ErcaLOzvgVz4bDspw6i3grYC.', 'xxx1@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:28:29', '2023-11-06 01:28:29'),
	(41, 'tata2', '$2y$04$kD6ZAwu5IGTGfdNv9xdKNecw9TlAKVtB.59eMRYFCAtELu95mo1.O', 'tata2@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:29:29', '2023-11-06 01:29:29'),
	(42, 'tata3', '$2y$04$DvoygYWQ3WByN.dUzzadwe6mFU5uOz8.im84Zx7Qcfj4RtsbrTDp2', 'tata3@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:30:12', '2023-11-06 01:30:12'),
	(43, 'tata4', '$2y$04$2kxPzh0m50yj6vy8wTb77u5Ce1BqCKApt.Oq5piiSlnWBtJWrzNRq', 'tata4@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:30:54', '2023-11-06 01:30:54'),
	(44, 'izaej', '$2y$04$DL7uaRND/laXtBy7BxxLj.5Pk6qs464oIxo5xBLDW0DkcSQ86H9uC', 'azieja@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:32:32', '2023-11-06 01:32:32'),
	(45, 'loleur', '$2y$04$fnQtZDumW9WnUQkBWAmdzOR7cniL6S9v7O6hVC3px5UC74NeIPrla', 'loleur@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:33:42', '2023-11-06 01:33:42'),
	(46, 'lolad', '$2y$04$jEm0GoUeiXUHQBSkORgkpewCh.PrRxfWa9/RCjgsNXoh9IZUz/B7S', 'lolad@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:34:12', '2023-11-06 01:34:12'),
	(47, 'lolad1', '$2y$04$YEcTg8FWK/K5MUjZOXY5NujSmMzDcWhJbWUqVP5oTKYv7dnJ7VIz.', 'lolad1@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:34:51', '2023-11-06 01:34:51'),
	(48, 'test181', '$2y$04$nzlglRMt3qekIyi3zaMlSug6n34xEdykSSWXOaK5sxJKQVWMn/OQu', 'test181@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:35:18', '2023-11-06 01:35:18'),
	(49, 'test2', '$2y$04$NPQ/7QxAzEkPDI5C8sM/weQ7itkwxYR19QGVIEDpvwRCVyrbcO3Ea', 'azejaiej@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:35:45', '2023-11-06 01:35:45'),
	(50, 'te54r', '$2y$04$9TVwWe.0ElJwCKjQFdeX6OjZod2MVo2nbsJSbhz9jsh75BTw0LeCO', 'azpkagjiao@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:36:55', '2023-11-06 01:36:55'),
	(51, 'iajzifajfi', '$2y$04$WyVwBkibcPiNcMTYUs5Kj.ifKNgpYGV3u6wNdW0iaSd2Zvnuy1KoW', 'gaokzejagmail@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:39:48', '2023-11-06 01:39:48'),
	(52, 'teaijezai', '$2y$04$WfZyIJP4B4C/V.PNo575ou4hCREQxh.zMlJvU5axOC15U2vjIAZ76', 'aijzifajijaiejga@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:47:15', '2023-11-06 01:47:15'),
	(53, 'aozeazoe', '$2y$04$7hLyTUaU93BnTrGxcsAzdOXcBjpXa9kl15vlS/QiqXl6Dg5A/PgSC', 'aozkeoazkeoa@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:48:08', '2023-11-06 01:48:08'),
	(54, 'test2azea', '$2y$04$EkRNcrL5FRy3z1JdOLU1GuLguEJyeViQHtYCHeq.J4YILK1meMm1u', 'azezatavdza@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:49:17', '2023-11-06 01:49:17'),
	(55, 'oazkeoae', '$2y$04$8aiXpMilPmHOx72o/4hJ/e.su71gxWy84mHt1w9PhhW1r1r2HoVki', 'ajzkaoikzpeaepa@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:49:49', '2023-11-06 01:49:49'),
	(56, 'test2zzz', '$2y$04$BqpfDUJksGRHVSM5y7BPy./SA6CZHjdsZ7Hw1a7k/FLJJSW1t0J9e', 'zezaeazeza@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:50:20', '2023-11-06 01:50:20'),
	(57, 'azazzazaa', '$2y$04$tiYUJdzZNVZEwS4LTfuOoe7ifFx8gRKJv7BTTls/YOGAvFqXhU132', 'azaazaa@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:51:23', '2023-11-06 01:51:23'),
	(58, 'leroi', '$2y$04$1T2bVI2hh/aggIEYZAe.2uChYEH74TVi8zraWKsn.Dr3rK7tgJNBC', 'leroi@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:52:57', '2023-11-06 01:52:57'),
	(59, 'lasttest', '$2y$04$ezllgX2WsBfIxUOWo.9UyebyISfpvmk64Xxy/A1a4tBUpzZJu5uvC', 'lasttest@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 01:57:07', '2023-11-06 01:57:07'),
	(60, 'pet', '$2y$04$fYJCepC0KnLq2muW0Rob1u0dunpEziwijdgUx7V.J9VYHZagU8VMO', 'pet@gmail.com', 'craftsman', NULL, NULL, '2023-11-06 02:00:43', '2023-11-07 18:23:03'),
	(61, 'Kazae', '$2y$04$990mdMXUrglg6qZ6nHNvH.gtdPuZ7tJ/pJSLqRQ46m7DuShW6gZU2', 'Kazae@gmail.com', 'craftsman', NULL, NULL, '2023-11-07 22:10:28', '2023-11-07 22:10:28');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table projet.wishlist : ~0 rows (environ)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
