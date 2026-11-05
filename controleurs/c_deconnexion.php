<?php
if (!defined('SAVEOL')) { http_response_code(403); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    exit('Utilisez le bouton de déconnexion.');
}
if (!jeton_csrf_valide($_POST['csrf'] ?? null)) {
    http_response_code(403);
    exit('Formulaire invalide.');
}
require 'includes/modele/deconnexion.php';
