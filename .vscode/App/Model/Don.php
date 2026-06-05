<?php

require_once __DIR__ . '/../config.php';

class Don
{
    protected $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function save($email, $montant, $userId = null)
    {
        $sql = "INSERT INTO dons (user_id, montant, email)
                VALUES (:user_id, :montant, :email)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':user_id' => $userId,
            ':montant' => $montant,
            ':email' => $email
        ]);

        return $this->pdo->lastInsertId();
    }
}