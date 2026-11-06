<?php
if (!defined('SAVEOL')) { http_response_code(403); exit; }
require 'includes/modele/gestion_commandes.php';
$action = $_GET['action'] ?? 'liste';
if (!in_array($action, ['liste', 'detail', 'modifier'], true)) {
    http_response_code(404); exit('Action introuvable.');
}
$id = filter_var($_GET['id_commande'] ?? null, FILTER_VALIDATE_INT, ['options'=>['min_range'=>1]]);
if ($action !== 'liste' && !$id) { http_response_code(404); exit('Commande introuvable.'); }
if ($action === 'modifier') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Allow: POST'); http_response_code(405); exit; }
    if (!jeton_csrf_valide($_POST['csrf'] ?? null)) { http_response_code(403); exit('Formulaire expiré.'); }
}
$statut = $_GET['statut'] ?? '';
$statuts = changements_possibles();
if (!is_string($statut) || ($statut !== '' && !isset($statuts[$statut]))) {
    http_response_code(400); exit('Filtre incorrect.');
}
try {
    require 'includes/modele/connexion.php';
    $exec = $bdd->prepare('SELECT role FROM utilisateur WHERE id_utilisateur = ?');
    $exec->execute([$_SESSION['id']]);
    if (!in_array($exec->fetchColumn(), ['admin', 'gestionnaire'], true)) { http_response_code(403); exit('Accès réservé.'); }
    if ($action === 'modifier') {
        if (!changer_statut_commande($bdd, $id, $_POST['ancien'] ?? null, $_POST['nouveau'] ?? null)) {
            http_response_code(409); exit('Changement impossible. Rechargez la commande pour voir son statut.');
        }
        header('Location: index.php?uc=gestion_commandes&action=detail&id_commande=' . $id, true, 303);
        exit;
    }
    $commandes = commandes_gestion($bdd, $statut, $action === 'detail' ? $id : null);
    if ($action === 'detail') {
        if (!$commandes) { http_response_code(404); exit('Commande introuvable.'); }
        $commande = $commandes[0];
        $lignes = lignes_gestion($bdd, $id);
    }
    require 'vues/v_gestion_commandes.php';
} catch (PDOException $e) {
    http_response_code(503); echo 'Les commandes sont momentanément indisponibles.';
}
