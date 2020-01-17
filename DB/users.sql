-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.11-0ubuntu6 - (Ubuntu)
-- Server OS:                    Linux
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

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
  `role` enum('admin','editor','user') DEFAULT 'user',
  `approver_id` int(10) unsigned DEFAULT NULL COMMENT 'ID of the user who has approved the registration of this user',
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `username_password_active` (`username`,`password`,`active`),
  KEY `countryFK` (`country_id`),
  KEY `approverFK` (`approver_id`),
  CONSTRAINT `approverFK` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `countryFK` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


INSERT INTO `users` (`id`, `active`, `first_name`, `last_name`, `email`, `country_id`, `city`, `address`, `birth_date`, `username`, `password`, `role`, `approver_id`, `created`) VALUES
    (1, 1, 'Christo', 'Yovev', 'cyovev@tvz.hr', 33, 'Zagreb', 'Trnsko 30B', '1990-08-14', 'cyovev', '$2y$10$BGhXpoqc7APPOgtmqNxClefXZAo9UOBrqdWv9VnNzAhjrBc6sIYKm', 'admin', NULL, '2020-01-14 14:55:08'),
    (2, 1, 'Some', 'User', 'test@test.com', 14, NULL, NULL, NULL, 'suser', '$2y$10$BGhXpoqc7APPOgtmqNxClefXZAo9UOBrqdWv9VnNzAhjrBc6sIYKm', 'user', 1, '2020-01-16 14:52:16');

-- Dumping structure for trigger tvz.users_bi
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `users_bi` BEFORE INSERT ON `users` FOR EACH ROW BEGIN
    SET NEW.created = NOW();
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;