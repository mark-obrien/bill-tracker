-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 16, 2014 at 05:44 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `zend`
--

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE IF NOT EXISTS `bill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `artist` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `testcol` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `bill`
--

INSERT INTO `bill` (`id`, `artist`, `title`, `testcol`) VALUES
(2, 'Adele123ss', '212s', 'TestCol2'),
(3, 'Bruce  Springsteen', 'Wrecking Ball (Deluxe)', 'TestCol3'),
(4, 'Lana  Del  Rey', 'Born  To  Die', 'TestCol3'),
(5, 'Gotye', 'Making  Mirrors', 'TestCol4'),
(9, 'Test', 'Homepage', NULL),
(10, 'Adele', '212', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_history`
--

CREATE TABLE IF NOT EXISTS `payment_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_id` int(11) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_amount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `payment_history`
--

INSERT INTO `payment_history` (`id`, `bill_id`, `payment_date`, `payment_amount`) VALUES
(1, 0, '0000-00-00', 123),
(2, 0, '0000-00-00', 123),
(3, 0, '0000-00-00', 123),
(4, 0, '0000-00-00', 123),
(5, 0, '0000-00-00', 123),
(6, 0, '0000-00-00', 123),
(7, 0, '0000-00-00', 123),
(8, 0, '0000-00-00', 123),
(9, 0, '0000-00-00', 123),
(10, 0, '0000-00-00', 123),
(11, 0, '0000-00-00', 123),
(12, 0, '0000-00-00', 123),
(13, 0, '0000-00-00', 123);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
