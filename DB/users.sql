-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.11-0ubuntu6 - (Ubuntu)
-- Server OS:                    Linux
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table tvz.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `active` tinyint(3) unsigned DEFAULT '0' COMMENT 'boolean value; inactive user cannot use the CMS',
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `country_id` int(10) unsigned NOT NULL DEFAULT '0',
  `city` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `username_password_active` (`username`,`password`,`active`),
  KEY `countryFK` (`country_id`),
  CONSTRAINT `countryFK` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table tvz.users: ~0 rows (approximately)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `active`, `first_name`, `last_name`, `email`, `country_id`, `city`, `address`, `birth_date`, `username`, `password`, `created`) VALUES
    (1, 1, 'Christo', 'Yovev', 'cyovev@tvz.hr', 52, 'Zagreb', 'Trnsko 30B', '1990-08-14', 'cyovev', '$2y$10$uDRp2C1LPUsIWHSy.snCnOxpqrpNvrpR/aePmAcTrNbqG7ItOHBpK', '2020-01-14 14:55:08');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
