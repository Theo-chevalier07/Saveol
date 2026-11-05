<?php
if (!defined('SAVEOL')) { http_response_code(403); exit; }
$action = $_GET['action'] ?? 'afficher';
switch ($action) {
    case 'afficher':
        $produitsEnAvant = produitsEnAvant();
        require 'vues/v_accueil.php';
        break;
    default:
        http_response_code(404);
        echo 'Action introuvable.';
}
