<?php
if (!isset($_REQUEST['action'])){
    $action = 'afficher';
}
else {
    $action = $_REQUEST['action'];
}

switch($action) {
    case "afficher": {
        require "vues/v_authentification.php";
        break;
    }

    case "connexion": {
        $login = $_REQUEST['email'];
        $mdp = $_REQUEST['mot_de_passe'];
        $utilisateur = verifier_identification($login, $mdp);
        if ($utilisateur == false) {
            require "vues/v_authentification.php";
        }
        else {
            $produitsEnAvant = produitsEnAvant();
            require "vues/v_accueil.php";
        }
        break;
    }
}