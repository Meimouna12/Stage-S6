# Guide de test des alertes automatiques

## Qu'est-ce que tu verras
Le système génère automatiquement 4 types d'alertes :
1. **Adhésion expirée** : plus de 365 jours sans renouvellement
2. **Document manquant** : utilisateur sans aucun document
3. **Événement complet** : 80% ou plus des places réservées
4. **Don élevé** : don >= 1000 €

## Étapes pour tester dans phpMyAdmin

### 1. Accède à phpMyAdmin
- URL: http://localhost:8000/phpmyadmin
- Base: association
- Tab SQL

### 2. Crée une adhésion expirée (alerte critique)
Exécute :
```sql
INSERT INTO adhesions (user_id, montant, date_adhesion)
VALUES (1, 50, DATE_SUB(NOW(), INTERVAL 400 DAY));
```

Vérification:
```sql
SELECT id, user_id, DATEDIFF(NOW(), date_adhesion) as jours_ecoulés
FROM adhesions
ORDER BY id DESC LIMIT 3;
```

Résultat attendu: une ligne avec environ 400 jours

### 3. Crée un événement presque complet
Exécute :
```sql
INSERT INTO evenements (titre, description, date_evenement, places_max, antenne_id)
VALUES ('Test Alerte Complet', 'Description test', DATE_ADD(NOW(), INTERVAL 10 DAY), 10, 1);

SELECT LAST_INSERT_ID() as event_id;
```

Note l'ID du dernier événement (exemple: 2)

Ensuite, inscris 8 participants (80%):
```sql
INSERT INTO inscriptions_evenements (user_id, evenement_id, nb_participants)
VALUES 
(1, 2, 1),
(2, 2, 1),
(4, 2, 1),
(5, 2, 1),
(1, 2, 1),
(2, 2, 1),
(4, 2, 1),
(5, 2, 1);
```

### 4. Crée un don élevé
Exécute:
```sql
INSERT INTO dons (user_id, montant, email, date_don)
VALUES (1, 2500, 'gros.donateur@test.local', NOW());
```

Vérification:
```sql
SELECT id, montant, email
FROM dons
ORDER BY id DESC LIMIT 3;
```

Résultat attendu: une ligne avec 2500 €

### 5. Lance la détection d'alertes
Exécute :
```sql
CALL sp_detect_all_alerts();
```

Pas de résultat = c'est normal, la procédure a juste créé les alertes

### 6. Consulte les alertes générées
Exécute :
```sql
SELECT id, alert_code, titre, description, severity, resolved, created_at
FROM alerts
WHERE resolved = 0
ORDER BY id DESC;
```

Résultat attendu: 4+ alertes non résolues

Exemples:
- Adhésion expirée pour Test Admin (400 jours)
- Document manquant pour DirectTestOK Adh
- Document manquant pour A2 Adh
- Document manquant pour SaintDenis Ref
- Évenement complet pour Test Alerte Complet (80%)
- Don élevé reçu: 2500.00 €

### 7. Résoudre une alerte
Pour marquer une alerte comme traitée:
```sql
CALL sp_resolve_alert(1);
```

Vérifie:
```sql
SELECT id, titre, resolved, resolved_at FROM alerts WHERE id = 1;
```

Résultat attendu: resolved=1 et resolved_at rempli

### 8. Configurer les seuils
Tu peux changer les seuils d'alerte:
```sql
UPDATE alert_types
SET seuil_value = 180
WHERE code = 'adhesion_expiration';

UPDATE alert_types
SET seuil_value = 500
WHERE code = 'don_eleve';
```

## Résumé des procédures disponibles
- `sp_detect_all_alerts()` : lance toutes les détections
- `sp_detect_adhesion_expirees()` : détecte adhésions > 365 jours
- `sp_detect_documents_manquants()` : détecte utilisateurs sans documents
- `sp_detect_evenements_complets()` : détecte événements >= 80% pleins
- `sp_detect_dons_eleves()` : détecte dons >= 1000 €
- `sp_list_unresolved_alerts()` : affiche alertes non résolues
- `sp_resolve_alert(id)` : marquer alerte comme traitée
