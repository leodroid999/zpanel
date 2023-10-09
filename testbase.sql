-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 09, 2023 at 05:25 PM
-- Server version: 10.3.34-MariaDB-0ubuntu0.20.04.1
-- PHP Version: 7.4.3-4ubuntu2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
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
  `engine` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `blueprints`
--

INSERT INTO `blueprints` (`blueprint`, `assetDir`, `engine`) VALUES
('demo_v1.0', 'demo_v1.0.zip', 'z');

-- --------------------------------------------------------

--
-- Table structure for table `btc_addresses`
--

CREATE TABLE `btc_addresses` (
  `userId` bigint(20) NOT NULL,
  `wallet_index` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `btc_addresses`
--

INSERT INTO `btc_addresses` (`userId`, `wallet_index`) VALUES
(2, 1),
(4, 3),
(1288649, 15),
(2337455, 8),
(2581218, 4),
(2651590, 5),
(3069021, 10),
(3744112, 14),
(3841369, 16),
(3936517, 12),
(5613713, 9),
(5806790, 11),
(6264076, 13),
(7389378, 2),
(9024736, 7),
(9927708, 6);

-- --------------------------------------------------------

--
-- Table structure for table `btc_rates`
--

CREATE TABLE `btc_rates` (
  `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `rate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `eth_addresses`
--

INSERT INTO `eth_addresses` (`userId`, `eth_wallet_index`) VALUES
(2, 1),
(4, 3),
(1288649, 15),
(2337455, 8),
(2581218, 4),
(2651590, 5),
(3069021, 10),
(3744112, 14),
(3841369, 16),
(3936517, 12),
(5613713, 9),
(5806790, 11),
(6264076, 13),
(7389378, 2),
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `eth_transactions`
--

INSERT INTO `eth_transactions` (`hash`, `userId`, `address`, `valueEth`, `valueUsd`, `rate`) VALUES
('0x15c87e91fc5c468e291de6ef38dc60c9e5a448b460b9b2e52e711b892140ad3f', 4, '0xc6686436d995ffa99aab1105de3eeb65e368489d', 557931015862116, 106, 190343),
('0x583a4d98470085f2d30801d9ff79e4fd0a402e2a6cba7ddba5d8e57bd13094e7', 3069021, '0x838a77ce291b5213937847252330e9b3197c6ba8', 568097533081698, 107, 189394),
('0x6c1a3537cafae2badcfafad9513461bf5c14c0286125c3351699433b09e242ee', 9024736, '0x1bd0aee453086d53035af92fec8787aed38cef84', 525959544750797, 98, 187325);

-- --------------------------------------------------------

--
-- Table structure for table `hosts`
--

CREATE TABLE `hosts` (
  `domain` varchar(255) NOT NULL,
  `panelId` varchar(255) NOT NULL,
  `hostStatus` varchar(20) DEFAULT NULL,
  `lastCheck` timestamp NOT NULL DEFAULT current_timestamp(),
  `currentIp` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hosts`
--

INSERT INTO `hosts` (`domain`, `panelId`, `hostStatus`, `lastCheck`, `currentIp`) VALUES
('5.206.227.232', 'testpanel', 'DOMAIN_DOWN', '2023-10-08 05:11:21', NULL),
('94.247.42.210', 'shortlink', 'DOMAIN_DOWN', '2023-10-08 04:27:02', NULL),
('be-15.online', 'dolphin3', 'SITE_DOWN', '2023-10-08 04:27:02', NULL),
('dienst.pw', 'shortlink', 'SITE_DOWN', '2023-10-08 04:27:02', NULL),
('eitsme.be-19.info', 'dolphin4', 'DOMAIN_DOWN', '2023-10-08 04:27:02', NULL),
('google.com', 'hey', 'ONLINE', '2023-10-09 15:03:07', NULL),
('huobidesk.com', 'test', 'DOMAIN_DOWN', '2023-10-08 04:27:02', NULL),
('icsapp.be-40.pw', 'dolphin7', 'DOMAIN_DOWN', '2023-10-08 04:27:02', NULL),
('icsnl.be-40.pw', 'dolphin4', 'DOMAIN_DOWN', '2023-10-08 12:26:54', NULL),
('itsme-id.be-2.pw', 'shortlink', 'ONLINE', '2023-10-08 13:16:35', NULL),
('itsme-id.be-34.info', 'dolphin3', 'DOMAIN_DOWN', '2023-10-08 04:27:02', NULL),
('itsme-id.be-40.pw', 'dolphin4', 'DOMAIN_DOWN', '2023-10-08 04:27:02', NULL),
('itsme-id.com-12.info', 'dolphin3', 'SITE_DOWN', '2023-10-08 04:27:02', NULL),
('itsme.be-0.pw', 'dolphin1', 'ONLINE', '2023-10-08 13:16:35', '5.206.224.49'),
('itsme.be-117.info', 'z-panel.io', 'DOMAIN_DOWN', '2023-10-08 04:27:02', NULL),
('itsme.be-19.pw', 'dolphin8', 'ONLINE', '2023-10-08 11:15:42', NULL),
('itsme.be-2.pw', 'shortlink', 'ONLINE', '2023-10-08 13:16:35', NULL),
('itsme.be-3.pw', 'shortlink', 'ONLINE', '2023-10-09 10:16:38', NULL),
('itsme.be-34.info', 'dolphin3', 'DOMAIN_DOWN', '2023-10-08 04:27:02', NULL),
('itsme.be-4.pw', 'dolphin4', 'SITE_DOWN', '2023-10-08 12:26:54', NULL),
('itsme.be-40.pw', 'dolphin1', 'DOMAIN_DOWN', '2023-10-08 04:27:02', NULL),
('itsme.be-49.info', 'shelby', 'DOMAIN_DOWN', '2023-10-08 04:27:02', '185.236.228.185'),
('itsme.be-54.pw', 'dolphin4', 'DOMAIN_DOWN', '2023-10-08 04:27:02', NULL),
('itsme.be-77.pw', 'dolphin8', 'DOMAIN_DOWN', '2023-10-08 04:27:02', NULL),
('itsme.help', 'shortlink', 'ONLINE', '2023-10-08 13:16:35', NULL),
('itsme.mx', 'shortlink', 'DOMAIN_DOWN', '2023-10-08 12:26:54', NULL),
('itsme.sh', 'shortlink', 'ONLINE', '2023-10-09 01:46:37', NULL),
('myitsme.be-54.pw', 'dolphin1', 'DOMAIN_DOWN', '2023-10-08 04:27:02', NULL),
('q.huobidesk.com', '', 'DOMAIN_DOWN', '2023-10-08 04:27:02', NULL),
('sim.support', 'shortlink', 'SITE_DOWN', '2023-10-08 04:27:02', NULL),
('tele.be-15.support', 'thomas', 'DOMAIN_DOWN', '2023-10-08 04:27:02', '185.236.228.185'),
('test.com-12.info', 'dolphin3', 'SITE_DOWN', '2023-10-08 04:27:02', NULL),
('z-panel.io', 'dolphin4', 'SERVER_DOWN', '2023-10-08 04:27:02', '91.219.238.86'),
('z-panel.xyz', 'sponge', 'SITE_DOWN', '2023-10-08 04:27:02', NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `nodes`
--

INSERT INTO `nodes` (`nodeId`, `NodeName`, `sql_user`, `server_user`, `server_password`, `sql_key`, `ip`, `create_date`) VALUES
('okxdesk.com', 'pufferfish', NULL, 'root', 'b5186a9eb9', 'TaxsSQL83819', '45.155.249.171', '2023-03-24 13:01:48'),
('z-panel.io', 'z-panel.io', 'paneldata', 'root', 'thNZvQ4wavRR', 'TaxsSQL83819', '91.219.238.86', '2023-03-24 13:01:48'),
('z-panel.xyz', 'xyz', NULL, 'root', 'HtUEFwcmWKmMht2W', '[NEWSQLKEY]', '91.242.217.14', '2023-05-15 19:21:29');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
(64, 1288649, 'settings', 'exclamation-circle', 'Cannot deliver panel notifcations, chatId not set', '2023-10-08 03:11:21', 1);

-- --------------------------------------------------------

--
-- Table structure for table `panels`
--

CREATE TABLE `panels` (
  `panelId` varchar(255) NOT NULL,
  `nodeId` varchar(512) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `panels`
--

INSERT INTO `panels` (`panelId`, `nodeId`, `userId`, `status`, `expires`) VALUES
('blablas', 'z-panel.io', 3936517, 'PENDING', NULL),
('dolphin1', 'z-panel.io', 9024736, NULL, '2023-09-22 15:33:26'),
('dolphin10', 'z-panel.io', 9024736, 'PENDING', NULL),
('dolphin2', 'z-panel.io', 9561505, 'PENDING', NULL),
('dolphin4', 'z-panel.io', 9024736, NULL, NULL),
('dolphin7', 'z-panel.io', 9024736, 'PENDING', NULL),
('dolphin8', 'z-panel.io', 9024736, 'PENDING', NULL),
('hey', 'okxdesk.com', 3841369, 'PENDING', NULL),
('meep', 'okxdesk.com', 8188580, 'PENDING', NULL),
('meow', 'z-panel.xyz', 3744112, 'PENDING', NULL),
('rich', 'okxdesk.com', 7389378, 'PENDING', NULL),
('shelby', 'z-panel.xyz', 8188580, 'PENDING', '2023-06-29 13:15:44'),
('shortlink', 'z-panel.io', 9024736, 'PENDING', NULL),
('sponge', 'z-panel.xyz', 7389378, 'PENDING', NULL),
('test456', 'z-panel.xyz', 7389378, 'PENDING', NULL),
('testpanel', 'z-panel.io', 8188580, 'PENDING', NULL),
('xx', 'z-panel.xyz', 1, 'PENDING', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `panel_access`
--

CREATE TABLE `panel_access` (
  `panelId` varchar(255) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `access` varchar(255) NOT NULL DEFAULT 'read_only'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `panel_access`
--

INSERT INTO `panel_access` (`panelId`, `userId`, `access`) VALUES
('shelby', 1288649, 'full'),
('testpanel', 1288649, 'full'),
('shelby', 3946674, 'view'),
('testpanel', 2, 'view');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `productID` varchar(16) NOT NULL,
  `creator` bigint(20) DEFAULT NULL,
  `blueprint` varchar(64) NOT NULL,
  `productType` varchar(16) DEFAULT NULL,
  `isDownload` tinyint(4) DEFAULT NULL,
  `title` varchar(32) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `shortDescription` varchar(75) DEFAULT NULL,
  `discountPercentage` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `shortlink`
--

INSERT INTO `shortlink` (`link`, `PreferedDomain`, `userId`, `destinationUrl`, `blackTDS`) VALUES
('app-verifeer', 'itsme.sh', 9561505, 'reacti-app.ddns.net/koppelen', 0),
('D19xRQo', 'sim.support', 9024736, 'itsme.be-0.pw/koppelen', 0),
('kanker', 'itsme.sh', 9024736, 'google.com', 0),
('koppelen', 'itsme.sh', 9561505, 'reacti-app.ddns.net/koppelen', 0),
('Kx81kwO', 'itsme.help', 9024736, 'itsme.be-0.pw/koppelen', 0),
('xq6Hj5Ko', 'itsme.mx', 9024736, 'itsme-id.be-40.pw/koppelen', 0);

-- --------------------------------------------------------

--
-- Table structure for table `shortlink_domains`
--

CREATE TABLE `shortlink_domains` (
  `domain` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `lastReadNotifications` datetime NOT NULL DEFAULT current_timestamp(),
  `shortlinksPkg` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `username`, `balance`, `password`, `user_type`, `telegram`, `lastUpdateBlockBTC`, `lastUpdateBlockETH`, `chatID`, `webnotifs`, `lastReadNotifications`, `shortlinksPkg`) VALUES
(0, 'Jake', 0, NULL, NULL, NULL, 0, 0, NULL, NULL, '2023-07-05 21:07:14', 0),
(1, 'Lisa', 297, NULL, NULL, NULL, 781280, 2, NULL, NULL, '2023-07-01 22:34:53', 0),
(2, 'Steve', 0, '$2y$10$V20UMNrsXgU6rvz9QHBGxOAq.2KOQEbv5rIplg1UHJf.DQuKdqGrK', NULL, NULL, 794448, 17484947, NULL, NULL, '2023-07-01 22:34:53', 0),
(4, 'heyys', 106, '$2y$10$V20UMNrsXgU6rvz9QHBGxOAq.2KOQEbv5rIplg1UHJf.DQuKdqGrK', NULL, NULL, 0, 16851001, NULL, NULL, '2023-07-01 22:34:53', 0),
(1288649, 'crazy8', 0, '$2y$10$I5E2MjLtDCj/BCtGYiT5eeMXYx1PegaH53YN8hWM4qxUdXteUhInm', 'member', '', 0, 0, NULL, NULL, '2023-09-09 03:54:55', 0),
(1419863, 'steve11', 0, '$2y$10$ew9dz4XbR1ccdIUA1b5jT.6UQrpqL9CJOFI/eoZyFeEDzZE0ZIn7W', 'member', '', 0, 0, NULL, NULL, '2023-09-17 13:34:03', 0),
(1552212, 'mee', 0, '$2y$10$ism3375qAVEPRfknvm2yHOtNnai7wu4uNum.jN9ncShndN0Z.eOm.', 'member', '', 0, 0, NULL, NULL, '2023-07-01 22:34:53', 0),
(1636760, 'test4', 0, '$2y$10$sFJhtveV8iKNcful2r4kdOrqKZu0hgriM/DF9yzMXjCsLP/xmBen.', 'member', 'ok', 0, 0, NULL, NULL, '2023-07-01 22:34:53', 0),
(1682915, 'Mirro', 0, '$2y$10$oJYVf0x.eTM3Xw4EVbfTqeoD/ne0yfiMNB5/O4G9DLD4s.1hZYxJa', 'member', '', 0, 0, NULL, NULL, '2023-07-01 22:34:53', 0),
(2337455, 'todisappearr', 0, '$2y$10$7QvwpQtBHWy.tOxNXN53suUKv1VTOqc4IGPZ.C0f4G6YtbfdIR726', 'member', 'todisappearr', 0, 0, NULL, NULL, '2023-07-01 22:34:53', 0),
(2581218, 'james', 0, '$2y$10$lCQgijZeGVZq2bqB5WobU.Q70QP2Vp7BXlkSEmbhR38s.TfW05YVK', 'member', 'james', 0, 0, NULL, NULL, '2023-07-01 22:34:53', 0),
(2651590, 'mkdir', 0, '$2y$10$/so/r6tD2n1YS2ccEClSd.thmeJrJ5xlHCYUGCvYdp.LsZvM71/ra', 'member', '', 0, 0, NULL, NULL, '2023-07-01 22:34:53', 0),
(3069021, 'Marcel', 231, '$2y$10$bD8lskzGDOtZqN/yeLxTe..plIlydxM.FCaP1V5wApe0UDVxfbYN6', 'admin', 'Thuisb', 786865, 16858073, NULL, NULL, '2023-07-15 00:07:12', 0),
(3243937, 'refwe', 0, '$2y$10$faiqmMQSfakTXsPOnF906eFca8URhRkCs5nhwO/KunWT4JDyn8JFy', 'member', 'ewfef', 0, 0, NULL, NULL, '2023-07-01 22:34:53', 0),
(3450269, 'fmoney', 0, '$2y$10$.0gcbRnueXINdNA.5y9xpOgXAN.ivNHs4sXuS5OQvSd6vc/V7/Ypa', 'member', 'chs21er', 0, 0, NULL, NULL, '2023-07-09 23:38:01', 0),
(3649261, 'random', 0, '$2y$10$H7I6YvIRgUD0vwsGbCtpUervJopW8zm8FLElqufFEkY94vAhLgNWm', 'member', '', 0, 0, NULL, NULL, '2023-08-03 09:08:28', 0),
(3744112, 'breath', 0, '$2y$10$iv9Uq/qZdJiGBrUJryKNKOjtLqpGyaGINlB5SYFC.uGmVe/h85UeW', 'admin', '', 0, 0, NULL, NULL, '2023-08-13 08:12:10', 0),
(3841369, 'jamesbond', 0, '$2y$10$.Z9ujGybC.1g1rCyOErTo.EH4msuGhRRpaxmqVSYdFpXssCwu7vTW', 'admin', '', 0, 0, NULL, NULL, '2023-10-09 12:29:24', 0),
(3936517, 'Booboo', 0, '$2y$10$qgb1vy.M.d07BQ93LhPzju1apSGQ8Fwsztr4OQuRIi5AOmNatxvg6', 'member', 'Taxsfree', 0, 0, NULL, NULL, '2023-07-03 00:40:31', 0),
(3946674, 'test123', 0, '$2y$10$Qn2d9AGNQz5rsF6dD3pup.n.6cEH9ioK/acinnVRG5qRP4UHp2XS.', 'member', 'test234', 0, 0, '1761604515', NULL, '2023-09-08 21:55:30', 0),
(5613713, 'someone', 0, '$2y$10$tHu4uNa.XbX1U7eTYUAe1.ZfXxJzm3vUvS2xgdnZY7oERsnTTXLLu', 'member', '@fg0d1', 0, 0, NULL, NULL, '2023-07-01 22:34:53', 0),
(5806790, 'Trapjack', 33, '$2y$10$Rl0yo/uTftRRfIEwGaSpDes17Q1/spznyWMyJW3EmWH7pIp0nlPm.', 'member', 'Trapjack1', 781433, 0, NULL, NULL, '2023-07-03 00:36:45', 0),
(5889176, 'makebelieve', 0, '$2y$10$x2bFpRsjpjRkQxUfmzE71uQMHmD..H7xDJW1.x/rEcUZ2j3Exj6Zi', 'member', '', 0, 0, NULL, NULL, '2023-08-11 05:30:16', 0),
(5991693, 'popslag959', 0, '$2y$10$TSfNCrlnfFd6lnktfsxp8uPvfcBgywyRoP6P/LdKN5.LcBz/s8sl2', 'member', 'todisappearr', 0, 0, NULL, NULL, '2023-07-01 22:34:53', 0),
(6264076, 'todisappear', 0, '$2y$10$epzD8FP6O9meq/OOTHm5kuk8ptUGMfbSTJKi1hICpY8O3pqF9ucfK', 'member', 'todisappear', 0, 0, NULL, NULL, '2023-08-01 18:43:26', 0),
(6353509, 'Sekret', 0, '$2y$10$WwJzRyM3K8MsywkuOmWOiOy7neqcdNqWn9qH10OyvPuAevNLzkT3e', 'member', 'saladcode', 0, 0, NULL, NULL, '2023-07-01 22:34:53', 0),
(7126761, 'Starrish', 0, '$2y$10$ZIizwWrFAP/cZatx2qFHLOMfx.4DV8szhB.Shnj1hG4aHGkhu0Xly', 'member', '@p', 0, 0, NULL, NULL, '2023-07-01 22:34:53', 0),
(7389378, 'testuser200', 0, '$2y$10$yVCwB7wV6bBDMCQG4hwtqe/nSJFJ97SaN4ckLCCKSotgQFSlmz/ZO', 'member', 'testuser200', 0, 0, NULL, NULL, '2023-07-01 22:34:53', 0),
(8156650, 'meep', 0, '$2y$10$xLC5eaJkK/684Y651X2iEerQ9dK4T0gIQBl5skj.00xHrBBlpxmNm', 'member', '', 0, 0, NULL, NULL, '2023-07-01 22:34:53', 0),
(8188580, 'test1234', 0, '$2y$10$W0z5773pQxv.q2FAjAim3O19cQ9Gsn3wMco3oh.Ifeo4K3XOW3fMW', 'member', '', 0, 0, NULL, NULL, '2023-07-01 22:34:53', 0),
(8198931, 'monke', 0, '$2y$10$cA4VQWC2NXxLOm7KCgyRW.BmeEHR8MJsn/mAQRnu.4AocfrpPVjg.', 'member', '', 0, 0, NULL, NULL, '2023-07-01 22:34:53', 0),
(8638510, 'Glasvezel', 0, '$2y$10$itcRHE/Mv1Rc/3QyL5/.Ru7zzAj4Lj8PPosNvQg.arCYPogmgoprW', 'member', 'Glassoe', 0, 0, NULL, NULL, '2023-07-01 22:34:53', 0),
(8839644, 'stevee', 0, '$2y$10$vyajbUuN/cvu65A1kTtdBuS731Xnze/2vMbSvJO434s75wGV9iSIi', 'member', 'Steve', 0, 0, NULL, NULL, '2023-07-13 19:58:42', 0),
(9024736, 'chops', 5000, '$2y$10$ynhq/Yiv2WVZyNd3TGqwiujtoTv8qwqKYnmaBLau9scU3KeA8vR5.', 'admin', 'strr7', 0, 17544556, '5351187058', NULL, '2023-10-09 00:00:53', 1),
(9385127, 'jobs', 0, '$2y$10$lQIAZnAc7TsWaUS7HoW10.0jQBDXI2w9HnGdO/WX66zNIkZMfYL62', 'member', '', 0, 0, NULL, NULL, '2023-09-19 22:33:07', 0),
(9561505, 'Fmoney21', 0, '$2y$10$qVxCXisSxQ7R9sod0ilidOPifahiDgct1UxE4f/KF2SrHHPnOJ.BC', 'member', 'Chs21er', 0, 0, NULL, NULL, '2023-10-02 22:00:10', 1),
(9927708, 'penguin', 0, '$2y$10$/H3nuIAqZ3i4iYtGgUUrYeOrmzqtYgRsLacE6R5vbiv7y.dwtT83q', 'member', '', 0, 0, NULL, NULL, '2023-07-01 22:34:53', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blueprints`
--
ALTER TABLE `blueprints`
  ADD PRIMARY KEY (`blueprint`);

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
  ADD KEY `blueprint` (`blueprint`),
  ADD KEY `productCreator` (`creator`);

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
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notificationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- Constraints for dumped tables
--

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
  ADD CONSTRAINT `blueprintLink` FOREIGN KEY (`blueprint`) REFERENCES `blueprints` (`blueprint`),
  ADD CONSTRAINT `productCreator` FOREIGN KEY (`creator`) REFERENCES `users` (`userId`);

--
-- Constraints for table `shortlink`
--
ALTER TABLE `shortlink`
  ADD CONSTRAINT `shortlink_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
