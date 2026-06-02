USE association;

-- 1) Mise a jour des antennes existantes
UPDATE antennes
SET nom = 'Antenne-Saint-Denis',
    ville = 'Saint-Denis',
    description = 'All Inclusive est une association a but non lucratif nee en 2018 a l initiative de parents d enfants autistes. Missions: inclusion sportive des enfants TSA, accompagnement administratif des familles (dont MDPH), accompagnement vers les loisirs. Actions: accueil des enfants pour des temps de repit, galas associatifs, interventions aupres des ecoles et des institutions, suivi de familles. Facteurs de reussite: equipe benevole experimentee, approche adaptee a chaque enfant. Perspectives: developper un service medico-social pluridisciplinaire et renforcer l accueil local.',
    responsable = 'Lynda Fekiri',
    telephone = '0659187892',
    email = 'allinclusive.autisme@gmail.com',
    adresse = '16 rue Jean Pierre Timbaud, 93200 Saint-Denis (d domiciliation)',
    is_new = 0
WHERE id = 1;

UPDATE antennes
SET nom = 'Antenne-Valenton',
    ville = 'Valenton',
    description = 'Antenne locale All Inclusive dans le Val-de-Marne. Mission: inclusion et accompagnement des familles concernees par l autisme, orientation locale et actions de sensibilisation.',
    responsable = 'Mouna Goubet',
    telephone = '0767260460',
    email = 'allinclusive.autismevalenton@gmail.com',
    adresse = '7 rue du Colonel Fabien, 94460 Valenton',
    is_new = 0
WHERE id = 2;

UPDATE antennes
SET nom = 'Antenne-Villiers-sur-Marne',
    ville = 'Villiers-sur-Marne',
    description = 'Nouvelle implantation All Inclusive portee par Emeline Maussion. Objectifs: inclusion sociale des enfants autistes, scolarisation adaptee, accompagnement des familles, sensibilisation a l accessibilite. Valeurs: inclusion, entraide, solidarite, tolerance, accessibilite. Specificite: approche de terrain basee sur l experience vecue et forte proximite avec les familles.',
    responsable = 'Emeline Maussion',
    telephone = '0651149092',
    email = 'allinclusive.villiers.sur.marne@gmail.com',
    adresse = '111 rue du General de Gaulle, 94350 Villiers-sur-Marne',
    is_new = 1
WHERE id = 3;

-- 2) Ajout des nouvelles antennes si absentes
INSERT INTO antennes (nom, ville, description, responsable, telephone, email, adresse, is_new)
SELECT 'Antenne-Paris', 'Paris',
       'Antenne parisienne All Inclusive. Actions d accompagnement et d inclusion pour les familles et les enfants concernes par les troubles du spectre autistique.',
       'Coumba Doucoure / Djohra Khaldi',
       '0665314157',
       'allinclusive.autismeparis@gmail.com',
       '153 rue des Poissonniers, 75018 Paris',
       0
WHERE NOT EXISTS (SELECT 1 FROM antennes WHERE ville = 'Paris');

INSERT INTO antennes (nom, ville, description, responsable, telephone, email, adresse, is_new)
SELECT 'Antenne-Gif-sur-Yvette', 'Gif-sur-Yvette',
       'Antenne All Inclusive agissant pour l inclusion des personnes atteintes de troubles neurodeveloppementaux (autisme, TDAH, dys). Missions: inclusion sociale et culturelle, sensibilisation au handicap, accompagnement des familles, acces aux loisirs et au sport.',
       'Equipe All Inclusive Gif',
       '0652079229',
       'allinclusive.autismegif@gmail.com',
       '1 place du Marche Neuf, 91190 Gif-sur-Yvette',
       1
WHERE NOT EXISTS (SELECT 1 FROM antennes WHERE ville = 'Gif-sur-Yvette');

INSERT INTO antennes (nom, ville, description, responsable, telephone, email, adresse, is_new)
SELECT 'Antenne-Epinay-sur-Seine', 'Epinay-sur-Seine',
       'Antenne locale All Inclusive a Epinay-sur-Seine. Role local: soutien aux familles, orientation vers les dispositifs existants, inclusion sociale.',
       'Oualida Challal / Aurore Fitoussi',
       '0695955450',
       'allinclusive.autismeepinay@gmail.com',
       '93800 Epinay-sur-Seine',
       1
WHERE NOT EXISTS (SELECT 1 FROM antennes WHERE ville = 'Epinay-sur-Seine');

INSERT INTO antennes (nom, ville, description, responsable, telephone, email, adresse, is_new)
SELECT 'Antenne-Villetaneuse', 'Villetaneuse',
       'Antenne All Inclusive active autour de l inclusion sociale et du handicap. Projet phare: jardin partage inclusif avec ateliers de jardinage et accompagnement d enfants pour l autonomie, la socialisation et le bien-etre.',
       'Kahina Chekkal',
       '0753763487',
       'allinclusive.villetaneuse93@gmail.com',
       '8 rue Gabriel Peri, 93430 Villetaneuse',
       1
WHERE NOT EXISTS (SELECT 1 FROM antennes WHERE ville = 'Villetaneuse');

INSERT INTO antennes (nom, ville, description, responsable, telephone, email, adresse, is_new)
SELECT 'Antenne-Sevran', 'Sevran',
       'Antenne All Inclusive a Sevran pour l accompagnement des familles et l inclusion des enfants autistes via des actions locales.',
       'Sonia Soukal',
       '0652847103',
       'allinclusive.autisme.sevran93270@gmail.com',
       '9 allee Raymond Queneau, 93270 Sevran',
       1
WHERE NOT EXISTS (SELECT 1 FROM antennes WHERE ville = 'Sevran');

-- 3) Reseaux sociaux
SET @id_stdenis = (SELECT id FROM antennes WHERE ville = 'Saint-Denis' LIMIT 1);
SET @id_valenton = (SELECT id FROM antennes WHERE ville = 'Valenton' LIMIT 1);
SET @id_villiers = (SELECT id FROM antennes WHERE ville = 'Villiers-sur-Marne' LIMIT 1);
SET @id_paris = (SELECT id FROM antennes WHERE ville = 'Paris' LIMIT 1);
SET @id_gif = (SELECT id FROM antennes WHERE ville = 'Gif-sur-Yvette' LIMIT 1);
SET @id_epinay = (SELECT id FROM antennes WHERE ville = 'Epinay-sur-Seine' LIMIT 1);
SET @id_villetaneuse = (SELECT id FROM antennes WHERE ville = 'Villetaneuse' LIMIT 1);
SET @id_sevran = (SELECT id FROM antennes WHERE ville = 'Sevran' LIMIT 1);

DELETE FROM reseaux_sociaux WHERE antenne_id IN (@id_stdenis, @id_valenton, @id_villiers, @id_paris, @id_gif, @id_epinay, @id_villetaneuse, @id_sevran);

INSERT INTO reseaux_sociaux (antenne_id, type, url) VALUES
(@id_stdenis, 'facebook', 'https://www.facebook.com/share/17t6TUQB9m/?mibextid=wwXIfr'),
(@id_stdenis, 'instagram', 'https://www.instagram.com/all_inclusive_93'),
(@id_stdenis, 'snapchat', 'https://snapchat.com/t/gjPVpAJu'),
(@id_stdenis, 'linkedin', 'https://www.linkedin.com/in/lynda-fekiri-71894996'),
(@id_stdenis, 'tiktok', 'https://www.tiktok.com/@allinclusiveregional?_t=8sEOUqArFeo&_r=1'),

(@id_valenton, 'facebook', 'https://www.facebook.com/share/1CX1H5tXMg/?mibextid=wwXIfr'),
(@id_valenton, 'instagram', 'https://www.instagram.com/all_inclusive_valenton?igsh=cGFkM2ZuNmRvZ3lo'),

(@id_villiers, 'facebook', 'https://www.facebook.com/search/top?q=all%20inclusive%20villiers%20sur%20marne'),
(@id_villiers, 'instagram', 'https://www.instagram.com/'),

(@id_paris, 'facebook', 'https://www.facebook.com/share/179TjhAS3G/'),
(@id_paris, 'snapchat', 'https://www.snapchat.com/add/allinclusiv75?share_id=-6xKZwbVfvg&locale=fr-FR'),
(@id_paris, 'tiktok', 'https://www.tiktok.com/@all.inclusive.par?_r=1&_t=ZN-95CLQTkfJFm'),

(@id_epinay, 'facebook', 'https://www.facebook.com/share/g/18HLxH9S5h/?mibextid=wwXIfr'),

(@id_villetaneuse, 'facebook', 'https://www.facebook.com/share/1EeMd6NQ8A/'),
(@id_villetaneuse, 'instagram', 'https://www.instagram.com/allinclusivevilletaneuse?utm_source=qr&igsh=MXBjajUwbDh1Y2d6Mw==');
