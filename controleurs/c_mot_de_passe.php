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
        $ancien = $_REQUEST['ancien'];
        $nouveau = $_REQUEST['nouveau'];
        $confirmation = $_REQUEST['confirmation'];

        // On vérifie l'ancien mot de passe puis le nouveau
        if (!verifierMotDePasse($_SESSION['id'], $ancien)) {
            $erreur = "L'ancien mot de passe est incorrect.";
        }
        elseif (strlen($nouveau) < 8) {
            $erreur = "Le nouveau mot de passe doit faire au moins 8 caractères.";
        }
        elseif ($nouveau != $confirmation) {
            $erreur = "Les deux nouveaux mots de passe ne sont pas identiques.";
        }
        elseif ($nouveau == $ancien) {
            $erreur = "Le nouveau mot de passe doit être différent de l'ancien.";
        }
        else {
            modifierMotDePasse($_SESSION['id'], $nouveau);
            $_SESSION['mdp_a_changer'] = 0;
            $message = "Votre mot de passe a bien été modifié.";
        }
        break;
    }
}

require "vues/v_mot_de_passe.php";
?>
