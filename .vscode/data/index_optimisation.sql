-- Optimisation des index pour la base association
-- Ajoute uniquement les index manquants

USE association;

DROP PROCEDURE IF EXISTS sp_add_index_if_missing;
DELIMITER $$
CREATE PROCEDURE sp_add_index_if_missing(
    IN p_table_name VARCHAR(64),
    IN p_index_name VARCHAR(64),
    IN p_index_sql TEXT
)
BEGIN
    DECLARE v_count INT DEFAULT 0;

    SELECT COUNT(*) INTO v_count
    FROM information_schema.statistics
    WHERE table_schema = DATABASE()
      AND table_name = p_table_name
      AND index_name = p_index_name;

    IF v_count = 0 THEN
        SET @sql_stmt = p_index_sql;
        PREPARE stmt FROM @sql_stmt;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$
DELIMITER ;

-- users: filtres par role + antenne
CALL sp_add_index_if_missing(
    'users',
    'idx_users_role_antenne',
    'CREATE INDEX idx_users_role_antenne ON users(role, antenne_id)'
);

-- adhesions: controles d expiration et historique utilisateur
CALL sp_add_index_if_missing(
    'adhesions',
    'idx_adhesions_date_adhesion',
    'CREATE INDEX idx_adhesions_date_adhesion ON adhesions(date_adhesion)'
);
CALL sp_add_index_if_missing(
    'adhesions',
    'idx_adhesions_user_date',
    'CREATE INDEX idx_adhesions_user_date ON adhesions(user_id, date_adhesion)'
);

-- documents: controles document manquant et dernier upload
CALL sp_add_index_if_missing(
    'documents',
    'idx_documents_user_uploaded',
    'CREATE INDEX idx_documents_user_uploaded ON documents(user_id, uploaded_at)'
);

-- evenements: recherche par date et antenne
CALL sp_add_index_if_missing(
    'evenements',
    'idx_evenements_date_evenement',
    'CREATE INDEX idx_evenements_date_evenement ON evenements(date_evenement)'
);
CALL sp_add_index_if_missing(
    'evenements',
    'idx_evenements_antenne_date',
    'CREATE INDEX idx_evenements_antenne_date ON evenements(antenne_id, date_evenement)'
);

-- inscriptions_evenements: occupation par evenement
CALL sp_add_index_if_missing(
    'inscriptions_evenements',
    'idx_inscriptions_evenement_created',
    'CREATE INDEX idx_inscriptions_evenement_created ON inscriptions_evenements(evenement_id, created_at)'
);

-- dons: detection dons eleves et suivi temporel
CALL sp_add_index_if_missing(
    'dons',
    'idx_dons_montant_date',
    'CREATE INDEX idx_dons_montant_date ON dons(montant, date_don)'
);
CALL sp_add_index_if_missing(
    'dons',
    'idx_dons_user_date',
    'CREATE INDEX idx_dons_user_date ON dons(user_id, date_don)'
);

-- alerts: listes d alertes rapides
CALL sp_add_index_if_missing(
    'alerts',
    'idx_alerts_code_resolved',
    'CREATE INDEX idx_alerts_code_resolved ON alerts(alert_code, resolved)'
);
CALL sp_add_index_if_missing(
    'alerts',
    'idx_alerts_entity',
    'CREATE INDEX idx_alerts_entity ON alerts(entity_type, entity_id)'
);
CALL sp_add_index_if_missing(
    'alerts',
    'idx_alerts_resolved_created',
    'CREATE INDEX idx_alerts_resolved_created ON alerts(resolved, created_at)'
);

DROP PROCEDURE IF EXISTS sp_add_index_if_missing;
