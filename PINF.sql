-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Mar 19 Janvier 2021 à 11:25
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

--
-- Contenu de la table `catalogue`
--

INSERT INTO `catalogue` (`id`, `nomCategorie`) VALUES
(1, 'Vidéo'),
(2, 'Audio'),
(3, 'Affichage'),
(4, 'Accés Sécurité'),
(5, 'CFO CFA'),
(6, 'Tout');

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
  `refcategories` int(11) NOT NULL,
  `description` text NOT NULL,
  `titre` varchar(255) NOT NULL,
  `tags` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `ferrures`
--

INSERT INTO `ferrures` (`id`, `image`, `refMatiere`, `refFinition`, `numeroPlan`, `planPDF`, `refcategories`, `description`, `titre`, `tags`) VALUES
(1, 'image1', 1, 1, '03-0174-00', '03-0174-00', 1, 'support drapeau ', 'support drapeau ', 'support video'),
(2, 'image2', 1, 1, '03-01-74-000', 'PDF2', 1, 'support réglable', 'support réglable', 'support video'),
(3, 'image3', 1, 1, '03-0207-000', 'PDF3', 1, 'socle orientable', 'socle orientable', 'scocle vidéo'),
(4, 'image4', 1, 1, '03-0174-00', 'PDF4', 1, 'mat basculant', 'mat basculant', 'video'),
(5, 'image1', 1, 1, '03-0234-001', 'pdf1', 2, 'tablier', 'tablier', ''),
(6, 'image2', 1, 1, '03-0235-001', 'pdf', 2, 'applique', 'applique', ''),
(7, 'image3', 1, 1, '03-0136-001', 'pdf', 2, 'suspente', 'suspente', ''),
(8, 'image4', 1, 1, '03-0237-001', 'pdf', 2, 'drapeau', 'drapeau', '');

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
  `nomF` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `finition`
--

INSERT INTO `finition` (`id`, `nomF`) VALUES
(1, 'Thermolaqué'),
(2, 'Brut');

-- --------------------------------------------------------

--
-- Structure de la table `matiere`
--

CREATE TABLE `matiere` (
  `id` int(11) NOT NULL,
  `nomM` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `matiere`
--

INSERT INTO `matiere` (`id`, `nomM`) VALUES
(1, 'Acier S235JR'),
(2, 'Aluminium AU4G');

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

--
-- Contenu de la table `option`
--

INSERT INTO `option` (`id`, `nom`, `prix`, `refFerrures`) VALUES
(1, 'Plus-value contre plaqué', 6, 1),
(2, 'Plus-value hauteur / ml', 35, 1);

-- --------------------------------------------------------

--
-- Structure de la table `prix`
--

CREATE TABLE `prix` (
  `id` int(11) NOT NULL,
  `dimMin` float DEFAULT NULL,
  `dimMax` float DEFAULT NULL,
  `prixU` float DEFAULT NULL,
  `refFerrures` int(11) DEFAULT NULL,
  `qteMin` int(11) NOT NULL,
  `qteMax` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `prix`
--

INSERT INTO `prix` (`id`, `dimMin`, `dimMax`, `prixU`, `refFerrures`, `qteMin`, `qteMax`) VALUES
(4, NULL, NULL, 308, 1, 0, 1),
(5, NULL, NULL, 173, 1, 2, 4),
(6, NULL, NULL, 92, 1, 5, 9),
(7, NULL, NULL, 72, 1, 10, 1000),
(8, 0.7, 1, 388, 2, 0, 1),
(9, 0.7, 1, 249, 2, 2, 4),
(10, 0.7, 1, 171, 2, 5, 9),
(11, 0.7, 1, 152, 2, 10, 1000),
(12, 1, 2, 419, 2, 0, 1),
(13, 1, 2, 280, 2, 2, 4),
(14, 1, 2, 213, 2, 5, 9),
(15, 1, 2, 194, 2, 10, 1000),
(16, 2, 3, 425, 2, 0, 1),
(17, 2, 3, 294, 2, 2, 4),
(18, 2, 3, 236, 2, 5, 9),
(19, 2, 3, 217, 2, 10, 1000);

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
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom`, `prenom`, `mdp`, `mail`, `telephone`, `connecte`, `admin`) VALUES
(1, 'benoit', 'admin', 'admin', 'admin@admin.com', 215, 1, 1);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `ferruresDevis`
--
ALTER TABLE `ferruresDevis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `finition`
--
ALTER TABLE `finition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `matiere`
--
ALTER TABLE `matiere`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `option`
--
ALTER TABLE `option`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `prix`
--
ALTER TABLE `prix`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
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
