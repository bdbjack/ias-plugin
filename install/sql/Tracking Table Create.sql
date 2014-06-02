CREATE TABLE IF NOT EXISTS `{{ias}}tracking` (
  `id` int(11) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `agent` text NOT NULL,
  `time` bigint(20) NOT NULL,
  `a_aid` varchar(20) NOT NULL,
  `a_bid` varchar(20) NOT NULL,
  `a_cid` varchar(20) NOT NULL,
  `tracker` text NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `ip` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;