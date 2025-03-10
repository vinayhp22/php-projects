CREATE DATABASE  IF NOT EXISTS `linklynx` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `linklynx`;
-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: linklynx
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `clicks`
--

DROP TABLE IF EXISTS `clicks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clicks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url_id` int(11) NOT NULL,
  `click_time` datetime NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `url_id` (`url_id`),
  CONSTRAINT `clicks_ibfk_1` FOREIGN KEY (`url_id`) REFERENCES `urls` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clicks`
--

LOCK TABLES `clicks` WRITE;
/*!40000 ALTER TABLE `clicks` DISABLE KEYS */;
INSERT INTO `clicks` VALUES (4,2,'2025-03-10 11:38:48','192.168.0.105'),(5,2,'2025-03-10 11:43:26','192.168.0.105'),(6,5,'2025-03-10 11:48:00','192.168.0.105'),(7,5,'2025-03-10 11:48:30','::1'),(8,5,'2025-03-10 11:48:34','192.168.0.104'),(9,6,'2025-03-10 13:44:52','192.168.0.106'),(10,5,'2025-03-10 13:44:57','192.168.0.106'),(11,2,'2025-03-10 13:45:02','192.168.0.106');
/*!40000 ALTER TABLE `clicks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `urls`
--

DROP TABLE IF EXISTS `urls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `original_url` text NOT NULL,
  `short_code` varchar(100) NOT NULL,
  `expiration_date` datetime DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `click_count` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `short_code` (`short_code`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `urls_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `urls`
--

LOCK TABLES `urls` WRITE;
/*!40000 ALTER TABLE `urls` DISABLE KEYS */;
INSERT INTO `urls` VALUES (2,'https://www.skyllx.com/java-full-stack-course-in-banglore.html','8Ssdvu',NULL,'',1,'2025-03-10 11:38:26',3),(3,'https://www.skyllx.com/java-full-stack-course-in-banglore.html','fMNpDV',NULL,'',NULL,'2025-03-10 11:39:55',0),(5,'https://www.skyllx.com/','Zo76dr',NULL,'',1,'2025-03-10 11:47:47',4),(6,'https://www.google.com/','mrulX1',NULL,'',1,'2025-03-10 11:51:10',1),(7,'https://www.skyllx.com/java-full-stack-course-in-banglore.html','EChYcX',NULL,'',NULL,'2025-03-10 13:40:01',0),(8,'https://www.skyllx.com/java-full-stack-course-in-banglore.html','fYAxho',NULL,'',NULL,'2025-03-10 13:42:59',0),(9,'https://www.skyllx.com/java-full-stack-course-in-banglore.html','QAnuOt',NULL,'',NULL,'2025-03-10 13:43:23',0);
/*!40000 ALTER TABLE `urls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'testuser','user@skyllx.com','$2y$10$tUsqgvu0aIaoot9ORR4CKeTVNwRaggcPAFeVQGKaCx6OTP7jIF6sG','2025-03-10 10:30:50'),(4,'user','user1@skyllx.com','$2y$10$cLinJZGygblYwf.rTt2rEuRWnReyeQmU6vPublBwesGUzncn7o/ZW','2025-03-10 13:48:51');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'linklynx'
--

--
-- Dumping routines for database 'linklynx'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-10 15:21:41
