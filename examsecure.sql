-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : sam. 09 mai 2026 à 05:05
-- Version du serveur : 8.0.45-0ubuntu0.24.04.1
-- Version de PHP : 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `examsecure`
--

-- --------------------------------------------------------

--
-- Structure de la table `anomalies`
--

CREATE TABLE `anomalies` (
  `id` int NOT NULL,
  `tentative_id` int NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `description` text,
  `severite` enum('faible','moyen','élevé') DEFAULT 'moyen',
  `date_detection` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `examens`
--

CREATE TABLE `examens` (
  `id` int NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text,
  `examinateur_id` int NOT NULL,
  `duree_minutes` int DEFAULT '60',
  `date_debut` datetime DEFAULT NULL,
  `date_fin` datetime DEFAULT NULL,
  `statut` enum('brouillon','publié','terminé') DEFAULT 'brouillon',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `examens`
--

INSERT INTO `examens` (`id`, `titre`, `description`, `examinateur_id`, `duree_minutes`, `date_debut`, `date_fin`, `statut`, `created_at`) VALUES
(1, 'Mathématiques - Test 1', 'Examen de mathématiques niveau 3ème', 1, 60, NULL, NULL, 'publié', '2026-05-09 04:58:50'),
(2, 'Français - Rédaction', 'Épreuve de rédaction et grammaire', 1, 90, NULL, NULL, 'publié', '2026-05-09 04:58:50');

-- --------------------------------------------------------

--
-- Structure de la table `options_qcm`
--

CREATE TABLE `options_qcm` (
  `id` int NOT NULL,
  `question_id` int NOT NULL,
  `texte` text NOT NULL,
  `est_correcte` tinyint(1) DEFAULT '0',
  `ordre` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `options_qcm`
--

INSERT INTO `options_qcm` (`id`, `question_id`, `texte`, `est_correcte`, `ordre`) VALUES
(1, 1, '3', 0, 1),
(2, 1, '4', 1, 2),
(3, 1, '5', 0, 3);

-- --------------------------------------------------------

--
-- Structure de la table `questions`
--

CREATE TABLE `questions` (
  `id` int NOT NULL,
  `examen_id` int NOT NULL,
  `type` enum('qcm','texte') DEFAULT 'qcm',
  `enonce` text NOT NULL,
  `points` int DEFAULT '1',
  `ordre` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `questions`
--

INSERT INTO `questions` (`id`, `examen_id`, `type`, `enonce`, `points`, `ordre`, `created_at`) VALUES
(1, 1, 'qcm', 'Quel est le résultat de 2 + 2 ?', 1, 1, '2026-05-09 04:58:51'),
(2, 1, 'texte', 'Expliquez comment vous avez trouvé le résultat', 2, 2, '2026-05-09 04:58:51');

-- --------------------------------------------------------

--
-- Structure de la table `reponses`
--

CREATE TABLE `reponses` (
  `id` int NOT NULL,
  `tentative_id` int NOT NULL,
  `question_id` int NOT NULL,
  `option_id` int DEFAULT NULL,
  `texte_reponse` text,
  `est_correcte` tinyint(1) DEFAULT NULL,
  `points_obtenus` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tentatives`
--

CREATE TABLE `tentatives` (
  `id` int NOT NULL,
  `examen_id` int NOT NULL,
  `etudiant_id` int NOT NULL,
  `date_debut` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_fin` datetime DEFAULT NULL,
  `statut` enum('en_cours','soumis','corrigé') DEFAULT 'en_cours',
  `score` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('etudiant','examinateur') NOT NULL,
  `sexe` enum('M','F','Autre') NOT NULL,
  `filiere` varchar(100) DEFAULT NULL,
  `reconnaissance_faciale` longblob,
  `date_reconnaissance` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`, `sexe`, `filiere`, `reconnaissance_faciale`, `date_reconnaissance`, `created_at`) VALUES
(1, 'Dupont', 'Jean', 'exam@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'examinateur', 'M', 'Informatique', NULL, NULL, '2026-05-09 04:58:50'),
(2, 'Martin', 'Alice', 'etudiant@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant', 'F', 'Mathématiques', NULL, NULL, '2026-05-09 04:58:50');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `anomalies`
--
ALTER TABLE `anomalies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tentative_id` (`tentative_id`);

--
-- Index pour la table `examens`
--
ALTER TABLE `examens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `examinateur_id` (`examinateur_id`);

--
-- Index pour la table `options_qcm`
--
ALTER TABLE `options_qcm`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Index pour la table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `examen_id` (`examen_id`);

--
-- Index pour la table `reponses`
--
ALTER TABLE `reponses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tentative_id` (`tentative_id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Index pour la table `tentatives`
--
ALTER TABLE `tentatives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `examen_id` (`examen_id`),
  ADD KEY `etudiant_id` (`etudiant_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `anomalies`
--
ALTER TABLE `anomalies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `examens`
--
ALTER TABLE `examens`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `options_qcm`
--
ALTER TABLE `options_qcm`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `reponses`
--
ALTER TABLE `reponses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `tentatives`
--
ALTER TABLE `tentatives`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `anomalies`
--
ALTER TABLE `anomalies`
  ADD CONSTRAINT `anomalies_ibfk_1` FOREIGN KEY (`tentative_id`) REFERENCES `tentatives` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `examens`
--
ALTER TABLE `examens`
  ADD CONSTRAINT `examens_ibfk_1` FOREIGN KEY (`examinateur_id`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `options_qcm`
--
ALTER TABLE `options_qcm`
  ADD CONSTRAINT `options_qcm_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`examen_id`) REFERENCES `examens` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reponses`
--
ALTER TABLE `reponses`
  ADD CONSTRAINT `reponses_ibfk_1` FOREIGN KEY (`tentative_id`) REFERENCES `tentatives` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reponses_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`),
  ADD CONSTRAINT `reponses_ibfk_3` FOREIGN KEY (`option_id`) REFERENCES `options_qcm` (`id`);

--
-- Contraintes pour la table `tentatives`
--
ALTER TABLE `tentatives`
  ADD CONSTRAINT `tentatives_ibfk_1` FOREIGN KEY (`examen_id`) REFERENCES `examens` (`id`),
  ADD CONSTRAINT `tentatives_ibfk_2` FOREIGN KEY (`etudiant_id`) REFERENCES `utilisateurs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
