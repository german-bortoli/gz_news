--
-- GZ NEWS DATABASE
--

CREATE TABLE IF NOT EXISTS /*TABLE_PREFIX*/t_news (
  `gn_id` int(11) NOT NULL AUTO_INCREMENT,
  `gn_lang` varchar(20) DEFAULT NULL,
  `gn_title` varchar(250) NOT NULL,
  `gn_description` text NOT NULL,
  `gn_tags` varchar(250) DEFAULT NULL,
  `gn_time_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `gn_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `gn_visits_counter` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gn_id`)
) ENGINE = InnoDB DEFAULT CHARACTER SET 'UTF8' COLLATE 'UTF8_GENERAL_CI';

