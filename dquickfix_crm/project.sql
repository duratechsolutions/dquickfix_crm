-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 14, 2013 at 12:48 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `computer_details`
--

CREATE TABLE IF NOT EXISTS `computer_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plan` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL,
  `valid_upto` date NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `mac_id` varchar(50) DEFAULT NULL,
  `os` varchar(100) DEFAULT NULL,
  `mark_and_mode` varchar(255) DEFAULT NULL,
  `info_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `computer_details`
--

INSERT INTO `computer_details` (`id`, `plan`, `amount`, `valid_upto`, `customer_id`, `mac_id`, `os`, `mark_and_mode`, `info_id`, `created_by`, `date`) VALUES
(1, '1 Year 1 Computer', 100, '2014-01-10', 1, 'qwerty', 'Windows Server 2003', 'Test mode', 1, 1, '2013-04-14 09:02:16'),
(2, '1 Year 1 Computer', 100, '2014-02-02', 1, 'qwerty', 'Windows Vista', 'Test mode', 2, 1, '2013-04-13 22:36:54'),
(3, '1 Year 1 Computer', 500, '2013-06-06', 1, 'qwerty', 'Windows Vista', 'Test mode', 3, 1, '2013-04-13 22:36:05'),
(4, '1 Year 1 Computer', 555, '2014-02-02', 4, 'qwerty1234', 'Windows Vista', NULL, 5, 1, '2013-04-14 09:56:36'),
(5, 'Gold Plan', 5000, '2014-04-04', 2, 'qwerty1234', 'Windows Server 2003 R2', NULL, 6, 2, '2013-04-14 10:28:59');

-- --------------------------------------------------------

--
-- Table structure for table `customer_details`
--

CREATE TABLE IF NOT EXISTS `customer_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `alternate_phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `street` varchar(100) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(50) NOT NULL,
  `country` varchar(50) NOT NULL,
  `create_by` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `customer_details`
--

INSERT INTO `customer_details` (`id`, `first_name`, `last_name`, `phone`, `alternate_phone`, `email`, `street`, `address`, `city`, `country`, `create_by`, `date`) VALUES
(1, 'Jagadesh', 'G', '1234567890', NULL, 'jag@gmail.com', 'Street-123', 'Test address', 'Salem', 'United States', 1, '2013-04-13 21:21:16'),
(2, 'Raja', 'R', '1234567890', NULL, 'raj@mail.com', 'Street-1', 'Sample address', 'Coimbatore', 'United States', 1, '2013-04-13 20:54:46'),
(3, 'Prabu', 'P', '1234567890', NULL, 'prabu@mail.com', 'Street-1', 'Test address', 'Chennai', 'United States', 1, '2013-04-13 20:56:08'),
(4, 'Kumar', 'G', '1234567892', NULL, 'test@gmail.com', 'Street-1', 'test', 'Salem', 'India', 1, '2013-04-14 09:55:19');

-- --------------------------------------------------------

--
-- Table structure for table `issue_info`
--

CREATE TABLE IF NOT EXISTS `issue_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) NOT NULL,
  `notes` text NOT NULL,
  `status` varchar(255) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `issue_info`
--

INSERT INTO `issue_info` (`id`, `subject`, `notes`, `status`, `customer_id`, `created_by`, `date`) VALUES
(1, 'Hardware', 'Mouse is not working1', 'Open - Callback', 1, 1, '2013-04-14 08:41:12'),
(2, 'Keyboard', 'Keyboard not working.', 'Open â€“ Callback', 1, 1, '2013-04-13 22:10:39'),
(3, 'CPU ', 'CPU not working', 'Resolved', 1, 1, '2013-04-13 22:35:35'),
(5, 'Ms Office', 'MS-Office not wokking.', 'Open - Callback', 4, 1, '2013-04-14 09:55:57'),
(6, 'Test', 'TEst', 'Open-Awaiting Customers Callback', 2, 2, '2013-04-14 10:28:37');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE IF NOT EXISTS `login` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `emp_id` varchar(50) NOT NULL,
  `level` tinyint(4) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`user_id`, `email`, `password`, `first_name`, `last_name`, `emp_id`, `level`, `create_date`) VALUES
(1, 'vijay@gmail.com', '*A4B6157319038724E3560894F7F932C8886EBFCF', 'vijay', 'mathew', '123', 1, '2013-04-13 12:59:31'),
(2, 'jaga@gmail.com', '*A4B6157319038724E3560894F7F932C8886EBFCF', 'Jagadesh', 'Ganapathy', '100', 2, '2013-04-13 14:44:13');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
