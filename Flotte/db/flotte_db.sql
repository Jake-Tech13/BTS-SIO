-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : jeu. 04 déc. 2025 à 17:37
-- Version du serveur : 10.3.39-MariaDB-0+deb10u1
-- Version de PHP : 7.3.31-1~deb10u5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `flotte_bd`
--

-- --------------------------------------------------------

--
-- Structure de la table `chauffeur`
--

CREATE TABLE `chauffeur` (
  `id_chauffeur` int(11) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `prenom` varchar(150) DEFAULT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `numero_permis` varchar(100) DEFAULT NULL,
  `date_embauche` date DEFAULT NULL,
  `statut` enum('actif','inactif') DEFAULT 'actif',
  `certifications` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `id_client` int(11) NOT NULL,
  `raison_sociale` varchar(255) DEFAULT NULL,
  `nom` varchar(150) DEFAULT NULL,
  `prenom` varchar(150) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mdp` varchar(256) DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `code_postal` varchar(20) DEFAULT NULL,
  `ville` varchar(150) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `numero_tva` varchar(100) DEFAULT NULL,
  `date_creation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `depot`
--

CREATE TABLE `depot` (
  `id_depot` int(11) NOT NULL,
  `nom` varchar(150) NOT NULL,
  `nom_contact` varchar(150) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `ville` varchar(150) DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `facture`
--

CREATE TABLE `facture` (
  `id_facture` int(11) NOT NULL,
  `id_livraison` int(11) NOT NULL,
  `montant_ht` decimal(12,2) NOT NULL,
  `tva` decimal(6,2) DEFAULT NULL,
  `statut` enum('emise','payee','impayee') DEFAULT 'emise',
  `date_emission` datetime DEFAULT current_timestamp(),
  `date_paiement` datetime DEFAULT NULL,
  `reference_externe` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `gps`
--

CREATE TABLE `gps` (
  `id_position` int(11) NOT NULL,
  `horodatage` datetime NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `vitesse_kmh` decimal(6,2) DEFAULT NULL,
  `cap` decimal(6,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `livraison`
--

CREATE TABLE `livraison` (
  `id_livraison` int(11) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `id_client` int(11) NOT NULL,
  `id_marchandise` int(11) NOT NULL,
  `id_destination_depot` int(11) NOT NULL,
  `poids_kg` decimal(12,2) NOT NULL,
  `volume_m3` decimal(12,3) DEFAULT NULL,
  `statut` enum('prevue','en_cours','livree','annulee') DEFAULT 'prevue',
  `date_creation` datetime DEFAULT current_timestamp(),
  `date_prelevement_prevue` datetime DEFAULT NULL,
  `date_livraison_prevue` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `maintenance`
--

CREATE TABLE `maintenance` (
  `id_maintenance` int(11) NOT NULL,
  `id_vehicule` int(11) NOT NULL,
  `date_intervention` date NOT NULL,
  `type_intervention` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `cout` decimal(12,2) DEFAULT NULL,
  `odometre_km` int(11) DEFAULT NULL,
  `km_prochaine_echeance` int(11) DEFAULT NULL,
  `date_prochaine_echeance` date DEFAULT NULL,
  `statut` enum('prevue','terminee','annulee') DEFAULT 'prevue'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `marchandise`
--

CREATE TABLE `marchandise` (
  `id_marchandise` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `num_un` varchar(50) DEFAULT NULL,
  `classe_danger` varchar(50) DEFAULT NULL,
  `etat` enum('solide','liquide','gaz') DEFAULT 'solide',
  `consignes_manipulation` text DEFAULT NULL,
  `restrictions_transport` text DEFAULT NULL,
  `date_creation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `trajet`
--

CREATE TABLE `trajet` (
  `id_trajet` int(11) NOT NULL,
  `id_vehicule` int(11) NOT NULL,
  `id_chauffeur` int(11) NOT NULL,
  `id_gps` int(11) NOT NULL,
  `heure_depart_prevue` datetime DEFAULT NULL,
  `heure_depart_reelle` datetime DEFAULT NULL,
  `heure_arrivee_prevue` datetime DEFAULT NULL,
  `heure_arrivee_reelle` datetime DEFAULT NULL,
  `statut` enum('prevu','en_cours','termine','annule') DEFAULT 'prevu',
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `vehicule`
--

CREATE TABLE `vehicule` (
  `id_vehicule` int(11) NOT NULL,
  `immatriculation` varchar(30) NOT NULL,
  `code_vin` varchar(50) DEFAULT NULL,
  `modele` varchar(150) DEFAULT NULL,
  `annee` year(4) DEFAULT NULL,
  `capacite_kg` decimal(12,2) NOT NULL,
  `capacite_m3` decimal(12,3) DEFAULT NULL,
  `statut` enum('disponible','indisponible','en_service','hors_service','en_entretien') DEFAULT 'disponible',
  `id_depot_actuel` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `chauffeur`
--
ALTER TABLE `chauffeur`
  ADD PRIMARY KEY (`id_chauffeur`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id_client`);

--
-- Index pour la table `depot`
--
ALTER TABLE `depot`
  ADD PRIMARY KEY (`id_depot`);

--
-- Index pour la table `facture`
--
ALTER TABLE `facture`
  ADD PRIMARY KEY (`id_facture`),
  ADD KEY `idx_facture_livraison` (`id_livraison`);

--
-- Index pour la table `gps`
--
ALTER TABLE `gps`
  ADD PRIMARY KEY (`id_position`),
  ADD KEY `idx_position_trajet_horodatage` (`horodatage`);

--
-- Index pour la table `livraison`
--
ALTER TABLE `livraison`
  ADD PRIMARY KEY (`id_livraison`),
  ADD UNIQUE KEY `reference` (`reference`),
  ADD KEY `fk_livraison_marchandise` (`id_marchandise`),
  ADD KEY `fk_livraison_destination` (`id_destination_depot`),
  ADD KEY `idx_livraison_reference` (`reference`),
  ADD KEY `idx_livraison_client` (`id_client`);

--
-- Index pour la table `maintenance`
--
ALTER TABLE `maintenance`
  ADD PRIMARY KEY (`id_maintenance`),
  ADD KEY `idx_maintenance_vehicule` (`id_vehicule`);

--
-- Index pour la table `marchandise`
--
ALTER TABLE `marchandise`
  ADD PRIMARY KEY (`id_marchandise`);

--
-- Index pour la table `trajet`
--
ALTER TABLE `trajet`
  ADD PRIMARY KEY (`id_trajet`),
  ADD KEY `fk_trajet_vehicule` (`id_vehicule`),
  ADD KEY `fk_trajet_chauffeur` (`id_chauffeur`),
  ADD KEY `fk_trajet_gps` (`id_gps`);

--
-- Index pour la table `vehicule`
--
ALTER TABLE `vehicule`
  ADD PRIMARY KEY (`id_vehicule`),
  ADD UNIQUE KEY `immatriculation` (`immatriculation`),
  ADD KEY `fk_vehicule_depot_actuel` (`id_depot_actuel`),
  ADD KEY `idx_vehicule_immatriculation` (`immatriculation`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `chauffeur`
--
ALTER TABLE `chauffeur`
  MODIFY `id_chauffeur` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `depot`
--
ALTER TABLE `depot`
  MODIFY `id_depot` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `facture`
--
ALTER TABLE `facture`
  MODIFY `id_facture` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `gps`
--
ALTER TABLE `gps`
  MODIFY `id_position` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `livraison`
--
ALTER TABLE `livraison`
  MODIFY `id_livraison` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `maintenance`
--
ALTER TABLE `maintenance`
  MODIFY `id_maintenance` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `marchandise`
--
ALTER TABLE `marchandise`
  MODIFY `id_marchandise` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `trajet`
--
ALTER TABLE `trajet`
  MODIFY `id_trajet` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `vehicule`
--
ALTER TABLE `vehicule`
  MODIFY `id_vehicule` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `facture`
--
ALTER TABLE `facture`
  ADD CONSTRAINT `fk_facture_livraison` FOREIGN KEY (`id_livraison`) REFERENCES `livraison` (`id_livraison`) ON UPDATE CASCADE;

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
  ADD CONSTRAINT `fk_trajet_gps` FOREIGN KEY (`id_gps`) REFERENCES `gps` (`id_position`) ON UPDATE CASCADE,
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
