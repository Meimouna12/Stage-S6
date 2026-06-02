-- Script de création des tables de base pour l'application
CREATE DATABASE IF NOT EXISTS `association` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `association`;

-- Table des adhésions
CREATE TABLE IF NOT EXISTS `adhesions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `prenom` VARCHAR(100) NOT NULL,
  `nom` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `antenne` VARCHAR(150) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Vous pouvez exécuter ce fichier via phpMyAdmin ou en ligne de commande :
-- mysql -u root -p < docs/sql/create_tables.sql
