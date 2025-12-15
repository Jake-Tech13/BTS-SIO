-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 15 déc. 2025 à 21:22
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `flotte_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `chauffeur`
--

DROP TABLE IF EXISTS `chauffeur`;
CREATE TABLE IF NOT EXISTS `chauffeur` (
  `id_chauffeur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telephone` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `numero_permis` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_embauche` date DEFAULT NULL,
  `statut` enum('actif','inactif') COLLATE utf8mb4_general_ci DEFAULT 'actif',
  `certifications` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id_chauffeur`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `chauffeur`
--

INSERT INTO `chauffeur` (`id_chauffeur`, `nom`, `prenom`, `telephone`, `numero_permis`, `date_embauche`, `statut`, `certifications`) VALUES
(1, 'Dupont', 'Marcel', '0600000001', NULL, NULL, 'actif', NULL),
(2, 'Etcheverry', 'Peio', '0600000002', NULL, NULL, 'actif', NULL),
(3, 'Lefevre', 'Julie', '0600000003', NULL, NULL, 'actif', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id_client` int NOT NULL AUTO_INCREMENT,
  `raison_sociale` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nom` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prenom` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mdp` varchar(256) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tel` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `code_postal` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ville` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `adresse` text COLLATE utf8mb4_general_ci,
  `numero_tva` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_client`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id_client`, `raison_sociale`, `nom`, `prenom`, `email`, `mdp`, `tel`, `code_postal`, `ville`, `adresse`, `numero_tva`, `date_creation`) VALUES
(1, NULL, 'admin', NULL, NULL, 'btssio', '0642884639', NULL, NULL, NULL, NULL, '2025-12-09 14:52:12');

-- --------------------------------------------------------

--
-- Structure de la table `depot`
--

DROP TABLE IF EXISTS `depot`;
CREATE TABLE IF NOT EXISTS `depot` (
  `id_depot` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `nom_contact` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `adresse` text COLLATE utf8mb4_general_ci,
  `ville` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tel` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  PRIMARY KEY (`id_depot`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `depot`
--

INSERT INTO `depot` (`id_depot`, `nom`, `nom_contact`, `adresse`, `ville`, `tel`, `latitude`, `longitude`) VALUES
(1, 'Dépôt Bayonne Central', 'Jean Michel', 'ZA Saint-Frédéric', 'Bayonne', '0559000001', 43.4929490, -1.4748410),
(2, 'Dépôt Bordeaux Sud', 'Sophie Martin', 'Rue de Bègles', 'Bordeaux', '0556000002', 44.8146760, -0.5606700),
(3, 'Dépôt Pau Pyrénées', 'Pierre Durou', 'Avenue de l\'Europe', 'Pau', '0559000003', 43.3269000, -0.3427000);

-- --------------------------------------------------------

--
-- Structure de la table `facture`
--

DROP TABLE IF EXISTS `facture`;
CREATE TABLE IF NOT EXISTS `facture` (
  `id_facture` int NOT NULL AUTO_INCREMENT,
  `id_livraison` int NOT NULL,
  `montant_ht` decimal(12,2) NOT NULL,
  `tva` decimal(6,2) DEFAULT NULL,
  `statut` enum('emise','payee','impayee') COLLATE utf8mb4_general_ci DEFAULT 'emise',
  `date_emission` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_paiement` datetime DEFAULT NULL,
  `reference_externe` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id_facture`),
  KEY `idx_facture_livraison` (`id_livraison`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gps`
--

DROP TABLE IF EXISTS `gps`;
CREATE TABLE IF NOT EXISTS `gps` (
  `id` int NOT NULL AUTO_INCREMENT,
  `horodatage` datetime NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `vitesse_kmh` decimal(6,2) DEFAULT NULL,
  `cap` decimal(6,2) DEFAULT NULL,
  `immatriculation` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_position_trajet_horodatage` (`horodatage`),
  KEY `idx_gps_immatriculation` (`immatriculation`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `gps`
--

INSERT INTO `gps` (`id`, `horodatage`, `latitude`, `longitude`, `vitesse_kmh`, `cap`, `immatriculation`) VALUES
(1, '2025-12-09 16:06:07', 43.4500000, -1.5500000, 85.50, 180.00, 'AB-123-CD'),
(2, '2025-12-09 16:06:07', 44.7500000, -0.6000000, 90.00, 15.00, 'EF-456-GH');

-- --------------------------------------------------------

--
-- Structure de la table `livraison`
--

DROP TABLE IF EXISTS `livraison`;
CREATE TABLE IF NOT EXISTS `livraison` (
  `id_livraison` int NOT NULL AUTO_INCREMENT,
  `reference` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `id_client` int NOT NULL,
  `id_marchandise` int NOT NULL,
  `id_destination_depot` int NOT NULL,
  `poids_kg` decimal(12,2) NOT NULL,
  `volume_m3` decimal(12,3) DEFAULT NULL,
  `statut` enum('prevue','en_cours','livree','annulee') COLLATE utf8mb4_general_ci DEFAULT 'prevue',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_prelevement_prevue` datetime DEFAULT NULL,
  `date_livraison_prevue` datetime DEFAULT NULL,
  PRIMARY KEY (`id_livraison`),
  UNIQUE KEY `reference` (`reference`),
  KEY `fk_livraison_marchandise` (`id_marchandise`),
  KEY `fk_livraison_destination` (`id_destination_depot`),
  KEY `idx_livraison_reference` (`reference`),
  KEY `idx_livraison_client` (`id_client`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `maintenance`
--

DROP TABLE IF EXISTS `maintenance`;
CREATE TABLE IF NOT EXISTS `maintenance` (
  `id_maintenance` int NOT NULL AUTO_INCREMENT,
  `id_vehicule` int NOT NULL,
  `date_intervention` date NOT NULL,
  `type_intervention` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `cout` decimal(12,2) DEFAULT NULL,
  `odometre_km` int DEFAULT NULL,
  `km_prochaine_echeance` int DEFAULT NULL,
  `date_prochaine_echeance` date DEFAULT NULL,
  `statut` enum('prevue','terminee','annulee') COLLATE utf8mb4_general_ci DEFAULT 'prevue',
  PRIMARY KEY (`id_maintenance`),
  KEY `idx_maintenance_vehicule` (`id_vehicule`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `marchandise`
--

DROP TABLE IF EXISTS `marchandise`;
CREATE TABLE IF NOT EXISTS `marchandise` (
  `id_marchandise` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `num_un` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `classe_danger` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `etat` enum('solide','liquide','gaz') COLLATE utf8mb4_general_ci DEFAULT 'solide',
  `consignes_manipulation` text COLLATE utf8mb4_general_ci,
  `restrictions_transport` text COLLATE utf8mb4_general_ci,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_marchandise`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `trajet`
--

DROP TABLE IF EXISTS `trajet`;
CREATE TABLE IF NOT EXISTS `trajet` (
  `id_trajet` int NOT NULL AUTO_INCREMENT,
  `id_vehicule` int NOT NULL,
  `id_chauffeur` int NOT NULL,
  `id_gps` int NOT NULL,
  `heure_depart_prevue` datetime DEFAULT NULL,
  `heure_depart_reelle` datetime DEFAULT NULL,
  `heure_arrivee_prevue` datetime DEFAULT NULL,
  `heure_arrivee_reelle` datetime DEFAULT NULL,
  `statut` enum('prevu','en_cours','termine','annule') COLLATE utf8mb4_general_ci DEFAULT 'prevu',
  `notes` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id_trajet`),
  KEY `fk_trajet_vehicule` (`id_vehicule`),
  KEY `fk_trajet_chauffeur` (`id_chauffeur`),
  KEY `fk_trajet_gps` (`id_gps`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `trajet`
--

INSERT INTO `trajet` (`id_trajet`, `id_vehicule`, `id_chauffeur`, `id_gps`, `heure_depart_prevue`, `heure_depart_reelle`, `heure_arrivee_prevue`, `heure_arrivee_reelle`, `statut`, `notes`) VALUES
(1, 1, 1, 1, '2025-12-09 16:06:07', NULL, NULL, NULL, 'en_cours', NULL),
(2, 2, 2, 2, '2025-12-09 16:06:07', NULL, NULL, NULL, 'en_cours', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `vehicule`
--

DROP TABLE IF EXISTS `vehicule`;
CREATE TABLE IF NOT EXISTS `vehicule` (
  `id_vehicule` int NOT NULL AUTO_INCREMENT,
  `immatriculation` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `code_vin` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `modele` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `annee` year DEFAULT NULL,
  `capacite_kg` decimal(12,2) NOT NULL,
  `capacite_m3` decimal(12,3) DEFAULT NULL,
  `statut` enum('disponible','indisponible','en_service','hors_service','en_entretien') COLLATE utf8mb4_general_ci DEFAULT 'disponible',
  `id_depot_actuel` int DEFAULT NULL,
  PRIMARY KEY (`id_vehicule`),
  UNIQUE KEY `immatriculation` (`immatriculation`),
  KEY `fk_vehicule_depot_actuel` (`id_depot_actuel`),
  KEY `idx_vehicule_immatriculation` (`immatriculation`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `vehicule`
--

INSERT INTO `vehicule` (`id_vehicule`, `immatriculation`, `code_vin`, `modele`, `annee`, `capacite_kg`, `capacite_m3`, `statut`, `id_depot_actuel`) VALUES
(1, 'AB-123-CD', NULL, 'Renault T-High', '2022', 24000.00, NULL, 'en_service', NULL),
(2, 'EF-456-GH', NULL, 'Mercedes Actros', '2021', 22000.00, NULL, 'en_service', NULL),
(3, 'IJ-789-KL', NULL, 'Volvo FH16', '2023', 26000.00, NULL, 'disponible', 1),
(4, 'MN-012-OP', NULL, 'Scania R500', '2020', 24000.00, NULL, 'en_entretien', 2);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `facture`
--
ALTER TABLE `facture`
  ADD CONSTRAINT `fk_facture_livraison` FOREIGN KEY (`id_livraison`) REFERENCES `livraison` (`id_livraison`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `gps`
--
ALTER TABLE `gps`
  ADD CONSTRAINT `fk_gps_vehicule` FOREIGN KEY (`immatriculation`) REFERENCES `vehicule` (`immatriculation`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `livraison`
--
ALTER TABLE `livraison`
  ADD CONSTRAINT `fk_livraison_client` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_livraison_destination` FOREIGN KEY (`id_destination_depot`) REFERENCES `depot` (`id_depot`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_livraison_marchandise` FOREIGN KEY (`id_marchandise`) REFERENCES `marchandise` (`id_marchandise`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `maintenance`
--
ALTER TABLE `maintenance`
  ADD CONSTRAINT `fk_maintenance_vehicule` FOREIGN KEY (`id_vehicule`) REFERENCES `vehicule` (`id_vehicule`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `trajet`
--
ALTER TABLE `trajet`
  ADD CONSTRAINT `fk_trajet_chauffeur` FOREIGN KEY (`id_chauffeur`) REFERENCES `chauffeur` (`id_chauffeur`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_trajet_gps` FOREIGN KEY (`id_gps`) REFERENCES `gps` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_trajet_vehicule` FOREIGN KEY (`id_vehicule`) REFERENCES `vehicule` (`id_vehicule`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `vehicule`
--
ALTER TABLE `vehicule`
  ADD CONSTRAINT `fk_vehicule_depot_actuel` FOREIGN KEY (`id_depot_actuel`) REFERENCES `depot` (`id_depot`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
