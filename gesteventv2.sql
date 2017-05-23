-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 23 Mai 2017 à 19:56
-- Version du serveur :  5.7.9
-- Version de PHP :  7.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `gesteventv2`
--

-- --------------------------------------------------------

--
-- Structure de la table `acteur`
--

DROP TABLE IF EXISTS `acteur`;
CREATE TABLE IF NOT EXISTS `acteur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `trigramme` varchar(255) NOT NULL,
  `categorie` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `acteur`
--

INSERT INTO `acteur` (`id`, `nom`, `prenom`, `trigramme`, `categorie`, `login`, `mdp`) VALUES
(1, 'LUVARI', 'Ange robert', 'LU-A', 'acteur', 'angerobertluvari@gmail.com', '1234'),
(2, 'CAPRILE', 'Alex', 'CA-A', 'acteur', 'alexcaprile@gmail.com', '1234'),
(3, 'DIAZ', 'Jb', 'DI-J', 'acteur', 'jb', '1234'),
(4, 'TORTI', 'Ange', 'TO-A', 'admin', 'angetorti', '1234');

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `categorie`
--

INSERT INTO `categorie` (`id`, `libelle`) VALUES
(1, 'Développement'),
(2, 'Support'),
(3, 'Mise en place'),
(4, 'Café');

-- --------------------------------------------------------

--
-- Structure de la table `lien`
--

DROP TABLE IF EXISTS `lien`;
CREATE TABLE IF NOT EXISTS `lien` (
  `id_projet` int(11) NOT NULL,
  `id_categorie` int(11) NOT NULL,
  `id_acteur` int(11) NOT NULL,
  `id_tache` int(11) NOT NULL,
  PRIMARY KEY (`id_projet`,`id_categorie`,`id_acteur`,`id_tache`),
  KEY `fk_id_acteur` (`id_acteur`),
  KEY `fk_id_tache` (`id_tache`),
  KEY `fk_id_categorie` (`id_categorie`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `lien`
--

INSERT INTO `lien` (`id_projet`, `id_categorie`, `id_acteur`, `id_tache`) VALUES
(21, 2, 1, 95),
(22, 1, 1, 122),
(22, 1, 1, 123),
(22, 1, 2, 100),
(23, 1, 2, 101),
(23, 1, 2, 118),
(23, 2, 2, 102),
(22, 1, 3, 119),
(22, 1, 3, 120),
(22, 2, 3, 121),
(23, 3, 3, 103),
(24, 1, 3, 105),
(24, 2, 4, 106);

-- --------------------------------------------------------

--
-- Structure de la table `projet`
--

DROP TABLE IF EXISTS `projet`;
CREATE TABLE IF NOT EXISTS `projet` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) NOT NULL,
  `etat` varchar(255) DEFAULT NULL,
  `dateDebut` date DEFAULT NULL,
  `dateFinPrevue` date DEFAULT NULL,
  `dateFin` date DEFAULT NULL,
  `chefProjet` int(255) DEFAULT NULL,
  `avancee` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_chef_projet` (`chefProjet`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `projet`
--

INSERT INTO `projet` (`id`, `libelle`, `etat`, `dateDebut`, `dateFinPrevue`, `dateFin`, `chefProjet`, `avancee`) VALUES
(21, 'Application GestEvent V2', 'Non débuté', '2017-01-09', '2017-02-17', '2017-02-01', 4, '0.00'),
(22, 'ManagEvent', 'En cours', '2016-10-12', '2017-04-10', '2017-03-01', 3, '0.75'),
(23, 'Application GestEvent', 'En cours', '2017-01-05', '2017-02-17', '2017-01-23', 4, '0.50'),
(24, 'Application Magellan', 'Terminé', '2015-05-06', '2018-05-09', '2016-01-15', 2, '1.00'),
(26, 'Projet Test 0%', 'Non débuté', '2017-02-02', '2017-02-10', NULL, 2, '0.00');

-- --------------------------------------------------------

--
-- Structure de la table `tache`
--

DROP TABLE IF EXISTS `tache`;
CREATE TABLE IF NOT EXISTS `tache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) NOT NULL,
  `etat` varchar(255) NOT NULL,
  `duree` int(11) NOT NULL,
  `dateDebut` date DEFAULT NULL,
  `dateFin` date DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `commentaire` varchar(255) DEFAULT NULL,
  `prevue` tinyint(1) NOT NULL,
  `dateMaj` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=latin1;

--
-- Contenu de la table `tache`
--

INSERT INTO `tache` (`id`, `libelle`, `etat`, `duree`, `dateDebut`, `dateFin`, `description`, `commentaire`, `prevue`, `dateMaj`) VALUES
(95, 'Modification de la base de données', 'En cours', 9, '2017-02-03', '2017-02-10', 'Modification de la table lien.', 'Ajout de id_categorie dans la table en clé étrangère en référence à id de la table catégorie.', 1, '2017-02-10'),
(99, 'Finalisation du projet du stage', 'Terminée', 2, '2017-01-09', '2017-02-04', 'Réaliser le projet demandé (Gestion d''événements).', 'Voir compte rendu du stage.', 1, '2017-02-10'),
(100, 'Découverte du FrameWork Phalcon', 'Terminée', 2, '2017-01-09', '2017-02-08', 'Visionnage de tutoriels et lecture de la documentation Phalcon.', '', 1, '2017-02-10'),
(101, 'Implémentation de la base de données', 'Non débutée', 1, '2017-02-02', NULL, '', '', 0, '2017-02-10'),
(102, 'Modification de l''implémentation de la base de données', 'Non débutée', 5, '2017-02-10', NULL, '', '', 0, '2017-02-10'),
(103, 'Déploiement de la base de données', 'Terminée', 5, '2017-02-18', '2017-02-20', '', '', 1, '2017-02-10'),
(104, 'Création de la structure de la base de données', 'Terminée', 2, '2017-01-10', '2017-02-17', 'Création du MCD relatif à la base de données.', '', 1, '2017-02-06'),
(105, 'Mise au propre du code de l''application', 'Terminée', 1, '2017-02-14', NULL, 'Balayage du code pour le rendre plus propre.', '', 1, '2017-02-10'),
(106, 'Déploiement de l''application', 'En cours', 2, '2017-02-18', NULL, 'Mise en place de l''application sur le serveur public.', '', 0, '2017-02-10'),
(107, 'conception de la bdd', 'Terminée', 5, '2017-02-07', NULL, '', '', 1, '2017-02-06'),
(117, 'stage', 'En cours', 6, '2017-02-08', NULL, '', '', 0, '2017-02-10'),
(118, 'tache developement', 'Non débutée', 6, NULL, NULL, '', '', 1, '2017-02-10'),
(119, 'création de devis (vue)', 'Terminée', 5, '2017-02-07', NULL, '', '', 1, '2017-02-10'),
(120, 'création de devis (controller)', 'En cours', 5, '2017-02-14', NULL, '', '', 0, '2017-03-06'),
(121, 'création de formulaire de recherche devis', 'Terminée', 6, NULL, NULL, '', '', 1, '2017-02-10'),
(122, 'tacheprevue', 'En cours', 5, '2017-02-10', NULL, '', '', 1, '2017-02-10'),
(123, 'tachenonprevue', 'En cours', 5, '2017-02-10', NULL, '', '', 0, '2017-02-10'),
(126, 'la tache', 'Terminée', 5, '2017-02-22', NULL, 'la tache a ete longue', '', 1, '2017-02-21'),
(127, 'la tache de maximus', 'Non débutée', 5, NULL, NULL, '', '', 1, '2017-03-06'),
(128, 'tache 2', 'Terminée', 4, '2017-03-15', NULL, '', '', 1, '2017-03-06');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `lien`
--
ALTER TABLE `lien`
  ADD CONSTRAINT `fk_id_acteur` FOREIGN KEY (`id_acteur`) REFERENCES `acteur` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_id_categorie` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_id_projet` FOREIGN KEY (`id_projet`) REFERENCES `projet` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_id_tache` FOREIGN KEY (`id_tache`) REFERENCES `tache` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `projet`
--
ALTER TABLE `projet`
  ADD CONSTRAINT `fk_chef_projet` FOREIGN KEY (`chefProjet`) REFERENCES `acteur` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
