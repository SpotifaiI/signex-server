USE signex;

CREATE TABLE `viewer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sign` int(10) unsigned DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `code` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `viewer_sign_fk` (`sign`),
  CONSTRAINT `viewer_sign_fk` FOREIGN KEY (`sign`) REFERENCES `sign` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
