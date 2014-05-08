CREATE TABLE IF NOT EXISTS `{{ias}}postbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` longtext NOT NULL,
  `trigger` enum('visit','emailCap','leadGen','customerGen','deposit') NOT NULL,
  `type` enum('server','image','iframe','js') NOT NULL,
  `priority` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;