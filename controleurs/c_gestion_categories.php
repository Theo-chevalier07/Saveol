<?php
if (!isset($_REQUEST['action'])){
    $action = 'liste';
}
else {
    $action = $_REQUEST['action'];
}

$message = '';
$erreur = '';

switch($action) {
    case "ajouter": {
        $libelle = trim($_REQUEST['libelle']);
        if ($libelle == '') {
            $erreur = "Le nom de la catégorie est obligatoire.";
        }
        else {
            ajouterCategorie($libelle);
            $message = "La catégorie a été ajoutée.";
        }
        break;
    }

    case "modifier": {
        $libelle = trim($_REQUEST['libelle']);
        if ($libelle == '') {
            $erreur = "Le nom de la catégorie est obligatoire.";
        }
        else {
            modifierCategorie($_REQUEST['id_categorie'], $libelle);
            $message = "La catégorie a été modifiée.";
        }
        break;
    }

    case "supprimer": {
        // On demande une confirmation avant de supprimer
        $categorie = getCategorie($_REQUEST['id_categorie']);
        $titre = "Supprimer la catégorie « " . $categorie['libelle'] . " » ?";
        $lienOui = "index.php?uc=gestion_categories&action=confirmer_suppression&id_categorie=" . $categorie['id_categorie'];
        $lienNon = "index.php?uc=gestion_categories";
        require "vues/v_confirmation.php";
        exit;
    }

    case "confirmer_suppression": {
        $id = $_REQUEST['id_categorie'];
        // Une catégorie qui contient des produits doit rester
        foreach (getCategories() as $categorie) {
            if ($categorie['id_categorie'] == $id && $categorie['nb_produits'] > 0) {
                $erreur = "Cette catégorie contient des produits, elle ne peut pas être supprimée.";
            }
        }
        if ($erreur == '') {
            supprimerCategorie($id);
            $message = "La catégorie a été supprimée.";
        }
        break;
    }
}

$lesCategories = getCategories();
require "vues/v_gestion_categories.php";
?>
