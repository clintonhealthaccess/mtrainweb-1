-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 09, 2015 at 01:48 AM
-- Server version: 5.5.20
-- PHP Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `chaidb`
--

-- --------------------------------------------------------

--
-- Table structure for table `cthx_actions`
--

CREATE TABLE IF NOT EXISTS `cthx_actions` (
  `action_id` int(11) NOT NULL AUTO_INCREMENT,
  `action_name` varchar(50) NOT NULL,
  `label` varchar(100) NOT NULL,
  `weight` int(11) NOT NULL COMMENT 'This will be the order of a particular action relative ONLY to other actions in the module',
  `app_module_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`action_id`),
  UNIQUE KEY `action_name` (`action_name`,`label`),
  KEY `app_module_id` (`app_module_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `cthx_actions`
--

INSERT INTO `cthx_actions` (`action_id`, `action_name`, `label`, `weight`, `app_module_id`, `status`) VALUES
(1, 'upload_user_list', 'Upload HCW (Batch) List', 0, 1, 0),
(2, 'create_admin_user', 'Create Admin User', 2, 1, 1),
(3, 'manage_roles_permissions', 'Manage Admin Roles and Permissions', 5, 1, 1),
(4, 'access_cadres', 'Access Cadres', -10, 2, 1),
(5, 'create_cadre', 'Create Cadre', -9, 2, 1),
(6, 'access_facilities', 'Access Facilities', 2, 2, 1),
(7, 'create_facility', 'Create Facility', 3, 2, 1),
(8, 'access_hcw_report', 'Access HCWs Reports', 0, 3, 1),
(9, 'access_admin_users', 'Access Admin Users', 1, 1, 1),
(10, 'delete_admin_user', 'Delete Admin User', 4, 1, 1),
(11, 'delete_facility', 'Delete Facility', 5, 2, 0),
(12, 'access_usage_report', 'Access Usage Metrics Report', 0, 3, 1),
(13, 'access_assessment_report', 'Access Assessment Metrics Report', 0, 3, 1),
(14, 'access_aids_report', 'Access Job Aids & Standing Order Report', 0, 3, 1),
(15, 'update_admin_user', 'Update Admin  User', 3, 1, 1),
(16, 'update_facility', 'Update Facility', 4, 2, 1),
(17, 'update_cadre', 'Update Cadre', -8, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cthx_aids_session`
--

CREATE TABLE IF NOT EXISTS `cthx_aids_session` (
  `session_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date_viewed` datetime NOT NULL,
  `aid_id` int(11) NOT NULL,
  `aid_type` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL COMMENT '1 - App, 2 - IVR, 3 - SMS',
  PRIMARY KEY (`session_id`),
  KEY `facility_id` (`facility_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `cthx_aids_session`
--

INSERT INTO `cthx_aids_session` (`session_id`, `date_viewed`, `aid_id`, `aid_type`, `facility_id`, `channel_id`) VALUES
(11, '2014-12-03 00:00:00', 1, 1, 2, 0),
(12, '2014-12-03 00:00:00', 0, 2, 2, 0),
(13, '2014-12-04 00:00:00', 4, 1, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cthx_app_modules`
--

CREATE TABLE IF NOT EXISTS `cthx_app_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_name` varchar(100) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='RBAC Related Table' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `cthx_app_modules`
--

INSERT INTO `cthx_app_modules` (`id`, `module_name`, `weight`) VALUES
(1, 'Users', 0),
(2, 'Settings', 1),
(3, 'Reports', 2);

-- --------------------------------------------------------

--
-- Table structure for table `cthx_cadre`
--

CREATE TABLE IF NOT EXISTS `cthx_cadre` (
  `cadre_id` int(11) NOT NULL AUTO_INCREMENT,
  `cadre_title` varchar(255) NOT NULL,
  PRIMARY KEY (`cadre_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `cthx_cadre`
--

INSERT INTO `cthx_cadre` (`cadre_id`, `cadre_title`) VALUES
(1, 'Nurse'),
(2, 'Midwife'),
(3, 'CHEW');

-- --------------------------------------------------------

--
-- Table structure for table `cthx_category`
--

CREATE TABLE IF NOT EXISTS `cthx_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `cthx_category`
--

INSERT INTO `cthx_category` (`category_id`, `category_name`, `description`) VALUES
(1, 'Reproductive Health', ''),
(2, 'Maternal Health', ''),
(3, 'Newborn & Child Health', '');

-- --------------------------------------------------------

--
-- Table structure for table `cthx_counters`
--

CREATE TABLE IF NOT EXISTS `cthx_counters` (
  `job_aids` bigint(20) NOT NULL,
  `standing_order` bigint(20) NOT NULL,
  `help` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cthx_health_facility`
--

CREATE TABLE IF NOT EXISTS `cthx_health_facility` (
  `facility_id` int(11) NOT NULL AUTO_INCREMENT,
  `facility_address` varchar(255) NOT NULL,
  `facility_name` varchar(150) NOT NULL,
  `state_id` int(11) NOT NULL,
  `lga_id` int(11) NOT NULL,
  PRIMARY KEY (`facility_id`),
  KEY `state_id` (`state_id`),
  KEY `lga_id` (`lga_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `cthx_health_facility`
--

INSERT INTO `cthx_health_facility` (`facility_id`, `facility_address`, `facility_name`, `state_id`, `lga_id`) VALUES
(2, 'Plot 222, Alvan Ikoku Way ', 'XYZ Hospital', 1, 15),
(3, '124, Suncan Street, Ebonyi', 'ABC Hospital', 12, 1),
(4, '7, Governors Crescent', 'Abuja Central PHC', 1, 18),
(9, 'Garki Abuja', 'Tristan Clinic', 1, 15);

-- --------------------------------------------------------

--
-- Table structure for table `cthx_health_worker`
--

CREATE TABLE IF NOT EXISTS `cthx_health_worker` (
  `worker_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `remote_id` int(11) NOT NULL,
  `title` varchar(20) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL,
  `firstname` varchar(35) NOT NULL,
  `middlename` varchar(35) NOT NULL,
  `lastname` varchar(35) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `qualification` varchar(100) NOT NULL,
  `supervisor` tinyint(4) NOT NULL,
  `cadre_id` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL,
  PRIMARY KEY (`worker_id`),
  KEY `facility_id` (`facility_id`),
  KEY `cadre_id` (`cadre_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=107 ;

--
-- Dumping data for table `cthx_health_worker`
--

INSERT INTO `cthx_health_worker` (`worker_id`, `remote_id`, `title`, `username`, `password`, `firstname`, `middlename`, `lastname`, `gender`, `email`, `phone`, `qualification`, `supervisor`, `cadre_id`, `facility_id`) VALUES
(1, 0, '', '', '', 'Aribi', '', 'Deshi', 'Male', 'leke@techieplanetltd.com', '08034200342', '', 1, 1, 2),
(97, 0, '', '', '', 'John', 'Bob', 'Doe', 'Male', 'admin@mtrain.com', '07038551703', '', 1, 1, 4),
(98, 0, '', '', '', 'Gavin', 'Rajal', 'Jamal', 'Male', 'sandy@mtrain.com', '1234567890', '', 1, 2, 3),
(99, 0, '', '', '', 'Sandy', 'Candy', 'Mandy', 'Female', 'sandy@mtrain.com', '1234567890', '', 1, 3, 2),
(100, 0, '', '', '', 'Mon', 'Ami', 'Vin', 'Female', 'sandy@mtrain.com', '1234567890', '', 1, 1, 4),
(101, 0, '', '', '', 'John', 'Bob', 'Doe', 'Male', 'admin@mtrain.com', '1234567890', '', 1, 1, 3),
(102, 0, '', '', '', 'Mon', 'Ami', 'Vin', 'Female', 'sandy@mtrain.com', '1234567890', '', 1, 1, 2),
(103, 0, '', '', '', 'Sandy', 'Candy', 'Mandy', 'Female', 'sandy@mtrain.com', '1234567890', '', 1, 3, 3),
(104, 0, '', '', '', 'Gavin', 'Rajal', 'Jamal', 'Male', 'sandy@mtrain.com', '1234567890', '', 1, 2, 4),
(105, 0, '', '', '', 'Gavin', 'Rajal', 'Jamal', 'Male', 'sandy@mtrain.com', '1234567890', '', 1, 2, 3),
(106, 0, '', '', '', 'John', 'Bob', 'Doe', 'Male', 'admin@mtrain.com', '1234567890', '', 1, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `cthx_jobaid_to_module`
--

CREATE TABLE IF NOT EXISTS `cthx_jobaid_to_module` (
  `aid_id` bigint(11) NOT NULL,
  `module_id` bigint(11) NOT NULL,
  PRIMARY KEY (`aid_id`,`module_id`),
  KEY `module_id` (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cthx_jobaid_to_module`
--

INSERT INTO `cthx_jobaid_to_module` (`aid_id`, `module_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 2),
(5, 3);

-- --------------------------------------------------------

--
-- Table structure for table `cthx_job_aid`
--

CREATE TABLE IF NOT EXISTS `cthx_job_aid` (
  `aid_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `aid_file` varchar(255) NOT NULL,
  PRIMARY KEY (`aid_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `cthx_job_aid`
--

INSERT INTO `cthx_job_aid` (`aid_id`, `title`, `aid_file`) VALUES
(1, 'Job Aid 1', 'jobaid1.pdf'),
(2, 'Job Aid 2', 'jobaid2.pdf'),
(3, 'Job Aid 3', 'jobaid3.pdf'),
(4, 'Job Aid 1', 'jobaid1.pdf'),
(5, 'Job Aid 1', 'jobaid1.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `cthx_lga`
--

CREATE TABLE IF NOT EXISTS `cthx_lga` (
  `lga_id` int(11) NOT NULL AUTO_INCREMENT,
  `lga_name` varchar(255) NOT NULL,
  `state_id` int(11) NOT NULL,
  PRIMARY KEY (`lga_id`),
  KEY `state_id` (`state_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `cthx_lga`
--

INSERT INTO `cthx_lga` (`lga_id`, `lga_name`, `state_id`) VALUES
(1, 'Afikpo South ', 12),
(2, 'Afikpo North ', 12),
(3, 'Onicha ', 12),
(4, 'Ohaozara ', 12),
(5, 'Abakaliki ', 12),
(6, 'Ishielu ', 12),
(7, 'lkwo ', 12),
(8, 'Ezza ', 12),
(9, 'Ezza South ', 12),
(10, 'Ohaukwu ', 12),
(11, 'Ebonyi ', 12),
(12, 'Ivo ', 12),
(13, 'Gwagwalada ', 1),
(14, 'Kuje ', 1),
(15, 'Abaji', 1),
(16, ' Abuja Municipal', 1),
(17, 'Bwari ', 1),
(18, 'Kwali', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cthx_partner`
--

CREATE TABLE IF NOT EXISTS `cthx_partner` (
  `partner_id` int(11) NOT NULL AUTO_INCREMENT,
  `partner_name` varchar(255) NOT NULL,
  `remarks` varchar(255) NOT NULL,
  PRIMARY KEY (`partner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cthx_roles`
--

CREATE TABLE IF NOT EXISTS `cthx_roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_title` varchar(255) NOT NULL,
  `level` int(11) NOT NULL,
  `permissions` text NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `cthx_roles`
--

INSERT INTO `cthx_roles` (`role_id`, `role_title`, `level`, `permissions`) VALUES
(1, 'LG Officer', 1, '{"access_admin_users":"on","access_cadres":"on","access_facilities":"on","update_facility":"on","access_hcw_report":"on","access_usage_report":"on","access_assessment_report":"on","access_aids_report":"on"}'),
(2, 'State Officer', 2, '{"access_admin_users":"on","access_cadres":"on","access_facilities":"on","create_facility":"on","update_facility":"on","access_hcw_report":"on","access_usage_report":"on","access_assessment_report":"on","access_aids_report":"on"}'),
(3, 'FMOH Officer', 3, '{"access_admin_users":"on","access_cadres":"on","update_cadre":"on","access_facilities":"on","create_facility":"on","update_facility":"on","access_hcw_report":"on","access_usage_report":"on","access_assessment_report":"on","access_aids_report":"on"}'),
(4, 'Administrator', 4, '{"upload_user_list":"on","access_admin_users":"on","create_admin_user":"on","update_admin_user":"on","delete_admin_user":"on","manage_roles_permissions":"on","access_cadres":"on","create_cadre":"on","update_cadre":"on","access_facilities":"on","create_facility":"on","update_facility":"on","access_hcw_report":"on","access_usage_report":"on","access_assessment_report":"on","access_aids_report":"on"}');

-- --------------------------------------------------------

--
-- Table structure for table `cthx_settings`
--

CREATE TABLE IF NOT EXISTS `cthx_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `settings_name` varchar(100) NOT NULL,
  `system_name` varchar(100) NOT NULL,
  `jsontext` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cthx_settings`
--

INSERT INTO `cthx_settings` (`id`, `settings_name`, `system_name`, `jsontext`) VALUES
(1, 'Last API Calls', 'last_api_calls', '{"ivr":"2015-01-15 17:08:45"}');

-- --------------------------------------------------------

--
-- Table structure for table `cthx_state`
--

CREATE TABLE IF NOT EXISTS `cthx_state` (
  `state_id` int(11) NOT NULL AUTO_INCREMENT,
  `state_name` varchar(100) NOT NULL,
  PRIMARY KEY (`state_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `cthx_state`
--

INSERT INTO `cthx_state` (`state_id`, `state_name`) VALUES
(1, 'FCT Abuja'),
(2, 'Abia'),
(3, 'Adamawa'),
(4, 'Akwa Ibom'),
(5, 'Anambra'),
(6, 'Bauchi '),
(7, 'Bayelsa '),
(8, 'Benue '),
(9, 'Borno '),
(10, 'Cross River'),
(11, 'Delta '),
(12, 'Ebonyi '),
(13, 'Edo '),
(14, 'Ekiti '),
(15, 'Enugu '),
(16, 'Gombe'),
(17, 'Imo '),
(18, 'Jigawa '),
(19, 'Kaduna '),
(20, 'Kano '),
(21, 'Katsina '),
(22, 'Kebbi '),
(23, 'Kogi '),
(24, 'Kwara '),
(25, 'Lagos '),
(26, 'Nassarawa '),
(27, 'Niger '),
(28, 'Ogun '),
(29, 'Ondo '),
(30, 'Osun '),
(31, 'Oyo '),
(32, 'Plateau '),
(33, 'Rivers '),
(34, 'Sokoto '),
(35, 'Taraba '),
(36, 'Yobe'),
(37, 'Zamfara');

-- --------------------------------------------------------

--
-- Table structure for table `cthx_system_admin`
--

CREATE TABLE IF NOT EXISTS `cthx_system_admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL,
  `salt` varchar(6) NOT NULL,
  `firstname` varchar(35) NOT NULL,
  `middlename` varchar(35) NOT NULL,
  `lastname` varchar(35) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `role_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `lga_id` int(11) NOT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `username` (`username`),
  KEY `role_id` (`role_id`),
  KEY `state_id` (`state_id`),
  KEY `lga_id` (`lga_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `cthx_system_admin`
--

INSERT INTO `cthx_system_admin` (`admin_id`, `username`, `password`, `salt`, `firstname`, `middlename`, `lastname`, `gender`, `email`, `phone`, `role_id`, `state_id`, `lga_id`) VALUES
(5, 'demo', '0371a9a3122b8a63dbfef5afabcc51f1', '75bdb4', 'Demo', 'Demo', 'Demo', 'Male', 'demo@hmail.com', '2348038445144', 1, 1, 15),
(6, 'demo1', '8473aad6f567c72474d8942d115003be', '60229f', 'John', 'Seun', 'Doe', 'Male', 'john@yahoo.com', '08038445144', 2, 1, 15),
(7, 'admin', '77b5ccc21da59bc9d2fd7bc92cf3b066', '2c6b4e', 'Admin', 'Admin', 'Admin', 'Male', 'admin@gmail.com', '08038445144', 4, 1, 15),
(10, 'demofm', 'b780dc03e0a2a4865719f26eb8961303', '7609f8', 'FMOH', 'FMOH', 'FMOH', 'Male', 'fm@ng.com', '123456789', 3, 12, 1);

-- --------------------------------------------------------

--
-- Table structure for table `cthx_test`
--

CREATE TABLE IF NOT EXISTS `cthx_test` (
  `test_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `module_id` bigint(20) NOT NULL,
  PRIMARY KEY (`test_id`),
  KEY `module_id` (`module_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `cthx_test`
--

INSERT INTO `cthx_test` (`test_id`, `title`, `module_id`) VALUES
(1, 'Family Planning', 1),
(2, 'MCPD', 2),
(3, 'MNC', 3),
(4, 'MCCI', 4);

-- --------------------------------------------------------

--
-- Table structure for table `cthx_test_question`
--

CREATE TABLE IF NOT EXISTS `cthx_test_question` (
  `question_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `options` text NOT NULL,
  `correct_option` varchar(255) NOT NULL,
  `test_id` bigint(20) NOT NULL,
  `tiptext` text NOT NULL,
  PRIMARY KEY (`question_id`),
  KEY `test_id` (`test_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cthx_test_session`
--

CREATE TABLE IF NOT EXISTS `cthx_test_session` (
  `session_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `date_taken` datetime NOT NULL,
  `score` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `improvement` double NOT NULL COMMENT 'we are assuming that the value for this column will be sent by the mobile app. the app does not do that at the time of creating this field.',
  `test_id` bigint(20) NOT NULL,
  `worker_id` bigint(20) NOT NULL,
  `facility_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL COMMENT '1 - App, 2 - IVR, 3 - SMS',
  PRIMARY KEY (`session_id`),
  KEY `test_id` (`test_id`),
  KEY `facility_id` (`facility_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=48 ;

--
-- Dumping data for table `cthx_test_session`
--

INSERT INTO `cthx_test_session` (`session_id`, `date_taken`, `score`, `total`, `improvement`, `test_id`, `worker_id`, `facility_id`, `channel_id`) VALUES
(11, '2014-11-03 00:00:00', 2, 4, 50, 1, 97, 4, 0),
(12, '2014-11-02 00:00:00', 3, 4, 75, 2, 98, 3, 0),
(13, '2014-11-02 00:00:00', 1, 4, 25, 3, 99, 2, 0),
(14, '2014-11-03 00:00:00', 4, 4, 50, 1, 101, 3, 0),
(15, '2014-11-04 00:00:00', 2, 4, 50, 4, 102, 2, 0),
(16, '2014-11-03 00:00:00', 3, 4, 50, 1, 103, 3, 0),
(17, '2014-11-04 00:00:00', 4, 4, 100, 4, 104, 4, 0),
(18, '2014-11-03 00:00:00', 3, 4, 25, 2, 105, 3, 0),
(19, '2014-11-02 00:00:00', 1, 4, 25, 3, 106, 3, 0),
(20, '2014-11-03 00:00:00', 4, 4, 25, 1, 98, 3, 0),
(31, '2014-11-03 00:00:00', 3, 4, 50, 2, 97, 4, 0),
(32, '2014-11-02 00:00:00', 2, 4, 50, 1, 98, 3, 0),
(33, '2014-11-02 00:00:00', 3, 4, 75, 4, 99, 2, 0),
(34, '2014-11-03 00:00:00', 4, 4, 25, 1, 101, 3, 0),
(35, '2014-11-04 00:00:00', 3, 4, 75, 2, 102, 4, 0),
(36, '2014-11-03 00:00:00', 3, 4, 25, 3, 103, 3, 0),
(37, '2014-11-04 00:00:00', 1, 4, 25, 4, 104, 4, 0),
(38, '2014-11-03 00:00:00', 4, 4, 25, 3, 105, 3, 0),
(39, '2014-11-02 00:00:00', 3, 4, 50, 1, 106, 3, 0),
(40, '2014-11-03 00:00:00', 4, 4, 75, 1, 98, 3, 0),
(41, '2014-12-02 00:00:00', 4, 10, 0, 1, 1, 2, 0),
(42, '2014-12-02 00:00:00', 5, 10, 0, 1, 1, 2, 0),
(43, '2014-12-02 00:00:00', 8, 10, 0, 1, 1, 2, 0),
(44, '2014-12-02 00:00:00', 4, 10, -40, 1, 1, 2, 0),
(45, '2014-12-02 00:00:00', 2, 10, -60, 1, 1, 2, 0),
(46, '2014-12-02 00:00:00', 8, 10, 40, 1, 1, 2, 0),
(47, '2014-12-02 00:00:00', 8, 10, 40, 1, 1, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cthx_training`
--

CREATE TABLE IF NOT EXISTS `cthx_training` (
  `training_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `training_title` varchar(255) NOT NULL,
  `video_file` varchar(255) NOT NULL,
  PRIMARY KEY (`training_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `cthx_training`
--

INSERT INTO `cthx_training` (`training_id`, `training_title`, `video_file`) VALUES
(1, 'Equipment and Materials', 'refer_sick_baby.mp4'),
(2, 'Follow-up Counselling', 'cold_baby.mp4'),
(3, 'Removing Contraceptive Implant Capsules', 'breathing_problems.mp4'),
(4, 'Barrier methods of contraception - The Female condom', ''),
(5, 'Emergency Contraception', ''),
(6, 'Bleeding after childbirth(postpartum haemorrhage)', 'breathing_problems.mp4'),
(7, 'Pre-eclampsia and Eclampsia', 'refer_sick_baby.mp4'),
(8, 'Bleeding in early pregnancy (Unsafe Abortion)', 'cold_baby.mp4'),
(9, 'Bleeding in Late Pregnancy', ''),
(10, 'Admitting a woman in Labour and Partograph', ''),
(11, 'Social support in Labour', ''),
(12, 'Prolonged obstructed labour', ''),
(13, 'Other indirect causes of maternal and newborn mortality', ''),
(14, 'Prevention and Management of Sepsis', ''),
(15, 'Examination of the newborn baby', 'refer_sick_baby.mp4'),
(16, 'Care of the newborn baby until discharge', 'breathing_problems.mp4'),
(17, 'Neonatal sepsis', 'cold_baby.mp4'),
(18, 'Communicate and counsel', ''),
(19, 'Special situations', ''),
(20, 'Assess and classify; Identify treatment; Treat the sick child or young infant', 'breathing_problems.mp4');

-- --------------------------------------------------------

--
-- Table structure for table `cthx_training_module`
--

CREATE TABLE IF NOT EXISTS `cthx_training_module` (
  `module_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `module_title` varchar(255) NOT NULL,
  `guide_file` varchar(255) NOT NULL,
  `remarks` text NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`module_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `cthx_training_module`
--

INSERT INTO `cthx_training_module` (`module_id`, `module_title`, `guide_file`, `remarks`, `category_id`) VALUES
(1, 'fp', 'fp.pdf', '', 1),
(2, 'mcpd', 'mcpd.pdf', '', 2),
(3, 'mnc', 'mnc.pdf', '', 3),
(4, 'mcci', 'mcci.pdf', '', 3);

-- --------------------------------------------------------

--
-- Table structure for table `cthx_training_session`
--

CREATE TABLE IF NOT EXISTS `cthx_training_session` (
  `session_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` tinyint(4) NOT NULL,
  `session_type` tinyint(4) NOT NULL,
  `material_type` tinyint(4) NOT NULL,
  `worker_id` bigint(20) NOT NULL,
  `module_id` bigint(20) NOT NULL,
  `training_id` bigint(20) NOT NULL,
  `facility_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL COMMENT '1 - App, 2 - SMS, 3 - IVR, ',
  PRIMARY KEY (`session_id`),
  KEY `training_id` (`training_id`),
  KEY `module_id` (`module_id`),
  KEY `facility_id` (`facility_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `cthx_training_session`
--

INSERT INTO `cthx_training_session` (`session_id`, `start_time`, `end_time`, `status`, `session_type`, `material_type`, `worker_id`, `module_id`, `training_id`, `facility_id`, `channel_id`) VALUES
(1, '2014-11-15 00:00:00', '2014-11-15 00:00:00', 2, 1, 1, 97, 1, 1, 4, 0),
(2, '2014-11-04 00:00:00', '2014-11-03 00:00:00', 1, 2, 1, 98, 1, 2, 3, 0),
(3, '2014-11-03 00:00:00', '2014-11-03 00:00:00', 2, 1, 2, 99, 1, 3, 2, 0),
(4, '2014-11-03 00:00:00', '2014-11-03 00:00:00', 2, 1, 1, 97, 2, 6, 4, 0),
(5, '2014-11-15 00:00:00', '2014-11-15 00:00:00', 1, 2, 1, 101, 1, 1, 3, 0),
(6, '2014-11-04 00:00:00', '2014-11-04 00:00:00', 2, 1, 1, 102, 2, 8, 2, 0),
(7, '2014-11-02 00:00:00', '2014-11-02 00:00:00', 2, 1, 2, 103, 1, 1, 3, 0),
(8, '2014-11-04 00:00:00', '2014-11-04 00:00:00', 1, 1, 1, 104, 1, 2, 4, 0),
(9, '2014-11-04 00:00:00', '2014-11-03 00:00:00', 2, 1, 2, 105, 2, 6, 3, 0),
(10, '2014-11-02 00:00:00', '2014-11-02 00:00:00', 2, 1, 1, 106, 2, 7, 3, 0),
(26, '2014-11-26 10:13:30', '2014-11-26 10:20:45', 1, 1, 3, 1, 1, 1, 2, 0),
(27, '2014-11-26 10:13:30', '2014-11-26 10:20:32', 1, 1, 3, 1, 1, 1, 2, 0),
(28, '2014-11-26 10:13:30', '2014-11-26 10:20:45', 1, 1, 3, 1, 1, 1, 2, 0),
(31, '2015-01-15 15:55:02', '2015-01-15 15:55:07', 2, 1, 3, 97, 1, 3, 4, 2),
(32, '2014-12-02 14:33:48', '2014-12-02 14:33:42', 2, 1, 3, 1, 1, 2, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `cthx_training_to_module`
--

CREATE TABLE IF NOT EXISTS `cthx_training_to_module` (
  `module_id` bigint(20) NOT NULL,
  `training_id` bigint(20) NOT NULL,
  PRIMARY KEY (`module_id`,`training_id`),
  KEY `training_id` (`training_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cthx_training_to_module`
--

INSERT INTO `cthx_training_to_module` (`module_id`, `training_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(2, 6),
(2, 7),
(2, 8),
(2, 9),
(2, 10),
(2, 11),
(2, 12),
(2, 13),
(2, 14),
(3, 15),
(3, 16),
(3, 17),
(3, 18),
(3, 19),
(4, 20);

-- --------------------------------------------------------

--
-- Table structure for table `cthx_user_guide`
--

CREATE TABLE IF NOT EXISTS `cthx_user_guide` (
  `guide_id` int(11) NOT NULL AUTO_INCREMENT,
  `guide_title` varchar(255) NOT NULL,
  `guide_file` varchar(255) NOT NULL,
  PRIMARY KEY (`guide_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cthx_actions`
--
ALTER TABLE `cthx_actions`
  ADD CONSTRAINT `cthx_actions_ibfk_1` FOREIGN KEY (`app_module_id`) REFERENCES `cthx_app_modules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cthx_aids_session`
--
ALTER TABLE `cthx_aids_session`
  ADD CONSTRAINT `cthx_aids_session_ibfk_2` FOREIGN KEY (`facility_id`) REFERENCES `cthx_health_facility` (`facility_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cthx_health_facility`
--
ALTER TABLE `cthx_health_facility`
  ADD CONSTRAINT `cthx_health_facility_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `cthx_state` (`state_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cthx_health_facility_ibfk_2` FOREIGN KEY (`lga_id`) REFERENCES `cthx_lga` (`lga_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cthx_health_worker`
--
ALTER TABLE `cthx_health_worker`
  ADD CONSTRAINT `cthx_health_worker_ibfk_1` FOREIGN KEY (`facility_id`) REFERENCES `cthx_health_facility` (`facility_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cthx_health_worker_ibfk_2` FOREIGN KEY (`cadre_id`) REFERENCES `cthx_cadre` (`cadre_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cthx_jobaid_to_module`
--
ALTER TABLE `cthx_jobaid_to_module`
  ADD CONSTRAINT `cthx_jobaid_to_module_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `cthx_training_module` (`module_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cthx_jobaid_to_module_ibfk_2` FOREIGN KEY (`aid_id`) REFERENCES `cthx_job_aid` (`aid_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cthx_jobaid_to_module_ibfk_3` FOREIGN KEY (`aid_id`) REFERENCES `cthx_job_aid` (`aid_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cthx_lga`
--
ALTER TABLE `cthx_lga`
  ADD CONSTRAINT `cthx_lga_ibfk_1` FOREIGN KEY (`state_id`) REFERENCES `cthx_state` (`state_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cthx_system_admin`
--
ALTER TABLE `cthx_system_admin`
  ADD CONSTRAINT `cthx_system_admin_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `cthx_roles` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cthx_system_admin_ibfk_2` FOREIGN KEY (`state_id`) REFERENCES `cthx_state` (`state_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cthx_system_admin_ibfk_3` FOREIGN KEY (`lga_id`) REFERENCES `cthx_lga` (`lga_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cthx_test`
--
ALTER TABLE `cthx_test`
  ADD CONSTRAINT `cthx_test_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `cthx_training_module` (`module_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cthx_test_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `cthx_training_module` (`module_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cthx_test_ibfk_3` FOREIGN KEY (`module_id`) REFERENCES `cthx_training_module` (`module_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cthx_test_ibfk_4` FOREIGN KEY (`module_id`) REFERENCES `cthx_training_module` (`module_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cthx_test_question`
--
ALTER TABLE `cthx_test_question`
  ADD CONSTRAINT `cthx_test_question_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `cthx_test` (`test_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cthx_test_session`
--
ALTER TABLE `cthx_test_session`
  ADD CONSTRAINT `cthx_test_session_ibfk_1` FOREIGN KEY (`test_id`) REFERENCES `cthx_test` (`test_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cthx_test_session_ibfk_2` FOREIGN KEY (`test_id`) REFERENCES `cthx_test` (`test_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cthx_test_session_ibfk_3` FOREIGN KEY (`test_id`) REFERENCES `cthx_test` (`test_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cthx_test_session_ibfk_4` FOREIGN KEY (`test_id`) REFERENCES `cthx_test` (`test_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cthx_test_session_ibfk_5` FOREIGN KEY (`test_id`) REFERENCES `cthx_test` (`test_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cthx_test_session_ibfk_6` FOREIGN KEY (`test_id`) REFERENCES `cthx_test` (`test_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cthx_test_session_ibfk_7` FOREIGN KEY (`test_id`) REFERENCES `cthx_test` (`test_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cthx_test_session_ibfk_8` FOREIGN KEY (`test_id`) REFERENCES `cthx_test` (`test_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cthx_training_module`
--
ALTER TABLE `cthx_training_module`
  ADD CONSTRAINT `cthx_training_module_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `cthx_category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cthx_training_session`
--
ALTER TABLE `cthx_training_session`
  ADD CONSTRAINT `cthx_training_session_ibfk_1` FOREIGN KEY (`training_id`) REFERENCES `cthx_training` (`training_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cthx_training_session_ibfk_13` FOREIGN KEY (`module_id`) REFERENCES `cthx_training_module` (`module_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cthx_training_session_ibfk_14` FOREIGN KEY (`facility_id`) REFERENCES `cthx_health_facility` (`facility_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cthx_training_session_ibfk_2` FOREIGN KEY (`training_id`) REFERENCES `cthx_training` (`training_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cthx_training_session_ibfk_3` FOREIGN KEY (`training_id`) REFERENCES `cthx_training` (`training_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cthx_training_session_ibfk_4` FOREIGN KEY (`training_id`) REFERENCES `cthx_training` (`training_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cthx_training_to_module`
--
ALTER TABLE `cthx_training_to_module`
  ADD CONSTRAINT `cthx_training_to_module_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `cthx_training_module` (`module_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cthx_training_to_module_ibfk_2` FOREIGN KEY (`training_id`) REFERENCES `cthx_training` (`training_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
