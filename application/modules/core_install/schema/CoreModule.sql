-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 14, 2013 at 01:08 AM
-- Server version: 5.5.29
-- PHP Version: 5.4.6-1ubuntu1.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `CoreModule`
--

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('21255e563095e2c2cf23d98cae586050', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:20.0) Gecko/20100101 Firefox/20.0', 1365919253, 'a:2:{s:9:"user_data";s:0:"";s:9:"back_link";b:0;}'),
('3451c5fd8363c422c151c255a890bcc3', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:20.0) Gecko/20100101 Firefox/20.0', 1365912376, 'a:2:{s:9:"user_data";s:0:"";s:9:"back_link";b:0;}'),
('3ea0b899050b7b285d51ce313e41648b', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:20.0) Gecko/20100101 Firefox/20.0', 1365908419, 'a:7:{s:9:"user_data";s:0:"";s:9:"back_link";s:26:"http://ignited_root/login/";s:7:"user_id";s:1:"2";s:8:"username";s:5:"admin";s:5:"email";s:15:"admin@admin.com";s:4:"permission";s:5:"admin";s:25:"flash:old:message_success";s:31:"You are now logged in as admin.";}'),
('46302a50d1769ac23de9199ac8fc03f5', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:20.0) Gecko/20100101 Firefox/20.0', 1365922982, ''),
('de2ddd265c03dee7c05728232d939826', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:20.0) Gecko/20100101 Firefox/20.0', 1365908061, 'a:6:{s:9:"user_data";s:0:"";s:9:"back_link";b:0;s:7:"user_id";s:1:"2";s:8:"username";s:5:"admin";s:5:"email";s:15:"admin@admin.com";s:4:"permission";s:5:"admin";}'),
('f1db07e43ccf1ed19655be2786e4ae0b', '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:20.0) Gecko/20100101 Firefox/20.0', 1365908854, 'a:2:{s:9:"user_data";s:0:"";s:9:"back_link";b:0;}');

-- --------------------------------------------------------

--
-- Table structure for table `core_menus`
--

CREATE TABLE IF NOT EXISTS `core_menus` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(255) NOT NULL,
  `menu_classes` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `core_menus`
--

INSERT INTO `core_menus` (`id`, `menu_name`, `menu_classes`, `description`) VALUES
(1, 'Admin menu', 'top-bar', 'The administrative menu.'),
(2, 'Demo menu', 'nav-bar right', 'The default primary menu that ships with CI Starter.'),
(4, 'Users', '', 'Sub menu of the Users link in the Admin menu.'),
(5, 'Content', '', 'Submenu of the Content link in the Admin menu.'),
(7, 'Pages', '', 'Sub menu of the admin Content link.');

-- --------------------------------------------------------

--
-- Table structure for table `core_menu_links`
--

CREATE TABLE IF NOT EXISTS `core_menu_links` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `weight` int(11) NOT NULL DEFAULT '1',
  `parent_menu_id` int(11) NOT NULL,
  `child_menu_id` int(11) NOT NULL DEFAULT '0',
  `external` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `permissions` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=45 ;

--
-- Dumping data for table `core_menu_links`
--

INSERT INTO `core_menu_links` (`id`, `weight`, `parent_menu_id`, `child_menu_id`, `external`, `title`, `text`, `link`, `permissions`) VALUES
(1, 3, 1, 0, 0, 'Set the system email perferences.', 'Email settings', 'email_settings', 'admin,super_user'),
(2, 2, 1, 5, 0, 'Edit site content.', 'Content', '#', 'admin,super_user'),
(3, 4, 1, 0, 0, 'Administer menus and links.', 'Menus', 'menus', 'admin,super_user'),
(4, 5, 4, 0, 0, 'User permissions admin page.', 'User permissions', 'admin_user_permissions', 'admin,super_user'),
(5, 5, 1, 4, 0, 'The user admin page.', 'Users', '#', 'admin,super_user'),
(6, 1, 1, 0, 0, 'The application home page.', 'Home', '{front}', ''),
(13, 1, 2, 0, 0, 'The application home page.', 'Home', '{front}', ''),
(14, 2, 2, 0, 0, 'The admin index page.', 'Admin', 'admin', 'admin,super_user'),
(26, 3, 4, 0, 0, 'Add a user to the database.', 'Add user', 'admin_user_add', 'admin,super_user'),
(27, 2, 4, 0, 0, 'The user admin page.', 'User admin', 'admin_users', 'admin,super_user'),
(28, 1, 5, 7, 0, 'Pages submenu.', 'Pages', '#', 'admin,super_user'),
(31, 1, 4, 0, 0, 'The label for the Users section of the User submenu.', '<label>Users</label>', '{label}', 'admin,super_user'),
(32, 4, 4, 0, 0, 'The permissions label for the Users submenu.', '<label>permissions</label>', '{label}', 'admin,super_user'),
(33, 6, 4, 0, 0, 'Add a user permission.', 'Add permission', 'admin_user_permission_add', 'admin,super_user'),
(34, 2, 7, 0, 0, 'Page admin.', 'Pages', 'pages', 'admin,super_user'),
(35, 3, 7, 0, 0, 'Add a page.', 'Add page', 'page_add', 'admin,super_user'),
(36, 1, 7, 0, 0, 'Public', '<label>Public</label>', '{label}', ''),
(37, 4, 7, 0, 0, 'Admin', '<label>Admin</label>', '{label}', ''),
(38, 5, 7, 0, 0, 'Admin pages', 'Admin pages', 'admin_pages', 'admin,super_user'),
(39, 6, 7, 0, 0, 'Admin page add.', 'Add admin page', 'admin_page_add', 'admin,super_user'),
(40, 3, 2, 0, 0, 'User profile.', 'Profile', 'user', ''),
(41, 4, 2, 0, 0, 'Login and logout link.', 'Login', '{login}', ''),
(42, 6, 1, 0, 0, 'Login and logout link.', 'Login', '{login}', ''),
(43, 8, 4, 0, 0, 'Core user settings.', 'Settings', 'core_user_settings', 'admin,super_user'),
(44, 7, 4, 0, 0, 'Settings label.', '<label>Settings</label>', '{label}', 'admin,super_user');

-- --------------------------------------------------------

--
-- Table structure for table `core_pages`
--

CREATE TABLE IF NOT EXISTS `core_pages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `is_front` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `author` varchar(50) NOT NULL,
  `created` bigint(20) NOT NULL,
  `last_edit` bigint(20) NOT NULL DEFAULT '0',
  `last_edit_username` varchar(255) NOT NULL DEFAULT '0',
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `template` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `slug` (`slug`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `core_pages`
--

INSERT INTO `core_pages` (`id`, `is_front`, `published`, `author`, `created`, `last_edit`, `last_edit_username`, `slug`, `title`, `body`, `template`) VALUES
(5, 1, 1, 'admin', 1362507902, 1365759766, 'admin', 'about', 'About us', '<h4>Welcome</h4><p>Front page content has not been set.</p>', 'demo_template/demo_template'),
(7, 0, 1, 'admin', 1365560705, 1365561421, 'admin', 'user', 'User profile', '{%module:core_user%}', 'demo_template/demo_template'),
(8, 0, 1, 'admin', 1365561683, 1365652836, 'admin', 'login', 'User login', '{%module:core_user/login%}', 'demo_template/demo_template'),
(9, 0, 1, 'admin', 1365561760, 0, '0', 'logout', 'Logout', '{%module:core_user/logout%}', 'demo_template/demo_template'),
(14, 0, 1, 'admin', 1365656611, 1365657136, '0', 'forgotten_password', 'Forgotten password', '{%module:core_user/forgotten_password%}', 'demo_template/demo_template'),
(15, 0, 1, 'admin', 1365657457, 0, '0', 'forgotten_password_login', 'Forgotten password login.', '{%module:core_user/forgotten_password_login%}', 'demo_template/demo_template'),
(16, 0, 1, 'admin', 1365657847, 0, '0', 'user_add', 'User add.', '{%module:core_user/user_add%}', 'demo_template/demo_template'),
(17, 0, 1, 'admin', 1365663679, 0, '0', 'user_activate', 'User activate', '{%module:core-user/user_activate%}', 'demo_template/demo_template'),
(18, 0, 1, 'admin', 1365664483, 1365752454, 'admin', 'user_edit', 'User edit.', '{%module:core_user/user_edit%}', 'demo_template/demo_template'),
(19, 0, 1, 'admin', 1365758582, 1365885260, 'admin', 'user_delete', 'User delete', '{%module:core_user/user_delete%}', 'demo_template/demo_template');

-- --------------------------------------------------------

--
-- Table structure for table `core_pages_admin`
--

CREATE TABLE IF NOT EXISTS `core_pages_admin` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `is_front` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `author` varchar(50) NOT NULL,
  `created` bigint(20) NOT NULL,
  `last_edit` bigint(20) NOT NULL DEFAULT '0',
  `last_edit_username` varchar(255) NOT NULL DEFAULT '0',
  `slug` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `template` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `core_pages_admin`
--

INSERT INTO `core_pages_admin` (`id`, `is_front`, `published`, `author`, `created`, `last_edit`, `last_edit_username`, `slug`, `title`, `body`, `template`) VALUES
(1, 0, 1, 'admin', 1365561760, 1365561800, 'admin', 'page_add', 'Add page', '{%module:core_module/page_add%}', 'admin_template/admin_template'),
(2, 0, 1, 'admin', 1365561800, 1365561900, 'admin', 'page_edit', 'Edit Page', '{%module:core_module/page_edit%}', 'admin_template/admin_template'),
(3, 0, 1, 'admin', 1365561900, 1365562000, 'admin', 'pages', 'Pages', '{%module:core_module/pages%}', 'admin_template/admin_template'),
(4, 0, 1, 'admin', 1365562000, 1365562100, 'admin', 'admin_pages', 'Admin pages', '{%module:core_module/admin_pages%}', 'admin_template/admin_template'),
(5, 0, 1, 'admin', 1365562200, 1365562300, 'admin', 'admin_page_add', 'Add admin page', '{%module:core_module/admin_page_add%}', 'admin_template/admin_template'),
(6, 0, 1, 'admin', 1365639962, 0, '0', 'admin_page_edit', 'Edit admin page.', '{%module:core_module/admin_page_edit%}', 'admin_template/admin_template'),
(7, 0, 1, 'admin', 1365644691, 0, '0', 'menus', 'Menus', '{%module:core_menu/menus%}', 'admin_template/admin_template'),
(8, 0, 1, 'admin', 1365645642, 1365645782, 'admin', 'menu_add', 'Add menu.', '{%module:core_menu/menu_add%}', 'admin_template/admin_template'),
(9, 0, 1, 'admin', 1365645918, 0, '0', 'menu_edit', 'Edit menu.', '{%module:core_menu/menu_edit%}', 'admin_template/admin_template'),
(10, 0, 1, 'admin', 1365645992, 0, '0', 'menu_delete', 'Delete menu.', '{%module:core_menu/menu_delete%}', 'admin_template/admin_template'),
(11, 0, 1, 'admin', 1365646185, 0, '0', 'menu_link_add', 'Add a menu link.', '{%module:core_menu/menu_link_add%}', 'admin_template/admin_template'),
(12, 0, 1, 'admin', 1365646367, 0, '0', 'menu_link_delete', 'Delete a menu link.', '{%module:core_menu/menu_link_delete%}', 'admin_template/admin_template'),
(13, 0, 1, 'admin', 1365646467, 0, '0', 'menu_link_edit_weight', 'Edit the weight of a menu link.', '{%module:core_menu/menu_link_edit_weight%}', 'admin_template/admin_template'),
(14, 0, 1, 'admin', 1365648263, 0, '0', 'email_settings', 'Email settings', '{%module:core_email/email_settings%}', 'admin_template/admin_template'),
(15, 0, 1, 'admin', 1365652984, 0, '0', 'page_delete', 'Delete a public page.', '{%module:core_module/page_delete%}', 'admin_template/admin_template'),
(16, 0, 1, 'admin', 1365684113, 0, '0', 'admin_user_permissions', 'User permissions', '{%module:core_user/admin_user_permissions%}', 'admin_template/admin_template'),
(17, 0, 1, 'admin', 1365684248, 0, '0', 'admin_user_permission_add', 'Add a user permission.', '{%module:core_user/admin_user_permission_add%}', 'admin_template/admin_template'),
(18, 0, 1, 'admin', 1365684629, 0, '0', 'admin_user_permission_edit', 'Edit a user permission.', '{%module:core_user/admin_user_permission_edit%}', 'admin_template/admin_template'),
(19, 0, 1, 'admin', 1365684717, 0, '0', 'admin_user_permission_delete', 'Delete a user permission.', '{%module:core_user/admin_user_permission_delete%}', 'admin_template/admin_template'),
(20, 0, 1, 'admin', 1365684905, 1365752001, 'admin', 'admin_users', 'User admin page.', '{%module:core_user/admin_users%}', 'admin_template/admin_template'),
(21, 0, 1, 'admin', 1365702374, 0, '0', 'admin_user_edit', 'Edit a user.', '{%module:core_user/admin_user_edit%}', 'admin_template/admin_template'),
(22, 0, 1, 'admin', 1365702614, 0, '0', 'admin_user_add', 'Add a user.', '{%module:core_user/admin_user_add%}', 'admin_template/admin_template'),
(23, 0, 1, 'admin', 1365702702, 0, '0', 'admin_user_delete', 'Delete a user.', '{%module:core_user/admin_user_delete%}', 'admin_template/admin_template'),
(24, 1, 1, 'admin', 1365726887, 1365727479, 'admin', 'admin', 'Core module admin front page module.', '{%module:core_admin_front%}', 'admin_template/admin_template'),
(25, 0, 1, 'superuser', 1365815995, 0, '0', 'core_user_settings', 'Core user settings.', '{%module:core_user/core_user_settings%}', 'admin_template/admin_template');

-- --------------------------------------------------------

--
-- Table structure for table `core_permissions`
--

CREATE TABLE IF NOT EXISTS `core_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `protected` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `core_permissions`
--

INSERT INTO `core_permissions` (`id`, `permission`, `description`, `protected`) VALUES
(1, 'super_user', 'Top level user account.', 1),
(2, 'admin', 'User with administrator privileges.', 1),
(3, 'authenticated', 'The default permission.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `core_settings`
--

CREATE TABLE IF NOT EXISTS `core_settings` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `setting` varbinary(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=48 ;

--
-- Dumping data for table `core_settings`
--

INSERT INTO `core_settings` (`id`, `name`, `setting`) VALUES
(12, 'user_activation_expire_limit', 's:5:"43200";'),
(13, 'user_forgotten_password_code_expire_limit', 's:4:"1800";'),
(14, 'user_persistent_cookie_name', 's:17:"core_module_login";'),
(15, 'user_persistent_cookie_expire', 's:7:"1209600";'),
(16, 'user_login_attempts_max', 's:1:"5";'),
(17, 'user_login_attempts_time', 's:3:"120";'),
(18, 'user_login_attempts_lockout_time', 's:3:"900";'),
(38, 'core_email_Host', 's:21:"smtp.laughinghost.com";'),
(39, 'core_email_Port', 's:3:"587";'),
(40, 'core_email_SMTPAuth', 's:1:"0";'),
(41, 'core_email_SMTPSecure', 's:3:"tls";'),
(42, 'core_email_Username', 's:22:"admin@laughinghost.com";'),
(43, 'core_email_Password', 's:88:"QkGDf/t70Hz07t6IdK0JlHrP/Cna5zkm315Icjp+3C5vmC9DJD+HGjQrFox4Dp/ewx0vIcsjsIuT0RCBxlWxLw==";'),
(44, 'core_email_From', 's:22:"admin@laughinghost.com";'),
(45, 'core_email_FromName', 's:10:"CoreModule";'),
(46, 'core_email_reply_to', 's:22:"admin@laughinghost.com";'),
(47, 'core_email_reply_to_name', 's:10:"CoreModule";');

-- --------------------------------------------------------

--
-- Table structure for table `core_users`
--

CREATE TABLE IF NOT EXISTS `core_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `protected` tinyint(1) NOT NULL DEFAULT '0',
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created` bigint(20) unsigned DEFAULT NULL,
  `last_login` bigint(20) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `locked_out_time` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `core_users`
--

INSERT INTO `core_users` (`id`, `protected`, `username`, `password`, `salt`, `email`, `created`, `last_login`, `active`, `locked_out_time`) VALUES
(1, 1, 'superuser', '$2a$08$wUWj5ZLsyGUWAKz7l9XHQ.kNDvs.XEfVEV3fKMFQzPpyaiExH3r4G', 'QzlMSN5UvApRIFDKeUNShKV4WIxX3crSo7FPc+N/p2bg7k4ZVHEFO7CEQouAPN/gQ/2qEdPGPZQ3ouGy/vQj7g==', 'superuser@superuser.com', 1361192180, NULL, 1, NULL),
(2, 1, 'admin', '$2a$08$Kl8pBCNfJ.33h/z8vBObVeIjEDbifJJxK5T8euuNLiS51GkBEHgjm', 'HBWqnp/gEmKliZQBoOdNYmU3Tak/3v84E7serRQtBus+S3R5GHF1Qv2RrHFzmKVVe5Inhq5nfMIPfg/nUhIcig==', 'admin@admin.com', 1365673864, NULL, 1, NULL),
(3, 1, 'demo', '$2a$08$4N4z5pfvNqzB0l2Sv.3w/uUWs4DkSM.iQaeaglJ0SSK.no0i/acqG', 'FDEu1HoR8exZ2QAQ1yE/suL5PEr4uZgykxzthDFF5qzeKDqt4DOrao9Q/3C5UenKzYVR01LfwJXet2y5NkUcjA==', 'demo@demo.com', 1361193519, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `core_user_activation_codes`
--

CREATE TABLE IF NOT EXISTS `core_user_activation_codes` (
  `user_id` varchar(255) NOT NULL,
  `activation_code` varchar(255) NOT NULL,
  `expire_time` bigint(20) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `core_user_forgotten_passwords`
--

CREATE TABLE IF NOT EXISTS `core_user_forgotten_passwords` (
  `user_id` bigint(20) NOT NULL,
  `forgotten_password_code` varchar(255) NOT NULL,
  `forgotten_password_expire_time` bigint(20) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `core_user_join_users_permissions`
--

CREATE TABLE IF NOT EXISTS `core_user_join_users_permissions` (
  `user_id` bigint(20) unsigned NOT NULL,
  `permission_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `core_user_join_users_permissions`
--

INSERT INTO `core_user_join_users_permissions` (`user_id`, `permission_id`) VALUES
(1, 1),
(2, 2),
(3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `core_user_login_attempts`
--

CREATE TABLE IF NOT EXISTS `core_user_login_attempts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(16) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `core_user_login_attempts`
--

INSERT INTO `core_user_login_attempts` (`id`, `ip_address`, `login`, `time`) VALUES
(1, '127.0.0.1', 'admin', 1365724273),
(2, '127.0.0.1', 'admin', 1365724280),
(3, '127.0.0.1', 'admin', 1365724320),
(4, '127.0.0.1', 'admin', 1365724331);

-- --------------------------------------------------------

--
-- Table structure for table `core_user_remember_codes`
--

CREATE TABLE IF NOT EXISTS `core_user_remember_codes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `ip_address` varbinary(100) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `remember_code` varchar(255) NOT NULL,
  `expire_time` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
