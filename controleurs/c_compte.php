<?php
if (!isset($_REQUEST['action'])){
    $action = 'afficher';
}
else {
    $action = $_REQUEST['action'];
}

$message = '';
$erreur = '';

switch($action) {
    case "modifier": {
        $nom = trim($_REQUEST['nom']);
        $prenom = trim($_REQUEST['prenom']);
        $email = trim($_REQUEST['email']);
        $telephone = trim($_REQUEST['telephone']);
        $adresse = trim($_REQUEST['adresse']);

        // On vérifie les champs avant d'enregistrer
        if ($nom == '' || $prenom == '' || $email == '' || $telephone == '' || $adresse == '') {
            $erreur = "Tous les champs sont obligatoires.";
        }
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreur = "L'adresse email n'est pas valide.";
        }
        elseif (emailDejaUtilise($email, $_SESSION['id'])) {
            $erreur = "Cette adresse email est déjà utilisée.";
        }
        else {
            modifierUtilisateur($_SESSION['id'], $nom, $prenom, $email, $telephone, $adresse);
            $_SESSION['nom'] = $nom;
            $_SESSION['prenom'] = $prenom;
            $message = "Vos informations ont bien été modifiées.";
        }
        break;
    }

    case "detail": {
        $commande = getCommande($_REQUEST['id_commande']);
        // On vérifie que la commande appartient bien au client connecté
        if ($commande == false || $commande['id_utilisateur'] != $_SESSION['id']) {
            header("Location: index.php?uc=compte");
            exit;
        }
        $lesLignes = getLignesCommande($commande['id_commande']);
        require "vues/v_detail_commande.php";
        exit;
    }
}

$utilisateur = getUtilisateur($_SESSION['id']);
$lesCommandes = getCommandesClient($_SESSION['id']);
require "vues/v_compte.php";
?>
