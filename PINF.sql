-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Lun 14 Décembre 2020 à 14:36
-- Version du serveur :  5.7.32-0ubuntu0.18.04.1
-- Version de PHP :  7.2.24-0ubuntu0.18.04.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `PINF`
--

-- --------------------------------------------------------

--
-- Structure de la table `catalogue`
--

CREATE TABLE `catalogue` (
  `id` int(11) NOT NULL,
  `nomCategorie` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `devis`
--

CREATE TABLE `devis` (
  `id` int(11) NOT NULL,
  `numeroDevis` varchar(255) NOT NULL,
  `refCA` int(11) NOT NULL,
  `nomProjet` varchar(255) NOT NULL,
  `nomClient` varchar(255) NOT NULL,
  `dateCreation` date NOT NULL,
  `etat` varchar(255) NOT NULL,
  `dateLivraison` date DEFAULT NULL,
  `commentaire` text,
  `PrixTotal` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `dimension`
--

CREATE TABLE `dimension` (
  `id` int(11) NOT NULL,
  `min` float NOT NULL,
  `max` float NOT NULL,
  `refFerrures` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `incluePrix` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `ferrures`
--

CREATE TABLE `ferrures` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `refMatiere` int(11) NOT NULL,
  `refFinition` int(11) NOT NULL,
  `numeroPlan` varchar(255) NOT NULL,
  `planPDF` varchar(255) NOT NULL,
  `refcategories` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `ferruresDevis`
--

CREATE TABLE `ferruresDevis` (
  `id` int(11) NOT NULL,
  `refFerrures` int(11) NOT NULL,
  `refDevis` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `a` float DEFAULT NULL,
  `b` float DEFAULT NULL,
  `c` float DEFAULT NULL,
  `prix` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `finition`
--

CREATE TABLE `finition` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `matiere`
--

CREATE TABLE `matiere` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `motsCles`
--

CREATE TABLE `motsCles` (
  `id` int(11) NOT NULL,
  `refFerrures` int(11) NOT NULL,
  `cles` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `option`
--

CREATE TABLE `option` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `prix` float DEFAULT NULL,
  `refFerrures` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `prix`
--

CREATE TABLE `prix` (
  `id` int(11) NOT NULL,
  `min` float DEFAULT NULL,
  `max` float DEFAULT NULL,
  `prixU` float DEFAULT NULL,
  `refFerrures` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `telephone` int(11) NOT NULL,
  `connecte` tinyint(1) DEFAULT '0',
  `admin` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Index pour les tables exportées
--

--
-- Index pour la table `catalogue`
--
ALTER TABLE `catalogue`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `devis`
--
ALTER TABLE `devis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `refCA` (`refCA`);

--
-- Index pour la table `dimension`
--
ALTER TABLE `dimension`
  ADD PRIMARY KEY (`id`),
  ADD KEY `refFerrures` (`refFerrures`);

--
-- Index pour la table `ferrures`
--
ALTER TABLE `ferrures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ferrures_ibfk_1` (`refcategories`),
  ADD KEY `refMatiere` (`refMatiere`),
  ADD KEY `refFinition` (`refFinition`);

--
-- Index pour la table `ferruresDevis`
--
ALTER TABLE `ferruresDevis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `refDevis` (`refDevis`),
  ADD KEY `refFerrures` (`refFerrures`);

--
-- Index pour la table `finition`
--
ALTER TABLE `finition`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `matiere`
--
ALTER TABLE `matiere`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `motsCles`
--
ALTER TABLE `motsCles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `refFerrures` (`refFerrures`);

--
-- Index pour la table `option`
--
ALTER TABLE `option`
  ADD PRIMARY KEY (`id`),
  ADD KEY `refFerrures` (`refFerrures`);

--
-- Index pour la table `prix`
--
ALTER TABLE `prix`
  ADD PRIMARY KEY (`id`),
  ADD KEY `refFerrures` (`refFerrures`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `catalogue`
--
ALTER TABLE `catalogue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `devis`
--
ALTER TABLE `devis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `dimension`
--
ALTER TABLE `dimension`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `ferrures`
--
ALTER TABLE `ferrures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `ferruresDevis`
--
ALTER TABLE `ferruresDevis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `finition`
--
ALTER TABLE `finition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `matiere`
--
ALTER TABLE `matiere`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `motsCles`
--
ALTER TABLE `motsCles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `option`
--
ALTER TABLE `option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `prix`
--
ALTER TABLE `prix`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `devis`
--
ALTER TABLE `devis`
  ADD CONSTRAINT `devis_ibfk_1` FOREIGN KEY (`refCA`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `dimension`
--
ALTER TABLE `dimension`
  ADD CONSTRAINT `dimension_ibfk_1` FOREIGN KEY (`refFerrures`) REFERENCES `ferrures` (`id`);

--
-- Contraintes pour la table `ferrures`
--
ALTER TABLE `ferrures`
  ADD CONSTRAINT `ferrures_ibfk_1` FOREIGN KEY (`refcategories`) REFERENCES `catalogue` (`id`),
  ADD CONSTRAINT `ferrures_ibfk_2` FOREIGN KEY (`refMatiere`) REFERENCES `matiere` (`id`),
  ADD CONSTRAINT `ferrures_ibfk_3` FOREIGN KEY (`refFinition`) REFERENCES `finition` (`id`);

--
-- Contraintes pour la table `ferruresDevis`
--
ALTER TABLE `ferruresDevis`
  ADD CONSTRAINT `ferruresDevis_ibfk_1` FOREIGN KEY (`refDevis`) REFERENCES `devis` (`id`),
  ADD CONSTRAINT `ferruresDevis_ibfk_2` FOREIGN KEY (`refFerrures`) REFERENCES `ferrures` (`id`);

--
-- Contraintes pour la table `motsCles`
--
ALTER TABLE `motsCles`
  ADD CONSTRAINT `motsCles_ibfk_1` FOREIGN KEY (`refFerrures`) REFERENCES `ferrures` (`id`);

--
-- Contraintes pour la table `option`
--
ALTER TABLE `option`
  ADD CONSTRAINT `option_ibfk_1` FOREIGN KEY (`refFerrures`) REFERENCES `ferrures` (`id`);

--
-- Contraintes pour la table `prix`
--
ALTER TABLE `prix`
  ADD CONSTRAINT `prix_ibfk_1` FOREIGN KEY (`refFerrures`) REFERENCES `ferrures` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
