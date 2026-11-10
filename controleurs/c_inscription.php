<?php
if (!isset($_REQUEST['action'])){
    $action = 'afficher';
}
else {
    $action = $_REQUEST['action'];
}

switch($action) {
    case "afficher": {
        require "vues/v_inscription.php";
        break;
    }

    case "inscription": {
        $nom = $_REQUEST['nom'];
        $prenom = $_REQUEST['prenom'];
        $email = $_REQUEST['email'];
        $mot_de_passe = $_REQUEST['mot_de_passe'];
        $confirmation_mot_de_passe = $_REQUEST['confirmation_mot_de_passe'];

        if ($mot_de_passe !== $confirmation_mot_de_passe) {
            $message = "Les mots de passe ne correspondent pas.";
            require "vues/v_inscription.php";
            break;
        }

        $utilisateur_existant = verifier_utilisateur_existant($email);
        if ($utilisateur_existant) {
            $message = "Un utilisateur avec cet email existe déjà.";
            require "vues/v_inscription.php";
            break;
        }

        creer_utilisateur($nom, $prenom, $email, $mot_de_passe);
        require "vues/v_authentification.php";
        break;
    }
}