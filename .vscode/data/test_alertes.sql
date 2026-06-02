-- Test rapide des alertes
USE association;

-- 1. Vérifier les alertes existantes
SELECT id, alert_code, titre, resolved FROM alerts ORDER BY id DESC LIMIT 20;

-- 2. Vérifier les données de test
SELECT id, user_id, montant, DATEDIFF(NOW(), date_adhesion) as jours_ecoulés FROM adhesions ORDER BY id DESC LIMIT 5;

-- 3. Vérifier l'événement
SELECT id, titre, places_max, (SELECT COUNT(*) FROM inscriptions_evenements WHERE evenement_id=1) as nb_inscrits FROM evenements WHERE id=1;

-- 4. Vérifier les dons
SELECT id, montant, email FROM dons ORDER BY id DESC LIMIT 5;
