<?php
if (!defined('SAVEOL')) { http_response_code(403); exit; }
require 'includes/modele/compte.php';
$action = $_GET['action'] ?? 'afficher';
if (!is_string($action) || !in_array($action, ['afficher', 'modifier', 'detail'], true)) {
    http_response_code(404); exit('Action introuvable.');
}
if ($action === 'modifier') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Allow: POST'); http_response_code(405); exit('Utilisez le formulaire de votre compte.');
    }
    if (!jeton_csrf_valide($_POST['csrf'] ?? null)) {
        http_response_code(403); exit('Le formulaire a expiré. Revenez à votre compte et réessayez.');
    }
}
$erreur = '';
$message = $_SESSION['compte_message'] ?? '';
unset($_SESSION['compte_message']);
try {
    $compte = consulter_compte();
    if ($action === 'detail') {
        $detail = detail_commande_client($_GET['id_commande'] ?? null);
        if (!$detail) { http_response_code(404); exit('Commande introuvable.'); }
        require 'vues/v_detail_commande.php';
        return;
    }
    if ($action === 'modifier') {
        try {
            modifier_compte($_POST);
            $_SESSION['compte_message'] = 'Vos informations ont été enregistrées.';
            header('Location: index.php?uc=compte', true, 303);
            exit;
        } catch (DomainException $e) {
            http_response_code(422);
            $erreur = $e->getMessage();
            // Conserver la saisie pour permettre au client de la corriger.
            foreach (['nom', 'prenom', 'email', 'telephone', 'adresse'] as $champ) {
                $compte[$champ] = is_string($_POST[$champ] ?? null) ? $_POST[$champ] : '';
            }
        }
    }
    $commandes = historique_commandes();
    require 'vues/v_compte.php';
} catch (PDOException $e) {
    http_response_code(503);
    echo 'Votre compte est momentanément indisponible. Réessayez plus tard.';
} catch (DomainException $e) {
    http_response_code(403);
    echo htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}
