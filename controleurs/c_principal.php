<?php
if (!isset($_REQUEST['uc'])) {
    $uc = 'auth';
    }
else {
    $uc = $_REQUEST['uc'];
}

// Pas connecté : on ne peut voir que la connexion et l'inscription
if (!isset($_SESSION['id']) && $uc != 'auth' && $uc != 'inscription') {
    $uc = 'auth';
}

// Première connexion : il faut d'abord changer le mot de passe provisoire
if (isset($_SESSION['id']) && $_SESSION['mdp_a_changer'] == 1 && $uc != 'mot_de_passe' && $uc != 'deco') {
    $uc = 'mot_de_passe';
}

// Pages réservées à certains rôles
$pagesClient = array('panier', 'compte');
$pagesGestionnaire = array('gestion_commandes', 'gestion_catalogue', 'gestion_categories');

if (in_array($uc, $pagesClient) && $_SESSION['role'] != 'client') {
    $uc = 'accueil';
}
if (in_array($uc, $pagesGestionnaire) && $_SESSION['role'] != 'gestionnaire' && $_SESSION['role'] != 'admin') {
    $uc = 'accueil';
}
if ($uc == 'gestion_comptes' && $_SESSION['role'] != 'admin') {
    $uc = 'accueil';
}

switch($uc) {
    case 'auth': { include "controleurs/c_auth.php"; break;}
    case 'deco': { include "controleurs/c_deconnexion.php"; break;}
    case 'accueil': { include "controleurs/c_accueil.php"; break;}
    case 'panier': { include "controleurs/c_panier.php"; break;}
    case 'compte': { include "controleurs/c_compte.php"; break;}
    case 'mot_de_passe': { include "controleurs/c_mot_de_passe.php"; break;}
    case 'gestion_commandes': { include "controleurs/c_gestion_commandes.php"; break;}
    case 'gestion_catalogue': { include "controleurs/c_gestion_catalogue.php"; break;}
    case 'gestion_categories': { include "controleurs/c_gestion_categories.php"; break;}
    default: { include "vues/v_espace.php"; break;}
}
?>
