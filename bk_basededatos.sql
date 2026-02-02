-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.4.3 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para restaurante_db
CREATE DATABASE IF NOT EXISTS `restaurante_db` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `restaurante_db`;

-- Volcando estructura para tabla restaurante_db.areas
CREATE TABLE IF NOT EXISTS `areas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla restaurante_db.areas: ~2 rows (aproximadamente)
DELETE FROM `areas`;
INSERT INTO `areas` (`id`, `name`, `created_at`, `updated_at`) VALUES
	(1, 'SALÓN PRINCIPAL', '2026-01-10 00:58:49', '2026-01-10 00:58:49'),
	(2, 'TERRAZA', '2026-01-10 00:59:07', '2026-01-10 00:59:07');

-- Volcando estructura para tabla restaurante_db.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla restaurante_db.cache: ~0 rows (aproximadamente)
DELETE FROM `cache`;

-- Volcando estructura para tabla restaurante_db.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla restaurante_db.cache_locks: ~0 rows (aproximadamente)
DELETE FROM `cache_locks`;

-- Volcando estructura para tabla restaurante_db.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla restaurante_db.categories: ~3 rows (aproximadamente)
DELETE FROM `categories`;
INSERT INTO `categories` (`id`, `name`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'BEBIDAS VARIOS', 'categories/eQwDdqfDlzaND9GDuetCaK9pP4WvHa566pCFRPyW.png', 1, '2026-01-10 01:05:38', '2026-01-10 01:05:38'),
	(2, 'HAMBURGUESAS VARIOS', 'categories/BjqiPByDc1KyUsiFOCLxVLwcEuXRHyKsh5Ac0tW1.png', 1, '2026-01-10 01:06:48', '2026-01-10 01:06:48');

-- Volcando estructura para tabla restaurante_db.clients
CREATE TABLE IF NOT EXISTS `clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `document_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DNI',
  `document_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clients_document_number_unique` (`document_number`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla restaurante_db.clients: ~1 rows (aproximadamente)
DELETE FROM `clients`;
INSERT INTO `clients` (`id`, `name`, `document_type`, `document_number`, `email`, `phone`, `address`, `created_at`, `updated_at`) VALUES
	(1, 'CLIENTE 1', 'DNI', '20202020', 'cliente1@correo.com', '999999991', 'direccion 1', '2026-01-10 05:18:20', '2026-01-10 05:18:20');

-- Volcando estructura para tabla restaurante_db.expenses
CREATE TABLE IF NOT EXISTS `expenses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_user_id_foreign` (`user_id`),
  CONSTRAINT `expenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla restaurante_db.expenses: ~0 rows (aproximadamente)
DELETE FROM `expenses`;

-- Volcando estructura para tabla restaurante_db.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
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

-- Volcando datos para la tabla restaurante_db.failed_jobs: ~0 rows (aproximadamente)
DELETE FROM `failed_jobs`;

-- Volcando estructura para tabla restaurante_db.inventory_logs
CREATE TABLE IF NOT EXISTS `inventory_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `old_stock` int DEFAULT NULL,
  `new_stock` int DEFAULT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_logs_product_id_foreign` (`product_id`),
  KEY `inventory_logs_user_id_foreign` (`user_id`),
  CONSTRAINT `inventory_logs_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla restaurante_db.inventory_logs: ~0 rows (aproximadamente)
DELETE FROM `inventory_logs`;
INSERT INTO `inventory_logs` (`id`, `product_id`, `user_id`, `type`, `quantity`, `old_stock`, `new_stock`, `note`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 'entry', 10, 0, 10, 'Stock Inicial', '2026-01-10 01:08:13', '2026-01-10 01:08:13'),
	(2, 2, 1, 'entry', 10, 0, 10, 'Stock Inicial', '2026-01-10 01:11:16', '2026-01-10 01:11:16'),
	(3, 3, 1, 'entry', 10, 0, 10, 'Stock Inicial', '2026-01-10 01:15:25', '2026-01-10 01:15:25'),
	(4, 4, 1, 'entry', 10, 0, 10, 'Inventario Inicial', '2026-01-10 03:40:50', '2026-01-10 03:40:50'),
	(5, 1, 1, 'sale', -1, 10, 9, 'Venta POS #1', '2026-01-10 05:15:29', '2026-01-10 05:15:29'),
	(6, 1, 1, 'sale', -1, 9, 8, 'Venta POS #2', '2026-01-10 05:18:37', '2026-01-10 05:18:37'),
	(7, 1, 1, 'sale', -2, 8, 6, 'Venta POS #4', '2026-01-10 06:51:26', '2026-01-10 06:51:26'),
	(8, 3, 1, 'sale', -2, 10, 8, 'Venta: HAMBURGUESA 1 (Orden #4)', '2026-01-10 06:51:26', '2026-01-10 06:51:26'),
	(9, 3, 1, 'sale', -1, 8, 7, 'Venta: PRODUCTO 3 (Orden #4)', '2026-01-10 06:51:26', '2026-01-10 06:51:26');

-- Volcando estructura para tabla restaurante_db.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla restaurante_db.jobs: ~0 rows (aproximadamente)
DELETE FROM `jobs`;

-- Volcando estructura para tabla restaurante_db.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla restaurante_db.job_batches: ~0 rows (aproximadamente)
DELETE FROM `job_batches`;

-- Volcando estructura para tabla restaurante_db.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla restaurante_db.migrations: ~24 rows (aproximadamente)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_12_25_151213_create_categories_table', 1),
	(5, '2025_12_25_151226_create_products_table', 1),
	(6, '2025_12_25_151405_create_areas_table', 1),
	(7, '2025_12_25_151405_create_tables_table', 1),
	(8, '2025_12_25_152440_create_sessions_table', 1),
	(9, '2025_12_25_154209_create_orders_table', 1),
	(10, '2025_12_25_154210_create_order_details_table', 1),
	(11, '2025_12_25_182109_create_settings_table', 2),
	(12, '2025_12_25_190357_add_payments_to_orders_table', 3),
	(13, '2025_12_25_194039_add_note_to_order_details_table', 4),
	(14, '2025_12_27_201525_create_expenses_table', 5),
	(15, '2025_12_27_202113_add_client_data_to_orders', 6),
	(16, '2025_12_27_203616_add_discount_and_tip_to_orders', 7),
	(17, '2025_12_28_024400_add_role_to_users', 8),
	(18, '2025_12_28_032924_create_inventory_logs_table', 8),
	(19, '2025_12_28_041453_create_clients_table', 9),
	(20, '2025_12_28_043046_add_client_id_to_orders_table', 10),
	(21, '2025_12_29_140318_add_position_to_tables_table', 11),
	(22, '2025_12_29_142451_add_coords_to_tables', 12),
	(23, '2025_12_29_161924_create_reservations_table', 13),
	(24, '2025_12_29_180402_create_product_ingredients_table', 14),
	(25, '2025_12_29_182322_add_is_saleable_to_products_table', 15),
	(26, '2026_01_10_031729_add_barcode_to_products_table', 16);

-- Volcando estructura para tabla restaurante_db.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `table_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `status` enum('pending','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `document_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ticket',
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Público General',
  `client_document` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tip` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `received_amount` decimal(10,2) DEFAULT NULL,
  `change_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `client_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_table_id_foreign` (`table_id`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_client_id_foreign` (`client_id`),
  CONSTRAINT `orders_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_table_id_foreign` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla restaurante_db.orders: ~0 rows (aproximadamente)
DELETE FROM `orders`;
INSERT INTO `orders` (`id`, `table_id`, `user_id`, `status`, `document_type`, `client_name`, `client_document`, `total`, `discount`, `tip`, `payment_method`, `received_amount`, `change_amount`, `notes`, `created_at`, `updated_at`, `client_id`) VALUES
	(1, 1, 1, 'completed', 'Ticket', '001', NULL, 3.00, 0.00, 0.00, 'cash', 3.00, 0.00, NULL, '2026-01-10 01:09:56', '2026-01-10 05:15:29', NULL),
	(2, 1, 1, 'completed', 'Ticket', 'CLIENTE 1', '20202020', 3.00, 0.00, 0.00, 'cash', 10.00, 7.00, NULL, '2026-01-10 05:15:47', '2026-01-10 05:18:37', 1),
	(3, 4, 1, 'pending', 'Ticket', 'Público General', NULL, 3.50, 0.50, 1.00, 'cash', NULL, 0.00, NULL, '2026-01-10 05:55:07', '2026-01-10 06:09:56', NULL),
	(4, 5, 1, 'completed', 'Ticket', 'CLIENTE 1', '20202020', 32.00, 3.00, 6.00, 'cash', 50.00, 18.00, NULL, '2026-01-10 06:10:07', '2026-01-10 06:51:26', 1),
	(5, 1, 1, 'pending', 'Ticket', 'Público General', NULL, 6.00, 0.00, 0.00, 'cash', NULL, 0.00, NULL, '2026-01-10 07:14:11', '2026-01-10 07:14:15', NULL);

-- Volcando estructura para tabla restaurante_db.order_details
CREATE TABLE IF NOT EXISTS `order_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(10,2) NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','cooking','served') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_details_order_id_foreign` (`order_id`),
  KEY `order_details_product_id_foreign` (`product_id`),
  CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla restaurante_db.order_details: ~0 rows (aproximadamente)
DELETE FROM `order_details`;
INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`, `note`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 1, 3.00, NULL, 'pending', '2026-01-10 01:09:56', '2026-01-10 01:09:56'),
	(2, 2, 1, 1, 3.00, NULL, 'pending', '2026-01-10 05:15:47', '2026-01-10 05:15:47'),
	(3, 3, 4, 1, 3.00, 'comentario 1', 'pending', '2026-01-10 05:55:07', '2026-01-10 06:03:35'),
	(7, 4, 1, 2, 3.00, NULL, 'pending', '2026-01-10 06:40:58', '2026-01-10 06:41:57'),
	(8, 4, 2, 2, 10.00, NULL, 'pending', '2026-01-10 06:42:02', '2026-01-10 06:45:25'),
	(9, 4, 4, 1, 3.00, NULL, 'pending', '2026-01-10 06:45:28', '2026-01-10 06:45:28'),
	(10, 5, 1, 1, 3.00, NULL, 'pending', '2026-01-10 07:14:11', '2026-01-10 07:14:11'),
	(11, 5, 4, 1, 3.00, NULL, 'pending', '2026-01-10 07:14:15', '2026-01-10 07:14:15');

-- Volcando estructura para tabla restaurante_db.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `stock` int DEFAULT NULL,
  `is_saleable` tinyint(1) NOT NULL DEFAULT '1',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_barcode_unique` (`barcode`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla restaurante_db.products: ~10 rows (aproximadamente)
DELETE FROM `products`;
INSERT INTO `products` (`id`, `category_id`, `name`, `barcode`, `code`, `price`, `cost`, `stock`, `is_saleable`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Coca Cola 500ml', NULL, NULL, 3.00, NULL, 6, 1, 'products/PNnQYzu6KRlMreb3cYyN4EG0yrwLxyCxh49FXgD7.png', 1, '2026-01-10 01:08:13', '2026-01-10 06:51:26'),
	(2, 2, 'HAMBURGUESA 1', NULL, NULL, 10.00, NULL, 10, 1, 'products/V2jbrHt8RsAoKvBSED8iN4C3iZIJWbtiUHyOx72p.png', 1, '2026-01-10 01:11:16', '2026-01-10 01:15:46'),
	(3, 2, 'Carne Molida', NULL, NULL, 1.00, NULL, 7, 0, 'products/wVFDAdJWxloNwNeCejId0QLET6GXXYwByCG7uDbq.png', 1, '2026-01-10 01:15:25', '2026-01-10 06:51:26'),
	(4, 1, 'PRODUCTO 3', '10101', NULL, 3.00, NULL, 10, 1, NULL, 1, '2026-01-10 03:40:50', '2026-01-10 03:41:19');

-- Volcando estructura para tabla restaurante_db.product_ingredients
CREATE TABLE IF NOT EXISTS `product_ingredients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `ingredient_id` bigint unsigned NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_ingredients_product_id_foreign` (`product_id`),
  KEY `product_ingredients_ingredient_id_foreign` (`ingredient_id`),
  CONSTRAINT `product_ingredients_ingredient_id_foreign` FOREIGN KEY (`ingredient_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_ingredients_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla restaurante_db.product_ingredients: ~0 rows (aproximadamente)
DELETE FROM `product_ingredients`;
INSERT INTO `product_ingredients` (`id`, `product_id`, `ingredient_id`, `quantity`, `created_at`, `updated_at`) VALUES
	(1, 2, 3, 1.00, '2026-01-10 01:15:46', '2026-01-10 01:15:46'),
	(2, 4, 3, 1.00, NULL, NULL);

-- Volcando estructura para tabla restaurante_db.reservations
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reservation_time` datetime NOT NULL,
  `people` int NOT NULL,
  `table_id` bigint unsigned DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','confirmed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reservations_table_id_foreign` (`table_id`),
  CONSTRAINT `reservations_table_id_foreign` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla restaurante_db.reservations: ~0 rows (aproximadamente)
DELETE FROM `reservations`;

-- Volcando estructura para tabla restaurante_db.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla restaurante_db.sessions: ~5 rows (aproximadamente)
DELETE FROM `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('1v1iTzkA6aMR9BRXofrl3nISQ4t0PRlgV7obJfU7', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoid2M5ejRFMERKVTZ4RjBQNThDWmJ6Rk0xeFlkTFlVVFR0Um5pa0RnQyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1768029280),
	('AcxkSiA8VIfYoGLNu6hVcq5E5eroFp4rEiSztNCU', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiYVczcmxISEQ0SWY3T0xMWDVpeDV1c05JMWpnY3hRckVWQ1EyaVdteSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo5OiJkYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1768055748);

-- Volcando estructura para tabla restaurante_db.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla restaurante_db.settings: ~6 rows (aproximadamente)
DELETE FROM `settings`;
INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
	(1, 'company_name', 'Mi Restaurante', '2025-12-25 23:24:03', '2025-12-29 20:38:08'),
	(2, 'company_address', 'Av. Gastronómica 123, Lima', '2025-12-25 23:24:03', '2025-12-25 23:24:03'),
	(3, 'company_phone', '(01) 555-9992', '2025-12-25 23:24:03', '2025-12-29 20:38:08'),
	(4, 'ticket_footer', '¡Gracias por su preferencia! Vuelva pronto.', '2025-12-25 23:24:03', '2025-12-25 23:24:03'),
	(5, 'currency_symbol', 'S/', '2025-12-25 23:24:03', '2025-12-25 23:32:57'),
	(6, 'company_logo', 'settings/ijjN0r0e6zPjwAMsY1Ko8yljo8HkjMNELo0Flrhi.png', '2025-12-25 23:32:57', '2025-12-25 23:32:57'),
	(7, 'timezone', 'America/Lima', '2025-12-29 22:20:51', '2025-12-29 22:20:51');

-- Volcando estructura para tabla restaurante_db.tables
CREATE TABLE IF NOT EXISTS `tables` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `area_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seats` int NOT NULL DEFAULT '4',
  `status` enum('available','occupied','reserved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `x_pos` int NOT NULL DEFAULT '0',
  `y_pos` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tables_area_id_foreign` (`area_id`),
  CONSTRAINT `tables_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla restaurante_db.tables: ~37 rows (aproximadamente)
DELETE FROM `tables`;
INSERT INTO `tables` (`id`, `area_id`, `name`, `seats`, `status`, `x_pos`, `y_pos`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Mesa 1', 4, 'available', 38, 43, '2026-01-10 01:01:45', '2026-01-10 01:02:43'),
	(2, 1, 'Mesa 2', 4, 'available', 37, 211, '2026-01-10 01:01:53', '2026-01-10 01:02:44'),
	(3, 1, 'Mesa 3', 4, 'available', 369, 89, '2026-01-10 01:02:04', '2026-01-10 01:02:44'),
	(4, 2, 'Mesa 1', 4, 'available', 63, 47, '2026-01-10 01:03:16', '2026-01-10 01:03:34'),
	(5, 2, 'Mesa 2', 4, 'available', 351, 234, '2026-01-10 01:03:24', '2026-01-10 01:03:34');

-- Volcando estructura para tabla restaurante_db.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','cashier','waiter','kitchen') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla restaurante_db.users: ~5 rows (aproximadamente)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Administrador', 'admin@admin.com', NULL, '$2y$12$Pj1NGrRCTz5JQOha67xiaOkDsRjGXlE5gnRbRfvHl06FMRoj4L8sG', 'admin', NULL, '2025-12-25 21:31:47', '2025-12-28 07:55:29'),
	(8, 'usuario', 'usuario@correo.com', NULL, '$2y$12$3Yrslj/Fg9mW1dMjkLiiy.2zqYrD7oJk08I9mWxtMsSprpyBE0K/q', 'waiter', NULL, '2026-01-10 06:57:38', '2026-01-10 06:57:38');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
