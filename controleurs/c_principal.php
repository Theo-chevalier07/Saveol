<?php
// Définition de la constante de sécurité indispensable pour le projet Saveol
define('SAVEOL', true);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_REQUEST['uc'])) {
    $uc = 'auth';
} else {
    $uc = $_REQUEST['uc'];
}

// Pas connecté : on ne peut voir que la connexion et l'inscription
if (!isset($_SESSION['id']) && $uc != 'auth' && $uc != 'inscription') {
    $uc = 'auth';
}

// Première connexion : il faut d'abord changer le mot de passe provisoire
if (isset($_SESSION['id']) && isset($_SESSION['mdp_a_changer']) && $_SESSION['mdp_a_changer'] == 1 && $uc != 'mot_de_passe' && $uc != 'deco') {
    $uc = 'mot_de_passe';
}

// Pages réservées à certains rôles (uniquement si l'utilisateur est connecté)
if (isset($_SESSION['role'])) {
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
}

// Petit panier affiché dans le menu des clients
if (isset($_SESSION['role']) && $_SESSION['role'] == 'client') {
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = array();
    }
    $miniPanier = getLignesPanier($_SESSION['panier']);
    $totalMiniPanier = getTotalPanier($miniPanier);
}

// Routage des actions
switch($uc) {
    case 'auth': { include "controleurs/c_auth.php"; break;}
    case 'inscription': { include "controleurs/c_inscription.php"; break;}
    case 'deco': { include "controleurs/c_deconnexion.php"; break;}
    case 'catalogue': { include "controleurs/c_catalogue.php"; break;}
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