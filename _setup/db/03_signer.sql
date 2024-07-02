USE signex;

CREATE TABLE `signer` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sign` int(10) unsigned DEFAULT NULL,
  `hash` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `signed_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `code` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `signer_sign_fk` (`sign`),
  CONSTRAINT `signer_sign_fk` FOREIGN KEY (`sign`) REFERENCES `sign` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
