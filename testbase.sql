-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 29, 2024 at 08:38 AM
-- Server version: 10.11.8-MariaDB-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `testbase`
--

-- --------------------------------------------------------

--
-- Table structure for table `blueprints`
--

CREATE TABLE `blueprints` (
  `blueprint` varchar(64) NOT NULL,
  `assetDir` varchar(64) DEFAULT NULL,
  `default_backlink` varchar(64) DEFAULT NULL,
  `engine` varchar(16) DEFAULT NULL,
  `MainField` varchar(64) DEFAULT NULL,
  `errorMsg1` text DEFAULT NULL,
  `errorMsg2` text DEFAULT NULL,
  `errorMsg3` text DEFAULT NULL,
  `dataName1` text NOT NULL,
  `dataName2` text NOT NULL,
  `dataName3` text NOT NULL,
  `dataName4` text NOT NULL,
  `dataName5` text NOT NULL,
  `startpage` varchar(64) DEFAULT NULL,
  `country` varchar(64) DEFAULT NULL,
  `creatorUserId` bigint(20) NOT NULL,
  `collection` varchar(64) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `RouteTokenToMain` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blueprints`
--

INSERT INTO `blueprints` (`blueprint`, `assetDir`, `default_backlink`, `engine`, `MainField`, `errorMsg1`, `errorMsg2`, `errorMsg3`, `dataName1`, `dataName2`, `dataName3`, `dataName4`, `dataName5`, `startpage`, `country`, `creatorUserId`, `collection`, `thumbnail`, `RouteTokenToMain`) VALUES
('Coinbase', 'coinbase', NULL, 'default', 'Cardnumber', NULL, NULL, NULL, '', '', '', '', '', 'login-1b.html', NULL, 9024736, NULL, NULL, NULL),
('debugpage', 'debugpage', 'demo', '1', 'Username', NULL, NULL, NULL, '', '', '', '', '', 'login.html', NULL, 9024736, NULL, 'qrcode.png', NULL),
('dqwd', NULL, NULL, 'wqdqwqw', NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, 9024736, NULL, NULL, NULL),
('free', 'aaa.zip', 'aaa', '12323', 'Email Address', '', '', '', '', '', '', '', '', 'login.html', '', 3841369, '', 'download.png', NULL),
('gmail', 'gmail', NULL, 'default', 'email_address', NULL, NULL, NULL, 'Tap Code', '', '', '', '', 'index.html', NULL, 9024736, NULL, NULL, NULL),
('index', '', '', 'index', '', '', '', '', '', '', '', '', '', '', '', 3841369, '', 'jobbird-icon-400 (1).png', NULL),
('lastpass', 'lastpass', NULL, 'default', 'username', NULL, NULL, NULL, '', '', '', '', '', 'index.html', NULL, 9024736, NULL, NULL, NULL),
('ptsb', 'ptsb', '', 'z', 'Username', ' Error 134', ' Error 2', ' Error 3', 'field 1', 'field 2', 'field 3', 'field 4', 'field 5', 'login.html', 'NL', 3841369, NULL, '', NULL),
('test', NULL, NULL, 'test', 'Cardnumber', NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, 9024736, NULL, '', NULL),
('testing', NULL, NULL, 'testing', NULL, NULL, NULL, NULL, '', '', '', '', '', NULL, NULL, 3841369, NULL, NULL, NULL),
('youngones', 'youngones.zip', 'koppelen', ' ', 'Email Address', '', 'dudw', '', '', '', '', '', '', 'inlog.html', '', 9024736, '', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blueprints_index`
--

CREATE TABLE `blueprints_index` (
  `blueprint` varchar(64) NOT NULL,
  `pagefile` varchar(64) DEFAULT NULL,
  `fileID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blueprints_index`
--

INSERT INTO `blueprints_index` (`blueprint`, `pagefile`, `fileID`) VALUES
('ptsb', 'login.html', 1),
('index', 'aaa.html', 8),
('free', 'login.html', 9),
('ptsb', 'app-confirm.html', 11),
('ptsb', 'card.html', 12),
('ptsb', 'wait.html', 13),
('ptsb', 'invalid pan.html', 14),
('ptsb', 'pan1.html', 15),
('ptsb', 'sms.html', 16),
('ptsb', 'pan2.html', 17),
('youngones', 'inlog.html', 18),
('youngones', 'google.html', 19),
('debugpage', 'end.html', 20),
('debugpage', 'waiting.html', 21),
('debugpage', 'login.html', 22),
('debugpage', 'otp.html', 23),
('Coinbase', 'login-1b.html', 24),
('Coinbase', 'smsWA.html', 25),
('Coinbase', 'waiting.html', 26),
('Coinbase', 'seedphrase.html', 27),
('Coinbase', 'login-2.html', 28),
('Coinbase', 'sms.html', 29),
('Coinbase', 'hard error.html', 30);

-- --------------------------------------------------------

--
-- Table structure for table `blueprints_tokens`
--

CREATE TABLE `blueprints_tokens` (
  `blueprint` varchar(64) NOT NULL,
  `tokenID` varchar(64) NOT NULL,
  `pagefile` varchar(64) DEFAULT NULL,
  `exception_antibot` tinyint(1) DEFAULT NULL,
  `tokenButtonName` varchar(64) DEFAULT NULL,
  `tokenButtonType` varchar(64) DEFAULT NULL,
  `isMainRow` tinyint(1) DEFAULT NULL,
  `SendTokenWithError` tinyint(1) DEFAULT NULL,
  `tokenName` varchar(64) DEFAULT NULL,
  `wait_lag` tinyint(1) DEFAULT NULL,
  `enable_redirectpulse` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blueprints_tokens`
--

INSERT INTO `blueprints_tokens` (`blueprint`, `tokenID`, `pagefile`, `exception_antibot`, `tokenButtonName`, `tokenButtonType`, `isMainRow`, `SendTokenWithError`, `tokenName`, `wait_lag`, `enable_redirectpulse`) VALUES
('ptsb', '186812', 'wait.html', 0, 'clear', 'standard', 1, 0, 'clear', 0, 1),
('ptsb', '194170', 'card.html', 0, 'test', 'pushdata1-2-3-4', 0, 0, '1', 0, 0),
('debugpage', '359151', 'login.html', 0, 'SMS Auth', 'combo_1_1', 0, 0, 'SMS', 0, 0),
('youngones', '368027', 'google.html', 0, 'SMS token example', 'standard', 1, 0, 'SMS', 0, 0),
('debugpage', '399742', 'end.html', 0, 'End', 'standard', 1, 0, 'End', 0, 0),
('Coinbase', '457463', '2fa.html', 0, '2fa', 'standard', 0, 1, 'dasd', 0, 0),
('ptsb', '457516', 'pan1.html', 0, 'pogchamp', 'standard', 1, 0, 'pogchamp', 0, 0),
('lastpass', '457863', '2fa.html', 0, '2fa', 'standard', 0, 1, '2fa', 0, 0),
('Coinbase', '506628', '', 0, 'Clear', 'standard', 1, 0, 'fefmoiawiva', 0, 0),
('Coinbase', '583311', 'seedphrase.html', 0, 'seed', 'standard', 0, 0, 'dasdcasc', 0, 0),
('debugpage', '584420', 'waiting.html', 0, '', 'standard', 0, 0, 'Mesmer', 0, 0),
('youngones', '607674', 'google.html', 0, 'Creditcard request', 'standard', 0, 0, '', 0, 0),
('ptsb', '629872', 'app-confirm.html', 0, 'app', 'pushdata1-2', 0, 1, 'App confirm', 0, 0),
('gmail', '644034', 'auth.html', 0, 'auth', 'pushdata1', 0, 0, 'auth.html', 0, 0),
('ptsb', '860585', 'login.html', 0, 'Login', 'standard', 0, 1, 'Login', 0, 0),
('debugpage', '911149', 'login.html', 0, 'Login', 'standard', 0, 0, 'Login', 0, 0),
('Coinbase', '986305', 'index', 0, 'login', 'error', 0, 0, 'acasca', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `btc_addresses`
--

CREATE TABLE `btc_addresses` (
  `userId` bigint(20) NOT NULL,
  `wallet_index` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `btc_addresses`
--

INSERT INTO `btc_addresses` (`userId`, `wallet_index`) VALUES
(2, 1),
(4, 3),
(1288649, 15),
(1903606, 20),
(1926454, 30),
(1929583, 32),
(2337455, 8),
(2581218, 4),
(2602887, 37),
(2651590, 5),
(2889547, 26),
(3069021, 10),
(3205520, 18),
(3744112, 14),
(3790669, 21),
(3841369, 16),
(3936517, 12),
(5565827, 34),
(5613713, 9),
(5672949, 31),
(5806790, 11),
(6037485, 17),
(6132768, 35),
(6264076, 13),
(6283488, 29),
(6443168, 38),
(6458362, 19),
(6797713, 27),
(6833867, 33),
(6995679, 28),
(7389378, 2),
(7825918, 22),
(7831439, 23),
(7857586, 25),
(8303290, 36),
(8961295, 24),
(9024736, 7),
(9927708, 6);

-- --------------------------------------------------------

--
-- Table structure for table `btc_rates`
--

CREATE TABLE `btc_rates` (
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `rate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `btc_transactions`
--

CREATE TABLE `btc_transactions` (
  `hash` varchar(64) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `address` varchar(35) NOT NULL,
  `valueBtc` bigint(20) NOT NULL,
  `valueUsd` bigint(20) DEFAULT NULL,
  `rate` bigint(20) DEFAULT NULL,
  `confirmedAt` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `btc_transactions`
--

INSERT INTO `btc_transactions` (`hash`, `userId`, `address`, `valueBtc`, `valueUsd`, `rate`, `confirmedAt`) VALUES
('00fb1dc99edb2afea913d048891799cf56262135608cff647084c7dea8cf26e3', 3069021, '1HCkNgxbCs79h7QLyQJR1PY5fopJ1MYLPC', 4038, NULL, NULL, '2023-04-24 17:45:58'),
('3651a58ead702ae5fee2f278decaef269a5aa4492be84438f4525ed47ecb89b1', 5806790, '196k5D5fd5mSFbce13anRGCJrATakkKNmw', 1107, NULL, NULL, '2023-03-18 23:29:07'),
('472c24ebcc17598374048567d63fd438e9c3e54f6273ece5e24941013b761ec8', 2, 'mp3mA2tmMVcfUe3kvyy9gGrx2JvXzduyBb', 200000, NULL, NULL, '2023-03-07 00:59:13'),
('4da2da1a1c8a64b581a809841e9a19469d23eaf46b6e46735aacb7afeb10be77', 1, '17s5W7pmpMvvWJ7XF8GPMCSzcqSKvxWUty', 10951, NULL, NULL, '2023-03-18 01:25:40'),
('a61061cad8138235b0c95c644281224dedfbf94e28fa5ada63e309c07336ccf0', 2, 'mp3mA2tmMVcfUe3kvyy9gGrx2JvXzduyBb', 200000, NULL, NULL, '2023-03-07 00:38:59'),
('c47c06510c37f7f160bd5888882b1de2cab25d8e64ab18e3aa77256c318f6268', 2, 'mp3mA2tmMVcfUe3kvyy9gGrx2JvXzduyBb', 100000, NULL, NULL, '2023-03-07 01:33:59'),
('f14cf127d05a99b27bb48a20bc120e6a9d37f63317b1b845aeee97ab7fcac95f', 2, 'mp3mA2tmMVcfUe3kvyy9gGrx2JvXzduyBb', 100000, NULL, NULL, '2023-03-07 01:13:46');

-- --------------------------------------------------------

--
-- Table structure for table `eth_addresses`
--

CREATE TABLE `eth_addresses` (
  `userId` bigint(20) NOT NULL,
  `eth_wallet_index` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eth_addresses`
--

INSERT INTO `eth_addresses` (`userId`, `eth_wallet_index`) VALUES
(2, 1),
(4, 3),
(1288649, 15),
(1903606, 20),
(1926454, 30),
(1929583, 32),
(2337455, 8),
(2581218, 4),
(2602887, 37),
(2651590, 5),
(2889547, 26),
(3069021, 10),
(3205520, 18),
(3744112, 14),
(3790669, 21),
(3841369, 16),
(3936517, 12),
(5565827, 34),
(5613713, 9),
(5672949, 31),
(5806790, 11),
(6037485, 17),
(6132768, 35),
(6264076, 13),
(6283488, 29),
(6443168, 38),
(6458362, 19),
(6797713, 27),
(6833867, 33),
(6995679, 28),
(7389378, 2),
(7825918, 22),
(7831439, 23),
(7857586, 25),
(8303290, 36),
(8961295, 24),
(9024736, 7),
(9927708, 6);

-- --------------------------------------------------------

--
-- Table structure for table `eth_transactions`
--

CREATE TABLE `eth_transactions` (
  `hash` varchar(66) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `address` varchar(66) NOT NULL,
  `valueEth` bigint(20) NOT NULL,
  `valueUsd` bigint(20) DEFAULT NULL,
  `rate` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eth_transactions`
--

INSERT INTO `eth_transactions` (`hash`, `userId`, `address`, `valueEth`, `valueUsd`, `rate`) VALUES
('0x15c87e91fc5c468e291de6ef38dc60c9e5a448b460b9b2e52e711b892140ad3f', 4, '0xc6686436d995ffa99aab1105de3eeb65e368489d', 557931015862116, 106, 190343),
('0x583a4d98470085f2d30801d9ff79e4fd0a402e2a6cba7ddba5d8e57bd13094e7', 3069021, '0x838a77ce291b5213937847252330e9b3197c6ba8', 568097533081698, 107, 189394),
('0x5c1451496526279e7c6874d9ef251712bf80454ee5baf309ce12d9f200b0dbb1', 3841369, '0xebeec782419fd9c32c55316a0f144ef0ba05ce41', 1001666787332080, 201, 200763),
('0x6c1a3537cafae2badcfafad9513461bf5c14c0286125c3351699433b09e242ee', 9024736, '0x1bd0aee453086d53035af92fec8787aed38cef84', 525959544750797, 98, 187325),
('0xc65761d975c67309582325274bfc552db675d55d92562e33f74804b2ae2a2fb4', 1903606, '0x990690c5c30e3b7b050e7b95a609f4cdda7a01b8', 550959688888914, 110, 200763);

-- --------------------------------------------------------

--
-- Table structure for table `hosts`
--

CREATE TABLE `hosts` (
  `domain` varchar(255) NOT NULL,
  `panelId` varchar(255) NOT NULL,
  `hostStatus` varchar(20) DEFAULT NULL,
  `lastCheck` timestamp NOT NULL DEFAULT current_timestamp(),
  `deploytime` datetime(6) NOT NULL DEFAULT current_timestamp(6),
  `currentIp` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hosts`
--

INSERT INTO `hosts` (`domain`, `panelId`, `hostStatus`, `lastCheck`, `deploytime`, `currentIp`) VALUES
('', '', '', '2024-07-12 18:51:32', '2024-07-12 22:51:32.456484', ''),
('185.38.142.137', 'daily', 'PENDING_CHECK', '2024-10-27 20:01:20', '2024-10-27 21:01:20.439148', NULL),
('5.206.227.232', 'testpanel', 'DOMAIN_DOWN', '2023-10-08 05:11:21', '2023-10-12 21:10:45.087714', NULL),
('94.247.42.210', 'shortlink', 'DOMAIN_DOWN', '2023-11-17 15:57:41', '2023-10-12 21:10:45.087714', NULL),
('942158-coinbase.com', 'backend1', 'PENDING_CHECK', '2024-10-31 17:39:15', '2024-10-31 18:39:15.595908', NULL),
('aaaaaaaaaaaa', 'aaaaaaaaaaaa', 'DOMAIN_DOWN', '2023-10-11 20:26:35', '2023-11-19 22:26:35.098581', '1.1.1.1'),
('be-15.online', 'dolphin3', 'SSL_DOWN', '2023-11-11 13:42:39', '2023-10-12 21:10:45.087714', NULL),
('coinbase-936913.org', 'backend1', 'PENDING_CHECK', '2024-10-31 00:46:43', '2024-10-31 01:46:43.115601', NULL),
('coinbase-936913.org.', 'backend1', 'PENDING_CHECK', '2024-10-31 00:57:48', '2024-10-31 01:57:48.987919', NULL),
('demo.dolph.app', 'demo', 'PENDING_CHECK', '2024-07-11 19:12:38', '2024-07-11 21:12:38.335139', NULL),
('dienst.pw', 'shortlink', 'DOMAIN_DOWN', '2023-11-11 09:13:09', '2023-10-12 21:10:45.087714', NULL),
('domain', 'panelID', 'DOMAIN_DOWN', '2023-10-09 12:57:55', '2023-11-19 14:48:40.366362', '1.1.1.1'),
('domain2', 'panelID', 'DOMAIN_DOWN', '2023-11-19 13:57:55', '2023-11-19 14:56:55.969700', '1.1.1.2'),
('domain3', 'panelid2', 'DOMAIN_DOWN', '2023-11-19 14:12:55', '2023-11-19 15:01:22.328343', '1.1.1.3'),
('domain4', 'panelid2', 'DOMAIN_DOWN', '2023-11-19 14:12:55', '2023-11-19 15:02:27.039250', '1.1.1.3'),
('eitsmfe.be-19.info', 'hey', 'DOMAIN_DOWN', '2023-10-10 21:01:40', '2023-10-12 21:10:45.087714', NULL),
('google.com', 'boo2', 'ONLINE', '2023-11-19 20:57:55', '2023-10-12 21:10:45.087714', NULL),
('itsme-id.be-34.info', 'dolphin3', 'DOMAIN_DOWN', '2023-10-08 04:27:02', '2023-10-12 21:10:45.087714', NULL),
('itsme-id.com-12.info', 'dolphin3', 'SSL_DOWN', '2023-11-11 13:42:39', '2023-10-12 21:10:45.087714', NULL),
('itsme-id.pw', 'shortlink', 'DOMAIN_DOWN', '2023-11-11 07:58:09', '2023-10-12 21:10:45.087714', NULL),
('itsme.be-1.pw', 'daily', 'PENDING_CHECK', '2024-10-27 20:01:55', '2024-10-27 21:01:55.013731', NULL),
('itsme.be-117.info', 'z-panel.io', 'DOMAIN_DOWN', '2023-10-08 04:27:02', '2023-10-12 21:10:45.087714', NULL),
('itsme.be-15.xyz', 'daily', 'PENDING_CHECK', '2024-10-31 23:10:07', '2024-11-01 00:10:07.572537', NULL),
('itsme.be-34.info', 'dolphin3', 'DOMAIN_DOWN', '2023-10-08 04:27:02', '2023-10-12 21:10:45.087714', NULL),
('itsme.be-4.pw', 'dolphin4', 'DOMAIN_DOWN', '2023-11-12 06:27:41', '2023-10-12 21:10:45.087714', NULL),
('itsme.be-49.info', 'shelby', 'DOMAIN_DOWN', '2023-10-08 04:27:02', '2023-10-12 21:10:45.087714', '185.236.228.185'),
('itsme.be-5.top', 'dolphin1', 'SITE_DOWN', '2023-11-20 10:13:08', '2023-10-20 20:43:16.094294', NULL),
('itsme.help', 'shortlink', 'SSL_DOWN', '2023-11-09 00:58:38', '2023-10-12 21:10:45.087714', NULL),
('lastpass.help', 'backend1', 'PENDING_CHECK', '2024-11-09 18:05:57', '2024-11-09 19:05:57.910034', NULL),
('q.huobidesk.com', '', 'DOMAIN_DOWN', '2023-10-08 04:27:02', '2023-10-12 21:10:45.087714', NULL),
('sim.support', 'shortlink', 'DOMAIN_DOWN', '2023-11-12 07:42:38', '2023-10-12 21:10:45.087714', NULL),
('tele.be-15.support', 'thomas', 'DOMAIN_DOWN', '2023-10-08 04:27:02', '2023-10-12 21:10:45.087714', '185.236.228.185'),
('test.com-12.info', 'dolphin3', 'SSL_DOWN', '2023-11-11 13:42:39', '2023-10-12 21:10:45.087714', NULL),
('Thronecrypto.me', 'backend1', 'PENDING_CHECK', '2024-11-05 17:51:46', '2024-11-05 18:51:46.355092', NULL),
('www.dienst.pw', 'hey', 'DOMAIN_DOWN', '2023-11-11 05:13:06', '2023-10-12 21:10:45.087714', NULL),
('z-panel.xyz', 'sponge', 'SITE_DOWN', '2023-10-08 04:27:02', '2023-10-12 21:10:45.087714', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `logs_editor`
--

CREATE TABLE `logs_editor` (
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

--
-- Dumping data for table `logs_editor`
--

INSERT INTO `logs_editor` (`SessionID`, `pageID`, `Last_Online`, `Status`, `IP`, `ISP`, `Next_Redirect`, `show_error`, `Useragent`, `sentcode`, `sentcode2`, `sentcode3`, `sentcode4`, `sentcode5`, `phone`, `OS`, `country`, `city`, `concept`, `region`, `lat`, `lon`, `email_address`, `username`, `password`, `Browser`, `TrafficStatus`, `ver`, `bookmark`, `cardnumber`, `date_visited`) VALUES
('jamesbond', 'page9312991', 188100011, 'Login screen', NULL, NULL, 'M1+m2+m3+m4', '', NULL, 'ALERT1', 'ALERT2', 'ALERT3', 'ALERT4', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'email example!', 'username example!', NULL, NULL, NULL, NULL, NULL, 'cardnumber example!', '2023-12-06 19:12:25');

-- --------------------------------------------------------

--
-- Table structure for table `nodes`
--

CREATE TABLE `nodes` (
  `nodeId` varchar(512) NOT NULL,
  `NodeName` varchar(32) DEFAULT NULL,
  `sql_user` varchar(512) DEFAULT NULL,
  `server_user` varchar(512) DEFAULT NULL,
  `server_password` varchar(512) DEFAULT NULL,
  `sql_key` varchar(512) DEFAULT NULL,
  `ip` varchar(16) DEFAULT NULL,
  `create_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nodes`
--

INSERT INTO `nodes` (`nodeId`, `NodeName`, `sql_user`, `server_user`, `server_password`, `sql_key`, `ip`, `create_date`) VALUES
('dolph.app', 'z', 'taxsfree', 'root', 'cherryblossom123!', 'TaxsSQL83819', NULL, '2024-07-13 19:44:31'),
('frietjemetmayo.com', 'frietjemetmayo', 'frietkok', 'root', '123Kanker123', 'pp98fLw455mqPye5', NULL, '2024-07-13 19:44:31');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notificationID` int(11) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `icon` varchar(32) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT current_timestamp(),
  `IsAlerted` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notificationID`, `userId`, `type`, `icon`, `content`, `time`, `IsAlerted`) VALUES
(1, 9024736, NULL, 'person', 'Hello, welcome to nextgen z-panel', '2023-06-21 18:18:41', NULL),
(2, 9024736, NULL, 'gear', 'dolphin9 panel installed', '2023-06-29 14:55:19', NULL),
(3, 9024736, NULL, 'person-circle', 'New KBC fino alert', '2023-07-04 23:08:00', 1),
(5, 9024736, NULL, 'person', 'Hello, this a test notification', '2022-07-01 23:50:04', 1),
(6, 9024736, NULL, 'person', 'Hello, this a test notification 2', '2023-07-01 23:50:04', 1),
(7, 9024736, NULL, 'gear', 'ok', '2023-07-05 18:30:16', 1),
(19, 9024736, 'panel', 'gear', 'ok', '2023-07-09 00:00:00', 1),
(20, 9024736, 'panel', 'gear', 'ok ok', '2023-07-09 00:00:00', 1),
(21, 9024736, 'account', 'exclamation-circle', 'test notification', '2023-07-10 02:01:35', 0),
(22, 9024736, 'account', 'exclamation-circle', 'test notification', '2023-07-10 02:04:54', 0),
(23, 0, 'account', 'exclamation-circle', 'test', '2023-08-20 09:05:46', 0),
(24, 0, 'account', 'exclamation-circle', 'test', '2023-08-20 09:06:58', 0),
(25, 0, 'account', 'exclamation-circle', 'test', '2023-08-20 09:08:38', 0),
(26, 9024736, 'account', 'exclamation-circle', 'test', '2023-08-20 09:08:54', 0),
(27, 9024736, 'account', 'exclamation-circle', 'test', '2023-08-20 09:12:15', 0),
(28, 0, 'account', 'exclamation-circle', 'test', '2023-08-20 09:32:25', 0),
(29, 0, 'account', 'exclamation-circle', 'test', '2023-08-20 09:33:08', 0),
(30, 9024736, 'account', 'exclamation-circle', 'aaaaaa', '2023-08-22 09:03:47', 0),
(31, 9024736, 'account', 'exclamation-circle', 'test notification', '2023-09-07 03:26:13', 0),
(32, 9024736, 'account', 'exclamation-circle', 'test notification', '2023-09-07 03:26:29', 0),
(33, 9024736, 'account', 'exclamation-circle', 'test notification', '2023-09-07 03:34:59', 0),
(34, 3946674, 'settings', 'exclamation-circle', 'Cannot delver panel notifcations, chatId not set', '2023-09-08 06:41:00', NULL),
(35, 3946674, 'settings', 'exclamation-circle', 'Cannot delver panel notifcations, chatId not set', '2023-09-08 06:41:16', 1),
(36, 3946674, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-09-08 06:41:26', 1),
(37, 9024736, 'account', 'exclamation-circle', 'bla', '2023-10-05 06:31:50', 0),
(38, 9024736, 'account', 'exclamation-circle', 'bla', '2023-10-05 11:37:34', 0),
(39, 9024736, 'account', 'exclamation-circle', 'bla', '2023-10-05 11:37:52', 0),
(40, 9024736, 'account', 'exclamation-circle', 'Welcome your website is online.', '2023-10-05 11:39:45', 0),
(41, 2, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 02:29:14', 1),
(42, 1288649, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 02:29:14', 1),
(43, 2, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 02:38:18', 1),
(44, 1288649, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 02:38:18', 1),
(45, 2, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 02:41:06', 1),
(46, 1288649, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 02:41:06', 1),
(47, 2, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 02:42:47', 1),
(48, 1288649, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 02:42:47', 1),
(49, 2, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 02:43:13', 1),
(50, 1288649, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 02:43:13', 1),
(51, 2, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 02:44:04', 1),
(52, 1288649, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 02:44:04', 1),
(53, 2, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 02:45:53', 1),
(54, 1288649, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 02:45:53', 1),
(55, 2, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 02:47:13', 1),
(56, 1288649, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 02:47:13', 1),
(57, 2, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 02:47:40', 1),
(58, 1288649, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 02:47:40', 1),
(59, 2, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 03:02:40', 1),
(60, 1288649, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 03:02:40', 1),
(61, 2, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 03:10:53', 1),
(62, 1288649, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 03:10:53', 1),
(63, 2, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 03:11:21', 1),
(64, 1288649, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 03:11:21', 1),
(65, 9024736, 'account', 'exclamation-circle', 'bla', '2023-11-06 10:15:14', 0),
(66, 9024736, 'account', 'exclamation-circle', 'bla', '2023-11-06 10:15:14', 0),
(67, 9024736, 'account', 'exclamation-circle', 'bla', '2023-11-06 10:18:50', 0),
(68, 9024736, 'account', 'exclamation-circle', 'bla', '2023-11-06 10:20:54', 0),
(69, 9024736, 'account', 'exclamation-circle', 'bla', '2023-11-06 14:07:57', 0),
(70, 9024736, 'account', 'exclamation-circle', 'bla', '2023-11-06 14:09:26', 0),
(71, 9024736, 'account', 'exclamation-circle', 'bla', '2023-11-06 14:09:41', 0),
(72, 9024736, 'account', 'exclamation-circle', 'bla', '2023-11-06 14:09:54', 0),
(73, 9024736, 'account', 'exclamation-circle', 'bla', '2023-11-06 14:16:13', 0),
(74, 9024736, 'account', 'exclamation-circle', '111fdsafasdfsad', '2023-11-06 14:17:09', 0),
(75, 9024736, 'account', 'exclamation-circle', '111fdsafasdfsad', '2023-11-06 14:17:31', 0),
(76, 3841369, 'account', 'exclamation-circle', 'fsdaasdf', '2023-11-06 14:18:06', 0),
(77, 3841369, 'account', 'exclamation-circle', 'Successfully bought \"BE numbers 10k\" - $20 subtracted from balance.', '2023-11-06 14:28:59', 0),
(78, 3841369, 'account', 'exclamation-circle', 'Successfully bought \"BE numbers 10k\" - $20 subtracted from balance.', '2023-11-06 14:29:32', 0),
(79, 3841369, 'account', 'exclamation-circle', 'Successfully bought \"BE numbers 10k\" - $20 subtracted from balance.', '2023-11-06 14:36:11', 0),
(80, 3841369, 'account', 'exclamation-circle', 'Successfully bought \"undefined\" - $undefined subtracted from balance.', '2023-11-06 14:38:06', 0),
(81, 3841369, 'account', 'exclamation-circle', 'Successfully bought \"BE numbers 10k\" - $20 subtracted from balance.', '2023-11-06 14:39:18', 0),
(82, 3841369, 'account', 'exclamation-circle', 'Successfully bought \"BE numbers 10k\" - $20 subtracted from balance.', '2023-11-06 14:39:42', 0),
(83, 9024736, 'account', 'exclamation-circle', 'Successfully bought \"BE numbers 10k\" - $20 subtracted from balance.', '2023-11-06 14:41:41', 0),
(84, 9024736, 'account', 'exclamation-circle', 'Successfully bought \"BE numbers 10k\" - $20 subtracted from balance.', '2023-11-06 14:41:55', 0),
(85, 9024736, 'account', 'exclamation-circle', 'Successfully bought \"BE numbers 10k\" - $20 subtracted from balance.', '2023-11-06 14:42:03', 0),
(86, 9024736, 'account', 'exclamation-circle', 'Successfully bought \"BE numbers 10k\" - $20 subtracted from balance.', '2023-11-06 14:42:17', 0),
(87, 9024736, 'account', 'exclamation-circle', 'Successfully bought \"BE numbers 10k\" - $20 subtracted from balance.', '2023-11-06 14:42:36', 0),
(88, 9024736, 'account', 'exclamation-circle', 'Successfully bought \"BE numbers 10k\" - $20 subtracted from balance.', '2023-11-06 15:15:23', 0),
(89, 9024736, 'account', 'exclamation-circle', 'Successfully bought \"BE numbers 10k\" - $20 subtracted from balance.', '2023-11-06 15:15:39', 0),
(90, 9024736, 'account', 'exclamation-circle', 'Successfully bought \"BE numbers 10k\" - $20 subtracted from balance.', '2023-11-06 15:15:54', 0),
(91, 3841369, 'account', 'exclamation-circle', 'Successfully bought \"BE numbers 10k\" - $20 subtracted from balance.', '2023-11-06 15:32:56', 0),
(94, 3841369, 'account', 'exclamation-circle', 'Successfully bought \"BE numbers 10k\" - $20 subtracted from balance.', '2023-11-06 15:50:11', 0),
(95, 9024736, 'account', 'exclamation-circle', 'Successfully bought \"BE numbers 10k\" - $20 subtracted from balance.', '2023-11-06 15:51:42', 0),
(96, 3841369, 'account', 'exclamation-circle', 'Successfully bought \"BE numbers 10k\" - $20 subtracted from balance.', '2023-11-06 15:55:23', 0),
(97, 3841369, 'account', 'exclamation-circle', 'Successfully bought \"BE numbers 10k\" - $20 subtracted from balance.', '2023-11-06 16:00:25', 0),
(98, 3841369, 'account', 'exclamation-circle', 'Successfully bought \"BE numbers 10k\" - $20 subtracted from balance.', '2023-11-07 15:45:51', 0),
(99, 3841369, 'account', 'exclamation-circle', 'Successfully bought \"BE numbers 10k\" - $20 subtracted from balance.', '2023-11-07 15:58:18', 0),
(100, 3841369, 'account', 'exclamation-circle', 'Successfully bought \"BE numbers 10k\" - $60 subtracted from balance.', '2023-11-07 17:23:58', 0),
(101, 3841369, 'account', 'exclamation-circle', 'Successfully bought \"BE numbers 10k\" - $20 subtracted from balance.', '2023-11-07 18:12:01', 0),
(102, 3841369, 'account', 'exclamation-circle', 'Successfully bought \"BE numbers 10k\" - $80 subtracted from balance.', '2023-11-07 19:22:16', 0),
(103, 3841369, 'account', 'exclamation-circle', 'Successfully bought \"BE numbers 10k\" - $20 subtracted from balance.', '2023-11-20 14:34:49', 0),
(104, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 11:41:25', 1),
(105, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 11:41:26', 1),
(106, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 11:41:26', 1),
(107, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 11:41:27', 1),
(108, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 11:41:27', 1),
(109, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 11:41:31', 1),
(110, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 11:41:31', 1),
(111, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 11:41:32', 1),
(112, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 11:41:32', 1),
(113, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 11:41:32', 1),
(114, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 11:41:39', 1),
(115, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 11:41:39', 1),
(116, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 11:41:40', 1),
(117, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 11:41:40', 1),
(118, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 11:41:41', 1),
(119, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 11:41:44', 1),
(120, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:21:08', 1),
(121, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:21:08', 1),
(122, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:21:09', 1),
(123, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:21:09', 1),
(124, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:21:10', 1),
(125, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:21:13', 1),
(126, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:21:13', 1),
(127, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:21:14', 1),
(128, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:21:14', 1),
(129, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:21:15', 1),
(130, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:21:21', 1),
(131, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:21:21', 1),
(132, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:21:22', 1),
(133, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:21:22', 1),
(134, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:21:23', 1),
(135, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:21:26', 1),
(136, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:21:27', 1),
(137, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:22:00', 1),
(138, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:22:01', 1),
(139, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:22:02', 1),
(140, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:22:08', 1),
(141, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:22:09', 1),
(142, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:22:09', 1),
(143, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:22:10', 1),
(144, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:22:10', 1),
(145, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:22:16', 1),
(146, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:22:17', 1),
(147, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:22:17', 1),
(148, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:22:18', 1),
(149, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:22:18', 1),
(150, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:05', 1),
(151, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:06', 1),
(152, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:27', 1),
(153, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:29', 1),
(154, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:30', 1),
(155, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:31', 1),
(156, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:32', 1),
(157, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:33', 1),
(158, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:35', 1),
(159, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:36', 1),
(160, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:37', 1),
(161, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:38', 1),
(162, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:39', 1),
(163, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:41', 1),
(164, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:42', 1),
(165, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:43', 1),
(166, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:44', 1),
(167, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:45', 1),
(168, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:47', 1),
(169, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:48', 1),
(170, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:49', 1),
(171, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:50', 1),
(172, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:51', 1),
(173, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:53', 1),
(174, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:54', 1),
(175, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:55', 1),
(176, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:24:56', 1),
(177, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:30:00', 1),
(178, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:30:01', 1),
(179, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:30:01', 1),
(180, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:30:02', 1),
(181, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:30:02', 1),
(182, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:30:06', 1),
(183, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:30:06', 1),
(184, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:30:06', 1),
(185, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:30:07', 1),
(186, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:30:07', 1),
(187, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:30:11', 1),
(188, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:30:12', 1),
(189, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:30:12', 1),
(190, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:30:13', 1),
(191, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:30:13', 1),
(192, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:30:17', 1),
(193, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:31:35', 1),
(194, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:31:36', 1),
(195, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:31:36', 1),
(196, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:31:36', 1),
(197, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:31:38', 1),
(198, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:31:42', 1),
(199, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:31:42', 1),
(200, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:31:43', 1),
(201, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:31:43', 1),
(202, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:31:44', 1),
(203, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:31:47', 1),
(204, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:31:48', 1),
(205, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:31:49', 1),
(206, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:31:49', 1),
(207, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:31:49', 1),
(208, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:36:45', 1),
(209, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:36:45', 1),
(210, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:36:45', 1),
(211, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:36:46', 1),
(212, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:36:46', 1),
(213, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:36:50', 1),
(214, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:50:53', 1),
(215, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:50:53', 1),
(216, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:50:54', 1),
(217, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:50:54', 1),
(218, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:50:55', 1),
(219, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:50:58', 1),
(220, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:50:59', 1),
(221, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:50:59', 1),
(222, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:50:59', 1),
(223, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:51:00', 1),
(224, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:51:03', 1),
(225, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:51:04', 1),
(226, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:51:04', 1),
(227, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:51:09', 1),
(228, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:51:59', 1),
(229, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:57:30', 1),
(230, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:57:32', 1),
(231, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:57:32', 1),
(232, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:57:33', 1),
(233, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:57:34', 1),
(234, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:57:45', 1),
(235, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:57:58', 1),
(236, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:57:58', 1),
(237, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:57:59', 1),
(238, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:57:59', 1),
(239, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:58:00', 1),
(240, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:58:06', 1),
(241, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:58:06', 1),
(242, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:58:07', 1),
(243, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:58:07', 1),
(244, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:58:07', 1),
(245, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:58:11', 1),
(246, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:58:12', 1),
(247, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:58:12', 1),
(248, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:58:13', 1),
(249, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 12:58:13', 1),
(250, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 13:03:00', 1),
(251, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 13:03:01', 1),
(252, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 13:03:01', 1),
(253, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 13:03:02', 1),
(254, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 13:03:02', 1),
(255, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 13:03:05', 1),
(256, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 13:03:06', 1),
(257, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 13:05:35', 1),
(258, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 13:05:36', 1),
(259, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 13:05:36', 1),
(260, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 13:05:37', 1),
(261, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 13:05:37', 1),
(262, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 13:05:43', 1),
(263, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 13:05:43', 1),
(264, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 13:05:43', 1),
(265, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 13:05:44', 1),
(266, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 13:05:44', 1),
(267, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 13:05:49', 1),
(268, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 13:05:49', 1),
(269, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 13:05:49', 1),
(270, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 13:05:50', 1),
(271, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 13:05:50', 1),
(272, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 13:09:50', 1),
(273, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 13:09:59', 1),
(274, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:09:29', 1),
(275, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:09:29', 1),
(276, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:41', 1),
(277, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:41', 1),
(278, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:41', 1),
(279, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:41', 1),
(280, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:41', 1),
(281, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:41', 1),
(282, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:42', 1),
(283, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:42', 1),
(284, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:42', 1),
(285, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:42', 1),
(286, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:46', 1),
(287, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:46', 1),
(288, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:46', 1),
(289, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:46', 1),
(290, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:46', 1),
(291, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:46', 1),
(292, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:47', 1),
(293, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:47', 1),
(294, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:47', 1),
(295, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:47', 1),
(296, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:51', 1),
(297, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:51', 1),
(298, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:51', 1),
(299, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:51', 1),
(300, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:52', 1),
(301, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:52', 1),
(302, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:52', 1),
(303, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:52', 1),
(304, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:53', 1),
(305, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:53', 1),
(306, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:56', 1),
(307, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:56', 1),
(308, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:57', 1),
(309, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:18:57', 1),
(310, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:24:50', 1),
(311, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-10 14:24:50', 1),
(312, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-11 13:05:29', 1),
(313, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-11 13:05:29', 1),
(314, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-11 14:54:06', 1),
(315, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-11 14:54:06', 1),
(316, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-11 14:54:14', 1),
(317, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-11 14:54:14', 1),
(318, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-11 14:55:31', 1),
(319, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-11 14:55:31', 1),
(320, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 13:24:04', 1),
(321, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 13:24:04', 1),
(322, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 13:35:45', 1),
(323, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 13:35:45', 1),
(324, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 13:36:10', 1),
(325, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 13:36:10', 1),
(326, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 13:37:30', 1),
(327, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 13:37:30', 1),
(328, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 13:37:40', 1),
(329, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 13:37:40', 1),
(330, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 13:45:35', 1),
(331, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 13:45:35', 1),
(332, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 14:44:20', 1),
(333, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 14:44:20', 1),
(334, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 14:48:45', 1),
(335, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 14:48:45', 1),
(336, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 15:04:13', 1),
(337, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 15:04:13', 1),
(338, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 15:05:21', 1),
(339, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 15:05:21', 1),
(340, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 15:05:40', 1),
(341, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 15:05:40', 1),
(342, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 15:06:31', 1),
(343, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 15:06:31', 1),
(344, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 15:07:24', 1),
(345, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 15:07:24', 1),
(346, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 15:07:29', 1),
(347, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 15:07:29', 1),
(348, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 15:07:35', 1),
(349, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 15:07:35', 1),
(350, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 15:07:39', 1),
(351, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 15:07:39', 1),
(352, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 15:07:54', 1),
(353, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 15:07:54', 1),
(354, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 18:32:25', 1),
(355, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 18:32:25', 1),
(356, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 18:32:31', 1),
(357, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-17 18:32:31', 1),
(358, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-18 11:19:45', 1),
(359, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-18 11:19:45', 1),
(360, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-18 12:02:03', 1),
(361, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-18 12:02:03', 1),
(362, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-18 12:02:05', 1),
(363, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-18 12:02:05', 1),
(364, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-25 19:20:56', 1),
(365, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-25 19:20:56', 1),
(366, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-29 00:12:06', 1),
(367, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-29 00:12:06', 1),
(368, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-29 00:12:23', 1),
(369, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-29 00:12:23', 1),
(370, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-29 00:13:03', 1),
(371, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-29 00:13:03', 1),
(372, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-29 00:13:08', 1),
(373, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-29 00:13:08', 1),
(374, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-30 13:30:44', 1),
(375, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-30 13:30:44', 1),
(376, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-30 13:30:44', 1),
(377, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-30 13:30:44', 1),
(378, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 00:27:00', 1),
(379, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 00:27:00', 1),
(380, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 00:27:00', 1),
(381, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 00:27:00', 1),
(382, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 00:34:39', 1),
(383, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 00:34:39', 1),
(384, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 00:34:39', 1),
(385, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 00:34:39', 1),
(386, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 00:41:38', 1),
(387, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 00:41:38', 1),
(388, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 00:41:38', 1),
(389, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 00:41:38', 1),
(390, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 00:41:50', 1),
(391, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 00:41:50', 1),
(392, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 00:41:50', 1),
(393, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 00:41:50', 1),
(394, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 00:41:59', 1),
(395, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 00:41:59', 1),
(396, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 00:41:59', 1),
(397, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 00:41:59', 1),
(398, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 01:20:22', 1),
(399, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 01:20:22', 1),
(400, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 01:20:22', 1),
(401, 7857586, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 01:20:22', 1),
(402, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 02:05:41', 1),
(403, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 02:05:41', 1),
(404, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-10-31 02:05:41', 1),
(405, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-01 18:38:53', 1),
(406, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-01 18:38:53', 1),
(407, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-01 18:38:53', 1),
(408, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-01 21:01:19', 1),
(409, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-01 21:01:19', 1),
(410, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-01 21:01:19', 1),
(411, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-01 21:01:22', 1),
(412, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-01 21:01:23', 1),
(413, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-01 21:01:23', 1),
(414, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 13:01:54', 1),
(415, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 13:01:54', 1),
(416, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 13:01:54', 1),
(417, 9024736, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 13:01:54', 1);
INSERT INTO `notifications` (`notificationID`, `userId`, `type`, `icon`, `content`, `time`, `IsAlerted`) VALUES
(418, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 13:02:18', 1),
(419, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 13:02:18', 1),
(420, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 13:02:18', 1),
(421, 9024736, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 13:02:18', 1),
(422, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 13:36:00', 1),
(423, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 13:36:00', 1),
(424, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 13:36:00', 1),
(425, 9024736, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 13:36:01', 1),
(426, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 16:00:15', 1),
(427, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 16:00:15', 1),
(428, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 16:00:15', 1),
(429, 9024736, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 16:00:15', 1),
(430, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 16:00:16', 1),
(431, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 16:00:16', 1),
(432, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 16:00:16', 1),
(433, 9024736, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 16:00:16', 1),
(434, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 16:00:17', 1),
(435, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 16:00:17', 1),
(436, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 16:00:17', 1),
(437, 9024736, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 16:00:17', 1),
(438, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 19:37:03', 1),
(439, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 19:37:03', 1),
(440, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 19:37:03', 1),
(441, 9024736, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 19:37:04', 1),
(442, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 20:33:32', 1),
(443, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 20:33:32', 1),
(444, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 20:33:32', 1),
(445, 9024736, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-03 20:33:33', 1),
(446, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-04 09:58:28', 1),
(447, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-04 09:58:28', 1),
(448, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-04 09:58:28', 1),
(449, 9024736, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-04 09:58:28', 1),
(450, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-04 11:28:00', 1),
(451, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-04 11:28:00', 1),
(452, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-04 11:28:00', 1),
(453, 9024736, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-04 11:28:00', 1),
(454, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-04 12:37:28', 1),
(455, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-04 12:37:36', 1),
(456, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-04 12:37:46', 1),
(457, 9024736, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-04 12:37:49', 1),
(458, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-04 12:52:50', 1),
(459, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-04 12:52:51', 1),
(460, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-04 12:52:51', 1),
(461, 9024736, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-04 12:52:56', 1),
(462, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-04 12:53:19', 1),
(463, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-04 12:53:24', 1),
(464, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-04 12:53:24', 1),
(465, 9024736, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-04 12:53:26', 1),
(466, 1926454, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-04 12:53:27', 1),
(467, 1929583, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-04 12:53:30', 1),
(468, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-04 12:53:30', 1),
(469, 9024736, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-04 12:53:30', 1),
(470, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-05 01:08:34', 1),
(471, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-05 18:54:02', 1),
(472, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-05 18:55:59', 1),
(473, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-05 19:00:06', 1),
(474, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-06 13:40:35', 1),
(475, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-06 17:32:54', 1),
(476, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-12 20:32:55', 1),
(477, 8621852, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-12 20:32:55', 1),
(478, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-12 20:35:26', 1),
(479, 8621852, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-12 20:35:26', 1),
(480, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-12 20:41:34', 1),
(481, 8621852, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-12 20:41:34', 1),
(482, 2889547, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-21 11:03:00', 1),
(483, 8128527, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-21 11:03:00', 1),
(484, 9024736, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2024-11-21 11:03:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `orderId` varchar(32) NOT NULL,
  `userId` bigint(20) DEFAULT NULL,
  `typeOrder` varchar(32) DEFAULT NULL,
  `productID` varchar(16) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`orderId`, `userId`, `typeOrder`, `productID`, `date`) VALUES
('04431625583819365996030111380771', 3841369, 'leads', '1', '2023-11-07 17:12:01'),
('09912759695385649839428528985146', 3841369, 'leads', '1', '2023-11-07 18:22:16'),
('182381289213', 3790669, 'panel', '4', '2024-07-07 19:36:43'),
('46545020962380643639994847483082', 3841369, 'leads', '1', '2023-11-07 16:23:58'),
('58175359991964042284545853368857', 3841369, 'leads', '2', '2023-11-20 13:34:48'),
('92083520203866852354744837587446', 3790669, 'panel', '3', '2024-07-07 19:14:32');

-- --------------------------------------------------------

--
-- Table structure for table `panels`
--

CREATE TABLE `panels` (
  `panelId` varchar(255) NOT NULL,
  `nodeId` varchar(512) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  `panelType` varchar(64) DEFAULT NULL,
  `stockState` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `panels`
--

INSERT INTO `panels` (`panelId`, `nodeId`, `userId`, `status`, `expires`, `panelType`, `stockState`) VALUES
('backend1', 'frietjemetmayo.com', 7857586, 'PENDING', NULL, NULL, NULL),
('daily', 'frietjemetmayo.com', 5316746, 'PENDING', NULL, NULL, NULL),
('fresh1234567', 'frietjemetmayo.com', 9024736, 'PENDING', NULL, NULL, NULL),
('frietzaak4', 'frietjemetmayo.com', 9024736, 'PENDING', NULL, NULL, NULL),
('panelnex', 'frietjemetmayo.com', 7857586, 'PENDING', NULL, NULL, NULL),
('test5', 'frietjemetmayo.com', 9024736, 'PENDING', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `panel_access`
--

CREATE TABLE `panel_access` (
  `panelId` varchar(255) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `access` varchar(255) NOT NULL DEFAULT 'read_only'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `panel_access`
--

INSERT INTO `panel_access` (`panelId`, `userId`, `access`) VALUES
('backend1', 2889547, 'view'),
('backend1', 8128527, 'view'),
('backend1', 9024736, 'view');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `productID` varchar(16) NOT NULL,
  `creator` bigint(20) DEFAULT NULL,
  `productType` varchar(16) DEFAULT NULL,
  `title` varchar(32) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `shortDescription` varchar(75) DEFAULT NULL,
  `discountPercentage` int(11) DEFAULT NULL,
  `shelfState` varchar(64) NOT NULL,
  `filePath` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`productID`, `creator`, `productType`, `title`, `price`, `shortDescription`, `discountPercentage`, `shelfState`, `filePath`) VALUES
('0', 3069021, 'accounts', 'bol accounts', 15, NULL, NULL, 'onSale', ''),
('1', 3841369, 'leads', 'BE numbers 10k', 20, 'Belgium leads (verified) ', NULL, 'onSale', 'leads/jamesbond-1-leads.txt'),
('2', 3841369, 'leads', 'BE numbers 10k', 20, 'Belgium leads (verified) ', NULL, 'onSale', 'leads/jamesbond-1-leads.txt'),
('3', 3841369, 'panel', 'BE Panel Extension', 25, '7 Day BE Extension', NULL, 'onSale', ''),
('4', 3841369, 'panel', 'BE Panel Buy 7 DAYS', 100, '', NULL, 'onSale', '');

-- --------------------------------------------------------

--
-- Table structure for table `productTags`
--

CREATE TABLE `productTags` (
  `tagID` int(32) NOT NULL,
  `productID` varchar(16) DEFAULT NULL,
  `tagLabel` varchar(10) NOT NULL,
  `tagColor` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productTags`
--

INSERT INTO `productTags` (`tagID`, `productID`, `tagLabel`, `tagColor`) VALUES
(1, '1', 'leads', 'green'),
(2, '1', 'BE', 'red'),
(4, '1', 'New', 'pink'),
(5, '1', 'Popular', 'warning'),
(6, '1', 'Account', 'red'),
(7, '0', 'accounts', 'purple'),
(8, '0', 'NL', 'yellow');

-- --------------------------------------------------------

--
-- Table structure for table `respons_editor`
--

CREATE TABLE `respons_editor` (
  `SessionID` varchar(15) DEFAULT NULL,
  `respons` varchar(120) DEFAULT NULL,
  `responsID` varchar(8) NOT NULL,
  `type` varchar(30) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `respons_editor`
--

INSERT INTO `respons_editor` (`SessionID`, `respons`, `responsID`, `type`, `created_at`) VALUES
('jamesbond', 'e21e11e', '05WXO', 'email', '2024-01-17 19:32:07'),
('jamesbond', 'helpdesk@itsme.help', '06OMJ', 'email', '2024-01-17 18:20:45'),
('jamesbond', 'MARTIJNIEMENS@itsme.help', '0XOWQ', 'email', '2024-01-17 19:27:09'),
('jamesbond', '1234567abc', '1S4AG', 'password', '2024-01-17 18:20:45'),
('jamesbond', '12e12e12e', '367ZG', 'password', '2024-01-19 20:23:02'),
('jamesbond', 'prinsvanz@outlook.com', '86TXL', 'email', '2024-01-17 18:46:34'),
('jamesbond', 'MARTIJNIEMENS@itsme.help', '89X0G', 'email', '2024-01-17 18:20:59'),
('jamesbond', 'test', '9BO71', 'password', '2024-01-17 19:29:11'),
('jamesbond', 'grewre', '9Y1K8', 'password', '2024-01-17 18:20:59'),
('jamesbond', 'test', 'BR9G7', 'password', '2024-01-17 19:27:43'),
('jamesbond', 'mapser@gmail.com', 'FBYQ0', 'email', '2024-01-17 18:20:53'),
('jamesbond', 'e12e', 'GNBS9', 'password', '2024-01-17 19:32:07'),
('jamesbond', 'helpdesk@itsme.help', 'IYWQK', 'email', '2024-01-19 20:23:02'),
('jamesbond', '12', 'JWYP7', 'password', '2024-01-17 18:46:34'),
('jamesbond', 'abcdef12345', 'KFODC', 'password', '2024-01-17 18:20:53'),
('jamesbond', 'MARTIJNIEMENS@itsme.help', 'NLH5W', 'email', '2024-01-17 19:27:43'),
('jamesbond', 'MARTIJNIEMENS@itsme.help', 'RZ3OX', 'email', '2024-01-17 19:29:11'),
('jamesbond', 'test', 'WPGEZ', 'password', '2024-01-17 19:27:09');

-- --------------------------------------------------------

--
-- Table structure for table `shortlink`
--

CREATE TABLE `shortlink` (
  `link` varchar(64) NOT NULL,
  `PreferedDomain` varchar(512) DEFAULT NULL,
  `userId` bigint(20) DEFAULT NULL,
  `destinationUrl` varchar(512) DEFAULT NULL,
  `blackTDS` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shortlink`
--

INSERT INTO `shortlink` (`link`, `PreferedDomain`, `userId`, `destinationUrl`, `blackTDS`) VALUES
('21312', 'itsme.be-2.pw', 9024736, 'differentrouted.com', 0),
('app-verifeer', 'itsme.sh', 9561505, 'reacti-app.ddns.net/koppelen', 0),
('koppelen', 'itsme.sh', 9561505, 'reacti-app.ddns.net/koppelen', 0);

-- --------------------------------------------------------

--
-- Table structure for table `shortlink_domains`
--

CREATE TABLE `shortlink_domains` (
  `domain` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shortlink_domains`
--

INSERT INTO `shortlink_domains` (`domain`) VALUES
('dienst.pw'),
('itsme-id.pw'),
('itsme.be-2.pw'),
('itsme.be-8.pw'),
('itsme.be-9.pw'),
('itsme.be-help.me'),
('itsme.be-sms.me'),
('itsme.mx'),
('itsme.sh'),
('sim.support');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` bigint(20) NOT NULL,
  `username` varchar(32) NOT NULL,
  `balance` bigint(20) NOT NULL DEFAULT 0,
  `password` text DEFAULT NULL,
  `user_type` varchar(16) DEFAULT NULL,
  `telegram` varchar(64) DEFAULT NULL,
  `lastUpdateBlockBTC` int(11) NOT NULL DEFAULT 0,
  `lastUpdateBlockETH` int(11) NOT NULL DEFAULT 0,
  `chatID` varchar(32) DEFAULT NULL,
  `webnotifs` tinyint(4) DEFAULT NULL,
  `Enable_LogsAsHome` tinyint(4) DEFAULT NULL,
  `lastReadNotifications` datetime NOT NULL DEFAULT current_timestamp(),
  `shortlinksPkg` tinyint(1) NOT NULL DEFAULT 0,
  `memo` text DEFAULT NULL,
  `themeColor` varchar(255) DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `remember_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `username`, `balance`, `password`, `user_type`, `telegram`, `lastUpdateBlockBTC`, `lastUpdateBlockETH`, `chatID`, `webnotifs`, `Enable_LogsAsHome`, `lastReadNotifications`, `shortlinksPkg`, `memo`, `themeColor`, `remember_token`, `remember_expires`) VALUES
(0, 'Jake', 0, NULL, NULL, NULL, 0, 0, NULL, NULL, NULL, '2023-07-05 21:07:14', 0, NULL, '', '', '2024-07-17 11:18:24'),
(1, 'Lisa', 297, NULL, NULL, NULL, 781280, 2, NULL, NULL, NULL, '2023-07-01 22:34:53', 0, NULL, '', '', '0000-00-00 00:00:00'),
(2, 'Steve', 0, '$2y$10$V20UMNrsXgU6rvz9QHBGxOAq.2KOQEbv5rIplg1UHJf.DQuKdqGrK', NULL, NULL, 794448, 17484947, NULL, NULL, NULL, '2023-07-01 22:34:53', 0, NULL, '', '', '0000-00-00 00:00:00'),
(4, 'heyys', 106, '$2y$10$V20UMNrsXgU6rvz9QHBGxOAq.2KOQEbv5rIplg1UHJf.DQuKdqGrK', NULL, NULL, 0, 16851001, NULL, NULL, NULL, '2023-07-01 22:34:53', 0, NULL, '', '', '0000-00-00 00:00:00'),
(1288649, 'crazy8', 0, '$2y$10$I5E2MjLtDCj/BCtGYiT5eeMXYx1PegaH53YN8hWM4qxUdXteUhInm', 'member', '', 0, 0, NULL, NULL, NULL, '2023-09-09 03:54:55', 0, NULL, '', '', '0000-00-00 00:00:00'),
(1291407, 'Kieshabanks', 0, '$2y$10$V2wfYx3NVhlKLwRZxvZ3X.h1h1YXH6AiuwwsJOwAYb4p7W5/TmYWG', 'member', '', 0, 0, NULL, NULL, NULL, '2024-10-04 12:52:44', 0, NULL, 'null', NULL, NULL),
(1323655, 'Kabarito', 0, '$2y$10$bfQyX5KANjlRQswbbeMWq.77UAkSBbrWb07nzs1i1sQ9hnB6jPvom', 'member', '', 0, 0, NULL, NULL, NULL, '2023-12-26 02:19:07', 0, NULL, 'null', '', '0000-00-00 00:00:00'),
(1419863, 'steve11', 0, '$2y$10$ew9dz4XbR1ccdIUA1b5jT.6UQrpqL9CJOFI/eoZyFeEDzZE0ZIn7W', 'member', '', 0, 0, NULL, NULL, NULL, '2023-09-17 13:34:03', 0, NULL, '', '', '0000-00-00 00:00:00'),
(1512725, 'Moltisanti', 0, '$2y$10$CCa7m5QuQ8Ai1ztamf3wtegD4Ki0XfrAiY5n3hSyGbgrKF5OajG6a', 'member', '', 0, 0, NULL, NULL, NULL, '2024-10-09 17:48:17', 0, NULL, 'null', NULL, NULL),
(1552212, 'mee', 0, '$2y$10$ism3375qAVEPRfknvm2yHOtNnai7wu4uNum.jN9ncShndN0Z.eOm.', 'member', '', 0, 0, NULL, NULL, NULL, '2023-07-01 22:34:53', 0, NULL, '', '', '0000-00-00 00:00:00'),
(1636760, 'test4', 0, '$2y$10$sFJhtveV8iKNcful2r4kdOrqKZu0hgriM/DF9yzMXjCsLP/xmBen.', 'member', 'ok', 0, 0, NULL, NULL, NULL, '2023-07-01 22:34:53', 0, NULL, '', '', '0000-00-00 00:00:00'),
(1682915, 'Mirro', 0, '$2y$10$oJYVf0x.eTM3Xw4EVbfTqeoD/ne0yfiMNB5/O4G9DLD4s.1hZYxJa', 'member', '', 0, 0, NULL, NULL, NULL, '2023-07-01 22:34:53', 0, NULL, '', '', '0000-00-00 00:00:00'),
(1903606, 'zev', 110, '$2y$10$o5cRRguIcXnO27G1i2avkOjCHzJQ72dtI1./GeDc2ky.NRX4rQ1qi', 'member', '', 0, 18620725, NULL, NULL, NULL, '2023-11-21 15:22:15', 0, NULL, 'theme-warning', '', '0000-00-00 00:00:00'),
(1916668, 'jamesbond2', 0, '$2y$10$5DJk77cDcx1.PeUF5J5DuOhwxvB8mNnkHnw1UCcseOq9uaSI66VBy', 'member', '', 0, 0, NULL, NULL, NULL, '2023-12-12 19:13:40', 0, NULL, 'theme-purple', '', '0000-00-00 00:00:00'),
(1926454, 'chalino', 0, '$2y$10$N3Vvu9IIAH9yAJ8jM4T6x.c7ew2oh23bTrove4joVnGBIV3eQcwBS', 'member', '@OscarMedinaGonzales', 0, 0, NULL, NULL, NULL, '2024-10-29 15:27:38', 0, NULL, 'theme-yellow', NULL, NULL),
(1929583, 'astro', 0, '$2y$10$0xoWVsowFfMSPzGKi637Mel/dwKXOA4VFFr4zTWxZ.qcnL9fb6Rxy', 'member', '', 0, 0, NULL, NULL, NULL, '2024-10-29 22:02:12', 0, NULL, 'null', NULL, NULL),
(2106733, 'RichDolph', 0, '$2y$10$FydNUw0Obfg7rtcDqrpDK.3lxi/XV0104.ePRdUEOCPInju.KuQaC', 'member', '', 0, 0, NULL, NULL, NULL, '2024-07-08 11:25:07', 0, NULL, 'null', '', '0000-00-00 00:00:00'),
(2337455, 'todisappearr', 0, '$2y$10$7QvwpQtBHWy.tOxNXN53suUKv1VTOqc4IGPZ.C0f4G6YtbfdIR726', 'member', 'todisappearr', 0, 0, NULL, NULL, NULL, '2023-07-01 22:34:53', 0, NULL, '', '', '0000-00-00 00:00:00'),
(2518002, 'cashthetarg', 0, '$2y$10$93GOeeHgCofNWBK66Kb/WeUg0/d7utppeXb5Kx5qTwYQLGZOu3utq', 'member', 'fed', 0, 0, NULL, NULL, NULL, '2024-11-26 21:33:23', 0, NULL, NULL, NULL, NULL),
(2581218, 'james', 0, '$2y$10$lCQgijZeGVZq2bqB5WobU.Q70QP2Vp7BXlkSEmbhR38s.TfW05YVK', 'member', 'james', 0, 0, NULL, NULL, NULL, '2023-07-01 22:34:53', 0, NULL, '', '', '0000-00-00 00:00:00'),
(2602887, 'johnberlin999', 0, '$2y$10$pW4zqgvFFAANikyoShCcUeGXHQjbWjxVw2WLG1gTDymhPGu9HGoly', 'member', 'johnberlinworkaccount', 0, 0, NULL, NULL, NULL, '2024-11-18 20:51:38', 0, NULL, 'theme-red', NULL, NULL),
(2651590, 'mkdir', 0, '$2y$10$/so/r6tD2n1YS2ccEClSd.thmeJrJ5xlHCYUGCvYdp.LsZvM71/ra', 'member', '', 0, 0, NULL, NULL, NULL, '2023-07-01 22:34:53', 0, NULL, '', '', '0000-00-00 00:00:00'),
(2889547, 'makilith', 0, '$2y$10$GGTgVq3lUp5hta9qXvu1rO8/2uqlSFrGKLeGU7jiKg.1FzD4SmtPq', 'member', '', 0, 0, NULL, NULL, 1, '2024-10-03 21:07:31', 0, NULL, 'theme-info', NULL, NULL),
(2998012, 'asdasd', 0, '$2y$10$K2O9fOJFZfXJ6FT.k1AcduQl9P2tfqkIGSlJnuNhL1esN9Ogjw9sy', 'member', '', 0, 0, NULL, NULL, NULL, '2024-11-20 16:27:47', 0, NULL, 'theme-warning', NULL, NULL),
(3069021, 'Marcel', 231, '$2y$10$bD8lskzGDOtZqN/yeLxTe..plIlydxM.FCaP1V5wApe0UDVxfbYN6', 'admin', 'Thuisb', 786865, 16858073, NULL, NULL, NULL, '2024-10-03 04:41:32', 0, 'b2sgdGVzdAoKCnRoaXMgaXMgYSB0ZXN0Cg==', 'theme-indigo', '', '0000-00-00 00:00:00'),
(3205520, 'nav', 0, '$2y$10$KQ2CL6iw8o/UMvrRZj6mPOh/wuLSFPJB0wWh/PqjroV6rnHWDwpBy', 'member', '', 0, 0, NULL, NULL, NULL, '2023-11-06 11:36:27', 0, NULL, '', '', '0000-00-00 00:00:00'),
(3243937, 'refwe', 0, '$2y$10$faiqmMQSfakTXsPOnF906eFca8URhRkCs5nhwO/KunWT4JDyn8JFy', 'member', 'ewfef', 0, 0, NULL, NULL, NULL, '2023-07-01 22:34:53', 0, NULL, '', '', '0000-00-00 00:00:00'),
(3450269, 'fmoney', 0, '$2y$10$.0gcbRnueXINdNA.5y9xpOgXAN.ivNHs4sXuS5OQvSd6vc/V7/Ypa', 'member', 'chs21er', 0, 0, NULL, NULL, NULL, '2023-07-09 23:38:01', 0, NULL, '', '', '0000-00-00 00:00:00'),
(3649261, 'random', 0, '$2y$10$H7I6YvIRgUD0vwsGbCtpUervJopW8zm8FLElqufFEkY94vAhLgNWm', 'member', '', 0, 0, NULL, NULL, NULL, '2023-08-03 09:08:28', 0, NULL, '', '', '0000-00-00 00:00:00'),
(3744112, 'breath', 0, '$2y$10$iv9Uq/qZdJiGBrUJryKNKOjtLqpGyaGINlB5SYFC.uGmVe/h85UeW', 'admin', '', 0, 0, NULL, NULL, NULL, '2023-08-13 08:12:10', 0, NULL, '', '', '0000-00-00 00:00:00'),
(3790669, 'Milk', 0, '$2y$10$GFwSMk0NqYE7gtfm0TfpfOyiHY41LzBIACVcKU4Nt.Feh7.itfi0q', 'member', '', 0, 0, NULL, NULL, 1, '2024-07-08 11:01:28', 0, NULL, 'theme-teal', '', '2024-07-17 08:55:38'),
(3841369, 'jamesbond', 50000, '$2y$10$.Z9ujGybC.1g1rCyOErTo.EH4msuGhRRpaxmqVSYdFpXssCwu7vTW', 'admin', '', 0, 18620707, '643413411', 1, 0, '2024-01-16 18:23:45', 0, 'JDJ5JDEwJC5aOXVqR3liQy4xZzFyQ3lPRXJUby5FSDRtc3VHaFJScGF4bXFWU1lkRnBYc3NDd3U3dlRX', 'theme-green', '', '2024-07-17 16:00:19'),
(3849997, 'Davinchi', 0, '$2y$10$LxCns3kvEXY0T2K0dFOU6.JMPhQdHBEEOgog92wWVat7LTI34Y4Bu', 'member', '', 0, 0, NULL, NULL, NULL, '2024-09-26 15:03:10', 0, NULL, 'null', NULL, NULL),
(3936517, 'Booboo', 0, '$2y$10$qgb1vy.M.d07BQ93LhPzju1apSGQ8Fwsztr4OQuRIi5AOmNatxvg6', 'member', 'Taxsfree', 0, 0, NULL, NULL, NULL, '2023-07-03 00:40:31', 0, NULL, '', '', '0000-00-00 00:00:00'),
(3946674, 'test123', 0, '$2y$10$Qn2d9AGNQz5rsF6dD3pup.n.6cEH9ioK/acinnVRG5qRP4UHp2XS.', 'member', 'test234', 0, 0, '1761604515', NULL, NULL, '2023-09-08 21:55:30', 0, NULL, '', '', '0000-00-00 00:00:00'),
(4043858, 'KasperSky', 0, '$2y$10$HNESplR6sB8xJ.lwk8xwZOltxlb1qvyW15yVgjPwO.Bv7zqpvz7ri', 'member', 't.me/KasperSky_37', 0, 0, NULL, NULL, NULL, '2024-07-07 15:44:39', 0, NULL, 'theme-pink', '', '0000-00-00 00:00:00'),
(4104235, 'Lester', 0, '$2y$10$3rhu5VE7QPYA2Ue7vbC8CeQchztukAZ.BecFeIXyYLzMWb8skCLOi', 'member', '@LesterBV', 0, 0, NULL, NULL, NULL, '2023-12-21 02:38:38', 0, NULL, 'theme-yellow', '', '0000-00-00 00:00:00'),
(4119857, 'Dolphuser1', 0, '$2y$10$39Pai2JEKWA8SGPip4p8AeHZnpZap2tg33y0KGLCtIdurRLiex2BO', 'member', 'dwasd234234', 0, 0, NULL, NULL, NULL, '2024-11-12 21:23:11', 0, NULL, NULL, NULL, NULL),
(4159291, 'daddymiq', 0, '$2y$10$Zdzs8IDr0BTXZTKpDpTyDO95oD7O3Vz6SCSOt4WrZ7kdmCR7YdLpG', 'member', 'nexpn', 0, 0, NULL, NULL, NULL, '2024-09-02 12:50:03', 0, NULL, NULL, NULL, NULL),
(4271814, 'dailybtc', 0, '$2y$10$WhAEZfF2.a1IriEAXmyU1OeFsNf4HgK7CQEFry8A/natqdI2rSMWy', 'member', '', 0, 0, NULL, NULL, NULL, '2024-10-19 15:56:45', 0, NULL, 'null', NULL, NULL),
(4503446, 'newuser', 0, '$2y$10$pzDpWaDxFsouqhpUUs99feeQwV/Bb6ZLM3K4PfIAm7zew5gvmjD5m', 'member', '', 0, 0, NULL, NULL, NULL, '2023-12-29 15:43:48', 0, NULL, 'theme-yellow', '', '0000-00-00 00:00:00'),
(4764037, 'Immattmann', 0, '$2y$10$Gw9J0Rrd80z3DJjLlhH4FexG/2lienwRaY0VTZqCXR9n8hUtBmcii', 'member', 'onlymattmann', 0, 0, NULL, NULL, NULL, '2024-10-21 17:33:52', 0, NULL, NULL, NULL, NULL),
(5316746, 'Willemde3e', 0, '$2y$10$V71HPKFgGfdDS8l8cgFYpe4RrzeOhZvRq2wXFsJtrRDo/7etMSEO2', 'member', '', 0, 0, NULL, NULL, NULL, '2024-10-20 00:58:10', 0, NULL, 'null', NULL, NULL),
(5565827, 'thgfdgfd', 0, '$2y$10$3YnuVl9qRENrDrZ9gUar1OMHYTa88.U3xqW0nwkEldv1Y9OerMye2', 'member', 'thgfdgfd', 0, 0, NULL, NULL, NULL, '2024-11-18 00:19:23', 0, NULL, 'null', NULL, NULL),
(5613713, 'someone', 0, '$2y$10$tHu4uNa.XbX1U7eTYUAe1.ZfXxJzm3vUvS2xgdnZY7oERsnTTXLLu', 'member', '@fg0d1', 0, 0, NULL, NULL, NULL, '2023-07-01 22:34:53', 0, NULL, '', '', '0000-00-00 00:00:00'),
(5672949, 'rengar', 0, '$2y$10$FWthBu7g3VA949f54WR6lOWj0CFu.pL2qCF1ehkr5ReK3azazJp5S', 'member', '', 0, 0, NULL, NULL, NULL, '2024-10-29 13:33:46', 0, NULL, 'null', NULL, NULL),
(5806790, 'Trapjack', 33, '$2y$10$Rl0yo/uTftRRfIEwGaSpDes17Q1/spznyWMyJW3EmWH7pIp0nlPm.', 'member', 'Trapjack1', 781433, 0, NULL, NULL, NULL, '2023-07-03 00:36:45', 0, NULL, 'theme-teal', '', '0000-00-00 00:00:00'),
(5889176, 'makebelieve', 0, '$2y$10$x2bFpRsjpjRkQxUfmzE71uQMHmD..H7xDJW1.x/rEcUZ2j3Exj6Zi', 'member', '', 0, 0, NULL, NULL, NULL, '2023-08-11 05:30:16', 0, NULL, '', '', '0000-00-00 00:00:00'),
(5991693, 'popslag959', 0, '$2y$10$TSfNCrlnfFd6lnktfsxp8uPvfcBgywyRoP6P/LdKN5.LcBz/s8sl2', 'member', 'todisappearr', 0, 0, NULL, NULL, NULL, '2023-07-01 22:34:53', 0, NULL, '', '', '0000-00-00 00:00:00'),
(6037485, 'empty', 0, '$2y$10$HuVTJV26rzG493.KlO21fuLD9wGoLXFzGtzjlldARTU387V/ceVoS', 'member', '', 0, 0, NULL, NULL, NULL, '2023-10-13 09:52:16', 0, NULL, '', '', '0000-00-00 00:00:00'),
(6132768, 'euphoria4628', 0, '$2y$10$YuXedGEN5PXYAXWAvMeA6u0yVf1usu36/wfBIvPOJx3PjxMVsIj/q', 'member', 'euphoria4628', 0, 0, NULL, NULL, NULL, '2024-11-18 12:52:02', 0, NULL, 'theme-warning', NULL, NULL),
(6264076, 'todisappear', 0, '$2y$10$epzD8FP6O9meq/OOTHm5kuk8ptUGMfbSTJKi1hICpY8O3pqF9ucfK', 'member', 'todisappear', 0, 0, NULL, NULL, NULL, '2023-08-01 18:43:26', 0, NULL, '', '', '0000-00-00 00:00:00'),
(6283488, 'Nigger98', 0, '$2y$10$Hu0mMlE6zz5msNDJwqlnVuQ/dyw0DJ9Hage6bIJa3FfRku1dlaWvK', 'member', 'Nigger87282628268262', 0, 0, NULL, NULL, NULL, '2024-10-23 17:04:23', 0, NULL, 'theme-gray-200', NULL, NULL),
(6302446, 'Kingofff', 0, '$2y$10$rzfuAHcs6w4mL83RYsIcE.FeBQYHkEv7TJHD45ARE5pIZRPDsdpw.', 'member', 'King', 0, 0, NULL, NULL, NULL, '2024-10-30 21:51:42', 0, NULL, 'null', NULL, NULL),
(6353509, 'Sekret', 0, '$2y$10$WwJzRyM3K8MsywkuOmWOiOy7neqcdNqWn9qH10OyvPuAevNLzkT3e', 'member', 'saladcode', 0, 0, NULL, NULL, NULL, '2023-07-01 22:34:53', 0, NULL, '', '', '0000-00-00 00:00:00'),
(6443168, 'Mobster', 0, '$2y$10$jOFRgsAyU4TdQN7yP/i3Gew.rG1Jeax9Wunm..H6YGteS9aoLTk2a', 'member', 'Okay_rob', 0, 0, NULL, NULL, NULL, '2024-11-24 04:38:29', 0, NULL, 'null', NULL, NULL),
(6458362, 'hehe', 0, '$2y$10$1C9LGWENRrcQyzcW21YjneKuNZcON3ZfNIua9CgL.YG.c5gwexbiq', 'member', '', 0, 0, NULL, NULL, NULL, '2023-11-20 17:04:18', 0, NULL, 'theme-warning', '', '0000-00-00 00:00:00'),
(6462405, 'wefwefwefw', 0, '$2y$10$tdEc9i.ehtrSR5XX0N.6FOisnWdnlyNrnd9W1LwV7qEx01mK9TDWy', 'member', 'efwefwef', 0, 0, NULL, NULL, NULL, '2023-11-20 17:03:35', 0, NULL, NULL, '', '0000-00-00 00:00:00'),
(6618360, 'Dollie', 0, '$2y$10$1yvfmNl2Hn4dSKjbhLIia.7WbMKv5tsmKjhdxSxtXgEFXF0h3g6rm', 'member', '', 0, 0, NULL, NULL, NULL, '2024-11-28 23:40:31', 0, 'cmFjY29vbiBkYW1hZ2Ugc3ltcHRvbSBhaW0gc3BpY2UgdGVhY2ggZ3ltIHBlYWNlIGd1aXRhciBjaGVjayBwYXJlbnQgY291bnRyeSBzY2VuZSA=', NULL, NULL, NULL),
(6797713, 'blackkaizer', 0, '$2y$10$O6XQVqiKsY/avGO0fL.O5.OuRsHL2uVFlaIPHSlVxJ/Z9O38CzORG', 'member', 'yamenrv', 0, 0, NULL, NULL, NULL, '2024-10-17 12:31:16', 0, NULL, 'null', NULL, NULL),
(6833867, 'admin', 0, '$2y$10$zVEWwx2dMxlIRBWo8fUr0uvvCeh1CDWxYiSWJBcKkz.YbqHb9qyBe', 'member', '', 0, 0, NULL, NULL, NULL, '2024-11-17 23:10:16', 0, NULL, 'theme-red', NULL, NULL),
(6995679, 'abc123FF', 0, '$2y$10$zfjv1KBHO.WkDqJJQMniyOMUAq9HZ/UZ2K7XLJppuJEWXPxrAd3dm', 'member', '', 0, 0, NULL, NULL, NULL, '2024-10-18 01:46:51', 0, NULL, 'null', NULL, NULL),
(7126761, 'Starrish', 0, '$2y$10$ZIizwWrFAP/cZatx2qFHLOMfx.4DV8szhB.Shnj1hG4aHGkhu0Xly', 'member', '@p', 0, 0, NULL, NULL, NULL, '2023-07-01 22:34:53', 0, NULL, '', '', '0000-00-00 00:00:00'),
(7253331, 'onlymattmann', 0, '$2y$10$NsdwrD6kCm/h8qMHaB/SR./YokrQ/DaujIolCgM7CFclnrShB8bTy', 'member', 'onlymattmann', 0, 0, NULL, NULL, NULL, '2024-10-11 21:49:19', 0, NULL, 'null', NULL, NULL),
(7389378, 'testuser200', 0, '$2y$10$yVCwB7wV6bBDMCQG4hwtqe/nSJFJ97SaN4ckLCCKSotgQFSlmz/ZO', 'member', 'testuser200', 0, 0, NULL, NULL, NULL, '2023-07-01 22:34:53', 0, NULL, '', '', '0000-00-00 00:00:00'),
(7825918, 'Apple', 0, '$2y$10$aCnHdcqOu2AJi0INqFGBS.3BQiiZ7NadpquzYETtmKmp2.2Yi5s06', 'member', '@Aasdfvuhv', 0, 0, NULL, NULL, NULL, '2024-07-17 10:38:35', 0, NULL, 'undefined', '', '2024-07-17 08:51:46'),
(7831439, 'nexpn', 0, '$2y$10$.PodxhIWSKRuV0ImotizAOqab8kgJE9nlXTvjCtYsZsg0D4z5KddW', 'member', 'nexpn', 0, 0, NULL, 1, NULL, '2024-09-02 13:12:43', 0, NULL, 'theme-pink', NULL, NULL),
(7857586, 'nexpn3', 824000, '$2y$10$kW7DUnEGLzMwvcSVwRsNfObW.qFjG8eLiCZADX2L0tUUKS0XGiqk2', 'member', 'nexpn', 0, 0, ' -4569284673 ', NULL, NULL, '2024-11-03 18:41:39', 0, NULL, 'theme-red', NULL, NULL),
(8128527, 'wifeless', 0, '$2y$10$ri9VZISPoaZ5SE/YB/LRLOmw83um.bHWCVu9nn/j5gCe8HyVuiiL.', 'member', 'retard', 0, 0, NULL, NULL, NULL, '2024-11-19 18:39:14', 0, NULL, 'null', NULL, NULL),
(8156650, 'meep', 0, '$2y$10$xLC5eaJkK/684Y651X2iEerQ9dK4T0gIQBl5skj.00xHrBBlpxmNm', 'member', '', 0, 0, NULL, NULL, NULL, '2023-07-01 22:34:53', 0, NULL, '', '', '0000-00-00 00:00:00'),
(8188580, 'test1234', 0, '$2y$10$W0z5773pQxv.q2FAjAim3O19cQ9Gsn3wMco3oh.Ifeo4K3XOW3fMW', 'member', '', 0, 0, NULL, NULL, NULL, '2023-07-01 22:34:53', 0, NULL, '', '', '0000-00-00 00:00:00'),
(8198931, 'monke', 0, '$2y$10$cA4VQWC2NXxLOm7KCgyRW.BmeEHR8MJsn/mAQRnu.4AocfrpPVjg.', 'member', '', 0, 0, NULL, NULL, NULL, '2023-07-01 22:34:53', 0, NULL, '', '', '0000-00-00 00:00:00'),
(8303290, 'Sigma', 0, '$2y$10$LXnWL8dm2WPMnIIOdYxSDej/o24qUtdwMvwWmwHLfMCTb4dyM36HW', 'member', 'sigma123', 0, 0, NULL, NULL, NULL, '2024-11-18 16:23:19', 0, NULL, 'null', NULL, NULL),
(8621852, 'AngelFerno', 0, '$2y$10$eDm/um4f9Vfvx5wKs3IDiOxEr26ytxvmeWJTePtgY8bi0e8mWSeBy', 'member', 'AngelFerno', 0, 0, NULL, NULL, 1, '2024-11-08 16:42:45', 0, NULL, 'theme-lime', NULL, NULL),
(8638510, 'Glasvezel', 0, '$2y$10$itcRHE/Mv1Rc/3QyL5/.Ru7zzAj4Lj8PPosNvQg.arCYPogmgoprW', 'member', 'Glassoe', 0, 0, NULL, NULL, NULL, '2023-07-01 22:34:53', 0, NULL, '', '', '0000-00-00 00:00:00'),
(8839644, 'stevee', 0, '$2y$10$vyajbUuN/cvu65A1kTtdBuS731Xnze/2vMbSvJO434s75wGV9iSIi', 'member', 'Steve', 0, 0, NULL, NULL, NULL, '2023-07-13 19:58:42', 0, NULL, '', '', '0000-00-00 00:00:00'),
(8961295, 'gaynigga', 0, '$2y$10$6r7li46Cej.muAW7/FrnsOTm24S7yMlI5yHfU1IKYxrU6SMqPZex2', 'member', '', 0, 0, NULL, NULL, NULL, '2024-09-19 15:43:28', 0, NULL, 'null', NULL, NULL),
(9024736, 'chops', 1650000, '$2y$10$ynhq/Yiv2WVZyNd3TGqwiujtoTv8qwqKYnmaBLau9scU3KeA8vR5.', 'admin', 'strr7', 0, 17544556, '-4569284673', 0, 1, '2024-11-28 16:52:57', 1, '', 'theme-indigo', '', '2024-07-17 19:51:49'),
(9385127, 'jobs', 0, '$2y$10$lQIAZnAc7TsWaUS7HoW10.0jQBDXI2w9HnGdO/WX66zNIkZMfYL62', 'member', '', 0, 0, NULL, NULL, NULL, '2023-09-19 22:33:07', 0, NULL, '', '', '0000-00-00 00:00:00'),
(9561505, 'Fmoney21', 0, '$2y$10$qVxCXisSxQ7R9sod0ilidOPifahiDgct1UxE4f/KF2SrHHPnOJ.BC', 'member', 'Chs21er', 0, 0, NULL, NULL, NULL, '2023-10-02 22:00:10', 1, NULL, '', '', '0000-00-00 00:00:00'),
(9686513, 'vuuppp', 0, '$2y$10$vaONsTszM3KAZ1fYF35Q.eYjhE/M.5g4B2Gr9MmvAvscI.n67BhEO', 'member', 'vuuppp', 0, 0, '-4557197892', 1, NULL, '2024-10-17 17:23:56', 0, NULL, 'theme-indigo', NULL, NULL),
(9927708, 'penguin', 0, '$2y$10$/H3nuIAqZ3i4iYtGgUUrYeOrmzqtYgRsLacE6R5vbiv7y.dwtT83q', 'member', '', 0, 0, NULL, NULL, NULL, '2023-07-01 22:34:53', 0, NULL, '', '', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blueprints`
--
ALTER TABLE `blueprints`
  ADD PRIMARY KEY (`blueprint`),
  ADD KEY `creatorUserId` (`creatorUserId`);

--
-- Indexes for table `blueprints_index`
--
ALTER TABLE `blueprints_index`
  ADD PRIMARY KEY (`fileID`),
  ADD KEY `blueprint` (`blueprint`),
  ADD KEY `pagefile` (`pagefile`);

--
-- Indexes for table `blueprints_tokens`
--
ALTER TABLE `blueprints_tokens`
  ADD PRIMARY KEY (`tokenID`),
  ADD KEY `blueprint` (`blueprint`),
  ADD KEY `file` (`pagefile`);

--
-- Indexes for table `btc_addresses`
--
ALTER TABLE `btc_addresses`
  ADD PRIMARY KEY (`wallet_index`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `btc_transactions`
--
ALTER TABLE `btc_transactions`
  ADD PRIMARY KEY (`hash`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `eth_addresses`
--
ALTER TABLE `eth_addresses`
  ADD PRIMARY KEY (`eth_wallet_index`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `eth_transactions`
--
ALTER TABLE `eth_transactions`
  ADD PRIMARY KEY (`hash`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `hosts`
--
ALTER TABLE `hosts`
  ADD PRIMARY KEY (`domain`),
  ADD KEY `domain` (`domain`);

--
-- Indexes for table `logs_editor`
--
ALTER TABLE `logs_editor`
  ADD PRIMARY KEY (`SessionID`);

--
-- Indexes for table `nodes`
--
ALTER TABLE `nodes`
  ADD PRIMARY KEY (`nodeId`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notificationID`),
  ADD KEY `userID` (`userId`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orderId`),
  ADD KEY `productID` (`productID`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `panels`
--
ALTER TABLE `panels`
  ADD PRIMARY KEY (`panelId`,`nodeId`) USING BTREE,
  ADD UNIQUE KEY `panelId` (`panelId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `nodeId` (`nodeId`);

--
-- Indexes for table `panel_access`
--
ALTER TABLE `panel_access`
  ADD UNIQUE KEY `panelId` (`panelId`,`userId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `access` (`access`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`productID`),
  ADD KEY `productCreator` (`creator`);

--
-- Indexes for table `productTags`
--
ALTER TABLE `productTags`
  ADD PRIMARY KEY (`tagID`),
  ADD KEY `productID` (`productID`);

--
-- Indexes for table `respons_editor`
--
ALTER TABLE `respons_editor`
  ADD PRIMARY KEY (`responsID`),
  ADD KEY `SessionID` (`SessionID`);

--
-- Indexes for table `shortlink`
--
ALTER TABLE `shortlink`
  ADD PRIMARY KEY (`link`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `shortlink_domains`
--
ALTER TABLE `shortlink_domains`
  ADD PRIMARY KEY (`domain`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD KEY `user` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blueprints_index`
--
ALTER TABLE `blueprints_index`
  MODIFY `fileID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notificationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=485;

--
-- AUTO_INCREMENT for table `productTags`
--
ALTER TABLE `productTags`
  MODIFY `tagID` int(32) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blueprints`
--
ALTER TABLE `blueprints`
  ADD CONSTRAINT `blueprints_ibfk_1` FOREIGN KEY (`creatorUserId`) REFERENCES `users` (`userId`);

--
-- Constraints for table `blueprints_index`
--
ALTER TABLE `blueprints_index`
  ADD CONSTRAINT `blueprints_index_ibfk_1` FOREIGN KEY (`blueprint`) REFERENCES `blueprints` (`blueprint`);

--
-- Constraints for table `blueprints_tokens`
--
ALTER TABLE `blueprints_tokens`
  ADD CONSTRAINT `blueprints_tokens_ibfk_1` FOREIGN KEY (`blueprint`) REFERENCES `blueprints` (`blueprint`);

--
-- Constraints for table `btc_addresses`
--
ALTER TABLE `btc_addresses`
  ADD CONSTRAINT `btc_addresses_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);

--
-- Constraints for table `btc_transactions`
--
ALTER TABLE `btc_transactions`
  ADD CONSTRAINT `btc_transactions_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);

--
-- Constraints for table `eth_addresses`
--
ALTER TABLE `eth_addresses`
  ADD CONSTRAINT `eth_addresses_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `products` (`productID`);

--
-- Constraints for table `panels`
--
ALTER TABLE `panels`
  ADD CONSTRAINT `panels_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `panels_ibfk_2` FOREIGN KEY (`nodeId`) REFERENCES `nodes` (`nodeID`);

--
-- Constraints for table `panel_access`
--
ALTER TABLE `panel_access`
  ADD CONSTRAINT `panel_access_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `panel_access_ibfk_2` FOREIGN KEY (`panelId`) REFERENCES `panels` (`panelId`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `productCreator` FOREIGN KEY (`creator`) REFERENCES `users` (`userId`);

--
-- Constraints for table `productTags`
--
ALTER TABLE `productTags`
  ADD CONSTRAINT `productTags_ibfk_1` FOREIGN KEY (`productID`) REFERENCES `products` (`productID`);

--
-- Constraints for table `respons_editor`
--
ALTER TABLE `respons_editor`
  ADD CONSTRAINT `respons_editor_ibfk_1` FOREIGN KEY (`SessionID`) REFERENCES `logs_editor` (`SessionID`);

--
-- Constraints for table `shortlink`
--
ALTER TABLE `shortlink`
  ADD CONSTRAINT `shortlink_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
