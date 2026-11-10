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
            $message = "Le produit a été ajouté au panier.";
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

// On prépare les lignes du panier pour la vue
$lesLignes = array();
$total = 0;
foreach ($_SESSION['panier'] as $idProduit => $quantite) {
    $produit = getProduit($idProduit);
    if ($produit != false) {
        $produit['quantite'] = $quantite;
        $produit['sous_total'] = $produit['prix'] * $quantite;
        $total = $total + $produit['sous_total'];
        $lesLignes[] = $produit;
    }
}

// En attendant la fiche produit, on peut ajouter un produit depuis le panier
$lesProduits = getTousLesProduits();

require "vues/v_panier.php";
?>
