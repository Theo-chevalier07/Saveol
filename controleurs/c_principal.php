<?php
if (!defined('SAVEOL')) { http_response_code(403); exit; }
header('Cache-Control: no-store');
$uc = $_GET['uc'] ?? (utilisateur_connecte() ? page_apres_connexion($_SESSION['role']) : 'auth');
if (!is_string($uc)) { http_response_code(400); exit('Page incorrecte.'); }

if ($uc === 'auth') {
    require __DIR__ . '/c_auth.php';
    return;
}
if (!utilisateur_connecte()) {
    header('Location: index.php?uc=auth');
    exit;
}
if (!acces_autorise($uc)) {
    header('Location: index.php?uc=' . page_apres_connexion($_SESSION['role']));
    exit;
}
switch ($uc) {
    case 'deco': require __DIR__ . '/c_deconnexion.php'; break;
    case 'accueil': require __DIR__ . '/c_accueil.php'; break;
    case 'panier': require __DIR__ . '/c_panier.php'; break;
    case 'compte': require __DIR__ . '/c_compte.php'; break;
    case 'gestion_commandes': require __DIR__ . '/c_gestion_commandes.php'; break;
    case 'gestion_catalogue': require __DIR__ . '/c_gestion_catalogue.php'; break;
    default:
        // Ces pages seront remplacées lors des prochains lots du binôme.
        $titres = [
            'catalogue' => 'Catalogue', 'produit' => 'Fiche produit',
            'panier' => 'Mon panier', 'compte' => 'Mon compte',
            'gestion_commandes' => 'Gestion des commandes',
            'gestion_catalogue' => 'Gestion du catalogue',
            'gestion_comptes' => 'Gestion des comptes'
        ];
        $titre = $titres[$uc];
        require 'vues/v_espace.php';
}
