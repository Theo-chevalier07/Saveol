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

## Crédits des photos

Les photos des produits viennent de Wikimedia Commons (licences libres). Elles ont été recadrées.

| Image | Auteur | Licence | Source |
|---|---|---|---|
| tomate_ronde.jpg | Dmitry Makeev | CC BY-SA 4.0 | https://commons.wikimedia.org/wiki/File:Red_tomatoes._img_20.jpg |
| tomate_coeur_de_boeuf.jpg | Dmitry Makeev | CC BY-SA 4.0 | https://commons.wikimedia.org/wiki/File:Red_tomatoes._img_16.jpg |
| tomate_allongee.jpg | Daderot | CC0 | https://commons.wikimedia.org/wiki/File:Tomatoes_(Plum_Roma)_-_Arlingtin,_MA.jpg |
| tomate_jaune.jpg | Ivar Leidus | CC BY-SA 4.0 | https://commons.wikimedia.org/wiki/File:Yellow_cherry_tomatoes.jpg |
| tomate_palette.jpg | Jazz Guy from New Jersey, United States | CC BY 2.0 | https://commons.wikimedia.org/wiki/File:A_Great_Day_in_New_York,_New_York_(3605437356).jpg |
| tomate_grappe.jpg | Eric Polk | CC BY-SA 4.0 | https://commons.wikimedia.org/wiki/File:Grape_tomatoes.jpg |
| cerise_rouge.jpg | Luc Viatour | CC BY-SA 3.0 | https://commons.wikimedia.org/wiki/File:Tomates_cerises_Luc_Viatour.jpg |
| cerise_allongee.jpg | Choo Yut Shing from Singapore, Singapore | CC BY 2.0 | https://commons.wikimedia.org/wiki/File:Roma_Tomatoes_(53512765752).jpg |
| cerise_melange.jpg | sarang | Public domain | https://commons.wikimedia.org/wiki/File:Grape_tomatoes_collection.jpg |
| cerise_grappe.jpg | domdomegg | CC BY 4.0 | https://commons.wikimedia.org/wiki/File:Grape_tomatoes_on_the_vine_at_Ljubljana_Central_Market.JPG |
| fraise_gariguette.jpg | David Monniaux | CC BY-SA 3.0 | https://commons.wikimedia.org/wiki/File:Strawberries_gariguettes_DSC03052.JPG |
| fraise_ciflorette.jpg | domdomegg | CC BY 4.0 | https://commons.wikimedia.org/wiki/File:Strawberries_at_Ljubljana_Central_Market.JPG |
| fraise_charlotte.jpg | INRA DIST from France | CC BY 2.0 | https://commons.wikimedia.org/wiki/File:Fraises_Gariguette_Cl_J_weber05_(23649254966).jpg |
| fraise_mara.jpg | Fraise Label Rouge | CC BY-SA 3.0 | https://commons.wikimedia.org/wiki/File:Barquettes_250g_de_Gariguette_lit%C3%A9es.jpg |
| mache_sachet.jpg | Schwäbin | CC BY-SA 3.0 de | https://commons.wikimedia.org/wiki/File:Ackersalat02.jpg |
| mache_vrac.jpg | voir la source | CC BY-SA 3.0 | https://commons.wikimedia.org/wiki/File:Corn_salad,_spring,_close_up.JPG |
| mache_roquette.jpg | Kévin Feltrin | CC BY 4.0 | https://commons.wikimedia.org/wiki/File:Valerianella_locusta_(M%C3%A2che).jpg |
