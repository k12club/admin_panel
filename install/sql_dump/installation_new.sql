DROP TABLE IF EXISTS `PHPAP105_admins`;
CREATE TABLE IF NOT EXISTS `PHPAP105_admins` (
  `id` int(10) NOT NULL auto_increment,
  `username` varchar(50) NOT NULL default '',
  `password` varchar(50) NOT NULL default '',
  `last_name` varchar(50) NOT NULL default '',
  `first_name` varchar(50) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `status` enum('main admin','admin') NOT NULL default 'admin',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

INSERT INTO `PHPAP105_admins` (`id`, `username`, `password`, `last_name`, `first_name`, `email`, `status`) VALUES
(1, '<USER_NAME>', <PASSWORD>, 'John', 'Smith', 'admin@domain.com', 'main admin');


DROP TABLE IF EXISTS `PHPAP105_menu`;
CREATE TABLE IF NOT EXISTS `PHPAP105_menu` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `page_name` varchar(30) NOT NULL default '',
  `is_menu_group` tinyint(1) NOT NULL default '0',
  `is_removable` tinyint(1) NOT NULL default '0',
  `is_hidden` tinyint(1) NOT NULL default '0',
  `parent_id` int(11) NOT NULL default '0',
  `order_index` tinyint(3) NOT NULL default '0',
  `icon` varchar(30) default NULL,
  `is_dashboard_icon` tinyint(1) default '1',
  `is_menu_item` tinyint(1) NOT NULL default '1',
  `file_type_id` tinyint(3) NOT NULL default '2',
  PRIMARY KEY  (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `is_menu_name` (`is_menu_group`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=94 ;

INSERT INTO `PHPAP105_menu` (`id`, `name`, `page_name`, `is_menu_group`, `is_removable`, `is_hidden`, `parent_id`, `order_index`, `icon`, `is_dashboard_icon`, `is_menu_item`, `file_type_id`) VALUES
(1, 'General', '', 1, 0, 0, 0, 0, '', 1, 1, 2),
(2, 'Account Manager', '', 1, 0, 0, 0, 5, '', 1, 1, 2),
(3, 'Emails & Events', '', 1, 0, 0, 0, 10, '', 1, 1, 2),
(4, 'Statistics', '', 1, 0, 0, 0, 15, '', 1, 1, 2),
(5, 'Menu Manager', 'pages/menu_manager.php', 0, 0, 0, 1, 10, 'menu_manager.png', 1, 1, 2),
(6, 'Main', 'home.php', 0, 0, 0, 1, 0, '', 0, 1, 2),
(7, 'My Account', 'pages/my_account.php', 0, 0, 0, 2, 0, 'my_account.png', 1, 1, 2),
(8, 'Admins', 'pages/admins.php', 0, 0, 0, 2, 0, 'admins.png', 1, 1, 2),
(9, 'Users', 'pages/users.php', 0, 0, 0, 2, 5, '', 1, 1, 2),
(10, 'News', 'pages/news.php', 0, 0, 0, 3, 0, '', 1, 1, 2),
(11, 'Mass Mail', 'pages/mass_mail.php', 0, 0, 0, 3, 5, '', 1, 1, 2),
(12, 'Events', 'pages/events.php', 0, 0, 0, 3, 10, '', 1, 1, 2),
(13, 'Logs', 'pages/logs.php', 0, 0, 0, 4, 0, '', 1, 1, 2),
(14, 'Statistics', 'pages/statistics.php', 0, 0, 0, 4, 5, '', 1, 1, 2),
(15, 'Pages', '', 1, 0, 0, 0, 7, NULL, 0, 1, 2),
(16, 'Static', 'pages/static_pages.php', 0, 0, 0, 15, 0, '', 0, 1, 2);


DROP TABLE IF EXISTS `PHPAP105_settings`;
CREATE TABLE IF NOT EXISTS `PHPAP105_settings` (
  `id` tinyint(3) NOT NULL auto_increment,
  `site_name` varchar(125) NOT NULL default '',
  `site_address` varchar(125) NOT NULL default '',
  `css_style` varchar(10) NOT NULL default '',
  `header_text` varchar(125) NOT NULL default '',
  `site_language` char(2) NOT NULL default 'en',
  `datagrid_css_style` varchar(10) NOT NULL default 'default',
  `menu_style` enum('left','top') NOT NULL default 'left',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

INSERT INTO `PHPAP105_settings` (`id`, `site_name`, `site_address`, `css_style`, `header_text`, `site_language`, `datagrid_css_style`, `menu_style`) VALUES
(1, 'Admin Panel Development', 'domain.com', 'blue', 'Admin Panel', 'en', 'blue', 'top');
