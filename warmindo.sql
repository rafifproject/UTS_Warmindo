-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: warmindo
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Mie','mie','Aneka olahan indomie dan mie hangat dengan racikan istimewa khas Warmindo',1,'2026-10-20 23:11:05','2026-10-20 23:11:05'),(2,'Minuman','minuman','Minuman dingin dan hangat pelepas dahaga dan penyegar suasana',1,'2026-10-20 23:11:05','2026-10-20 23:11:05'),(3,'Snack','snack','Camilan dan gorengan gurih renyah teman setia ngobrol santai',1,'2026-10-20 23:11:05','2026-10-20 23:11:05'),(4,'Nasi','nasi','Menu nasi lezat porsi pas bikin kenyang maksimal',1,'2026-10-20 23:11:05','2026-10-20 23:11:05');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2024_01_01_000001_create_categories_table',1),(6,'2024_01_01_000002_create_products_table',1),(7,'2024_01_01_000003_create_suppliers_table',1),(8,'2024_01_01_000004_create_purchases_table',1),(9,'2024_01_01_000005_create_purchase_items_table',1),(10,'2024_01_01_000006_create_sales_table',1),(11,'2024_01_01_000007_create_sale_items_table',1),(12,'2024_01_01_000008_create_stock_mutations_table',1),(13,'2024_01_01_000009_create_order_status_histories_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_status_histories`
--

DROP TABLE IF EXISTS `order_status_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_status_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint unsigned NOT NULL,
  `from_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `staff_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Sistem / Dapur',
  `user_id` bigint unsigned DEFAULT NULL,
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_status_histories_sale_id_foreign` (`sale_id`),
  KEY `order_status_histories_user_id_foreign` (`user_id`),
  CONSTRAINT `order_status_histories_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_status_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_status_histories`
--

LOCK TABLES `order_status_histories` WRITE;
/*!40000 ALTER TABLE `order_status_histories` DISABLE KEYS */;
INSERT INTO `order_status_histories` VALUES (1,4,'waiting_payment','queued','Kasir: Siti Aisyah',2,'Pembayaran tunai lunas Rp 20.000','2026-10-20 23:01:06','2026-10-20 23:11:06'),(2,5,'queued','preparing','Dapur: Budi',3,'Mulai dimasak','2026-10-20 22:59:06','2026-10-20 23:11:06');
/*!40000 ALTER TABLE `order_status_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `buy_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `sell_price` decimal(12,2) NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `min_stock` int NOT NULL DEFAULT '5',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `is_favorite` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,'WMW-001','Indomie Goreng','indomie-goreng','Mie instan goreng dengan bumbu khas Indonesia, disajikan dengan telur, sayuran segar, dan taburan bawang goreng renyah.',8000.00,12000.00,25,5,'images/menu/indomie-goreng.png',1,1,'2026-10-20 23:11:05','2026-10-20 23:11:05',NULL),(2,1,'WMW-002','Mie Ayam','mie-ayam','Mie kenyal disajikan dengan potongan daging ayam bumbu kecap manis gurih, sawi hijau, dan kuah kaldu harum.',10000.00,15000.00,18,5,'images/menu/mie-ayam.png',1,1,'2026-10-20 23:11:05','2026-10-20 23:11:05',NULL),(3,1,'WMW-003','Mie Kari','mie-kari','Mie kuah kari kental berempah aroma menggugah selera, komplit dengan telur rebus dan irisan cabai rawit.',10000.00,15000.00,20,5,'images/menu/mie-kari.png',1,0,'2026-10-20 23:11:05','2026-10-20 23:11:05',NULL),(4,1,'WMW-004','Mie Kuah','mie-kuah','Mie kuah kaldu ayam spesial yang hangat, gurih, disajikan dengan sayur sawi dan telur mata sapi lembut.',9000.00,13000.00,15,5,'images/menu/mie-kuah.png',1,0,'2026-10-20 23:11:05','2026-10-20 23:11:05',NULL),(5,2,'WMW-005','Es Teh Manis','es-teh-manis','Teh melati wangi dengan seduhan gula asli dingin menyegarkan peneman santap favorit.',2000.00,5000.00,50,10,'images/menu/es-teh-manis.png',1,1,'2026-10-20 23:11:05','2026-10-20 23:11:05',NULL),(6,2,'WMW-006','Es Jeruk','es-jeruk','Perasan jeruk asli manis alami dengan sensasi bulir segar dan es batu dingin.',3000.00,7000.00,30,10,'images/menu/es-jeruk.png',1,0,'2026-10-20 23:11:05','2026-10-20 23:11:05',NULL),(7,2,'WMW-007','Es Milo','es-milo','Minuman cokelat malt pekat legit creamy dengan taburan bubuk milo di atasnya.',4000.00,8000.00,22,8,'images/menu/es-milo.png',1,0,'2026-10-20 23:11:05','2026-10-20 23:11:05',NULL),(8,3,'WMW-008','Kentang Goreng','kentang-goreng','Kentang renyah keemasan dibumbui taburan gurih istimewa, disajikan dengan saus sambal.',5000.00,10000.00,15,5,'images/menu/kentang-goreng.png',1,1,'2026-10-20 23:11:05','2026-10-20 23:11:05',NULL),(9,4,'WMW-009','Nasi Goreng','nasi-goreng','Nasi goreng racikan bumbu khas Warmindo wangi asap lezat, lengkap dengan acar, kerupuk, dan telur.',11000.00,16000.00,12,4,'images/menu/nasi-goreng.png',1,0,'2026-10-20 23:11:05','2026-10-20 23:11:05',NULL),(10,3,'WMW-010','Kerupuk','kerupuk','Kerupuk kaleng renyah gurih pelengkap wajib makan mie dan nasi.',1500.00,3000.00,40,10,'images/menu/kerupuk.png',1,0,'2026-10-20 23:11:05','2026-10-20 23:11:05',NULL),(11,3,'WMW-011','Telur Rebus','telur-rebus','Tambahan telur rebus matang lembut berprotein tinggi.',2500.00,4000.00,3,10,'images/menu/telur-rebus.png',1,0,'2026-10-20 23:11:05','2026-10-20 23:11:05',NULL),(12,4,'WMW-012','Ayam Goreng','ayam-goreng','Ayam goreng gurih bumbu rempah kuning gurih garing.',10000.00,15000.00,4,10,'images/menu/ayam-goreng.png',1,0,'2026-10-20 23:11:05','2026-10-20 23:11:05',NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_items`
--

DROP TABLE IF EXISTS `purchase_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_items_purchase_id_foreign` (`purchase_id`),
  KEY `purchase_items_product_id_foreign` (`product_id`),
  CONSTRAINT `purchase_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `purchase_items_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_items`
--

LOCK TABLES `purchase_items` WRITE;
/*!40000 ALTER TABLE `purchase_items` DISABLE KEYS */;
INSERT INTO `purchase_items` VALUES (1,1,1,30,8000.00,240000.00,'2026-10-20 23:11:05','2026-10-20 23:11:05'),(2,1,4,20,9000.00,180000.00,'2026-10-20 23:11:05','2026-10-20 23:11:05'),(3,2,5,50,2000.00,100000.00,'2026-10-20 23:11:05','2026-10-20 23:11:05'),(4,2,6,30,3000.00,90000.00,'2026-10-20 23:11:05','2026-10-20 23:11:05');
/*!40000 ALTER TABLE `purchase_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchases`
--

DROP TABLE IF EXISTS `purchases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchases` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `purchase_date` date NOT NULL,
  `total_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` enum('pending','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchases_purchase_number_unique` (`purchase_number`),
  KEY `purchases_supplier_id_foreign` (`supplier_id`),
  KEY `purchases_user_id_foreign` (`user_id`),
  CONSTRAINT `purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `purchases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchases`
--

LOCK TABLES `purchases` WRITE;
/*!40000 ALTER TABLE `purchases` DISABLE KEYS */;
INSERT INTO `purchases` VALUES (1,'PB-001',1,1,'2026-10-11',450000.00,'completed','Pasokan rutin mie dan bumbu','2026-10-20 23:11:05','2026-10-20 23:11:05'),(2,'PB-002',1,1,'2026-10-16',326000.00,'completed','Stok tambahan teh dan jeruk','2026-10-20 23:11:05','2026-10-20 23:11:05');
/*!40000 ALTER TABLE `purchases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sale_items`
--

DROP TABLE IF EXISTS `sale_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sale_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `quantity` int NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','queued','preparing','ready_to_serve','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_items_sale_id_foreign` (`sale_id`),
  KEY `sale_items_product_id_foreign` (`product_id`),
  CONSTRAINT `sale_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_items`
--

LOCK TABLES `sale_items` WRITE;
/*!40000 ALTER TABLE `sale_items` DISABLE KEYS */;
INSERT INTO `sale_items` VALUES (1,1,2,'Mie Ayam',15000.00,2,30000.00,'Satu pedas, satu biasa','pending','2026-10-20 23:11:05','2026-10-20 23:11:05'),(2,1,5,'Es Teh Manis',5000.00,1,5000.00,'Es dipisah','pending','2026-10-20 23:11:05','2026-10-20 23:11:05'),(3,1,10,'Kerupuk',3000.00,1,3000.00,NULL,'pending','2026-10-20 23:11:06','2026-10-20 23:11:06'),(4,2,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'pending','2026-10-20 23:11:06','2026-10-20 23:11:06'),(5,3,10,'Kerupuk',3000.00,1,3000.00,NULL,'pending','2026-10-20 23:11:06','2026-10-20 23:11:06'),(6,4,1,'Indomie Goreng',12000.00,1,12000.00,'Setengah matang','queued','2026-10-20 23:11:06','2026-10-20 23:11:06'),(7,4,5,'Es Teh Manis',5000.00,1,5000.00,'Manis sedang','queued','2026-10-20 23:11:06','2026-10-20 23:11:06'),(8,5,2,'Mie Ayam',15000.00,1,15000.00,NULL,'preparing','2026-10-20 23:11:06','2026-10-20 23:11:06'),(9,5,6,'Es Jeruk',7000.00,1,7000.00,NULL,'preparing','2026-10-20 23:11:06','2026-10-20 23:11:06'),(10,6,4,'Mie Kuah',13000.00,1,13000.00,NULL,'ready_to_serve','2026-10-20 23:11:06','2026-10-20 23:11:06'),(11,6,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'ready_to_serve','2026-10-20 23:11:06','2026-10-20 23:11:06'),(12,7,9,'Nasi Goreng',16000.00,1,16000.00,NULL,'ready_to_serve','2026-10-20 23:11:06','2026-10-20 23:11:06'),(13,7,7,'Es Milo',8000.00,1,8000.00,NULL,'ready_to_serve','2026-10-20 23:11:06','2026-10-20 23:11:06'),(14,8,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-20 23:11:06','2026-10-20 23:11:06'),(15,8,6,'Es Jeruk',7000.00,1,7000.00,NULL,'completed','2026-10-20 23:11:06','2026-10-20 23:11:06'),(16,9,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-20 14:37:00','2026-10-20 23:11:06'),(17,9,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-20 14:37:00','2026-10-20 23:11:06'),(18,10,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-20 14:37:00','2026-10-20 23:11:06'),(19,10,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-20 14:37:00','2026-10-20 23:11:06'),(20,11,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-20 14:37:00','2026-10-20 23:11:06'),(21,11,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-20 14:37:00','2026-10-20 23:11:06'),(22,12,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-20 14:37:00','2026-10-20 23:11:06'),(23,12,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-20 14:37:00','2026-10-20 23:11:06'),(24,13,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-20 14:37:00','2026-10-20 23:11:06'),(25,13,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-20 14:37:00','2026-10-20 23:11:06'),(26,14,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-20 14:37:00','2026-10-20 23:11:06'),(27,14,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-20 14:37:00','2026-10-20 23:11:06'),(28,15,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-19 12:34:00','2026-10-20 23:11:06'),(29,15,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-19 12:34:00','2026-10-20 23:11:06'),(30,16,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-19 12:34:00','2026-10-20 23:11:06'),(31,16,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-19 12:34:00','2026-10-20 23:11:06'),(32,17,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-19 12:34:00','2026-10-20 23:11:06'),(33,17,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-19 12:34:00','2026-10-20 23:11:06'),(34,18,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-19 12:34:00','2026-10-20 23:11:06'),(35,18,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-19 12:34:00','2026-10-20 23:11:06'),(36,19,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-19 12:34:00','2026-10-20 23:11:06'),(37,19,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-19 12:34:00','2026-10-20 23:11:06'),(38,20,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-19 12:34:00','2026-10-20 23:11:06'),(39,20,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-19 12:34:00','2026-10-20 23:11:06'),(40,21,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-19 12:34:00','2026-10-20 23:11:06'),(41,21,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-19 12:34:00','2026-10-20 23:11:06'),(42,22,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-19 12:34:00','2026-10-20 23:11:06'),(43,22,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-19 12:34:00','2026-10-20 23:11:06'),(44,23,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-18 11:46:00','2026-10-20 23:11:06'),(45,23,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-18 11:46:00','2026-10-20 23:11:06'),(46,24,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-18 11:46:00','2026-10-20 23:11:06'),(47,24,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-18 11:46:00','2026-10-20 23:11:06'),(48,25,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-18 11:46:00','2026-10-20 23:11:06'),(49,25,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-18 11:46:00','2026-10-20 23:11:06'),(50,26,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-18 11:46:00','2026-10-20 23:11:06'),(51,26,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-18 11:46:00','2026-10-20 23:11:06'),(52,27,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-18 11:46:00','2026-10-20 23:11:06'),(53,27,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-18 11:46:00','2026-10-20 23:11:06'),(54,28,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-18 11:46:00','2026-10-20 23:11:06'),(55,28,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-18 11:46:00','2026-10-20 23:11:06'),(56,29,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-18 11:46:00','2026-10-20 23:11:06'),(57,29,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-18 11:46:00','2026-10-20 23:11:06'),(58,30,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-18 11:46:00','2026-10-20 23:11:06'),(59,30,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-18 11:46:00','2026-10-20 23:11:06'),(60,31,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-17 14:36:00','2026-10-20 23:11:06'),(61,31,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-17 14:36:00','2026-10-20 23:11:06'),(62,32,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-17 14:36:00','2026-10-20 23:11:06'),(63,32,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-17 14:36:00','2026-10-20 23:11:06'),(64,33,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-17 14:36:00','2026-10-20 23:11:06'),(65,33,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-17 14:36:00','2026-10-20 23:11:06'),(66,34,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-17 14:36:00','2026-10-20 23:11:06'),(67,34,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-17 14:36:00','2026-10-20 23:11:06'),(68,35,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-17 14:36:00','2026-10-20 23:11:06'),(69,35,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-17 14:36:00','2026-10-20 23:11:06'),(70,36,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-17 14:36:00','2026-10-20 23:11:06'),(71,36,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-17 14:36:00','2026-10-20 23:11:06'),(72,37,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-17 14:36:00','2026-10-20 23:11:06'),(73,37,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-17 14:36:00','2026-10-20 23:11:06'),(74,38,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-16 07:38:00','2026-10-20 23:11:06'),(75,38,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-16 07:38:00','2026-10-20 23:11:06'),(76,39,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-16 07:38:00','2026-10-20 23:11:06'),(77,39,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-16 07:38:00','2026-10-20 23:11:06'),(78,40,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-16 07:38:00','2026-10-20 23:11:06'),(79,40,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-16 07:38:00','2026-10-20 23:11:06'),(80,41,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-16 07:38:00','2026-10-20 23:11:06'),(81,41,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-16 07:38:00','2026-10-20 23:11:06'),(82,42,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-16 07:38:00','2026-10-20 23:11:06'),(83,42,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-16 07:38:00','2026-10-20 23:11:06'),(84,43,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-16 07:38:00','2026-10-20 23:11:06'),(85,43,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-16 07:38:00','2026-10-20 23:11:06'),(86,44,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-16 07:38:00','2026-10-20 23:11:06'),(87,44,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-16 07:38:00','2026-10-20 23:11:06'),(88,45,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-15 12:49:00','2026-10-20 23:11:06'),(89,45,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-15 12:49:00','2026-10-20 23:11:06'),(90,46,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-15 12:49:00','2026-10-20 23:11:06'),(91,46,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-15 12:49:00','2026-10-20 23:11:06'),(92,47,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-15 12:49:00','2026-10-20 23:11:06'),(93,47,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-15 12:49:00','2026-10-20 23:11:06'),(94,48,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-15 12:49:00','2026-10-20 23:11:06'),(95,48,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-15 12:49:00','2026-10-20 23:11:06'),(96,49,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-15 12:49:00','2026-10-20 23:11:06'),(97,49,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-15 12:49:00','2026-10-20 23:11:06'),(98,50,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-14 11:33:00','2026-10-20 23:11:06'),(99,50,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-14 11:33:00','2026-10-20 23:11:06'),(100,51,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-14 11:33:00','2026-10-20 23:11:06'),(101,51,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-14 11:33:00','2026-10-20 23:11:06'),(102,52,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-14 11:33:00','2026-10-20 23:11:06'),(103,52,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-14 11:33:00','2026-10-20 23:11:06'),(104,53,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-14 11:33:00','2026-10-20 23:11:06'),(105,53,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-14 11:33:00','2026-10-20 23:11:06'),(106,54,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-14 11:33:00','2026-10-20 23:11:06'),(107,54,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-14 11:33:00','2026-10-20 23:11:06'),(108,55,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-14 11:33:00','2026-10-20 23:11:06'),(109,55,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-14 11:33:00','2026-10-20 23:11:06'),(110,56,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-14 11:33:00','2026-10-20 23:11:06'),(111,56,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-14 11:33:00','2026-10-20 23:11:06'),(112,57,1,'Indomie Goreng',12000.00,1,12000.00,NULL,'completed','2026-10-14 11:33:00','2026-10-20 23:11:06'),(113,57,5,'Es Teh Manis',5000.00,1,5000.00,NULL,'completed','2026-10-14 11:33:00','2026-10-20 23:11:06');
/*!40000 ALTER TABLE `sale_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sale_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pelanggan',
  `order_type` enum('dine_in','take_away') COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `service_fee` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `payment_status` enum('pending','paid','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `order_status` enum('waiting_payment','queued','preparing','ready_to_serve','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting_payment',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cash_received` decimal(12,2) DEFAULT NULL,
  `cash_change` decimal(12,2) DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sales_sale_number_unique` (`sale_number`),
  KEY `sales_user_id_foreign` (`user_id`),
  CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (1,'PSN-0012',NULL,'Pelanggan Meja 3','dine_in','3',40000.00,0.00,40000.00,'pending','waiting_payment',NULL,NULL,NULL,NULL,'Mie jangan terlalu lembek ya mas','2026-10-20 22:56:05','2026-10-20 23:11:05'),(2,'PSN-0011',NULL,'Pelanggan Meja 5','dine_in','5',5000.00,0.00,5000.00,'pending','waiting_payment',NULL,NULL,NULL,NULL,NULL,'2026-10-20 22:51:06','2026-10-20 23:11:06'),(3,'PSN-0010',NULL,'Pelanggan Meja 1','dine_in','1',3000.00,0.00,3000.00,'pending','waiting_payment',NULL,NULL,NULL,NULL,NULL,'2026-10-20 22:46:06','2026-10-20 23:11:06'),(4,'PSN-0128',2,'Pelanggan Meja 3','dine_in','3',17000.00,0.00,17000.00,'paid','queued','cash',20000.00,3000.00,'2026-10-20 23:01:06','Indomie setengah matang','2026-10-20 22:59:06','2026-10-20 23:11:06'),(5,'PSN-0127',2,'Pelanggan Meja 5','dine_in','5',22000.00,0.00,22000.00,'paid','preparing','qris',NULL,NULL,'2026-10-20 22:56:06',NULL,'2026-10-20 22:51:06','2026-10-20 23:11:06'),(6,'PSN-0126',2,'Pelanggan Meja 7','dine_in','7',18000.00,0.00,18000.00,'paid','ready_to_serve','cash',20000.00,2000.00,'2026-10-20 22:41:06',NULL,'2026-10-20 22:36:06','2026-10-20 23:11:06'),(7,'PSN-0125',2,'Take Away','take_away',NULL,24000.00,0.00,24000.00,'paid','ready_to_serve','cash',50000.00,26000.00,'2026-10-20 22:26:06',NULL,'2026-10-20 22:21:06','2026-10-20 23:11:06'),(8,'PSN-0124',2,'Pelanggan Meja 2','dine_in','2',19000.00,0.00,19000.00,'paid','completed','cash',20000.00,1000.00,'2026-10-20 22:11:06',NULL,'2026-10-20 22:01:06','2026-10-20 23:11:06'),(9,'TRX-1020-001',2,'Meja 5','dine_in','6',36000.00,0.00,36000.00,'paid','completed','cash',41000.00,5000.00,'2026-10-20 14:37:00',NULL,'2026-10-20 14:37:00','2026-10-20 23:11:06'),(10,'TRX-1020-002',2,'Meja 6','dine_in','7',36000.00,0.00,36000.00,'paid','completed','cash',41000.00,5000.00,'2026-10-20 14:37:00',NULL,'2026-10-20 14:37:00','2026-10-20 23:11:06'),(11,'TRX-1020-003',2,'Meja 3','dine_in','6',48000.00,0.00,48000.00,'paid','completed','qris',53000.00,5000.00,'2026-10-20 14:37:00',NULL,'2026-10-20 14:37:00','2026-10-20 23:11:06'),(12,'TRX-1020-004',2,'Take Away','take_away',NULL,36000.00,0.00,36000.00,'paid','completed','cash',41000.00,5000.00,'2026-10-20 14:37:00',NULL,'2026-10-20 14:37:00','2026-10-20 23:11:06'),(13,'TRX-1020-005',2,'Meja 8','dine_in','4',48000.00,0.00,48000.00,'paid','completed','cash',53000.00,5000.00,'2026-10-20 14:37:00',NULL,'2026-10-20 14:37:00','2026-10-20 23:11:06'),(14,'TRX-1020-006',2,'Meja 8','dine_in','4',36000.00,0.00,36000.00,'paid','completed','cash',41000.00,5000.00,'2026-10-20 14:37:00',NULL,'2026-10-20 14:37:00','2026-10-20 23:11:06'),(15,'TRX-1019-001',2,'Take Away','take_away',NULL,36000.00,0.00,36000.00,'paid','completed','cash',41000.00,5000.00,'2026-10-19 12:34:00',NULL,'2026-10-19 12:34:00','2026-10-20 23:11:06'),(16,'TRX-1019-002',2,'Meja 3','dine_in','8',24000.00,0.00,24000.00,'paid','completed','qris',29000.00,5000.00,'2026-10-19 12:34:00',NULL,'2026-10-19 12:34:00','2026-10-20 23:11:06'),(17,'TRX-1019-003',2,'Take Away','take_away',NULL,48000.00,0.00,48000.00,'paid','completed','cash',53000.00,5000.00,'2026-10-19 12:34:00',NULL,'2026-10-19 12:34:00','2026-10-20 23:11:06'),(18,'TRX-1019-004',2,'Meja 7','dine_in','5',24000.00,0.00,24000.00,'paid','completed','cash',29000.00,5000.00,'2026-10-19 12:34:00',NULL,'2026-10-19 12:34:00','2026-10-20 23:11:06'),(19,'TRX-1019-005',2,'Take Away','take_away',NULL,48000.00,0.00,48000.00,'paid','completed','qris',53000.00,5000.00,'2026-10-19 12:34:00',NULL,'2026-10-19 12:34:00','2026-10-20 23:11:06'),(20,'TRX-1019-006',2,'Meja 3','dine_in','2',24000.00,0.00,24000.00,'paid','completed','cash',29000.00,5000.00,'2026-10-19 12:34:00',NULL,'2026-10-19 12:34:00','2026-10-20 23:11:06'),(21,'TRX-1019-007',2,'Meja 8','dine_in','3',48000.00,0.00,48000.00,'paid','completed','qris',53000.00,5000.00,'2026-10-19 12:34:00',NULL,'2026-10-19 12:34:00','2026-10-20 23:11:06'),(22,'TRX-1019-008',2,'Take Away','take_away',NULL,36000.00,0.00,36000.00,'paid','completed','qris',41000.00,5000.00,'2026-10-19 12:34:00',NULL,'2026-10-19 12:34:00','2026-10-20 23:11:06'),(23,'TRX-1018-001',2,'Take Away','take_away',NULL,48000.00,0.00,48000.00,'paid','completed','cash',53000.00,5000.00,'2026-10-18 11:46:00',NULL,'2026-10-18 11:46:00','2026-10-20 23:11:06'),(24,'TRX-1018-002',2,'Meja 5','dine_in','2',48000.00,0.00,48000.00,'paid','completed','cash',53000.00,5000.00,'2026-10-18 11:46:00',NULL,'2026-10-18 11:46:00','2026-10-20 23:11:06'),(25,'TRX-1018-003',2,'Meja 5','dine_in','2',24000.00,0.00,24000.00,'paid','completed','cash',29000.00,5000.00,'2026-10-18 11:46:00',NULL,'2026-10-18 11:46:00','2026-10-20 23:11:06'),(26,'TRX-1018-004',2,'Meja 3','dine_in','3',48000.00,0.00,48000.00,'paid','completed','cash',53000.00,5000.00,'2026-10-18 11:46:00',NULL,'2026-10-18 11:46:00','2026-10-20 23:11:06'),(27,'TRX-1018-005',2,'Take Away','take_away',NULL,36000.00,0.00,36000.00,'paid','completed','cash',41000.00,5000.00,'2026-10-18 11:46:00',NULL,'2026-10-18 11:46:00','2026-10-20 23:11:06'),(28,'TRX-1018-006',2,'Meja 6','dine_in','2',48000.00,0.00,48000.00,'paid','completed','cash',53000.00,5000.00,'2026-10-18 11:46:00',NULL,'2026-10-18 11:46:00','2026-10-20 23:11:06'),(29,'TRX-1018-007',2,'Meja 1','dine_in','4',48000.00,0.00,48000.00,'paid','completed','qris',53000.00,5000.00,'2026-10-18 11:46:00',NULL,'2026-10-18 11:46:00','2026-10-20 23:11:06'),(30,'TRX-1018-008',2,'Meja 6','dine_in','9',24000.00,0.00,24000.00,'paid','completed','qris',29000.00,5000.00,'2026-10-18 11:46:00',NULL,'2026-10-18 11:46:00','2026-10-20 23:11:06'),(31,'TRX-1017-001',2,'Meja 5','dine_in','4',48000.00,0.00,48000.00,'paid','completed','qris',53000.00,5000.00,'2026-10-17 14:36:00',NULL,'2026-10-17 14:36:00','2026-10-20 23:11:06'),(32,'TRX-1017-002',2,'Meja 7','dine_in','4',24000.00,0.00,24000.00,'paid','completed','qris',29000.00,5000.00,'2026-10-17 14:36:00',NULL,'2026-10-17 14:36:00','2026-10-20 23:11:06'),(33,'TRX-1017-003',2,'Meja 7','dine_in','8',48000.00,0.00,48000.00,'paid','completed','qris',53000.00,5000.00,'2026-10-17 14:36:00',NULL,'2026-10-17 14:36:00','2026-10-20 23:11:06'),(34,'TRX-1017-004',2,'Meja 5','dine_in','2',48000.00,0.00,48000.00,'paid','completed','qris',53000.00,5000.00,'2026-10-17 14:36:00',NULL,'2026-10-17 14:36:00','2026-10-20 23:11:06'),(35,'TRX-1017-005',2,'Take Away','take_away',NULL,36000.00,0.00,36000.00,'paid','completed','cash',41000.00,5000.00,'2026-10-17 14:36:00',NULL,'2026-10-17 14:36:00','2026-10-20 23:11:06'),(36,'TRX-1017-006',2,'Meja 8','dine_in','5',48000.00,0.00,48000.00,'paid','completed','cash',53000.00,5000.00,'2026-10-17 14:36:00',NULL,'2026-10-17 14:36:00','2026-10-20 23:11:06'),(37,'TRX-1017-007',2,'Meja 1','dine_in','8',24000.00,0.00,24000.00,'paid','completed','qris',29000.00,5000.00,'2026-10-17 14:36:00',NULL,'2026-10-17 14:36:00','2026-10-20 23:11:06'),(38,'TRX-1016-001',2,'Take Away','take_away',NULL,24000.00,0.00,24000.00,'paid','completed','cash',29000.00,5000.00,'2026-10-16 07:38:00',NULL,'2026-10-16 07:38:00','2026-10-20 23:11:06'),(39,'TRX-1016-002',2,'Take Away','take_away',NULL,48000.00,0.00,48000.00,'paid','completed','qris',53000.00,5000.00,'2026-10-16 07:38:00',NULL,'2026-10-16 07:38:00','2026-10-20 23:11:06'),(40,'TRX-1016-003',2,'Meja 9','dine_in','10',24000.00,0.00,24000.00,'paid','completed','cash',29000.00,5000.00,'2026-10-16 07:38:00',NULL,'2026-10-16 07:38:00','2026-10-20 23:11:06'),(41,'TRX-1016-004',2,'Meja 2','dine_in','8',36000.00,0.00,36000.00,'paid','completed','qris',41000.00,5000.00,'2026-10-16 07:38:00',NULL,'2026-10-16 07:38:00','2026-10-20 23:11:06'),(42,'TRX-1016-005',2,'Take Away','take_away',NULL,48000.00,0.00,48000.00,'paid','completed','cash',53000.00,5000.00,'2026-10-16 07:38:00',NULL,'2026-10-16 07:38:00','2026-10-20 23:11:06'),(43,'TRX-1016-006',2,'Take Away','take_away',NULL,24000.00,0.00,24000.00,'paid','completed','cash',29000.00,5000.00,'2026-10-16 07:38:00',NULL,'2026-10-16 07:38:00','2026-10-20 23:11:06'),(44,'TRX-1016-007',2,'Meja 3','dine_in','1',24000.00,0.00,24000.00,'paid','completed','qris',29000.00,5000.00,'2026-10-16 07:38:00',NULL,'2026-10-16 07:38:00','2026-10-20 23:11:06'),(45,'TRX-1015-001',2,'Meja 3','dine_in','8',36000.00,0.00,36000.00,'paid','completed','cash',41000.00,5000.00,'2026-10-15 12:49:00',NULL,'2026-10-15 12:49:00','2026-10-20 23:11:06'),(46,'TRX-1015-002',2,'Meja 8','dine_in','7',36000.00,0.00,36000.00,'paid','completed','qris',41000.00,5000.00,'2026-10-15 12:49:00',NULL,'2026-10-15 12:49:00','2026-10-20 23:11:06'),(47,'TRX-1015-003',2,'Meja 4','dine_in','3',48000.00,0.00,48000.00,'paid','completed','cash',53000.00,5000.00,'2026-10-15 12:49:00',NULL,'2026-10-15 12:49:00','2026-10-20 23:11:06'),(48,'TRX-1015-004',2,'Meja 2','dine_in','6',24000.00,0.00,24000.00,'paid','completed','cash',29000.00,5000.00,'2026-10-15 12:49:00',NULL,'2026-10-15 12:49:00','2026-10-20 23:11:06'),(49,'TRX-1015-005',2,'Take Away','take_away',NULL,36000.00,0.00,36000.00,'paid','completed','qris',41000.00,5000.00,'2026-10-15 12:49:00',NULL,'2026-10-15 12:49:00','2026-10-20 23:11:06'),(50,'TRX-1014-001',2,'Meja 2','dine_in','7',48000.00,0.00,48000.00,'paid','completed','cash',53000.00,5000.00,'2026-10-14 11:33:00',NULL,'2026-10-14 11:33:00','2026-10-20 23:11:06'),(51,'TRX-1014-002',2,'Meja 7','dine_in','3',36000.00,0.00,36000.00,'paid','completed','cash',41000.00,5000.00,'2026-10-14 11:33:00',NULL,'2026-10-14 11:33:00','2026-10-20 23:11:06'),(52,'TRX-1014-003',2,'Meja 4','dine_in','9',48000.00,0.00,48000.00,'paid','completed','cash',53000.00,5000.00,'2026-10-14 11:33:00',NULL,'2026-10-14 11:33:00','2026-10-20 23:11:06'),(53,'TRX-1014-004',2,'Meja 7','dine_in','4',48000.00,0.00,48000.00,'paid','completed','cash',53000.00,5000.00,'2026-10-14 11:33:00',NULL,'2026-10-14 11:33:00','2026-10-20 23:11:06'),(54,'TRX-1014-005',2,'Meja 4','dine_in','3',24000.00,0.00,24000.00,'paid','completed','qris',29000.00,5000.00,'2026-10-14 11:33:00',NULL,'2026-10-14 11:33:00','2026-10-20 23:11:06'),(55,'TRX-1014-006',2,'Meja 6','dine_in','9',48000.00,0.00,48000.00,'paid','completed','qris',53000.00,5000.00,'2026-10-14 11:33:00',NULL,'2026-10-14 11:33:00','2026-10-20 23:11:06'),(56,'TRX-1014-007',2,'Meja 7','dine_in','9',36000.00,0.00,36000.00,'paid','completed','cash',41000.00,5000.00,'2026-10-14 11:33:00',NULL,'2026-10-14 11:33:00','2026-10-20 23:11:06'),(57,'TRX-1014-008',2,'Meja 9','dine_in','6',24000.00,0.00,24000.00,'paid','completed','qris',29000.00,5000.00,'2026-10-14 11:33:00',NULL,'2026-10-14 11:33:00','2026-10-20 23:11:06');
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_mutations`
--

DROP TABLE IF EXISTS `stock_mutations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_mutations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `type` enum('in','out','adjustment') COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `reference_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int NOT NULL,
  `before_stock` int NOT NULL,
  `after_stock` int NOT NULL,
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_mutations_product_id_foreign` (`product_id`),
  KEY `stock_mutations_user_id_foreign` (`user_id`),
  CONSTRAINT `stock_mutations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_mutations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_mutations`
--

LOCK TABLES `stock_mutations` WRITE;
/*!40000 ALTER TABLE `stock_mutations` DISABLE KEYS */;
INSERT INTO `stock_mutations` VALUES (1,1,'in','purchase',1,'PB-001',30,0,30,'Pembelian dari PT ABC Food',1,'2026-10-10 23:11:05','2026-10-20 23:11:05');
/*!40000 ALTER TABLE `stock_mutations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES (1,'PT ABC Food','Budi Hartono','0811-234-1800','sales@abcfood.com','Kawasan Industri Pulo Gadung, Jakarta Timur',1,'2026-10-20 23:11:05','2026-10-20 23:11:05'),(2,'CV Maju Jaya','Hendra Wijaya','0812-987-6543','hendra@majujaya.com','Jl. Pasar Minggu No. 45, Jakarta Selatan',1,'2026-10-20 23:11:05','2026-10-20 23:11:05'),(3,'Sumber Rezeki','Dewi Lestari','0813-555-8888','order@sumberrezeki.com','Jl. Surya Kencana No. 12, Bogor',1,'2026-10-20 23:11:05','2026-10-20 23:11:05'),(4,'Indo Prima','Agus Setiawan','0818-091-2345','admin@indoprima.co.id','Jl. Daan Mogot KM 11, Jakarta Barat',1,'2026-10-20 23:11:05','2026-10-20 23:11:05');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','staff') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'staff',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin Owner','admin','admin@warmindo.id',NULL,'$2y$12$MNKlYGhpesZsAKBvcn/R7.TlJNd8hMApba.uWMC32tiX9xExFCT.O','admin','0812-3456-7890','active',NULL,NULL,'2026-10-20 23:11:05','2026-10-20 23:11:05'),(2,'Siti Aisyah','siti','kasir@warmindo.id',NULL,'$2y$12$lkFi/Kjyil18eLiRLOOSYu85AoWVRgruznacV/TziOmNX6e808nhK','staff','0813-8877-6655','active',NULL,NULL,'2026-10-20 23:11:05','2026-10-20 23:11:05'),(3,'Budi Santoso','budi','budi@warmindo.id',NULL,'$2y$12$iCHWeDlHegWFP4bgcPQcKe3U2RbZBGm2Fl7M0mFPrvjodsHWZevLq','staff','0812-9988-7711','active',NULL,NULL,'2026-10-20 23:11:05','2026-10-20 23:11:05'),(4,'Rian Pratama','rian','rian@warmindo.id',NULL,'$2y$12$Ji41Mowca/QY4BuvznW1suAAsGd.4YNKM5ht4z5IdbC3XLTWClVom','staff','0856-1122-3344','active',NULL,NULL,'2026-10-20 23:11:05','2026-10-20 23:11:05'),(5,'Dini Lestari','dini','dini@warmindo.id',NULL,'$2y$12$HH.igCTpEFHGSuABnxrMbenycjp536n5JMBQLtC1NIfvhQZtZhzI2','staff','0878-4455-6677','inactive',NULL,NULL,'2026-10-20 23:11:05','2026-10-20 23:11:05');
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

-- Dump completed on 2026-10-21 14:54:13
