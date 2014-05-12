CREATE TABLE IF NOT EXISTS `{{ias}}pending_postbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` varchar(20) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `trigger` text NOT NULL,
  `fired` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;