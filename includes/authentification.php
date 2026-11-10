<?php
if (!defined('SAVEOL')) { http_response_code(403); exit; }

function utilisateur_connecte() {
    return isset($_SESSION['id'], $_SESSION['role'])
        && is_numeric($_SESSION['id']) && $_SESSION['id'] > 0
        && in_array($_SESSION['role'], ['client', 'gestionnaire', 'admin'], true);
}

function page_apres_connexion($role) {
    switch ($role) {
        case 'gestionnaire': return 'gestion_commandes';
        case 'admin': return 'gestion_comptes';
        default: return 'accueil';
    }
}

function acces_autorise($uc) {
    if (!utilisateur_connecte() || !is_string($uc)) { return false; }
    // Les droits sont vérifiés côté serveur, même si un lien est masqué.
    $droits = [
        'accueil' => ['client', 'gestionnaire', 'admin'],
        'deco' => ['client', 'gestionnaire', 'admin'],
        'catalogue' => ['client', 'gestionnaire', 'admin'],
        'produit' => ['client', 'gestionnaire', 'admin'],
        'panier' => ['client'],
        'compte' => ['client'],
        'gestion_commandes' => ['gestionnaire', 'admin'],
        'gestion_catalogue' => ['gestionnaire', 'admin'],
        'gestion_comptes' => ['admin']
    ];
    return isset($droits[$uc]) && in_array($_SESSION['role'], $droits[$uc], true);
}

function jeton_csrf() {
    if (empty($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(32)); }
    return $_SESSION['csrf'];
}

function jeton_csrf_valide($jeton) {
    return is_string($jeton) && isset($_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], $jeton);
}
