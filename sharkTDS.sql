-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- httpswww.phpmyadmin.net
--
-- Host localhost3306
-- Generation Time Oct 09, 2023 at 0242 PM
-- Server version 10.3.34-MariaDB-0ubuntu0.20.04.1
-- PHP Version 7.4.3-4ubuntu2.18

SET SQL_MODE = NO_AUTO_VALUE_ON_ZERO;
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = +0000;


!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT ;
!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS ;
!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION ;
!40101 SET NAMES utf8mb4 ;

--
-- Database `sharkTDS`
--

-- --------------------------------------------------------

--
-- Table structure for table `digitalprints`
--

CREATE TABLE `digitalprints` (
  `IP` varchar(64) NOT NULL,
  `ISP` varchar(64) DEFAULT NULL,
  `Useragent` varchar(512) DEFAULT NULL,
  `Country` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `digitalprints`
--

INSERT INTO `digitalprints` (`IP`, `ISP`, `Useragent`, `Country`) VALUES
('5.211.66.54', 'Verizon', 'Opera7.23 (Windows 98; U) ', 'Iceland');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `digitalprints`
--
ALTER TABLE `digitalprints`
  ADD PRIMARY KEY (`IP`);
COMMIT;

!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT ;
!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS ;
!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION ;
