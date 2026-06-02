# Matrice des permissions par role

## Contexte
Base de donnees: association
Fichier SQL source: .vscode/data/permissions_roles.sql

## Objectif
Definir qui peut voir, modifier et supprimer des utilisateurs, selon le role.

## Regles de base
- admin: acces global.
- referent: acces limite aux adherents de sa propre antenne.
- adherent: acces a son propre profil uniquement.

## Matrice "qui peut faire quoi"

| Action | admin | referent | adherent |
|---|---|---|---|
| Voir tous les utilisateurs | Oui | Non | Non |
| Voir les adherents de son antenne | Oui | Oui | Non |
| Voir son propre profil | Oui | Oui | Oui |
| Modifier un utilisateur quelconque | Oui | Non | Non |
| Modifier un adherent de sa propre antenne | Oui | Oui | Non |
| Modifier son propre profil | Oui | Oui (si c'est lui) | Oui |
| Supprimer un utilisateur | Oui (sauf lui-meme) | Non | Non |

## Correspondance avec les procedures SQL

### 1) sp_list_visible_users(actor_user_id)
Role de la procedure:
- Retourner uniquement les utilisateurs visibles par l'acteur.

Comportement:
- admin: retourne tous les users.
- referent: retourne les users role=adherent de la meme antenne.
- adherent: retourne uniquement sa propre ligne.

Exemple:

```sql
CALL sp_list_visible_users(2);
```

### 2) sp_update_user_as_actor(actor_user_id, target_user_id, nom, prenom, email)
Role de la procedure:
- Mettre a jour un utilisateur cible avec controle d'autorisation.

Comportement:
- admin: peut modifier n'importe qui.
- referent: peut modifier uniquement un adherent de sa meme antenne.
- adherent: peut modifier uniquement son propre profil.

Exemples:

```sql
-- Autorise si meme antenne et cible adherent
CALL sp_update_user_as_actor(2, 3, 'Dupont', 'Lina', 'lina@test.local');

-- Interdit si cible hors antenne ou non adherent
CALL sp_update_user_as_actor(2, 4, 'X', 'Y', 'x@test.local');
```

### 3) sp_delete_user_as_actor(actor_user_id, target_user_id)
Role de la procedure:
- Supprimer un utilisateur avec regle stricte.

Comportement:
- admin: peut supprimer, mais pas son propre compte.
- referent/adherent: suppression interdite.

Exemples:

```sql
-- Interdit pour referent
CALL sp_delete_user_as_actor(2, 3);

-- Autorise pour admin (si 1 != 3)
CALL sp_delete_user_as_actor(1, 3);
```

## Codes d'erreur attendus
En cas d'action non autorisee, les procedures renvoient une erreur SQL:
- SQLSTATE 45000
- Message metier explicite (ex: Interdit: seul admin peut supprimer un utilisateur)

## Bonnes pratiques d'utilisation cote application
- Toujours passer par les procedures SQL pour lire/modifier/supprimer users.
- Eviter les UPDATE/DELETE directs sur users depuis l'application.
- Logger les actions sensibles (qui, quand, quoi) pour audit.

## Historique des actions (audit) => Table logs_actions

La table logs_actions est utilisee pour tracer les actions sensibles sur users.

### Colonnes principales
- actor_user_id: utilisateur qui execute l'action.
- target_user_id: utilisateur cible de l'action.
- action_type: type d'action (CREATE, UPDATE, DELETE).
- table_name: table concernee (ici users).
- record_id: identifiant de l'enregistrement cible.
- old_values: ancienne valeur (JSON texte) avant modification/suppression.
- new_values: nouvelle valeur (JSON texte) apres modification (NULL pour DELETE).
- created_at: date/heure de l'action.

### Quand un log est ecrit
- sp_update_user_as_actor: ecrit un log UPDATE apres validation des permissions.
- sp_delete_user_as_actor: ecrit un log DELETE avant suppression.

### Exemples de consultation

```sql
-- Dernieres actions
SELECT id, actor_user_id, target_user_id, action_type, created_at
FROM logs_actions
ORDER BY id DESC
LIMIT 20;

-- Details old/new values pour une action
SELECT id, action_type, old_values, new_values, created_at
FROM logs_actions
WHERE id = 1;
```
