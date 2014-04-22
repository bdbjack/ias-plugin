CREATE TABLE IF NOT EXISTS `{{ias}}brands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `isBDB` smallint(1) NOT NULL,
  `active` smallint(1) NOT NULL,
  `weight` varchar(11) DEFAULT NULL,
  `name` text NOT NULL,
  `URL` text NOT NULL,
  `loginByCredsURL` text NOT NULL,
  `phoneNumbers` longtext NOT NULL,
  `logoURL` longtext NOT NULL,
  `licenseKey` text NOT NULL,
  `apiURL` text NOT NULL,
  `apiUser` text NOT NULL,
  `apiPass` text NOT NULL,
  `campaignID` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;