# Checklist migration et sauvegarde automatique (Windows + XAMPP)

## Objectif
Conserver une sauvegarde automatique de la base de donnees meme apres changement d'ordinateur.

## 1) Installer l'environnement
- Installer XAMPP (Apache, MySQL, PHP).
- Verifier la presence des executables:
  - C:\xampp\php\php.exe
  - C:\xampp\mysql\bin\mysqldump.exe

## 2) Copier le projet
- Copier le dossier du projet sur le nouveau PC.
- Verifier que les scripts existent:
  - .vscode/scripts/backup_database.php
  - .vscode/scripts/backup_database.bat

## 3) Verifier la configuration base de donnees
Dans .vscode/App/config.php, verifier:
- host
- dbname
- username
- password

Les valeurs doivent correspondre a la configuration MySQL du nouveau PC.

## 4) Verifier les chemins du script de sauvegarde
Dans .vscode/scripts/backup_database.bat, verifier:
- le chemin de php.exe
- le chemin de backup_database.php

Si le projet est dans un autre dossier, adapter ces chemins.

## 5) Test manuel immediat
Executer une sauvegarde manuelle une fois:
- Lancer .vscode/scripts/backup_database.bat

Resultat attendu:
- Un fichier .sql est cree dans .vscode/backups.

## 6) Recreer la tache planifiee (toutes les 6 heures)
Commande Windows:

schtasks /Create /TN AssociationDBBackup /SC HOURLY /MO 6 /TR C:\xampp\htdocs\Projet\.vscode\scripts\backup_database.bat /F

Verification:

schtasks /Query /TN AssociationDBBackup /V /FO LIST

## 7) Controle apres planification
- Verifier la prochaine execution dans le Planificateur de taches.
- Verifier apres l'heure prevue qu'un nouveau fichier .sql apparait dans .vscode/backups.

## Depannage rapide
- Erreur Access denied MySQL:
  - Verifier username/password dans .vscode/App/config.php.
- Erreur chemin introuvable:
  - Verifier les chemins dans .vscode/scripts/backup_database.bat.
- Aucune sauvegarde creee:
  - Lancer manuellement le .bat pour voir le message d'erreur.

## Bonnes pratiques
- Garder plusieurs versions de sauvegarde.
- Copier regulierement le dossier .vscode/backups vers un disque externe ou cloud.
- Tester une restauration de temps en temps pour valider les fichiers de backup.
