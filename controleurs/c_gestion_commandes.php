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
    case "liste": {
        if (isset($_REQUEST['statut'])) {
            $statutChoisi = $_REQUEST['statut'];
        }
        else {
            $statutChoisi = '';
        }
        $lesCommandes = getToutesLesCommandes($statutChoisi);
        require "vues/v_gestion_commandes.php";
        break;
    }

    case "statut": {
        $commande = getCommande($_REQUEST['id_commande']);
        $nouveauStatut = $_REQUEST['statut'];
        // On revérifie que le changement est autorisé
        if ($commande != false && in_array($nouveauStatut, getStatutsSuivants($commande['statut']))) {
            modifierStatutCommande($commande['id_commande'], $nouveauStatut);
            $message = "Le statut est passé à « " . $nouveauStatut . " ».";
        }
        else {
            $erreur = "Ce changement de statut n'est pas possible.";
        }
        // Pas de break : on réaffiche le détail de la commande
    }

    case "detail": {
        $commande = getCommande($_REQUEST['id_commande']);
        if ($commande == false) {
            header("Location: index.php?uc=gestion_commandes");
            exit;
        }
        $lesLignes = getLignesCommande($commande['id_commande']);
        $lesStatuts = getStatutsSuivants($commande['statut']);
        require "vues/v_detail_commande.php";
        break;
    }
}
?>
