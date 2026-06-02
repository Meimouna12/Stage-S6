<?php

require_once __DIR__ . '/../../Core/model.php';

class Antenne extends Model {

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM antennes");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM antennes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUpcomingEventsByAntenne($antenneId, $limit = 3) {
        $limit = max(1, (int) $limit);

        $sql = "SELECT
                    e.id,
                    e.titre,
                    e.description,
                    e.date_evenement,
                    e.places_max,
                    COALESCE(SUM(ie.nb_participants), 0) AS inscrits
                FROM evenements e
                LEFT JOIN inscriptions_evenements ie ON ie.evenement_id = e.id
                WHERE e.antenne_id = :antenne_id
                  AND (e.date_evenement IS NULL OR e.date_evenement >= NOW())
                GROUP BY e.id, e.titre, e.description, e.date_evenement, e.places_max
                ORDER BY e.date_evenement ASC
                LIMIT {$limit}";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['antenne_id' => (int) $antenneId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSocialLinksByAntenne($antenneId) {
        $stmt = $this->pdo->prepare(
            "SELECT type, url
             FROM reseaux_sociaux
             WHERE antenne_id = ?
             ORDER BY FIELD(type, 'facebook', 'instagram', 'snapchat', 'tiktok', 'linkedin')"
        );
        $stmt->execute([(int) $antenneId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMairieInfoByAntenne($antenneId) {
        $stmt = $this->pdo->prepare(
            "SELECT mairie_nom, maire_nom, maire_email, mairie_tel, cabinet_email, adjointe_nom, adjointe_fonction, notes
             FROM mairie_infos
             WHERE antenne_id = ?
             LIMIT 1"
        );
        $stmt->execute([(int) $antenneId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}