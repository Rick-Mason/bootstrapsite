-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 28, 2015 at 12:11 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bootstrap`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`user_id` int(11) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_name` varchar(20) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_email`, `user_password`, `user_name`) VALUES
(1, 'code@codedancer.com', 'P14VtlebkRFSsNY7$JdZxHhQZac9bVldWeJxm64MfB6KOlq.hzWEZvMSwRp1xyQGchUThYh.4YFBqCB4nJ6WFayTIR6m9Ei3oFytnB.', 'codedancer'),
(2, 'mario@mariocart.com', 'P14VtlebkRFSsNY7$JdZxHhQZac9bVldWeJxm64MfB6KOlq.hzWEZvMSwRp1xyQGchUThYh.4YFBqCB4nJ6WFayTIR6m9Ei3oFytnB.', 'mario');

-- --------------------------------------------------------

--
-- Table structure for table `user_description`
--

CREATE TABLE IF NOT EXISTS `user_description` (
  `user_id` int(11) NOT NULL,
  `user_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_description`
--

INSERT INTO `user_description` (`user_id`, `user_description`) VALUES
(1, '&lt;strong&gt;This is strong Text&lt;/strong&gt;'),
(2, '&lt;strong&gt;This is Mario&lt;/strong&gt;&lt;br /&gt;Want to ride with me?');

-- --------------------------------------------------------

--
-- Table structure for table `user_image`
--

CREATE TABLE IF NOT EXISTS `user_image` (
  `user_id` int(11) NOT NULL,
  `image_name` varchar(18) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_image`
--

INSERT INTO `user_image` (`user_id`, `image_name`) VALUES
(1, '54c8a2c0cc92e.png'),
(2, '54c89d58ae87b.png');

-- --------------------------------------------------------

--
-- Table structure for table `user_personal_info`
--

CREATE TABLE IF NOT EXISTS `user_personal_info` (
  `user_id` int(11) NOT NULL,
  `user_phone` varchar(11) NOT NULL,
  `user_first` varchar(20) NOT NULL,
  `user_last` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_personal_info`
--

INSERT INTO `user_personal_info` (`user_id`, `user_phone`, `user_first`, `user_last`) VALUES
(1, '4075551234', 'Rick', 'Mason');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_description`
--
ALTER TABLE `user_description`
 ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `user_image`
--
ALTER TABLE `user_image`
 ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `user_personal_info`
--
ALTER TABLE `user_personal_info`
 ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
