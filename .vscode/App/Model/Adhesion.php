<?php

require_once __DIR__ . '/../config.php';

class Adhesion {
    protected $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
        // Ensure table exists (helpful for environnements de dev sans migration)
        $sql = "CREATE TABLE IF NOT EXISTS `adhesions` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `prenom` VARCHAR(100) NOT NULL,
            `nom` VARCHAR(100) NOT NULL,
            `email` VARCHAR(150) NOT NULL,
            `antenne` VARCHAR(150) DEFAULT NULL,
            `montant` DECIMAL(10,2) DEFAULT NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            INDEX (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        try {
            $this->pdo->exec($sql);
        } catch (Exception $e) {
            // If creation fails, continue — higher layers will handle errors
        }
    }

    public function save($prenom, $nom, $email, $antenne = null, $montant = null) {
        $sql = "INSERT INTO adhesions (prenom, nom, email, antenne, montant) VALUES (:prenom, :nom, :email, :antenne, :montant)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':prenom' => $prenom,
            ':nom' => $nom,
            ':email' => $email,
            ':antenne' => $antenne,
            ':montant' => $montant,
        ]);
        return $this->pdo->lastInsertId();
    }
}
