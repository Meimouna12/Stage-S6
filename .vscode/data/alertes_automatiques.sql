-- Système d'alertes automatiques pour la base association
-- Cible: MariaDB/MySQL (XAMPP)

USE association;

-- =====================================
-- 1. Configuration des alertes
-- =====================================

CREATE TABLE IF NOT EXISTS alert_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    label VARCHAR(100) NOT NULL,
    description TEXT,
    seuil_value INT DEFAULT NULL,
    enabled TINYINT DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO alert_types (code, label, description, seuil_value, enabled) VALUES
('adhesion_expiration', 'Adhésion expirée', 'Adhésion de plus de 365 jours', 365, 1),
('document_manquant', 'Document manquant', 'Utilisateur sans document requis', NULL, 1),
('evenement_complet', 'Événement complet', 'Événement avec plus de 80% de places utilisées', 80, 1),
('don_eleve', 'Don élevé', 'Don dépassant un seuil élevé', 1000, 1);

-- =====================================
-- 2. Table d\'enregistrement des alertes
-- =====================================

CREATE TABLE IF NOT EXISTS alerts (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    alert_type_id INT NOT NULL,
    alert_code VARCHAR(50) NOT NULL,
    user_id INT NULL,
    entity_type VARCHAR(64) NULL,
    entity_id INT NULL,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    severity ENUM('info','warning','critical') DEFAULT 'warning',
    resolved TINYINT DEFAULT 0,
    resolved_at TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_alert_type_id (alert_type_id),
    KEY idx_user_id (user_id),
    KEY idx_resolved (resolved),
    KEY idx_created_at (created_at),
    CONSTRAINT fk_alerts_type FOREIGN KEY (alert_type_id) REFERENCES alert_types(id) ON DELETE CASCADE,
    CONSTRAINT fk_alerts_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================
-- 3. Procédures de détection d\'alertes
-- =====================================

DROP PROCEDURE IF EXISTS sp_detect_adhesion_expirees;
DELIMITER $$
CREATE PROCEDURE sp_detect_adhesion_expirees()
BEGIN
    DECLARE v_seuil_jours INT;
    DECLARE v_alert_type_id INT;
    
    SELECT seuil_value INTO v_seuil_jours
    FROM alert_types
    WHERE code = 'adhesion_expiration'
    LIMIT 1;
    
    SELECT id INTO v_alert_type_id
    FROM alert_types
    WHERE code = 'adhesion_expiration'
    LIMIT 1;
    
    IF v_alert_type_id IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Type d\'alerte non trouvé';
    END IF;
    
    INSERT INTO alerts (
        alert_type_id,
        alert_code,
        user_id,
        entity_type,
        entity_id,
        titre,
        description,
        severity
    )
    SELECT
        v_alert_type_id,
        'adhesion_expiration',
        a.user_id,
        'adhesions',
        a.id,
        CONCAT('Adhésion expirée pour ', u.prenom, ' ', u.nom),
        CONCAT('Adhésion datant de ', DATE_FORMAT(a.date_adhesion, '%d/%m/%Y'), ' - ', DATEDIFF(NOW(), a.date_adhesion), ' jours'),
        'critical'
    FROM adhesions a
    JOIN users u ON u.id = a.user_id
    WHERE DATEDIFF(NOW(), a.date_adhesion) > v_seuil_jours
      AND a.id NOT IN (
          SELECT entity_id FROM alerts
          WHERE alert_code = 'adhesion_expiration'
            AND entity_id = a.id
            AND resolved = 0
      );
END$$
DELIMITER ;

DROP PROCEDURE IF EXISTS sp_detect_documents_manquants;
DELIMITER $$
CREATE PROCEDURE sp_detect_documents_manquants()
BEGIN
    DECLARE v_alert_type_id INT;
    
    SELECT id INTO v_alert_type_id
    FROM alert_types
    WHERE code = 'document_manquant'
    LIMIT 1;
    
    IF v_alert_type_id IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Type d\'alerte non trouvé';
    END IF;
    
    INSERT INTO alerts (
        alert_type_id,
        alert_code,
        user_id,
        entity_type,
        entity_id,
        titre,
        description,
        severity
    )
    SELECT
        v_alert_type_id,
        'document_manquant',
        u.id,
        'users',
        u.id,
        CONCAT('Document manquant pour ', u.prenom, ' ', u.nom),
        CONCAT('Aucun document fourni par ', u.prenom, ' ', u.nom, ' (', u.email, ')'),
        'warning'
    FROM users u
    WHERE u.role IN ('adherent', 'referent')
      AND u.id NOT IN (
          SELECT DISTINCT user_id FROM documents WHERE user_id IS NOT NULL
      )
      AND u.id NOT IN (
          SELECT user_id FROM alerts
          WHERE alert_code = 'document_manquant'
            AND user_id = u.id
            AND resolved = 0
      );
END$$
DELIMITER ;

DROP PROCEDURE IF EXISTS sp_detect_evenements_complets;
DELIMITER $$
CREATE PROCEDURE sp_detect_evenements_complets()
BEGIN
    DECLARE v_seuil_percent INT;
    DECLARE v_alert_type_id INT;
    
    SELECT seuil_value INTO v_seuil_percent
    FROM alert_types
    WHERE code = 'evenement_complet'
    LIMIT 1;
    
    SELECT id INTO v_alert_type_id
    FROM alert_types
    WHERE code = 'evenement_complet'
    LIMIT 1;
    
    IF v_alert_type_id IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Type d\'alerte non trouvé';
    END IF;
    
    INSERT INTO alerts (
        alert_type_id,
        alert_code,
        entity_type,
        entity_id,
        titre,
        description,
        severity
    )
    SELECT
        v_alert_type_id,
        'evenement_complet',
        'evenements',
        e.id,
        CONCAT('Événement ', e.titre, ' bientôt complet'),
        CONCAT('Occupation: ', ROUND(COUNT(ie.id) * 100 / e.places_max, 1), '% (', COUNT(ie.id), '/', e.places_max, ' places)'),
        'warning'
    FROM evenements e
    LEFT JOIN inscriptions_evenements ie ON ie.evenement_id = e.id
    WHERE e.places_max > 0
      AND (COUNT(ie.id) * 100 / e.places_max) >= v_seuil_percent
      AND e.id NOT IN (
          SELECT entity_id FROM alerts
          WHERE alert_code = 'evenement_complet'
            AND entity_id = e.id
            AND resolved = 0
      )
    GROUP BY e.id;
END$$
DELIMITER ;

DROP PROCEDURE IF EXISTS sp_detect_dons_eleves;
DELIMITER $$
CREATE PROCEDURE sp_detect_dons_eleves()
BEGIN
    DECLARE v_seuil_montant DECIMAL(10,2);
    DECLARE v_alert_type_id INT;
    
    SELECT seuil_value INTO v_seuil_montant
    FROM alert_types
    WHERE code = 'don_eleve'
    LIMIT 1;
    
    SELECT id INTO v_alert_type_id
    FROM alert_types
    WHERE code = 'don_eleve'
    LIMIT 1;
    
    IF v_alert_type_id IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Type d\'alerte non trouvé';
    END IF;
    
    INSERT INTO alerts (
        alert_type_id,
        alert_code,
        user_id,
        entity_type,
        entity_id,
        titre,
        description,
        severity
    )
    SELECT
        v_alert_type_id,
        'don_eleve',
        d.user_id,
        'dons',
        d.id,
        CONCAT('Don élevé reçu: ', FORMAT(d.montant, 2), ' €'),
        CONCAT('Don de ', d.montant, ' € reçu de ', COALESCE(u.prenom, ''), ' ', COALESCE(u.nom, ''), ' (', d.email, ')'),
        'info'
    FROM dons d
    LEFT JOIN users u ON u.id = d.user_id
    WHERE d.montant >= v_seuil_montant
      AND d.id NOT IN (
          SELECT entity_id FROM alerts
          WHERE alert_code = 'don_eleve'
            AND entity_id = d.id
      );
END$$
DELIMITER ;

DROP PROCEDURE IF EXISTS sp_detect_all_alerts;
DELIMITER $$
CREATE PROCEDURE sp_detect_all_alerts()
BEGIN
    CALL sp_detect_adhesion_expirees();
    CALL sp_detect_documents_manquants();
    CALL sp_detect_evenements_complets();
    CALL sp_detect_dons_eleves();
END$$
DELIMITER ;

-- =====================================
-- 4. Procédure pour résoudre une alerte
-- =====================================

DROP PROCEDURE IF EXISTS sp_resolve_alert;
DELIMITER $$
CREATE PROCEDURE sp_resolve_alert(IN p_alert_id BIGINT)
BEGIN
    UPDATE alerts
    SET resolved = 1,
        resolved_at = NOW()
    WHERE id = p_alert_id;
END$$
DELIMITER ;

-- =====================================
-- 5. Procédure de consultation des alertes
-- =====================================

DROP PROCEDURE IF EXISTS sp_list_unresolved_alerts;
DELIMITER $$
CREATE PROCEDURE sp_list_unresolved_alerts()
BEGIN
    SELECT
        a.id,
        at.label AS alert_type,
        a.titre,
        a.description,
        u.prenom,
        u.nom,
        a.severity,
        a.created_at
    FROM alerts a
    JOIN alert_types at ON at.id = a.alert_type_id
    LEFT JOIN users u ON u.id = a.user_id
    WHERE a.resolved = 0
    ORDER BY a.severity DESC, a.created_at DESC;
END$$
DELIMITER ;
