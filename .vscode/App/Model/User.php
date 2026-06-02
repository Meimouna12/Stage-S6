<?php

require_once __DIR__ . '/../../Core/model.php';

class User extends Model {
    public function getVisibleUsers($role, $antenneId = null) {
        if ($role === 'admin') {
            $stmt = $this->pdo->query(
                "SELECT u.id, u.nom, u.prenom, u.email, u.role, u.antenne_id, a.nom AS antenne_nom
                 FROM users u
                 LEFT JOIN antennes a ON a.id = u.antenne_id
                 ORDER BY u.role, u.nom, u.prenom"
            );

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        if ($role === 'referent' && $antenneId !== null) {
            $stmt = $this->pdo->prepare(
                "SELECT u.id, u.nom, u.prenom, u.email, u.role, u.antenne_id, a.nom AS antenne_nom
                 FROM users u
                 LEFT JOIN antennes a ON a.id = u.antenne_id
                 WHERE u.role = 'adherent' AND u.antenne_id = ?
                 ORDER BY u.nom, u.prenom"
            );
            $stmt->execute([$antenneId]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        if ($role === 'adherent' && $antenneId !== null) {
            $stmt = $this->pdo->prepare(
                "SELECT u.id, u.nom, u.prenom, u.email, u.role, u.antenne_id, a.nom AS antenne_nom
                 FROM users u
                 LEFT JOIN antennes a ON a.id = u.antenne_id
                 WHERE u.antenne_id = ? AND u.role = 'adherent'
                 ORDER BY u.nom, u.prenom"
            );
            $stmt->execute([$antenneId]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }
}