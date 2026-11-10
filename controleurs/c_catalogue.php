<?php
// Vérification de la constante de sécurité
if (!defined('SAVEOL')) {
    exit;
}

if (!isset($_REQUEST['action'])) {
    $action = 'voir_catalogue';
} else {
    $action = $_REQUEST['action'];
}

// Récupération des catégories pour le menu de navigation / filtres
$lesCategories = getCategories();

switch ($action) {
    case 'voir_catalogue': {
        $lesProduits = getTousLesProduits();
        $titreCatalogue = "Nos produits frais";
        break;
    }
    
    case 'par_categorie': {
        $idCategorie = $_REQUEST['id_categorie'];
        $lesProduits = getProduitsParCategorie($idCategorie);
        // Optionnel : récupérer le nom de la catégorie courante pour l'affichage
        $titreCatalogue = "Produits de la catégorie";
        break;
    }

    case 'detail_produit': {
        $idProduit = $_REQUEST['id_produit'];
        $produit = getProduit($idProduit);
        require "vues/v_detail_produit.php";
        exit;
    }

    default: {
        $lesProduits = getTousLesProduits();
        $titreCatalogue = "Nos produits frais";
        break;
    }
}

// Appel de la vue principale du catalogue
require "vues/v_catalogue.php";
?>