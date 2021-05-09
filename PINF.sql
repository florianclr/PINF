-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Dim 09 Mai 2021 à 11:03
-- Version du serveur :  5.7.33-0ubuntu0.18.04.1
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
  `nomCategorie` varchar(255) NOT NULL,
  `couleur` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `catalogue`
--

INSERT INTO `catalogue` (`id`, `nomCategorie`, `couleur`) VALUES
(1, 'Vidéo', '#0000CD'),
(2, 'Audio', '#8B0000'),
(3, 'Affichage', '#9ACD32'),
(4, 'Accès / Sécurité', '#4B0082'),
(5, 'CFO / CFA', '#008B8B'),
(6, 'Tout', '#000000'),
(8, 'Test', '#e7650d');

-- --------------------------------------------------------

--
-- Structure de la table `couleursFerrures`
--

CREATE TABLE `couleursFerrures` (
  `id` int(11) NOT NULL,
  `couleur` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `couleursFerrures`
--

INSERT INTO `couleursFerrures` (`id`, `couleur`) VALUES
(1, 'Noir'),
(2, 'Gris foncé'),
(3, 'Gris'),
(4, 'Autre');

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
  `etat` enum('EN_CRÉATION','DEMANDE_COMMANDE','COMMANDE_VALIDÉE','EN_FABRICATION','LIVRÉ','ARCHIVÉ') DEFAULT 'EN_CRÉATION',
  `dateLivraison` date DEFAULT NULL,
  `commentaire` text,
  `PrixTotal` float DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `devis`
--

INSERT INTO `devis` (`id`, `numeroDevis`, `refCA`, `nomProjet`, `nomClient`, `dateCreation`, `etat`, `dateLivraison`, `commentaire`, `PrixTotal`) VALUES
(1, '11', 1, 'essai', 'essai', '2021-02-23', 'ARCHIVÉ', '2021-04-30', NULL, 997),
(4, '2', 1, 'Chantier1', 'Jeanne', '2021-04-09', 'LIVRÉ', '2021-04-01', 'Aucun commentaire', 952),
(11, '12', 4, 'Chantier6', 'Johnson', '2021-04-24', 'EN_FABRICATION', '2021-05-13', NULL, 0),
(14, '1234', 6, 'testNew', 'Beubeu', '2021-04-28', 'LIVRÉ', '2021-05-18', 'Validé', 1953),
(16, '203', 6, 'Tesla', 'Quentin', '2021-05-02', 'EN_FABRICATION', '2021-05-27', 'CC', 308),
(17, '12345', 7, 'Chantier10', 'defsghj', '2021-05-05', 'ARCHIVÉ', NULL, NULL, 0);

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

--
-- Contenu de la table `dimension`
--

INSERT INTO `dimension` (`id`, `min`, `max`, `refFerrures`, `nom`, `incluePrix`) VALUES
(1, 20, 50, 1, 'a', 0),
(2, 80, 100, 1, 'b', 0),
(3, 1, 3, 2, 'a', 1),
(4, 4, 6, 2, 'b', 0),
(5, 15, 20, 32, 'a', 1);

-- --------------------------------------------------------

--
-- Structure de la table `ferrures`
--

CREATE TABLE `ferrures` (
  `id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `refMatiere` int(11) NOT NULL,
  `refFinition` int(11) NOT NULL,
  `numeroPlan` varchar(255) DEFAULT NULL,
  `planPDF` varchar(255) DEFAULT NULL,
  `refcategories` int(11) NOT NULL,
  `description` text NOT NULL,
  `titre` varchar(255) NOT NULL,
  `tags` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `ferrures`
--

INSERT INTO `ferrures` (`id`, `image`, `refMatiere`, `refFinition`, `numeroPlan`, `planPDF`, `refcategories`, `description`, `titre`, `tags`) VALUES
(1, 'image1.jpeg', 1, 1, '03-0174-00', '03-0174-00', 1, 'support drapeau ', 'support drapeau ', 'support video'),
(2, 'image3.jpeg', 1, 1, '03-01-74-000', 'PDF2', 1, 'support réglable', 'support réglable', 'support video'),
(3, 'image1.jpeg', 1, 1, '03-0207-000', 'PDF3', 1, 'support', 'support', 'support vidéo'),
(4, 'mat_basculant.jpg', 1, 1, '03-0174-00', 'PDF4', 1, 'mat basculant', 'mat basculant', 'video'),
(5, 'SET_83779__-606120b7ed01a.jpg', 2, 2, '03-0234-001', 'pdf1', 2, 'attache tablier', 'attache de tablier', 'attache tablier'),
(18, 'Q3.png', 1, 1, 'SDA2-TP5.pdf', 'SDA2-TP5.pdf', 8, 'frgh', 'qsdcfvg', 'efrgth;qsdcfvg;Test;Acier S235JR;Thermolaqué'),
(24, 'addFriendPicture.png', 1, 1, 'ISIM TP4 2016.pdf', 'ISIM TP4 2016.pdf', 8, 'n', 'new', 'u;new;Test;Acier S235JR;Thermolaqué'),
(25, 'addFriendPicture.png', 1, 1, 'secret (1).pdf', 'secret (1).pdf', 8, 'n', 'new2', 'u;new;Test;Acier S235JR;Thermolaqué'),
(26, 'Truck-Blue.png', 1, 1, 'null', 'null', 8, 'this', 'this', ';this;Test;Acier S235JR;Thermolaqué;undefined'),
(29, 'zenity-information-screenshot.png', 1, 1, 'null', 'null', 8, 'zertgy', 'hey', 'edqfrgthy;hey;Test;Acier S235JR;Thermolaqué;undefined'),
(30, 'webclip-icon.png', 1, 1, 'null', 'null', 8, 'edfrgh', 'test3', 'rfgtrh;test3;Test;Acier S235JR;Thermolaqué;undefined'),
(31, 'ferrure-pour-plan-de-travail-amovible.jpg', 1, 1, 'null', 'null', 8, 'fdghnj,fbgnh', 'dvfbgnh', ';dvfbgnh;Test;Acier S235JR;Thermolaqué;undefined'),
(32, 's-l500.jpg', 1, 1, 'null', 'null', 2, 'raccord plan de travail', 'raccord plan', 'raccord plan travail;raccord plan de travail;Audio;Acier S235JR;Thermolaqué;undefined');

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
  `prix` float NOT NULL,
  `couleur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `ferruresDevis`
--

INSERT INTO `ferruresDevis` (`id`, `refFerrures`, `refDevis`, `quantite`, `a`, `b`, `c`, `prix`, `couleur`) VALUES
(16, 1, 1, 1, 20, 80, -1, 308, 2),
(18, 1, 1, 1, 20, 80, -1, 343, 3),
(19, 1, 1, 2, 20, 80, -1, 346, 1),
(22, 1, 4, 3, 21, 100, -1, 554, 2),
(28, 2, 4, 1, 0.7, 2.1, -1, 398, 2),
(29, 2, 14, 3, 1.7, 4.1, -1, 860, 2),
(30, 1, 14, 3, 20, 80, -1, 595, 1),
(32, 2, 14, 2, 0.7, 2.1, -1, 498, 4),
(33, 1, 16, 1, 20, 80, -1, 308, 2);

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
(2, 'Plus-value hauteur / ml', 35, 1),
(4, 'test', 10, 2);

-- --------------------------------------------------------

--
-- Structure de la table `optionDevis`
--

CREATE TABLE `optionDevis` (
  `quantité` int(11) NOT NULL,
  `refOption` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `refFerrureDevis` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `optionDevis`
--

INSERT INTO `optionDevis` (`quantité`, `refOption`, `id`, `refFerrureDevis`) VALUES
(1, 1, 1, 16),
(1, 2, 2, 16),
(2, 4, 3, 29),
(1, 1, 4, 30),
(2, 2, 5, 30);

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
(19, 2, 3, 217, 2, 10, 1000),
(20, 15, 17, 10, 32, 1, 5),
(21, 18, 20, 15, 32, 1, 5),
(22, 15, 17, 20, 32, 6, 10),
(23, 18, 20, 25, 32, 6, 10);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `mdp` varchar(255) DEFAULT NULL,
  `mail` varchar(255) NOT NULL,
  `telephone` int(11) NOT NULL,
  `connecte` tinyint(1) DEFAULT '0',
  `admin` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom`, `prenom`, `mdp`, `mail`, `telephone`, `connecte`, `admin`) VALUES
(1, 'benoit', 'admin', '$2y$12$Uh0DsFNaPAaTipEZf7WX/eL.X6ifZEnUjlaySy11Ba/hMYsPL1X2i', 'benoit.blart@ig2i.centralelille.fr', 123, 0, 0),
(2, 'Sueur', 'Jeanne', '$2y$12$PfbN3yJabf8yJPZLDvcYeuXcJshf0leVJgHUiOzr5OI6omzvI6Zm2', 'jsu62n@orange.fr', 679296422, 0, 2),
(3, 'Faget', 'Pierre', '$2y$12$MChysjSwAT2KnQ/4hsVSyua/wqDFl9lKPovSycIBdgliGmnLdGn5y', 'pierre@ig2i.fr', 1234, 0, 1),
(4, 'Dumont', 'Mathilde', '$2y$12$N0wP1XV/UfU5NckXP5A9iuxI4.F7/DnfODEFTNmVBDSywaI4Vr9.e', 'mathilde@ig2i.fr', 101, 0, 0),
(6, 'Celary', 'Flo', '$2y$12$j7EP1x.iTLjY/0zdJ/axkuxPXf3AJ/GBFfHCU2BuEXwkorvcsMOO.', 'jsu62n@gmail.com', 69, 0, 0),
(7, 'new', 'new', '$2y$12$O3tAZzrPWoqEkqSVxN3n5edsrXMkyRliH48z6pnoT4zOfumLpTgy6', 'new@new.fr', 1, 0, 0),
(13, 'Sagnol', 'Quentin', NULL, 'quentin@ig2i.fr', 122, 0, 0);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `catalogue`
--
ALTER TABLE `catalogue`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `couleursFerrures`
--
ALTER TABLE `couleursFerrures`
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
  ADD KEY `refFerrures` (`refFerrures`),
  ADD KEY `couleur` (`couleur`);

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
-- Index pour la table `optionDevis`
--
ALTER TABLE `optionDevis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `optioDevis_ibfk_2` (`refOption`),
  ADD KEY `optioDevis_ibfk_3` (`refFerrureDevis`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT pour la table `couleursFerrures`
--
ALTER TABLE `couleursFerrures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `devis`
--
ALTER TABLE `devis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT pour la table `dimension`
--
ALTER TABLE `dimension`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `ferrures`
--
ALTER TABLE `ferrures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT pour la table `ferruresDevis`
--
ALTER TABLE `ferruresDevis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `optionDevis`
--
ALTER TABLE `optionDevis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `prix`
--
ALTER TABLE `prix`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
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
  ADD CONSTRAINT `ferruresDevis_ibfk_2` FOREIGN KEY (`refFerrures`) REFERENCES `ferrures` (`id`),
  ADD CONSTRAINT `ferruresDevis_ibfk_3` FOREIGN KEY (`couleur`) REFERENCES `couleursFerrures` (`id`);

--
-- Contraintes pour la table `option`
--
ALTER TABLE `option`
  ADD CONSTRAINT `option_ibfk_1` FOREIGN KEY (`refFerrures`) REFERENCES `ferrures` (`id`);

--
-- Contraintes pour la table `optionDevis`
--
ALTER TABLE `optionDevis`
  ADD CONSTRAINT `optioDevis_ibfk_2` FOREIGN KEY (`refOption`) REFERENCES `option` (`id`),
  ADD CONSTRAINT `optioDevis_ibfk_3` FOREIGN KEY (`refFerrureDevis`) REFERENCES `ferruresDevis` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `prix`
--
ALTER TABLE `prix`
  ADD CONSTRAINT `prix_ibfk_1` FOREIGN KEY (`refFerrures`) REFERENCES `ferrures` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
