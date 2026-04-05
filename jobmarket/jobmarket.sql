-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1:3306
-- Vytvořeno: Sob 04. dub 2026, 22:26
-- Verze serveru: 8.0.31
-- Verze PHP: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `jobmarket`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `event`
--

DROP TABLE IF EXISTS `event`;
CREATE TABLE IF NOT EXISTS `event` (
  `id_event` int NOT NULL AUTO_INCREMENT,
  `id_job` int DEFAULT NULL,
  `name_job` varchar(50) DEFAULT NULL,
  `price_hour` int DEFAULT NULL,
  `begin_event` datetime DEFAULT NULL,
  `hours` int DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `id_worker` int DEFAULT NULL,
  `workername` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `birth_number` varchar(11) DEFAULT NULL,
  `hpp` tinyint DEFAULT NULL,
  `tax_event` decimal(5,2) DEFAULT NULL,
  `start_event` datetime DEFAULT NULL,
  `stop_event` datetime DEFAULT NULL,
  `control` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `comment_job` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `comment_leader` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `comment_event` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `comment_control` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `leadername` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`id_event`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Vypisuji data pro tabulku `event`
--

INSERT INTO `event` (`id_event`, `id_job`, `name_job`, `price_hour`, `begin_event`, `hours`, `salary`, `id_worker`, `workername`, `birth_number`, `hpp`, `tax_event`, `start_event`, `stop_event`, `control`, `comment_job`, `comment_leader`, `comment_event`, `comment_control`, `leadername`) VALUES
(1, 1, 'úklid skladu', 200, '2026-02-02 08:00:00', 8, '1600.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Empty', 'Sklad je v Praze 14 Nová 66 tel. 564555231', 'Ranní úklid po řemeslnících', NULL, NULL, NULL),
(2, 2, 'kontola lístků', 200, '2026-03-02 18:00:00', 6, '1200.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Empty', 'kontrola vstupenek v O2 aréněPraha 9 Vysočany tel. 897222147', 'Koncert skupiny Kabát', NULL, NULL, NULL),
(3, 3, 'uklízení odpadků', 170, '2026-03-03 05:00:00', 6, '1020.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Storno', 'uklízení odpadků po akcích, Václavské náměstí tel. 978231999', 'Ranní úklid po akci', NULL, NULL, NULL),
(4, 7, 'rozvoz letáků', 150, '2026-03-03 08:00:00', 4, '600.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Empty', 'rozvoz reklamních plakátů, Praha-Holešovice, tel.604123789', 'Rozvoz plakátů před veletržní akcí', NULL, NULL, NULL),
(5, 10, 'rozdávání letáků', 170, '2026-03-04 16:00:00', 6, '1020.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Empty', 'rozdávání propagačních letáků, Výstaviště Letňany tel. 60856132', 'Rozdávání letáků před výstavištěm', NULL, NULL, NULL),
(6, 12, 'počítání aut', 180, '2026-03-02 08:00:00', 6, '1080.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Storno', 'flexibilní brigáda-zjišťování hustoty provozu, Praha tel.777562145', 'Počítání aut na Pražské magistrále', NULL, NULL, NULL),
(7, 13, 'práce na pokladně', 200, '2026-03-12 08:00:00', 8, '1600.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Empty', 'brigáda, pokladní v hypermarketu, Kolín tel. 601888999', 'Práce v Tescu', NULL, NULL, NULL),
(8, 14, 'počítání, inventura', 180, '2026-03-13 20:00:00', 8, '1440.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Empty', 'inventurní výpomoc v textilním skladu tel.777582692', 'Modletice, nutná fyzická zdatnost', NULL, NULL, NULL),
(9, 15, 'vykládka zboží', 200, '2026-03-16 14:00:00', 6, '1200.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Storno', 'vykládka zboží v logistickém centru Říčany tel.666999444', 'Nutná fyzická zdatnost', NULL, NULL, NULL),
(10, 16, 'roznáška balíků', 190, '2026-03-19 08:00:00', 6, '1140.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Empty', 'rozvážení balíků autem, Kutná hora tel 777589321', 'Nutný řidičský průkaz skupiny C', NULL, NULL, NULL),
(11, 17, 'rozvážka obědů', 170, '2026-03-20 08:00:00', 6, '1020.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Empty', 'Rozvážka obědů pro důchodce Lysá nad Labem tel.777823147', 'Nutný řidičský průkaz skupiny B', NULL, NULL, NULL),
(12, 18, 'výpomoc v zahradnictví', 160, '2026-03-21 06:00:00', 8, '1280.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Storno', 'výpomoc s likvidací bioodpadu Mělník tel.777999555', 'Nutná fyzická zdatnost', NULL, NULL, NULL),
(13, 19, 'vyklízení nebytových prostor', 200, '2026-03-21 08:00:00', 8, '1600.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Empty', 'pomoc s likvidací věcí z průmyslové zóny Kladno tel.777982666', 'Převážně likvidace starého nábytku', NULL, NULL, NULL),
(14, 20, 'marketingový průzkum', 180, '2026-03-22 08:00:00', 8, '1440.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Empty', 'výpomoc se zpracováním údajů z marketingového průzkumu, práce je on-lline, tel.601234589', 'Nutný vlastní počítač a internetové připojení', NULL, NULL, NULL),
(15, 21, 'vykládka zavazadel', 200, '2026-03-22 18:00:00', 8, '1600.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Storno', 'Výpomoc s vykládkou zavazadel na letišti Praha-Ruzyně tel.777564147', 'Je potřeba být fyzicky zdatný', NULL, NULL, NULL),
(16, 22, 'ranní roznáška novin', 170, '2026-03-24 04:00:00', 6, '1020.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Empty', 'ranní roznaška denního tisku Poděbrady tel. 604123456', 'Je třeba mít vlasní auto', NULL, NULL, NULL),
(17, 23, 'kontrola lístků', 180, '2026-03-24 18:00:00', 4, '720.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Empty', 'kontrola lístků na Výsvavišti Praha-Letňany tel. 777888999', 'Koncert skupiny Visací zámek', NULL, NULL, NULL),
(18, 24, 'kontrola lístků', 160, '2026-03-25 08:00:00', 6, '960.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Storno', 'kontrola lístků na Výstavišti Lysá nad Labem tel.777999222', 'Výstava akce Retro garáž', NULL, NULL, NULL),
(19, 25, 'prodej zmrzliny', 160, '2026-03-28 16:00:00', 4, '640.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Empty', 'stánkový prodej zmrzliny na koupališti Mělník te. 605123789', 'Soukromá narozeninová  akce', NULL, NULL, NULL),
(20, 26, 'stánkový prodej', 200, '2026-03-28 08:00:00', 8, '1600.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Empty', 'prodej suvenýrů Praha, Václavské náměstí tel. 777555222', 'Výpomoc ve stánku', NULL, NULL, NULL),
(21, 27, 'stánkový prodej', 160, '2026-03-30 08:00:00', 8, '1280.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Storno', 'stánkový prodej v rychlém občerstvení Praha, Staroměstské náměstí tel.606123478', 'Nutný zdravotní průkaz', NULL, NULL, NULL),
(22, 3, 'uklízení odpadků', 170, '2026-01-01 04:00:00', 8, '1360.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Empty', 'uklízení odpadků po akcích, Václavské náměstí tel. 978231999', 'Uklízení odpadků po Silvestru', NULL, NULL, NULL),
(23, 3, 'uklízení odpadků', 170, '2026-02-01 04:00:00', 6, '1020.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ready', 'uklízení odpadků po akcích, Václavské náměstí tel. 978231999', 'likvidace vánoční výzdoby', NULL, NULL, NULL),
(24, 1, 'úklid skladu', 200, '2026-05-05 08:00:00', 8, '1600.00', 6, 'worker6', '9503030147', 1, '15.00', NULL, NULL, 'Ready', 'Sklad je v Praze 14 Nová 66 tel. 564555231', 'Je potřeba přijít včas', NULL, NULL, NULL),
(25, 2, 'kontola lístků', 200, '2026-05-06 18:00:00', 6, '1200.00', 6, 'worker6', '9503030147', 1, '15.00', NULL, NULL, 'Ready', 'kontrola vstupenek v O2 aréněPraha 9 Vysočany tel. 897222147', 'Koncert skupiny Buty', NULL, NULL, NULL),
(26, 3, 'uklízení odpadků', 170, '2026-05-07 05:00:00', 6, '1020.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ready', 'uklízení odpadků po akcích, Václavské náměstí tel. 978231999', 'Úklid po demonstraci', NULL, NULL, NULL),
(27, 7, 'rozvoz letáků', 150, '2026-05-08 08:00:00', 4, '600.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ready', 'rozvoz reklamních plakátů, Praha-Holešovice, tel.604123789', 'Rozvoz volebních plakátů', NULL, NULL, NULL),
(28, 10, 'rozdávání letáků', 170, '2026-05-09 18:00:00', 6, '1020.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ready', 'rozdávání propagačních letáků, Výstaviště Letňany tel. 60856132', 'Rozdávání propagačních předmětů', NULL, NULL, NULL),
(29, 12, 'počítání aut', 180, '2026-06-05 05:00:00', 8, '1440.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ready', 'flexibilní brigáda-zjišťování hustoty provozu, Praha tel.777562145', 'Práce pro statistický úřad', NULL, NULL, NULL),
(30, 13, 'práce na pokladně', 200, '2026-06-06 08:00:00', 8, '1600.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ready', 'brigáda, pokladní v hypermarketu, Kolín tel. 601888999', 'Brigáda v Tescu', NULL, NULL, NULL),
(31, 14, 'počítání, inventura', 180, '2026-06-07 08:00:00', 8, '1440.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ready', 'inventurní výpomoc v textilním skladu tel.777582692', 'Sklad v Modleticích , nutná fyzická zdatnost', NULL, NULL, NULL),
(32, 16, 'roznáška balíků', 190, '2026-06-08 08:00:00', 8, '1520.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ready', 'rozvážení balíků autem, Kutná hora tel 777589321', 'Nutný řidičský průkaz skupiny C', NULL, NULL, NULL),
(33, 17, 'rozvážka obědů', 170, '2026-06-09 08:00:00', 6, '1020.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ready', 'Rozvážka obědů pro důchodce Lysá nad Labem tel.777823147', 'Nutný zdravotní průkaz', NULL, NULL, NULL),
(34, 18, 'výpomoc v zahradnictví', 160, '2026-04-05 08:00:00', 8, '1280.00', 4, 'worker4', '6812030189', 1, '15.00', NULL, NULL, 'Ready', 'výpomoc s likvidací bioodpadu Mělník tel.777999555', 'Nutná fyzická zdatnost', NULL, NULL, NULL),
(35, 19, 'vyklízení nebytových prostor', 200, '2026-04-06 06:00:00', 8, '1600.00', 4, 'worker4', '6812030189', 1, '15.00', NULL, NULL, 'Ready', 'pomoc s likvidací věcí z průmyslové zóny Kladno tel.777982666', 'Nutná fyzická zdatnost', NULL, NULL, NULL),
(36, 20, 'marketingový průzkum', 180, '2026-04-07 08:00:00', 8, '1440.00', 5, 'worker5', '9112060158', 1, '15.00', '2026-04-05 00:17:12', NULL, 'Ready', 'výpomoc se zpracováním údajů z marketingového průzkumu, práce je on-lline, tel.601234589', 'Nutný vlastní počítač a internetové připojení', NULL, NULL, NULL),
(37, 21, 'vykládka zavazadel', 200, '2026-04-08 06:00:00', 8, '1600.00', 5, 'worker5', '9112060158', 1, '15.00', NULL, NULL, 'Ready', 'Výpomoc s vykládkou zavazadel na letišti Praha-Ruzyně tel.777564147', 'Fyzická zdatnost, profesní řidičský průkaz', NULL, NULL, NULL),
(38, 22, 'ranní roznáška novin', 170, '2026-04-10 04:00:00', 5, '850.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ready', 'ranní roznaška denního tisku Poděbrady tel. 604123456', 'Nutný včasný příchod', NULL, NULL, NULL),
(39, 27, 'stánkový prodej', 160, '2026-04-07 08:00:00', 8, '1280.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ready', 'stánkový prodej v rychlém občerstvení Praha, Staroměstské náměstí tel.606123478', 'Nutný zdravotní průkaz', NULL, NULL, NULL),
(40, 26, 'stánkový prodej', 200, '2026-04-08 08:00:00', 8, '1600.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ready', 'prodej suvenýrů Praha, Václavské náměstí tel. 777555222', 'Výprodej velikonočního zboží', NULL, NULL, NULL),
(41, 25, 'prodej zmrzliny', 160, '2026-04-09 16:00:00', 4, '640.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ready', 'stánkový prodej zmrzliny na koupališti Mělník te. 605123789', 'Výpomoc na soukromé párty', NULL, NULL, NULL),
(42, 24, 'kontrola lístků', 160, '2026-04-06 06:00:00', 8, '1280.00', 8, 'worker8', '6112110185', 1, '15.00', '2026-04-04 23:31:29', '2026-04-04 23:44:17', 'Ok', 'kontrola lístků na Výstavišti Lysá nad Labem tel.777999222', 'Nutná fyzická zdatnost', NULL, 'Vše v pořádku', 'leader1'),
(43, 17, 'rozvážka obědů', 170, '0000-00-00 00:00:00', 6, '1020.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Ready', 'Rozvážka obědů pro důchodce Lysá nad Labem tel.777823147', 'Nutný zdravotní průkaz', NULL, NULL, NULL),
(44, 17, 'rozvážka obědů', 170, '2026-04-03 06:00:00', 6, '1020.00', 4, 'worker4', '6812030189', 1, '15.00', '2026-04-04 22:28:20', '2026-04-04 22:35:18', 'Ok', 'Rozvážka obědů pro důchodce Lysá nad Labem tel.777823147', 'Nutný zdravotní průkaz', 'Rozvážku jsem zařídil dodatečně', 'Vše v pořádku', 'leader1'),
(45, 18, 'výpomoc v zahradnictví', 160, '2026-04-04 08:00:00', 8, '1280.00', 7, 'worker7', '7508080158', 1, '15.00', '2026-04-04 23:07:08', '2026-04-04 23:28:53', 'False', 'výpomoc s likvidací bioodpadu Mělník tel.777999555', 'Nutná fyzická zdatnost', 'po dohodě vykonáno později', 'všechno špatně...musíš se polepšit', 'leader1'),
(46, 18, 'výpomoc v zahradnictví', 160, '2026-04-04 08:00:00', 8, '1280.00', 6, 'worker6', '9503030147', 1, '15.00', '2026-04-04 22:38:42', '2026-04-04 23:04:51', 'Ready', 'výpomoc s likvidací bioodpadu Mělník tel.777999555', 'Nutná fyzická zdatnost', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktura tabulky `job`
--

DROP TABLE IF EXISTS `job`;
CREATE TABLE IF NOT EXISTS `job` (
  `id_job` int NOT NULL AUTO_INCREMENT,
  `name_job` varchar(50) DEFAULT NULL,
  `price_hour` int DEFAULT NULL,
  `comment_job` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  PRIMARY KEY (`id_job`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Vypisuji data pro tabulku `job`
--

INSERT INTO `job` (`id_job`, `name_job`, `price_hour`, `comment_job`) VALUES
(1, 'úklid skladu', 200, 'Sklad je v Praze 14 Nová 66 tel. 564555231'),
(2, 'kontola lístků', 200, 'kontrola vstupenek v O2 aréněPraha 9 Vysočany tel. 897222147'),
(3, 'uklízení odpadků', 170, 'uklízení odpadků po akcích, Václavské náměstí tel. 978231999'),
(10, 'rozdávání letáků', 170, 'rozdávání propagačních letáků, Výstaviště Letňany tel. 60856132'),
(7, 'rozvoz letáků', 150, 'rozvoz reklamních plakátů, Praha-Holešovice, tel.604123789'),
(12, 'počítání aut', 180, 'flexibilní brigáda-zjišťování hustoty provozu, Praha tel.777562145'),
(13, 'práce na pokladně', 200, 'brigáda, pokladní v hypermarketu, Kolín tel. 601888999'),
(14, 'počítání, inventura', 180, 'inventurní výpomoc v textilním skladu tel.777582692'),
(15, 'vykládka zboží', 200, 'vykládka zboží v logistickém centru Říčany tel.666999444'),
(16, 'roznáška balíků', 190, 'rozvážení balíků autem, Kutná hora tel 777589321'),
(17, 'rozvážka obědů', 170, 'Rozvážka obědů pro důchodce Lysá nad Labem tel.777823147'),
(18, 'výpomoc v zahradnictví', 160, 'výpomoc s likvidací bioodpadu Mělník tel.777999555'),
(19, 'vyklízení nebytových prostor', 200, 'pomoc s likvidací věcí z průmyslové zóny Kladno tel.777982666'),
(20, 'marketingový průzkum', 180, 'výpomoc se zpracováním údajů z marketingového průzkumu, práce je on-lline, tel.601234589'),
(21, 'vykládka zavazadel', 200, 'Výpomoc s vykládkou zavazadel na letišti Praha-Ruzyně tel.777564147'),
(22, 'ranní roznáška novin', 170, 'ranní roznaška denního tisku Poděbrady tel. 604123456'),
(23, 'kontrola lístků', 180, 'kontrola lístků na Výsvavišti Praha-Letňany tel. 777888999'),
(24, 'kontrola lístků', 160, 'kontrola lístků na Výstavišti Lysá nad Labem tel.777999222'),
(25, 'prodej zmrzliny', 160, 'stánkový prodej zmrzliny na koupališti Mělník te. 605123789'),
(26, 'stánkový prodej', 200, 'prodej suvenýrů Praha, Václavské náměstí tel. 777555222'),
(27, 'stánkový prodej', 160, 'stánkový prodej v rychlém občerstvení Praha, Staroměstské náměstí tel.606123478');

-- --------------------------------------------------------

--
-- Struktura tabulky `login1`
--

DROP TABLE IF EXISTS `login1`;
CREATE TABLE IF NOT EXISTS `login1` (
  `id_worker` int NOT NULL AUTO_INCREMENT,
  `workername` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `birth_number` varchar(11) DEFAULT NULL,
  `hpp` tinyint(1) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`id_worker`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Vypisuji data pro tabulku `login1`
--

INSERT INTO `login1` (`id_worker`, `workername`, `password`, `active`, `birth_number`, `hpp`, `comment`) VALUES
(1, 'worker1', 'worker1', 1, '7112030189', 0, 'Petr Tichý tel. 789654321'),
(2, 'worker2', 'worker2', 1, '7112030190', 0, 'Věra Beranová te. 888745123           je na mateřské'),
(3, 'worker3', 'worker3', 1, '7112030200', 0, 'Václav Lokota tel. 777444111  student, pouze víkendy'),
(4, 'worker4', 'worker4', 1, '6812030189', 1, 'Václav Vydra tel. 987654321'),
(5, 'worker5', 'worker5', 1, '9112060158', 1, 'Jan Zíma tel. 777214321'),
(6, 'worker6', 'worker6', 1, '9503030147', 1, 'Vaclav Nejezchleba tel 999602147'),
(7, 'worker7', 'worker7', 1, '7508080158', 1, 'Karel Přeučil tel. 602123478'),
(8, 'worker8', 'worker8', 1, '6112110185', 1, 'Vojtěch Prášil tel. 601235478'),
(9, 'worker9', 'worker9', 1, '9012030188', 1, 'Igor Hnízdo tel. 602145214'),
(10, 'worker10', 'worker10', 0, '9801010258', 1, 'Václav Nerudný te. 666222333');

-- --------------------------------------------------------

--
-- Struktura tabulky `login2`
--

DROP TABLE IF EXISTS `login2`;
CREATE TABLE IF NOT EXISTS `login2` (
  `id_leader` int NOT NULL AUTO_INCREMENT,
  `leadername` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `comment_leader` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  PRIMARY KEY (`id_leader`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Vypisuji data pro tabulku `login2`
--

INSERT INTO `login2` (`id_leader`, `leadername`, `password`, `active`, `comment_leader`) VALUES
(1, 'leader1', 'leader1', 1, 'Jan Zahradil tel. 777564123'),
(3, 'leader3', 'leader3', 0, 'Tomáš Kryl tel. 777898411'),
(2, 'leader2', 'leader2', 1, 'František Koudelka 774561123');

-- --------------------------------------------------------

--
-- Struktura tabulky `login3`
--

DROP TABLE IF EXISTS `login3`;
CREATE TABLE IF NOT EXISTS `login3` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `comment` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=132 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Vypisuji data pro tabulku `login3`
--

INSERT INTO `login3` (`id`, `username`, `password`, `active`, `comment`) VALUES
(1, 'manager1', 'manager1', 1, 'ing. Jan Kašpar tel. 735111555'),
(2, 'manager2', 'manager2', 1, 'Jan Vyskočil tel. 444582692'),
(3, 'manager3', 'manager3', 0, 'František Šťastný tel. 999562741');

-- --------------------------------------------------------

--
-- Struktura tabulky `setup`
--

DROP TABLE IF EXISTS `setup`;
CREATE TABLE IF NOT EXISTS `setup` (
  `id` int NOT NULL AUTO_INCREMENT,
  `limithours` int DEFAULT NULL,
  `limitsalary` int DEFAULT NULL,
  `tax` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Vypisuji data pro tabulku `setup`
--

INSERT INTO `setup` (`id`, `limithours`, `limitsalary`, `tax`) VALUES
(1, 300, 12000, '15.00');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
