-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 16, 2014 at 06:56 PM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `jtabletestdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `cpassword` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin5 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`, `cpassword`, `role`) VALUES
(1, 'spekky96', '', '', 'State Officer'),
(2, 'charly2014', '', '', 'FMOH Officer'),
(6, 'demopp', 'demopp', 'demopp', 'LG Officer'),
(7, 'keffiTechi', 'keffiTechi', 'keffiTechi', 'FMOH Officer');

-- --------------------------------------------------------

--
-- Table structure for table `assessment_metrics`
--

CREATE TABLE IF NOT EXISTS `assessment_metrics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cadre` varchar(20) NOT NULL,
  `number_of_health_care_workers` varchar(50) NOT NULL,
  `number_of_health_care_workers_taking_tests` varchar(50) NOT NULL,
  `number_of_tests_taken` varchar(50) NOT NULL,
  `high_performing_score` varchar(50) NOT NULL,
  `average_score` varchar(50) NOT NULL,
  `underperforming_score` varchar(50) NOT NULL,
  `failed_score` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin5 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `assessment_metrics`
--

INSERT INTO `assessment_metrics` (`id`, `cadre`, `number_of_health_care_workers`, `number_of_health_care_workers_taking_tests`, `number_of_tests_taken`, `high_performing_score`, `average_score`, `underperforming_score`, `failed_score`) VALUES
(1, 'Nurses', '100', '250', '30', '20%', '50%', '20%', '20%'),
(2, 'Midwives', '150', '100', '100', '30%', '25%', '25%', '50%'),
(3, 'CHEWs', '100', '100', '30', '50%', '25%', '55%', '30%'),
(4, 'Total', '350', '450', '160', '100%', '100%', '100%', '100%');

-- --------------------------------------------------------

--
-- Table structure for table `cadre`
--

CREATE TABLE IF NOT EXISTS `cadre` (
  `cadreId` int(11) NOT NULL AUTO_INCREMENT,
  `cadreName` varchar(20) NOT NULL,
  PRIMARY KEY (`cadreId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin5 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `cadre`
--

INSERT INTO `cadre` (`cadreId`, `cadreName`) VALUES
(1, 'CHEW'),
(2, 'Midwife'),
(3, 'Nurse');

-- --------------------------------------------------------

--
-- Table structure for table `job_aids_and_standing_orders_views`
--

CREATE TABLE IF NOT EXISTS `job_aids_and_standing_orders_views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `indicator` varchar(50) NOT NULL,
  `views` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin5 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `job_aids_and_standing_orders_views`
--

INSERT INTO `job_aids_and_standing_orders_views` (`id`, `indicator`, `views`) VALUES
(1, 'Standing Orders', 128),
(2, 'Job Aids', 176);

-- --------------------------------------------------------

--
-- Table structure for table `people`
--

CREATE TABLE IF NOT EXISTS `people` (
  `PersonId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `FacilityName` varchar(245) NOT NULL,
  `FacilityAddress` varchar(450) NOT NULL,
  `LocalGovernmentArea` varchar(250) NOT NULL,
  `State` varchar(100) NOT NULL,
  PRIMARY KEY (`PersonId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin5 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `people`
--

INSERT INTO `people` (`PersonId`, `FacilityName`, `FacilityAddress`, `LocalGovernmentArea`, `State`) VALUES
(1, 'R-jolad Hospital', '245, Newgarrage Round About, Opposite MRS Filling Station', 'Gbagada', 'Lagos'),
(2, 'Ogudu Health Center', '17, Ajibike Street, Ogudu', 'Kosofe', 'Lagos'),
(3, 'Gbagada  General Hospital', '12, Medinah Estate', 'Shomolu', 'Lagos'),
(4, 'Lasuth', '103-106 Obakran Road', 'Kosofe', 'Lagos'),
(5, 'Magodo Health Center', '101 Magodo, Isheri.', 'Gbagada', 'Lagos'),
(6, 'Shomolu Health Clinic', '1, Adeniyi Jones Street', 'Shomolu', 'Lagos'),
(8, 'XYZ Hospiy', 'Festac ', 'Gbagada', 'Lagos'),
(11, 'Clinton Health Hospital', '367, Mazamaza, Garki, Abuja', 'Gbagada', 'Abuja');

-- --------------------------------------------------------

--
-- Table structure for table `training_metrics`
--

CREATE TABLE IF NOT EXISTS `training_metrics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cadre` varchar(20) NOT NULL,
  `number_of_health_care_workers` int(10) NOT NULL,
  `trainings_completed` int(10) NOT NULL,
  `topic_views` int(10) NOT NULL,
  `number_of_topics_viewed` varchar(10) NOT NULL,
  `number_of_guide_views` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin5 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `training_metrics`
--

INSERT INTO `training_metrics` (`id`, `cadre`, `number_of_health_care_workers`, `trainings_completed`, `topic_views`, `number_of_topics_viewed`, `number_of_guide_views`) VALUES
(1, 'Nurses', 200, 100, 250, '100 of 500', 50),
(2, 'Midwives', 100, 150, 100, '30 of 500', 20),
(3, 'CHEWs', 100, 100, 100, '30 of 500', 20),
(4, 'Total', 100, 350, 450, '160 of 500', 90);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `users_id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `cadre` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `state` varchar(50) NOT NULL,
  `lga` varchar(100) NOT NULL,
  `facility` varchar(245) NOT NULL,
  PRIMARY KEY (`users_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin5 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`users_id`, `firstname`, `middlename`, `lastname`, `cadre`, `phone`, `gender`, `state`, `lga`, `facility`) VALUES
(1, 'Adam', 'Smith', 'Cambell', 'Midwife', '080232847738', 'Male', 'Ekiti', 'Gbagada', 'Secretariat General Hospital'),
(2, 'Andre', 'Phill', 'Solange', 'Nurse', '08166834002', 'Female', 'Lagos', 'Kosofe', '(LASUTH)Lagos State University Teaching Hospital'),
(4, 'Femi', 'ewdwfd', 'edwd', 'Midwife', '08166834002', 'Male', 'Akwa Ibom', 'Shomolu', '(LASUTH)Lagos State University Teaching Hospital');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
