-- Permissions coherentes par role pour la base association
-- Cible: MariaDB/MySQL (XAMPP)

USE association;

CREATE TABLE IF NOT EXISTS role_permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM('admin','referent','adherent') NOT NULL,
    permission_key VARCHAR(100) NOT NULL,
    UNIQUE KEY uq_role_permission (role, permission_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT IGNORE INTO role_permissions (role, permission_key) VALUES
('admin', 'user.view.all'),
('admin', 'user.create'),
('admin', 'user.update.all'),
('admin', 'user.delete.all'),
('referent', 'user.view.antenne'),
('referent', 'user.update.antenne.adherent'),
('adherent', 'user.view.self'),
('adherent', 'user.update.self');

CREATE TABLE IF NOT EXISTS logs_actions (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    actor_user_id INT NOT NULL,
    target_user_id INT NULL,
    action_type ENUM('CREATE','UPDATE','DELETE') NOT NULL,
    table_name VARCHAR(64) NOT NULL,
    record_id INT NULL,
    old_values LONGTEXT NULL,
    new_values LONGTEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_logs_actor_user_id (actor_user_id),
    KEY idx_logs_target_user_id (target_user_id),
    KEY idx_logs_action_type (action_type),
    KEY idx_logs_created_at (created_at),
    CONSTRAINT fk_logs_actions_actor_user FOREIGN KEY (actor_user_id) REFERENCES users(id) ON DELETE RESTRICT,
    CONSTRAINT fk_logs_actions_target_user FOREIGN KEY (target_user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

DROP FUNCTION IF EXISTS user_has_permission;
DELIMITER $$
CREATE FUNCTION user_has_permission(p_user_id INT, p_permission_key VARCHAR(100))
RETURNS TINYINT
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE v_role VARCHAR(20);
    DECLARE v_count INT DEFAULT 0;

    SELECT role INTO v_role
    FROM users
    WHERE id = p_user_id
    LIMIT 1;

    IF v_role IS NULL THEN
        RETURN 0;
    END IF;

    SELECT COUNT(*) INTO v_count
    FROM role_permissions rp
    WHERE rp.role = v_role
      AND rp.permission_key = p_permission_key;

    RETURN IF(v_count > 0, 1, 0);
END$$
DELIMITER ;

DROP PROCEDURE IF EXISTS sp_list_visible_users;
DELIMITER $$
CREATE PROCEDURE sp_list_visible_users(IN p_actor_user_id INT)
BEGIN
    DECLARE v_actor_role VARCHAR(20);
    DECLARE v_actor_antenne_id INT;

    SELECT role, antenne_id
      INTO v_actor_role, v_actor_antenne_id
    FROM users
    WHERE id = p_actor_user_id
    LIMIT 1;

    IF v_actor_role IS NULL THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Acteur introuvable';
    END IF;

    IF v_actor_role = 'admin' THEN
        SELECT u.id, u.nom, u.prenom, u.email, u.role, u.antenne_id
        FROM users u
        ORDER BY u.role, u.nom, u.prenom;

    ELSEIF v_actor_role = 'referent' THEN
        SELECT u.id, u.nom, u.prenom, u.email, u.role, u.antenne_id
        FROM users u
        WHERE u.role = 'adherent'
          AND u.antenne_id = v_actor_antenne_id
        ORDER BY u.nom, u.prenom;

    ELSE
        SELECT u.id, u.nom, u.prenom, u.email, u.role, u.antenne_id
        FROM users u
        WHERE u.id = p_actor_user_id
        LIMIT 1;
    END IF;
END$$
DELIMITER ;

DROP PROCEDURE IF EXISTS sp_update_user_as_actor;
DELIMITER $$
CREATE PROCEDURE sp_update_user_as_actor(
    IN p_actor_user_id INT,
    IN p_target_user_id INT,
    IN p_nom VARCHAR(100),
    IN p_prenom VARCHAR(100),
    IN p_email VARCHAR(255)
)
BEGIN
    DECLARE v_actor_role VARCHAR(20);
    DECLARE v_actor_antenne_id INT;
    DECLARE v_target_role VARCHAR(20);
    DECLARE v_target_antenne_id INT;
    DECLARE v_old_nom VARCHAR(100);
    DECLARE v_old_prenom VARCHAR(100);
    DECLARE v_old_email VARCHAR(255);
    DECLARE v_old_antenne_id INT;
    DECLARE v_old_values LONGTEXT;
    DECLARE v_new_values LONGTEXT;

    SELECT role, antenne_id
      INTO v_actor_role, v_actor_antenne_id
    FROM users
    WHERE id = p_actor_user_id
    LIMIT 1;

        SELECT role, antenne_id, nom, prenom, email
            INTO v_target_role, v_target_antenne_id, v_old_nom, v_old_prenom, v_old_email
    FROM users
    WHERE id = p_target_user_id
    LIMIT 1;

        SET v_old_antenne_id = v_target_antenne_id;

    IF v_actor_role IS NULL OR v_target_role IS NULL THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Acteur ou cible introuvable';
    END IF;

    IF v_actor_role = 'admin' THEN
        UPDATE users
        SET nom = p_nom,
            prenom = p_prenom,
            email = p_email
        WHERE id = p_target_user_id;

    ELSEIF v_actor_role = 'referent' THEN
        IF v_target_role <> 'adherent' OR v_target_antenne_id <> v_actor_antenne_id THEN
            SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'Interdit: un referent ne peut modifier que les adherents de son antenne';
        END IF;

        UPDATE users
        SET nom = p_nom,
            prenom = p_prenom,
            email = p_email
        WHERE id = p_target_user_id;

    ELSEIF v_actor_role = 'adherent' THEN
        IF p_actor_user_id <> p_target_user_id THEN
            SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'Interdit: un adherent ne peut modifier que son profil';
        END IF;

        UPDATE users
        SET nom = p_nom,
            prenom = p_prenom,
            email = p_email
        WHERE id = p_target_user_id;

    ELSE
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Role non autorise';
    END IF;

    SET v_old_values = JSON_OBJECT(
        'nom', v_old_nom,
        'prenom', v_old_prenom,
        'email', v_old_email,
        'role', v_target_role,
        'antenne_id', v_old_antenne_id
    );

    SET v_new_values = JSON_OBJECT(
        'nom', p_nom,
        'prenom', p_prenom,
        'email', p_email,
        'role', v_target_role,
        'antenne_id', v_old_antenne_id
    );

    INSERT INTO logs_actions (
        actor_user_id,
        target_user_id,
        action_type,
        table_name,
        record_id,
        old_values,
        new_values
    ) VALUES (
        p_actor_user_id,
        p_target_user_id,
        'UPDATE',
        'users',
        p_target_user_id,
        v_old_values,
        v_new_values
    );
END$$
DELIMITER ;

DROP PROCEDURE IF EXISTS sp_delete_user_as_actor;
DELIMITER $$
CREATE PROCEDURE sp_delete_user_as_actor(
    IN p_actor_user_id INT,
    IN p_target_user_id INT
)
BEGIN
    DECLARE v_actor_role VARCHAR(20);
    DECLARE v_target_role VARCHAR(20);
    DECLARE v_target_nom VARCHAR(100);
    DECLARE v_target_prenom VARCHAR(100);
    DECLARE v_target_email VARCHAR(255);
    DECLARE v_target_antenne_id INT;
    DECLARE v_old_values LONGTEXT;

    SELECT role INTO v_actor_role
    FROM users
    WHERE id = p_actor_user_id
    LIMIT 1;

    IF v_actor_role IS NULL THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Acteur introuvable';
    END IF;

    IF v_actor_role <> 'admin' THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Interdit: seul admin peut supprimer un utilisateur';
    END IF;

    IF p_actor_user_id = p_target_user_id THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Interdit: un admin ne peut pas se supprimer lui-meme';
    END IF;

    SELECT role, nom, prenom, email, antenne_id
      INTO v_target_role, v_target_nom, v_target_prenom, v_target_email, v_target_antenne_id
    FROM users
    WHERE id = p_target_user_id
    LIMIT 1;

    IF v_target_role IS NULL THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Utilisateur cible introuvable';
    END IF;

    SET v_old_values = JSON_OBJECT(
        'nom', v_target_nom,
        'prenom', v_target_prenom,
        'email', v_target_email,
        'role', v_target_role,
        'antenne_id', v_target_antenne_id
    );

    INSERT INTO logs_actions (
        actor_user_id,
        target_user_id,
        action_type,
        table_name,
        record_id,
        old_values,
        new_values
    ) VALUES (
        p_actor_user_id,
        p_target_user_id,
        'DELETE',
        'users',
        p_target_user_id,
        v_old_values,
        NULL
    );

    DELETE FROM users WHERE id = p_target_user_id;
END$$
DELIMITER ;
