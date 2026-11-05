<?php
if (!defined('SAVEOL')) { http_response_code(403); exit; }
require 'includes/modele/panier.php';
$action = $_GET['action'] ?? 'afficher';
if (!is_string($action) || !in_array($action, ['afficher', 'ajouter', 'modifier', 'supprimer', 'valider'], true)) {
    http_response_code(404);
    exit('Action introuvable.');
}
if ($action !== 'afficher') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Allow: POST'); http_response_code(405);
        exit('Utilisez les boutons du panier.');
    }
    if (!jeton_csrf_valide($_POST['csrf'] ?? null)) {
        http_response_code(403);
        exit('Le formulaire a expiré. Revenez au panier et réessayez.');
    }
    try {
        switch ($action) {
            case 'ajouter':
                ajouter_au_panier($_POST['id_produit'] ?? null, $_POST['quantite'] ?? null);
                $_SESSION['panier_message'] = 'Le produit a été ajouté au panier.';
                break;
            case 'modifier':
                modifier_quantite_panier($_POST['id_produit'] ?? null, $_POST['quantite'] ?? null);
                $_SESSION['panier_message'] = 'La quantité a été modifiée.';
                break;
            case 'supprimer':
                supprimer_du_panier($_POST['id_produit'] ?? null);
                $_SESSION['panier_message'] = 'Le produit a été retiré du panier.';
                break;
            case 'valider':
                $idCommande = valider_commande();
                $_SESSION['panier_message'] = 'Votre commande n°' . $idCommande . ' a été enregistrée avec le statut « En attente ».';
                break;
        }
    } catch (PDOException $e) {
        $_SESSION['panier_erreur'] = 'La commande n’a pas pu être enregistrée. Votre panier est conservé. Réessayez plus tard.';
    } catch (DomainException $e) {
        $_SESSION['panier_erreur'] = $e->getMessage();
    }
    // Recharger la page ne renvoie pas le formulaire et ne crée pas de doublon.
    header('Location: index.php?uc=panier', true, 303);
    exit;
}
$message = $_SESSION['panier_message'] ?? '';
$erreur = $_SESSION['panier_erreur'] ?? '';
unset($_SESSION['panier_message'], $_SESSION['panier_erreur']);
try {
    $recap = recapitulatif_panier();
    $produits = produits_a_ajouter();
} catch (PDOException $e) {
    http_response_code(503);
    exit('Le panier est momentanément indisponible. Vos articles sont conservés.');
}
require 'vues/v_panier.php';
