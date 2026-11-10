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
        // Formulaire vide pour un nouveau produit
        $produit = array('id_produit' => '', 'nom' => '', 'description' => '', 'prix' => '', 'conditionnement' => '', 'image' => '', 'mis_en_avant' => 0, 'id_categorie' => '');
        $lesCategories = getCategories();
        require "vues/v_form_produit.php";
        exit;
    }

    case "modifier": {
        $produit = getProduit($_REQUEST['id_produit']);
        $lesCategories = getCategories();
        require "vues/v_form_produit.php";
        exit;
    }

    case "enregistrer": {
        $id = $_REQUEST['id_produit'];
        $nom = trim($_REQUEST['nom']);
        $description = trim($_REQUEST['description']);
        $prix = str_replace(',', '.', trim($_REQUEST['prix']));
        $conditionnement = trim($_REQUEST['conditionnement']);
        $idCategorie = $_REQUEST['id_categorie'];
        if (isset($_REQUEST['mis_en_avant'])) {
            $misEnAvant = 1;
        }
        else {
            $misEnAvant = 0;
        }

        // Prix vide = produit sur devis
        if ($prix == '') {
            $prix = null;
        }

        // On garde l'ancienne image si on n'en envoie pas de nouvelle
        $image = '';
        if ($id != '') {
            $ancienProduit = getProduit($id);
            $image = $ancienProduit['image'];
        }

        if ($nom == '' || $conditionnement == '' || $idCategorie == '') {
            $erreur = "Le nom, le conditionnement et la catégorie sont obligatoires.";
        }
        elseif ($prix != null && (!is_numeric($prix) || $prix < 0)) {
            $erreur = "Le prix doit être un nombre positif.";
        }
        elseif ($_FILES['image']['name'] != '') {
            // On vérifie l'image envoyée
            $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (!in_array($extension, array('jpg', 'jpeg', 'png', 'webp'))) {
                $erreur = "L'image doit être en jpg, jpeg, png ou webp.";
            }
            elseif ($_FILES['image']['size'] > 2000000) {
                $erreur = "L'image ne doit pas dépasser 2 Mo.";
            }
            else {
                $image = uniqid() . '.' . $extension;
                move_uploaded_file($_FILES['image']['tmp_name'], "images/produits/" . $image);
            }
        }

        if ($erreur == '' && $image == '') {
            $erreur = "Ajoutez une image pour le nouveau produit.";
        }

        if ($erreur != '') {
            // On réaffiche le formulaire avec ce qui a été tapé
            $produit = array('id_produit' => $id, 'nom' => $nom, 'description' => $description, 'prix' => $prix, 'conditionnement' => $conditionnement, 'image' => $image, 'mis_en_avant' => $misEnAvant, 'id_categorie' => $idCategorie);
            $lesCategories = getCategories();
            require "vues/v_form_produit.php";
            exit;
        }

        if ($id == '') {
            ajouterProduit($nom, $description, $prix, $conditionnement, $image, $misEnAvant, $idCategorie);
            $message = "Le produit a été ajouté.";
        }
        else {
            modifierProduit($id, $nom, $description, $prix, $conditionnement, $image, $misEnAvant, $idCategorie);
            $message = "Le produit a été modifié.";
        }
        break;
    }

    case "mise_en_avant": {
        changerMiseEnAvant($_REQUEST['id_produit']);
        $message = "La mise en avant a été modifiée.";
        break;
    }

    case "supprimer": {
        // On demande une confirmation avant de supprimer
        $produit = getProduit($_REQUEST['id_produit']);
        $titre = "Supprimer le produit « " . $produit['nom'] . " » ?";
        $lienOui = "index.php?uc=gestion_catalogue&action=confirmer_suppression&id_produit=" . $produit['id_produit'];
        $lienNon = "index.php?uc=gestion_catalogue";
        require "vues/v_confirmation.php";
        exit;
    }

    case "confirmer_suppression": {
        $produit = getProduit($_REQUEST['id_produit']);
        // Un produit déjà commandé doit rester en base
        if (produitDansUneCommande($produit['id_produit'])) {
            $erreur = "Ce produit est dans au moins une commande, il ne peut pas être supprimé.";
        }
        else {
            supprimerProduit($produit['id_produit']);
            if (file_exists("images/produits/" . $produit['image'])) {
                unlink("images/produits/" . $produit['image']);
            }
            $message = "Le produit a été supprimé.";
        }
        break;
    }
}

$lesProduits = getTousLesProduits();
require "vues/v_gestion_catalogue.php";
?>
