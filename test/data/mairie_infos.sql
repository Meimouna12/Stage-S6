USE association;

CREATE TABLE IF NOT EXISTS mairie_infos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    antenne_id INT NOT NULL,
    mairie_nom VARCHAR(255) NULL,
    maire_nom VARCHAR(255) NULL,
    maire_email VARCHAR(255) NULL,
    mairie_tel VARCHAR(30) NULL,
    cabinet_email VARCHAR(255) NULL,
    adjointe_nom VARCHAR(255) NULL,
    adjointe_fonction VARCHAR(255) NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_mairie_antenne (antenne_id),
    CONSTRAINT fk_mairie_infos_antenne FOREIGN KEY (antenne_id) REFERENCES antennes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET @id_stdenis = (SELECT id FROM antennes WHERE ville = 'Saint-Denis' LIMIT 1);
SET @id_valenton = (SELECT id FROM antennes WHERE ville = 'Valenton' LIMIT 1);
SET @id_villiers = (SELECT id FROM antennes WHERE ville = 'Villiers-sur-Marne' LIMIT 1);
SET @id_paris = (SELECT id FROM antennes WHERE ville = 'Paris' LIMIT 1);
SET @id_gif = (SELECT id FROM antennes WHERE ville = 'Gif-sur-Yvette' LIMIT 1);
SET @id_epinay = (SELECT id FROM antennes WHERE ville = 'Epinay-sur-Seine' LIMIT 1);
SET @id_villetaneuse = (SELECT id FROM antennes WHERE ville = 'Villetaneuse' LIMIT 1);
SET @id_sevran = (SELECT id FROM antennes WHERE ville = 'Sevran' LIMIT 1);

INSERT INTO mairie_infos (
    antenne_id, mairie_nom, maire_nom, maire_email, mairie_tel, cabinet_email, adjointe_nom, adjointe_fonction, notes
) VALUES
(@id_stdenis, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(@id_valenton, 'Mairie de Valenton', 'Metin Yavuz', 'mairie.dg@ville-valenton.fr', NULL, NULL, NULL, NULL, NULL),
(@id_villiers, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(@id_paris, 'Mairie du 18e arrondissement de Paris', 'Eric Lejoindre', NULL, NULL, NULL, 'Nadia Benakli', 'Adjointe chargee du handicap', NULL),
(@id_gif, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(@id_epinay, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(@id_villetaneuse, 'Mairie de Villetaneuse', 'Dieunor Excellent', 'monsieurlemaire@mairie-villetaneuse.fr', '0185573910', 'cabinet@mairie-villetaneuse.fr', NULL, NULL, NULL),
(@id_sevran, 'Mairie de Sevran', 'Stephane Blanchet', NULL, NULL, NULL, NULL, NULL, NULL)
ON DUPLICATE KEY UPDATE
    mairie_nom = VALUES(mairie_nom),
    maire_nom = VALUES(maire_nom),
    maire_email = VALUES(maire_email),
    mairie_tel = VALUES(mairie_tel),
    cabinet_email = VALUES(cabinet_email),
    adjointe_nom = VALUES(adjointe_nom),
    adjointe_fonction = VALUES(adjointe_fonction),
    notes = VALUES(notes);
