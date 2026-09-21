-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 19, 2026 at 06:15 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eventt`
--

-- --------------------------------------------------------

--
-- Table structure for table `evenements`
--

CREATE TABLE `evenements` (
  `id_evenement` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `date_evenement` date NOT NULL,
  `lieu` varchar(255) NOT NULL,
  `heure` time NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `evenements`
--

INSERT INTO `evenements` (`id_evenement`, `nom`, `type`, `date_evenement`, `lieu`, `heure`, `image`) VALUES
(1, 'Mariage', 'Mariage', '0000-00-00', 'Villa des Roses, Casablanca', '16:00:00', 'images/mariage.jpg'),
(2, 'Seminaire professionnel', 'Seminaire', '0000-00-00', 'Hotel Kenzi, Marrakech', '09:00:00', 'images/seminaire.jpg'),
(3, 'Anniversaire', 'Anniversaire', '0000-00-00', 'Salle Le Palace, Rabat', '18:00:00', 'images/anniversaire.jpg'),
(4, 'Atelier creatif', 'Atelier', '0000-00-00', 'Centre Culturel, Fes', '10:00:00', 'images/atelier.jpg'),
(5, 'Soiree gala', 'Evenement prive', '0000-00-00', 'Palais des Congres, Agadir', '19:00:00', 'images/gala.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id_notification` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `message` text NOT NULL,
  `lu` tinyint(1) NOT NULL DEFAULT 0,
  `date_notification` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id_reservation` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_evenement` int(11) NOT NULL,
  `nombre_places` int(11) NOT NULL DEFAULT 1,
  `statut` enum('en_attente','confirmee','annulee') NOT NULL DEFAULT 'en_attente',
  `date_reservation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('utilisateur','organisateur','admin') NOT NULL,
  `date_inscription` datetime DEFAULT current_timestamp(),
  `reset_code` varchar(10) DEFAULT NULL,
  `reset_expiration` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_utilisateur`, `nom`, `prenom`, `email`, `telephone`, `mot_de_passe`, `role`, `date_inscription`, `reset_code`, `reset_expiration`) VALUES
(1, 'elomari', 'houda', 'rim12@gamil.ma', '0602885984', '$2y$10$0V3XH1dTKpVG74gfqAByqOQtfEJ1zLm.t9Ys.LmeU02dZ6mQauWdO', 'utilisateur', '2026-09-13 19:15:36', NULL, NULL),
(2, 'ELOMARI', 'RIM', 'rim10@gamil.ma', '0602885984', '$2y$10$5mvDCSlEfSg4DxcUMgR.M.97xWPAW8O5MuJe.fCqWNChxBZ7jFsgi', 'utilisateur', '2026-09-13 19:18:37', NULL, NULL),
(3, 'yahyaoi', 'moaad', 'moaad2003@gmail.ma', '0612345667', '27798', 'utilisateur', '2026-09-13 19:26:29', NULL, NULL),
(4, 'elhaddouchi', 'fatimazahraa', 'elhaddouchifatimazahra@gmail.com', '0789556432', '567789', 'utilisateur', '2026-09-13 19:27:30', NULL, NULL),
(7, 'elhaddouchi', 'fatimazahraa', 'fatimazahra@gmail.ma', '0789556432', '27798', 'utilisateur', '2026-09-13 19:33:03', NULL, NULL),
(8, 'rahimi', 'douaa', 'doua45@gmail.ma', '0786547838', '123456', 'utilisateur', '2026-09-13 19:37:29', NULL, NULL),
(9, 'omrani', 'khawla', 'khawla45@gmail.ma', '0576895423', '3456@OMRANI', 'utilisateur', '2026-09-13 20:30:27', NULL, NULL),
(10, 'elhaddouchi', 'fatimazahraa', 'fati@gamil.ma', '0756789489', '12345', 'utilisateur', '2026-09-14 13:33:14', NULL, NULL),
(11, 'elhaddouchi', 'fatimazahraa', 'fatimazahra234@gamil.ma', '0756789489', '1234', 'utilisateur', '2026-09-14 23:01:09', NULL, NULL),
(12, 'almorad', 'loubna', 'loubna123@gmail.ma', '0647534523', '345LOUBNA', 'utilisateur', '2026-09-14 23:03:46', NULL, NULL),
(13, 'El Amrani', 'Sara', 'sara@evently.com', '0612345678', '123456', 'organisateur', '2026-09-16 17:29:20', NULL, NULL),
(14, 'Admin', 'Evently', 'admin@evently.com', '0600000000', '123456', 'admin', '2026-09-16 17:32:55', NULL, NULL),
(15, 'Aneflous', 'Lamiae', 'lamiaeaneflous@gmail.com', '0684296058', '12345', 'utilisateur', '2026-09-19 15:22:02', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `evenements`
--
ALTER TABLE `evenements`
  ADD PRIMARY KEY (`id_evenement`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id_notification`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_evenement` (`id_evenement`);

--
-- Indexes for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `evenements`
--
ALTER TABLE `evenements`
  MODIFY `id_evenement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id_notification` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id_reservation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id_utilisateur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`id_evenement`) REFERENCES `evenements` (`id_evenement`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
