# Saveol

Site web de la coopérative Savéol (projet commun SLAM x SISR, groupe G1).
Site externe pour les clients (GMS et grossistes) en PHP MVC avec une base MySQL (PDO).

## Installation en local (WAMP)

1. Mettre le dossier `Saveol` dans `www`.
2. Dans phpMyAdmin, onglet Importer : `bdd/creation_tables.sql` puis `bdd/jeu_essai.sql`.
3. Vérifier les identifiants de la base dans `includes/modele/connexion.php`.
4. Ouvrir `http://localhost/Saveol`.

## Comptes de test

| Rôle | Email | Mot de passe provisoire |
|---|---|---|
| Client | client@primeurs-elorn.fr | Client123! |
| Gestionnaire | gestion@saveol.fr | Gestion123! |
| Administrateur | admin@saveol.fr | Admin123! |

Ce sont des mots de passe provisoires : à la première connexion, le site oblige à choisir un nouveau mot de passe.

## Organisation du code

- `index.php` : point d'entrée, lance la session et le contrôleur principal.
- `controleurs/c_principal.php` : choisit le contrôleur selon `uc` et vérifie les droits selon le rôle.
- `controleurs/` : un contrôleur par page, avec un `switch` sur `action`.
- `includes/modele/gestion_bdd.php` : toutes les requêtes SQL.
- `vues/` : l'affichage (HTML + Bootstrap).
- `bdd/` : scripts SQL de création et jeu d'essai.
- `images/produits/` : images des produits.

## Mise en ligne

Fichiers du site déposés avec FileZilla sur le serveur web des SISR, base importée sur leur serveur BDD.
