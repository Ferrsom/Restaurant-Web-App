-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 21. Jan 2026 um 16:09
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `bestellungssystem`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bestellposition`
--

CREATE TABLE `bestellposition` (
  `pID` int(11) NOT NULL,
  `bID` int(11) NOT NULL,
  `kID` int(11) NOT NULL,
  `menge` int(11) NOT NULL,
  `einzelpreis` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `bestellung`
--

CREATE TABLE `bestellung` (
  `bID` int(11) NOT NULL,
  `tID` int(11) NOT NULL,
  `zeitpunkt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `karte`
--

CREATE TABLE `karte` (
  `kID` int(11) NOT NULL,
  `produkt` varchar(150) NOT NULL,
  `preis` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `karte`
--

INSERT INTO `karte` (`kID`, `produkt`, `preis`) VALUES
(1, 'Bruschetta mit Tomaten und Knoblauch', 6.50),
(2, 'Gemischter Beilagensalat', 4.50),
(3, 'Kartoffelsuppe mit Croutons', 5.90),
(4, 'Schnitzel Wiener Art mit Pommes frites', 15.90),
(5, 'Jägerschnitzel mit Spätzle', 16.90),
(6, 'Rinderroulade mit Rotkohl und Klößen', 18.90),
(7, 'Bratwurst mit Sauerkraut und Kartoffelpüree', 12.90),
(8, 'Käsespätzle mit Röstzwiebeln', 13.90),
(9, 'Gebratener Lachs mit Zitronensauce und Reis', 19.90),
(10, 'Currywurst mit Pommes', 10.90),
(11, 'Flammkuchen klassisch', 11.90),
(12, 'Mineralwasser still 0,5l', 2.90),
(13, 'Apfelschorle 0,5l', 3.20),
(14, 'Cola 0,5l', 3.50),
(15, 'Weizenbier 0,5l', 4.20),
(16, 'Pilsbier 0,5l', 3.90),
(17, 'Vanilleeis mit heißen Kirschen', 6.50),
(18, 'Apfelstrudel mit Vanillesauce', 6.90),
(19, 'Schokoladenpudding mit Sahne', 5.50);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rechnung`
--

CREATE TABLE `rechnung` (
  `rID` int(11) NOT NULL,
  `bID` int(11) NOT NULL,
  `erstellt_am` datetime NOT NULL DEFAULT current_timestamp(),
  `gesamt` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tisch`
--

CREATE TABLE `tisch` (
  `tID` int(11) NOT NULL,
  `tisch_nummer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `tisch`
--

INSERT INTO `tisch` (`tID`, `tisch_nummer`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `bestellposition`
--
ALTER TABLE `bestellposition`
  ADD PRIMARY KEY (`pID`),
  ADD KEY `fk_position_bestellung` (`bID`),
  ADD KEY `fk_position_karte` (`kID`);

--
-- Indizes für die Tabelle `bestellung`
--
ALTER TABLE `bestellung`
  ADD PRIMARY KEY (`bID`),
  ADD KEY `fk_bestellung_tisch` (`tID`);

--
-- Indizes für die Tabelle `karte`
--
ALTER TABLE `karte`
  ADD PRIMARY KEY (`kID`);

--
-- Indizes für die Tabelle `rechnung`
--
ALTER TABLE `rechnung`
  ADD PRIMARY KEY (`rID`),
  ADD UNIQUE KEY `bID` (`bID`);

--
-- Indizes für die Tabelle `tisch`
--
ALTER TABLE `tisch`
  ADD PRIMARY KEY (`tID`),
  ADD UNIQUE KEY `tisch_nummer` (`tisch_nummer`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `bestellposition`
--
ALTER TABLE `bestellposition`
  MODIFY `pID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `bestellung`
--
ALTER TABLE `bestellung`
  MODIFY `bID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `karte`
--
ALTER TABLE `karte`
  MODIFY `kID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT für Tabelle `rechnung`
--
ALTER TABLE `rechnung`
  MODIFY `rID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `tisch`
--
ALTER TABLE `tisch`
  MODIFY `tID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `bestellposition`
--
ALTER TABLE `bestellposition`
  ADD CONSTRAINT `fk_position_bestellung` FOREIGN KEY (`bID`) REFERENCES `bestellung` (`bID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_position_karte` FOREIGN KEY (`kID`) REFERENCES `karte` (`kID`) ON UPDATE CASCADE;

--
-- Constraints der Tabelle `bestellung`
--
ALTER TABLE `bestellung`
  ADD CONSTRAINT `fk_bestellung_tisch` FOREIGN KEY (`tID`) REFERENCES `tisch` (`tID`) ON UPDATE CASCADE;

--
-- Constraints der Tabelle `rechnung`
--
ALTER TABLE `rechnung`
  ADD CONSTRAINT `fk_rechnung_bestellung` FOREIGN KEY (`bID`) REFERENCES `bestellung` (`bID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
