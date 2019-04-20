-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Creato il: Mar 29, 2019 alle 21:34
-- Versione del server: 5.7.25-0ubuntu0.16.04.2
-- Versione PHP: 7.0.33-0ubuntu0.16.04.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mms`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `accessorio`
--

CREATE TABLE `accessorio` (
  `id` bigint(20) NOT NULL,
  `name` varchar(63) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `type` set('servizio','accessorio') NOT NULL,
  `nAvailable` int(11) NOT NULL,
  `returnable` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `accessorio`
--

INSERT INTO `accessorio` (`id`, `name`, `price`, `type`, `nAvailable`, `returnable`) VALUES
(1, 'Audioguida', '17', 'accessorio', 16, 1),
(2, 'Guida Personale', '30', 'servizio', 14, 1),
(4, 'Opuscolo', '1', 'accessorio', 800, 0),
(5, 'Mattia Maglie', '1', 'accessorio', 1, 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `biglietto`
--

CREATE TABLE `biglietto` (
  `id` bigint(20) NOT NULL,
  `codUser` bigint(20) NOT NULL,
  `codCat` bigint(20) DEFAULT NULL,
  `codTimeSlot` bigint(20) NOT NULL,
  `datePurchase` datetime NOT NULL,
  `dateValidity` date NOT NULL,
  `totalPrice` decimal(10,2) NOT NULL,
  `validation` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `biglietto`
--

INSERT INTO `biglietto` (`id`, `codUser`, `codCat`, `codTimeSlot`, `datePurchase`, `dateValidity`, `totalPrice`, `validation`) VALUES
(1, 1, 1, 223, '2018-06-02 18:08:37', '2018-07-03', '15.00', '86b63c452b63d3930415dafe36458445'),
(2, 28, 1, 158, '2018-06-02 18:11:15', '2018-06-12', '25.00', '6c519689e6679620cea215c29b5526e4'),
(3, 25, 1, 223, '2018-06-02 18:23:08', '2018-07-03', '15.00', 'b5e6a7b21ef202957e1e8ecf479c2ec1'),
(4, 25, 1, 223, '2018-06-02 18:23:08', '2018-07-03', '15.00', 'ab735f8868d962be9101f7f8e2056242'),
(5, 25, 1, 223, '2018-06-02 18:23:08', '2018-07-03', '15.00', '0aee1445ba4c3bb895b07003a00ab1e1'),
(6, 25, 1, 238, '2018-06-04 21:29:21', '2018-06-20', '25.00', '9f8cd6a4dd455af29c79a0ff24fe2014'),
(7, 1, 1, 231, '2018-06-06 07:55:00', '2018-06-09', '15.00', '00a94d658cccfc09d81a9a3e21640e36'),
(8, 1, 2, 231, '2018-06-06 07:55:00', '2018-06-09', '9.00', '00d929f6eb04e503e81b505203a3dbc3'),
(9, 1, 1, 224, '2018-06-12 12:37:27', '2018-06-13', '15.00', '325a9c866fbed664d111e83937899397'),
(10, 26, 1, 160, '2018-06-13 14:52:06', '2018-06-21', '25.00', '21c21b63b1f0146fe11687d5bed85fc2'),
(11, 26, 1, 160, '2018-06-13 14:52:06', '2018-06-21', '25.00', '04be3ac886c17b9e39255abfa28e63b3'),
(12, 26, 4, 160, '2018-06-13 14:52:06', '2018-06-21', '13.00', '998a8592194183f6f1f66a7914501a6f'),
(13, 26, 4, 160, '2018-06-13 14:52:06', '2018-06-21', '13.00', '296699b79110fcb18af81129bd31edfd'),
(14, 1, 1, 233, '2018-06-16 10:58:45', '2018-06-17', '15.00', 'a4eae8b4a82589449503ba6c62eae4bb'),
(15, 1, 1, 233, '2018-06-16 10:58:45', '2018-06-17', '15.00', '7ea1b71b424e1980a1b29690e1fe1450'),
(16, 25, 23, 231, '2018-06-22 08:51:24', '2018-06-23', '11.00', 'd39ab97f7c1fb08c00811f115f83d67c'),
(17, 26, 1, 223, '2018-07-21 14:08:22', '2018-07-24', '15.00', 'b8e78a01d5d7ae2a6feed481242dd1c4'),
(18, 25, 2, 222, '2018-07-22 20:29:32', '2018-07-24', '9.00', '91e354c977e3b6da986bd3d34b70781a'),
(19, 25, 2, 223, '2018-07-22 20:30:46', '2018-07-24', '8.70', 'cfd184c981eab2a6d446bd906b9cb0ac'),
(20, 25, 2, 223, '2018-07-22 20:30:46', '2018-07-24', '8.70', 'd2737b1cebe2d56d6685989965f6a363'),
(21, 25, 2, 223, '2018-07-22 20:30:46', '2018-07-24', '8.70', '06a6e2fab8b71b2414b35fb32aefdcbf'),
(22, 242, 3, 233, '2018-07-28 17:24:55', '2018-07-29', '10.50', '1092f9505c75fc2e068f40dc3d9b9133'),
(23, 25, 1, 250, '2018-09-08 22:27:00', '2018-09-10', '10.00', 'c31b2679fd675437202fb0bb9cff5586'),
(24, 25, 1, 223, '2018-11-05 14:59:33', '2018-11-06', '16.00', '30b5d835a1eee6417eb06bd2978b7325'),
(25, 25, 1, 223, '2018-11-05 14:59:33', '2018-11-06', '16.00', '8d470ba2b30b2152d977b3b5e2f617a8'),
(26, 25, 1, 223, '2018-11-05 14:59:33', '2018-11-06', '16.00', 'a880b90c0b4df7d6289d1720241dd720'),
(27, 25, 2, 223, '2018-11-05 14:59:33', '2018-11-06', '9.28', '6c673eca499fcc4ab994695a197656df'),
(28, 25, 2, 223, '2018-11-05 14:59:33', '2018-11-06', '9.28', '13150d4b2cda555f26a5b0453052faa8'),
(29, 25, 2, 223, '2018-11-05 14:59:33', '2018-11-06', '9.28', '120b92fdbdfbcd284215a4f08472ed77'),
(30, 25, 23, 223, '2018-11-05 14:59:33', '2018-11-06', '11.20', '0d53d5cef4aa4bc33dbd9b0390922444'),
(31, 25, 23, 223, '2018-11-05 14:59:33', '2018-11-06', '11.20', '1c6e7d3d4847801fe04460f5eb0df32a'),
(32, 25, 1, 257, '2018-11-24 19:48:59', '2018-11-25', '1428.00', 'f1ba4cb286408a4976f8a06d452abffa'),
(33, 25, 1, 260, '2019-01-29 14:49:03', '2019-01-30', '2.00', 'f5ba53e0e9d5111b15d37c7bd20aef1a'),
(34, 25, 1, 260, '2019-01-29 14:49:03', '2019-01-30', '2.00', '7a0593495490c15b4f2eb501d17770e5'),
(35, 25, 1, 260, '2019-01-29 14:49:03', '2019-01-30', '2.00', '229d328f0c7f198874b06f924782eacb'),
(36, 25, 1, 260, '2019-01-29 14:49:03', '2019-01-30', '2.00', '0e09c4ef1d60d2699a1311d81f271c92'),
(37, 25, 1, 260, '2019-01-29 14:49:03', '2019-01-30', '2.00', 'dbe02097eb84c68c5f5100108dd77635'),
(38, 25, 1, 260, '2019-01-29 14:49:03', '2019-01-30', '2.00', '71448388e77f403115e41a760c9837fb'),
(39, 25, 1, 260, '2019-01-29 14:49:03', '2019-01-30', '2.00', '9a4e684a40ccea1d0ce624dc8fd86288'),
(40, 25, 1, 260, '2019-01-29 14:49:03', '2019-01-30', '2.00', 'b1d2aa6342f5fe767918a64870fe3d83'),
(41, 25, 1, 260, '2019-01-29 14:49:03', '2019-01-30', '2.00', 'f9b4436db24efa981ee45fde355d55cf'),
(42, 25, 1, 260, '2019-01-29 14:49:03', '2019-01-30', '2.00', '1300753a2ce51fd6629e13f95640be45'),
(43, 25, 1, 260, '2019-01-29 14:49:03', '2019-01-30', '2.00', '651267200d2a36619e0c94be93574f9c'),
(44, 25, 1, 260, '2019-01-29 14:49:03', '2019-01-30', '2.00', 'd8fe3451868a1b7e0f52d29da2f6ad24'),
(45, 245, 1, 257, '2019-02-08 12:42:27', '2019-02-10', '1428.00', '98b133b889e788efed20e22b60ba9c06'),
(46, 245, 1, 257, '2019-02-08 12:42:27', '2019-02-10', '1428.00', 'd0fe421b061636f14e66b66d24c53e40'),
(47, 245, 1, 257, '2019-02-08 12:42:27', '2019-02-10', '1428.00', 'fe7f0d1527568a53645facb980c36108'),
(48, 245, 1, 257, '2019-02-08 12:42:27', '2019-02-10', '1428.00', '7e655cdc49c094c7fd230b74c3df9cf9'),
(49, 245, 1, 257, '2019-02-08 12:42:27', '2019-02-10', '1428.00', '242b568aea84079b9057d331f1b7cd19'),
(50, 245, 1, 257, '2019-02-08 12:42:27', '2019-02-10', '1428.00', 'e9148e772bb94a05c8fc7bd6e60222b9'),
(51, 245, 1, 257, '2019-02-08 12:42:27', '2019-02-10', '1428.00', '5640fbad2de8c3346a198f51a5377bb3'),
(52, 245, 1, 257, '2019-02-08 12:42:27', '2019-02-10', '1428.00', 'da760a6ad6ceaab624bbdf79cfccdc55'),
(53, 245, 1, 257, '2019-02-08 12:42:27', '2019-02-10', '1428.00', '8ed49dc14456405759c52430cb91d245'),
(54, 245, 1, 257, '2019-02-08 12:42:27', '2019-02-10', '1428.00', '33575099ed1ab6c583d85180bd072ed0'),
(55, 25, 25, 257, '2019-03-16 18:50:02', '2019-04-28', '71.40', '6fa514c77c5b56e6ef59a18971a028b7');

-- --------------------------------------------------------

--
-- Struttura della tabella `bigliettoAccessorio`
--

CREATE TABLE `bigliettoAccessorio` (
  `codTicket` bigint(20) NOT NULL,
  `codAccessory` bigint(20) NOT NULL,
  `qta` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `bigliettoAccessorio`
--

INSERT INTO `bigliettoAccessorio` (`codTicket`, `codAccessory`, `qta`) VALUES
(2, 1, 1),
(3, 1, 1),
(6, 1, 1),
(6, 4, 1),
(7, 1, 1),
(9, 2, 1),
(10, 1, 4),
(10, 4, 4),
(16, 1, 1),
(17, 1, 1),
(22, 1, 1),
(22, 2, 1),
(22, 4, 1),
(24, 1, 1),
(24, 2, 2),
(24, 4, 1),
(32, 2, 12),
(33, 1, 1),
(33, 5, 1),
(55, 1, 1),
(55, 2, 1),
(55, 4, 1),
(55, 5, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `categoria`
--

CREATE TABLE `categoria` (
  `id` bigint(20) NOT NULL,
  `name` varchar(63) NOT NULL,
  `discount` decimal(10,0) NOT NULL,
  `docType` varchar(255) NOT NULL,
  `priority` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `categoria`
--

INSERT INTO `categoria` (`id`, `name`, `discount`, `docType`, `priority`) VALUES
(1, 'Intero', '0', '', 0),
(2, 'Ridotto (fino ai 12 anni)', '42', 'Carta Identità', 0),
(3, 'Disabile', '30', 'Carta Bianca', 0),
(4, 'Studente', '50', 'Carta Identità', 0),
(7, 'Militari', '15', 'Tesserino', 0),
(23, 'Anziani (over 65)', '30', 'Carta Identità', 0),
(24, 'fddffd', '100', 'Carta Bianca', -1),
(25, 'Nullafacenti', '95', 'Tessera del Reddito di cittadinanza', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `evento`
--

CREATE TABLE `evento` (
  `id` bigint(20) NOT NULL,
  `name` varchar(63) NOT NULL,
  `description` text NOT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `price` decimal(10,0) NOT NULL,
  `maxSeats` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `evento`
--

INSERT INTO `evento` (`id`, `name`, `description`, `startDate`, `endDate`, `price`, `maxSeats`) VALUES
(0, 'Visita', 'Vieni a visitare il nostro bellissimo museo nella sua completezza. Costa poco è c\'è una gelateria vicino.', NULL, NULL, '16', 350),
(1, 'Mostra Egizia', 'Reperti unici dai più grandi musei italiani e internazionali, ricostruzioni scenografiche e fedeli riproduzioni in scala 1:1, multimedialità interattiva e immersiva.', '2018-04-29', '2018-06-05', '20', 55),
(2, 'Mostra Etrusca', 'Al centro del progetto espositivo figurano i risultati degli scavi nel Campo della Fiera di Orvieto, condotti a partire dal 2000 sotto la direzione della Prof. Simonetta Stopponi, che hanno  portato alla luce uno straordinario complesso archeologico su una superficie di cinque ettari, con diversi templi, una Via Sacra e un grande spazio riservato alle offerte votive.\r\n\r\nAttraverso le strutture archeologiche e i 1200 reperti esposti (statuette, terracotta, rilievi, ceramiche..) il visitatore può immergersi nell’universo culturale, religioso ed estetico degli Etruschi.', '2018-05-07', '2018-06-27', '25', 500),
(41, 'Prova', 'Esempio', '2018-05-18', '2018-05-18', '-1', 0),
(42, 'Italia s\\\'è desta!', 'Vieni a scoprire la storia del nostro fantastico paese! Dai romani alla Repubblica', '2018-06-04', '2018-06-20', '25', 200),
(43, 'Space', 'fun stuff going on here', '2018-09-08', '2018-09-30', '10', 100),
(44, 'ExpoScuola', 'VENITE SUBITO CHE CI GUADAGNAMO, vedrete il  cambio d\'ora', '1890-11-11', '2271-11-24', '1428', 8053),
(45, 'ExpoScuola', 'VENITE SUBITO CHE CI GUADAGNAMO, vedrete il  cambio d\'ora', '1890-11-11', '2271-11-24', '1428', 8053),
(46, 'ExpoScuola', 'VENITE SUBITO CHE CI GUADAGNAMO, vedrete il  cambio d\'ora', '1890-11-11', '2271-11-24', '1428', 8053),
(47, 'Test', 'Mattia Maglie che fa Analisi Matematica 1', '2019-01-21', '2019-02-14', '2', 131);

-- --------------------------------------------------------

--
-- Struttura della tabella `fasciaoraria`
--

CREATE TABLE `fasciaoraria` (
  `id` bigint(20) NOT NULL,
  `codEvent` bigint(20) NOT NULL,
  `startHour` time NOT NULL,
  `minutes` int(11) NOT NULL,
  `day` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `fasciaoraria`
--

INSERT INTO `fasciaoraria` (`id`, `codEvent`, `startHour`, `minutes`, `day`) VALUES
(80, 1, '07:50:00', 340, 7),
(81, 1, '15:05:00', 265, 7),
(84, 1, '07:50:00', 340, 2),
(85, 1, '15:05:00', 265, 2),
(86, 1, '07:50:00', 340, 3),
(87, 1, '15:05:00', 265, 3),
(88, 1, '07:50:00', 340, 4),
(89, 1, '15:05:00', 265, 4),
(90, 1, '07:50:00', 340, 5),
(91, 1, '15:05:00', 265, 5),
(92, 1, '07:50:00', 340, 6),
(93, 1, '15:05:00', 265, 6),
(143, 41, '05:55:00', 455, 1),
(144, 41, '15:25:00', 265, 1),
(145, 41, '05:55:00', 455, 2),
(146, 41, '15:25:00', 265, 2),
(147, 41, '05:55:00', 455, 3),
(148, 41, '15:25:00', 265, 3),
(149, 41, '05:55:00', 455, 4),
(150, 41, '15:25:00', 265, 4),
(151, 41, '05:55:00', 455, 5),
(152, 41, '15:25:00', 265, 5),
(153, 41, '05:55:00', 455, 6),
(154, 41, '15:25:00', 265, 6),
(155, 41, '05:55:00', 455, 7),
(156, 41, '15:25:00', 265, 7),
(157, 2, '08:40:00', 510, 1),
(158, 2, '08:40:00', 510, 2),
(159, 2, '08:40:00', 10, 3),
(160, 2, '08:40:00', 510, 4),
(161, 2, '08:40:00', 510, 5),
(162, 2, '08:40:00', 510, 6),
(163, 2, '08:40:00', 5, 7),
(222, 0, '06:00:00', 390, 2),
(223, 0, '13:50:00', 370, 2),
(224, 0, '06:05:00', 300, 3),
(225, 0, '12:35:00', 370, 3),
(226, 0, '08:00:00', 300, 4),
(228, 0, '08:00:00', 300, 5),
(229, 0, '13:50:00', 370, 5),
(230, 0, '08:00:00', 300, 6),
(231, 0, '13:50:00', 370, 6),
(232, 0, '08:00:00', 300, 7),
(233, 0, '13:50:00', 370, 7),
(234, 42, '07:30:00', 540, 0),
(235, 42, '17:30:00', 240, 0),
(236, 42, '07:30:00', 540, 0),
(237, 42, '17:30:00', 240, 0),
(238, 42, '07:30:00', 0, 3),
(240, 42, '07:30:00', 540, 0),
(241, 42, '17:30:00', 240, 0),
(242, 42, '07:30:00', 540, 0),
(243, 42, '17:30:00', 240, 0),
(244, 42, '07:30:00', 540, 0),
(245, 42, '17:30:00', 240, 0),
(246, 42, '07:30:00', 540, 0),
(247, 42, '17:30:00', 240, 0),
(248, 0, '21:30:00', 149, 3),
(249, 0, '21:30:00', 149, 3),
(250, 43, '08:00:00', 720, 1),
(251, 43, '08:00:00', 720, 2),
(252, 43, '08:00:00', 720, 3),
(253, 43, '08:00:00', 720, 4),
(254, 43, '08:00:00', 720, 5),
(255, 43, '08:00:00', 720, 6),
(256, 43, '08:00:00', 0, 7),
(257, 44, '23:55:00', 4, 7),
(258, 45, '23:55:00', 4, 7),
(259, 46, '23:55:00', 4, 7),
(260, 47, '04:45:00', 430, 3);

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `id` bigint(20) NOT NULL,
  `name` varchar(65) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `role` int(2) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`id`, `name`, `mail`, `pass`, `role`) VALUES
(1, 'Matty98', 'novembre1998@live.it', '$2y$10$YgW/w.GeWRKyiRU3hVmZMOq5vdLvoAXrs67i1hiUKa9lvyHKzCzLq', 2),
(25, 'admin', 'admin@musetek.tk', '$2y$10$umaz8VuR5IRcN3flHp8mLuf1Tg/3l2SS6vjhmwugQAB8kr/FFMdkG', 2),
(26, 'lorenzo', 'lorenzo.trovato3@live.com', '$2y$10$9nkDAfX4AWEognb8xYKY3OQSUD/XQ7pEmiJyT03AooRJbfudROVIW', 2),
(28, 'andreasegala', 'andrea.segala9@gmail.com', '$2y$10$1xi80PP7qZF9uCyHvN/hgOFMnjgs4UsYPoo1vxdai/jlxJQY6ib5i', 2),
(30, 'Zank98', 'andrea.chierchia98@gmail.com', '$2y$10$HafiQjex51opAICTUY9sxehpceV.YLjn.ayOdK6sh/txwmPc7L5j2', 2),
(38, 'bryce20', 'nwiza@example.org', '287093931b1a3fae1b95ed6405a4488c346ca551', 1),
(39, 'hailee39', 'gerlach.catalina@example.com', '78615cb00b5c752e574b870a8dd9d86b94c8a8be', 1),
(40, 'herta90', 'tward@example.net', 'ed0dd31c6167d0db52fa0f559a9a28685db664cf', 1),
(41, 'mjacobson', 'reilly.alvina@example.org', '2d51d31804b842c5645756406e643b3dd0175e51', 1),
(42, 'oadams', 'wunsch.dean@example.com', '663b9e723862680de5c714c344782c5bd4387108', 1),
(43, 'muriel.wiza', 'lafayette.pfeffer@example.com', '649a31c77262c14a830abcc7bb79a790c55576b7', 1),
(44, 'greenfelder.nicole', 'earl54@example.org', 'bd6beff54c4c1106cec98317f0927859545393e1', 1),
(45, 'albert44', 'beffertz@example.org', 'b8e4e444e25b285b7a68b50422c7002d9d5a3017', 1),
(46, 'loma36', 'emard.deontae@example.net', 'ac0a22d3013798a366fa109459638a88244d3ba0', 1),
(47, 'jaylon05', 'feil.vincent@example.com', '36839a9a3a2950d20e102b552594dd7088eccb7a', 1),
(48, 'esawayn', 'melisa.russel@example.com', 'bc007a5ed80e897d661395730a915554147dd3a8', 1),
(49, 'brain.o\'keefe', 'daija.halvorson@example.org', '46f2322eef8830a973599900931fb298662de31e', 1),
(50, 'hkuphal', 'herta89@example.com', 'd6835745695727b2432a1d37f04a18f1abfad455', 1),
(51, 'dare.monroe', 'zbuckridge@example.net', '4bd9bebe5e57b19d13a953b937dd1441e5b7ce34', 1),
(52, 'seth.wisoky', 'vgutkowski@example.com', 'fa5b6cbddb52f3b7f12d12697b156e490a6afd67', 1),
(53, 'jrunte', 'caden.senger@example.org', 'affaa7827428d2a593122ddda1f547cd7ec01e40', 1),
(54, 'xmoore', 'labadie.adolph@example.net', 'e73dd71a4ceb1701e71b0e372ad3a443ed07e606', 1),
(55, 'waelchi.sam', 'brakus.ursula@example.net', '6da6bceabdd8d54e367bc1f65b20ee880ba63253', 1),
(56, 'drogahn', 'bernie.huel@example.org', '8fb5f66f9bf06d0566f7f5f4116d148f3e494be2', 1),
(57, 'fturner', 'penelope54@example.net', 'c4cdf296a921d8d98a7be9b6fa230fba06426134', 1),
(158, 'shirley.stiedemann', 'lemke.juwan@example.net', 'acedc04df68d12a9039417ead546721cf069eeec', 1),
(159, 'cruickshank.keara', 'kernser@example.com', 'de95f9b6126de7f3da4b8faf483986c80d4ad404', 1),
(160, 'fidel.hane', 'elinore89@example.net', 'b28d0026d4f01da10aa0588e10f20e36f03b102c', 1),
(161, 'sally30', 'wrobel@example.org', 'ee05286a56219f792080b37a84314573fc3d8ee7', 1),
(162, 'janick.rice', 'willy88@example.org', 'f7ba8648f7104c4588833555a4f36f840d721a61', 1),
(163, 'crist.lionel', 'hane.colt@example.com', 'ad93c97222b6e9bd56c5b75594f34977f90c4ef0', 1),
(164, 'tkassulke', 'mccullough.ryleigh@example.org', '62e1ad3e1f2fe977ecc7f2399ea903ec3104748f', 1),
(165, 'stewart.mante', 'vschulist@example.com', '68ecfc5e1b97b54b73a6aa92d6487b1bf46ec782', 1),
(166, 'jessy42', 'wisoky.milo@example.com', 'fb189455d3d34629fa43f92eb36d82b186523622', 1),
(167, 'cathy52', 'linda78@example.com', '88a1d6772b3d7c998442c6d88583140c416c9f89', 1),
(168, 'bahringer.diego', 'kadin03@example.org', '3e17697ddecaa9e3cd5b45815c78fffea1a175e2', 1),
(169, 'dion36', 'brendon.kilback@example.org', 'de5af0aec1684f706cedb590f53a753995ab3029', 1),
(170, 'dlockman', 'ricardo56@example.org', 'eb7e5b13161d722aab6879545cd1d1a23450f7af', 1),
(171, 'yconnelly', 'pink14@example.net', '25a009d3bcf20012f24ce018ce3b78489b38ebc5', 1),
(172, 'tatum41', 'erling57@example.org', 'c3ce34489273d44b77d0fbc204d7bddf156b9d4f', 1),
(173, 'rogelio73', 'jsporer@example.com', '0ffcf184bd7a66996d5a834c3229ab336c29e3a4', 1),
(174, 'lisa.streich', 'icummings@example.com', '9401d9a0ca0ef4ae8a5fdd58c8b73f7bc331f5c4', 1),
(175, 'ena59', 'pconsidine@example.net', '1214a194a9f8f72ceaad0293d265d6c9d110f005', 1),
(176, 'earline.wehner', 'samanta.effertz@example.org', 'ba8bf173c3c4896679dc395c518e4cd7e86e733f', 1),
(177, 'halvorson.myles', 'felton.walker@example.com', '127e1a311ade0f15f90b50593adcc46a11fd6aca', 1),
(178, 'jenkins.betsy', 'casey61@example.net', '91893e9702a1229f35299343b20ab9f7b8bb38c8', 1),
(179, 'johnathan25', 'wisoky.emie@example.org', '934aeaf1e72d3e74253bbe5f9c9069ceedfe4d33', 1),
(180, 'fschroeder', 'wisoky.kenyon@example.org', '80c7400c755815bf96352662cf3cc7d1e3f0c823', 1),
(181, 'kkunze', 'rkassulke@example.com', '4e071f8f786eec2136c1670c0fefa301d710d182', 1),
(182, 'dayne.champlin', 'halie44@example.net', '81447e842df406ab204825fba2f41463a524cb2b', 1),
(183, 'andre.kris', 'lyla56@example.net', 'a20285a012c92b73826c800dc1b9c9d702e05db6', 1),
(184, 'margie.anderson', 'nolan.donnelly@example.com', '147bf333aa08f331ccfe26dd2abc29d6d8f731e9', 1),
(185, 'heber.sipes', 'uhahn@example.com', 'c01c52bcb7c909ad0f5d89820ca2613064fb7ad9', 1),
(186, 'nrau', 'tania72@example.com', 'cc990ef7d5da0dd1584ee0e1eb1b524f73e6087f', 1),
(187, 'jed.hamill', 'melissa.mills@example.net', '9ec7518b5dcb92615f33be4abd81861a77ff61c9', 1),
(188, 'nona42', 'xkub@example.org', 'aa167b2036317faa865927ae40a35e3c27337c9b', 1),
(189, 'colton78', 'angelita19@example.com', 'df91113632659eb286f0755707adf1e1a7ec1a20', 1),
(190, 'beverly43', 'lavonne11@example.org', 'df7511813b80bc656fce668428bf55d4b67d2739', 1),
(191, 'wbauch', 'ashlynn.hettinger@example.org', 'be6df6b31f7d5d5208081016c433e953b5a1a58f', 1),
(192, 'felicia28', 'sjacobs@example.org', 'd9d0c2576efe65bf00a8449c4e818f2791813310', 1),
(193, 'runolfsson.berenice', 'rrogahn@example.com', 'ea52dcad586997fad30c70382a4dde6226640aa6', 1),
(194, 'rraynor', 'genevieve.stark@example.com', '40e55aa666a1a2a136664591359a5d54c1512f55', 1),
(195, 'federico.roberts', 'casper42@example.com', '91d687207614f7b9011ff3cc4356fb34a26616a8', 1),
(196, 'mellie.raynor', 'ritchie.tracy@example.net', '40212914ee993da70a23a096fd1227641850adb7', 1),
(197, 'hettinger.jaylan', 'gusikowski.freida@example.com', 'ed3a8d635322b498fe573a65086763a8cd5119a0', 1),
(198, 'reilly.yasmine', 'cindy58@example.net', 'f04fa80c1902004c7d44c36af38f285e2b7dae0a', 1),
(199, 'dooley.kennedy', 'morissette.madalyn@example.org', '911376fcffbe1806ce58759a5ec9b1ae51f7c476', 1),
(200, 'blick.calista', 'gspencer@example.com', '421feb383c472b91d6ee156ec91357d19f55cc19', 1),
(201, 'cward', 'fiona.o\'reilly@example.net', '0f3fcc0ec49a61aa885811602d6162dd2dab7c00', 1),
(202, 'aliyah18', 'schneider.barry@example.org', '5fa0f69225352beca876531d300641ba66380b6a', 1),
(203, 'carol.upton', 'uschulist@example.com', 'efabcda16b56034cc8fc7ae202d2232d050cb075', 1),
(204, 'jerod83', 'nikolaus.felipe@example.net', 'dd0ab54e7b8c7fe2175470fb96c5ae9e66c89785', 1),
(205, 'vrowe', 'kaleigh96@example.com', '574daf3873901547748d5775c59b82384c8e2026', 1),
(206, 'garrick91', 'jose.ernser@example.org', '59d2468c1e978576c3fb2c947e85bc9793ffbd47', 1),
(207, 'kswift', 'jazmyn46@example.org', 'bf83293ced4e82314be0c6f29cd20525145e052d', 1),
(229, 'matte234', 'm@tte.o', '$2y$10$/jKm8jkEUg/53U/O/E6c0.whoYYJMfqsItAoN6ixdS2CRkiywn09u', 1),
(230, 'pippop', 'pip@pip.it', '$2y$10$D7YiVAshuGk92JHb4PySgOGgj.aPnXLc7iXmRIL4O7MxRs.wABsR.', 0),
(231, 'pluto', 'prof.severi.test@gmail.com', '$2y$10$H3mdAinjM2VYsm4YO6VUlOveOtDYrLJIr6xbYNA4nlvq/C24kEwT2', 1),
(234, 'dasdasdas', 'dasdas@asdas.it', '$2y$10$SS0vHRH6rUY3iEs2ERVaSOMb8R63o3RQ7BN0HncGEZmp9/uLG.F.q', 0),
(241, 'Mattiam2', 'novembre1998@libero.it', '$2y$10$WIsSa8afcxXJPAAyiY53ZODI20DoSE0DaN3coRyz1KS0/jjonof66', 1),
(242, 'andre', 'andrea.segalax@gmail.com', '$2y$10$tHZJHmWntQqinmGVsO5DmuGZ8FRq7wDaALBbm/LW.r1TEWb.ikr/C', 2),
(243, 'Xxx', 'hbo@lol.it', '$2y$10$Lykwq.0YeMRiRS/MFJUmWunKXrD19QI4AqZFkAfUDkvSiOPKcx39y', 0),
(244, 'Lll', 'kwyit@hi2.in', '$2y$10$rAU0x3NzazeUBWeGEgsi1.lKdx2HtVtSqAqNiiKNUAuUmXQufYC8K', 0),
(245, 'H', 'Black_Merovingio@n8.gs', '$2y$10$FguQfgbZDpdBAk/vzbdNUObTvhCYZNqNqjiqWFxjzhYY1P/c/VrKu', 1);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `accessorio`
--
ALTER TABLE `accessorio`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `biglietto`
--
ALTER TABLE `biglietto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `validation` (`validation`),
  ADD KEY `codUser` (`codUser`),
  ADD KEY `codCat` (`codCat`),
  ADD KEY `codTimeSlot` (`codTimeSlot`);

--
-- Indici per le tabelle `bigliettoAccessorio`
--
ALTER TABLE `bigliettoAccessorio`
  ADD PRIMARY KEY (`codTicket`,`codAccessory`),
  ADD KEY `codAccessory` (`codAccessory`);

--
-- Indici per le tabelle `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `evento`
--
ALTER TABLE `evento`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `fasciaoraria`
--
ALTER TABLE `fasciaoraria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fasciaoraria_ibfk_1` (`codEvent`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `mail` (`mail`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `accessorio`
--
ALTER TABLE `accessorio`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT per la tabella `biglietto`
--
ALTER TABLE `biglietto`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;
--
-- AUTO_INCREMENT per la tabella `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT per la tabella `evento`
--
ALTER TABLE `evento`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT per la tabella `fasciaoraria`
--
ALTER TABLE `fasciaoraria`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=261;
--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=246;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `biglietto`
--
ALTER TABLE `biglietto`
  ADD CONSTRAINT `biglietto_ibfk_1` FOREIGN KEY (`codUser`) REFERENCES `utente` (`id`),
  ADD CONSTRAINT `biglietto_ibfk_2` FOREIGN KEY (`codCat`) REFERENCES `categoria` (`id`),
  ADD CONSTRAINT `biglietto_ibfk_3` FOREIGN KEY (`codTimeSlot`) REFERENCES `fasciaoraria` (`id`);

--
-- Limiti per la tabella `bigliettoAccessorio`
--
ALTER TABLE `bigliettoAccessorio`
  ADD CONSTRAINT `bigliettoAccessorio_ibfk_1` FOREIGN KEY (`codTicket`) REFERENCES `biglietto` (`id`),
  ADD CONSTRAINT `bigliettoAccessorio_ibfk_2` FOREIGN KEY (`codAccessory`) REFERENCES `accessorio` (`id`);

--
-- Limiti per la tabella `fasciaoraria`
--
ALTER TABLE `fasciaoraria`
  ADD CONSTRAINT `fasciaoraria_ibfk_1` FOREIGN KEY (`codEvent`) REFERENCES `evento` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
