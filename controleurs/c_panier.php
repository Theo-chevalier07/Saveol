<?php
if (!isset($_REQUEST['action'])){
    $action = 'afficher';
}
else {
    $action = $_REQUEST['action'];
}

// Le panier est gardé dans la session : id_produit => quantité
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = array();
}

$message = '';
$erreur = '';

switch($action) {
    case "ajouter": {
        $idProduit = $_REQUEST['id_produit'];
        $quantite = $_REQUEST['quantite'];
        $produit = getProduit($idProduit);
        if ($produit == false) {
            $erreur = "Ce produit n'existe pas.";
        }
        elseif ($produit['disponible'] == 0) {
            $erreur = "Ce produit est en rupture de stock.";
        }
        elseif ($produit['prix'] == null) {
            $erreur = "Ce produit est sur devis, contactez Savéol pour le commander.";
        }
        elseif (!ctype_digit($quantite) || $quantite < 1) {
            $erreur = "La quantité doit être un nombre entier supérieur à 0.";
        }
        else {
            // Si le produit est déjà dans le panier, on ajoute la quantité
            if (isset($_SESSION['panier'][$idProduit])) {
                $_SESSION['panier'][$idProduit] = $_SESSION['panier'][$idProduit] + $quantite;
            }
            else {
                $_SESSION['panier'][$idProduit] = (int) $quantite;
            }
            $message = "« " . $produit['nom'] . " » a été ajouté au panier.";
        }
        break;
    }

    case "modifier": {
        $idProduit = $_REQUEST['id_produit'];
        $quantite = $_REQUEST['quantite'];
        if (!ctype_digit($quantite)) {
            $erreur = "La quantité doit être un nombre entier.";
        }
        elseif ($quantite == 0) {
            unset($_SESSION['panier'][$idProduit]);
            $message = "Le produit a été retiré du panier.";
        }
        else {
            $_SESSION['panier'][$idProduit] = (int) $quantite;
            $message = "La quantité a été modifiée.";
        }
        break;
    }

    case "retirer_un": {
        // On enlève un seul produit, et la ligne disparaît s'il n'en reste plus
        $idProduit = $_REQUEST['id_produit'];
        if (isset($_SESSION['panier'][$idProduit])) {
            $_SESSION['panier'][$idProduit] = $_SESSION['panier'][$idProduit] - 1;
            if ($_SESSION['panier'][$idProduit] <= 0) {
                unset($_SESSION['panier'][$idProduit]);
            }
            $message = "Une quantité a été retirée.";
        }
        break;
    }

    case "supprimer": {
        unset($_SESSION['panier'][$_REQUEST['id_produit']]);
        $message = "Le produit a été retiré du panier.";
        break;
    }

    case "valider": {
        if (count($_SESSION['panier']) == 0) {
            $erreur = "Votre panier est vide.";
        }
        else {
            $idCommande = creerCommande($_SESSION['id'], $_SESSION['panier']);
            if ($idCommande == 0) {
                $erreur = "La commande n'a pas pu être enregistrée, réessayez.";
            }
            else {
                $_SESSION['panier'] = array();
                $message = "Votre commande n°" . $idCommande . " a bien été enregistrée avec le statut « En attente ».";
            }
        }
        break;
    }
}

// Action faite depuis une autre page ou depuis le petit panier : on reste sur la même page
if ($action != 'valider' && isset($_REQUEST['retour']) && substr($_REQUEST['retour'], 0, 13) == 'index.php?uc=') {
    if ($erreur != '') {
        $_SESSION['message_ajout'] = $erreur;
        $_SESSION['message_ajout_type'] = 'danger';
    }
    else {
        $_SESSION['message_ajout'] = $message;
        $_SESSION['message_ajout_type'] = 'success';
    }
    // Si l'action vient du petit panier, on le rouvre sur la page suivante
    if (isset($_REQUEST['depuis_panier'])) {
        $_SESSION['ouvrir_panier'] = true;
    }
    header("Location: " . $_REQUEST['retour']);
    exit;
}

// On prépare les lignes du panier pour la vue
$lesLignes = getLignesPanier($_SESSION['panier']);
$total = getTotalPanier($lesLignes);

// Le petit panier du menu doit aussi être à jour
$miniPanier = $lesLignes;
$totalMiniPanier = $total;

require "vues/v_panier.php";
?>
