<?php
if (!defined('SAVEOL')) { http_response_code(403); exit; }
if (utilisateur_connecte()) {
    header('Location: index.php?uc=' . page_apres_connexion($_SESSION['role']));
    exit;
}
$action = $_GET['action'] ?? 'afficher';
$erreur = '';
$email = '';
if ($action === 'connexion') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Allow: POST');
        http_response_code(405);
        exit('Utilisez le formulaire de connexion.');
    }
    if (!jeton_csrf_valide($_POST['csrf'] ?? null)) {
        http_response_code(403);
        $erreur = 'Le formulaire a expiré. Veuillez réessayer.';
    } else {
        $email = is_string($_POST['email'] ?? null) ? trim($_POST['email']) : '';
        $mdp = $_POST['mot_de_passe'] ?? '';
        if (verifier_identification($email, $mdp)) {
            header('Location: index.php?uc=' . page_apres_connexion($_SESSION['role']), true, 303);
            exit;
        }
        $erreur = 'Email ou mot de passe incorrect.';
    }
} elseif ($action !== 'afficher') {
    http_response_code(404);
    exit('Action introuvable.');
}
require 'vues/v_authentification.php';
