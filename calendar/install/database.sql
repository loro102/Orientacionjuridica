SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE IF NOT EXISTS `announcement` (
  `announceID` int(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `detail` text,
  `laste_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` bigint(20) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`announceID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS `calendar` (
  `calendar_id` int(6) NOT NULL AUTO_INCREMENT,
  `calendar_name` varchar(150) NOT NULL DEFAULT 'General Events',
  `calendar_description` varchar(250) DEFAULT NULL,
  `sharing` tinyint(4) NOT NULL DEFAULT '0',
  `adjustable` char(1) NOT NULL DEFAULT '0',
  `color` varchar(6) DEFAULT 'ee8800',
  `user_id` bigint(20) NOT NULL,
  PRIMARY KEY (`calendar_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS `calendar_sharing` (
  `calendar_id` int(6) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  `title` varchar(150) NOT NULL DEFAULT 'General Events',
  `show` tinyint(4) NOT NULL DEFAULT '1',
  `color` varchar(6) DEFAULT 'ee8800',
  `user_id` bigint(20) NOT NULL,
  `calendar_id_shared` int(15) DEFAULT NULL,
  PRIMARY KEY (`calendar_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=3;

CREATE TABLE IF NOT EXISTS `events` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(127) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `details` text NOT NULL,
  `calendar_id` int(6) DEFAULT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `settings` (
  `user_id` bigint(20) NOT NULL,
  `settings_id` varchar(15) CHARACTER SET latin1 NOT NULL,
  `value` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`user_id`,`settings_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `support` (
  `support_id` int(12) NOT NULL AUTO_INCREMENT,
  `notice` text NOT NULL,
  `detail` text NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `IP` varchar(30) DEFAULT NULL,
  `user_agent` varchar(200) DEFAULT NULL,
  `status` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`support_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2;


CREATE TABLE IF NOT EXISTS `user` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) NOT NULL DEFAULT '',
  `user_pass` varchar(64) NOT NULL DEFAULT '',
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `user_url` varchar(100) DEFAULT NULL,
  `user_registered` datetime DEFAULT NULL,
  `user_activation_key` varchar(60) NOT NULL DEFAULT '',
  `user_status` tinyint(4) NOT NULL DEFAULT '0',
  `display_name` varchar(250) NOT NULL DEFAULT '',
  `user_type` tinyint(4) NOT NULL DEFAULT '0',
  `user_lastlogin` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


INSERT INTO `announcement` (`announceID`, `title`, `detail`, `laste_modified`, `user_id`, `status`) VALUES
(1, 'Beta Version 0.1 released', 'ArTrix Calendar was   released the first version under Beta 1.0 For further information please contact the ArTrix Calendar web page or <a href="mailto:calendar@artrixdesign.com">calendar@artrixdesign.com</a>', now(), 1, 1),
(2, 'Welcome to ArTrix Calendar!', 'Organizing your schedule in easy way. With Artrix Calendar, it is able to track all events in the same place.', now(), 1, 1);

INSERT INTO `calendar` (`calendar_id`, `calendar_name`, `calendar_description`, `sharing`, `adjustable`, `color`, `user_id`) VALUES (1, 'Administration Events', 'Sample Description', 1, '1', 'ee8800', 1),
(2, 'General Events', 'Sample Description', 1, '1', '8c6d8c', 2);

INSERT INTO `calendar_sharing` (`calendar_id`, `url`, `title`, `show`, `color`, `user_id`, `calendar_id_shared`) VALUES
(1, 'webcal://ical.mac.com/ical/US32Holidays.ics', 'US Holidays', 0, 'ee8800', 1, 0),
(2, 'webcal://www.newsfromgod.com/ologgio/liturgical.ics', 'Catholic Liturgical Calendar', 1, '109618', 1, 0),
(3, 'webcal://icalx.com/public/wesleyrhall/Astronomy.ics', 'Astronomy events', 1, 'dd4477', 1, 0);

INSERT INTO `events` (`event_id`, `event_name`, `start_date`, `end_date`, `details`, `calendar_id`) VALUES
(1, 'System Installed!', NOW(), NOW(), '', 1),
(2, 'First Impression!', DATE_ADD(NOW(), INTERVAL 2 HOUR), DATE_ADD(NOW(), INTERVAL 5 HOUR), '', 1),
(3, 'Sample event I', DATE_ADD(NOW(), INTERVAL 2 DAY), DATE_ADD(NOW(), INTERVAL 2 DAY), '', 1),
(4, 'Sample event II!', DATE_ADD(NOW(), INTERVAL 5 DAY), DATE_ADD(NOW(), INTERVAL 5 DAY), '', 1),
(5, 'Hello world!', DATE_ADD(NOW(), INTERVAL 1 DAY), DATE_ADD(NOW(), INTERVAL 1 DAY), '', 1);

INSERT INTO `settings` (`user_id`, `settings_id`, `value`) VALUES
(1, 'DEF_VIEW', 'month'),
(1, 'START_ON', 'true'),
(1, 'TIME_STEP', '20'),
(1, 'FRIST_HOUR', '6'),
(1, 'LAST_HOUR', '21'),
(1, 'STEP_WD', '15'),
(1, 'SHOW_Y', 'on'),
(1, 'Y_YSCALE', '3'),
(1, 'X_YSCALE', '4'),
(-1, 'SYSTEM_NAME', 'Calendar System'),
(-1, 'SYSTEM_EMAIL', 'calendar@artrixdesign.com'),
(-1, 'SYSTEM_LANG', 'EN'),
(-1, 'NO_ANNOUNCE', '2'),
(-1, 'SYSTEM_VER', 'Beta 1.0'),
(-1, 'SIGN_UP', 'on');

INSERT INTO `support` (`support_id`, `notice`, `detail`, `user_id`, `date`, `IP`, `user_agent`, `status`) VALUES
(1, 'Sample feedback', 'I couldn not believe more thats awesome system!', 2, NOW(), '127.0.0.1', 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', '2');

INSERT INTO `user` (`ID`, `user_login`, `user_pass`, `user_email`, `user_url`, `user_registered`, `user_activation_key`, `user_status`, `display_name`, `user_type`, `user_lastlogin`) VALUES
(1, 'admin', 'fe01ce2a7fbac8fafaed7c982a04e229', 'calendar@artrixdesign.com', 'http://www.artrixdesign.com', NOW(), 'sWLYE0dDmHYut5gS8XB3KXQU8', 1, 'Administrator', 1, '0000-00-00 00:00:00'),
(2, 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229', 'demo@calendar.com', 'http://www.demo.com', NOW(), 'SiYW2mp4rJPRXF3hJrFRhm6Iw', 1, 'Demo User', 0, '0000-00-00 00:00:00');