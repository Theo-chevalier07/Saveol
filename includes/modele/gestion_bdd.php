<?php
if (!defined('SAVEOL')) { http_response_code(403); exit; }

function verifier_identification($email, $mdp) {
    global $bdd;
    if (!is_string($email) || !is_string($mdp) || $mdp === ''
        || !filter_var($email, FILTER_VALIDATE_EMAIL)) { return false; }
    if (!isset($bdd)) { require __DIR__ . '/connexion.php'; }
    $sql = 'SELECT id_utilisateur, nom, prenom, role, mot_de_passe FROM utilisateur WHERE email = ?';
    $exec = $bdd->prepare($sql);
    $exec->execute([$email]);
    $ligne = $exec->fetch(PDO::FETCH_ASSOC);
    if (!$ligne || !in_array($ligne['role'], ['client', 'gestionnaire', 'admin'], true)
        || !hash_equals($ligne['mot_de_passe'], hash('sha256', $mdp))) { return false; }

    // Renouveler l'identifiant évite de conserver une session fixée avant connexion.
    session_regenerate_id(true);
    $_SESSION = [
        'id' => (int) $ligne['id_utilisateur'],
        'role' => $ligne['role'],
        'nom' => $ligne['nom'],
        'prenom' => $ligne['prenom']
    ];
    return true;
}

function produitsEnAvant() {
    global $bdd;
    if (!isset($bdd)) { require __DIR__ . '/connexion.php'; }
    $sql = 'SELECT nom, description, prix, image FROM produit WHERE mis_en_avant = 1';
    $exec = $bdd->prepare($sql);
    $exec->execute();
    return $exec->fetchAll(PDO::FETCH_ASSOC);
}
